<?php
class Caption extends Admin_Controller {
	var $page_id=3;
	function index(){
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['languages']=$this->selfcare_mod->get_language();
		$data['captions']=$this->selfcare_mod->get_caption_list();
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/caption/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function save(){
		$l_id=$this->input->post('hd_l_id');
		$capt_id=$this->input->post('hd_capt_id');
		$translate=$this->input->post('txt_translate');
		$values='';
		for($i=0;$i<sizeof($l_id);$i++){
			$tsl=trim(str_replace("'","''",$translate[$i]));
			$this->selfcare_mod->update_caption($tsl,$l_id[$i],$capt_id[$i],$this->login_name);
		}
		redirect(base_url('admin/caption'));
	}
	function edit()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$data['caption'] = null;
		$data['capt_id'] = $this->uri->segment(4, '');
		$dbCaption = $this->selfcare_mod->get_caption($data['capt_id']);
		foreach ($dbCaption as $item) {
			$data['caption'][$item->l_id] = $item;
		}
		$data['languages'] = $this->selfcare_mod->get_language();
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
			$this->form_validation->set_rules('capt_id', 'Caption ID', 'trim');
			if (!$data['capt_id']) {$this->form_validation->set_rules('capt_id', 'Caption ID', 'trim|required');}
			$this->form_validation->set_rules('hd_l_id[]', 'Language ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('hd_capt_id[]', 'Each edited caption ID', 'trim|alpha_dash');
			$this->form_validation->set_rules('txt_translate[]', 'Caption translation', 'trim');
			
			if ($this->form_validation->run() == true)	{
				//$data['customer']->user_id = $this->form_validation->set_value('user_id');
				$txt_translate = $this->input->post('txt_translate[]');
				$capt_id = $this->form_validation->set_value('capt_id');
				$languages_ids = $this->input->post("hd_l_id[]");
				
				
				$data_update = array();
				foreach ($languages_ids as $key => $l_id) {
					$data_update = array (
						'translate' => $txt_translate[$key],
						'modified_by' => $this->login_name,	
						);
					if ($data['capt_id']) {
						$this->selfcare_mod->updateCaption($capt_id, $l_id, $data_update);
					} else {
						$data_update['capt_id'] = $capt_id;
						$data_update['l_id'] = $l_id;
						
						$this->selfcare_mod->addCaption($data_update);
					}
				}

				redirect('admin/caption/edit/'.$capt_id);
			} 
			
		}
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/caption/form';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function deleteCaption(){
		$capt_id = $this->uri->segment(4, 0);
		$this->selfcare_mod->deleteCaption($capt_id);
		redirect('admin/caption/');
	}
}
?>