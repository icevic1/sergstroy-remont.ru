<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Variable extends Admin_Controller {
	var $page_id=7;
	function index()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/variable/index';
		$data['variables']=$this->selfcare_mod->get_gb_variales();
		$this->load->view('template/tmpl_admin',$data);
	}
	function add(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['scopes']=$this->selfcare_mod->get_scope_list();
		$data['CONTENT']='admin/variable/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$var_id=$this->uri->segment(4, 0);
		$data['scopes']=$this->selfcare_mod->get_scope_list();
		$data['variable']=$this->selfcare_mod->get_gb_variale($var_id);
		$data['CONTENT']='admin/variable/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$var_id=$this->security->xss_clean($this->input->post('hd_var_id'));
		$var_key=$this->security->xss_clean($this->input->post('txt_var_key'));
		$var_val=$this->security->xss_clean($this->input->post('txt_var_val'));
		$var_desc=$this->security->xss_clean($this->input->post('txt_var_desc'));
		$scope_id=$this->security->xss_clean($this->input->post('cbo_scope_id'));
		$msg='';
		if($var_id){
			$msg=$this->selfcare_mod->update_variable($var_id,$var_key,$var_val,$var_desc,$scope_id,$this->login_name);
		}else{
			$msg=$this->selfcare_mod->save_variable($var_key,$var_val,$var_desc,$scope_id,$this->login_name);
		}
		$this->session->set_flashdata('msg',$msg);
		redirect(base_url('admin/variable'));
	}
	function delete(){
		$var_id=$this->uri->segment(4, 0);
		$msg=$this->selfcare_mod->delete_variable($var_id);
		$this->session->set_flashdata('msg',$msg);
		redirect(base_url('admin/variable'));
	}
}