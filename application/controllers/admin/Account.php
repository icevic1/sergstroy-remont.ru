<?php
class Account extends My_Controller {
	function index(){
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		clearstatcache();
		$data['PAGE_TITLE']="Login";
		$data['LOGIN']=true;
		$data['CONTENT']='admin/account/login';
		$this->load->view('template/tmpl_admin_login',$data);
	}
	function login(){
		$this->load->library('cryptastic');
		$username=$this->input->post('txt_username');
		$pwd=$this->input->post('txt_pwd');
		$res=$this->selfcare_mod->check_user($username,$pwd);
		if($res['err']==0){
			$this->session->set_userdata('login_name', $username);
			redirect(base_url('admin/home/'));
		}else{
			$this->session->set_flashdata('msg', $res['msg']);
			redirect(base_url('admin/account/'));
		}
		

	}
	function logout(){
		$this->session->sess_destroy();
		redirect(base_url('admin/account'));
	}
}
?>