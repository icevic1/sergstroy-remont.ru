<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customeracl extends Admin_Controller {
	var $page_id=18;
	function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
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
	
	function roles()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 19);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$this->db->order_by('parent_id ASC, role_id DESC'); //Get the roles
		$query = $this->db->get('smacl_roles');
		
		$data['roles'] = $query->result();
		$data['roleOptions'] =  Acl::simpleRoleArray();
		$data['staff_type'] =  Acl::$staff_type;
// var_dump($data['staff_type']);
		$data['CONTENT']='admin/customeracl/roles';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function roleedit()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$role_id = $this->uri->segment(4, 0);
		$data['customer_role'] = null;
		if ($role_id > 0) {
			$this->db->select('*');
			$this->db->from('smacl_roles');
			$this->db->where('role_id', $role_id);
			$data['customer_role'] = $this->db->get()->row();
			$page_action = 'Edit Role';
		} else 
			$page_action = 'Add new role';
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['customer_role']->role_id)) {
				$this->form_validation->set_rules('role_id', 'Role ID', 'trim|required|min_length[1]|max_length[4]');
			}
			$this->form_validation->set_rules('role_name', 'Role name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('staff_type', 'Staff type', 'trim|numeric');
			$this->form_validation->set_rules('parent_id', 'Parent', 'trim|numeric');
			$this->form_validation->set_rules('role_description', 'Role description', 'trim');
			
			if ($this->form_validation->run() == FALSE)	{
				$data['customer_role']->role_id = $this->form_validation->set_value('role_id');
				$data['customer_role']->role_name = $this->form_validation->set_value('role_name');
				$data['customer_role']->role_description = $this->form_validation->set_value('role_description');
				$data['customer_role']->parent_id = $this->form_validation->set_value('parent_id');
				$data['customer_role']->staff_type = $this->form_validation->set_value('staff_type');
			} else {
				$data_update = array(
						'role_name' => $this->form_validation->set_value('role_name'),
						'role_description' => $this->form_validation->set_value('role_description'),
				);
				
				$data_update['parent_id'] = $this->form_validation->set_value('parent_id');
				$data_update['staff_type'] = $this->form_validation->set_value('staff_type');
				
				$data_update = $this->empty2null($data_update);
				
				$role_id = $this->form_validation->set_value('role_id');
				if ($role_id) {
					//edit role
					$this->db->where('role_id', $this->form_validation->set_value('role_id'));
					$this->db->limit(1);
					$this->db->update('smacl_roles', $data_update);
					$afftectedRows = $this->db->affected_rows();
				} else {
					//add new role
					$data_update['who_create'] = $this->session->userdata('login_name');
					$this->db->insert('smacl_roles', $data_update);
					$role_id = $this->db->insert_id();
				}
// 				$gg = $this->db->last_query();
				
				redirect('admin/customeracl/roles/');
			}
		}

		$data['roleOptions'] =  array_replace(array('' => '---'), Acl::simpleRoleArray());
// 		var_dump($data['roleOptions']);
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 19);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/customeracl/roleEdit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteRole(){
		$role_id= $this->uri->segment(4, 0);
		
		$this->db->delete('smacl_roles', array('role_id' => $role_id), 1);
// 		$gg = $this->db->last_query();
// 		var_dump($role_id, $gg);die;
		$this->session->set_flashdata('msg', $msg);
		redirect('admin/customeracl/roles/');
	}
	
	
	public function resources()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 20);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		
		$data['resources'] = Acl::getAllResources();
		$data['resourceOptions'] =  array_replace(array('0' => 'Choose a parent'), Acl::simpleResourcesArray());
		
		$data['CONTENT']='admin/customeracl/resources';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function resourceEdit()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$resource_id = $this->uri->segment(4, 0);
		$data['customer_resource'] = null;
		if ($resource_id > 0) {
			$data['customer_resource'] = $this->acl->getResourceById($resource_id);
			$page_action = 'Edit resource';
		} else
			$page_action = 'Add new resource';

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['customer_resource']->resource_id)) {
				$this->form_validation->set_rules('resource_id', 'Resource ID', 'trim|required|min_length[1]|max_length[4]');
			}
			$this->form_validation->set_rules('resource_name', 'Resource name', 'trim|required|min_length[2]|max_length[100]');
			// 			$this->form_validation->set_message('resource_name','That %s already exists.');
			$this->form_validation->set_rules('resource_description', 'Resource description', 'trim');
			$this->form_validation->set_rules('parent_id', 'Parent', 'trim');
				
			if ($this->form_validation->run() == FALSE)	{
				$data['customer_resource']->resource_id = $this->form_validation->set_value('resource_id');
				$data['customer_resource']->resource_name = $this->form_validation->set_value('resource_name');
				$data['customer_resource']->resource_description = $this->form_validation->set_value('resource_description');
				$data['customer_resource']->parent_id = $this->form_validation->set_value('parent_id');
			} else {
				$data_update = array(
						'resource_name' => $this->form_validation->set_value('resource_name'),
						'resource_description' => $this->form_validation->set_value('resource_description'),
						'parent_id' => (($this->form_validation->set_value('parent_id'))? $this->form_validation->set_value('parent_id'): null),
				);
	
				$resource_id = $this->form_validation->set_value('resource_id');
				if ($resource_id) {
					//edit role
					$this->db->where('resource_id', $this->form_validation->set_value('resource_id'));
					$this->db->limit(1);
					$this->db->update('smacl_resources', $data_update);
					$afftectedRows = $this->db->affected_rows();
				} else {
					//add new role
					$data_update['who_create'] = $this->session->userdata('login_name');
					$this->db->insert('smacl_resources', $data_update);
					$resource_id = $this->db->insert_id();
				}
				// 				$gg = $this->db->last_query();
	
				redirect('admin/customeracl/resources/');
			}
		}
	
		$data['resourceOptions'] =  array_replace(array('' => '---'), Acl::simpleResourcesArray());
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 20);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/customeracl/resourceEdit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	
	
	public function deleteResource(){
		$resource_id= $this->uri->segment(4, 0);
	
		if ($resource_id > 0)
		$this->db->delete('smacl_resources', array('resource_id' => $resource_id), 1);
// 		$gg = $this->db->last_query();
		// 		var_dump($resource_id, $gg);die;
		$this->session->set_flashdata('msg', $msg);
		redirect('admin/customeracl/resources/');
	}
	
/***************** PERMISSIONS **************************/
		
	public function permissions(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 21);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		
		$this->db->select('pe.*, ro.role_name, ro.role_description, re.resource_name, re.resource_description');
		$this->db->from('smacl_permissions as pe');
		$this->db->join('smacl_roles as ro', 'ro.role_id = pe.role_id', 'inner');
		$this->db->join('smacl_resources as re', 're.resource_id = pe.resource_id', 'inner');
		$this->db->order_by('pe.role_id asc, pe.resource_id ASC');
		$result = $this->db->get()->result();
		$data['permissions'] = $result;
		
		$data['CONTENT']='admin/customeracl/permissions';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function permissionEdit()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$permission_id = $this->uri->segment(4, 0);
		$data['permission'] = null;
		if ($permission_id > 0) {
			$this->db->select('*');
			$this->db->from('smacl_permissions');
			$this->db->where('permission_id', $permission_id);
			$data['permission'] = $this->db->get()->row();
			$page_action = 'Edit permission';
		} else
			$page_action = 'Add new permission';
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($data['permission']->permission_id)) {
				$this->form_validation->set_rules('permission_id', 'Permission ID', 'trim|required|is_natural');
			}
			$this->form_validation->set_rules('role_id', 'Role', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('resource_id', 'Resource', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('description', 'Description', 'trim');
			$this->form_validation->set_rules('read', 'read', 'trim|is_natural');

			if (!($data['permission'] instanceof stdClass)) {
				$data['permission'] = new stdClass;
			}
			
			if ($this->form_validation->run() == FALSE)	{
				$data['permission']->permission_id = $this->form_validation->set_value('permission_id');
				$data['permission']->role_id = $this->form_validation->set_value('role_id');
				$data['permission']->resource_id = $this->form_validation->set_value('resource_id');
				$data['permission']->description = $this->form_validation->set_value('description');
				$data['permission']->read_access = (int)$this->input->post('read');
				$data['permission']->write_access = (int)$this->input->post('write');
				$data['permission']->modify_access = (int)$this->input->post('modify');
				$data['permission']->delete_access = (int)$this->input->post('delete');
			} else {
				$data_update = array(
						'role_id' => (int)$this->form_validation->set_value('role_id'),
						'resource_id' => (int)$this->form_validation->set_value('resource_id'),
						'description' => $this->form_validation->set_value('description'),
						'read_access' => (int)$this->input->post('read'),
						'write_access' => (int)$this->input->post('write'),
						'modify_access' => (int)$this->input->post('modify'),
						'delete_access' => (int)$this->input->post('delete'),
				);

				$permission_id = $this->form_validation->set_value('permission_id');
				if ($permission_id) {
					//edit role
					$this->db->where('permission_id', $permission_id);
					$this->db->limit(1);
					$this->db->update('smacl_permissions', $data_update);
					$afftectedRows = $this->db->affected_rows();
					$insert_id = $permission_id;
				} else {
					//add new role
					$data_update['who_create'] = $this->session->userdata('login_name');
					$this->db->insert('smacl_permissions', $data_update);
					$insert_id = $this->db->insert_id();
				}
				// if $insert_id = 0 then inserted data lost, bec role_id resource_id is unique
				if ($insert_id) {
					redirect('admin/customeracl/permissions/');
				} else {
					$data['permission']->role_id = $this->form_validation->set_value('role_id');
					$data['permission']->resource_id = $this->form_validation->set_value('resource_id');
					$data['error'] = 'The permission is already taken';
				}
				
			}
		}
		
		$roles = array('0' => 'Choose a role');
		foreach (Acl::$db_roles as $item) {
			$roles[$item->role_id] = $item->role_name;
		}
		
		$resources = array('0' => 'Choose a resource');
		foreach (Acl::$db_resources as $item) {
			$resources[$item->resource_id] = $item->resource_name;
		}
		
		$data['roles'] = $roles;
		$data['resources'] = $resources;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 20);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT']='admin/customeracl/permissionEdit';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deletePermission(){
		$permission_id= $this->uri->segment(4, 0);
	
		if ($permission_id > 0)	$this->db->delete('smacl_permissions', array('permission_id' => $permission_id), 1);
		redirect('admin/customeracl/permissions/');
	}
}