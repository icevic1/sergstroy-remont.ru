<?php
class Home extends Admin_Controller {
	function index(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['CONTENT']='admin/home/index';
		$data['PAGE_TITLE']='Home';
		$this->load->view('template/tmpl_admin',$data);
	}
}
?>