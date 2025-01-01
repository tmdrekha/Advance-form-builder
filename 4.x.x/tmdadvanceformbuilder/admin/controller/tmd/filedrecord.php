<?php
namespace Opencart\Admin\Controller\Extension\TmdAdvanceformbuilder\Tmd;
class Filedrecord extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$data['VERSION'] = VERSION;
		$this->load->language('extension/tmdadvanceformbuilder/tmd/record');

		$this->document->setTitle($this->language->get('heading_titles'));
			$data['heading_title']          = $this->language->get('heading_title2');

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title2'),
			'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd/record', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['sort_date']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record', 'user_token=' .$this->session->data['user_token'] . $url);

		if(VERSION>='4.0.2.0')
		{
			$data['add'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/record.form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/record.delete', 'user_token=' . $this->session->data['user_token']);
			
		}
		else{
			$data['add'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/record|form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/record|delete', 'user_token=' . $this->session->data['user_token']);
		}


		$data['list'] = $this->getList();

		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/filedrecord', $data));
	}

	public function list(): void {
		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->language('extension/tmdadvanceformbuilder/tmd/record');

		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		$data['VERSION'] = VERSION;

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

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
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
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

		if(VERSION>='4.0.2.0')
		{			
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord.list', 'user_token=' . $this->session->data['user_token'] . $url);
		}
		else{
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord|list', 'user_token=' . $this->session->data['user_token'] . $url);
			
		}

		$data['filter_title']= $this->request->get['filter_title'];
		$data['form_id']= $this->request->get['form_id'];

		$filter_data = [
			'filter_title' 	=> $filter_title,
			'filter_name' 	=> $filter_name,
			'filter_ip'		=> $filter_ip,
			'filter_date'	=> $filter_date,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');

		$data['records'] = [];
	
		$recordurl = $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] .'&filter_title=' . $this->request->get['filter_title'] . $url);
		
		$data['recordurl'] =str_replace('&amp;','&',$recordurl);
			
		$report_total = $this->model_extension_tmdadvanceformbuilder_tmd_record->getTotalRecord($filter_data);
		$records = $this->model_extension_tmdadvanceformbuilder_tmd_record->getFormRecords($filter_data);

			$form_infos=$this->model_extension_tmdadvanceformbuilder_tmd_form->getexportheading($this->request->get['filter_title']);
		
			$data['filedheading'] = [];	
			
			if(isset($form_infos)) {
				foreach($form_infos as $form_info) {
					$data['filedheading'][]    = [	
						'label' => $form_info['label'],
					];
				}
			}


	 	foreach($records as $result){

	 		$form_price = $this->model_extension_tmdadvanceformbuilder_tmd_record->getOrderForm($result['fs_id']);
			
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
			
			$filedvalue  = [];
			if(isset($form_infos)) {
					foreach($form_infos as $form_info) {
						if ($form_info['serialize']) {
							$fieldinfos = unserialize($form_info['value']);

							$value = '';
							foreach ($fieldinfos as $field) {

								$value .= $field.',';
							}

							$form_info['value'] = $value;
						}

						$form_infosvalue = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFieldExport($result['fs_id'],$this->request->get['filter_title'],$form_info['field_id']);

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
						$type = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFieldType($form_info['field_id']);
						if($type=='file') {
							
							$upload_info        = $this->model_tool_upload->getUploadByCode($form_infosvalue);

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
								$form_infosvalue='<a href="'.$this->url->link('tool/upload|download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $uploacode, true).'">'.$uploaname.'</a>'; 
							}
						}
					   /* update code */
					   
						$filedvalue[]    = [	
							'value' => $form_infosvalue,
						];
					}
			}
			
			$data['records'][]    = [
				'fs_id'           =>$result['fs_id'],
				'form_id'         =>$result['form_id'],
				'title'           =>$result['title'],
				'filedvalue'      =>$filedvalue,
				'productname'     =>$productname,
				// 
				'total'      	=>$total,
				'status'     	=> $paid_status ? $this->language->get('text_paid') : $this->language->get('text_unpaid'),
				// 
				'ip'              =>$result['ip'],
				'date_added'      =>date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'            => $this->url->link('extension/tmdadvanceformbuilder/tmd/tmdformrecord', 'user_token=' . $this->session->data['user_token'] . '&fs_id=' . $result['fs_id'] . $url, true)
			];

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


		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(VERSION>='4.0.2.0'){
			$data['sort_date']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord.list', 'user_token=' .$this->session->data['user_token'] .'&sort=date' . '&form_id=' . $this->request->get['form_id'] . $url);
		}else{
			$data['sort_date']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord|list', 'user_token=' .$this->session->data['user_token'] .'&sort=date' . '&form_id=' . $this->request->get['form_id'] . $url);			
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

		if(VERSION>='4.0.2.0')
		{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $report_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord.list', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] . $url . '&page={page}')
			]);
		}
		else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $report_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord|list', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $this->request->get['filter_title'] . $url . '&page={page}')
			]);
		}

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($report_total - $this->config->get('config_pagination_admin'))) ? $report_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $report_total, ceil($report_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		return $this->load->view('extension/tmdadvanceformbuilder/tmd/filedrecord_list', $data);
	}
	public function autocomplete(): void {
		$json = [];
		
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
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
			
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		//'filter_name' => $filter_name,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
		$results=$this->model_extension_tmdadvanceformbuilder_tmd_form->getForms($filter_data);

		foreach ($results as $result) {

		$json[]     = [
		'form_id'   => $result['form_id'],
		'title'     => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
		];
		}
		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
   

	public function deleterecord(): void {
		$this->load->language('extension/tmdadvanceformbuilder/tmd/record');

	    $json = [];

	    if (isset($this->request->post['fs_id'])) {
	        $this->load->model('extension/tmdadvanceformbuilder/tmd/record');    
	        
	        $this->model_extension_tmdadvanceformbuilder_tmd_record->deleteRecord($this->request->post['fs_id']);

	        $json['success'] = $this->language->get('text_success_message');
	    } else {
	        $json['error'] = $this->language->get('text_success_not');
	    }

	    $this->response->addHeader('Content-Type: application/json');
	    $this->response->setOutput(json_encode($json));
	}
}
