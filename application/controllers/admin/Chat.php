<?php
class Chat extends Admin_Controller {
	var $page_id=4;
	var $login_name;
	function __construct() {
		parent::__construct();
		$this->load->model('chat_mod');
		$this->login_name=$this->session->userdata('login_name');
	}
	function index(){
		
		$data['chat']=$this->chat_mod->get_chat_list($this->login_name);
		$data['chat_user']=$this->selfcare_mod->get_chat_user($this->login_name);
		if(!$data['chat_user']){
			$this->selfcare_mod->save_chat_user($this->login_name,'Operation',1);
			$data['chat_user']=$this->selfcare_mod->get_chat_user($this->login_name);
		}
		$data['PAGE_TITLE']="Chat";
		$data['CONTENT']='admin/chat/index';
		$this->load->view('template/tmpl_admin_login',$data);
	}
}
?>