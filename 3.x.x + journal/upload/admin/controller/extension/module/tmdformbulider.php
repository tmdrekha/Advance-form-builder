<?php
class ControllerExtensionModuleTmdFormBulider extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/module/tmdformbulider');
        
        $this->load->model('setting/module');

		$this->document->setTitle($this->language->get('heading_title1'));
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('tmdformbulider', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_showproduct'] = $this->language->get('entry_showproduct');
		$data['entry_formlayout'] = $this->language->get('entry_formlayout');
		$data['entry_name'] = $this->language->get('entry_name');

		$data['text_select'] = $this->language->get('text_select');
		
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
		
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}
        
        if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/module/tmdformbulider', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/tmdformbulider', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/tmdformbulider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

	
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}
        
        if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
        if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '1';
		}
        
		if (isset($this->request->post['tmdformbulider_showproduct'])) {
			$data['tmdformbulider_showproduct'] = $this->request->post['tmdformbulider_showproduct'];
		} elseif (!empty($module_info['tmdformbulider_showproduct'])) {
			$data['tmdformbulider_showproduct'] = $module_info['tmdformbulider_showproduct'];
		} else {
			$data['tmdformbulider_showproduct'] = '';
		}

		if (isset($this->request->post['tmdformbuilder_title'])) {
			$data['tmdformbuilder_title'] = $this->request->post['tmdformbuilder_title'];
		} elseif (!empty($module_info['tmdformbuilder_title'])) {
			$data['tmdformbuilder_title'] = $module_info['tmdformbuilder_title'];
		} else {
			$data['tmdformbuilder_title'] = '';
		}

		
		$filter_data =array();
		$this->load->model('extension/tmd/form');
		$data['formbuliders'] = array();
		$formbulider_info = $this->model_extension_tmd_form->getForms($filter_data);
	
		foreach ($formbulider_info as $formbulider) {			
				$data['formbuliders'][] = array(
					'form_id' => $formbulider['form_id'],
					'title'       => $formbulider['title']
				);			
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tmdformbulider', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tmdformbulider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

}
