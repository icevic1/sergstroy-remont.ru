<?php

class SiteSettings_mod extends CI_Model
{
    function __construct()
	{
		parent::__construct();
	}

	public function get()
	{
		$query = $this->db->select('*')
			->from('site_settings');

        $query = $query->get();
		if ($query && $query->num_rows() > 0) {
            return $query->row_array();
		} else
		    return null;
	}
	
	/**
	 * Update dealer data by ID
	 * @param string $id
	 * @param array $inputData
	 * @return multitype:|int affected rows
	 */
	public function update($inputData = array())
	{
		if (!$inputData) return array();
	
		$this->db->update('site_settings', $inputData);
		$afftectedRows = $this->db->affected_rows();
		//print $lastQuery = $this->db->last_query();
		return $afftectedRows;
	}

}// end Servicetickets_model class