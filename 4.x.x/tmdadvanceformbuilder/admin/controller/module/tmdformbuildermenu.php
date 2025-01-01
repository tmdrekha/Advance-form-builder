<?php
namespace Opencart\Admin\Controller\Extension\TmdAdvanceformbuilder\Module;
// Lib Include 
require_once(DIR_EXTENSION.'/tmdadvanceformbuilder/system/library/tmd/system.php');
// Lib Include 
use \Opencart\System\Helper as Helper;
class Tmdformbuildermenu extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->registry->set('tmd', new  \Tmdformbuilder\System\Library\Tmd\System($this->registry));
		$keydata=array(
		'code'=>'tmdkey_tmdformbuilder',
		'eid'=>'NDI1MjM=',
		'route'=>'extension/tmdadvanceformbuilder/module/tmdformbuildermenu',
		);
		$tmdformbuilder=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);
		
		$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuildermenu');

		$this->document->setTitle($this->language->get('heading_title1'));
		
		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuildermenu', 'user_token=' . $this->session->data['user_token'])
		];

		if(VERSION>='4.0.2.0')
        {
			$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuildermenu.save', 'user_token=' . $this->session->data['user_token']);
        }
        else{
			$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuildermenu|save', 'user_token=' . $this->session->data['user_token']);
        }

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		$data['module_tmdformbuildermenu_status'] = $this->config->get('module_tmdformbuildermenu_status');
		
		//SEO 
		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$data['tmdformbulider_seo_url'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSeoUrl('extension/tmdadvanceformbuilder/tmd/form');

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$data['tmdformbulider_seo_url_success'] = $this->model_extension_tmdadvanceformbuilder_tmd_form->getSuccessSeoUrl('extension/tmdadvanceformbuilder/tmd/success');

		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');

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
		//SEO

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/module/tmdformbuildermenu', $data));
	}
	
	public function install():void {
		$this->load->model('extension/tmdadvanceformbuilder/tmd/tmdformbuilder');
		$this->model_extension_tmdadvanceformbuilder_tmd_tmdformbuilder->install();
		
		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/module/tmdformbuilder');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/module/tmdformbuilder');
		
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu');
		
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/captcha/tmdgooglev');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/captcha/tmdgooglev');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/form');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/form');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/filedrecord');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/filedrecord');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/record');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/record');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/tmdformrecord');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/tmdformrecord');

		//TMD Form Builder Dashboard events Menu
        if(VERSION>='4.0.2.0')
        {
            $eventaction='extension/tmdadvanceformbuilder/module/tmdformbuildermenu.menu';
        }
        else{
            $eventaction='extension/tmdadvanceformbuilder/module/tmdformbuildermenu|menu';
        }
        $this->model_setting_event->deleteEventByCode('tmd_moduleformbuilder');
        $eventrequest=[
                    'code'=>'tmd_moduleformbuilder',
                    'description'=>'TMD Form Builder Dashboard',
                    'trigger'=>'admin/view/common/column_left/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0')
        {
        $this->model_setting_event->addEvent('tmd_moduleformbuilder', 'TMD Form Builder Dashboard', 'admin/view/common/column_left/before', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu|menu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// Add Footer code in Front
        if(VERSION>='4.0.2.0')
        {
            $eventaction='extension/tmdadvanceformbuilder/tmd/form.advanceformbuilderfooter';
        }
        else{
            $eventaction='extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderfooter';
        }
        $this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderfooter');
        $eventrequest=[
                    'code'=>'tmd_advanceformbuilderfooter',
                    'description'=>'TMD formbuilder footer',
                    'trigger'=>'catalog/view/common/footer/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0')
        {
        	$this->model_setting_event->addEvent('tmd_advanceformbuilderfooter', 'TMD formbuilder footer', 'catalog/view/common/footer/before', 'extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderfooter', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// Add Information code in Front
        if(VERSION>='4.0.2.0')
        {
            $eventaction='extension/tmdadvanceformbuilder/tmd/form.advanceformbuilderinformation';
        }
        else{
            $eventaction='extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderinformation';
        }
        $this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderinformation');
        $eventrequest=[
                    'code'=>'tmd_advanceformbuilderinformation',
                    'description'=>'TMD formbuilder information',
                    'trigger'=>'catalog/view/information/information/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0')
        {
       $this->model_setting_event->addEvent('tmd_advanceformbuilderinformation', 'TMD formbuilder information', 'catalog/view/information/information/before', 'extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderinformation', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// Add Product code in Front
        if(VERSION>='4.0.2.0')
        {
            $eventaction='extension/tmdadvanceformbuilder/tmd/form.advanceformbuilderproduct';
        }
        else{
            $eventaction='extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderproduct';
        }
        $this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderproduct');
        $eventrequest=[
                    'code'=>'tmd_advanceformbuilderproduct',
                    'description'=>'TMD formbuilder product',
                    'trigger'=>'catalog/view/product/product/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0')
        {
      $this->model_setting_event->addEvent('tmd_advanceformbuilderproduct', 'TMD formbuilder product', 'catalog/view/product/product/before', 'extension/tmdadvanceformbuilder/tmd/form|advanceformbuilderproduct', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// Add header menu in Front
        if(VERSION>='4.0.2.0')
        {
            $eventaction='extension/tmdadvanceformbuilder/tmd/form.advanceformbuildermenu';
        }
        else{
            $eventaction='extension/tmdadvanceformbuilder/tmd/form|advanceformbuildermenu';
        }
        $this->model_setting_event->deleteEventByCode('tmd_advanceformbuildermenu');
        $eventrequest=[
                    'code'=>'tmd_advanceformbuildermenu',
                    'description'=>'TMD formbuilder header menu',
                    'trigger'=>'catalog/view/common/menu/before',
                    'action'=>$eventaction,
                    'status'=>'1',
                    'sort_order'=>'1',
                ];
                
        if(VERSION=='4.0.0.0')
        {
      $this->model_setting_event->addEvent('tmd_advanceformbuildermenu', 'TMD formbuilder header menu', 'catalog/view/common/menu/before', 'extension/tmdadvanceformbuilder/tmd/form|advanceformbuildermenu', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// TMD catalog order events
		$this->model_setting_event->deleteEventByCode('module_advanceformbuilderorder');

		if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdadvanceformbuilder/tmd/form.addHistory';
		}
		else{
			$eventaction='extension/tmdadvanceformbuilder/tmd/form|addHistory';
		}

		$eventrequest=[
			'code'=>'module_advanceformbuilderorder',
			'description'=>'TMD Advanceformbuilder Order',
			'trigger'=>'catalog/model/checkout/order/addHistory/before',
			'action'=>$eventaction,
			'status'=>'1',
			'sort_order'=>'1',
		];

		if(VERSION=='4.0.0.0')
		{
			$this->model_setting_event->addEvent('module_advanceformbuilderorder', 'TMD Advanceformbuilder Order','catalog/model/checkout/order/addHistory/before', 'extension/tmdadvanceformbuilder/tmd/form|addHistory', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		// TMD admin order events
			$this->model_setting_event->deleteEventByCode('module_advanceformbuilderadminorder');

		if(VERSION>='4.0.2.0'){
			$eventaction='extension/tmdadvanceformbuilder/module/tmdformbuildermenu.advanceformbuilderadminorder';
		}
		else{
			$eventaction='extension/tmdadvanceformbuilder/module/tmdformbuildermenu|advanceformbuilderadminorder';
		}

		$eventrequest=[
			'code'=>'module_advanceformbuilderadminorder',
			'description'=>'TMD Advanceformbuilder Adminorder',
			'trigger'=>'admin/view/sale/order_list/before',
			'action'=>$eventaction,
			'status'=>'1',
			'sort_order'=>'1',
		];

		if(VERSION=='4.0.0.0')
		{
			$this->model_setting_event->addEvent('module_advanceformbuilderadminorder', 'TMD Advanceformbuilder Adminorder','admin/view/sale/order_list/before/before', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu|advanceformbuilderadminorder', true, 1);
		}else{
			$this->model_setting_event->addEvent($eventrequest);
		}

		/// Add startup to catalog
	    $startup_data = [
	        'code'    => 'module_tmdformbuilder',
	        'description' => 'Tmdadvanceformbuilder extension',
	         'action'   => 'catalog/extension/tmdadvanceformbuilder/startup/tmdcart',
	        'status'   => 1,
	        'sort_order' => 2
	    ];

		// Add startup for admin
		$this->load->model('setting/startup');
		$this->model_setting_startup->addStartup($startup_data);

	}

	public function uninstall():void {

		$this->load->model('extension/tmdadvanceformbuilder/tmd/tmdformbuilder');
		$this->model_extension_tmdadvanceformbuilder_tmd_tmdformbuilder->uninstall();

		// TMD Account Dashboard events
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('tmd_moduleformbuilder');
		$this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderfooter');
		$this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderinformation');

		$this->model_setting_event->deleteEventByCode('tmd_advanceformbuilderproduct');
		$this->model_setting_event->deleteEventByCode('tmd_advanceformbuildermenu');
		$this->model_setting_event->deleteEventByCode('module_advanceformbuilderorder');
		$this->model_setting_event->deleteEventByCode('module_advanceformbuilderadminorder');

		// Fix permissions
		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/module/tmdformbuilder');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/module/tmdformbuilder');
		
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu');
		
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/captcha/tmdgooglev');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/captcha/tmdgooglev');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/form');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/form');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/filedrecord');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/filedrecord');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/record');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/record');

		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/tmdadvanceformbuilder/tmd/tmdformrecord');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/tmdadvanceformbuilder/tmd/tmdformrecord');

	}

	public function menu(string&$route, array&$args, mixed&$output):void {
		$modulestatus=$this->config->get('module_tmdformbuildermenu_status');
		
		if(!empty($modulestatus)){
			$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuildermenu');

			$this->load->model('setting/module');

			$tmdformbuilder = [];

			if ($this->user->hasPermission('access', 'extension/tmdadvanceformbuilder/tmd/form')) {
				$tmdformbuilder[] = [
					'name'     => $this->language->get('entry_form'),
					'href'     => $this->url->link('extension/tmdadvanceformbuilder/tmd/form', 'user_token='.$this->session->data['user_token']),
					'children' => []
				];
			}

			if ($this->user->hasPermission('access', 'extension/tmdadvanceformbuilder/tmd/record')) {
				$tmdformbuilder[] = [
					'name'     => $this->language->get('entry_all_form'),
					'href'     => $this->url->link('extension/tmdadvanceformbuilder/tmd/record', 'user_token='.$this->session->data['user_token']),
					'children' => []
				];
			}

			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
			$form_infos = $this->model_extension_tmdadvanceformbuilder_tmd_form->getHeaderlinks();
			if (isset($form_infos)) {
				foreach ($form_infos as $form_info) {

					if ($this->user->hasPermission('access', 'extension/tmdadvanceformbuilder/tmd/record')) {
						$tmdformbuilder[] = [
							'name'     => $form_info['title'],
							'href'     => $this->url->link('extension/tmdadvanceformbuilder/tmd/filedrecord', 'user_token='.$this->session->data['user_token'].'&form_id='.$form_info['form_id'].'&filter_title='.$form_info['form_id'], true),
							'children' => []
						];
					}
				}
			}

			if ($tmdformbuilder) {
				$args['menus'][] = [
					'id'       => 'menu-extension',
					'icon'     => 'fa-solid fa-file',
					'name'     => $this->language->get('entry_formmenu'),
					'href'     => '',
					'children' => $tmdformbuilder
				];
			}
		}
	}

	public function advanceformbuilderadminorder(string&$route, array&$args, mixed&$output):void {


			if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = (int)$this->request->get['filter_order_id'];
			} else {
			$filter_order_id = '';
			}

			if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
			} else {
			$filter_customer_id = '';
			}

			if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
			} else {
			$filter_customer = '';
			}

			if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
			} else {
			$filter_store_id = '';
			}

			if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
			} else {
			$filter_order_status = '';
			}

			if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
			} else {
			$filter_order_status_id = '';
			}

			if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
			} else {
			$filter_total = '';
			}

			if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
			} else {
			$filter_date_from = '';
			}

			if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
			} else {
			$filter_date_to = '';
			}

			if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			} else {
			$sort = 'o.order_id';
			}

			if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
			} else {
			$order = 'DESC';
			}

			if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
			} else {
			$page = 1;
			}

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
			}

			if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
			}

			if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
			}

			if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
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
			$args['orders'] = [];

			$filter_data = [
			'filter_order_id'        => $filter_order_id,
			'filter_customer_id'     => $filter_customer_id,
			'filter_customer'        => $filter_customer,
			'filter_store_id'        => $filter_store_id,
			'filter_order_status'    => $filter_order_status,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_from'       => $filter_date_from,
			'filter_date_to'         => $filter_date_to,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * (int)$this->config->get('config_pagination_admin'),
			'limit'                  => (int)$this->config->get('config_pagination_admin')
			];

			$this->load->model('sale/order');

			$order_total = $this->model_sale_order->getTotalOrders($filter_data);

			$results = $this->model_sale_order->getOrders($filter_data);

			foreach ($results as $result) {

			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');

			/* Form Builder */
			$form_info = $this->model_extension_tmdadvanceformbuilder_tmd_form->getOrderForm($result['order_id']);
			if(isset($form_info['fs_id'])) {
			$fs_id = $form_info['fs_id'];
			} else {
			$fs_id =0;
			}
			if(VERSION >='4.0.2.0'){
				$args['orders'][] = [
					'order_id'        => $result['order_id'],
					/* Form Builder */
					'fs_id'      => $fs_id,
					'formview'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/tmdformrecord', 'user_token=' . $this->session->data['user_token'] . '&fs_id=' . $fs_id . $url, true),
					/* Form Builder */
					'store_name'      => $result['store_name'],
					'customer'        => $result['customer'],
					'order_status'    => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
					'total'           => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'date_modified'   => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
					'shipping_method' => $result['shipping_method'],
					'view'            => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url)
				];
			}else{
				/* Form Builder */
				$args['orders'][] = [
				'order_id'      => $result['order_id'],
				/* Form Builder */
				'fs_id'      => $fs_id,
				'formview'   => $this->url->link('extension/tmdadvanceformbuilder/tmd/tmdformrecord', 'user_token=' . $this->session->data['user_token'] . '&fs_id=' . $fs_id . $url, true),
				/* Form Builder */
				'store_name'    => $result['store_name'],
				'customer'      => $result['customer'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url)
				];
			}


			$template_buffer = $this->getTemplateBuffer($route,$output);

			$find='<td class="text-end"><a href="{{ order.view }}" data-bs-toggle="tooltip" title="{{ button_view }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a></td>';
			$replace='<td class="text-end"><a href="{{ order.view }}" data-bs-toggle="tooltip" title="{{ button_view }}" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a> <!--Form Builder-->
			{% if order.fs_id %}
			<a href="{{ order.formview }}" data-toggle="tooltip" title="Form View" class="btn btn-info"><i class="far fa-copy fa-flip-horizontal"></i></a>
			{% endif %}<!--Form Builder--></td>';
			$output = str_replace( $find, $replace, $template_buffer );
			}
	}
	
	public function save(): void {
		$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuildermenu');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdadvanceformbuilder/module/tmdformbuildermenu')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$tmdformbuilder=$this->config->get('tmdkey_tmdformbuilder');
		if (empty(trim($tmdformbuilder))) {			
		$json['error'] ='Module will Work after add License key!';
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('module_tmdformbuildermenu', $this->request->post);
			
			//SEO
			$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
			
			$this->request->post['urlformat']='tmdformbulider_seo_url';
			$this->model_extension_tmdadvanceformbuilder_tmd_form->saveSeoUrls($this->request->post,'extension/tmdadvanceformbuilder/tmd/form');


			$this->request->post['urlsuccessformat']='tmdformbulider_seo_url_success';
			$this->model_extension_tmdadvanceformbuilder_tmd_form->saveSuccessSeoUrls($this->request->post,'extension/tmdadvanceformbuilder/tmd/success');

			//SEO

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdformbuilder',
			'eid'=>'NDI1MjM=',
			'route'=>'extension/tmdadvanceformbuilder/module/tmdformbuildermenu',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new  \Tmdformbuilder\System\Library\Tmd\System($this->registry));
		
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
		if ($this->isAdmin()) {
		trigger_error("Cannot find template file for route '$route'");
		exit;
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
