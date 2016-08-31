<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Masteracc extends Admin_Controller 
{
	var $page_id=23;
	
	function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Masteracc_model');
	}
	
	function index()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['menus_page']=$this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['users']=$this->selfcare_mod->get_users();
		$data['CONTENT']='admin/customeracl/index';
		$this->load->view('template/tmpl_admin',$data);
	}
/*================== COMPANIES =========================*/	
	function companies()
	{
		$this->load->helper('text');
		
// 		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['menus']=$this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 25);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		
		$companies = $this->db->select('*')
			->from('sm_companies')
			->get()
			->result();
		
		$data['companies'] = $companies;
		$data['CONTENT']='admin/masteracc/companies';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function saveCompany()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$company_id = $this->uri->segment(4, 0);
		$data['company'] = null;
		if ($company_id > 0) {
			$data['company'] = $this->db->select('*')->from('sm_companies')->where('company_id', $company_id)->get()->row();
			$page_action = 'Edit company';
		} else
			$page_action = 'Add new company';
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->form_validation->set_rules('company_id', 'Company ID', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('picassigned_type', 'PICs assigned type', 'trim|is_natural');
			$this->form_validation->set_rules('sc_status', 'Company selfcare status', 'trim|is_natural');
	
			if (!($data['company'] instanceof stdClass)) {
				$data['company'] = new stdClass;
			}
				
			if ($this->form_validation->run() == FALSE)	{
				$data['company']->company_id = $this->form_validation->set_value('company_id');
				$data['company']->picassigned_type = $this->form_validation->set_value('picassigned_type');
				$data['company']->sc_status = $this->form_validation->set_value('sc_status');
			} else {
				$data_update = array(
						'picassigned_type' => $this->form_validation->set_value('picassigned_type'),
						'sc_status' => $this->form_validation->set_value('sc_status')
				);

				$company_id = (int)$this->form_validation->set_value('company_id');
				$this->Masteracc_model->updateCompany($company_id, $data_update);
				redirect('admin/masteracc/companies/');
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 25);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/masteracc/saveCompany';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteCompany(){
		$company_id = $this->uri->segment(4, 0);
	
		if ($company_id > 0) {
			$this->db->delete('sm_branches', array('company_id' => $company_id));
			$this->db->delete('sm_departments', array('company_id' => $branch_id));
			$this->db->delete('sm_companies', array('company_id' => $company_id), 1);
		}
		redirect('admin/masteracc/companies/');
	}
	
	/*================== COMPANIES BRANCHES=========================*/	
	function branches()
	{
// 		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['menus']=$this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 26);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
	
		$branches = $this->db->select('*')
		->from('sm_branches')
		->get()
		->result();
		
		$companiesList = $this->db->select('*')->from('sm_companies')->get()->result();
		$companies = array();
		foreach ($companiesList as $item) {
			$companies[$item->company_id] = $item->company_name;
		}
	
		$data['branches'] = $branches;
		$data['companies'] = $companies;
		$data['CONTENT']='admin/masteracc/branches';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function saveBranch()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$branch_id = $this->uri->segment(4, 0);
		$data['branch'] = null;
		if ($branch_id > 0) {
			$data['branch'] = $this->db->select('*')->from('sm_branches')->where('branch_id', $branch_id)->get()->row();
			$page_action = 'Edit branch';
		} else
			$page_action = 'Add new branch';
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['branch']->branch_id)) {
				$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim|required|is_natural_no_zero');
			}
			$this->form_validation->set_rules('branch_name', 'Branch name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('branch_address', 'Brache address', 'trim|min_length[2]|max_length[255]');
			$this->form_validation->set_rules('company_id', 'Company name', 'trim|required|is_natural_no_zero');
	
			if (!($data['branch'] instanceof stdClass)) {
				$data['branch'] = new stdClass;
			}
	
			if ($this->form_validation->run() == FALSE)	{
				$data['branch']->branch_id = $this->form_validation->set_value('branch_id');
				$data['branch']->branch_name = $this->form_validation->set_value('branch_name');
				$data['branch']->branch_address = $this->form_validation->set_value('branch_address');
				$data['branch']->company_id = $this->form_validation->set_value('company_id');
			} else {
				$data_update = array(
						'branch_name' => $this->form_validation->set_value('branch_name'),
						'branch_address' => $this->form_validation->set_value('branch_address'),
						'company_id' => (int)$this->form_validation->set_value('company_id'),
				);
	
				$branch_id = (int)$this->form_validation->set_value('branch_id');
				if ($branch_id) {
					//edit branch
					$this->db->where('branch_id', $branch_id);
					$this->db->limit(1);
					$this->db->update('sm_branches', $data_update);
					$afftectedRows = $this->db->affected_rows();
					$insert_id = $branch_id;
				} else {
					//add new role
					$data_update['who_create'] = $this->session->userdata('login_name');
					$this->db->insert('sm_branches', $data_update);
					$insert_id = $this->db->insert_id();
				}
	
				redirect('admin/masteracc/saveBranch/'.(($insert_id)?$insert_id:''));
			}
		}
	
		$companiesList = $this->db->select('*')->from('sm_companies')->get()->result();
		$companies = array('0' => 'Choose an company');
		foreach ($companiesList as $item) {
			$companies[$item->company_id] = $item->company_name;
		}

		$data['companies'] = $companies;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 26);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/masteracc/saveBranch';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteBranch()
	{
		$branch_id = $this->uri->segment(4, 0);
	
		if ($branch_id > 0)	
			$this->db->delete('sm_branches', array('branch_id' => $branch_id), 1);
		
		$this->db->delete('sm_departments', array('branch_id' => $branch_id));
		
		redirect('admin/masteracc/branches/');
	}
	
	function ajax_get_company_branches()
	{
		$company_id = $this->input->get('company_id', TRUE); 
		$branchesList = $this->Masteracc_model->getCompanyBranches($company_id, true);
		
		if ($branchesList) {
			$branchesList = array_replace(array('0' => '---'), $branchesList);
		} else {
			$branchesList = array('0' => '---');
		}
		
		echo form_dropdown('branch_id', $branchesList, 0, 'id="branch_id"');
	}
	function ajax_get_branch_departments()
	{
		$company_id = $this->input->get('company_id', TRUE); 
		$branch_id = $this->input->get('branch_id', TRUE); 
		$depList = $this->Masteracc_model->getDepartments($company_id, $branch_id, TRUE);
		
		if ($depList) {
			$depList = array_replace(array('0' => '---'), $depList);
		} else {
			$depList = array('0' => '---');
		}
		
		echo form_dropdown('dep_id', $depList, 0, 'id="dep_id"');
	}
	
	/*================== MASTER ACOUNT =========================*/	
	function maccList()
	{
		$data['maccList'] = $this->db->select('*')->from('sm_master_acc')->get()->result();
		
		$companiesList = $this->db->select('*')->from('sm_companies')->get()->result();
		$companies = array();
		foreach ($companiesList as $item) {
			$companies[$item->company_id] = $item->company_name;
		}
		
		$data['companies'] = $companies;
		
		$branchesList = $this->db->select('*')->from('sm_branches')->get()->result();
		$branches = array();
		foreach ($branchesList as $item) {
			$branches[$item->branch_id] = $item->branch_name;
		}
		$data['branches'] = $branches;
		
// 		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['menus']=$this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 24);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/masteracc/macclist';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function saveMacc()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$macc_id = $this->uri->segment(4, 0);
		$data['macc'] = null;
		if ($macc_id > 0) {
			$data['macc'] = $this->db->select('*')->from('sm_master_acc')->where('macc_id', $macc_id)->get()->row();
			$page_action = 'Edit master acount';
		} else
			$page_action = 'Add new master acount';
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['macc']->macc_id)) {
				$this->form_validation->set_rules('macc_id', 'ID', 'trim|required|is_natural_no_zero');
			}
			$this->form_validation->set_rules('maccBillID', 'Master acount ID', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('macc_name', 'Master acount name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('macc_description', 'Description', 'trim|min_length[2]|max_length[1000]');
			$this->form_validation->set_rules('company_id', 'Company', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('branch_id', 'Branch', 'trim|is_natural');
	
			if (!($data['macc'] instanceof stdClass)) {
				$data['macc'] = new stdClass;
			}
	
			if ($this->form_validation->run() == FALSE)	{
				$data['macc']->macc_id = $this->form_validation->set_value('macc_id');
				$data['macc']->maccBillID = $this->form_validation->set_value('maccBillID');
				$data['macc']->macc_name = $this->form_validation->set_value('macc_name');
				$data['macc']->macc_description = $this->form_validation->set_value('macc_description');
				$data['macc']->company_id = $this->form_validation->set_value('company_id');
				$data['macc']->branch_id = $this->form_validation->set_value('branch_id');
			} else {
				$data_update = array(
						'maccBillID' => $this->form_validation->set_value('maccBillID'),
						'macc_name' => $this->form_validation->set_value('macc_name'),
						'macc_description' => $this->form_validation->set_value('macc_description'),
						'company_id' => (int)$this->form_validation->set_value('company_id'),
						'branch_id' => (($this->form_validation->set_value('branch_id'))? $this->form_validation->set_value('branch_id') : null),
				);
	
				$macc_id = (int)$this->form_validation->set_value('macc_id');
				if ($macc_id) {
					//edit macc
					$this->db->where('macc_id', $macc_id);
					$this->db->limit(1);
					$this->db->update('sm_master_acc', $data_update);
					$afftectedRows = $this->db->affected_rows();
					$insert_id = $macc_id;
				} else {
					//add new role
					$data_update['who_create'] = $this->session->userdata('login_name');
					$this->db->insert('sm_master_acc', $data_update);
					$insert_id = $this->db->insert_id();
				}
	
				redirect('admin/masteracc/saveMacc/'.(($insert_id)? $insert_id:''));
			}
		}
		
		$companiesList = $this->db->select('*')->from('sm_companies')->get()->result();
		$companies = array('0' => 'Choose an company');
		foreach ($companiesList as $item) {
			$companies[$item->company_id] = $item->company_name;
		}
		$data['companies'] = $companies;
		
		$branches = array('0' => 'Choose an branch');
		if (isset($data['macc']->company_id) && $data['macc']->company_id > 0) {
			$branchList = $this->db->select('*')->from('sm_branches')->where('company_id', $data['macc']->company_id)->get()->result();	
			foreach ($branchList as $item) {
				$branches[$item->branch_id] = $item->branch_name;
			}
		}
		$data['branches'] = $branches;
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 26);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/masteracc/saveMacc';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteMacc(){
		$macc_id = $this->uri->segment(4, 0);
	
		if ($macc_id > 0)	$this->db->delete('sm_master_acc', array('macc_id' => $macc_id), 1);
		redirect('admin/masteracc/maccList/');
	}
	
	
	/*================== DEPARTMENTS FROM BRANCHES=========================*/
	function departments()
	{
		$data['menus']=$this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 35);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
	
		$departments = $this->Masteracc_model->getDepartments();
// 		var_dump($departments);die;
		$data['departments'] = $departments;
// 		$data['companies'] = $companies;
		$data['CONTENT']='admin/masteracc/departments';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function saveDepartment()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$dep_id = $this->uri->segment(4, 0);
		$data['department'] = null;
		$selectedCompanyID = null;
		$companyBranches = array('---');
		
		if ($dep_id > 0) {
			$data['department'] = $this->Masteracc_model->getDepartmentByID($dep_id);
			$page_action = 'Edit branch';
			if ($data['department']->company_id) {
				$selectedCompanyID = $data['department']->company_id;
			}
			
		} else
			$page_action = 'Add new branch';
	
// 		var_dump($dep_id, $data['department']);die;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['department']->dep_id)) {
				$this->form_validation->set_rules('dep_id', 'Department ID', 'trim|required|is_natural_no_zero');
			}
			$requireBranch = '';
			if ($this->input->post('company_id') > 0 && $this->Masteracc_model->countCompanyBranches($this->input->post('company_id'))) {
				$requireBranch = '|required|is_natural_no_zero';
			}
			
			$this->form_validation->set_rules('dep_name', 'Department name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('company_id', 'Company name', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('branch_id', 'Branch name', 'trim'.$requireBranch);
			
			$this->form_validation->set_message('is_natural_no_zero', 'The %s field need be choosed!');
			$this->form_validation->set_message('is_natural', 'The %s field need be choosed!');
	
			if (!($data['department'] instanceof stdClass)) {
				$data['department'] = new stdClass;
			}
	
			if ($this->form_validation->run() == FALSE)	{
				$data['department']->dep_id = $this->form_validation->set_value('dep_id');
				$data['department']->dep_name = $this->form_validation->set_value('dep_name');
				$data['department']->company_id = $this->form_validation->set_value('company_id');
				$data['department']->branch_id = $this->form_validation->set_value('branch_id');
				$selectedCompanyID = $this->form_validation->set_value('company_id');
			} else {
				$data_update = array(
						'dep_name' => $this->form_validation->set_value('dep_name'),
						'company_id' => (int)$this->form_validation->set_value('company_id'),
						'branch_id' => ($this->form_validation->set_value('branch_id'))?$this->form_validation->set_value('branch_id'): null,
				);
	
				$dep_id = (int)$this->form_validation->set_value('dep_id');

				if ($dep_id) {
					//edit branch
					$this->Masteracc_model->editDepartmentByID($dep_id, $data_update);
				} else {
					//add new role
					$this->Masteracc_model->addDepartment($data_update);
				}
				redirect('admin/masteracc/departments/');
			}
		}
	
		$companies = array_replace(array('0' => '---'), Masteracc_model::getAllCompanies(true));
		
		$companyBranchesDB = $this->Masteracc_model->getCompanyBranches($selectedCompanyID, true);
		$companyBranches = array_replace($companyBranches, $companyBranchesDB);
	
// 		var_dump($companyBranches, $companyBranchesDB);die;
		$data['companies'] = $companies;
		$data['companyBranches'] = $companyBranches;
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 35);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/masteracc/saveDepartment';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteDepartment($dep_id = null)
	{
		$this->Masteracc_model->deleteDepartmentByID($dep_id);
		
		redirect('admin/masteracc/departments/');
	}
}