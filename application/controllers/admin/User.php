<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends Admin_Controller {
	var $page_id=10;
	function __construct() {
		parent::__construct();
		$this->load->library('cryptastic');
	}
	function index()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['users']=$this->selfcare_mod->get_users();
		$data['CONTENT']='admin/user/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function add(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/user/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$this->load->library('cryptastic');
		$this->config->load('my_config');
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$user_id=$this->uri->segment(4, 0);
		$data['user']=$this->selfcare_mod->get_user($user_id);
		if($data['user']){
            $key = $this->config->item('encryption_key');						
            $data['user']['password'] = $this->cryptastic->decrypt($data['user']['password'], $key, true);
        }
		$data['CONTENT']='admin/user/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$user_id=$this->input->post('hd_user_id');
		$user_name=$this->input->post('txt_user_name');
		$pwd=$this->input->post('txt_pwd');
		$key = $this->config->item('encryption_key');
		$pwd=$this->cryptastic->encrypt($pwd, $key, true);
		$msg='';
		if($user_id){
			$msg=$this->selfcare_mod->update_user($user_id,$user_name,$pwd,$this->login_name);
		}else{
			$msg=$this->selfcare_mod->save_user($user_name,$pwd,$this->login_name);
		}
		$this->session->set_flashdata('msg', $msg);
		redirect(base_url('admin/user/'));
	}
	function delete(){
		$user_id= $this->uri->segment(4, 0);
		$msg=$this->selfcare_mod->delete_user($user_id,$this->login_name);
		$this->session->set_flashdata('msg', $msg);
		redirect(base_url('admin/user/'));
	}
	function permission(){
		$user_name=$this->uri->segment(4, 0);
		$data['user_name']=$user_name;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['perms']=$this->selfcare_mod->get_per_page($user_name);
		$data['CONTENT']='admin/user/permission';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save_per_page(){
		$user_name=$this->input->post('hd_user_name');
		$pg_id_arr=$this->input->post('hd_pg_id');
		$this->selfcare_mod->remove_per_page($user_name);
		for($i=0;$i<sizeof($pg_id_arr);$i++){
			$view=$this->input->post('chkshow_'.$pg_id_arr[$i])?1:0;
			$save=$this->input->post('chksave_'.$pg_id_arr[$i])?1:0;
			$update=$this->input->post('chkupdate_'.$pg_id_arr[$i])?1:0;
			$delete=$this->input->post('chkdelete_'.$pg_id_arr[$i])?1:0;
			if($view==1 || $save==1 || $update==1 || $delete==1)
			$this->selfcare_mod->save_per_page($pg_id_arr[$i],$user_name,$view,$save,$update,$delete);
			//echo $pg_id_arr[$i].','.$user_name.','.$view.','.$save.','.$update.','.$delete;
		}
		redirect('admin/user');
	}
}
?>