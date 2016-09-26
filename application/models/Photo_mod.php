<?php

class Photo_mod extends CI_Model
{
    function __construct()
	{
		parent::__construct();
	}

    public function get($id = null)
    {
        if (!$id) return false;
        $query = $this->db->select('p.*')
            ->from('photos p')
            ->where('p.photo_id', $id)
            ->join("galleries as g", 'p.gallery_id = g.id', 'LEFT')->select('g.name as gallery_name, g.description as gallery_description, g.event_date')
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')->select('us.user_id, us.name as user_name');

        $query = $query->get();
        if ($query && $query->num_rows() > 0) {
            return $query->row_array();
        } else
            return null;
    }

	public function all($filter = array())
	{
		$query = $this->db->select('p.*')
			->from('photos as p')
            ->join("galleries as g", 'p.gallery_id = g.id', 'LEFT')->select('g.name as gallery_name, g.description as gallery_description, g.event_date')
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')->select('us.name as user_name');

//        if (isset($filter['is_video'])) $query->where('is_video', (string)$filter['is_video']);

        $query = $query->get();
		if ($query && $query->num_rows() > 0) {
            return $query->result_array();
		} else
		    return null;
	}
	
	/**
	 * Insert new album
	 * @param unknown $inputData
	 * @return int last insert id
	 */
	public function store($inputData = array())
	{
		if (!$inputData) return false;
	
		$this->db->insert('photos', $inputData);
		$insert_id = $this->db->insert_id();
		//print $lastQuery = $this->db->last_query();
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
		return $this->db->delete('photos', array('photo_id' => $ID), 1);
	}
}