<?php
namespace Opencart\Admin\Model\Extension\TmdAdvanceFormbuilder\Tmd;
class Form extends \Opencart\System\Engine\Model {
	private function tablesname() {
		$tablesname=[];
		$tablesname[]="tmdform_customergroup";
		$tablesname[]="tmdform";
		$tablesname[]="tmdform_information";
		$tablesname[]="tmdform_success";
		$tablesname[]="tmdform_store";
		$tablesname[]="tmdform_manufacturer";
		$tablesname[]="tmdform_category";
		$tablesname[]="tmdform_product";
		$tablesname[]="tmdform_notification";
		$tablesname[]="tmdform_field_option";
		$tablesname[]="tmdform_field_option_base";
		$tablesname[]="tmdform_field_description";
		$tablesname[]="tmdform_field";
		$tablesname[]="tmdform_description";
		$tablesname[]="tmdform_submit";
		return $tablesname;
	}
	
 	public function addForm($data): int {


		$sql="INSERT INTO " . DB_PREFIX . "tmdform set headerlink = '" . (isset($data['headerlink']) ? (int)$data['headerlink'] : 0) . "',footerlink = '" . (isset($data['footerlink']) ? (int)$data['footerlink'] : 0) . "', productwidth='".$this->db->escape($data['productwidth'])."', productheight='".$this->db->escape($data['productheight'])."', custome_style='".$this->db->escape($data['custome_style'])."', captcha='".(int)$data['captcha']."', resetbutton='".(int)$data['resetbutton']."', display_type='".$data['display_type']."',assign_product='".$data['assign_product']."',status='".(int)$data['status']."',	product_id = '" . (int)$data['product_id'] . "', price='".(float)$data['price']."', bgcolor='".$this->db->escape($data['bgcolor'])."',txtcolor='".$this->db->escape($data['txtcolor'])."',submit_bgcolor='".$this->db->escape($data['submit_bgcolor'])."',submit_txtcolor='".$this->db->escape($data['submit_txtcolor'])."',reset_bgcolor='".$this->db->escape($data['reset_bgcolor'])."', reset_txtcolor='".$this->db->escape($data['reset_txtcolor'])."', date_added=now()";
		$this->db->query($sql);
		$form_id = $this->db->getLastId();
		
		if (isset($data['form_description'])) {
			foreach ($data['form_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_description SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',title='".$this->db->escape($value['title'])."', button_name='".$this->db->escape($value['button_name'])."', resetbuttonname='".$this->db->escape($value['resetbuttonname'])."',uplaodfilebtn='".$this->db->escape($value['uplaodfilebtn'])."', popuplinkname='".$this->db->escape($value['popuplinkname'])."',  meta_title='".$this->db->escape($value['meta_title'])."', meta_description='".$this->db->escape($value['meta_description'])."',meta_keyword='".$this->db->escape($value['meta_keyword'])."',top_description='".$this->db->escape($value['top_description'])."',bottom_description='".$this->db->escape($value['bottom_description'])."'"); 
			}
		}
		
		if (!empty($data['form_store'])) {
			foreach ($data['form_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_store SET form_id = '" . (int)$form_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		if (!empty($data['form_customer'])) {
			foreach ($data['form_customer'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_customergroup SET form_id = '" . (int)$form_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
		
		if(!empty($data['succes_form'])) {
			foreach ($data['succes_form'] as $language_id => $success) {
				$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_success SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."', success_title='".$this->db->escape($success['success_title'])."', success_meta_title='".$this->db->escape($success['success_meta_title'])."', success_description='".$this->db->escape($success['success_description'])."'"); 
			}
		}
		
		if(!empty($data['form_notification'])) {
			foreach ($data['form_notification'] as $language_id => $notification) {
				$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_notification SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',customer_notification='".(int)$notification['customer_notification']."', admin_notification='".(int)$notification['admin_notification']."',customer_subject='".$this->db->escape($notification['customer_subject'])."',customer_message='".$this->db->escape($notification['customer_message'])."', admin_subject='".$this->db->escape($notification['admin_subject'])."', admin_message='".$this->db->escape($notification['admin_message'])."'"); 
			}
		}

		if (!empty($data['form_product'])) {
		    if (!is_array($data['form_product'])) {
		        $data['form_product'] = [$data['form_product']]; // Convert single value to array
		    }

		    foreach ($data['form_product'] as $product_id) {
		        $this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_product 
		            SET form_id = '" . (int) $form_id . "', 
		            productform_id = '" . $this->db->escape($product_id) . "'");
		    }
		}

		
		if (!empty($data['form_category'])){
		   foreach ($data['form_category'] as $category_id){
			 	$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_category SET  form_id = '" . (int) $form_id . "', category_id = '" . $this->db->escape($category_id). "'");
		   }
		}
		
		if (!empty($data['form_manufacturer'])){
		   foreach ($data['form_manufacturer'] as $manufacturer_id){
			 	$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_manufacturer SET  form_id = '" . (int) $form_id . "', manufacturer_id = '" . $this->db->escape($manufacturer_id). "'");
		   }
		}

		if (!empty($data['information'])){
		   foreach ($data['information'] as $information_id){
			 	$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_information SET  form_id = '" . (int) $form_id . "', information_id = '" . $this->db->escape($information_id). "'");
		   }
		}				
		
		if (isset($data['option_fields'])){
		   foreach ($data['option_fields'] as $fields){
			   if(!empty($fields['type'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_field SET form_id='".(int)$form_id."',form_status = '" .$fields['form_status']. "', required = '".$fields['required']. "', sort_order='".(int)$fields['sort_order']."', type='".$fields['type']."'");

					$field_id = $this->db->getLastId();

				   if(isset($fields['form_fields'])) {
						foreach ($fields['form_fields'] as $language_id => $form) {
							$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_field_description SET field_id ='" . (int)$field_id . "',form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',field_name='".$this->db->escape($form['field_name'])."',help_text='".$this->db->escape($form['help_text'])."',placeholder='".$this->db->escape($form['placeholder'])."',error_message='".$this->db->escape($form['error_message'])."'"); 
						}
					}
					if(isset($fields['option_type'])) {
						foreach ($fields['option_type'] as $option_description) {


							$baseoption=$this->db->query("INSERT INTO  " .DB_PREFIX . "tmdform_field_option_base set field_id ='" . (int)$field_id . "' , form_id ='" . (int)$form_id . "'");
							$optiobaseid=$this->db->getLastId();
							
							if(isset($option_description['option_value_description'])) {
								foreach ($option_description['option_value_description'] as $language_id => $option_value_description) {							
									$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_field_option SET field_id ='" . (int)$field_id . "',form_id ='" . (int)$form_id . "',optiobaseid='".$optiobaseid."',language_id = '" . (int)$language_id ."',name='".$option_value_description['name']."',sort_order='".$option_description['sort_order']."',price_prefix='".$option_description['price_prefix']."',price='".$option_description['price']."',image='".$option_description['image']."'"); 
									
								}
							}
						}
					}
				}		  
		   	}
		}

		// SEO URL
		if (isset($data['form_seo_url'])) {
			foreach ($data['form_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'form_id', `value` = '" . (int)$form_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		if (!empty($data['form_success_page_seo'])) {
			foreach ($data['form_success_page_seo'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'form_success', `value` = '" . (int)$form_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		return $form_id;
	}   


	public function getFormProductes(int $form_id): array {
		$form_product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tmdform_product` WHERE `form_id` = '" . (int)$form_id . "'");

		foreach ($query->rows as $result) {
			$form_product_data[] = $result['productform_id'];
		}

		return $form_product_data;
	}

	public function getFormCategories(int $form_id): array {
		$form_category_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tmdform_category` WHERE `form_id` = '" . (int)$form_id . "'");

		foreach ($query->rows as $result) {
			$form_category_data[] = $result['category_id'];
		}

		return $form_category_data;
	}

	public function getFormManufacturer(int $form_id): array {
		$form_manufacturer_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tmdform_manufacturer` WHERE `form_id` = '" . (int)$form_id . "'");

		foreach ($query->rows as $result) {
			$form_manufacturer_data[] = $result['manufacturer_id'];
		}

		return $form_manufacturer_data;
	}


 	public function editForm($form_id,$data) {

		$sql = "UPDATE " . DB_PREFIX . "tmdform SET `headerlink` = '" . (isset($data['headerlink']) ? (int)$data['headerlink'] : 0) . "',`footerlink` = '" . (isset($data['footerlink']) ? (int)$data['footerlink'] : 0) . "',`productwidth` = '" . $this->db->escape($data['productwidth']) . "',`productheight` = '" . $this->db->escape($data['productheight']) . "',`custome_style` = '" . $this->db->escape($data['custome_style']) . "',`captcha` = '" . (int)$data['captcha'] . "',`resetbutton` = '" . (int)$data['resetbutton'] . "',
        `display_type` = '" . $this->db->escape($data['display_type']) . "',`assign_product` = '" . $this->db->escape($data['assign_product']) . "',`status` = '" . (int)$data['status'] . "',`product_id` = '" . (int)$data['product_id'] . "',`price`='".(float)$data['price']."',`bgcolor` = '" . $this->db->escape($data['bgcolor']) . "',`txtcolor` = '" . $this->db->escape($data['txtcolor']) . "',`submit_bgcolor` = '" . $this->db->escape($data['submit_bgcolor']) . "',
        `submit_txtcolor` = '" . $this->db->escape($data['submit_txtcolor']) . "',`reset_bgcolor` = '" . $this->db->escape($data['reset_bgcolor']) . "',`reset_txtcolor` = '" . $this->db->escape($data['reset_txtcolor']) . "',`date_modified` = NOW()	WHERE `form_id` = '" . (int)$form_id . "'";

	 	$this->db->query($sql);
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_description WHERE  form_id ='" . (int)$form_id."'");
		foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_description SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',title='".$this->db->escape($value['title'])."', button_name='".$this->db->escape($value['button_name'])."', resetbuttonname='".$this->db->escape($value['resetbuttonname'])."',uplaodfilebtn='".$this->db->escape($value['uplaodfilebtn'])."', popuplinkname='".$this->db->escape($value['popuplinkname'])."', meta_title='".$this->db->escape($value['meta_title'])."',meta_description='".$this->db->escape($value['meta_description'])."',meta_keyword='".$this->db->escape($value['meta_keyword'])."',top_description='".$this->db->escape($value['top_description'])."',bottom_description='".$this->db->escape($value['bottom_description'])."'"); 
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_store WHERE  form_id ='" . (int)$form_id."'");
		if (!empty($data['form_store'])) {
			foreach ($data['form_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_store SET form_id = '" . (int)$form_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_customergroup WHERE  form_id ='" . (int)$form_id."'");
		if (!empty($data['form_customer'])) {
			foreach ($data['form_customer'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_customergroup SET form_id = '" . (int)$form_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_success WHERE  form_id ='" . (int)$form_id."'");
		foreach ($data['succes_form'] as $language_id => $success) {
			$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_success SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',success_title='".$this->db->escape($success['success_title'])."',success_meta_title='".$this->db->escape($success['success_meta_title'])."',success_description='".$this->db->escape($success['success_description'])."'"); 
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_notification WHERE  form_id ='" . (int)$form_id."'");
		foreach ($data['form_notification'] as $language_id => $notification) {
			$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_notification SET form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',customer_notification='".(int)$notification['customer_notification']."', admin_notification='".(int)$notification['admin_notification']."',customer_subject='".$this->db->escape($notification['customer_subject'])."',customer_message='".$this->db->escape($notification['customer_message'])."',admin_subject='".$this->db->escape($notification['admin_subject'])."',admin_message='".$this->db->escape($notification['admin_message'])."'"); 
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_product WHERE form_id = '" . (int)$form_id . "'");

		if (!empty($data['form_product'])) {
		    if (!is_array($data['form_product'])) {
		        $data['form_product'] = explode(',', $data['form_product']); 
		    }

		    foreach ($data['form_product'] as $product_id) {
		        $this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_product SET form_id = '" . (int)$form_id . "', productform_id = '" . $this->db->escape($product_id) . "'");
		    }
		}

		
		$this->db->query("DELETE from " . DB_PREFIX . "tmdform_category where  form_id ='" . (int)$form_id."'");		
		if (!empty($data['form_category'])){
		   foreach ($data['form_category'] as $category_id){
			 $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_category SET  form_id = '" . (int) $form_id . "', category_id = '" . $this->db->escape($category_id). "'");
		   }
		}

		$this->db->query("DELETE from " . DB_PREFIX . "tmdform_manufacturer where  form_id ='" . (int)$form_id."'");		
		if (!empty($data['form_manufacturer'])){
		   foreach ($data['form_manufacturer'] as $manufacturer_id){
			 $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_manufacturer SET  form_id = '" . (int) $form_id . "', manufacturer_id = '" . $this->db->escape($manufacturer_id). "'");
		   }
		}

		$this->db->query("DELETE from " . DB_PREFIX . "tmdform_information where  form_id ='" . (int)$form_id."'");
		if (!empty($data['information'])){
		   foreach ($data['information'] as $information_id){
			 	$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_information SET  form_id = '" . (int) $form_id . "', information_id = '" . $this->db->escape($information_id). "'");
		   }
		}		
	
		if (isset($data['option_fields'])){
		   foreach ($data['option_fields'] as $fields){

			   if(!empty($fields['type'])) {
				    if(empty($fields['field_id'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "tmdform_field SET  form_id='".(int)$form_id."',form_status = '" .$fields['form_status']. "',
					required = '".$fields['required']. "',sort_order = '".$fields['sort_order']. "',type='".$fields['type']."'");
					$field_id = $this->db->getLastId();
					}
					else
					{
					$this->db->query("UPDATE  " . DB_PREFIX . "tmdform_field SET  
					form_id='".(int)$form_id."',
					form_status = '" .$fields['form_status']. "',
					required = '".$fields['required']. "',
					sort_order = '".$fields['sort_order']. "',
					type='".$fields['type']."' WHERE field_id='".$fields['field_id']."'");
					$field_id = $fields['field_id'];		
					}
					
				   	$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_field_description WHERE field_id ='" . (int)$field_id."'");
					
				   	if(isset($fields['form_fields'])) {
						foreach ($fields['form_fields'] as $language_id => $form) {
							$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_field_description SET field_id ='" . (int)$field_id . "',form_id ='" . (int)$form_id . "',language_id = '" . (int)$language_id ."',field_name='".$this->db->escape($form['field_name'])."',help_text='".$this->db->escape($form['help_text'])."',placeholder='".$this->db->escape($form['placeholder'])."',error_message='".$this->db->escape($form['error_message'])."'"); 
						}
					}
					
				$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_field_option WHERE  field_id ='" . (int)$field_id."'");	
				$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_field_option_base WHERE  field_id ='" . (int)$field_id."'");	
			   	if(isset($fields['option_type'])) {
					foreach ($fields['option_type'] as $option_description) {

						
						$baseoption=$this->db->query("INSERT INTO  " .DB_PREFIX . "tmdform_field_option_base set field_id ='" . (int)$field_id . "' , form_id ='" . (int)$form_id . "'");
						$optiobaseid=$this->db->getLastId();

						if(isset($option_description['option_value_description'])) {
							foreach ($option_description['option_value_description'] as $language_id => $option_value_description) {
								
								$this->db->query("INSERT INTO " .DB_PREFIX . "tmdform_field_option SET field_id ='" . (int)$field_id . "',form_id ='" . (int)$form_id . "',optiobaseid='".$optiobaseid."',language_id = '" . (int)$language_id ."',name='".$option_value_description['name']."',sort_order='".$option_description['sort_order']."',price_prefix='".$option_description['price_prefix']."',price='".$option_description['price']."',image='".$option_description['image']."'");
							}
						}
					}
				}
			}	   
				
		   	}
		}
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_id' AND `value` = '" . (int)$form_id . "'");

		if (isset($data['form_seo_url'])) {
			foreach ($data['form_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'form_id', `value` = '" . (int)$form_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}	

		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_success' AND `value` = '" . (int)$form_id . "'");
		if (!empty($data['form_success_page_seo'])) {
			foreach ($data['form_success_page_seo'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'form_success', `value` = '" . (int)$form_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
 	}
	
 	public function deleteForm(int $form_id): void{	
	
		$tables=$this->tablesname();

		foreach($tables as $table)
		{
		$sql=$this->db->query("DELETE  FROM `" . DB_PREFIX . $table."` WHERE form_id='".$form_id."'");
		}
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_id' AND `value` = '" . (int)$form_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_success' AND `value` = '" . (int)$form_id . "'");
		
	} 
	
	public function getForm($form_id){
		
		$sql="SELECT DISTINCT * FROM " . DB_PREFIX . "tmdform WHERE form_id='".$form_id."'";
		
		$query=$this->db->query($sql);
		return $query->row;
	}
	public function getFormname($form_id){
		$sql="SELECT DISTINCT * FROM " . DB_PREFIX . "tmdform_description WHERE form_id='".$form_id."'";
		
		$query=$this->db->query($sql);
		return $query->row;
	
	}
	
 	public function getForms($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tmdform` f LEFT JOIN " . DB_PREFIX . "tmdform_description fd ON (f.form_id = fd.form_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_title'])){
			$sql .=" and fd.title like '".$this->db->escape($data['filter_title'])."%'";
		}
		
		$sort_data = array(
			'fd.title',
			'f.status'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		 	$sql .= " ORDER BY " . $data['sort'];
		} else {
		 	$sql .= " ORDER BY fd.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		

		$query = $this->db->query($sql);
		return $query->rows;
 	}
	
	public function getFormDescriptions($form_id) {
		$form_descriptio_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."tmdform_description WHERE form_id = '" . (int)$form_id . "'");
		foreach ($query->rows as $result) {
			
			$form_descriptio_data[$result['language_id']] =$result;
			
	 	}
		return $form_descriptio_data;
	}
	
	public function getFormSuccess($form_id) {
		$form_success_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."tmdform_success WHERE form_id = '" . (int)$form_id . "'");
		foreach ($query->rows as $result) {
			$form_success_data[$result['language_id']] = array(
				'success_title'=> $result['success_title'],
				'success_meta_title'=> $result['success_meta_title'],
				'success_description'=> $result['success_description'],
			);
	 	}
		return $form_success_data;
	}
	
	public function getFormNotification($form_id) {
		$form_notification_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."tmdform_notification WHERE form_id = '" . (int)$form_id . "'");
		foreach ($query->rows as $result) {
			$form_notification_data[$result['language_id']] = array(
				'customer_subject'=> $result['customer_subject'],
				'customer_message'=> $result['customer_message'],
				'customer_notification'=> $result['customer_notification'],
				'admin_notification'=> $result['admin_notification'],
				'admin_subject'=> $result['admin_subject'],
				'admin_message'=> $result['admin_message'],
			);
	 	}
		return $form_notification_data;
	}
			
 	public function getTotalForm($data) {
		$sql = "SELECT COUNT(*) AS total 
	        FROM " . DB_PREFIX . "tmdform f 
	        LEFT JOIN " . DB_PREFIX . "tmdform_description fd 
	        ON (f.form_id = fd.form_id) 
	        WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
	        AND f.form_id <> 0";

	$query = $this->db->query($sql);
	return $query->row['total'];

	}
	
	public function getCustomerCheckbox($form_id){
		$form_to_customer=array();
		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_customergroup WHERE form_id='".$form_id."'";
		$query=$this->db->query($sql);	
		foreach ($query->rows as $result) {
			$form_to_customer[] = $result['customer_group_id'];
		}
		return $form_to_customer;
	}
	
	public function getFormByStoreId($form_id) {
		$form_store_data = array();
		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_store WHERE form_id='".$form_id."'";
		$query=$this->db->query($sql);	
		foreach ($query->rows as $result) {
			$form_store_data[] = $result['store_id'];
		}
		
		return $form_store_data;
	}
	
	public function getFormProductbyid($form_id){
		$productform_ids=array();
		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_product WHERE form_id='".$form_id."'";
		$query=$this->db->query($sql);	
		foreach ($query->rows as $result) {
			$productform_ids[] = $result['productform_id'];
		}
		return $productform_ids;
 	}
	
	public function getFormCategorybyid($form_id){
		$category_ids=array();
		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_category WHERE form_id='".$form_id."'";
		$query=$this->db->query($sql);	
		foreach ($query->rows as $result) {
			$category_ids[] = $result['category_id'];
		}
		return $category_ids;
 	}
	
	public function getFormManufacturerbyid($form_id){
		$manufacturer_ids=array();
		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_manufacturer WHERE form_id='".$form_id."'";
		$query=$this->db->query($sql);	
		foreach ($query->rows as $result) {
			$manufacturer_ids[] = $result['manufacturer_id'];
		}
		return $manufacturer_ids;
 	}
	
 	public function getFormInformation($form_id){

		$information_data=array();

		$sql="SELECT  *  FROM " . DB_PREFIX . "tmdform_information WHERE form_id='".$form_id."'";

		$query=$this->db->query($sql);	

		foreach ($query->rows as $result) {
			$information_data[] = $result['information_id'];
		}

		return $information_data;
 	}

	public function getFormFieldById($form_id) {
		$form_field_data = array();

		$form_field_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_field WHERE form_id = '" . (int)$form_id . "' order by sort_order asc");

		foreach ($form_field_query->rows as $form_field) {
			$form_field_description_data = array();

			$form_field_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_field_description WHERE form_id = '" . (int)$form_id . "' and field_id='".$form_field['field_id']."'");
			foreach ($form_field_description_query->rows as $form_field_description) {
				
				$form_field_description_data[$form_field_description['language_id']] =$form_field_description;
				
			}
			$form_field_options=array();
			
			$form_field_options_query= $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_field_option WHERE form_id = '" . (int)$form_id . "' and field_id='".$form_field['field_id']."' and language_id='".$this->config->get('config_language_id')."' order by sort_order asc");
			
			if(isset($form_field_options_query->row))
			{
				foreach ($form_field_options_query->rows as $form_field_option) {
				
					$form_field_options_query1= $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_field_option WHERE form_id = '" . (int)$form_id . "' and field_id='".$form_field['field_id']."' and optiobaseid='".$form_field_option['optiobaseid']."'");
					foreach ($form_field_options_query1->rows as $form_field_option1) {
					$option_name[$form_field_option1['language_id']]=$form_field_option1['name'];
					
					}
					$this->load->model('tool/image');
					if (is_file(DIR_IMAGE . $form_field_option['image'])) {
						$thumb = $this->model_tool_image->resize($form_field_option['image'], 500, 500);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', 500, 500);
					}
					
					$form_field_options[] = array(
						'name' => $option_name,
						'sort_order' => $form_field_option['sort_order'],
						'image' => $form_field_option['image'],
						'field_id' => $form_field_option['field_id'],
						'price' => $form_field_option['price'],
						'price_prefix' => $form_field_option['price_prefix'],
						'thumb' => $thumb,
					);
				}
			}
			
			$form_field_data[] = array(
				'field_id' => $form_field['field_id'],
				'type' => $form_field['type'],
				'form_status' => $form_field['form_status'],
				'required' => $form_field['required'],
				'sort_order' => $form_field['sort_order'],
				'form_field_description' => $form_field_description_data,
				'form_field_options' => $form_field_options
			);

		}
		return $form_field_data;
	}
	
	public function getFormfields($form_id) {
		$submit_data = array();
		$submit_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_submit WHERE form_id = '" . (int)$form_id . "'");
		foreach($submit_query->rows as $submit) {
			$field_data=array();
			$data_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdfield_submit WHERE fs_id = '" . (int)$submit['fs_id'] . "'");
			foreach($data_query->rows as $fieldsubmit) {
				$field_data=array(
					'field_id' => $fieldsubmit['field_id'],
					'value' => $fieldsubmit['value']
				);
			}
			
			$submit_data = array(
				'form_id' => $submit['form_id'],
				'fieldsubmit' => $field_data
			);
			
		}
		
		return $submit_data;
	}
	
	public function getTotalFormfield($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tmdform_field WHERE form_id<>0 ";
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getexportheading($form_id){
		$sql="SELECT * FROM " . DB_PREFIX . "tmdfield_submit WHERE form_id='".$form_id."'  group by field_id ORDER by sort_order asc ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getexportsubmit($form_id){
		$sql="SELECT * FROM " . DB_PREFIX . "tmdform_submit WHERE form_id='".$form_id."' ORDER by date_added desc ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getFieldType($field_id){
		$sql="SELECT type FROM " . DB_PREFIX . "tmdform_field WHERE field_id='".$field_id."'";
		$query = $this->db->query($sql);
		if(isset($query->row['type'])){
		return $query->row['type'];
		}
	}
	
	public function getFieldExport($fs_id, $form_id, $field_id) {

	    $sql = "SELECT * FROM " . DB_PREFIX . "tmdfield_submit WHERE fs_id='" . (int)$fs_id . "' AND form_id='" . (int)$form_id . "' AND field_id='" . (int)$field_id . "'";
	    $query = $this->db->query($sql);

	    if (isset($query->row['value'])) {
	        $data = '';

	        // If the data is serialized, we process it differently
	        if ($query->row['serialize']) {
	            $values = unserialize($query->row['value']);

	            if (!empty($values) && is_array($values)) {
	                foreach ($values as $value) {
	                    $data .= $value . ', ';
	                }
	                $data = rtrim($data, ', ');
	            }
	        } else {
	            $data = $query->row['value'];
	        }

	        // Check if the field is a country or zone type and process accordingly
	        $field_info = $this->db->query("SELECT type FROM " . DB_PREFIX . "tmdform_field WHERE field_id='" . (int)$field_id . "'")->row;

	        if (!empty($field_info['type'])) {
	            if ($field_info['type'] === 'country') {
	                $this->load->model('localisation/country');
	                $country_info = $this->model_localisation_country->getCountry($query->row['value']);
	                if (!empty($country_info['name'])) {
	                    $data = $country_info['name'];
	                }
	            } elseif ($field_info['type'] === 'zone') {
	                $this->load->model('localisation/zone');
	                $zone_info = $this->model_localisation_zone->getZone($query->row['value']);
	                if (!empty($zone_info['name'])) {
	                    $data = $zone_info['name'];
	                }
	            }
	        }

	        return $data;
	    }

	    return '';
	}


	
	public function deleteAllFieldById($field_id){	
		$this->db->query("DELETE FROM " . DB_PREFIX . "tmdform_field WHERE field_id='".$field_id."'");
		
		$this->db->query("DELETE FROM " .  DB_PREFIX . "tmdform_field_description WHERE field_id='".$field_id."'");
		
		$this->db->query("DELETE FROM " .  DB_PREFIX . "tmdform_field_option WHERE field_id='".$field_id."'");
		$this->db->query("DELETE FROM " .  DB_PREFIX . "tmdform_field_option_base WHERE field_id='".$field_id."'");		
 	} 
	
	public function getHeaderlinks(){
		$sql = "show table status WHERE name='".DB_PREFIX . "tmdform'";
		$query = $this->db->query($sql);
		if(empty($query->row))
		{
		return array();
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tmdform t LEFT JOIN " . DB_PREFIX . "tmdform_description td ON (t.form_id = td.form_id) WHERE td.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query=$this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getFormStores($form_id) {
		$store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tmdform_store WHERE form_id = '" . (int)$form_id . "'");
		foreach ($query->rows as $result) {
			$store_data[] = $result['store_id'];
		}
		return $store_data;
	}
	
	public function getSeoUrls(int $form_id): array {
		$form_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_id' AND `value` = '" . (int)$form_id . "'");

		foreach ($query->rows as $result) {
			$form_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $form_seo_url_data;
	}
	
	public function getFormSuccessSeoUrls($value) {
		$seourldata = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'form_success' AND `value` = '".$this->db->escape($value)."'");
		foreach ($query->rows as $result) {
			$seourldata[$result['store_id']][$result['language_id']] = $result['keyword'];
		}
		return $seourldata;
	}
	

	public function getSeoUrl($value): array {
		$product_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '".$this->db->escape($value)."'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}

	public function getSuccessSeoUrl($value): array {
		$product_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '".$this->db->escape($value)."'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}
	
	public function saveSeoUrls($data,$value): void {
		$query = $this->db->query("delete FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '".$this->db->escape($value)."'");

		foreach ($data[$data['urlformat']] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'route', `value` = '" . $this->db->escape($value) . "', `keyword` = '" . $this->db->escape($keyword) . "',sort_order='-1'");
			}
		}
		
	}
	public function saveSuccessSeoUrls($data,$value): void {
		$query = $this->db->query("delete FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'route' AND `value` = '".$this->db->escape($value)."'");

		foreach ($data[$data['urlsuccessformat']] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'route', `value` = '" . $this->db->escape($value) . "', `keyword` = '" . $this->db->escape($keyword) . "',sort_order='-1'");
				}
			}
		
	}

	public function getOrderForm($order_id) {
		$sql="SELECT * FROM " . DB_PREFIX . "tmdform_submit_to_order where order_id='".$order_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}
		
}
?>
