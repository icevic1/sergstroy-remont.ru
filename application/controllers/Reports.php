<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Reports extends Site_Controller 
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
		$this->load->model('Applicant_model', 'applicant');

		$this->staff = $this->session->userdata('staff');
	}	
	
	function index()
	{			
		$data['items'] = $this->applicant->search();
		// add breadcrumbs
		$this->breadcrumbs->push('Application Forms Management', 'home/');
		$this->breadcrumbs->push('Reports', 'reports/');
		
		$data['PAGE_TITLE'] = 'eKYC - Reports';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='reports/index';
		$this->load->view('layout/layout_st', $data);
        
	}
	
}// end Reports class