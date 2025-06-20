<?php
namespace Opencart\Catalog\Controller\Extension\TmdAdvanceFormbuilder\Tmd;
class Form extends \Opencart\System\Engine\Controller {
	
	public function index(): void {
		
		$this->load->language('extension/tmdadvanceformbuilder/form/popupform');
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('tool/image');	

		$data['language'] = $this->config->get('config_language_id');

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/daterangepicker.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/daterangepicker.css');
		
		if (!empty($this->config->get('module_tmdformbuildermenu_status'))) {
			$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');	
		}else{
			$tmdformbuildermenu_status = '0';
		}

		if(!empty($tmdformbuildermenu_status)){
			$data['VERSION']=VERSION;
		
			if (!empty($this->request->get['form_id'])) {
				$forms_id = $this->request->get['form_id'];
			} else {
				$forms_id = '';
			}

			$url='';


			if(!empty($this->request->get['language_id'])) {
				$data['language'] = $this->request->get['language_id'];
			}else{
				$data['language'] = $this->config->get('config_language_id');
			}

			if (!empty($this->request->get['language_id'])) {
			    $language_id = (int)$this->request->get['language_id'];  
			} else {
			    $language_id = (int)$this->config->get('config_language_id');
			}

			if (isset($this->request->get['productform_id'])) {
				$data['productform_id'] = $this->request->get['productform_id'];
			} else {
				$data['productform_id'] = 0;
			}

			if (isset($this->request->get['form_id'])) {
				$data['form_id'] = $this->request->get['form_id'];
			} else {
				$data['form_id'] = 0;
			}



			$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForminfo($forms_id,$language_id);


			if(!empty($tmdforms_info)){
				if(!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type'] == 'Only customer')
				{
					if (!$this->customer->isLogged()) {
						$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language') . 'form_id='.$forms_id);
						$this->response->redirect($this->url->link('account/login', ''));
					}
				}

				if(!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type']=='Only guest')
				{
					if ($this->customer->isLogged()) {
						
						$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language') . 'form_id='.$forms_id);
					}
				}	

				if(!empty($tmdforms_info['title'])){
					$title=$tmdforms_info['title'];
				}else{
					$title='';
				}

				$this->document->setTitle($tmdforms_info['meta_title']);
				$this->document->setDescription($tmdforms_info['meta_description']);
				$this->document->setKeywords($tmdforms_info['meta_keyword']);

				$data['breadcrumbs'] = [];

				$data['breadcrumbs'][] = [
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
				];
				
				$data['breadcrumbs'][] = [
					'text' => $title,
					'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd/form' , 'language=' . $this->config->get('config_language') . '&form_id=' . $forms_id)
				];

				if (isset($this->request->get['productform_id'])) {
					$data['productform_id'] = $this->request->get['productform_id'];
				} else {
					$data['productform_id'] = 0;
				}
				if (isset($this->request->get['form_id'])) {
					$data['form_id'] = $this->request->get['form_id'];
				} else {
					$data['form_id'] = 0;
				}

				// image code
				if (!empty($this->request->get['language_id'])) {
				    $language_ids = (int)$this->request->get['language_id'];  
				} else {
				    $language_ids = (int)$this->config->get('config_language_id');
				}

				$query = $this->db->query("SELECT `code` FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_ids . "'");

				if ($query->num_rows) {
				    $data['language_code'] = $query->row['code'];  
				} else {
				    $data['language_code'] = $this->config->get('config_language');
				}

				
				// image code

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
				
			 	$data['resetbutton'] = isset( $tmdforms_info['resetbutton'] ) ? $tmdforms_info['resetbutton'] : '';	
			
				$data['customcss']  = isset( $tmdforms_info['custome_style'] ) ? $tmdforms_info['custome_style'] : '';	
				$data['form_id'] 	= isset($tmdforms_info['form_id']) ? $tmdforms_info['form_id'] : '';
				
				if(!empty($tmdforms_info['title'])){
					$data['title'] 	 	= $tmdforms_info['title'];
				}else{
					$data['title'] 	 	= '';
				}

				if(!empty($tmdforms_info['title'])){
					$data['formtitle']  = $tmdforms_info['title'];
				}else{
					$data['formtitle'] 	 	= '';
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
					

				if(!empty($tmdforms_info['form_id'])){
				$form_id = $tmdforms_info['form_id'];
				} else {
				$form_id =  '';
				}
				
				$data['form_fields'] = [];	
				
				foreach($this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldByIdinfo($form_id,$language_id) as $option) {
					if($option['form_status'] == 1){
						$form_field_option_data = [];

						foreach ($option['form_field_option'] as $option_value) {

								$form_field_option_data[] = [
									'field_id' => $option_value['field_id'],
									'form_id'         => $option_value['form_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),	
													
									'sort_order'=> $option_value['sort_order'],
									'price'     => $this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']),
									'tmdprice'  => $this->currency->format($this->calculatePrice($option_value['price_prefix'],$option_value['price'],$data['base_price']), $this->session->data['currency']),
									
								];
							}
							
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
						];
					}
				}

				$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success', 'language=' . $this->config->get('config_language') .'&form_success=' . $form_id);
				
				$data['producturl'] =str_replace('&amp;','&',$producturls);

				if(isset($tmdforms_info['captcha'])){
					$data['captchastatus'] = $tmdforms_info['captcha'];
				} else {
					$data['captchastatus']='';
				}

				if ($data['captchastatus']==1) {
				
					// Load the captcha extension model
					$this->load->model('setting/extension');

					// Check if captcha is enabled in the configuration
					if ($data['captchastatus'] == 1) {
					    $extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

					    if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
					        $data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
					    } else {
					        $data['captcha'] = '';
					    }
					}
				}
				

				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

				if ($information_info) {
					$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information|info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_account_id')), $information_info['title']);
				} else {
					$data['text_agree'] = '';
				}

				if(!empty($this->request->get['iframe'])){
					$data['informationform'] = $this->request->get['iframe'];
				}
			}
				
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/form', $data));
		
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

	public function Informationform(): void {

		$this->load->language('extension/tmdadvanceformbuilder/form/popupform');
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('tool/image');	

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/daterangepicker.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/daterangepicker.css');
		
	
		if (!empty($this->request->get['form_id'])) {
			$forms_id = $this->request->get['form_id'];
		} else {
			$forms_id = '';
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

		$data['VERSION']=VERSION;

		$url='';

		$tmdforms_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($forms_id);

		if(!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type'] == 'Only customer')
		{
			if (!$this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language'), 'form_id='.$forms_id);
				$this->response->redirect($this->url->link('account/login', ''));
			}
		}

		if(!empty($tmdforms_info['display_type']) && $tmdforms_info['display_type']=='Only guest')
		{
			if ($this->customer->isLogged()) {
				$this->session->data['redirect'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'language=' . $this->config->get('config_language'), 'form_id='.$forms_id);
			}
		}	

		if(!empty($tmdforms_info['title'])){
			$title=$tmdforms_info['title'];
		}else{
			$title='';
		}

		if (isset($this->request->get['productform_id'])) {
			$data['productform_id'] = $this->request->get['productform_id'];
		} else {
			$data['productform_id'] = 0;
		}
		if (isset($this->request->get['form_id'])) {
			$data['form_id'] = $this->request->get['form_id'];
		} else {
			$data['form_id'] = 0;
		}
			
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
	 	$data['resetbutton'] = isset( $tmdforms_info['resetbutton'] ) ? $tmdforms_info['resetbutton'] : '';	
	
		$data['customcss']  = isset( $tmdforms_info['custome_style'] ) ? $tmdforms_info['custome_style'] : '';	
		$data['form_id'] 	= isset($tmdforms_info['form_id']) ? $tmdforms_info['form_id'] : '';
		
		if(!empty($tmdforms_info['title'])){
			$data['title'] 	 	= $this->document->setTitle($tmdforms_info['title']);
		}else{
			$data['title'] 	 	= '';
		}

		if(!empty($tmdforms_info['title'])){
			$data['formtitle']  = $tmdforms_info['title'];
		}else{
			$data['formtitle'] 	 	= '';
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

		if(!empty($tmdforms_info['form_id'])){
		$form_id = $tmdforms_info['form_id'];
		} else {
		$form_id =  '';
		}
		
		$data['form_fields'] = [];	
		
		foreach($this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($form_id) as $option) {
			if($option['form_status'] == 1){
				$form_field_option_data = [];

				foreach ($option['form_field_option'] as $option_value) {

						$form_field_option_data[] = [
							'field_id' => $option_value['field_id'],
							'form_id'         => $option_value['form_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),						
							'sort_order'                    => $option_value['sort_order']
						];
					}
					
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
				];
			}
		}

		$producturls = $this->url->link('extension/tmdadvanceformbuilder/tmd/success','&form_id=' . $form_id);
		
		$data['producturl'] =str_replace('&amp;','&',$producturls);

	 	if(!empty($tmdforms_info['captcha'])){
	 		$data['captchastatus'] = $tmdforms_info['captcha'];
		}

		if ($data['captchastatus']==1) {
		
			// Captcha
			$this->load->model('setting/extension');

			$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

			if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code'] );
			} else {
				$data['captcha'] = '';
			}
		}

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

		if ($information_info) {
			$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information|info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_account_id')), $information_info['title']);
		} else {
			$data['text_agree'] = '';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
			
		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/informationform', $data));
	}

	public function advanceformbuildermenu(string &$route, array &$args, mixed &$output): void {
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');
		if(!empty($tmdformbuildermenu_status)){
			$forminfos = [];
			
			$form_infos = $this->model_extension_tmdadvanceformbuilder_tmd_form->getHeaderForms();
			
			foreach ($form_infos as $forminfo) {
				if ($forminfo['headerlink']==1) {
					$args['categories'][] = [
					'name'     => $forminfo['title'],
					'children' => [],
					'column'   => 1,
					'href'     => $this->url->link('extension/tmdadvanceformbuilder/tmd/form&language='.$this->config->get('config_language').'&form_id=' . $forminfo['form_id'])
					];	
					
				}	
			} 
		}			
	}

	public function advanceformbuilderfooter(string &$route, array &$args, mixed &$output): void {
		
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

	    $this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		
		$this->document->addStyle('extension/tmdadvanceformbuilder/catalog/view/stylesheet/popup_stylesheet.css');

		$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');
		if(!empty($tmdformbuildermenu_status)){
			$args['forminfos'] = [];
			$data1 = [];
			$form_infos = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForms($data1);
						

			foreach ($form_infos as $forminfo) {
				if ($forminfo['footerlink']==1) {
					$args['forminfos'][] = [
						'title' => $forminfo['title'],
						'href'     => $this->url->link('extension/tmdadvanceformbuilder/tmd/form&language='.$this->config->get('config_language').'&form_id=' . $forminfo['form_id'])
					];
				}
			}	

			if(isset($this->request->get['form_id'])){
				$args['form_id'] = $this->request->get['form_id'];
			} else {
				$args['form_id'] = 0;
			}
			
			$forminfo = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($args['form_id']);

			if(isset($forminfo['title'])){
				$args['entry_title'] = $forminfo['title'];
			} else {
				$args['entry_title'] = 'Form';
			}

			// Get the language ID from the configuration
			$args['language'] = $this->config->get('config_language_id');

			// Check the OpenCart version to determine the URL format
			if (VERSION >= '4.0.2.0') {
			    // OpenCart version 4.0.2.0 or later
			    $commonurls = $this->url->link('extension/tmdadvanceformbuilder/tmd/popupform.popupformpage&language='.$this->config->get('config_language'), '&form_id=');
			} else {
			    // OpenCart version earlier than 4.0.2.0
			    $commonurls = $this->url->link('extension/tmdadvanceformbuilder/tmd/popupform|popupformpage&language='.$this->config->get('config_language'), '&form_id=');
			}
		
			$args['common_pop'] =str_replace('&amp;','&',$commonurls);

			$template_buffer = $this->getTemplateBuffer($route,$output);	
			$find='<li><a href="{{ sitemap }}">{{ text_sitemap }}</a></li>';
			$replace= '<li><a href="{{ sitemap }}">{{ text_sitemap }}</a></li>'.'{% for forminfo in forminfos %}
		      <li><a href="{{ forminfo.href }}">{{ forminfo.title }}</a></li>
		     {% endfor %}';
			$output = str_replace( $find, $replace, $template_buffer );	

			$template_buffer = $this->getTemplateBuffer($route,$output);	
			$find='<footer>';
			$replace= '<footer>'.'<div class="modal in" id="help-modal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	          <div class="modal-dialog"></div>
	         </div>'.'<style type="text/css">
				  .modal.modal-wide .modal-dialog {
				    width: 50%;
				  }
				</style>      
				  <script>
				  function tmdFormPopup(formid) {
				    tmdhref = "{{ common_pop }}";
	          		var href = tmdhref+formid;
				    $("#help-modal2 .modal-content").html("");
				    $("#help-modal2").load(href); 
				}
				  </script>';
			$output = str_replace( $find, $replace, $template_buffer );	
		}

    }

    public function advanceformbuilderinformation(string &$route, array &$args, mixed &$output): void {

		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		// $this->load->model('catalog/information');
		
		$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');
		if(!empty($tmdformbuildermenu_status)){
			if (isset($this->request->get['information_id'])) {
				$information_id = (int)$this->request->get['information_id'];
			} else {
				$information_id = 0;
			}

	        $args['informationlinks'] ='';
	        $args['informationlinks'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getInformationform($information_id);
	        $args['logged'] = $this->customer->isLogged();

			$template_buffer = $this->getTemplateBuffer($route,$output);	
			$find='{{ description }}';
			$replace='{{ description }}'.' {% if informationlinks %}
	        {% for result in informationlinks %}
	            <div id="informationform{{ result.form_id }}">
	            {% if logged and result.display_type == "Only customer" or not logged and result.display_type == "Only guest" or result.display_type == "All" %}
		            <script>
		              $("#informationform{{ result.form_id }}").load("{{ result.formlink }}");  
		          
		              url = "index.php?route=extension/tmdadvanceformbuilder/tmd/form&language=' . $this->config->get('config_language').'&form_id={{ result.form_id }}&iframe=true";

		              $("#informationform{{ result.form_id }}").load(url);

		            </script>
	        		{% endif %}
	            </div>
	  		    {% endfor %}
	        {% endif %}';
			$output = str_replace( $find, $replace, $template_buffer );	
		}
    }

    public function advanceformbuilderproduct(string &$route, array &$args, mixed &$output): void {

		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');	

		$tmdformbuildermenu_status = $this->config->get('module_tmdformbuildermenu_status');
		if(!empty($tmdformbuildermenu_status)){

			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
			} else {
				$product_id = 0;
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
			} else {
				$manufacturer_id = 0;
			}

			$language_id= $this->config->get('config_language_id');

			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($product_id);

			$args['formlinks'] = '';
			$args['countproduct']= $this->model_extension_tmdadvanceformbuilder_tmd_form->getTotalFormProduct($product_id,$manufacturer_id);
			$args['formlinks']= $this->model_extension_tmdadvanceformbuilder_tmd_form->getAssignProduct($product_id,$manufacturer_id,$language_id);
	        $args['logged'] = $this->customer->isLogged();
			$args['entry_popuplinkname'] = $this->language->get('entry_popuplinkname');	

			$template_buffer = $this->getTemplateBuffer($route,$output);	
			$find='<button type="submit" id="button-cart" class="btn btn-primary btn-lg btn-block">{{ button_cart }}</button>';
			$replace='<button type="submit" id="button-cart" class="btn btn-primary btn-lg btn-block">{{ button_cart }}</button>'.'  {% if formlinks %}
		<div class="col-sm-12 linkbox">
		<div class="row m-0">
		{% for formlink in formlinks %}
		{% if formlink.display_type %}
		{% if logged and formlink.display_type == "Only customer" or not logged and  formlink.display_type == "Only guest" or formlink.display_type == "All" %}
		{% if countproduct < 1 and countproduct > 0 %}
		<div class="col padd0"> 
		{% else %}
		<div class="col paddbox">   
		{% endif %}
		{% if formlink.popuplinkname %}
		{% set popuplinkname = formlink.popuplinkname %}
		{% else %}
		{% set popuplinkname = entry_popuplinkname %}
		{% endif %}

		<a data-bs-toggle="modal" href="{{ formlink.formlink }}" data-bs-target="#help-modal" class="btn btn-primary w-100 btn-lg btn-block tmdhelptopic text-center">{{ popuplinkname }}</a> 

		</div>   
		{% endif %}
		{% endif %}
		{% endfor %}       
		</div>
		</div>
		<div class="modal in" id="help-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog"></div>
		</div>       

		<div class="modal in" id="help-modal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog"></div>  
		<!--   <script>
		$(document).on("click",".tmdhelptopic",function(e) {
		$("#help-modal .modal-content").html("<div class="loader-if centered"></div>");
		$("#help-modal").load($(this).attr("href"));        
		});       
		</script>  -->
		<script>
		$(document).on("click",".tmdhelptopic",function(e) {
		$("#help-modal .modal-content").html("");
		/*<div class="loader-if centered"></div>*/
		$("#help-modal").load($(this).attr("href"));        
		});       
		</script> 
		<style>.linkbox{padding-left:0px; padding-right:0px; margin-top:10px;}
		.paddbox{padding-left:0px; padding-right:0px;margin-bottom:10px;}
		.paddbox + .paddbox{padding-left:10px;}
		.paddbox:nth-child(3){padding-left:0px;}
		</style>
		{% endif %} 
		<div class="clearfix"></div>';
			$output = str_replace( $find, $replace, $template_buffer );
		}	
    }

    public function addHistory(string&$route,array&$args):void {
		$products ='';
		foreach($args as $values){
		$products= $values;
		}
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$order_event = $this->model_extension_tmdadvanceformbuilder_tmd_form->addHistory($products);
	}

  

	protected function getTemplateBuffer($route, $event_template_buffer) {

		// if there already is a modified template from view/*/before events use that one
		if ($event_template_buffer) {
			return $event_template_buffer;
		}

		// load the template file (possibly modified by ocmod and vqmod) into a string buffer

		if ($this->config->get('config_theme') == 'default') {
			$theme = $this->config->get('theme_default_directory');
		} else {
			$theme = $this->config->get('config_theme');
		}
		$dir_template = DIR_TEMPLATE;

		$template_file = $dir_template.$route.'.twig';
		if (file_exists($template_file) && is_file($template_file)) {

			return file_get_contents($template_file);
		}
		
		$dir_template  = DIR_TEMPLATE.'default/template/';
		$template_file = $dir_template.$route.'.twig';
		if (file_exists($template_file) && is_file($template_file)) {

			return file_get_contents($template_file);
		}
		trigger_error("Cannot find template file for route '$route'");
		exit;
	}

		
}
