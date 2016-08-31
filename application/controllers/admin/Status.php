<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Status extends Admin_Controller {
	var $page_id=8;
	function index()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['status']=$this->selfcare_mod->get_subscriber_statuses();
		$data['CONTENT']='admin/status/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$status_id=$this->uri->segment(4, 0);
		$data['status_id']=$status_id;
		$data['status']=$this->selfcare_mod->get_subscriber_status($status_id);
		$data['CONTENT']='admin/status/form';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$l_id=$this->input->post('hd_l_id');
		$status_id=$this->input->post('hd_status_id');
		$translate=$this->input->post('txt_translate');
		$desc_translate=$this->input->post('txt_desc_translate');
		$values='';
		for($i=0;$i<sizeof($l_id);$i++){
			$tsl=trim(str_replace("'","''",$translate[$i]));
			$desc_tsl=trim(str_replace("'","''",$desc_translate[$i]));
			$this->selfcare_mod->update_subscriber_status($tsl,$desc_tsl,$l_id[$i],$status_id[$i],$this->login_name);
		}
		redirect(base_url('admin/status'));
	}
}
?>