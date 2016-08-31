<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Dealer extends Site_Controller 
{
	//public $ST_DB;
	public $staff = null;
	private $visitor = null;
	private $debugEmail	 = array('orletchi.victor@gmail.com');
    
    function __construct()
	{
 		parent::__construct();
		$this->load->helper('cookie');
        $this->load->helper('text');
        $this->load->library('form_validation');		
        $this->load->library('Acl');
		$this->load->helper('form');
		$this->load->helper('pagination');
		$this->load->helper('url');
		$this->load->library('breadcrumbs'); // load Breadcrumbs
		$this->load->model('Dealer_model', 'dealer');     
		$this->load->model('Location_model', 'location');     
		$this->load->model('Inventory_model', 'inventory');

		$this->staff = $this->session->userdata('staff');
	}	
	
	function index()
	{			
	    $filter = array();
	    
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    	$filter	= $this->input->post();
// 	    	var_dump($this->input->post(), $filter);
	    }
	    
	    $data['filter'] = $filter;
	    $data['items'] = $this->dealer->search($filter);
// 	    var_dump($data['items']);die;
	    $data['districts'] = $this->location->getDistricts(true);
	    $data['regions'] = $this->location->getRegions(true);
	    $data['communes'] = $this->location->getCommunes(true);
	    $data['cities'] = $this->location->getCities(true);
	    $data['zones'] = $this->location->getZone(true);
	    $data['dealer_kinds'] = $this->dealer->getDealerKinds(true);
	    $data['dealer_types'] = $this->dealer->getDealerTypes(true);

		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Dealer Registration Platform', 'dealer/');
		
		$data['navAdd'] = array('link'=>'dealer/save', 'title'=>'Add Dealer');
		$data['PAGE_TITLE'] = 'eKYC - Dealer Registration Platform';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='dealer/index';
		$this->load->view('layout/layout_st', $data);
        
	}
	
	function profile($ID = null)
	{
		$item = $this->dealer->getFullDetails($ID);

		if (empty($item)) {
			show_error('Dealer with ID #'.$ID.'# was not found!', $status_code= 500 );
		}
		
// 		var_dump($item);die;
		$data['item'] = $item;
		$data['navBackLink'] = site_url('dealer/');
		$data['navEditLink'] = site_url('dealer/save/'.$ID);
		
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Dealer Registration Platform', 'dealer/');
		$this->breadcrumbs->push('Dealer Profile', 'dealer/profile');
	
		$data['PAGE_TITLE'] = 'eKYC - Dealer Registration Platform';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='dealer/profile';
		$this->load->view('layout/layout_st', $data);
	}
    
	function save($ID = null)
	{
		$this->load->helper('text');
		
		$required = '';
		if ($ID) {
			$dbdata = $this->dealer->getFullDetails($ID);
// 			var_dump($dbdata);die;
			if (empty($dbdata)) {
				show_error('Dealer with ID #'.$ID.'# was not found!', $status_code= 500 );
			}
			$data['choosed'] = $dbdata;
			
			$required = '|required';
		}
// var_dump($data['choosed_status_id']);die;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('dealer[dealer_id]', 'Dealer ID', 'trim|numeric|max_length[5]'.$required);
			$this->form_validation->set_rules('dealer[dealer_name]', 'Dealer Name', 'trim|required|max_length[128]');
			$this->form_validation->set_rules('dealer[salesID]', 'Sales ID', 'trim|max_length[64]');
			$this->form_validation->set_rules('dealer[dealer_type_id]', 'Dealer Type', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[dealer_kind_id]', 'Dealer Kind', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[status]', 'Status', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[location_name]', 'Location', 'trim|max_length[256]');
			$this->form_validation->set_rules('dealer[source_id]', 'Source', 'trim|max_length[64]');
			$this->form_validation->set_rules('dealer[zone_id]', 'Zone', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[reg_id]', 'Region', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[city_id]', 'City', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[district_id]', 'District', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[commune_id]', 'Commune', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('dealer[house_no]', 'House Number', 'trim|max_length[32]');
			$this->form_validation->set_rules('dealer[street]', 'Street', 'trim|max_length[256]');
			$this->form_validation->set_rules('dealer[village]', 'Village', 'trim|max_length[128]');
			$this->form_validation->set_rules('dealer[owner]', 'Owner', 'trim|max_length[128]');
			$this->form_validation->set_rules('dealer[phone_1]', 'Phone Number', 'trim|numeric|max_length[12]');
			$this->form_validation->set_rules('dealer[phone_2]', 'Phone Number second', 'trim|numeric|max_length[12]');
			$this->form_validation->set_rules('dealer[email]', 'Email', 'trim|valid_email|max_length[64]');
			$this->form_validation->set_rules('dealer[status]', 'Dealer Status', 'trim|numeric');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
			
			/**
			 * passed validation proceed to post success logic
			 */
// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() == true) {
				
				$inputData = $this->empty2null($this->input->post('dealer'));
				$dealer_id = $this->form_validation->set_value('dealer[dealer_id]');
// 				var_dump($inputData);die;
				if ($dealer_id) {
					$this->dealer->update($dealer_id, $inputData);
				} else {
					$dealer_id = $this->dealer->insert($inputData);
				}
				
				redirect('dealer/profile/'.$dealer_id);
			} else {
				$data['choosed'] = $this->input->post('dealer');
			}
		} // end if POST
		
		$data['districts'] = $this->location->getDistricts(true);
	    $data['regions'] = $this->location->getRegions(true);
	    $data['communes'] = $this->location->getCommunes(true);
	    $data['cities'] = $this->location->getCities(true);
	    $data['zones'] = $this->location->getZone(true);
	    $data['dealer_kinds'] = $this->dealer->getDealerKinds(true);
	    $data['dealer_types'] = $this->dealer->getDealerTypes(true);
	    $data['dealer_statuses'] = $this->dealer->getDealerStatuse(true);

	    // add breadcrumbs
	   	$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Dealer Registration Platform', 'dealer/');
		if ($ID) {
			$this->breadcrumbs->push('Dealer Profile', 'dealer/profile/'.$ID);
			$this->breadcrumbs->push('Change Profile', 'dealer/save/'.$ID);
			$data['ACTION_TITLE'] = 'Change Dealer Profile';
		} else {
			$this->breadcrumbs->push('New Dealer', 'dealer/save/');
			$data['ACTION_TITLE'] = 'Add New Dealer';
		}
	    
	    $data['BODY_CLASS'] = "sts";
		$data['PAGE_TITLE'] = 'eKYC - '.$data['ACTION_TITLE'];
		$data['CONTENT']='dealer/save';
		$this->load->view('layout/layout_st', $data);
	}// end save()
	
}// end Servicetickets class