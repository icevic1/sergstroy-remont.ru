<?php
class Message extends Admin_Controller {
	var $page_id=2;
	function index(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['languages']=$this->selfcare_mod->get_language();
		$data['message']=$this->selfcare_mod->get_message_list();
		$data['CONTENT']='admin/message/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['msg_id']=$this->uri->segment(4, 0);
		$data['message']=$this->selfcare_mod->get_message($this->uri->segment(4, 0));
		$data['languages']=$this->selfcare_mod->get_language();
		$data['CONTENT']='admin/message/form';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$l_id=$this->input->post('hd_l_id');
		$msg_id=$this->input->post('hd_msg_id');
		$translate=$this->input->post('txt_translate');
		$values='';
		for($i=0;$i<sizeof($l_id);$i++){
			$tsl=trim(str_replace("'","''",$translate[$i]));
			$this->selfcare_mod->update_message($tsl,$l_id[$i],$msg_id[$i],$this->login_name);
		}
		redirect(base_url('admin/message'));
	}
}
?>