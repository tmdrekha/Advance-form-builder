<?php
class ControllerExtensionModuleTmdFormBulider extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/tmdformbulider');
		$this->load->language('extension/tmd/form');
	   $this->load->model('extension/tmd/form');
		$this->load->model('tool/image');	
		
		
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		
		if (!empty($this->config->get('module_tmdformbuildermenu_status'))) {
			$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');	
		}else{
			$tmdformbuildermenu_status = '0';
		}


		if(!empty($tmdformbuildermenu_status)){

			if (!empty($setting['tmdformbulider_showproduct'])) {
				$forms_id = $setting['tmdformbulider_showproduct'];	
			}else{
				$forms_id = '';
			}
			
			$url='';	
			$data=array();
			$tmdforms_info = $this->model_extension_tmd_form->getForm($forms_id);	
			if(!empty($tmdforms_info)){	
				//$data['form_id'] = 0;
				if (!empty($this->request->get['form_id'])) {
					$data['form_id'] = $this->request->get['form_id'];
				} 
				
				if (isset($this->error['warning'])) {
					$data['error_warning'] = $this->error['warning'];
				} 

				if(isset($tmdforms_info['display_type'])){
					if($tmdforms_info['display_type']=='Only customer')
					{
						if (!$this->customer->isLogged()) {
							$this->session->data['redirect'] = $this->url->link('tmdform/form', 'form_id='.$forms_id, true);
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
				
				$data['heading_title'] 		= $this->language->get('heading_title');
				$data['text_yes'] 			= $this->language->get('text_yes');
				$data['text_no'] 			= $this->language->get('text_no');
				$data['text_select'] 		= $this->language->get('text_select');
				$data['text_none'] 			= $this->language->get('text_none');
				$data['text_loading'] 		= $this->language->get('text_loading');
				$data['text_option'] 		= $this->language->get('text_option');
				$data['button_upload'] = $this->language->get('button_upload');
				$data['text_entercode'] = $this->language->get('text_entercode');
				
				$this->load->model('localisation/country');

				$data['countries'] = $this->model_localisation_country->getCountries();
				
				if(isset($tmdforms_info['custome_style'])){
					$data['customcss'] = $tmdforms_info['custome_style'];	
				} else {
					$data['customcss'] = '';
				}

				if(!empty($tmdforms_info['price'])){
					$data['base_price'] = $tmdforms_info['price'];
					$data['b_price'] = $this->currency->format($tmdforms_info['price'], $this->session->data['currency']);
				} else {
					$data['base_price'] = '';
					$data['b_price'] =  '';	
				}

				if(isset($tmdforms_info['form_id'])){
					$data['form_id'] = $tmdforms_info['form_id'];
				} else {
					$data['form_id'] = '';
				}
			
				if(isset($tmdforms_info['title'])){
					$data['formtitle'] = $tmdforms_info['title'];
				} else {
					$data['formtitle'] = '';
				}

				$tmdformbuilder_title = $setting['tmdformbuilder_title'];	
			
				if(!empty($tmdformbuilder_title[$this->config->get('config_language_id')])){
		        	$data['formtitle']    = $tmdformbuilder_title[$this->config->get('config_language_id')];
			    } else {
			        $data['formtitle'] = $tmdforms_info['title'];
			    }
				
				$data['config_captchastatus'] = $this->config->get('config_captcha');

				if(isset($tmdforms_info['captcha'])){
					$data['captchastatus'] = $tmdforms_info['captcha'];
				} else {
					$data['captchastatus']='';
				}

				
				if ($this->config->get('config_captcha')) {
					
				if ($tmdforms_info['captcha']==1) {
					// Captcha
					if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
						$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
				}
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

				$data['resetbutton'] = $tmdforms_info['resetbutton'];	

				if(!empty($tmdforms_info['reset_txtcolor'])){
					$data['reset_txtcolor'] = $tmdforms_info['reset_txtcolor'];
				} else {
					$data['reset_txtcolor'] =  '';	
				}

				if(!empty($tmdforms_info['resetbuttonname'])){
					$data['resetbutton_name'] = $tmdforms_info['resetbuttonname'];
				} else {
					$data['resetbutton_name'] =  $this->language->get('button_reset');	
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
				if (!empty($tmdforms_info['button_name'])) {
				$data['button_name'] 	    = $tmdforms_info['button_name'];
				}else{
					$data['button_name'] 	    = $this->language->get('button_name');	
				}
		
				
				$data['form_fields'] = array();	
				
				if (!empty($tmdforms_info['form_id'])) {
					$form_id = $tmdforms_info['form_id'];
				}else{
					$form_id = '';
				}
				foreach($this->model_extension_tmd_form->getFormFieldById($form_id) as $option) {
						
						$form_field_option_data = array();

						foreach ($option['form_field_option'] as $option_value) {
							

								$form_field_option_data[] = array(
									'field_id' => $option_value['field_id'],
									'form_id'         => $option_value['form_id'],
									'name'                    => $option_value['name'],
									'price_prefix'  => $option_value['price_prefix'],
									'price'     => $this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']),
									'tmdprice'  => $this->currency->format($this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']), $this->session->data['currency']),

									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),						
									'sort_order'                    => $option_value['sort_order']
								);
							}
						
				$option_value = date('Y-m-d H:i');
				$value_date = date('Y-m-d');
				$value_time = date('H:i');		
					
						$data['form_fields'][] = array(
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
							'value_time'        		=> $value_time
						);
					}
				
				if (isset($this->error['form_fields'])) {
					 $data['error_message'] = $this->error['form_fields'];
				} else {
					$data['error_message'] ='';
				}
				
				$producturls = $this->url->link('extension/tmd/success','form_success=' . $form_id);
				
				$data['producturl'] =str_replace('&amp;','&',$producturls);
				
				if (isset($this->error['captcha'])) {
					$data['error_captcha'] = $this->error['captcha'];
				} else {
					$data['error_captcha'] = '';
				}
				
				if ($this->config->get('config_google_captcha_public')) {

					if ($tmdforms_info['captcha']==1) {
						
						$data['site_key'] = $this->config->get('config_google_captcha_public');
					}
				} else {
					$data['site_key'] = '';
				}
			}

			if (!empty($form_id)) {
				return $this->load->view('extension/module/tmdformbulider', $data);
			}else{
				return '';
			}
			
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
