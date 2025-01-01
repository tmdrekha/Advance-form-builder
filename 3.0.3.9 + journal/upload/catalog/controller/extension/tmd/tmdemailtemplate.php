<?php
class ControllerExtensionTMDTmdEmailTemplate extends Controller {
	
	public function index() {
		$this->load->language('extension/tmd/tmdemailtemplate');
		$this->load->model('extension/tmd/form');
		$this->load->model('localisation/language');
		
		if (isset($this->session->data['form_id'])) {
			$forms_id = $this->session->data['form_id'];
		} else {
			$forms_id = 0;
		} 

		if (!empty($this->config->get('module_tmdformbuildermenu_status'))) {
			$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');	
		}else{
			$tmdformbuildermenu_status = '0';
		}

		if(!empty($tmdformbuildermenu_status)){
				
			$data['text_detail'] = $this->language->get('text_detail');
			$data['text_custdetail'] = $this->language->get('text_custdetail');
			$data['text_fields'] = $this->language->get('text_fields');
			$data['entry_fieldname'] = $this->language->get('entry_fieldname');
			$data['entry_fieldvalue'] = $this->language->get('entry_fieldvalue');

			$tmdrecord_info = $this->model_extension_tmd_form->getTmdRecord($forms_id);
			if(isset($this->request->get['productform_id'])){
				$productform_id = $this->request->get['productform_id'];
			} else {
				$productform_id = 0;
			}

			if(!empty($tmdrecord_info['form_id'])) {
				$form_id = $tmdrecord_info['form_id'];
			} else {
				$form_id = 0;
			}

			if(!empty($tmdrecord_info['fs_id'])) {
				$fs_id = $tmdrecord_info['fs_id'];
			} else {
				$fs_id = 0;
			}


			// product name and image code 
			if(!empty($productform_id)){
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($productform_id);
				$this->load->model('tool/image');
				if (!empty($product_info['image'])) {
					$data['image'] =  $this->model_tool_image->resize($product_info['image'], 40, 40);
				} else {
					$data['image'] = '';
				}

				$data['thumb'] = str_replace(' ', '%20', $data['image']);

				if (!empty($product_info['name'])) {
					$data['pro_name'] = $product_info['name'];
				} else {
					$data['pro_name'] = '';
				}
			} 
			// product name and image code 
			
			if(isset($tmdrecord_info['language_id'])){
				$language_info = $this->model_localisation_language->getLanguage($tmdrecord_info['language_id']);
			}
				
			if(isset($language_info['name'])){
				$lname = $language_info['name'];
			} else {
				$lname ='';
			}

			$store_info = $this->model_extension_tmd_form->getStore($tmdrecord_info['store_id']);
			
			if(isset($store_info['name'])){
				$sname = $store_info['name'];
			} else {
				$sname ='Default';
			}
			
			$data['sname'] 		= $sname;
			$data['lname'] 		= $lname;
			$data['customer_id']= $tmdrecord_info['customer_id'];
			if(!empty($tmdrecord_info['customer_id'])) {
				$data['customer_name']= $tmdrecord_info['customer_name'];
			} else {
				$data['customer_name'] = 'Guest';
			}
			$data['title'] 		= $tmdrecord_info['title'];
			$data['ip'] 		= $tmdrecord_info['ip'];
			$data['user_agent'] = (substr($tmdrecord_info['user_agent'], 0, 45));
			$data['date_added'] = $tmdrecord_info['date_added'];
					
			$data['field_infos'] = array();
			$filter_data=array(
				'fs_id'=>$tmdrecord_info['fs_id']
			);
				
			$field_infos = $this->model_extension_tmd_form->getTmdFieldRecord($filter_data);
			
			$this->session->data['emailfiles']=array();
			foreach($field_infos as $fieldinfo){			
				if($fieldinfo['serialize']){
					$fieldinfos=unserialize($fieldinfo['value']);			
							
					$value='';
					foreach($fieldinfos as $field){
					
					
						$value .=$field.',';
					}
					$fieldinfo['value']=$value;
				}
			
				$this->load->model('tool/upload');
				$type = $this->model_extension_tmd_form->getfieldtype($fieldinfo['field_id']);
				
				if($type=='file') {
					$upload_info = $this->model_tool_upload->getUploadByCode($fieldinfo['value']);
					if(isset($upload_info['name']))
					{
					$fieldinfo['value']=$upload_info['name'];
					copy(DIR_UPLOAD.$upload_info['filename'], DIR_UPLOAD.$upload_info['name']);
					$this->session->data['emailfiles'][]=DIR_UPLOAD.$upload_info['name'];
					}
				}


				$country_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='country' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;

				if(!empty($country_id)) {
					$this->load->model('localisation/country');
					$country_info = $this->model_localisation_country->getCountry($country_id['value']);
					if(!empty($country_info['name'])) {
						$fieldinfo['value'] = $country_info['name'];
					}
				}

				$zone_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='zone' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;
				if(!empty($zone_id)) {
					$this->load->model('localisation/zone');
					$zone_info = $this->model_localisation_zone->getZone($zone_id['value']);
					if(!empty($zone_info['name'])) {
						$fieldinfo['value'] = $zone_info['name'];
					}
				}
				
				
				$data['field_infos'][] = array(				
					'label'     =>$fieldinfo['label'],
					'value'     =>$fieldinfo['value']				
				);
				
			}
			
			return $this->load->view('extension/tmd/tmdemailtemplate', $data);
	    }
	}
	
}
