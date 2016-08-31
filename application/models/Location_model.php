<?php
/**
 * 
 * @author orletchi.victor
 *
 */
class Location_model extends CI_Model 
{
    function __construct()
	{
		parent::__construct();
	}
	
	public function getRegions($asKey = false)
    {        
        $this->db->select('*')->from('regions');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'reg_name', 'reg_id'));
			}
			return $result;
		}
		return FALSE;
    }
    
	public function getCities($asKey = false)
    {        
        $this->db->select('*')->from('cities');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'city_name', 'city_id'));
			}
			return $result;
		}
		return FALSE;
    }
    
	public function getDistricts($asKey = false)
    {        
        $this->db->select('*')->from('districts');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'district_name', 'district_id'));
			}
			return $result;
		}
		return FALSE;
    }
    
	public function getCommunes($asKey = false)
    {        
        $this->db->select('*')->from('communes');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'commune_name', 'commune_id'));
			}
			return $result;
		}
		return FALSE;
    }
    
	public function getZone($asKey = false)
    {        
        $this->db->select('*')->from('zone');

        $query = $this->db->get();
			
// 		print $this->db->last_query();
		
		if ($query && $query->num_rows() > 0) {
			$result = $query->result_array();
			
			if ($result && $asKey) {
				$result = array_replace(array(''=>'---'), array_column($result, 'zone_name', 'zone_id'));
			}
			return $result;
		}
		return FALSE;
    }
    
}// end Servicetickets_model class