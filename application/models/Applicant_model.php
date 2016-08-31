<?php
/**
 * 
 * @author orletchi.victor
 *
 */
class Applicant_model extends CI_Model 
{
	public static $FOREIGNER_TYPES = array('0'=>'Cambodian', '1'=>'Foreigner');
	public static $SUBSCRIBER_TYPES = array('1'=>'Individual', '2'=>'Company');
	public static $GENDER_TYPES = array('ms'=>'Ms', 'mr'=>'Mr', 'mrs'=>'Mrs');//
	
	public static $DOCUMENT_TYPES = array('1'=>'Cambodian ID Card', '2'=>'Government ID Card', '3'=>'Valid Passport', '4'=>'Monk ID Card', '5'=>'Passport with valid visa', '6'=>'Registration Certificate');
    function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Search serial numbers by filter
	 * @param array $filter
	 * @return unknown
	 */
	public function search($filter = array())
	{
	
		$query = $this->db->distinct()
			->from("applicants as a")->select('a.*')
			->join("applicant_statuses as asn", 'a.applicant_status_id = asn.applicant_status_id', 'LEFT')->select('asn.applicant_status_name')
			->join("dealers as d", 'a.dealer_id = d.dealer_id', 'LEFT')->select('d.dealer_name, d.salesID')
			->join("serial_numbers as sn", 'a.serial_number = sn.serial_number', 'LEFT')->select('sn.serial_id')
			->join("dealer_sallers as dsl", 'a.sales_id = dsl.saller_id', 'LEFT')->select('dsl.login as saller_name')
			->join("cities as c", 'a.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'd.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'd.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name')
			->join("applicant_reasons as r", 'a.applicant_id = r.applicant_id', 'LEFT')->select('r.reason, r.created_at as reject_time')
			;
		
		if (!empty($filter['applicant_status_id'])) {
			$query->where('a.applicant_status_id', $filter['applicant_status_id']);
		}
		
		if (!empty($filter['serial_number'])) {
			$query->like('a.serial_number', $filter['serial_number']);
		}
		
		if (!empty($filter['phone_number'])) {
			$query->like('a.phone_number', $filter['phone_number']);
		}
		
		if (isset($filter['dealer_id'])) {
			$query->where('a.dealer_id', $filter['dealer_id']);
		}
		if (isset($filter['subscriber_type'])) {
			$query->where('a.subscriber_type', $filter['subscriber_type']);
		}
		
		if (isset($filter['subscriber_name'])) {
			$query->like('a.subscriber_name', $filter['subscriber_name']);
		}
		
		if (isset($filter['city_id'])) {
			$query->where('a.city_id', $filter['city_id']);
		}
		
		if (isset($filter['sales_id'])) {
			$query->where('a.sales_id', $filter['sales_id']);
		}
		
		if (isset($filter['date_to'])) {
			$date_to = $this->db->escape_like_str($filter['date_to']);
			$query->where("(`sn`.`changed_at` <= '{$date_to}')");
		}
		 
		if (isset($filter['date_from'])) {
			$date_from = $this->db->escape_like_str($filter['date_from']);
			$query->where("(`sn`.`changed_at` >= '{$date_from}')");
		}
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
		
			return $result;
		} else return array();
	}
	
	public function getStatuses($asKey = false)
	{
		$this->db->select('*')->from('applicant_statuses');
	
		$query = $this->db->get();
			
// 		print $this->db->last_query();
	
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
				
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'applicant_status_name', 'applicant_status_id'));
			}
			return $result;
		}
		return FALSE;
	}
	
	/**
	 * Insert new dealer
	 * @param unknown $inputData
	 * @return int last insert id
	 */
	public function insert($inputData = array())
	{
		if (!$inputData) return false;
	
		$this->db->insert('applicants', $inputData);
		$insert_id = $this->db->insert_id();
		//print $lastQuery = $this->db->last_query();
		return $insert_id;
	}
	
	/**
	 * Update dealer data by ID
	 * @param string $id
	 * @param array $inputData
	 * @return multitype:|int affected rows
	 */
	public function update($ID = null, $inputData = array())
	{
		if (!$ID || !$inputData) return array();
	
		$this->db->update('applicants', $inputData, $where = array('applicant_id'=>$ID), $limit = 1);
		$afftectedRows = $this->db->affected_rows();
		//print $lastQuery = $this->db->last_query();
		return $afftectedRows;
	}
	
	/**
	 * Get dealer information and related value also
	 * @param int $ID
	 * @return NULL|array
	 */
	public function getFullDetails($ID = null)
	{
		if (!$ID) return null;
		$query = $this->db->select('a.*')
			->from('applicants as a')
			->join("applicant_statuses as asn", 'a.applicant_status_id = asn.applicant_status_id', 'LEFT')->select('asn.applicant_status_name')
			->join("dealers as d", 'a.dealer_id = d.dealer_id', 'LEFT')->select('d.dealer_name, d.salesID')
			->join("cities as c", 'a.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'a.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'a.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name')
			->join("applicant_reasons as r", 'a.applicant_id = r.applicant_id', 'LEFT')->select('r.reason, r.created_at as reject_time')
			->join("scsm_users as rus2", 'a.changed_by = rus2.user_id', 'LEFT')->select('rus2.name as change_user')
			->join("scsm_users as rus", 'r.user_id = rus.user_id', 'LEFT')->select('rus.name as reject_user')
			->where('a.applicant_id', $ID)
			->get();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->row_array();
			return  $result;
		} else return null;
	}
	
	/**
	 * Get dealer information and related value also
	 * @param int $ID
	 * @return NULL|array
	 */
	public function dealerFormReport($dealer_id = null, $from = null, $to = null, $status = null )
	{
		if (!$dealer_id) return null;
		
		$query = $this->db->select('aps.applicant_status_name, COUNT(a.applicant_id) as total')
			->from('applicants as a')
			->join("applicant_statuses as aps", 'aps.applicant_status_id=a.applicant_status_id', 'LEFT')
			->where('a.dealer_id', $dealer_id)
			->order_by('a.applicantion_date')
			->group_by('aps.applicant_status_id');
		
		$query->group_start();
		if (is_array($status)) {
			$query->where_in('a.applicant_status_id', $status);
		}
		
		if (isset($from)) {
			$date_from = $this->db->escape_like_str($from);
			$query->where("(`a`.`applicantion_date` >= '{$date_from}')");
		}
		
		if (isset($to)) {
			$date_to = $this->db->escape_like_str($to);
			$query->where("(`a`.`applicantion_date` <= '{$date_to}')");
		}
		$query->group_end();
		
		$query->or_where('a.applicant_status_id', 3); // rejected status
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			return  $result;
		} else return null;
	}
	
	/**
	 * Insert new reason after rejection the form
	 * @param unknown $inputData
	 * @return int last insert id
	 */
	public function appendRejectReason($inputData = array())
	{
		if (!$inputData) return false;
	
		$this->db->insert('applicant_reasons', $inputData);
		
// 		print $lastQuery = $this->db->last_query();
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	/**
	 * Delete dealer by ID
	 * @param string $ID
	 * @return boolean
	 */
	public function delete($ID = null)
	{
		if (!$ID) return false;
		return $this->db->delete('dealers', array('dealer_id' => $ID), 1);
	}
	
}// end Servicetickets_model class