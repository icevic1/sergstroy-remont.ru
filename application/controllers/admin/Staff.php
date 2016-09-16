<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff extends Admin_Controller 
{
	var $page_id=27;
	function __construct() 
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Staff_mod');
//		$this->load->model('Customer_model');
// 		$this->load->model('Masteracc_model');
		$this->load->library('Aes');
	}
	
	function index()
	{
		$data['filteredUsers'] = array();
		$filter = array();
	
		$data['filteredUsers'] = $filteredUsers = $this->Staff_mod->searchUsers($filter);
		
		// 		var_dump($data['filteredUsers']);die;
		$data['choosedFilters'] = $filter;
		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 27);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/staff/index';
		$this->load->view('template/tmpl_admin', $data);
	}

	/**
	 * Delete User
	 * @param int $user_id, $user_type
	 */
	public function delete_user($user_id = null, $user_type = null)
	{
		if ($user_id) {
			$this->Staff_mod->deleteUser($user_id);
		}
	
		switch ($user_type){
			case 1: redirect('admin/staff/pics');break;
			case 2: redirect('admin/staff/kams');break;
			default: redirect('admin/staff/');
		}
		
	}
	

	public function edit($getUser_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->helper('security');

		$user_id = null;
		$page_action = 'Register New User';
		if ($getUser_id) {
			$LoadedUserDetails = $this->Staff_mod->getByID($getUser_id);
			if ($LoadedUserDetails) {
				$data['loadedItem'] = $LoadedUserDetails;
				$user_id = $LoadedUserDetails['user_id'];
				
				$data['loadedRoles'] = $this->Staff_mod->getUserRoles($user_id, 'values');
				$page_action = 'Change';
			} else {
				show_error("Selfcare User with <b>{$getUser_id}</b> ID was not found!", $status_code= 500 );
			}
		}
		//var_dump($data['loadedItem']);die;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$inputData = $this->input->post('user');
			$inputRoles = $this->input->post('roles');
			$user_id = $inputData['user_id'];
// 			var_dump($this->input->post('user'));
			
			$uniqEmail = '';
			if ((!isset($data['loadedItem']['email']) && $inputData['email']) || (isset($data['loadedItem']['email']) && $inputData['email'] && $inputData['email'] != $data['loadedItem']['email'])) {
				$uniqEmail = '|is_unique[scsm_users.email]';
			}
			
			//required|is_natural_no_zero|is_natural
			$this->form_validation->set_rules('user[user_id]', 'User ID', 'trim'.(($getUser_id)?'|required':''));
			
			$this->form_validation->set_rules('user[name]', 'Name', 'trim|required|max_length[64]');
//			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[3]|max_length[32]');
			$this->form_validation->set_rules('user[email]', 'Email', 'trim|valid_email|max_length[128]'.$uniqEmail);
			$this->form_validation->set_rules('user[mobile_no]', 'Mobile No.', 'trim|required|is_natural|max_length[64]');
//			$this->form_validation->set_rules('user[home_no]', 'Home No.', 'trim|is_natural|max_length[64]');
//			$this->form_validation->set_rules('user[office_no]', 'Office No.', 'trim|is_natural|max_length[64]');
//			$this->form_validation->set_rules('user[fax_no]', 'Fax No.', 'trim|is_natural|max_length[64]');
			$this->form_validation->set_rules('user[address]', 'Адрес', 'trim');
			$this->form_validation->set_rules('roles[]', 'Role', 'trim|is_natural_no_zero');

// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());
			
			if ($this->form_validation->run() != FALSE)	{
				
				$inputData = $this->empty2null($inputData);
// 				var_dump($inputRoles);die;

				$form_password = $this->form_validation->set_value('password');
				if (!empty($form_password)) {
// 					$inputData['password'] = md5($form_password);
					$this->aes->setKey($this->config->item('aes_key'));
					$this->aes->setBlockSize($this->config->item('aes_size'));
					$this->aes->setData($form_password);
						
					$encryptedPassword = $this->aes->encrypt();
					$inputData['password'] = $encryptedPassword;
				}
				
// 				var_dump($WebCustId, $inputCustData);die;
				if ($user_id) {
					$this->Staff_mod->updateUser($user_id, $inputData);
				} else {
					$user_id = $this->Staff_mod->insertUser($inputData);
				}
				
				/**
				 * clean all user roles before new population
				 */
				$this->Staff_mod->deleteUserRoles($user_id, null);
				foreach ($inputRoles as $roleItem) {
					if (Acl::getSimpleRoleById($roleItem) == false) continue;
					$this->Staff_mod->insertUserRoles(array('user_id'=>$user_id, 'role_id'=>$roleItem));
				}
				
// 				redirect('admin/staff/edit/');
				redirect('admin/staff');
			} else {
				$data['loadedItem'] = $this->input->post('user');
				$data['loadedRoles'] = $this->input->post('roles');
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 27);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		
		$data['CONTENT'] = 'admin/staff/edit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	/**
	 * Delete staff
	 * @param int $staff_id
	 */
	public function delete($staff_id = null)
	{
		if ($staff_id) {
			$this->Staff_mod->deleteStaffByID($staff_id);
		}
		
		redirect('admin/staff/');
	}
	
	function ajax_get_staff()
	{
		$filter = array();
		
		$filter['is_default_responsible'] = true;
		$filter['company_id'] 	= $this->input->post('company_id', null);
		$filter['branch_id']	= $this->input->post('branch_id', null);
		$filter['activity_status'] 	= '0';
		
		$companyInfo = $this->Masteracc_model->getCompanyByID($filter['company_id']);
		
		$filteredUsers = ($companyInfo)? $this->Staff_mod->search($filter): null;
		
		
		$out = json_encode(array('companyInfo'=>$companyInfo, 'companyPics'=>$filteredUsers));
// 		if ($filter['company_id']) {
			
		
// 			var_dump($filter, $filteredUsers);
			
// 			if ($filteredUsers) {
// 				$dropDown = array('0' => 'Choose a PIC');
// 				foreach ($filteredUsers as $item) {
// 					$dropDown[$item->staff_id] = $item->staff_name;
// 				}
// 			}
// 		}
	
// 		echo form_dropdown('staff_id', $dropDown, 0);
		header('Content-Type: application/json');
		echo $out;
	}
}