<?php
namespace Opencart\Catalog\Controller\Extension\TmdAdvanceFormbuilder\Tmd;
class Popupform extends \Opencart\System\Engine\Controller {
	public function index():void {
		$this->load->language('extension/tmdadvanceformbuilder/tmd/popupform');
		$this->document->setTitle($this->language->get('heading_title'));

		if(!empty($this->request->get['language'])) {
			$data['language_id'] = $this->request->get['language'];
		}else{
			$data['language_id'] = $this->config->get('config_language_id');
		}

	}

	public function popupformpage():void {

		$this->load->language('extension/tmdadvanceformbuilder/tmd/popupform');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$data['VERSION']=VERSION;
		if (isset($this->request->get['productform_id'])) {
			$data['productform_id'] = $this->request->get['productform_id'];
		} else {
			$data['productform_id'] = 0;
		}
		
		if (isset($this->request->get['form_id'])) {
			$data['form_id'] = $this->request->get['form_id'];
			$form_id         = $this->request->get['form_id'];
		} else {
			$data['form_id'] = 0;
			$form_id         = 0;
		}

		$data['heading_title']  = $this->language->get('heading_title');
		$data['text_yes']       = $this->language->get('text_yes');
		$data['text_no']        = $this->language->get('text_no');
		$data['text_select']    = $this->language->get('text_select');
		$data['text_none']      = $this->language->get('text_none');
		$data['text_loading']   = $this->language->get('text_loading');
		$data['text_option']    = $this->language->get('text_option');
		$data['button_upload']  = $this->language->get('button_upload');
		$data['text_entercode'] = $this->language->get('text_entercode');

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

	
		if(!empty($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		}else{
			$language_id = $this->config->get('config_language_id');
		}

		if(!empty($this->request->get['language_id'])) {
			$data['language'] = $this->request->get['language_id'];
		}else{
			$data['language'] = $this->config->get('config_language_id');
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

		$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getPopupForm($form_id,$data['language']);



		// $tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getPopupForm($form_id,$language_id);

		if (!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type'] == 'Only customer') {
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language') . 'form_id='.$forms_id, true);
				$this->response->redirect($this->url->link('account/login', '', true));
			}
		}

		if (!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type'] == 'Only guest') {
			if ($this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language') . 'form_id='.$forms_id, true);
			}
		}

		$data['resetbutton'] = isset($tmdforms_info['resetbutton'])?$tmdforms_info['resetbutton']:'';

		$data['customcss'] = isset($tmdforms_info['custome_style'])?$tmdforms_info['custome_style']:'';
		$data['form_id']   = isset($tmdforms_info['form_id'])?$tmdforms_info['form_id']:'';

		if (!empty($tmdforms_info['title'])) {
			$data['title'] = $this->document->setTitle($tmdforms_info['title']);
		} else {
			$data['title'] = '';
		}

		$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') .'&form_success=' . $form_id);
				
		$data['producturl'] =str_replace('&amp;','&',$producturls);

		if (!empty($tmdforms_info['title'])) {
			$data['formtitle'] = $tmdforms_info['title'];
		} else {
			$data['formtitle'] = '';
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

		if (!empty($tmdforms_info['top_description'])) {
			$data['top_description'] = html_entity_decode($tmdforms_info['top_description']);
		} else {
			$data['top_description'] = '';
		}
		
		if (!empty($tmdforms_info['bottom_description'])) {
			$data['bottom_description'] = html_entity_decode($tmdforms_info['bottom_description']);
		} else {
			$data['bottom_description'] = '';
		}

		if (!empty($tmdforms_info['resetbuttonname'])) {
			$data['resetbutton_name'] = $tmdforms_info['resetbuttonname'];
		} else {
			$data['resetbutton_name'] = $this->language->get('button_reset');
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
		

		if (!empty($tmdforms_info['button_name'])) {
			$data['button_name'] = $tmdforms_info['button_name'];
		} else {
			$data['button_name'] = $this->language->get('button_name');
		}


		$data['form_fields'] = [];

		$form_id = !empty($tmdforms_info['form_id']) ? $tmdforms_info['form_id'] : 0;

		foreach ($this->model_extension_tmdadvanceformbuilder_tmd_form->getPopupFormFieldById($form_id, $language_id) as $option) {
			if ($option['form_status'] == 1) {
				$form_field_option_data = [];

				foreach ($option['form_field_option'] as $option_value) {

					$form_field_option_data[] = [
						'field_id'   => $option_value['field_id'],
						'form_id'    => $option_value['form_id'],
						'name'       => $option_value['name'],
						'image'      => $this->model_tool_image->resize($option_value['image'], 50, 50),
										
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
		}

		
		if(!empty($data['productform_id'])){
			$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') . '&productform_id=' . $data['productform_id']);
			$data['producturl'] = str_replace('&amp;', '&', $producturls);
		}
		$tmdsuccessform_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSuccesstext(!empty($tmdforms_info['form_id']));
		
			if(isset($tmdsuccessform_info['success_description'])){
				$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
			} else {
				$data['text_message'] = $this->language->get('text_message');
			}
			// product name and image code 

			if (isset($this->request->get['productform_id'])) {
				$productform_id = $this->request->get['productform_id'];
			} else {
				$productform_id = 0;
			}

			$product_info = $this->model_catalog_product->getProduct($productform_id);

		if (isset($product_info['image'])) {
			
			if (!empty($tmdforms_info['productwidth'])) {
				$productwidth = $tmdforms_info['productwidth'];
			} else {
				$productwidth = 100;
			}
			if (!empty($tmdforms_info['productheight'])) {
				$productheight = $tmdforms_info['productheight'];
			} else {
				$productheight = 100;
			}

			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $productwidth, $productheight);
		} else {
			$data['thumb'] = '';
			
		}

		$data['productname'] = '';
		if (isset($product_info['name'])) {
			$data['productname'] = $product_info['name'];
		}

		if (isset($tmdforms_info['captcha'])) {
			$data['captchastatus'] = $tmdforms_info['captcha'];
		} else {
			$data['captchastatus'] = '';
		}

		// Captcha

		$this->load->model('setting/extension');

		if (isset($tmdforms_info['captcha'])==1) {

			$this->load->model('setting/extension');
			$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

			if ($extension_info && $this->config->get('captcha_'.$this->config->get('config_captcha').'_status') && in_array('contact', (array) $this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/'.$extension_info['extension'].'/captcha/'.$extension_info['code']);
			} else {
				$data['captcha'] = '';
			}
		}

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/popupform', $data));

	}

	public function loadprice() {
	    $json = []; 

	    if (isset($this->request->post['price'])) {
	        $json['price'] = $this->currency->format($this->request->post['price'], $this->session->data['currency']);
	    } 

	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
	}
	
	public function popupformloadprice() {
	    $json = []; 

	    if (isset($this->request->post['price'])) {
	        $json['price'] = $this->currency->format($this->request->post['price'], $this->session->data['currency']);
	    } 

	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
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


	public function addpoup():void {

		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$json = [];

		if (!$json) {

			if (isset($this->request->post['form_id'])) {
				$form_id = (int) $this->request->post['form_id'];
			} else {
				$form_id = 0;
			}

			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment-with-locales.min.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/daterangepicker.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/daterangepicker.css');

			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
			$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($this->request->post['form_id']);

			$form_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($form_id);

			if ($form_info) {

				$form_options = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($this->request->post['form_id']);

				foreach ($form_options as $form_option) {
					// Email
					if ($form_option['form_status'] == 1) {
						if ($form_option['type'] == 'email') {
							if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
								$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
							}
						}

						// Exist Email

						if ($form_option['type'] == 'emaile_exists') {

							if ($this->model_extension_tmdadvanceformbuilder_tmd_form->getEmailExist($this->request->post['formfields'][$form_option['field_id']], $form_option['field_id'])) {
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

				$this->load->model('setting/extension');

				if ($tmdforms_info['captcha'] == 1) {
					$this->load->model('setting/extension');

					$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

					if ($extension_info && $this->config->get('captcha_'.$this->config->get('config_captcha').'_status') && in_array('contact', (array) $this->config->get('config_captcha_page'))) {
						$captcha = $this->load->controller('extension/'.$extension_info['extension'].'/captcha/'.$extension_info['code'].'.validate');

						if ($captcha) {
							 $json['error']['captcha'] = $captcha;
						}
					}
				}

				
				if ($json) {
					$json['error']['warning'] = $this->language->get('text_warrning');
				}

				if (!$json) {

					if (isset($this->request->get['productform_id'])) {
						$productform_id = (int)$this->request->get['productform_id'];
					} else {
						$productform_id = 0;
					}

					if(!empty($this->request->get['language_id'])) {
			            $this->request->post['language'] = $this->request->get['language_id'];
			        }

			        if (isset($this->request->get['baseprice'])) {
					    $baseprice = (int)$this->request->get['baseprice'];
					    
					    // Add baseprice to the post data
					    $this->request->post['baseprice'] = $baseprice;
					}

					$this->model_extension_tmdadvanceformbuilder_tmd_form->addForm($this->request->post,$productform_id);

					$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') . '&form_success='.$tmdforms_info['form_id']);
					// product name and image code 
					$tmdsuccessform_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSuccesstext($tmdforms_info['form_id']);
					
					if(!empty($tmdsuccessform_info['success_description'])){
						$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
					} else {
						$data['text_message'] = $this->language->get('text_success');
					}

					$json['producturl'] = str_replace('&amp;', '&', $producturls);
					$json['success'] = $data['text_message'];
				}
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function add():void {

		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$json = [];

		if (isset($this->request->post['form_id'])) {
			$form_id = (int) $this->request->post['form_id'];
		} else {
			$form_id = 0;
		}
        
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($this->request->post['form_id']);

		$form_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($form_id);

		if ($form_info) {

			$form_options = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($this->request->post['form_id']);

			foreach ($form_options as $form_option) {
				// Email
				if ($form_option['form_status'] == 1) {
					if ($form_option['type'] == 'email') {
						if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['formfields'][$form_option['field_id']])) {
							$json['error']['formfields'][$form_option['field_id']] = $this->language->get('error_email');
						}
					}

					// Exist Email

					if ($form_option['type'] == 'emaile_exists') {

						if ($this->model_extension_tmdadvanceformbuilder_tmd_form->getEmailExist($this->request->post['formfields'][$form_option['field_id']], $form_option['field_id'])) {
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

			if ($tmdforms_info['captcha'] == 1) {
				$this->load->model('setting/extension');

				$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

				if ($extension_info && $this->config->get('captcha_'.$this->config->get('config_captcha').'_status') && in_array('contact', (array) $this->config->get('config_captcha_page'))) {
					$captcha = $this->load->controller('extension/'.$extension_info['extension'].'/captcha/'.$extension_info['code'].'.validate');

					if ($captcha) {
						 $json['error']['captcha'] = $captcha;
					}
				}
			}

			if ($json) {
				$json['error']['warning'] = $this->language->get('text_warrning');
			}
			
			if (!$json) {

				if (isset($this->request->get['productform_id'])) {
					$productform_id = (int)$this->request->get['productform_id'];
				} else {
					$productform_id = 0;
				}

				if(!empty($this->request->get['language_id'])) {
		            $this->request->post['language'] = $this->request->get['language_id'];
		        }

		        if (isset($this->request->get['baseprice'])) {
				    $baseprice = (int)$this->request->get['baseprice'];
				    
				    // Add baseprice to the post data
				    $this->request->post['baseprice'] = $baseprice;
				}

		      	$this->model_extension_tmdadvanceformbuilder_tmd_form->addForm($this->request->post,$productform_id);

		      	$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') . '&form_success='.$tmdforms_info['form_id']);
					// product name and image code 
					$tmdsuccessform_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSuccesstext($tmdforms_info['form_id']);
					
					if(!empty($tmdsuccessform_info['success_description'])){
						$data['text_message'] = html_entity_decode($tmdsuccessform_info['success_description']);
					} else {
						$data['text_message'] = $this->language->get('text_success');
					}

					$json['producturl'] = str_replace('&amp;', '&', $producturls);
					$json['success'] = $data['text_message'];
				}

			}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
