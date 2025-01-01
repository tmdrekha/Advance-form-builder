<?php
namespace Opencart\Catalog\Controller\Extension\TmdAdvanceFormbuilder\Module;
class Tmdformbuilder extends \Opencart\System\Engine\Controller {
	public function index(array $setting): string {
		$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuilder');
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');
	    $this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('tool/image');	

		$data['VERSION']=VERSION;

		$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');

		if(!empty($tmdformbuildermenu_status)){
			$forms_id =0;
			if (!empty($setting['tmdformbulider_showproduct'])) {	
				$forms_id = $setting['tmdformbulider_showproduct'];	
			}

			$tmdformbuilder_title = $setting['tmdformbuilder_title'];	
			
			if(!empty($tmdformbuilder_title[$this->config->get('config_language_id')])){
	        	$data['formtitle']    = $tmdformbuilder_title[$this->config->get('config_language_id')];
		    } else {
		        $data['formtitle'] = $this->language->get('tmdformbuilder_title');
		    }

			$url='';	

			$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($forms_id);	

			if (isset($this->request->get['form_id'])) {
				$data['form_id'] = $this->request->get['form_id'];
			} else {
				$data['form_id'] = 0;
			}
			
			$data['language'] = $this->config->get('config_language_id');


			if(isset($tmdforms_info['display_type'])){
				if($tmdforms_info['display_type']=='Only customer')
				{
					if (!$this->customer->isLogged()) {
						$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language') . 'form_id='.$forms_id, true);
						$this->response->redirect($this->url->link('account/login', '', true));
					}
				}
				if($tmdforms_info['display_type']=='Only guest')
				{
					if ($this->customer->isLogged()) {
						$this->response->redirect($this->url->link('common/home', '', true));
					}
				}	
			}	
		
			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();
			
			if(isset($tmdforms_info['custome_style'])){
				$data['customcss'] = $tmdforms_info['custome_style'];	
			} else {
				$data['customcss'] = '';
			}

			if(isset($tmdforms_info['form_id'])){
				$data['form_id'] = $tmdforms_info['form_id'];
			} else {
				$data['form_id'] = '';
			}
				
			if(isset($tmdforms_info['captcha'])){
				$data['captchastatus'] = $tmdforms_info['captcha'];
			} else {
				$data['captchastatus']='';
			}


			// image code

			if (!empty($this->request->get['language_id'])) {
			    $language_id = (int)$this->request->get['language_id'];  
			} else {
			    $language_id = (int)$this->config->get('config_language_id');
			}

			$query = $this->db->query("SELECT `code` FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

			if ($query->num_rows) {
			    $data['language_code'] = $query->row['code'];  
			} else {
			    $data['language_code'] = $this->config->get('config_language');
			}
			// image code
			
			if (isset($tmdforms_info['captcha'])==1) {
			
			// Captcha
				$this->load->model('setting/extension');

				$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

				if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
					$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
				} else {
					$data['captcha'] = '';
				}
			}


			if(!empty($tmdforms_info['submit_bgcolor'])){
				$data['submit_bgcolor'] = $tmdforms_info['submit_bgcolor'];
			} else {
				$data['submit_bgcolor'] =  '';	
			}


			if(!empty($tmdforms_info['submit_txtcolor'])){
				$data['submit_txtcolor'] = $tmdforms_info['submit_txtcolor'];
			} else {
				$data['submit_txtcolor'] =  '';	
			}
			
			if(!empty($tmdforms_info['reset_bgcolor'])){
				$data['reset_bgcolor'] = $tmdforms_info['reset_bgcolor'];
			} else {
				$data['reset_bgcolor'] =  '';	
			}

			if(!empty($tmdforms_info['resetbutton'])){
				$data['resetbutton'] = $tmdforms_info['resetbutton'];
			} else {
				$data['resetbutton'] =  '';	
			}

			if(!empty($tmdforms_info['resetbuttonname'])){
				$data['resetbutton_name'] = $tmdforms_info['resetbuttonname'];
			} else {
				$data['resetbutton_name'] =  $this->language->get('button_reset');	
			}

			if (!empty($tmdforms_info['uplaodfilebtn'])) {
				$data['uplaodfilebtn'] = $tmdforms_info['uplaodfilebtn'];
			} else {
				$data['uplaodfilebtn'] = $this->language->get('button_uplaodfilebtn');
			}

			if(!empty($tmdforms_info['price'])){
				$data['base_price'] = $tmdforms_info['price'];
				$data['b_price'] = $this->currency->format($tmdforms_info['price'], $this->session->data['currency']);
			} else {
				$data['base_price'] = '';
				$data['b_price'] =  '';	
			}
			
			if (isset($tmdforms_info['product_id'])) {
				$data['form_product_id'] = $tmdforms_info['product_id'];
			} else {
				$data['form_product_id'] = 0;
			}


			if(!empty($tmdforms_info['reset_txtcolor'])){
				$data['reset_txtcolor'] = $tmdforms_info['reset_txtcolor'];
			} else {
				$data['reset_txtcolor'] =  '';	
			}

			
			if(!empty($tmdforms_info['top_description'])){
			$data['top_description']    = html_entity_decode($tmdforms_info['top_description']);
			} else {
			$data['top_description']='';
			}
			if(!empty($tmdforms_info['bottom_description'])){
			$data['bottom_description']    = html_entity_decode($tmdforms_info['bottom_description']);
			} else {
			$data['bottom_description']='';	
			}

			if(!empty($tmdforms_info['button_name'])){
				$data['button_name'] = $tmdforms_info['button_name'];
			} else {
				$data['button_name'] =  $this->language->get('button_name');	
			}

			$form_id 	    = !empty($tmdforms_info['form_id'])?$tmdforms_info['form_id']:0;
			
			$data['form_fields'] = [];	
			
			foreach($this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($form_id) as $option) {

				$form_field_option_data = [];

				foreach ($option['form_field_option'] as $option_value) {
					$form_field_option_data[] = [
						'field_id' => $option_value['field_id'],
						'form_id'  => $option_value['form_id'],
						'name'     => $option_value['name'],
						'image'    => $this->model_tool_image->resize($option_value['image'], 50, 50),						
						'sort_order'=> $option_value['sort_order'],
						'price'     => $this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']),
						'tmdprice'  => $this->currency->format($this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']), $this->session->data['currency']),
					];
				}


				$option_value = date('Y-m-d H:i');
				$value_date = date('Y-m-d');
				$value_time = date('H:i');
			
				$data['form_fields'][] = [
					'field_id'          => $option['field_id'],
					'form_id'           => $option['form_id'],
					'form_field_option' => $form_field_option_data,
					'field_name'        => $option['field_name'],				
					'type'              => $option['type'],
					'required'          => $option['required'],
					'form_status'       => $option['form_status'],
					'help_text'         => $option['help_text'],
					'placeholder'       => $option['placeholder'],
					'placeholder'       => $option['placeholder'],
					'error_message'     => $option['error_message'],
					'value'             => $option_value,
					'value_date'        => $value_date,
					'value_time'        		=> $value_time,
				];
			}
			
			$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') . '&form_success=' . $form_id);
			
			$data['producturl'] =str_replace('&amp;','&',$producturls);
			
			if (!empty($form_id)) {
				return $this->load->view('extension/tmdadvanceformbuilder/module/tmdformbuilder', $data);
			} else {
				return '';
			}	
		}else{
			return '';
		}

	}

	public function calculatePrice($prefix='',$price=0,$base_price=0) {
		if ($prefix == '+') {
			return '+'.$price;
		}

		if ($prefix == '-') {
			return '-'.$price;
		}
		
		if ($prefix == '+%') {
			return '+'.($base_price*$price)/100;
		}

		if ($prefix == '-%') {
			return '-'.($base_price*$price)/100;
		}
	}
	
}
