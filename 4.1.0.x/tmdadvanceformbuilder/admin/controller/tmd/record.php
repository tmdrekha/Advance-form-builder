<?php
namespace Opencart\Admin\Controller\Extension\TmdAdvanceformbuilder\Tmd;

class Record extends \Opencart\System\Engine\Controller {
	private $error = [];
	public function index(): void {
		$data['VERSION'] = VERSION;
		$this->load->language('extension/tmdadvanceformbuilder/tmd/record');
			
		$this->document->setTitle($this->language->get('heading_title1'));

		$url = '';

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
		$data['VERSION'] = VERSION;

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		
		if(VERSION>='4.0.2.0')
		{
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title1'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd.record', 'user_token=' . $this->session->data['user_token'] . $url)
			];			
		}
		else{
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title1'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd|record', 'user_token=' . $this->session->data['user_token'] . $url)
			];			
		}
		
		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/record', $data));
	}
	
	public function list(): void {
		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->language('extension/tmdadvanceformbuilder/tmd/record');

		$this->response->setOutput($this->getList());
	}


	public function getList(): string {
		$data['VERSION'] = VERSION;

 		$this->load->model('extension/tmdadvanceformbuilder/tmd/record');
 		$this->load->model('customer/customer');
	 
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
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
	 	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
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

		if(VERSION>='4.0.2.0')
		{			
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title1'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd.record', 'user_token=' . $this->session->data['user_token'] . $url, true)
			);
		}
		else{
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title1'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd|record', 'user_token=' . $this->session->data['user_token'] . $url, true)
			);	
		}

		$data['records'] = [];

		$filter_data = [
			'filter_title' 	=> $filter_title,
			'filter_name' 	=> $filter_name,
			'filter_ip'		=> $filter_ip,
			'filter_date'	=> $filter_date,
			'sort'  		=> $sort,
			'order'			=> $order,
			'start'			=> ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' 		=> $this->config->get('config_pagination_admin')
		];
		
	
		$report_total = $this->model_extension_tmdadvanceformbuilder_tmd_record->getTotalRecord($filter_data);
		$records = $this->model_extension_tmdadvanceformbuilder_tmd_record->getFormRecords($filter_data);
		
	 	foreach($records as $result){
			
			if(!empty($result['productform_id'])){
			$productname = $result['name'];
			} else {
			$productname = '';
			}			
			
			if(!empty($result['customer_name'])){
			$customer_name = $result['customer_name'];
			} else {
			$customer_name = 'Guest';
			}
			
			$data['records'][]    = [
				'fs_id'           =>$result['fs_id'],
				'form_id'         =>$result['form_id'],
				'title'           =>$result['title'],
				'customer_name'   =>$customer_name,				
				'productname'     =>$productname,
				'ip'              =>$result['ip'],
				'date_added'      =>date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'            => $this->url->link('extension/tmdadvanceformbuilder/tmd/tmdformrecord', 'user_token=' . $this->session->data['user_token'] . '&fs_id=' . $result['fs_id'] . $url, true)
			];

		}

		$url = '';
	 
	 	if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
	 	if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
	 	
   		
		$data['heading_title']          = $this->language->get('heading_title');
		
		$data['user_token']             = $this->session->data['user_token'];
		
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
		
		if(VERSION>='4.0.2.0')
		{
			$data['sort_title']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record.list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url, true);
			$data['sort_name']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
			$data['sort_ip']        = $this->url->link('extension/tmdadvanceformbuilder/tmd/record.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ip' . $url, true);
			$data['sort_date']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record.list', 'user_token=' . $this->session->data['user_token'] . '&sort=date' . $url, true);			
		}
		else{			
			$data['sort_title']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record|list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url, true);
			$data['sort_name']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
			$data['sort_ip']        = $this->url->link('extension/tmdadvanceformbuilder/tmd/record|list', 'user_token=' . $this->session->data['user_token'] . '&sort=ip' . $url, true);
			$data['sort_date']  	= $this->url->link('extension/tmdadvanceformbuilder/tmd/record|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date' . $url, true);
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
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/record.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);			
		}
		else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $report_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/record|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}		

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($report_total - $this->config->get('config_pagination_admin'))) ? $report_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $report_total, ceil($report_total / $this->config->get('config_pagination_admin')));


		$data['filter_title']  = $filter_title;

		$results=$this->model_extension_tmdadvanceformbuilder_tmd_form->getFormname($filter_title);

		if(isset($results['title'])){
			$data['form_title'] = $results['title'];
		}else{
			$data['form_title'] = '';
		}	

		$data['filter_name']   = $filter_name;

		if(!empty($data['filter_name'])){
		$customername = $this->model_customer_customer->getCustomer($data['filter_name']);
		}

		if(isset($customername['firstname'])){
			$data['customernames'] = $customername['firstname'].' '.$customername['lastname'];
		}else{
			$data['customernames'] = '';
		}

		$data['filter_ip']     = $filter_ip;
		$data['filter_date']   = $filter_date;

		$data['sort']          = $sort;
		$data['order']         = $order;

		// $this->load->model('customer/customer');
		if(isset($records['customer_id'])){
			$customer_info = $this->model_customer_customer->getCustomer($data,$records['customer_id']);
		}

		if(isset($customer_info['firstname'])){
			$firstname = $customer_info['firstname'];
		}else{
			$firstname = '';
		}

		if(isset($customer_info['lastname'])){
			$lastname = $customer_info['lastname'];
		}else{
			$lastname = '';
		}

		if(isset($customer_info['customer_id'])){
			$data['customer_id'] = $customer_info['customer_id'];
		}else{
			$data['customer_id'] = '';
		}

		$data['customername'] = $firstname.' '.$lastname;

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');
		
		return $this->load->view('extension/tmdadvanceformbuilder/tmd/record_list', $data);
	}

	public function autocompletecustomer(): void {

		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}


		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = 5;
		}

		$this->load->model('customer/customer');

		$data = [
			'filter_name' => $this->request->get['filter_name'],
			'start'       => 0,
			'limit'       => 20
		];

		$results = $this->model_customer_customer->getCustomers($data);

		foreach ($results as $result) {
			$json[] = [
				'customer_id'       => $result['customer_id'],
				'firstname'         => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
			];

		}
		
		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['firstname'];
		}

		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function autocomplete(): void {
		
		$json = [];

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = 5;
		}

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
			
		$filter_data = [
			'filter_title' => $filter_title,
			'start'        => 0,
			'limit'        => $limit
		];

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

		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
?>
