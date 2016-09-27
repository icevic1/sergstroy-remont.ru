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
            ->join("photo_categories as c", 'p.category_id = c.category_id', 'LEFT')->select('c.category_name')
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')->select('us.name as user_name');

        if (isset($filter['selected'])) $query->where('p.selected', (string)$filter['selected']);

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
     * Update photo data by ID
     * @param string $id
     * @param array $inputData
     * @return multitype:|int affected rows
     */
    public function save($ID = null, $inputData = array())
    {
        if (!$ID || !$inputData) return array();

        $this->db->update('photos', $inputData, $where = array('photo_id'=>$ID), $limit = 1);
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
		return $this->db->delete('photos', array('photo_id' => $ID), 1);
	}

    public function categories()
    {
        $query = $this->db->select('*')->from('photo_categories');

        $query = $query->get();
        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else
            return null;
    }
}