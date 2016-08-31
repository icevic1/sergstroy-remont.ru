<?php
/**
 * 
 * @author orletchi.victor
 *
 */
class Dealer_model extends CI_Model 
{
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
			->from("dealers as d")->select('d.*')
			->join("dealer_sallers as slr", 'd.dealer_id = slr.dealer_id', 'LEFT')->select("GROUP_CONCAT(slr.login SEPARATOR ', ') as `sallers`")
			->join("dealer_type as dt", 'd.dealer_type_id = dt.dealer_type_id', 'LEFT')->select('dt.dealer_type_name')
			->join("dealer_status as dst", 'd.status = dst.dealer_status_id', 'LEFT')->select('dst.dealer_status_name')
			->join("dealer_kinds as dk", 'd.dealer_kind_id = dk.kind_id', 'LEFT')->select('dk.kind_name')
			->join("zone as z", 'd.zone_id = z.zone_id', 'LEFT')->select('z.zone_name')
			->join("regions as r", 'd.reg_id = r.reg_id', 'LEFT')->select('r.reg_name')
			->join("cities as c", 'd.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'd.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'd.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name')
			->group_by('slr.dealer_id')	;
		
		if (!empty($filter['dealer_id'])) {
			$query->where('d.dealer_id', $filter['dealer_id']);
		}
		
		if (!empty($filter['dealer_name'])) {
			$query->like('d.dealer_name', $filter['dealer_name']);
		}
	
		if (!empty($filter['saller'])) {
			$query->where('slr.login', $filter['saller']);
		}
		
		if (isset($filter['dealer_kind_id'])) {
			$query->where('d.dealer_kind_id', $filter['dealer_kind_id']);
		}
	
		if (isset($filter['dealer_type_id'])) {
			$query->where('d.dealer_type_id', $filter['dealer_type_id']);
		}
		
		if (isset($filter['reg_id'])) {
			$query->where('d.reg_id', $filter['reg_id']);
		}
		
		if (isset($filter['city_id'])) {
			$query->where('d.city_id', $filter['city_id']);
		}
		
		if (isset($filter['district_id'])) {
			$query->where('d.district_id', $filter['district_id']);
		}
		
		if (isset($filter['zone_id'])) {
			$query->where('d.zone_id', $filter['zone_id']);
		}
		if (isset($filter['commune_id'])) {
			$query->where('d.commune_id', $filter['commune_id']);
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
				$result = array_replace(array(''=>'---'), array_column($result, 'dealer_name', 'dealer_id'));
			}
			
			return $result;
		} else return array();
	}
	
	/**
	 * Search serial numbers by filter
	 * @param array $filter
	 * @return unknown
	 */
	public function getDealerBySallerID($saller_id = null, $full = false)
	{
		if (!$saller_id) return null;
		
		$query = $this->db
			->from("dealers as d")->select('d.*')
			->join("dealer_sallers as slr", 'd.dealer_id = slr.dealer_id', 'LEFT')->select("GROUP_CONCAT(slr.login SEPARATOR ', ') as `sallers`")
			->join("dealer_sallers as ds", 'd.dealer_id = ds.dealer_id', 'INNER')->select('ds.saller_id')
			->where('ds.saller_id', $saller_id)
			->group_by('slr.dealer_id')	;
		
		if ($full) {
			$query->join("dealer_type as dt", 'd.dealer_type_id = dt.dealer_type_id', 'LEFT')->select('dt.dealer_type_name')
			->join("dealer_kinds as dk", 'd.dealer_kind_id = dk.kind_id', 'LEFT')->select('dk.kind_name')
			->join("dealer_status as dst", 'd.status = dst.dealer_status_id', 'LEFT')->select('dst.dealer_status_name')
			->join("zone as z", 'd.zone_id = z.zone_id', 'LEFT')->select('z.zone_name')
			->join("regions as r", 'd.reg_id = r.reg_id', 'LEFT')->select('r.reg_name')
			->join("cities as c", 'd.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'd.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'd.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name');
		}
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->row_array();
			
			return $result;
		} else 
			return null;
	}
	
	/**
	 * Search serial numbers by filter
	 * @param array $filter
	 * @return unknown
	 */
	public function getDealerBySallerPhone($phone_number = null, $full = false)
	{
		if (!$phone_number) return null;
		
		$query = $this->db
			->from("dealers as d")->select('d.*')
			->join("dealer_sallers as slr", 'd.dealer_id = slr.dealer_id', 'LEFT')->select("GROUP_CONCAT(slr.login SEPARATOR ', ') as `sallers`")
			->join("dealer_sallers as ds", 'd.dealer_id = ds.dealer_id', 'INNER')->select('ds.saller_id')
			->where('ds.login', $phone_number)
			->group_by('slr.dealer_id')	;
		
		if ($full) {
			$query->join("dealer_type as dt", 'd.dealer_type_id = dt.dealer_type_id', 'LEFT')->select('dt.dealer_type_name')
			->join("dealer_status as dst", 'd.status = dst.dealer_status_id', 'LEFT')->select('dst.dealer_status_name')
			->join("dealer_kinds as dk", 'd.dealer_kind_id = dk.kind_id', 'LEFT')->select('dk.kind_name')
			->join("zone as z", 'd.zone_id = z.zone_id', 'LEFT')->select('z.zone_name')
			->join("regions as r", 'd.reg_id = r.reg_id', 'LEFT')->select('r.reg_name')
			->join("cities as c", 'd.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'd.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'd.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name');
		}
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->row_array();
			
			return $result;
		} else 
			return null;
	}
	
	public function getDealerSallers($dealer_id = null, $asKey = false)
	{
		$this->db->select('*')->from('dealer_sallers');
	
		if ($dealer_id) {
			$this->db->where('dealer_id', $dealer_id);
		}
		
		$query = $this->db->get();
			
// 		print $this->db->last_query();
	
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
	
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'login', 'saller_id'));
			}
			return $result;
		}
		return FALSE;
	}
	
	public function getDealerKinds($asKey = false)
	{
		$this->db->select('*')->from('dealer_kinds');
	
		$query = $this->db->get();
			
		// 		print $this->db->last_query();
	
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
				
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'kind_name', 'kind_id'));
			}
			return $result;
		}
		return FALSE;
	}
	
	public function getDealerTypes($asKey = false)
	{
		$this->db->select('*')->from('dealer_type');
	
		$query = $this->db->get();
			
		// 		print $this->db->last_query();
	
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
				
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'dealer_type_name', 'dealer_type_id'));
			}
			return $result;
		}
		return FALSE;
	}
	
	public function getDealerStatuse($asKey = false)
	{
		$this->db->select('*')->from('dealer_status');
	
		$query = $this->db->get();
			
		// 		print $this->db->last_query();
	
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
				
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'dealer_status_name', 'dealer_status_id'));
			}
			return $result;
		}
		return FALSE;
	}
	
	/**
	 * Get dealer information and related value also
	 * @param int $ID
	 * @return NULL|array
	 */
	public function getFullDetails($ID = null)
	{
		if (!$ID) return null;
		$query = $this->db->select('d.*')
			->from('dealers as d')
			->join("dealer_sallers as slr", 'd.dealer_id = slr.dealer_id', 'LEFT')->select("GROUP_CONCAT(slr.login SEPARATOR ', ') as `sallers`")
			->join("dealer_status as dst", 'd.status = dst.dealer_status_id', 'LEFT')->select('dst.dealer_status_name')
			->join("dealer_type as dt", 'd.dealer_type_id = dt.dealer_type_id', 'LEFT')->select('dt.dealer_type_name')
			->join("dealer_kinds as dk", 'd.dealer_kind_id = dk.kind_id', 'LEFT')->select('dk.kind_name')
			->join("zone as z", 'd.zone_id = z.zone_id', 'LEFT')->select('z.zone_name')
			->join("regions as r", 'd.reg_id = r.reg_id', 'LEFT')->select('r.reg_name')
			->join("cities as c", 'd.city_id = c.city_id', 'LEFT')->select('c.city_name')
			->join("districts as ds", 'd.district_id = ds.district_id', 'LEFT')->select('ds.district_name')
			->join("communes as cm", 'd.commune_id = cm.commune_id', 'LEFT')->select('cm.commune_name')
			->where('d.dealer_id', $ID)
			->group_by('slr.dealer_id')
			->get();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->row_array();
			return  $result;
		} else return null;
	}
	
	/**
	 * Insert new dealer
	 * @param unknown $inputData
	 * @return int last insert id
	 */
	public function insert($inputData = array())
	{
		if (!$inputData) return false;
	
		$this->db->insert('dealers', $inputData);
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
	
		$this->db->update('dealers', $inputData, $where = array('dealer_id'=>$ID), $limit = 1);
		$afftectedRows = $this->db->affected_rows();
		//print $lastQuery = $this->db->last_query();
		return $afftectedRows;
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