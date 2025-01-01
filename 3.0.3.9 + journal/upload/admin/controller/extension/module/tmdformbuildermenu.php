<?php
//lib
require_once(DIR_SYSTEM.'library/tmd/system.php');
//lib
class ControllerExtensionModuleTmdformbuildermenu extends Controller {
	private $error = array();

	public function install() {
		$this->load->model('extension/tmd/tmdformbulider');
		$this->model_extension_tmd_tmdformbulider->install();
	}	
	public function uninstall() {
		$this->load->model('extension/tmd/tmdformbulider');
		$this->model_extension_tmd_tmdformbulider->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/tmdformbuildermenu');

		$this->registry->set('tmd', new TMD($this->registry));
		$keydata=array(
			'code'=>'tmdkey_tmdformbulider',
			'eid'=>'NDI1MjM=',
			'route'=>'extension/module/tmdformbuildermenu',
		);
		$tmdformbulider=$this->tmd->getkey($keydata['code']);
		$data['getkeyform']=$this->tmd->loadkeyform($keydata);

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_tmdformbuildermenu', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/tmdformbuildermenu', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/tmdformbuildermenu', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


		if (isset($this->request->post['module_tmdformbuildermenu_status'])) {
			$data['module_tmdformbuildermenu_status'] = $this->request->post['module_tmdformbuildermenu_status'];
		} else {
			$data['module_tmdformbuildermenu_status'] = $this->config->get('module_tmdformbuildermenu_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tmdformbuildermenu', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tmdformbuildermenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$tmdformbulider=$this->config->get('tmdkey_tmdformbulider');
		if (empty(trim($tmdformbulider))) {			
		$this->session->data['warning'] ='Module will Work after add License key!';
		$this->response->redirect($this->url->link('extension/module/tmdformbulider', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		return !$this->error;
	}

	public function keysubmit() {
		$json = array(); 
		
      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$keydata=array(
			'code'=>'tmdkey_tmdformbulider',
			'eid'=>'NDI1MjM=',
			'route'=>'extension/module/tmdformbuildermenu',
			'moduledata_key'=>$this->request->post['moduledata_key'],
			);
			$this->registry->set('tmd', new TMD($this->registry));
            $json=$this->tmd->matchkey($keydata);       
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}