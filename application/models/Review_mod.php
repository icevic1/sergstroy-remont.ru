<?php

class Review_mod extends CI_Model
{
    function __construct()
	{
		parent::__construct();
	}

	public function all($filter = array())
	{
		$query = $this->db->select('*')
			->from('client_reviews');

        if (isset($filter['is_video'])) $query->where('is_video', (string)$filter['is_video']);

        $query = $query->get();
		if ($query && $query->num_rows() > 0) {
            return $query->result_array();
		} else
		    return null;
	}
	
	/**
	 * Insert new dealer
	 * @param unknown $inputData
	 * @return int last insert id
	 */
	public function store($inputData = array())
	{
		if (!$inputData) return false;
	
		$this->db->insert('client_reviews', $inputData);
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