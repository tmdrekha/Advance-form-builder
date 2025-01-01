<?php
require_once (DIR_SYSTEM . '/library/tmd/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
class ControllerExtensionTMDTmdformrecord extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/tmd/tmdformrecord');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('extension/tmd/form');
		$this->load->model('extension/tmd/record');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');

		$this->getForm();
	}

	public function getForm() {
		if(isset($this->session->data['token'])){
			$tokenchange = 'token=' . $this->session->data['token'];
		} else {
			$tokenchange =  'user_token=' . $this->session->data['user_token'];
		}

		if (isset($this->request->get['fs_id'])) {
			$fs_id = $this->request->get['fs_id'];
		} else {
			$fs_id = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_detail'] = $this->language->get('text_detail');
		$data['text_custdetail'] = $this->language->get('text_custdetail');
		$data['text_fields'] = $this->language->get('text_fields');
		
		$data['entry_fieldname'] = $this->language->get('entry_fieldname');
		$data['entry_fieldvalue'] = $this->language->get('entry_fieldvalue');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_pdf'] = $this->language->get('button_pdf');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title1'),
			'href' => $this->url->link('extension/tmd/tmdformrecord', $tokenchange . $url, true)
		);

		$data['pdfgenrate'] = $this->url->link('extension/tmd/tmdformrecord/Pdf', $tokenchange . '&fs_id=' . $this->request->get['fs_id'], true);
		

		if(isset($this->request->get['fs_id'])){
			$tmdrecord_info = $this->model_extension_tmd_record->getTmdRecord($this->request->get['fs_id']);
		}


		if(!empty($tmdrecord_info['form_id'])) {
			$form_id = $tmdrecord_info['form_id'];
		} else {
			$form_id = 0;
		}
		
		if(isset($this->request->get['filter_title'])){
			$data['cancel'] = $this->url->link('extension/tmd/filedrecord', $tokenchange . '&form_id=' . $tmdrecord_info['form_id'] . '&filter_title=' . $this->request->get['filter_title'] , true);
		}else{			
			$data['cancel'] = $this->url->link('extension/tmd/record', $tokenchange . $url, true);
		}

		if(isset($tmdrecord_info['language_id'])){
			$language_info = $this->model_localisation_language->getLanguage($tmdrecord_info['language_id']);
		}

		if(isset($language_info['name'])){
			$lname = $language_info['name'];
		} else {
			$lname ='';
		}

		if(isset($tmdrecord_info['store_id'])){
			$store_info = $this->model_setting_store->getStore($tmdrecord_info['store_id']);
		}

		if(isset($store_info['name'])){
			$sname = $store_info['name'];
		} else {
			$sname ='Default';
		}

		$data['sname'] 		= $sname;
		$data['lname'] 		= $lname;

		if(!empty($tmdrecord_info['customer_id'])){
			$data['customer_name']= $tmdrecord_info['customer_name'];
		} else{
			$data['customer_name']='';
		}

		if(isset($tmdrecord_info['form_id'])){
			$title_name = $this->model_extension_tmd_record->getTmdRecordname($tmdrecord_info['form_id'],$tmdrecord_info['language_id']);
		}


		if(isset($title_name['title'])){
			$data['title'] 		= $title_name['title'];
		} else {
			$data['title']='';
		}

		if(isset($tmdrecord_info['ip'])){
			$data['ip'] 		= $tmdrecord_info['ip'];
		} else {
			$data['ip'] ='';
		}

		if(isset($tmdrecord_info['user_agent'])){
			$data['user_agent'] = (substr($tmdrecord_info['user_agent'], 0, 45));
		} else {
			$data['user_agent'] = '';
		}

		if(isset($tmdrecord_info['date_added'])){
			$data['date_added'] = $tmdrecord_info['date_added'];
		} else {
			$data['date_added'] ='';
		}
		
		$data['field_infos'] = array();
		$filter_data=array(
			'fs_id'=>$fs_id
		);
				
		$field_infos = $this->model_extension_tmd_record->getTmdFieldRecord($filter_data);
		
		foreach($field_infos as $fieldinfo){			
			if($fieldinfo['serialize']){
				$fieldinfos=unserialize($fieldinfo['value']);			
						
				$value='';
				foreach($fieldinfos as $field){
				
				
					$value .=$field.',';
				}
				$fieldinfo['value']=$value;
			}
			
				$this->load->model('tool/upload');
				$type = $this->model_extension_tmd_form->getFieldType($fieldinfo['field_id']);
				
				if($type=='file') {
				 $upload_info = $this->model_tool_upload->getUploadByCode($fieldinfo['value']);
					$fieldinfo['value']='';
					if(isset($upload_info['name']))
					{
					$fieldinfo['value']='<a style="color:#1e91cf" href="'.$this->url->link('tool/upload/download', $tokenchange . '&code=' . $upload_info['code'], true).'">'.$upload_info['name'].'</a>'; 
					}
				}
			

			$country_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='country' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;

			if(!empty($country_id)) {
				$this->load->model('localisation/country');
				$country_info = $this->model_localisation_country->getCountry($country_id['value']);
				if(!empty($country_info['name'])) {
					$fieldinfo['value'] = $country_info['name'];
				}
			}

			$zone_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='zone' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;
			if(!empty($zone_id)) {
				$this->load->model('localisation/zone');
				$zone_info = $this->model_localisation_zone->getZone($zone_id['value']);
				if(!empty($zone_info['name'])) {
					$fieldinfo['value'] = $zone_info['name'];
				}
			}


			$data['field_infos'][] = array(				
				'label'     => $fieldinfo['label'],
				'value'     => $fieldinfo['value']
				
			);

		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/tmd/tmdformrecord_form', $data));
	}
	
	public function Pdf() {
		$this->load->language('extension/tmd/tmdformrecord');
		
		$this->load->model('extension/tmd/form');
		$this->load->model('extension/tmd/record');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');


		if(isset($this->session->data['token'])){
			$tokenchange = 'token=' . $this->session->data['token'];
		} else {
			$tokenchange =  'user_token=' . $this->session->data['user_token'];
		}

		if (isset($this->request->get['fs_id'])) {
			$fs_id = $this->request->get['fs_id'];
		} else {
			$fs_id = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_detail'] = $this->language->get('text_detail');
		$data['text_custdetail'] = $this->language->get('text_custdetail');
		$data['text_fields'] = $this->language->get('text_fields');
		
		$data['entry_fieldname'] = $this->language->get('entry_fieldname');
		$data['entry_fieldvalue'] = $this->language->get('entry_fieldvalue');

		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenchange, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/tmd/tmdformrecord', $tokenchange . $url, true)
		);

		if (isset($this->request->get['fs_id'])) {
			$fs_id = $this->request->get['fs_id'];
		} else {
			$fs_id = '';
		}

		$data['cancel'] = $this->url->link('extension/tmd/record', $tokenchange . $url, true);

		if(isset($this->request->get['fs_id'])){
			$tmdrecord_info = $this->model_extension_tmd_record->getTmdRecord($this->request->get['fs_id']);
		}

		if(!empty($tmdrecord_info['form_id'])) {
			$form_id = $tmdrecord_info['form_id'];
		} else {
			$form_id = 0;
		}

		if(isset($tmdrecord_info['language_id'])){
			$language_info = $this->model_localisation_language->getLanguage($tmdrecord_info['language_id']);
		}

		if(isset($language_info['name'])){
			$lname = $language_info['name'];
		} else {
			$lname ='';
		}

		if(isset($tmdrecord_info['store_id'])){
			$store_info = $this->model_setting_store->getStore($tmdrecord_info['store_id']);
		}

		if(isset($store_info['name'])){
			$sname = $store_info['name'];
		} else {
			$sname ='Default';
		}
		
		$data['sname'] 		= $sname;
		$data['lname'] 		= $lname;

		if(!empty($tmdrecord_info['customer_id'])){
			$data['customer_name']= $tmdrecord_info['customer_name'];
		} else{
			$data['customer_name']='';
		}

		if(isset($tmdrecord_info['form_id'])){
			$title_name = $this->model_extension_tmd_record->getTmdRecordname($tmdrecord_info['form_id'],$tmdrecord_info['language_id']);
		}

		if(isset($title_name['title'])){
			$data['title'] 		= $title_name['title'];
		} else {
			$data['title']='';
		}
		
		if(isset($tmdrecord_info['ip'])){
			$data['ip'] 		= $tmdrecord_info['ip'];
		} else {
			$data['ip'] ='';
		}

		if(isset($tmdrecord_info['user_agent'])){
			$data['user_agent'] = (substr($tmdrecord_info['user_agent'], 0, 45));
		} else {
			$data['user_agent'] = '';
		}

		if(isset($tmdrecord_info['date_added'])){
			$data['date_added'] = $tmdrecord_info['date_added'];
		} else {
			$data['date_added'] ='';
		}
		
		$data['field_infos'] = array();
		$filter_data=array(
			'fs_id'=>$fs_id
		);
				
		$field_infos = $this->model_extension_tmd_record->getTmdFieldRecord($filter_data);
		
		foreach($field_infos as $fieldinfo){			
			if($fieldinfo['serialize']){
				$fieldinfos=unserialize($fieldinfo['value']);			
						
				$value='';
				foreach($fieldinfos as $field){
					$value .=$field.',';
				}
				$fieldinfo['value']=$value;
			}
			
				$this->load->model('tool/upload');
				$type = $this->model_extension_tmd_form->getFieldType($fieldinfo['field_id']);
				
				if($type=='file') {
				 $upload_info = $this->model_tool_upload->getUploadByCode($fieldinfo['value']);
					$fieldinfo['value']='';
					if(isset($upload_info['name']))
					{
					$fieldinfo['value']='<a style="color:#1e91cf" href="'.$this->url->link('tool/upload/download', $tokenchange . '&code=' . $upload_info['code'], true).'">'.$upload_info['name'].'</a>'; 
					}
				}

				$country_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='country' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;

				if(!empty($country_id)) {
					$this->load->model('localisation/country');
					$country_info = $this->model_localisation_country->getCountry($country_id['value']);
					if(!empty($country_info['name'])) {
						$fieldinfo['value'] = $country_info['name'];
					}
				}

				$zone_id = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit ts LEFT JOIN " . DB_PREFIX . "tmdform_field tf ON (ts.field_id = tf.field_id) WHERE tf.type='zone' AND tf.form_id ='".(int)$form_id."' AND ts.fs_id ='".(int)$fs_id."' AND ts.field_id ='".(int)$fieldinfo['field_id']."'")->row;
				if(!empty($zone_id)) {
					$this->load->model('localisation/zone');
					$zone_info = $this->model_localisation_zone->getZone($zone_id['value']);
					if(!empty($zone_info['name'])) {
						$fieldinfo['value'] = $zone_info['name'];
					}
				}
			
			$data['field_infos'][] = array(				
				'label'     =>$fieldinfo['label'],
				'value'     =>$fieldinfo['value']
				
			);
			
		}
		
		$pdf  = $this->load->view('extension/tmd/tmdpdfrecord', $data);
		$data=html_entity_decode($pdf,ENT_QUOTES, 'UTF-8');
        $data=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);

        $data=str_replace(HTTP_SERVER .'image/',DIR_IMAGE,$data);
        $data='<html><head>
		<script type="text/javascript" src="view/javascript/jquery/jquery-3.7.0.min.js"></script>
		<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
		<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
		<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
        <style type="text/css">@page{margin:0px;size:A4;padding:10px;}
        *{
        padding: 0;
        margin: 0;
		}
		
		#form-tmdformrecord {
			font-size:14px;
		}
		#form-tmdformrecord .row {
			clear:both;
		}
		
		#form-tmdformrecord .panel {
			margin: 0 0 15px;
		}
		#form-tmdformrecord .panel-default .panel-heading .panel-title {
			font-size: 15px;
		}
		#form-tmdformrecord .row .col-sm-6 {
			width:46.3%;
			float:left;
		}
		#form-tmdformrecord table{
			width:100%;
			border-collapse:collapse;
		}
		#form-tmdformrecord .col-sm-12 {
			width:95%;
		}
		#form-tmdformrecord .fields .table-responsive, #form-tmdformrecord .padd15 {
			padding:0 !important;
		}
		#form-tmdformrecord table tr td{
			border: 1px solid #ddd;
			padding:5px;
		}
		#form-tmdformrecord .row {
			margin:0 -10px ;
		}
		#form-tmdformrecord .row .col-sm-12, #form-tmdformrecord .row .col-sm-6 {
			padding:0 10px !important;
		}
		#form-tmdformrecord .panel-default .panel-heading{
			padding:5px !important;
			border:1px solid #ddd;
			margin-bottom:-1px;
			background-color: #fafafa;
		}
        </style></head><body><div style="position: relative;width:100%;overflow: hidden;padding:10px;>'.$data.'</div></body></html>';
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($data);
        $dompdf->set_option('isHtml5ParserEnabled', true);
    
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $file_pdf         = DIR_CACHE.'/tmd.pdf';

        $file_pdf_name     = 'pdf'.$fs_id.'.pdf';

        $pdf_string     = $dompdf->output();
        

         file_put_contents($file_pdf, $pdf_string);

         $mask = '';
         header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . urlencode($mask ? $mask : basename($file_pdf)) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_pdf));
        readfile($file_pdf);
		
	}
	

}
