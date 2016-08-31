<?php
class FAQ extends Admin_Controller {
	var $page_id=5;
	function index(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['faqs']=$this->selfcare_mod->get_faq_list($this->lang->lang());
		$data['CONTENT']='admin/faq/index';
		$data['PAGE_TITLE']='FAQ';
		$this->load->view('template/tmpl_admin',$data);
	}
	function add(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['languages']=$this->selfcare_mod->get_language();
		$data['CONTENT']='admin/faq/form';
		$data['PAGE_TITLE']='FAQ';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$faq_id=$this->input->post('hd_faq_id');
		$l_id_arr=$this->input->post('hd_lid');
		$question_arr=$this->input->post('txt_question');
		$answer_arr=$this->input->post('txt_answer');
		if(empty($faq_id)){
			$faq_id=$this->selfcare_mod->save_faq($this->login_name);
		}else{
			$this->selfcare_mod->update_faq($faq_id,$this->login_name);
		}
		for($i=0;$i<sizeof($l_id_arr);$i++){
			if($question_arr[$i] && $answer_arr[$i]){
				$this->selfcare_mod->save_faq_detail($l_id_arr[$i],$faq_id,$question_arr[$i],$answer_arr[$i]);
			}
		}
		redirect('admin/faq');
	}
	function edit($faq_id){
		$data['faq_id']=$faq_id;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['languages']=$this->selfcare_mod->get_language();
		$data['faq']=$this->selfcare_mod->get_faq_detail($faq_id);
		$data['CONTENT']='admin/faq/form';
		$data['PAGE_TITLE']='FAQ';
		$this->load->view('template/tmpl_admin',$data);
	}
}