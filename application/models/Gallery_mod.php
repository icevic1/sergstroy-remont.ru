<?php

class Gallery_mod extends CI_Model
{
    function __construct()
	{
		parent::__construct();
	}

    public function get($id = null)
    {
        if (!$id) return false;
        $query = $this->db->select('g.*')
            ->from('galleries g')
            ->where('g.id', $id)
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')->select('us.name as user_name, us.mobile_no, us.address');

        $query = $query->get();
        if ($query && $query->num_rows() > 0) {
            return $query->row_array();
        } else
            return null;
    }

	public function all($filter = array())
	{
		$query = $this->db->select('g.*')
			->from('galleries g')
            ->join("photos as p", 'g.id = p.gallery_id', 'LEFT')->select('COUNT(p.photo_id) as uploaded, p.thumb')
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')->select('us.name as user_name')
            ->group_by('g.id');

//        if (isset($filter['is_video'])) $query->where('is_video', (string)$filter['is_video']);

        $query = $query->get();
//        print $lastQuery = $this->db->last_query();
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
	
		$this->db->insert('galleries', $inputData);
		$insert_id = $this->db->insert_id();
		//print $lastQuery = $this->db->last_query();
		return $insert_id;
	}
	
	/**
	 * Update galleries data by ID
	 * @param string $id
	 * @param array $inputData
	 * @return multitype:|int affected rows
	 */
	public function save($ID = null, $inputData = array())
	{
		if (!$ID || !$inputData) return array();
	
		$this->db->update('galleries', $inputData, $where = array('id'=>$ID), $limit = 1);
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
		return $this->db->delete('galleries', array('id' => $ID), 1);
	}

    public function getPhotos($gallery_id = null)
    {
        if (!$gallery_id) return false;
        $query = $this->db->select('p.*')
            ->from('photos as p')
            ->join("galleries as g", 'p.gallery_id = g.id', 'LEFT')->select('g.name')
            ->where('p.gallery_id', $gallery_id);

        $query = $query->get();
        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else
            return null;
    }

    public function getEvents($filter = array())
    {
        $query = $this->db->from('galleries g')->select('g.id, g.event_date')
            ->join("photos as p", 'g.id = p.gallery_id', 'LEFT')->select('COUNT(p.photo_id) as number')
            ->join("scsm_users as us", 'g.user_id = us.user_id', 'LEFT')
            ->where("g.published", "1")
            ->order_by('g.event_date desc')
            ->group_by('g.id');

        if (isset($filter['user_id'])) $query->where('us.user_id', $filter['user_id']);

        $query = $query->get();
//        print $lastQuery = $this->db->last_query();
        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else
            return null;
    }
}