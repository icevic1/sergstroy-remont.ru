<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Productitem extends Admin_Controller {
	var $page_id=11;
	var $act_msg;
	function __construct() {
		parent::__construct();
		$this->act_msg=array('Save Failed.','Save Succeed.','Delete Failed.','Delete Succeed.');
	}
	function index(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['items']=$this->selfcare_mod->get_product_items();
		$data['CONTENT']='admin/productitem/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function add(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['groups']=$this->selfcare_mod->get_product_group_option();
		$data['CONTENT']='admin/productitem/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['item']=$this->selfcare_mod->get_product_item($this->uri->segment(4, 0));
		$data['groups']=$this->selfcare_mod->get_product_group_option();
		$data['CONTENT']='admin/productitem/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$h_acc_ordinal=$this->input->post('h_acc_ordinal');
		$acc_ordinal=$this->input->post('acc_ordinal');
		$pp_name=$this->input->post('pp_name');
		$group_id=$this->input->post('group_id');
		$is_roaming=$this->input->post('is_roaming')?1:0;
		$res=0;
		if($h_acc_ordinal){
			$res=$this->selfcare_mod->update_product_item($h_acc_ordinal,$acc_ordinal,$pp_name,$group_id,$is_roaming,$this->login_name);
		}else{
			$res=$this->selfcare_mod->save_product_item($acc_ordinal,$pp_name,$group_id,$is_roaming,$this->login_name);
		}
		$this->session->set_flashdata('msg',$this->act_msg[$res]);
		redirect(base_url('admin/productitem'));
	}
	function delete(){
		$res=$this->selfcare_mod->delete_product_item($this->uri->segment(4, 0),$this->login_name);
		$msg=$res==1?$this->act_msg[3]:$this->act_msg[3];
		$this->session->set_flashdata('msg',$msg);
		redirect(base_url('admin/productitem'));
	}
}