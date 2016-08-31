<?php

class Customer_model extends CI_Model 
{
	public static $sc_status = array('0'=>'Imported','1'=>'Active');
	public static $CustTypes = array('1'=>'Corporate','2'=>'Subsidiary', '3'=>'Department');
	public static $CertificateTypes = array('1'=>'National identity','2'=>'Passport');
	public static $CustSizes = array('Small Accounts'=>'Small Accounts', 'Medium Accounts'=>'Medium Accounts', 'Large Accounts'=>'Large Accounts');
	public static $RegisterCapitals = array('10000_C'=>'10000>=C', '10000_20000'=>'10000<C<=20000', '20000_50000'=>'20000<C<=50000', '50000_C'=>'50000<C');
	public static $BillMediumCode = array('1'=>'Paper','2'=>'SMS','3'=>'Email','4'=>'Fax');
	public static $CustStatuses = array('1'=>'Prospect','2'=>'Active','3'=>'Deleted');
	
	public static $AddressTypes = array('0'=>'Home address','1'=>'Emergency address');
	
    function __construct()
	{
		parent::__construct();
	}
	
	public function getCustomers($WebCustId = null)
	{
	
		$query = $this->db->select('*')
			->from('sm_customers');

		
		if ($WebCustId && false == is_array($WebCustId)) {
			$query->where('WebCustId', $WebCustId);
		}
		
		if (is_array($WebCustId)) {
			$query->where_in('WebCustId', $WebCustId);
		}
		
		$query = $query->get();
		// 		print $lastQuery = $this->db->last_query();
		return $query->result_array();
	}
	
	public function getCustById($CustId = null, $return_as = 'array')
	{
		if (!$CustId) return array();
	
		$queryResult = $this->db->select('*')
			->from('sm_customers')
			->where('CustId', $CustId);
		$query = $queryResult->get();
		// 		print $lastQuery = $this->db->last_query();
		
		if ($return_as == 'array') {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}
	
	public function getWebCustById($WebCustId = null, $return_as = 'array')
	{
		if (!$WebCustId) return array();
	
		$queryResult = $this->db->select('c.*')
			->from('sm_customers as c')
			->join('sm_industry as i', 'c.Industry=i.WebIndustryId', 'left')->select('i.IndustryName')
			->where('c.WebCustId', $WebCustId);
	
		$query = $queryResult->get();
		// 		print $lastQuery = $this->db->last_query();
		
		if ($return_as == 'array') {
			return $query->row_array();
		} else {
			return $query->row();
		}
	}
	
	public function updateCustomerByWebId($WebCustId = null, $data_update = null) 
	{
		if (!$WebCustId || !$data_update) return false;

		$this->db->where('WebCustId', $WebCustId);
		$this->db->limit(1);
		$this->db->update('sm_customers', $data_update);
		
		return $afftectedRows = $this->db->affected_rows();
	}
	
	public function insertCustomer($data_insert = array())
	{
		if (!$data_insert) return false;
	
		$this->db->insert('sm_customers', $data_insert);
		
// 		print $lastQuery = $this->db->last_query();
		
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function deleteCustomer($WebCustId = null)
	{
		if (!$WebCustId) return false;
		$this->db->delete('sm_customer_address', array('WebCustId' => $WebCustId));
		return $this->db->delete('sm_customers', array('WebCustId' => $WebCustId));
	}
	
	public static function webRegisterCapitals()
	{
		$out = array(''=>'...');
		foreach (self::$RegisterCapitals as $index => $item) {
			$out[$index] = htmlentities($item);
		}
		return $out;
	}
	
	public function getCustAddresses($WebCustId = null)
	{
		if (!$WebCustId) return array();
	
		$queryResult = $this->db->select('*')
			->from('sm_customer_address')
			->where('WebCustId', $WebCustId)
			->get()
			->result_array();
		// 		print $lastQuery = $this->db->last_query();
		return $queryResult;
	}
	
	public function updateCustomerAddress($WebAddressId = null, $data_update = null)
	{
		if (!$WebAddressId || !$data_update) return false;
	
		$this->db->where('WebAddressId', $WebAddressId);
		$this->db->limit(1);
		$this->db->update('sm_customer_address', $data_update);
	
		return $afftectedRows = $this->db->affected_rows();
	}
	
	public function insertCustomerAddress($data_insert = array())
	{
		if (!$data_insert) return false;
	
		$this->db->insert('sm_customer_address', $data_insert);
				print $lastQuery = $this->db->last_query();
		$insert_id = $this->db->insert_id();
		
		return $insert_id;
	}

	public function deleteCustomerAddress($WebAddressId = null)
	{
		if (!$WebAddressId) return false;
		return $this->db->delete('sm_customer_address', array('WebAddressId' => $WebAddressId));
	}
}