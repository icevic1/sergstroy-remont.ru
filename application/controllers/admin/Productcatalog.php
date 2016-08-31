<?php
class Productcatalog extends Admin_Controller {
	
	var $page_id=11;
	private $def_viw_path = 'productcatalog';
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Offer_model');
	}
	
	function index()
	{
		
// 		$data['pcs'] = $this->selfcare_mod->get_product_catalog_offer_list();
		$data['offer_list'] = $this->Offer_model->getOfferList();
// 		var_dump($data['offer_list']);die;
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/productcatalog/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function edit_offer($web_offer_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		
		$data['offer'] = $this->Offer_model->getOfferByWebId($web_offer_id);
		if ($data['offer']) {
			$data['loadedFreeUnits'] = $this->Offer_model->getOfferValues($web_offer_id);
		}
		
// 		var_dump($data['loadedFreeUnits']);die;
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('web_offer_id', 'Web Offer Id', 'trim|numeric');
			$this->form_validation->set_rules('web_name', 'Web Offer Name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('offering_id', 'NGBSS Offering Id', 'trim|numeric');
			$this->form_validation->set_rules('offering_name', 'NGBSS Offer Name', 'trim|max_length[100]');
			$this->form_validation->set_rules('offering_code', 'NGBSS Offer Code', 'trim|max_length[100]');
			
			$this->form_validation->set_rules('offer_cost', 'Offer cost', 'trim|numeric');
			$this->form_validation->set_rules('offer_credit', 'Offer credit', 'trim|numeric');
			$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|max_length[100]');
			
			$this->form_validation->set_rules('group_id', 'Offer Gorup', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('offer_type', 'Offer Type', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('remark', 'Remark', 'trim');
			
			if ($this->form_validation->run() != FALSE)	{
				$form_web_offer_id = $this->form_validation->set_value('web_offer_id');
				$input_freeunits = $this->input->post('freeunits');
				
				
				$data_update = array(
						'web_name' => $this->form_validation->set_value('web_name'),
						'offering_id' => $this->form_validation->set_value('offering_id'),
						'offering_name' => $this->form_validation->set_value('offering_name'),
						'offering_code' => $this->form_validation->set_value('offering_code'),
						'offer_cost' => $this->form_validation->set_value('offer_cost'),
						'offer_credit' => $this->form_validation->set_value('offer_credit'),
						'payment_method' => $this->form_validation->set_value('payment_method'),
						'group_id' => $this->form_validation->set_value('group_id'),
						'offer_type' => $this->form_validation->set_value('offer_type'),
						'remark' => $this->form_validation->set_value('remark')
					);

				if ($form_web_offer_id) {
					$afftectedRows = $this->Offer_model->updateOffer($form_web_offer_id, $data_update);
				} else {
					$form_web_offer_id = $this->Offer_model->insertOffer($data_update);
				}
		
				/**
				 * Keep free units
				 */
				if ($input_freeunits) {
					foreach ($input_freeunits as $item) {
						$item = $this->empty2null($item);
							
						$item['web_offer_id'] = $form_web_offer_id;
	
						if ($item['value_id'] > 0) {
							$this->Offer_model->updateFreeUnit($item['value_id'], $item);
						} else {
							$this->Offer_model->insertFreeUnit($item);
						}
					}
				}
				
				$this->session->set_userdata('msg', 'Data saved successfully!');
				redirect('admin/productcatalog/edit_offer/'.$form_web_offer_id);
			} else {
				$data['offer'] = $this->input->post();
				$data['loadedFreeUnits'] = $this->input->post('freeunits');
// 				$data['loadedQuestions'] = $this->input->post('Questions');
			}
		}
		
		$data['offer_groups'] = array_replace(array('0' => '---'), $this->Offer_model->getGroups(null, null, true));
		$data['offer_types'] = array_replace(array('0' => '---'), $this->Offer_model->getTypes(true));
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = 'admin/productcatalog/edit_offer';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function ajax_delete_freeunit()
	{
		$value_id = $this->input->get_post('value_id');
		$this->Offer_model->deleteFreeUnit($value_id);
	}
	
	function groups()
	{
		$data['list'] = $this->Offer_model->getGroups();
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		$data['CONTENT']='admin/productcatalog/groups';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	public function edit_group($group_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
	
		$data['group'] = $this->Offer_model->getGroup($group_id);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
			$this->form_validation->set_rules('group_id', 'Group Id', 'trim|numeric');
			$this->form_validation->set_rules('group_name', 'group_name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('CustId', 'CustId', 'trim|numeric');
			$this->form_validation->set_rules('weight', 'Order weight', 'trim|numeric');
				
			$this->form_validation->set_rules('offer_type', 'Offer Type', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('addition', 'addition', 'trim');
				
			if ($this->form_validation->run() != FALSE)	{
				$form_group_id = $this->form_validation->set_value('group_id');
	
				$data_update = array(
						'group_name' => $this->form_validation->set_value('group_name'),
						'CustId' => $this->form_validation->set_value('CustId'),
						'weight' => $this->form_validation->set_value('weight'),
						'offer_type' => $this->form_validation->set_value('offer_type'),
						'addition' => $this->form_validation->set_value('addition')
				);
	
				if ($form_group_id) {
					$afftectedRows = $this->Offer_model->updateGroup($form_group_id, $data_update);
				} else {
					$form_group_id = $this->Offer_model->insertGroup($data_update);
				}
	
				$this->session->set_userdata('msg', 'Data saved successfully!');
				redirect('admin/productcatalog/edit_group/'.$form_group_id);
			} else {
				$data['group'] = $this->input->post();
			}
		}
	
		$data['offer_types'] = array_replace(array('0' => '---'), $this->Offer_model->getTypes(true));
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = 'admin/productcatalog/edit_group';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function delete_group($group_id = null)
	{
		if (!$group_id) show_404();
		$this->Offer_model->deleteGroup($group_id);
		
		$this->session->set_userdata('msg', 'Record deleted successfully!');
		redirect('admin/productcatalog/groups/');
	}
	
	function types()
	{
		$data['list'] = $this->Offer_model->getTypes();
	
		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = 'admin/productcatalog/types';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function edit_type($type_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['type'] = $this->Offer_model->getType($type_id);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('type_id', 'Type Id', 'trim|numeric');
			$this->form_validation->set_rules('type_name', 'type_name', 'trim|required|min_length[2]|max_length[100]');
	
			if ($this->form_validation->run() != FALSE)	{
				
				$form_type_id = $this->form_validation->set_value('type_id');
	
				$data_update = array('type_name' => $this->form_validation->set_value('type_name')	);

				if ($form_type_id) {
					$afftectedRows = $this->Offer_model->updateType($form_type_id, $data_update);
				} else {
					$form_type_id = $this->Offer_model->insertType($data_update);
				}	

				$this->session->set_userdata('msg', 'Data saved successfully!');
				redirect('admin/productcatalog/edit_type/'.$form_type_id);
			} else {
				$data['type'] = $this->input->post();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 13);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = 'admin/productcatalog/edit_type';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function delete_type($type_id = null)
	{
		if (!$type_id) show_404();
		$this->Offer_model->deleteType($type_id);
		$this->session->set_userdata('msg', 'Record deleted successfully!');
		redirect('admin/productcatalog/types/');
	}
	
	
	public static function input_hidden_freeunits($fields_array = array(), $rowindex = '0')
	{
		$out = '';
		foreach ($fields_array as $fieldName=>$fieldValue) {
			$inputName = "freeunits[{$rowindex}][{$fieldName}]";
			$inputID = "freeunit_{$rowindex}_{$fieldName}";
				
			$out .= '<input type="hidden" name="'.$inputName.'" id="'.$inputID.'" value="'.$fieldValue.'" data-originalname="'.$fieldName.'" />';
		}
		return $out;
	}
}