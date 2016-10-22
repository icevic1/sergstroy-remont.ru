<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Page extends Admin_Controller {
	var $page_id=6;
	function index()
	{
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['tmpls']=$this->selfcare_mod->get_dynamic_pages();
		$data['CONTENT']='admin/page/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function add(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['details']=$this->selfcare_mod->get_dynamic_detail_page(0);
		$data['CONTENT']='admin/page/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function edit(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$pg_id= $this->uri->segment(4, 0);
		$data['page']=$this->selfcare_mod->get_dynamic_page($pg_id);
//		$data['details']=$this->selfcare_mod->get_dynamic_detail_page($pg_id);
		$data['CONTENT']='admin/page/edit';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$pg_id=$this->input->post('hd_pg_id');
		$pg_name=$this->input->post('txt_pg_name');
		$pg_url=$this->input->post('txt_pg_url');
		$pg_script=htmlspecialchars(trim(str_replace("'","''",$this->input->post('txt_pg_script'))));
		$l_id=$this->input->post('hd_lid');
		$pg_content=$this->input->post('txt_content');
		$is_public=$this->input->post('chk_public')?1:0;
		if($pg_id){
			$pg_id=$this->selfcare_mod->update_dynamic_page($pg_id,$pg_name,$pg_url,$is_public,$pg_script,$this->login_name, $pg_content);
		}else{
			$pg_id=$this->selfcare_mod->create_dynamic_page($pg_name,$pg_url,$is_public,$pg_script,$this->login_name, $pg_content);
		}

        $this->session->set_flashdata('msg', $this->config->item('msg_succeed'));
        redirect(base_url('admin/page'));
	}
	function block(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['pg_id']=$this->uri->segment(4, 0);
		$data['blocks']=$this->selfcare_mod->get_blocks($this->uri->segment(4, 0));
		$data['CONTENT']='admin/page/block';
		$this->load->view('template/tmpl_admin',$data);
		
	}
	function save_block(){
		$pg_id=$this->input->post('pg_id');
		$block_id_arr=$this->input->post('block_id');
		$this->selfcare_mod->save_block_page($pg_id,$block_id_arr,$this->login_name);
		redirect(base_url('admin/page'));
	}
	function view(){
		$pg_id= $this->uri->segment(4, '');
		$html=$this->selfcare_mod->get_dmp_by_name($this->lang->lang(),$pg_id);
		$data['block']=$this->selfcare_mod->get_block($pg_id);
		$data['html']=$html['pg_content'];
		$this->load->view('template/tmpl_site',$data);
	}
}