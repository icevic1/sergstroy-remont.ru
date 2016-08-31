<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Inventory extends Site_Controller 
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
		$this->load->model('Servicetickets_model', 'st');     
		$this->load->model('Inventory_model', 'inventory');
		$this->load->model('Dealer_model', 'dealer');

		$this->staff = $this->session->userdata('staff');
	}	
	
	function index()
	{			
	    $data['filteredTickets'] = array();
	    $filter = array();
	    
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    	$filter	= $this->input->post();
	    	var_dump($this->input->post(), $filter);
	    }
	    
	    $data['filter'] = $filter;
	    $data['items'] = $this->inventory->search($filter);
// 	    var_dump($data['items']);
	    
	    $data['serial_statuses'] = $this->inventory->getStatusesVals(true);
	    $data['dealers'] = $this->inventory->getDealersVals(true);
	    $data['sallers'] = $this->dealer->getDealerSallers(null, true);
	    
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Inventory Management System', 'inventory/');
		
		$data['navAdd'] = array('link'=>'inventory/save', 'title'=>'Add New');
		$data['PAGE_TITLE'] = 'Inventory Management System';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='inventory/index';
		$this->load->view('layout/layout_st', $data);
        
	}
	
	function simprofile($ID = null)
	{
		$item = $this->inventory->getSimDetails($ID);

		if (empty($item)) {
			show_error('SIM with ID #'.$ID.'# was not found!', $status_code= 500 );
		}
// 	var_dump($item['created_at'], $item);
// 		echo date('Y-m-d', strtotime($item['created_at']));die;
		
		$data['item'] = $item;
		$data['navBackLink'] = site_url('inventory/');
		$data['navEditLink'] = site_url('inventory/save/'.$ID);
		
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Inventory Management System', 'inventory/');
		$this->breadcrumbs->push('SIM Profile', 'inventory/simprofile');
	
		$data['PAGE_TITLE'] = 'eKYC - SIM Profile';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='inventory/simprofile';
		$this->load->view('layout/layout_st', $data);
	}
    
	function save($ID = null)
	{
		$this->load->helper('text');
		$required = '';
		if ($ID) {
			$simdata = $this->inventory->getSimDetails($ID);
			
			if (empty($simdata)) {
				show_error('SIM Serial with ID #'.$ID.'# was not found!', $status_code= 500 );
			}
			$data['data'] = $simdata;
			$data['choosed_serial_id'] = $simdata['serial_id'];
			$data['choosed_serial_number'] = $simdata['serial_number'];
			$data['choosed_status_id'] = $simdata['serial_status_id'];
			$data['choosed_dealer_id'] = $simdata['dealer_id'];
			$data['choosed_sales_id'] = $simdata['sales_id'];
			$data['choosed_sim_type'] = $simdata['sim_type'];
			$data['choosed_phone_number'] = $simdata['phone_number'];
			$required = '|required';
		}
// var_dump($data['choosed_status_id']);die;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('serial_id', 'Serial ID', 'trim|numeric|max_length[8]'.$required);
			$this->form_validation->set_rules('serial_number', 'Serial Number', 'trim|required|numeric|max_length[20]');
			$this->form_validation->set_rules('serial_status_id', 'Status', 'trim|numeric');
			$this->form_validation->set_rules('dealer_id', 'Dealer', 'trim|numeric');
			$this->form_validation->set_rules('sales_id', 'Sales ID', 'trim|max_length[64]');
			$this->form_validation->set_rules('sim_type', 'SIM Type', 'trim|required|max_length[64]');
			$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|numeric|max_length[12]');
			
			/**
			 * passed validation proceed to post success logic
			 */
// 			var_dump($this->input->post(), $this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() == true) {
				
				
				$serial_id = $this->form_validation->set_value('serial_id');
				
				$form_data = array(
						'serial_number' => $this->form_validation->set_value('serial_number'),
						'serial_status_id' => $this->form_validation->set_value('serial_status_id'),
						'dealer_id' => $this->form_validation->set_value('dealer_id') ,
						'sales_id' => $this->form_validation->set_value('sales_id'),
						'phone_number' => $this->form_validation->set_value('phone_number'),
						'sim_type' => $this->form_validation->set_value('sim_type'),
				);
// 				var_dump($form_data);die;
				
				if ($serial_id) {
					$this->inventory->updateSim($serial_id, $form_data);
				} else {
					$serial_id = $this->inventory->addSim($form_data);
				}
					
				
				redirect('inventory/simprofile/'.$serial_id);
			} else {
				$data['choosed_serial_id'] = $this->form_validation->set_value('serial_id');
				$data['choosed_serial_number'] = $this->form_validation->set_value('serial_number');
				$data['choosed_status_id'] = $this->form_validation->set_value('serial_status_id');
				$data['choosed_dealer_id'] = $this->form_validation->set_value('dealer_id');
				$data['choosed_sales_id'] = $this->form_validation->set_value('sales_id');
				$data['choosed_sim_type'] = $this->form_validation->set_value('sim_type');
				$data['choosed_phone_number'] = $this->form_validation->set_value('phone_number');
			}
		} // end if POST
		
		$data['serial_statuses'] = $this->inventory->getStatusesVals(true);
	    $data['dealers'] = $this->inventory->getDealersVals(true);
	    $data['phone_numbers'] = $this->inventory->search_numbers(null, true);
	    $data['sallers_list'] = $this->dealer->getDealerSallers(null, true);
		
	    // add breadcrumbs
// 	    $this->breadcrumbs->push('Home', 'home');
// 	    $this->breadcrumbs->push('Inventory Management System', 'inventory/');
// 	    $this->breadcrumbs->push('Change SIM Profile', 'inventory/save');
	    
	    
	    if ($ID) {
	    	$this->breadcrumbs->push('Home', 'home');
	    	$this->breadcrumbs->push('Inventory Management System', 'inventory/');
	    	$this->breadcrumbs->push('Change SIM Profile', 'inventory/save/'.$ID);
	    	$data['ACTION_TITLE'] = 'Change SIM Profile';
	    } else {
	    	$this->breadcrumbs->push('Home', 'home');
	    	$this->breadcrumbs->push('Inventory Management System', 'inventory/');
	    	$this->breadcrumbs->push('New SIM Profile', 'inventory/save/');
	    	$data['ACTION_TITLE'] = 'New SIM Profile';
	    }
	     
	    $data['BODY_CLASS'] = "sts";
	    $data['PAGE_TITLE'] = 'eKYC - '.$data['ACTION_TITLE'];
	    
		$data['CONTENT'] = 'inventory/save';
		$this->load->view('layout/layout_st', $data);
	}// end save()
	
	function phone_numbers()
	{
		$data['filteredTickets'] = array();
		$filter = array();
		 
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$filter	= $this->input->post();
// 			var_dump($this->input->post(), $filter);
		}
		 
		$data['filter'] = $filter;
		$data['items'] = $this->inventory->search_numbers($filter);
		// 	    var_dump($data['items']);
		 
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Customer Relationship Management', 'inventory/phone_numbers');
	
		$data['PAGE_TITLE'] = 'eKYC - Customer Relationship Management';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='inventory/phone_numbers';
		$this->load->view('layout/layout_st', $data);
	
	}
	
	public function check_new_phone()
	{
		$new_phone = $this->input->post('new_phone', true);
		$TicketType = $this->input->post('TicketType');
		
		if($TicketType == '3') {
			$new_phone = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $new_phone));
			
			if (!$new_phone) {
				$this->form_validation->set_message('check_new_phone', "Subscriber phone number can't be empty!" );
				return false;
			}

			/* if searched user is urrent user, sotp request ruturn true */
			if (isset($this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo']) 
					&& $new_phone == $this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo']) return true;
			
			/* get all staff customers profile*/
			$staffCustomers = $this->Customer_model->getCustomers(array_keys($this->staff['companies']));
			
			/* get CustId from ngbss related to the input phone number */
			$CustId = GetCorpCustomerData($new_phone, $onlyCustId = true);

			/* search CustId in staff array */
			$custArrayKey = array_search($CustId, array_column($staffCustomers, 'CustId'));
// 			var_dump($CustId, $custArrayKey, $staffCustomers);die;
			if (!$CustId || ($CustId && false == key_exists($custArrayKey, $staffCustomers))) {
				$this->form_validation->set_message('check_new_phone', "Subscriber information by 0{$new_phone} was not found!");
				return false;
			}
			
			/* 
			 * Subscriber found, must load information
			 */
			$current_customer = $staffCustomers[$custArrayKey];
			
			/*
			 * Replace session customer information if is diferent than current session
			 */
			if (isset($this->session->userdata('current_customer')['CustId']) && $current_customer['CustId'] != $this->session->userdata('current_customer')['CustId']) {
				$current_customer['Groups_Info'] = Group_Member($current_customer['GroupId']) or array();
				$this->session->set_userdata('current_customer', $current_customer);
			}
			
			$current_subscriber = $this->subscriber->loadSubscriber($new_phone);
			
			$_POST['TicketType'] = '0';
			
			$this->session->set_userdata('current_subscriber', $current_subscriber);
		}

		return true;
	}
	
}// end class