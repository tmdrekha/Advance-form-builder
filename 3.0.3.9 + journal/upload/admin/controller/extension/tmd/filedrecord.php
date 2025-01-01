<?php
class ControllerExtensionTMDFiledRecord extends Controller {
 private $error = array();
		public function index() {
		$this->load->language('extension/tmd/record');
		
		$this->document->addScript('view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
			
		$this->document->setTitle($this->language->get('heading_titles'));
		
		$this->load->model('extension/tmd/record');
		$this->load->model('extension/tmd/form');
				
		$this->getList();
	}
 
 
	public function getList() {
	 
	 	if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		}
	 
	 	if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
	 
	 	if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}
	 
	  	if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = '';
		}
			 
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
		 	$sort = 'form_id';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			 $order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
	 
	 	if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . $this->request->get['filter_title'];
		}
			
	 	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
	 
	 	if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
	 
	 	if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}
	 
	 	if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title2'),
			'href' => $this->url->link('extension/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] . $url, true)
		);


		$data['records'] = array();

		$filter_data = array(
			'filter_title' 	=> $filter_title,
			'filter_name' 	=> $filter_name,
			'filter_ip'		=> $filter_ip,
			'filter_date'	=> $filter_date,
			'sort'  		=> $sort,
			'order'			=> $order,
			'start'			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 		=> $this->config->get('config_limit_admin')
		);
		
	
		$recordurl = $this->url->link('extension/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] .'&filter_title=' . $this->request->get['filter_title'] . $url, true);

		
		
		$data['recordurl'] =str_replace('&amp;','&',$recordurl);
		
		
		$report_total = $this->model_extension_tmd_record->getTotalRecord($filter_data);
		$records = $this->model_extension_tmd_record->getFormRecords($filter_data);
		
			$form_infos=$this->model_extension_tmd_form->getexportheading($this->request->get['filter_title']);
			$data['filedheading'] = array();	
			
			if(isset($form_infos)) {
				foreach($form_infos as $form_info) {
				$data['filedheading'][]    = array(	
				'label' => $form_info['label'],
				);
				}
			}
			
	 	foreach($records as $result){

	 		$form_price = $this->model_extension_tmd_record->getOrderForm($result['fs_id']);
			
			if(!empty($form_price['paid_status'])){
				$paid_status = $form_price['paid_status'];
			} else {
				$paid_status = 0;
			}

			if(!empty($form_price['order_id'])){
				$order_id = $form_price['order_id'];
			} else {
				$order_id = 0;
			}
			$this->load->model('sale/order');
	 		$order_info = $this->model_sale_order->getOrder($order_id);

	 		if(!empty($order_info['total'])){
				$total = $this->currency->format($order_info['total'], $this->config->get('config_currency'));
			} else {
				$total = $this->currency->format(0, $this->config->get('config_currency'));
			}
			$data['column_total']          = $this->language->get('column_total');
			$data['column_status']          = $this->language->get('column_status');


			if(!empty($result['productform_id'])){
			$productname = $result['name'];
			} else {
			$productname = '';
			}
			
			$filedvalue  = array();
			if(isset($form_infos)) {
					foreach($form_infos as $form_info) {
						$form_infosvalue = $this->model_extension_tmd_form->getFieldExport($result['fs_id'],$this->request->get['filter_title'],$form_info['field_id']);

						$country_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='country' AND tf.form_id ='".(int)$result['form_id']."' AND ts.fs_id ='".(int)$result['fs_id']."' AND ts.field_id ='".(int)$form_info['field_id']."'")->row;

						if(!empty($country_id)) {
							$this->load->model('localisation/country');
							$country_info = $this->model_localisation_country->getCountry($country_id['value']);
							if(!empty($country_info['name'])) {
								$form_infosvalue = $country_info['name'];
							}
						}

						$zone_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='zone' AND tf.form_id ='".(int)$result['form_id']."' AND ts.fs_id ='".(int)$result['fs_id']."' AND ts.field_id ='".(int)$form_info['field_id']."'")->row;
						if(!empty($zone_id)) {
							$this->load->model('localisation/zone');
							$zone_info = $this->model_localisation_zone->getZone($zone_id['value']);
							if(!empty($zone_info['name'])) {
								$form_infosvalue = $zone_info['name'];
							}
						}
						
						/* update code */
						$this->load->model('tool/upload');
						$type = $this->model_extension_tmd_form->getFieldType($form_info['field_id']);
						if($type=='file') {
							
							$upload_info = $this->model_tool_upload->getUploadByCode($form_infosvalue);
							if(isset($upload_info['code'])){
								$uploacode = $upload_info['code'];
							} else {
								$uploacode = '';
							}
							
							if(isset($upload_info['name'])){
								$uploaname = $upload_info['name'];
							} else {
								$uploaname = '';
							}
							if(isset($upload_info['name'])) {
							$form_infosvalue='<a href="'.$this->url->link('tool/upload/download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $uploacode, true).'">'.$uploaname.'</a>'; 
						}}
					   /* update code */
					   
					$filedvalue[]    = array(	
					'value' => $form_infosvalue,
					);
					}
			}
			
			$data['records'][]    = array(
				'fs_id'           =>$result['fs_id'],
				'form_id'         =>$result['form_id'],
				'title'           =>$result['title'],
				'total'      	  => $total,
				'status'     	  => $paid_status ? $this->language->get('text_paid') : $this->language->get('text_unpaid'),
				'filedvalue'      =>$filedvalue,
				'productname'     =>$productname,
				'ip'              =>$result['ip'],
				'date_added'      =>date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'            => $this->url->link('extension/tmd/tmdformrecord', 'user_token=' . $this->session->data['user_token'] . '&fs_id=' . $result['fs_id'] . $url, true)
			);
		}
		
		$data['heading_title']          = $this->language->get('heading_title2');
		$data['text_list']           	= $this->language->get('text_list');
		$data['text_no_results'] 		= $this->language->get('text_no_results');
		$data['text_confirm']			= $this->language->get('text_confirm');
		$data['text_none'] 				= $this->language->get('text_none');
	 	$data['text_enable']            = $this->language->get('text_enable');
		$data['text_disable']           = $this->language->get('text_disable');
		$data['text_select']            = $this->language->get('text_select');
		$data['text_none']              = $this->language->get('text_none');
		$data['column_title']			= $this->language->get('column_title');
		$data['column_name']			= $this->language->get('column_name');
		$data['column_ip']		        = $this->language->get('column_ip');
		$data['column_date']			= $this->language->get('column_date');
		$data['column_action']			= $this->language->get('column_action');
		$data['entry_title']			= $this->language->get('entry_title');
		$data['entry_name']			    = $this->language->get('entry_name');
		$data['entry_ip']			    = $this->language->get('entry_ip');
		$data['entry_date']			    = $this->language->get('entry_date');
		$data['button_remove']          = $this->language->get('button_remove');
		$data['button_delete']          = $this->language->get('button_delete');
		$data['button_filter']          = $this->language->get('button_filter');
		$data['button_view']            = $this->language->get('button_view');
		$data['column_productname']     = $this->language->get('column_productname');
		$data['text_confirm']           = $this->language->get('text_confirm');
		$data['name']                   = $this->language->get('name');
		$data['user_token']                  = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	 
	 	if (isset($this->session->data['success'])) {
		 	$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
	 
		$data['sort'] 			= $sort;
		$data['order'] 			= $order;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$data['sort_date']  	= $this->url->link('extension/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] . '&sort=date' . $url, true);
		
	 	$url = '';
		
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . $this->request->get['filter_title'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
	 	if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
	 	
	 	if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}
	 		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination 		= new Pagination();
		$pagination->total 	= $report_total;
		$pagination->page  	= $page;
		$pagination->limit 	= $this->config->get('config_limit_admin');
		$pagination->url   	= $this->url->link('extension/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] . $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();


		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($report_total - $this->config->get('config_limit_admin'))) ? $report_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $report_total, ceil($report_total / $this->config->get('config_limit_admin')));

		$data['filter_title']  = $filter_title;
		$data['filter_name']   = $filter_name;
		$data['filter_ip']     = $filter_ip;
		$data['filter_date']   = $filter_date;
		$data['sort']          = $sort;
		$data['order']         = $order;
		
		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/tmd/filedrecord_list',$data));
		
	}
	
	public function autocomplete(){
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$this->load->model('extension/tmd/form');
			
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
		$results=$this->model_extension_tmd_form->getForms($filter_data);

		foreach ($results as $result) {

		$json[]     = array(
		'form_id'   => $result['form_id'],
		'title'     => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
		);
		}
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
   
    public function deleterecord() {	
		
		$json = array();

	    if (isset($this->request->post['fs_id'])) {
	        $this->load->model('extension/tmd/record');
			$this->load->language('extension/tmd/record');
			
	        $this->model_extension_tmd_record->deleteRecord($this->request->post['fs_id']);
	        $json['success'] = $this->language->get('text_success_message');
	        
	        $this->session->data['success'] = $this->language->get('text_success_message');
	    } else {
	        $json['error'] = $this->language->get('text_success_not');
	    }

	    // Return the JSON response
	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
	}
			
}
?>
