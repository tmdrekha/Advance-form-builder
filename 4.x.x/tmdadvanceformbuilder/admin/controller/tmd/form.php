<?php
namespace Opencart\Admin\Controller\Extension\TmdAdvanceformbuilder\Tmd;
use \Opencart\System\Helper as Helper;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/Psr/autoloader.php');
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/myclabs/Enum.php');
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/ZipStream/autoloader.php');
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/ZipStream/ZipStream.php');
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/PhpSpreadsheet/autoloader.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Form extends \Opencart\System\Engine\Controller {

	public function index(): void {
		$data['HTTP_CATALOG']=HTTP_CATALOG;
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$this->document->setTitle($this->language->get('heading_title1'));

		$url = '';

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
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if(VERSION>='4.0.2.0')
		{
			$data['add'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.delete', 'user_token=' . $this->session->data['user_token']);
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
		}
		else{
			$data['add'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|form', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['delete'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|delete', 'user_token=' . $this->session->data['user_token']);			
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
		}


		$data['list'] = $this->getList();

		$data['config_language_id'] = $this->config->get('config_language_id');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/form ', $data));
	}

	public function list(): void {
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
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
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . $url);			
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
		}
		else{
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['action'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . $url);
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
		}

		$data['forms'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

		$form_total = $this->model_extension_tmdadvanceformbuilder_tmd_form->getTotalForm($filter_data);

		$forms = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForms($filter_data);

		foreach($forms as $form){
			
			$formurl = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($form['form_id']);
			
			$preview = HTTP_CATALOG.'index.php?route=extension/tmdadvanceformbuilder/tmd/form'.'&form_id=' . $form['form_id'] ; 

			if(VERSION>='4.0.2.0')
			{				
				$data['forms'][] = [
					'form_id' 	=> $form['form_id'],
					'title' 	=> $form['title'],
					'status'    => ($form['status'] ? $this->language->get('text_enable') : $this->language->get('text_disable')),
					'edit'		=> $this->url->link('extension/tmdadvanceformbuilder/tmd/form.form', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id']. $url),
					'view'		=> $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id'] .'&filter_title=' . $form['form_id'] . $url, true),
					'export'	=> $this->url->link('extension/tmdadvanceformbuilder/tmd/form.export', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id'] . $url, true),				
					'preview'	=>  $preview
				];
			}
			else{
				$data['forms'][] = [
					'form_id' 	=> $form['form_id'],
					'title' 	=> $form['title'],
					'status'    => ($form['status'] ? $this->language->get('text_enable') : $this->language->get('text_disable')),
					'edit'		=> $this->url->link('extension/tmdadvanceformbuilder/tmd/form|form', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id']. $url),
					'view'		=> $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id'] .'&filter_title=' . $form['form_id'] . $url, true),
					'export'	=> $this->url->link('extension/tmdadvanceformbuilder/tmd/form|export', 'user_token=' . $this->session->data['user_token'] .'&form_id=' . $form['form_id'] . $url, true),				
					'preview'	=>  $preview
				];
				
			}
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if(VERSION>='4.0.2.0')
		{
			$data['sort_title']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url);
			$data['sort_preview']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . '&sort=preview' . $url);
			$data['sort_status']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
			
		}
		else{			
			$data['sort_title']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url);
			$data['sort_preview']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . '&sort=preview' . $url);
			$data['sort_status']  = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
			
		}
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if(VERSION>='4.0.2.0')
		{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $form_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/form.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);			
		}
		else{
			$data['pagination'] = $this->load->controller('common/pagination', [
				'total' => $form_total,
				'page'  => $page,
				'limit' => $this->config->get('config_pagination_admin'),
				'url'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/form|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
			]);
		}

		$data['results'] = sprintf($this->language->get('text_pagination'), ($form_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($form_total - $this->config->get('config_pagination_admin'))) ? $form_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $form_total, ceil($form_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('extension/tmdadvanceformbuilder/tmd/form_list', $data);
	}

	public function form(): void {
		$data['VERSION'] = VERSION;
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$this->document->setTitle($this->language->get('heading_title1'));

		$data['text_form'] = !isset($this->request->get['form_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			///  language //////
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		
	 	$this->load->model('customer/customer_group');
		$data['customergroups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();


		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		/// Display Type		
		$data['displaytypes'] = [];
			
		$data['displaytypes'][] = [
			'display_type'  => $this->language->get('text_all'),
			'value' 		=> 'all'
		];
	 	$data['displaytypes'][] = [
			'display_type'  => $this->language->get('text_guest'),
			'value' 		=> 'only guest'
		];
		$data['displaytypes'][] = [
			'display_type'  => $this->language->get('text_customer'),
			'value' 		=> 'only customer'
		];
/// Display Type

		/// Product		
		$data['assigns'] = [];
		
		$data['assigns'][] = [
			'assign_product'  => $this->language->get('text_noproduct'),
			'value' 		=> '1'
		];
	 	$data['assigns'][] = [
			'assign_product'  => $this->language->get('text_allproduct'),
			'value' 		=> '2'
		];
		$data['assigns'][] = [
			'assign_product'  => $this->language->get('text_selectproduct'),
			'value' 		=> '3'
		];
/// Product		

		if(VERSION>='4.0.2.0')
		{
			$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form.save', 'user_token=' . $this->session->data['user_token']);
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
			
		}
		else{
			$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form|save', 'user_token=' . $this->session->data['user_token']);
			$data['back'] = $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token=' . $this->session->data['user_token'] . $url);
		}


		if (isset($this->request->get['form_id'])) {
			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

			$form_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForm($this->request->get['form_id']);
		}

		$this->load->model('setting/store');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		if(isset($this->request->post['form_id'])){
			$form_id =$this->request->post['form_id'];
		} else if(!empty($form_info)){
			$form_id =$form_info['form_id'];
		} else {
			$form_id ='';
		}

		if (isset($this->request->post['form_seo_url'])) {
			$data['form_seo_url'] = $this->request->post['form_seo_url'];
		} elseif (isset($this->request->get['form_id'])) {
			$data['form_seo_url'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSeoUrls($this->request->get['form_id']);
		} else {
			$data['form_seo_url'] = [];
		}


		
		if (isset($this->request->post['form_success_page_seo'])) {
			$data['form_success_page_seo'] = $this->request->post['form_success_page_seo'];
		} elseif (isset($this->request->get['form_id'])) {
			$data['form_success_page_seo'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormSuccessSeoUrls($this->request->get['form_id']);
		} else {
			$data['form_success_page_seo'] = array(0);
		}
		
		


		if(isset($this->request->post['form_id'])){
			$data['form_id']=$this->request->post['form_id'];
		} else if(!empty($form_info)){
			$data['form_id']=$form_info['form_id'];
		} else {
			$data['form_id']='';
		}
		
		if(isset($this->request->post['status'])){
			$data['status']=$this->request->post['status'];
		} else if(!empty($form_info)){
			$data['status']=$form_info['status'];
		} else {
			$data['status']='';
		}

// xml
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($form_info)) {
			$data['product_id'] = $form_info['product_id'];
		} else {
			$data['product_id'] = 0;
		}

		if (isset($this->request->post['form_product'])) {
			$data['form_product'] = $this->request->post['form_product'];
		} elseif (!empty($form_info)) {
			$pro_info = $this->model_catalog_product->getProduct($form_info['product_id']);

			if ($pro_info) {
				$data['form_product'] = $pro_info['name'];
			} else {
				$data['form_product'] = '';
			}
		} else {
			$data['form_product'] = '';
		}

		if(isset($this->request->post['price'])){
			$data['price']=$this->request->post['price'];
		} else if(!empty($form_info)){
			$data['price']=$form_info['price'];
		} else {
			$data['price']='';
		}

// xml


		
		if(isset($this->request->post['resetbutton'])){
			$data['resetbutton']=$this->request->post['resetbutton'];
		} else if(!empty($form_info)){
			$data['resetbutton']=$form_info['resetbutton'];
		} else {
			$data['resetbutton']='';
		}
		if(isset($this->request->post['captcha'])){
			$data['captcha']=$this->request->post['captcha'];
		} else if(!empty($form_info)){
			$data['captcha']=$form_info['captcha'];
		} else {
			$data['captcha']='';
		}
		
		if(isset($this->request->post['headerlink'])){
			$data['headerlink']=$this->request->post['headerlink'];
		} else if(!empty($form_info['headerlink'])){
			$data['headerlink']=$form_info['headerlink'];
		} else {
			$data['headerlink']='';
		}
		
		if(isset($this->request->post['footerlink'])){
			$data['footerlink']=$this->request->post['footerlink'];
		} else if(!empty($form_info['footerlink'])){
			$data['footerlink']=$form_info['footerlink'];
		} else {
			$data['footerlink']='';
		}

		
		if(isset($this->request->post['bgcolor'])){
			$data['bgcolor']=$this->request->post['bgcolor'];
		} else if(!empty($form_info['bgcolor'])){
			$data['bgcolor']=$form_info['bgcolor'];
		} else {
			$data['bgcolor']='';
		}

		if(isset($this->request->post['txtcolor'])){
			$data['txtcolor']=$this->request->post['txtcolor'];
		} else if(!empty($form_info['txtcolor'])){
			$data['txtcolor']=$form_info['txtcolor'];
		} else {
			$data['txtcolor']='';
		}

		if(isset($this->request->post['submit_bgcolor'])){
			$data['submit_bgcolor']=$this->request->post['submit_bgcolor'];
		} else if(!empty($form_info['submit_bgcolor'])){
			$data['submit_bgcolor']=$form_info['submit_bgcolor'];
		} else {
			$data['submit_bgcolor']='';
		}

		if(isset($this->request->post['submit_txtcolor'])){
			$data['submit_txtcolor']=$this->request->post['submit_txtcolor'];
		} else if(!empty($form_info['submit_txtcolor'])){
			$data['submit_txtcolor']=$form_info['submit_txtcolor'];
		} else {
			$data['submit_txtcolor']='';
		}


		if(isset($this->request->post['reset_bgcolor'])){
			$data['reset_bgcolor']=$this->request->post['reset_bgcolor'];
		} else if(!empty($form_info['reset_bgcolor'])){
			$data['reset_bgcolor']=$form_info['reset_bgcolor'];
		} else {
			$data['reset_bgcolor']='';
		}

		if(isset($this->request->post['reset_txtcolor'])){
			$data['reset_txtcolor']=$this->request->post['reset_txtcolor'];
		} else if(!empty($form_info['reset_txtcolor'])){
			$data['reset_txtcolor']=$form_info['reset_txtcolor'];
		} else {
			$data['reset_txtcolor']='';
		}		
		
		if(isset($this->request->post['productwidth'])){
			$data['productwidth']=$this->request->post['productwidth'];
		} else if(!empty($form_info['productwidth'])){
			$data['productwidth']=$form_info['productwidth'];
		} else {
			$data['productwidth']='';
		}
		
		if(isset($this->request->post['productheight'])){
			$data['productheight']=$this->request->post['productheight'];
		} else if(!empty($form_info['productheight'])){
			$data['productheight']=$form_info['productheight'];
		} else {
			$data['productheight']='';
		}
		
				
		if(isset($this->request->post['display_type'])){
			$data['display_type']=$this->request->post['display_type'];
		} else if(!empty($form_info)){
			$data['display_type']=$form_info['display_type'];
		} else {
			$data['display_type']='';
		}
		
		if(isset($this->request->post['assign_product'])){
			$data['assign_product']=$this->request->post['assign_product'];
		} else if(!empty($form_info)){
			$data['assign_product']=$form_info['assign_product'];
		} else {
			$data['assign_product']='';
		}
	
		
		if(isset($this->request->post['custome_style'])){
			$data['custome_style']=$this->request->post['custome_style'];
		} else if(isset($form_info['custome_style'])){
			$data['custome_style']=$form_info['custome_style'];
		} else {
			$data['custome_style']='';
		}
				
		if (isset($this->request->post['form_description'])) {
			$data['form_description'] = $this->request->post['form_description'];
		}elseif (isset($form_info)) {
			$data['form_description'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormDescriptions($this->request->get['form_id']);
		}else {
			$data['form_description'] = '';
		}
		
		if (isset($this->request->post['succes_form'])) {
			$data['succes_form'] = $this->request->post['succes_form'];
		}elseif (isset($form_info)) {
			$data['succes_form'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormSuccess($this->request->get['form_id']);
		}else {
			$data['succes_form'] = '';
		}
		
		if (isset($this->request->post['form_notification'])) {
			$data['form_notification'] = $this->request->post['form_notification'];
		}elseif (isset($form_info)) {
			$data['form_notification'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormNotification($this->request->get['form_id']);
		}else {
			$data['form_notification'] = '';
		}
		
		if (isset($this->request->post['form_customer'])) {
			$data['form_customer'] = $this->request->post['form_customer'];
		} elseif (isset($this->request->get['form_id'])) {
			$data['form_customer'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getCustomerCheckbox($this->request->get['form_id']);
		} else {
			$data['form_customer'] = [];
		}
		
		if (isset($this->request->post['form_store'])) {
			$data['form_store'] = $this->request->post['form_store'];
		} elseif (isset($this->request->get['form_id'])) {
			$data['form_store'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormByStoreId($this->request->get['form_id']);
		} else {
			$data['form_store'] = [0];
		}
		
		if (isset($this->request->post['productform_id'])){
			$productform_ids = $this->request->post['productform_id'];
		}elseif (isset($form_info)){
			$productform_ids = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormProductbyid($this->request->get['form_id']);
		} else{
			$productform_ids = '';
		}
		
		if (isset($this->request->post['category_id'])){
			$category_ids = $this->request->post['category_id'];
		}elseif (isset($form_info)){
			$category_ids = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormCategorybyid($this->request->get['form_id']);
		} else{
			$category_ids = '';
		}
		
		if (isset($this->request->post['manufacturer_id'])){
			$manufacturer_ids = $this->request->post['manufacturer_id'];
		}elseif (isset($form_info)){
			$manufacturer_ids = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormManufacturerbyid($this->request->get['form_id']);
		} else{
			$manufacturer_ids = '';
		}
		

	// producta
		$this->load->model('catalog/product');

		if ($form_id) {
			$products = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormProductes($form_id);
		} else {
			$products = [];
		}

		$data['products'] = [];

		foreach ($products as $productform_id) {
			$product_info = $this->model_catalog_product->getProduct($productform_id);

			if ($product_info) {
				$data['products'][] = [
					'product_id' => $product_info['product_id'],
					'name'        =>  $product_info['name'] ,
				];
			}
		}

		
		
		// Categories
		$this->load->model('catalog/category');

		if ($form_id) {
			$categories = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormCategories($form_id);
		} else {
			$categories = [];
		}

		$data['form_categoriess'] = [];

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['form_categoriess'][] = [
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				];
			}
		}

		// Manufecture
		$this->load->model('catalog/manufacturer');

		if ($form_id) {
			$manufacturers = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormManufacturer($form_id);
		} else {
			$manufacturers = [];
		}


		$data['form_manufacturers'] = [];

		foreach ($manufacturers as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);


			if ($manufacturer_info) {
				$data['form_manufacturers'][] = [
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'name'        => $manufacturer_info['name']
				];
			}
		}		
		
				
		
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} else {
			$data['type'] = '';
		}
		
		if (isset($this->request->post['form_status'])) {
			$data['form_status'] = $this->request->post['form_status'];
		} else {
			$data['form_status'] = '';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		}  else {
			$data['sort_order'] = '0';
		}
		
		if (isset($this->request->post['required'])) {
			$data['required'] = $this->request->post['required'];
		} else {
			$data['required'] = '';
		}


		
		$this->load->model('tool/image');
		if (isset($this->request->post['option_fields'])) {
			$optionfieldss=array();
			$optionsdatas = $this->request->post['option_fields'];

			foreach($optionsdatas as $optionsdata)
			{
				$option_type=array();
				if(!empty($optionsdata['option_type']))
				{
					foreach($optionsdata['option_type'] as $optiontype)
					{
						$option_value_description=array();
						foreach($optiontype['option_value_description'] as $language_id=>$value)
						{
							$option_value_description[$language_id]=$value['name'];
						}

						if (is_file(DIR_IMAGE . $optiontype['image'])) {
							$thumb = $this->model_tool_image->resize($optiontype['image'], 500, 500);
						} else {
							$thumb = $this->model_tool_image->resize('no_image.png', 500, 500);
						}
					


						$option_type[]=array(
											'name'=>$option_value_description,
											'sort_order'=>$optiontype['sort_order'],
											'image'=>$optiontype['image'],
											'price'=>$optiontype['price'],
											'price_prefix'=>$optiontype['price_prefix'],
											'thumb'=>$thumb,
										);
					}
					$optionsdata['form_field_options']=$option_type;
				}
				$optionfieldss[]=$optionsdata;
			}
			
		} elseif (isset($this->request->get['form_id'])) {
			$optionfieldss = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormFieldById($this->request->get['form_id']);
		} else {
			$optionfieldss = array();
		}


		$data['optionfieldss'] = $optionfieldss;


		if (isset($this->request->post['information'])){
			$data['information'] = $this->request->post['information'];
		}elseif (isset($form_info['form_id'])){
			$data['information'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getFormInformation($this->request->get['form_id']);
		} else{
			$data['information'] = [0];
		}

		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['user_token'] = $this->session->data['user_token'];

		$data['config_language_id'] = $this->config->get('config_language_id');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['HTTP_CATALOG']=HTTP_CATALOG;

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/tmd/formbuilder', $data));
	}

	public function save(): void {
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdadvanceformbuilder/tmd/form')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['form_description'] as $language_id => $value) {
            if(VERSION>='4.0.2.0')
            {
                    if ((oc_strlen(trim($value['title'])) < 1) || (oc_strlen(trim($value['title'])) > 255)) {
                    $json['error']['title_' . $language_id] = $this->language->get('error_title');
                }
            }else{
                if ((Helper\Utf8\strlen(trim($value['title'])) < 1) || (Helper\Utf8\strlen(trim($value['title'])) > 255)) {
                    $json['error']['title_' . $language_id] = $this->language->get('error_title');
                }
            }
        }


		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

			if (!$this->request->post['form_id']) {
				$json['form_id'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->addForm($this->request->post);
			} else {
				$this->model_extension_tmdadvanceformbuilder_tmd_form->editForm($this->request->post['form_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'extension/tmdadvanceformbuilder/tmd/form')) {
			$json['error'] = $this->language->get('error_permission');
		}

	
		if (!$json) {
			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

			foreach ($selected as $form_id) {
				$this->model_extension_tmdadvanceformbuilder_tmd_form->deleteForm($form_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function fielddelete(){
		$json = array();
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$this->model_extension_tmdadvanceformbuilder_tmd_form->deleteAllFieldById($this->request->get['field_id']);
			
			$json['success'] = $this->language->get('text_success');
		}					
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function export(){
		if (isset($this->request->get['form_id'])) {
			$form_id = $this->request->get['form_id'];
		} else {
		 	$form_id = '';
		}
		$this->load->language('extension/tmdadvanceformbuilder/tmd/form');
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		
		$spreadsheet = new Spreadsheet();

		$form_infos=$this->model_extension_tmdadvanceformbuilder_tmd_form->getexportheading($form_id);
		
		$i=1;
		$cel="A";
		if(isset($form_infos))
		{
		 foreach($form_infos as $form_info) {
						
		  $spreadsheet->getActiveSheet()->SetCellValue($cel.$i, $form_info['label']);
			$cel++;
			
			}	
		}
		 
		 $spreadsheet->getActiveSheet()->SetCellValue($cel.$i, 'Customer Id');
		 $cel++;
		 $spreadsheet->getActiveSheet()->SetCellValue($cel.$i, 'IP');
		 $cel++;
		 $spreadsheet->getActiveSheet()->SetCellValue($cel.$i, 'Date Add');
		 $cel++;
		 
		$submitdatas=$this->model_extension_tmdadvanceformbuilder_tmd_form->getexportsubmit($form_id);

		$cel="A";

			if(isset($submitdatas)) {			
				foreach($submitdatas as $submitdata) {					
					$cel="A";
					$i++;
					if(isset($form_infos)){
						foreach($form_infos as $form_info) {
							$value=$this->model_extension_tmdadvanceformbuilder_tmd_form->getFieldExport($submitdata['fs_id'],$form_id,$form_info['field_id']);
					  		$spreadsheet->getActiveSheet()->SetCellValue($cel.$i,$value);
							$cel++;					
						}	
					}
					
					$spreadsheet->getActiveSheet()->SetCellValue($cel.$i, $submitdata['customer_id']);
					$cel++;
					$spreadsheet->getActiveSheet()->SetCellValue($cel.$i, $submitdata['ip']);
					$cel++;
					$spreadsheet->getActiveSheet()->SetCellValue($cel.$i, $submitdata['date_added']);
					$cel++;
						
				}
			}

			/* color setup */
			for($col = 'A'; $col != 'U'; $col++) {
		   		$spreadsheet->getActiveSheet()->getColumnDimension($col)->setWidth(25);
		 	}
			
			$spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
			
			$spreadsheet->getActiveSheet()
			->getStyle('A1:U1')
			->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('FF4F81BD');
			
			$styleArray = array(
				'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size'  => 9,
				'name'  => 'Verdana'
			));
			
			$spreadsheet->getActiveSheet()->getStyle('A1:U1')->applyFromArray($styleArray);
			$spreadsheet->getActiveSheet()->setTitle('FormBuilder');

			$filename = 'FormBuilder.xls';
			$writer =new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
			

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
		$writer->save('php://output');
	}
	

	
}
?>
