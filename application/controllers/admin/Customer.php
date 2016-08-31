<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends Admin_Controller 
{
	var $page_id = 5;
	
	function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
// 		$this->load->library('Acl');
// 		$this->load->model('Subscriber_mod');
		$this->load->model('Customer_model', 'customer');
		$this->load->model('Staff_mod');
	}
	
	function index()
	{
		$data['customers'] = $this->customer->getCustomers();
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/customers/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function save_customer($getWebCustId = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->helper('security');
// 	var_dump(Customer_model::webRegisterCapitals());
		$WebCustId = null;
		$page_action = 'Register Corporate Customer';
		if ($getWebCustId) {
			$LoadedCustomerDetails = $this->customer->getWebCustById($getWebCustId);
			if ($LoadedCustomerDetails) {
				$data['loadedItem'] = $LoadedCustomerDetails;
				$WebCustId = $LoadedCustomerDetails['WebCustId'];
				$page_action = 'Change Corporate Customer';
				
				/*todo*/
				$data['loadedAddress'] = $this->customer->getCustAddresses($WebCustId);
				$data['loadedPICS'] = $this->Staff_mod->getCustUsers($WebCustId, $user_type = '1');
				$data['loadedKAMS'] = $this->Staff_mod->getCustUsers($WebCustId, $user_type = '2');
// 				var_dump($data['loadedAddress']);die;
				
			} else {
				show_error("Customer <b>{$getWebCustId}</b> was not found!", $status_code= 500 );
			}
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$inputCustData = $this->input->post('customer');
			$inputCustAddresses = $this->input->post('customer_address');
			$inputCustPICS = $this->input->post('users_pic');
			$inputCustKAMS = $this->input->post('users_kam');
			
			$WebCustId = $inputCustData['WebCustId'];
// 			var_dump($this->input->post());die;
			/* 
			$format = 'Y-m-d';
			$d = DateTime::createFromFormat($format, $inputCustData['IssuedDate']);
			//Check for valid date in given format
			
			var_dump($d->format($format) == $inputCustData['IssuedDate'], $this->input->post());die; */
			
			//required|is_natural_no_zero|is_natural
			$this->form_validation->set_rules('customer[WebCustId]', 'WebCustId', 'trim'.(($WebCustId)?'|required':''));
			$this->form_validation->set_rules('customer[CustId]', 'CustId', 'trim');
			
			$this->form_validation->set_rules('customer[CustName]', 'CustName', 'trim|required|max_length[256]');
			$this->form_validation->set_rules('customer[CustShortName]', 'CustShortName', 'trim|max_length[256]');
			$this->form_validation->set_rules('customer[CustCode]', 'CustCode', 'trim|max_length[64]');
			
			$this->form_validation->set_rules('customer[CustType]', 'CustType', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('customer[CertificateTypeId]', 'CertificateTypeId', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('customer[CertificateNumId]', 'CertificateNumId', 'trim|required|max_length[32]');
			
			$this->form_validation->set_rules('customer[IssuedDate]', 'IssuedDate', 'trim|date');
			$this->form_validation->set_rules('customer[BrnExpiryDate]', 'BrnExpiryDate', 'trim|date');
			$this->form_validation->set_rules('customer[Industry]', 'Industry', 'trim|max_length[16]');
			
			$this->form_validation->set_rules('customer[SubIndustry]', 'SubIndustry', 'trim|max_length[16]');
			$this->form_validation->set_rules('customer[CustPhoneNumber]', 'CustPhoneNumber', 'trim|max_length[64]');
			$this->form_validation->set_rules('customer[CustEmail]', 'CustEmail', 'trim|valid_email|max_length[128]');
			
			$this->form_validation->set_rules('customer[CustFaxNumber]', 'CustFaxNumber', 'trim|max_length[16]');
			$this->form_validation->set_rules('customer[CustLevel]', 'CustLevel', 'trim|is_natural|max_length[64]');
			$this->form_validation->set_rules('customer[CustSize]', 'CustSize', 'trim|is_natural|max_length[8]');
			
			$this->form_validation->set_rules('customer[RegisterDate]', 'RegisterDate', 'trim|required|date'); //valid_date[Y-m-d]
			$this->form_validation->set_rules('customer[RegisterCapital]', 'RegisterCapital', 'trim|max_length[16]');
			$this->form_validation->set_rules('customer[ParentCustId]', 'ParentCustId', 'trim|max_length[64]');
			
			$this->form_validation->set_rules('customer[Remark]', 'Remark', 'trim|max_length[512]');
			$this->form_validation->set_rules('customer[CustLanguage]', 'CustLanguage', 'trim|max_length[4]');
			$this->form_validation->set_rules('customer[CustWrittenLanguage]', 'CustWrittenLanguage', 'trim|max_length[4]');
			
			$this->form_validation->set_rules('customer[AgreementId]', 'Agreement Number', 'trim|max_length[20]');
			$this->form_validation->set_rules('customer[TIN]', 'TIN', 'trim|required|max_length[64]');
	
// 			$this->form_validation->set_message('valid_datetime', 'msg_sure_phone_num');
// 			$this->form_validation->set_message('valid_date', 'The %s date is not valid it should match this (YYYY-MM-DD) format!');
			$this->form_validation->set_message('valid_date', "The date or format is invalid.");
			
			$this->form_validation->set_message('is_natural_no_zero', 'The %s field need be choosed!');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());
			
			if ($this->form_validation->run() != FALSE)	{
				
				$RegisterDate = $this->form_validation->set_value('customer[RegisterDate]');
				$IssuedDate = $this->form_validation->set_value('customer[IssuedDate]');
				$BrnExpiryDate = $this->form_validation->set_value('customer[BrnExpiryDate]');
				
				$inputCustData['IssuedDate'] = ($IssuedDate)?date("{$IssuedDate} H:i:s"):'';
				$inputCustData['BrnExpiryDate'] = ($BrnExpiryDate)?date("{$BrnExpiryDate} H:i:s"):'';
				$inputCustData['RegisterDate'] = ($RegisterDate)?date("{$RegisterDate} H:i:s"):'';
				
				
				$inputCustData = $this->empty2null($inputCustData);
// 				var_dump($this->form_validation->set_value('customer[RegisterDate]'), $inputCustData);die;
				
// 				var_dump($WebCustId, $inputCustData);die;
				if ($WebCustId) {
					$this->customer->updateCustomerByWebId($WebCustId, $inputCustData);
				} else {
					$WebCustId = $this->customer->insertCustomer($inputCustData);
				}
				
				/*Customer addresses*/
				if ($inputCustAddresses)
				foreach ($inputCustAddresses as $addressItem) {
					$addressItem = $this->empty2null($addressItem);
					
					$addressItem['WebCustId'] = $WebCustId; 
					$addressItem['CustId'] = $inputCustData['CustId'];
					
					if ($addressItem['WebAddressId'] > 0) {
						$this->customer->updateCustomerAddress($addressItem['WebAddressId'], $addressItem);
					} else {
						$this->customer->insertCustomerAddress($addressItem);
					}
// 					var_dump($addressItem);die('aaaa');
				}
				
				/*Customer users*/
				if ($WebCustId && ($inputCustPICS || $inputCustKAMS)) {
					
					$this->Staff_mod->deleteCustUsers($WebCustId);
					
					if ($inputCustPICS) {
						foreach ($inputCustPICS as $itemVal) {
							$this->Staff_mod->insertCustUser(array('WebCustId' => $WebCustId, 'user_id' => $itemVal));
						}
					}
					if ($inputCustKAMS) {
						foreach ($inputCustKAMS as $itemVal) {
							$this->Staff_mod->insertCustUser(array('WebCustId' => $WebCustId, 'user_id' => $itemVal));
						}
					}
				}
				redirect('admin/customer/save_customer/'.$WebCustId);
			} else {
				$data['loadedItem'] = $this->input->post('customer');
				$data['loadedAddress'] = $this->input->post('customer_address');
				
				$inputPICs = $this->input->post('users_pic');
				$inputKAMs = $this->input->post('users_kam');
				
				$data['loadedPICS'] = (is_array($inputPICs))? $this->Staff_mod->searchUsers($filter = array('user_id'=>$inputPICs, 'user_type'=>'1'), $return_type = 'array'): array();
				$data['loadedKAMS'] = (is_array($inputKAMs))? $this->Staff_mod->searchUsers($filter = array('user_id'=>$inputKAMs, 'user_type'=>'2'), $return_type = 'array'): array();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 5);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		
		$data['CONTENT']='admin/customers/edit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function delete_customer($WebCustId = null)
	{
		if ($WebCustId) {
			$this->customer->deleteCustomer($WebCustId);
		}
		
		redirect('admin/customer/');
	}
	
	public function ajax_delete_customer_address () 
	{
		$WebAddressId = $this->input->get_post('WebAddressId');
		$this->customer->deleteCustomerAddress($WebAddressId);
	}
	
	
	//Define a callback and pass the format of date
	function valid_datetime($date = '')
	{
		$format = 'Y-m-d';
		if ('' == $date) return true;
// 		var_dump($date);
		$d = DateTime::createFromFormat($format, $date);
		
// 		var_dump($d, $d->format($format));
// 		die;
		//Check for valid date in given format
		if($d && $d->format($format) == $date) {
// 			var_dump('suka:'.$date, $d->date);
// 			return $d->date;
			return true;
		} else {
			return false;
		}
	}
	
	public static function input_hidden_addresses($fields_array = array(), $rowindex = '0')
	{
		$out = '';
		foreach ($fields_array as $fieldName=>$fieldValue) {
			$inputName = "customer_address[{$rowindex}][{$fieldName}]";
			$inputID = "customeraddress_{$rowindex}_{$fieldName}";
			
			$out .= '<input type="hidden" name="'.$inputName.'" id="'.$inputID.'" value="'.$fieldValue.'" data-originalname="'.$fieldName.'" />';
		}
		return $out;
	}
}