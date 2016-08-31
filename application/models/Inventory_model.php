<?php
/**
 * 
 * @author orletchi.victor
 *
 */
class Inventory_model extends CI_Model 
{
	public static $PHONE_NUMBER_STATUS = array('0'=>'Available', '1'=>'Assigned', '2'=>'Barred');
	public static $SIM_TYPES = array('0'=>'Blank SIM', '1'=>'SIM Kit');
	const PHONE_STATUS_AVAILABLE = '0';
	
    function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Search serial numbers by filter
	 * @param array $filter
	 * @return unknown
	 */
	public function search($filter = array(), $asKey = false)
	{
	
		$query = $this->db
			->from("serial_numbers as sn")->select('sn.*')
			->join("serial_statuses as st", 'sn.serial_status_id = st.status_id', 'LEFT')->select('st.status_name')
			->join("dealer_sallers as ds", 'sn.sales_id = ds.saller_id', 'LEFT')->select('ds.login')
			->join("dealers as d", 'sn.dealer_id = d.dealer_id', 'LEFT')->select('d.dealer_name, d.salesID');
	
		if (!empty($filter['serial_id'])) {
			$query->where('sn.serial_id', $filter['serial_id']);
		}
		
		if (!empty($filter['serial_number'])) {
			$query->like('sn.serial_number', $filter['serial_number']);
		}
		
		if (!empty($filter['salesID'])) {
			$query->like('d.salesID', $filter['salesID']);
		}
	
		if (isset($filter['serial_status_id'])) {
			$query->where('sn.serial_status_id', $filter['serial_status_id']);
		}
	
		if (isset($filter['sales_id'])) {
			$query->where('sn.sales_id', $filter['sales_id']);
		}
		
		if (isset($filter['sim_type'])) {
			$query->where('sn.sim_type', $filter['sim_type']);
		}
		
		if (isset($filter['dealer_id'])) {
			$query->where('d.dealer_id', $filter['dealer_id']);
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
    		if ($result && $asKey) {
    			$result = array_replace(array(''=>'---'), array_column($result, 'serial_number', 'serial_number'));
    		}
    		return $result;
    	} else return array();
	
	}
	
	public function getStatuses()
    {        
        $this->db->select('*')->from('serial_statuses');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			return $result;
		}
		return FALSE;
    }
    
	public function getStatusesVals($leadZero = false)
    {        
    	$items = $this->getStatuses();

		if ($items && isset($items[0]['status_id'])) {
			return $leadZero == true ? array_replace(array(''=>'---'), array_column($items, 'status_name', 'status_id')): array_column($items, 'status_name', 'status_id');
		}
		return FALSE;
    }
    
    public function getStatusByID($StatusID = null, $asArray = false)
    {
    	if (!$StatusID) return null;
    	$query = $this->db->select('*')
	    	->from('serial_statuses')
	    	->where('status_id', $StatusID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }// end GetStatuses()
    
    /**
     * Insert new statuses
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addStatus($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->db->insert('serial_statuses', $data_insert);
    	$insert_id = $this->db->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update status by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateStatus($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->db->update('serial_statuses', $data_update, $where = array('status_id' => $id), $limit = 1);
    	$afftectedRows = $this->db->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete status by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteStatus($ID = null)
    {
    	if (!$ID) return false;
    	return $this->db->delete('st_statuses', array('StatusID' => $ID), 1);
    }
    
    public function getDealers()
    {
    	$this->db->select('*')->from('dealers');
    
    	$query = $this->db->get();
    		
//     			print $this->db->last_query();
    
    	if ($query && $query->num_rows() > 0) {
    		$result = $query->result_array();
    		return $result;
    	}
    	return FALSE;
    }
    
    public function getDealersVals($leadZero = false)
    {
    	$items = $this->getDealers();
    
    	if ($items && isset($items[0]['dealer_id'])) {
    		return $leadZero == true ? array_replace(array(''=>'---'), array_column($items, 'dealer_name', 'dealer_id')): array_column($items, 'dealer_name', 'dealer_id');
    	}
    	return FALSE;
    }
    
    public function getSimDetails($ID = null)
    {
    	if (!$ID) return null;
    	$query = $this->db->select('sn.*')
	    	->from('serial_numbers as sn')
	    	->join("serial_statuses as st", 'sn.serial_status_id = st.status_id', 'LEFT')->select('st.status_name')
	    	->join("dealer_sallers as sl", 'sn.sales_id = sl.saller_id', 'LEFT')->select('sl.login')
	    	->join("dealers as d", 'sn.dealer_id = d.dealer_id', 'LEFT')->select('d.dealer_name, d.salesID')
	    	->where('sn.serial_id', $ID)
	    	->get();
    
    	$result = $query->row_array();
    	return  $result;
    }
    
    public function getSimDetailsBySerial($serial_number = null)
    {
    	if (!$serial_number) return null;
    	$query = $this->db->select('*')
	    	->from('serial_numbers as sn')
	    	->join("phone_numbers as pn", 'sn.phone_number = pn.phone_number', 'LEFT')->select('pn.phone_status')
	    	->join("serial_statuses as st", 'sn.serial_status_id = st.status_id', 'LEFT')->select('st.status_name')
	    	//->join("dealer_sim as ds", 'sn.serial_id = ds.serial_id', 'LEFT')
	    	->join("dealers as d", 'sn.dealer_id = d.dealer_id', 'LEFT')->select('d.dealer_name')
	    	->where('sn.serial_number', $serial_number)
	    	->get();
    
    	$result = $query->row_array();
    	return  $result;
    }
    
    /**
     * Update SIM by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateSim($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->db->update('serial_numbers', $data_update, $where = array('serial_id' => $id), $limit = 1);
    	$afftectedRows = $this->db->affected_rows();
    	//print $lastQuery = $this->db->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Insert new sim
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addSim($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->db->insert('serial_numbers', $data_insert);
    	
//     	print $lastQuery = $this->ST_DB->last_query();
    	$insert_id = $this->db->insert_id();
    	return $insert_id;
    }
    
    /**
     * Search phone numbers by filter
     * @param array $filter
     * @return unknown
     */
    public function getPhoneNumberInfo($filter = array())
    {
    
    	$query = $this->db
    	->from("phone_numbers as n")->select('n.*')
    	;
    
    	if (!empty($filter['phone_number_id'])) {
    		$query->where('n.phone_number_id', $filter['phone_number_id']);
    	}
    
    	if (!empty($filter['phone_number'])) {
    		$query->like('n.phone_number', $filter['phone_number']);
    	}
    
    	if (!empty($filter['phone_status'])) {
    		$query->where('n.phone_status', $filter['phone_status']);
    	}
    
    	if (!empty($filter['reason'])) {
    		$query->like('n.reason', $filter['reason'], 'both');
    	}
    
    	$query = $query->get();
    	// 		print $lastQuery = $this->db->last_query();
    	
    	if ($query && $query->num_rows() > 0) {
    		$result = $query->row_array();
    		return $result;
    	} else return array();
    }
    
    /**
     * Search phone numbers by filter
     * @param array $filter
     * @return unknown
     */
    public function search_numbers($filter = array(), $asKey = false)
    {
    
    	$query = $this->db
    	->from("phone_numbers as n")->select('n.*')
    	;
    
    	if (!empty($filter['phone_number_id'])) {
    		$query->where('n.phone_number_id', $filter['phone_number_id']);
    	}
    
    	if (!empty($filter['phone_number'])) {
    		$query->like('n.phone_number', $filter['phone_number']);
    	}
    
    	if (!empty($filter['phone_status'])) {
    		$query->where('n.phone_status', $filter['phone_status']);
    	}
    
    	if (!empty($filter['reason'])) {
    		$query->like('n.reason', $filter['reason'], 'both');
    	}
    
    	$query = $query->get();
    	// 		print $lastQuery = $this->db->last_query();
    	
    	if ($query && $query->num_rows() > 0) {
    		$result = $query->result_array();
    		if ($result && $asKey) {
    			$result = array_replace(array(''=>'---'), array_column($result, 'phone_number', 'phone_number'));
    		}
    		return $result;
    	} else return array();
    }
    
    /**
     * Update Phone number by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updatePhoneNumber($phone_number = null, $data_update = array())
    {
    	if (!$phone_number || !$data_update) return array();
    
    	$this->db->update('phone_numbers', $data_update, $where = array('phone_number' => $phone_number), $limit = 1);
    	$afftectedRows = $this->db->affected_rows();
    	//print $lastQuery = $this->db->last_query();
    	return $afftectedRows;
    }
    
}// end class