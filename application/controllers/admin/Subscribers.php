<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Subscribers extends Admin_Controller {
	public $page_id = 33;
	
	function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Subscriber_mod');
		$this->load->model('Masteracc_model');
		$this->load->model('Staff_mod');
	}
	
	function index()
	{
// 		$this->load->library('pagination');
		$this->load->helper('pagination');
		$page = $this->uri->segment(5);
		$per_page = 1;
		
		/******* prepare form filter ************/
		$data['filteredUsers'] = array();
		$filter = array();
		$data['select_branches'] = array('0'=>'---');
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//$company_id = $this->input->get('company_id', TRUE); 
			$filter['searchtext'] 	= $this->input->post_get('searchtext');
			$filter['search_field'] = $this->input->post_get('search_field');
			$filter['company_id'] 	= ($this->input->post_get('company_id'))? 	$this->input->post_get('company_id'): 	null;
			$filter['branch_id']	= ($this->input->post_get('branch_id'))	? 	$this->input->post_get('branch_id')	: 	null;
			
			$data['branches'] = $this->Masteracc_model->getCompanyBranches($filter['company_id'], true);
			$data['select_branches'] = array_replace(array('0' => '---'), $data['branches']);
		}
		$filter['sc_status'] 	= ($this->input->post('sc_status'))?	$this->input->post('sc_status'):	'0';
		
		$filteredUsers = $this->Subscriber_mod->search($filter, $per_page, $page);
		
		$FOUND_ROWS = 0;
		if (isset($filteredUsers['FOUND_ROWS'])) {
			$FOUND_ROWS = $filteredUsers['FOUND_ROWS'];
			unset($filteredUsers['FOUND_ROWS']);
		}
		
		/* $pagination['base_url'] = base_url().'admin/subscribers/index/page/';
		$pagination['total_rows'] = $FOUND_ROWS;
		$pagination['per_page'] = $per_page;
		$pagination['uri_segment'] = 5;
		$pagination['use_page_numbers']  = TRUE;
		$pagination['first_tag_open'] = $pagination['last_tag_open']= $pagination['next_tag_open']= $pagination['prev_tag_open'] = $pagination['num_tag_open'] = '<li>';
		$pagination['first_tag_close'] = $pagination['last_tag_close']= $pagination['next_tag_close']= $pagination['prev_tag_close'] = $pagination['num_tag_close'] = '</li>';
		$pagination['cur_tag_open'] = "<li><span><b>";
		$pagination['cur_tag_close'] = "</b></span></li>";
		
		$this->pagination->initialize($pagination);
		$data['pagination'] = $this->pagination->create_links(); */
// 		var_dump($filteredUsers);

		$data['pagination'] = $pagination = init_pagination('admin/subscribers/index/page/', $FOUND_ROWS, $per_page, $segment = 5);
		
// 		var_dump($pagination, $pagination->create_links());die;
		
		$data['filteredUsers'] = $filteredUsers;
		$data['choosedFilters'] = $filter;
		$data['companies'] = Masteracc_model::getAllCompanies(true);
		$data['select_companies'] = array_replace(array('0' => 'Choose an company'), $data['companies']);
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/subscribers/index';
		$this->load->view('template/tmpl_admin', $data);
	}

	public function edit($subs_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$data['subscriber'] = $this->Subscriber_mod->getByID($subs_id, true);
// 		var_dump($data['subscriber']);
		if (empty($data['subscriber'])) {
			show_error('Subscriber with ID #'.$subs_id.'# was not found!', $status_code= 500 );
		}
		
		$data['choosedCompany_id'] = $data['subscriber']->company_id;
		$data['choosedBranch_id'] = $data['subscriber']->branch_id;
		$data['choosedDep_id'] = $data['subscriber']->dep_id;
		$data['choosedStaff_id'] =  $data['subscriber']->staff_id;
		$data['choosedStatus'] = $data['subscriber']->sc_status;
		
		$postCompanyID = $this->input->post('company_id', null);
		$selectedCompany = ($postCompanyID)? $postCompanyID: $data['subscriber']->company_id;
		
		$data['companyInfo'] = $this->Masteracc_model->getCompanyByID($selectedCompany);
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$branchesArray = $this->Masteracc_model->getCompanyBranches($postCompanyID, true);
			
			$branchRule = $staffRule = '|is_natural';
			if (count($branchesArray) > 0) {
				$branchRule = '|required|is_natural_no_zero';
			}
			
			if (isset($data['companyInfo']) && $data['companyInfo']->picassigned_type > 0) {
				$staffRule = '|required|is_natural_no_zero';
			}
			
			$this->form_validation->set_rules('subs_id', 'Subscriber ID', 'trim|required|alpha_numeric');
// 			$this->form_validation->set_rules('password', 'Staff password', 'trim|min_length[2]');
			$this->form_validation->set_rules('company_id', 'Company', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('dep_id', 'Department', 'trim');
			$this->form_validation->set_rules('branch_id', 'Branch', "trim{$branchRule}");
			$this->form_validation->set_rules('staff_id', 'Person in charge', "trim{$staffRule}");
			$this->form_validation->set_rules('sc_status', 'Status', "is_natural");
			
			$this->form_validation->set_message('is_natural_no_zero', 'The %s field need be choosed!');
			$this->form_validation->set_message('is_natural', 'The %s field need be choosed!');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_subs_id = $this->form_validation->set_value('subs_id');
				$form_staff_id = $this->form_validation->set_value('staff_id');
// 				$form_password = $this->form_validation->set_value('password');
				
				$data_update = array(
						'company_id' => (($this->form_validation->set_value('company_id'))? $this->form_validation->set_value('company_id') : null),
						'branch_id' => (($this->form_validation->set_value('branch_id'))? $this->form_validation->set_value('branch_id') : null),
						'dep_id' => (($this->form_validation->set_value('dep_id'))? $this->form_validation->set_value('dep_id') : null),
						'staff_id' => (($form_staff_id)? $form_staff_id : null),
						'sc_status' => $this->form_validation->set_value('sc_status'),
				);
				
				/**
				 * Modify password field only if this field are filled
				
				if (!empty($form_password)) {
					$data_update['password'] = md5($this->form_validation->set_value('password'));
				}
				 */
				/**
				 * if subscriber id is in form, then update subscriber with form data 
				 */
				if ($form_subs_id) {
					$data_update['who_changed'] = $this->session->userdata('login_name');
					$data_update['updated_at'] = date('Y-m-d H:i:s');
					$afftectedRows = $this->Subscriber_mod->updateSubscriberByID($form_subs_id, $data_update);
// 					var_dump($data_update);die;
				} 
				redirect('admin/subscribers');
				
			} else {
				$data['choosedStaff_id'] =  $this->form_validation->set_value('staff_id');
				$data['choosedCompany_id'] =  $this->form_validation->set_value('company_id');
				$data['choosedBranch_id'] =  $this->form_validation->set_value('branch_id');
				$data['choosedDep_id'] =  $this->form_validation->set_value('dep_id');
				$data['choosedStatus'] =  $this->form_validation->set_value('sc_status');
				
			}
			//var_dump($this->input->post());
		}
// 		var_dump($data['companyInfo']);
		
		/**
		 * 0-common pic for all company subs
		 * 1-associated, pic asociated for sprecific subs
		 */
		if (isset($data['companyInfo']->picassigned_type) && '0' == $data['companyInfo']->picassigned_type) {
			$data['companyPicsArray'] = $this->Staff_mod->getCompanyPIC($data['companyInfo']->company_id, true);
// 			var_dump($companyPics);die;
		}
		
		$data['roles'] = array_replace(array('0' => 'Choose a role'), Acl::simpleRoleArray());
		
		$data['companies'] = Masteracc_model::getAllCompanies(true);
		$data['select_companies'] = array_replace(array('0' => '---'), $data['companies']);
		
		$branchesArray = $this->Masteracc_model->getCompanyBranches($data['choosedCompany_id'], true);
		
		$data['branches'] = $branchesArray;
		$data['select_branches'] = array_replace(array('0' => '---'), $branchesArray);
// 		$data['select_branches'] = array('0' => '---');

		$departmentsArray = $this->Masteracc_model->getDepartments($data['choosedCompany_id'], $data['choosedBranch_id'], true);
		$data['select_departments'] = array_replace(array('0' => '---'), $departmentsArray);
// var_dump($departmentsArray);
		$staffArray = $this->Staff_mod->getCompanyPIC($data['choosedCompany_id'], true);
		$data['select_staff'] = array_replace(array('0' => '---'), $staffArray);
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 34);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/subscribers/edit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	function ajax_search_parent_result()
	{
		$data['roles'] = array_replace(array('0' => 'Choose a role'), Acl::simpleRoleArray());
	
		$data['companies'] = Masteracc_model::getAllCompanies(true);
		$data['select_companies'] = array_replace(array('0' => 'Choose an company'), $data['companies']);
	
		$data['branches'] = Masteracc_model::getAllBranches(true);
		$data['select_branches'] = array_replace(array('0' => 'Choose an branch'), $data['branches']);

		/******* end form filter ************/
		$data['filteredUsers'] = array();
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$filter = array();
			$filter['searchtext'] 	= $this->input->post('searchtext');
			$filter['search_field'] = $this->input->post('search_field');
			$filter['role_id'] 		= ($this->input->post('role_id'))	?	$this->input->post('role_id')	:	null;
			$filter['company_id'] 	= ($this->input->post('company_id'))? 	$this->input->post('company_id'): 	null;
			$filter['branch_id']	= ($this->input->post('branch_id'))	? 	$this->input->post('branch_id')	: 	null;
			$filter['is_updated'] 	= '1';
				
			$data['filteredUsers'] = $filteredUsers = $this->Customer_model->search($filter);
		}
		$this->load->view('admin/customers/ajax_search_parent_result', $data);
	}
	
	public function ajax_filter_source()
	{
		$this->load->helper('pagination');
		$page = $this->uri->segment(5);
// 		$per_page = 2;
	
// 		$out = new stdClass;
// 		$out->sEcho = intval($sEcho = $this->input->get_post('sEcho'));
// 		$out->iTotalRecords = 4;
// 		$out->iTotalDisplayRecords = 4;
// 		$out->aaData = array();
		
		$iDisplayStart = $this->input->get_post('iDisplayStart');
		$per_page = $this->input->get_post('iDisplayLength');
		$output = array(
				'sEcho' => intval($sEcho = $this->input->get_post('sEcho')),
				'iTotalRecords' => 0,
				'iTotalDisplayRecords' => 0,
				'aaData' => array()
		);
		
		/******* prepare form filter ************/
		$filter = array();
	
// 		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//$company_id = $this->input->get('company_id', TRUE);
			$filter['searchtext'] 	= $this->input->post_get('searchtext');
			$filter['search_field'] = $this->input->post_get('search_field');
			$filter['company_id'] 	= ($this->input->post_get('company_id'))? 	$this->input->post_get('company_id'): 	null;
			$filter['branch_id']	= ($this->input->post_get('branch_id'))	? 	$this->input->post_get('branch_id')	: 	null;
				
// 		}
		$filter['sc_status'] 	= ($this->input->post('sc_status'))?	$this->input->post('sc_status'):	'0';
	
		$filteredUsers = $this->Subscriber_mod->search($filter, $per_page, $iDisplayStart);

		$FOUND_ROWS = 0;
		if (isset($filteredUsers['FOUND_ROWS'])) {
			$FOUND_ROWS = $filteredUsers['FOUND_ROWS'];
			unset($filteredUsers['FOUND_ROWS']);
		}
	
		foreach ($filteredUsers as $item) {
			
			$output['aaData'][] = array(
						$item->subs_id,
						$item->firstname. ' ' .$item->lastname,
						$item->email,
						$item->company_name . '<br />' . $item->branch_name . '<br />' . $item->dep_name,
// 						$item->branch_name,
// 						$item->dep_name,
						$item->sfirstname. ' ' .$item->slastname,
						$item->phone,
						Subscriber_mod::getStatusByID($item->sc_status),
						$item->imported_at,
						$item->updated_at,
						'<a class="btn" href="' . base_url('admin/subscribers/edit/'.$item->subs_id) .'"><i class="cus-page-white-edit"></i>Edit</a>',
					
			);  //$out->aaData[] = $item; //array('subs_name'=>$item->subs_name);
		}
		
		$output['iTotalRecords']=$FOUND_ROWS;
		$output['iTotalDisplayRecords']=$FOUND_ROWS;
		
		header('Content-type: application/json');
		echo json_encode($output);
	}
	
}