<?php
class ControllerExtensionTMDPopupForm extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/tmd/popupform');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		
	}
	
	public function popupformpage() {
		
		$this->load->language('extension/tmd/popupform');
		$this->load->language('extension/tmd/form');
		$this->load->model('extension/tmd/form');
		$this->load->model('tool/image');	
		$this->load->model('catalog/product');	

		
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = 0;
		}
		
		if (isset($this->request->get['product_id'])) {
			$data['productform_id'] = $this->request->get['product_id'];
		} else {
			$data['productform_id'] = 0;
		}

		if (isset($this->request->get['form_id'])) {
			$data['form_id'] = $this->request->get['form_id'];
			$form_id = $this->request->get['form_id'];
		} else {
			$data['form_id'] = 0;
			$form_id = 0;
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	
		$data['heading_title'] 		= $this->language->get('heading_title');
		$data['text_yes'] 			= $this->language->get('text_yes');
		$data['text_no'] 			= $this->language->get('text_no');
		$data['text_select'] 		= $this->language->get('text_select');
		$data['text_none'] 			= $this->language->get('text_none');
		$data['text_loading'] 		= $this->language->get('text_loading');
		$data['text_option'] 		= $this->language->get('text_option');
		$data['button_upload']  = $this->language->get('button_upload');
		$data['text_entercode'] = $this->language->get('text_entercode');
	
	
		$url='';	
		$data=array();
		$tmdforms_info = $this->model_extension_tmd_form->getForm($form_id);
		if(!empty($tmdforms_info)){
			if($tmdforms_info['display_type']=='Only customer') { 
				if (!$this->customer->isLogged()) {
					$this->session->data['redirect'] = $this->url->link('extension/tmd/form', 'form_id='.$forms_id, true);
					$this->response->redirect($this->url->link('account/login', '', true));
				}
			}

			if($tmdforms_info['display_type']=='Only guest') {
				if ($this->customer->isLogged()) {
					$this->session->data['redirect'] = $this->url->link('extension/tmd/form', 'form_id='.$forms_id, true);
				}
			}


			$data['resetbutton'] = $tmdforms_info['resetbutton'];	
			
			$data['customcss'] = $tmdforms_info['custome_style'];	
			$data['form_id'] = $tmdforms_info['form_id'];
			$data['title'] 	 = $this->document->setTitle($tmdforms_info['title']);
			$data['formtitle'] = $tmdforms_info['title'];
			
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
			
			if(!empty($tmdforms_info['resetbuttonname'])){
			$data['resetbutton_name'] = $tmdforms_info['resetbuttonname'];
			} else {
			$data['resetbutton_name'] =  $this->language->get('button_reset');	
			}
			
			if(!empty($tmdforms_info['button_name'])){
			$data['button_name'] = $tmdforms_info['button_name'];
			} else {
			$data['button_name'] =  $this->language->get('button_name');	
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

			if(!empty($tmdforms_info['reset_txtcolor'])){
				$data['reset_txtcolor'] = $tmdforms_info['reset_txtcolor'];
			} else {
				$data['reset_txtcolor'] =  '';	
			}

			/// new code 27 march 2020 //	
			if(!empty($tmdforms_info['price'])){
				$data['base_price'] = $tmdforms_info['price'];
				$data['b_price'] = $this->currency->format($tmdforms_info['price'], $this->session->data['currency']);
			} else {
				$data['base_price'] = '';
				$data['b_price'] =  '';	
			}

			
			if (isset($tmdforms_info['product_id'])) {
				$data['productform_id'] = $tmdforms_info['product_id'];
			} else {
				$data['productform_id'] = 0;
			}
			/// new code 27 march 2020 //

			
			$data['form_fields'] = array();	
			
			foreach($this->model_extension_tmd_form->getFormFieldById($tmdforms_info['form_id']) as $option) {
				if($option['form_status'] == 1){	
					$form_field_option_data = array();

					foreach ($option['form_field_option'] as $option_value) {
						

							$form_field_option_data[] = array(
								'field_id' 		=> $option_value['field_id'],
								'form_id'       => $option_value['form_id'],
								'name'          => $option_value['name'],
								'price_prefix'  => $option_value['price_prefix'],
								'image'         => $this->model_tool_image->resize($option_value['image'], 50, 50),		
								'sort_order'    => $option_value['sort_order'],
								'price'         => $this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']),
								'tmdprice'      => $this->currency->format($this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']), $this->session->data['currency']),
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
						'value_time'        		=> $value_time,
					);
				}
			}


			if (isset($this->error['form_fields'])) {
				 $data['error_message'] = $this->error['form_fields'];
			} else {
				$data['error_message'] ='';
			}
			
			if(isset($this->request->get['productform_id'])){
				$data['productform_id'] = $this->request->get['productform_id'];
			} else {
				$data['productform_id'] = 0;
			}

			// product name and image code 
			if(!empty($data['productform_id'])){
				$producturls = $this->url->link('product/product&productform_id='.$data['productform_id']);
				$data['producturl'] =str_replace('&amp;','&',$producturls);
			}
			

			$this->load->model('extension/tmd/form');
			$tmdsuccessform_info = $this->model_extension_tmd_form->getSuccesstext($tmdforms_info['form_id']);

			if(isset($tmdsuccessform_info['success_description'])){
				$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
			} else {
				$data['text_message'] = $this->language->get('text_message');
			}
			// product name and image code 

			$product_info = $this->model_catalog_product->getProduct($data['productform_id']);
			
			if (isset($product_info['image'])) {
				
				if(!empty($tmdforms_info['productwidth'])){
				$productwidth = $tmdforms_info['productwidth'];
				} else {
				$productwidth = 100;	
				}
				if(!empty($tmdforms_info['productheight'])){
				$productheight = $tmdforms_info['productheight'];
				} else {
				$productheight = 100;	
				}
				
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'],$productwidth, $productheight);
				} else {
				$data['thumb'] = '';
					
			}
			$data['productname'] ='';
			
			if(isset($product_info['name'])){
				$data['productname'] = $product_info['name'];
			}

			$data['config_captchastatus'] = $this->config->get('config_captcha');

			if(!empty($tmdforms_info['captcha'])){
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

			$this->load->model('localisation/country');

		    $data['countries'] = $this->model_localisation_country->getCountries();

			$this->response->setOutput($this->load->view('extension/tmd/popupform', $data));
		}
		
	}

	/// new code 27 march 2020 //
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
	
	public function loadprice() {
	    $json = [];

	    if (isset($this->request->post['price']) && is_numeric($this->request->post['price'])) {
	        $price = (float)$this->request->post['price']; // Ensure it's a float
	        $json['price'] = $this->currency->format($price, $this->session->data['currency']);
	    }
	    
	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
	}

	public function popupformloadprice() {
	    $json = [];

	    if (isset($this->request->post['price']) && is_numeric($this->request->post['price'])) {
	        $price = (float)$this->request->post['price']; // Ensure it's a float
	        $json['price'] = $this->currency->format($price, $this->session->data['currency']);
	    }
	    
	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
	}

	/// new code 27 march 2020 //
	
	
	public function addpoup() {
		
		$this->load->language('extension/tmd/form');

		$json = array();

		if (isset($this->request->post['form_id'])) {
			$form_id = (int)$this->request->post['form_id'];
		} else {
			$form_id = 0;
		}
		if (isset($this->request->get['productform_id'])) {
			$productform_id = (int)$this->request->get['productform_id'];
		} else {
			$productform_id = 0;
		}

		$this->load->model('extension/tmd/form');
		$tmdforms_info = $this->model_extension_tmd_form->getForm($this->request->post['form_id']);	
		
		$form_info = $this->model_extension_tmd_form->getFormFieldById($form_id);

		if (!empty($form_info)) {			
			
			$form_options = $this->model_extension_tmd_form->getFormFieldById($this->request->post['form_id']);
			
			foreach ($form_options as $form_option) {					
				// Email
			if($form_option['form_status'] == 1){
				if($form_option['type']=='email'){
					if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
					$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
					}
				}
				
				// Exist Email
				
				if($form_option['type']=='emaile_exists'){
					
					if ($this->model_extension_tmd_form->getEmailExist($this->request->post['formfields'][$form_option['field_id']],$form_option['field_id'])) {
					$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_exists');
					}
					if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
					$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
					}
					
				}
				
				// Password

				if ($form_option['type'] == 'password') {
					if (empty($this->request->post['formfields'][$form_option['field_id']])) {
						$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_password');
					}
				}
				// Confirm Password

				if ($form_option['type'] == 'confirm') {
					if (empty($this->request->post['formfields'][$form_option['field_id']])) {
						$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_confirm');
					}
				}
				
				
				if ($form_option['required'] && empty($this->request->post['formfields'][$form_option['field_id']])) {
					$json['error']['formfields'][$form_option['field_id']] = sprintf($form_option['error_message'], $form_option['field_name']);
				}
			}
		}
			
     	if ($tmdforms_info['captcha']==1) {
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
				if ($captcha) {
					$json['error']['g-recaptcha-response'] = $captcha;
				}
		    }
		}
				
		if ($json) {
			$json['warning'] = $this->language->get('text_warrning');
		}
		
			if (!$json) {

				if (isset($this->request->get['productform_id'])) {
					$productform_id = (int)$this->request->get['productform_id'];
				} else {
					$productform_id = 0;
				}


				if (isset($this->request->get['baseprice'])) {
				    $baseprice = (int)$this->request->get['baseprice'];
				    
				    // Add baseprice to the post data
				    $this->request->post['baseprice'] = $baseprice;
				}

				$this->model_extension_tmd_form->addForm($this->request->post ,$productform_id );
				
				$producturls = $this->url->link('extension/tmd/success','form_success=' . $tmdforms_info['form_id']);

				// product name and image code 
				$this->load->model('extension/tmd/form');
				$tmdsuccessform_info = $this->model_extension_tmd_form->getSuccesstext($tmdforms_info['form_id']);
				
				if(!empty($tmdsuccessform_info['success_description'])){
					$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
				} else {
					$data['text_message'] = $this->language->get('text_success');
				}

				$json['producturl'] =str_replace('&amp;','&',$producturls);
				$json['success'] =$data['text_message'];
				// product name and image code 
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function add() {
		
		$this->load->language('extension/tmd/form');

		$json = array();

		if (isset($this->request->post['form_id'])) {
			$form_id = (int)$this->request->post['form_id'];
		} else {
			$form_id = 0;
		}

		$this->load->model('extension/tmd/form');
		$tmdforms_info = $this->model_extension_tmd_form->getForm($form_id);	
		
		$form_info = $this->model_extension_tmd_form->getFormFieldById($form_id);

		if ($form_info) {			
			
			$form_options = $this->model_extension_tmd_form->getFormFieldById($form_id);
			
			foreach ($form_options as $form_option) {					
				// Email
				if($form_option['form_status'] == 1){
					if($form_option['type']=='email'){
						if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
						$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
						}
					}
					
					// Exist Email
					
					if($form_option['type']=='emaile_exists'){
						
						if ($this->model_extension_tmd_form->getEmailExist($this->request->post['formfields'][$form_option['field_id']],$form_option['field_id'])) {
						$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_exists');
						}
						if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
						$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
						}
						
					}
					
					// Password

					if ($form_option['type'] == 'password') {
						if (empty($this->request->post['formfields'][$form_option['field_id']])) {
							$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_password');
						}
					}
					// Confirm Password

					if ($form_option['type'] == 'confirm') {
						if (empty($this->request->post['formfields'][$form_option['field_id']])) {
							$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_confirm');
						}
					}
					
					
					if ($form_option['required'] && empty($this->request->post['formfields'][$form_option['field_id']])) {
						$json['error']['formfields'][$form_option['field_id']] = sprintf($form_option['error_message'], $form_option['field_name']);
					}
				}
			
			}
			
		
		if ($tmdforms_info['captcha']==1) {
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
				if ($captcha) {
					$json['error']['g-recaptcha-response'] = $captcha;
				}
		    }
		}
	
			if ($json) {
				$json['warning'] = $this->language->get('text_warrning');
			}
			
			if (!$json) {

				if (isset($this->request->get['productform_id'])) {
					$productform_id = (int)$this->request->get['productform_id'];
				} else {
					$productform_id = 0;
				}

				if (isset($this->request->get['baseprice'])) {
				    $baseprice = (int)$this->request->get['baseprice'];
				    
				    // Add baseprice to the post data
				    $this->request->post['baseprice'] = $baseprice;
				}

				$this->model_extension_tmd_form->addForm($this->request->post, $productform_id);

				
				$producturls = $this->url->link('extension/tmd/success','form_success=' . $tmdforms_info['form_id']);

				$this->load->model('extension/tmd/form');
				$tmdsuccessform_info = $this->model_extension_tmd_form->getSuccesstext($tmdforms_info['form_id']);
					
				if(!empty($tmdsuccessform_info['success_description'])){
					$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
				} else {
					$data['text_message'] = $this->language->get('text_success');
				}
			
				$json['producturl'] =str_replace('&amp;','&',$producturls);
				$json['success'] = $data['text_message'];
		
			}

		}		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}