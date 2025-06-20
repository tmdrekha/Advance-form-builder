<?php
namespace Opencart\Admin\Controller\Extension\TmdAdvanceformbuilder\Module;
use \Opencart\System\Helper as Helper;
class Tmdformbuilder extends \Opencart\System\Engine\Controller {
	public function index():void {
		$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuilder');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setTitle($this->language->get('heading_title1'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token='.$this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module')
		];

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder', 'user_token='.$this->session->data['user_token'])
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder', 'user_token='.$this->session->data['user_token'].'&module_id='.$this->request->get['module_id'])
			];
		}

		if(VERSION>='4.0.2.0')
		{
			if (!isset($this->request->get['module_id'])) {
				$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder.save', 'user_token='.$this->session->data['user_token']);
			} else {
				$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder.save', 'user_token='.$this->session->data['user_token'].'&module_id='.$this->request->get['module_id']);
			}			
		}
		else{
			if (!isset($this->request->get['module_id'])) {
				$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder|save', 'user_token='.$this->session->data['user_token']);
			} else {
				$data['save'] = $this->url->link('extension/tmdadvanceformbuilder/module/tmdformbuilder|save', 'user_token='.$this->session->data['user_token'].'&module_id='.$this->request->get['module_id']);
			}
		}

		$data['back'] = $this->url->link('marketplace/extension', 'user_token='.$this->session->data['user_token'].'&type=module');

		if (isset($this->request->get['module_id'])) {
			$this->load->model('setting/module');

			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
	
		if (isset($this->request->post['tmdformbuilder_title'])) {
			$data['tmdformbuilder_title'] = $this->request->post['tmdformbuilder_title'];
		} elseif (!empty($module_info)) {
			$data['tmdformbuilder_title'] = $module_info['tmdformbuilder_title'];
		} else {
			$data['tmdformbuilder_title'] = '';
		}

		if (isset($module_info['name'])) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		if (isset($module_info['tmdformbulider_showproduct'])) {
			$data['tmdformbulider_showproduct'] = $module_info['tmdformbulider_showproduct'];
		} else {
			$data['tmdformbulider_showproduct'] = '';
		}

		$filter_data = [];

		$this->load->model('extension/tmdadvanceformbuilder/tmd/form');
		$data['formbuliders'] = [];
		$formbulider_info     = $this->model_extension_tmdadvanceformbuilder_tmd_form->getForms($filter_data);

		foreach ($formbulider_info as $formbulider) {
			$data['formbuliders'][] = [
				'form_id' => $formbulider['form_id'],
				'title'   => $formbulider['title']
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

	
		$this->response->setOutput($this->load->view('extension/tmdadvanceformbuilder/module/tmdformbuilder', $data));
	}

	public function save():void {
		$this->load->language('extension/tmdadvanceformbuilder/module/tmdformbuilder');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/tmdadvanceformbuilder/module/tmdformbuilder')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if(VERSION>='4.0.2.0')
            {
            if ((oc_strlen(trim($this->request->post['name'])) < 3) || (oc_strlen(trim($this->request->post['name'])) > 64)) {
                $json['error']['name'] = $this->language->get('error_name');
            }
        }
        else{
        
            if ((Helper\Utf8\strlen(trim($this->request->post['name'])) < 3) || (Helper\Utf8\strlen(trim($this->request->post['name'])) > 64)) {
                $json['error']['name'] = $this->language->get('error_name');
            }
            
        }

		if (!$json) {
			$this->load->model('setting/module');

			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('tmdadvanceformbuilder.tmdformbuilder', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
