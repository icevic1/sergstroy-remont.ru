<?php

class Staff_mod extends CI_Model {

	public static $search_fields = array('0'=>'--all--', '1' => 'ID', '2'=>'Name/email');
	public static $activity_status = array('0'=>'Active', '1'=>'Suspended', '2'=>'Deleted'); //0-active; 1-suspended; 2-deleted
	public $n_parents = 6;
	public static $PICTypes = array('1'=>'Administrator','2'=>'Authorized Signatory');
	public static $ACCOUNT_TYPE = '0'; //0-pic, kam, other; 1-subscriber
	
    function __construct()
	{
		parent::__construct();
// 		$this->load();
	}
	
	public function getByID($user_id = null, $full = false)
	{
		if (!$user_id) return false;
	
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->where('us.user_id', $user_id)
			->limit(1);
	
		if (true == $full) {
			$query->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left');
			$query->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'left')->select('ro.role_id, ro.role_name, ro.role_description, ro.staff_type, ro.is_default_responsible');
		}
	
		$query = $query->get();
		// 		print $lastQuery = $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	public function getByEmail($email = null)
	{
		if (!$email) return null;
	
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->where('us.email', $email);
	
		$query = $query->get();
		// 		print $lastQuery = $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	public function searchUsers($filter = array(), $retur_type = 'object')
	{
	
		$query = $this->db->select("us.*, GROUP_CONCAT(ro.role_id SEPARATOR ',') as `role_ids`, GROUP_CONCAT(ro.role_name SEPARATOR ', ') as `role_name`")
			->from('scsm_users as us')
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left')
			->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'left')
			->where('us.is_deleted', '0')
			->group_by('us.user_id');
	
		if (!empty($filter['searchtext']) && array_key_exists($filter['search_field'], $this->search_fields) && $filter['search_field'] == 1) {
			$query->where('us.staff_id', $filter['searchtext']);
		} elseif (!empty($filter['searchtext']) && $filter['search_field'] == '0') {
			$query->like('CONCAT(us.name, us.firstname, us.lastname, us.email, us.phone, us.skype)', $filter['searchtext'], 'both');
		}
	
		if (isset($filter['role_id']) && $filter['role_id'] > 0) {
			$query->where('ur.role_id', $filter['role_id']);
		}
		
		if (isset($filter['user_id']) && is_numeric($filter['user_id'])) {
			$query->where('us.user_id', $filter['user_id']);
		} elseif (isset($filter['user_id']) && is_array($filter['user_id'])) {
			$query->where('us.user_id IN('.implode(',', $filter['user_id']).')', null, false);
		}
	
		if (isset($filter['user_type']) ) {
			$query->where('us.user_type', (string)$filter['user_type']);
		} else {
			$query->where('us.user_type', '0'); //get users only
		}
		
		if (!isset($filter['is_deleted']) ) {
			$query->where('us.is_deleted', '0');
		} else {
			$query->where('us.is_deleted', (string)$filter['is_deleted']);
		}
		
		$query->order_by('us.created_at DESC');
	
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		
		if ($retur_type == 'object') {
			$result = $query->result();
		} elseif ($retur_type == 'array') {
			$result = $query->result_array();
		}
		
		return $result;
	}
	
	public function checkUserRole($user_id = null, $role_id = null)
	{
		if (!$user_id || !$role_id) return false;
	
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'inner')
			->where('us.user_id', $user_id)
			->where('ur.role_id', $role_id);
	
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	public function getPICByID($user_id = null, $full = false)
	{
		if (!$user_id) return false;
	
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->where('us.user_id', $user_id)
			->where('us.user_type', '1')
			->limit(1);
		
		if (true == $full) {
			$query->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left');
			$query->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'left')->select('ro.role_id, ro.role_name, ro.role_description, ro.staff_type, ro.is_default_responsible');
		}
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}	
	
	public function getKAMByID($user_id = null, $full = false)
	{
		if (!$user_id) return false;
	
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->where('us.user_id', $user_id)
			->where('us.user_type', '2')
			->limit(1);
		
		if (true == $full) {
			$query->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left');
			$query->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'left')->select('ro.role_id, ro.role_name, ro.role_description, ro.staff_type, ro.is_default_responsible');
		}
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->row_array();
		return $result;
	}
	
	/**
	 * Change user information
	 * @param int $user_id
	 * @param array $data_update
	 * @return int affected_rows
	 */
	public function updateUser($user_id = null, $data_update = array())
	{
		if (!$user_id || !$data_update) return false;
	
		$this->db->update('scsm_users', $data_update, array('user_id'=> $user_id));
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	/**
	 * Add new user
	 * @param array $form_data
	 * @return int last insert id
	 */
	public function insertUser($form_data = array())
	{
		if (empty($form_data)) return false;
		$this->db->insert('scsm_users', $form_data);
	
		//print $lastQuery = $this->db->last_query();
		return $this->db->insert_id();
	}
	
	/**
	 * change status of staff to deleted
	 * @param string $staff_id
	 * @return int affectedRows
	 */
	public function deleteUser($user_id = null)
	{
		if (!$user_id) return false;
		$this->db->update('scsm_users', array('is_deleted' => '1'), array('user_id'=> $user_id));
// 		print $lastQuery = $this->db->last_query();die;
		return $this->db->affected_rows();
	}
	
	
	public function getNoCustUsers($WebCustId = null, $user_type = false)
	{
		if (!$user_type) return array();
		
		$whereJoin = $WebCustId? "AND cs.WebCustId != {$WebCustId}" : '';
		
		$query = $this->db->select('us.*')->distinct()
			->from('scsm_users as us')
			->join('sm_customers_users as cs', "us.user_id = cs.user_id {$whereJoin}", 'left')
			->where('us.user_type', (string)$user_type)
			->where('us.is_deleted', '0')
			;
	
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->result_array();
		return $result;
	}
	
	public function getCustUsers($WebCustId = null, $user_type = false, $role_id = null)
	{
		if (!$WebCustId) return array();
		
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->join('sm_customers_users as cs', "us.user_id = cs.user_id AND cs.WebCustId = {$WebCustId}", 'INNER')
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left')
			->where('us.is_deleted', '0')
			;
	
		if ($user_type !== false)  $query->where('us.user_type', (string)$user_type);
		if ($role_id) $query->where("ur.role_id IN({$role_id})", null, false);
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->result_array();
		return $result;
	}
	
	public function getUserCustomers($user_id = null, $keyArray = false)
	{
		if (!$user_id) return array();
		
		//scsm_users
		$query = $this->db->select('c.*')
			->from('sm_customers as c')
			->join('sm_customers_users as cu', "c.WebCustId = cu.WebCustId", 'INNER')->select('cu.user_id')
			->where('cu.user_id', $user_id)
			;
	
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->result_array();
		
		if ($keyArray === true) {
			$companies = array();
			foreach ($result as $item) {
				$companies[$item['WebCustId']] = $item['CustName'];
			}
			$result = $companies;
		} elseif ($keyArray === 'values') {
			$companies = array();
			foreach ($result as $item) {
				$companies[] = $item['WebCustId'];
			}
			$result = $companies;
		}
		
		return $result;
	}
	
	/**
	 * Assign user to customer
	 * @param array $form_data
	 * @return int last insert id
	 */
	public function insertCustUser($form_data = array())
	{
		if (empty($form_data)) return false;
		$this->db->insert('sm_customers_users', $form_data);
	
		//print $lastQuery = $this->db->last_query();
		return $this->db->insert_id();
	}
	
	/**
	 * change status of staff to deleted
	 * @param string $staff_id
	 * @return int affectedRows
	 */
	public function deleteCustUsers($WebCustId = null)
	{
		if (!$WebCustId) return false;
		$this->db->delete('sm_customers_users', array('WebCustId' => $WebCustId));
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	public function getUserRoles($user_id = null, $keyArray = false)
	{
		if (!$user_id) return array();
	
		$query = $this->db->select('*')
			->from('smacl_users_roles ur')
			->join('smacl_roles as r', "ur.role_id = r.role_id", 'INNER')->select('r.role_name')
			->where('user_id', $user_id);
	
		$query = $query->get();
		// 		print $lastQuery = $this->db->last_query();
		$result = $query->result_array();
	
		if ($keyArray === true) {
			$out = array();
			foreach ($result as $item) {
				$out[$item['role_id']] = $item['role_name'];
			}
			$result = $out;
		} elseif ($keyArray === 'values') {
			$out = array();
			foreach ($result as $item) {
				$out[] = $item['role_id'];
			}
			$result = $out;
		}
	
		return $result;
	}
	
	/**
	 * Delete user roles
	 * @param string $user_id
	 * @param string $role_id
	 * @return int affectedRows
	 */
	public function deleteUserRoles($user_id = null, $role_id = null)
	{
		if (!$user_id) return false;
		
		$where = array('user_id' => $user_id);
		
		if ($role_id) $where['role_id'] = $role_id;
		
		$this->db->delete('smacl_users_roles', $where);
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	/**
	 * Add new role to user
	 * @param array $form_data
	 * @return int last insert id
	 */
	public function insertUserRoles($form_data = array())
	{
		if (empty($form_data)) return false;
		$this->db->insert('smacl_users_roles', $form_data);
	
		//print $lastQuery = $this->db->last_query();
		return $this->db->insert_id();
	}
/*
 * 
 * @param string $staff_id
 * @param string $full
 * @return boolean|unknown*/	
	public function _getByID($staff_id = null, $full = false)
	{
        if (!$staff_id) return false;
        
		$query = $this->db->select('us.*')
			->from('sm_staff as us')
			->where('us.staff_id', $staff_id)
			->limit(1);
		
		if ($full) {
			$query->join('smacl_roles as ro', 'ro.role_id = us.role_id', 'left')
				->select('ro.role_name, ro.role_description, ro.staff_type, ro.is_default_responsible');
			$query->join('sm_companies as co', 'co.company_id = us.company_id', 'left')
				->select('co.company_name, co.picassigned_type');
			$query->join('sm_branches as br', 'br.branch_id = us.branch_id', 'left')
				->select('br.branch_name');
		}
		$result = $query->get()->row();
// 		print $lastQuery = $this->db->last_query();
		return $result;
	}
	
	/**
	 * Get responsible customer CSR (KAM)
	 * @param string $WebCustId
	 * @return multitype:|unknown
	 */
	public function getResponsibleCustomer($WebCustId = null)
	{
		if (!$WebCustId) return array();
		
		$query = $this->db->select('us.*')
			->from('scsm_users as us')
			->join('sm_customers_users as cs', "us.user_id = cs.user_id AND cs.WebCustId = {$WebCustId}", 'INNER')
			
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'INNER')
			->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'INNER')
			
			->where('ro.is_default_responsible', '1')
			->where('us.user_type', '2')
			->where('us.is_deleted', '0');
		
		$query = $query->get();
// 		print $lastQuery = $this->db->last_query();
		$result = $query->row();
		return $result;
	}
	
	/**
	 * Add new saff user
	 * @param unknown $form_data
	 * @return int last insert id
	 */
	public function addStaff($form_data = array())
	{
		if (empty($form_data)) return false;
		$this->db->insert('sm_staff', $form_data);
		
		//print $lastQuery = $this->db->last_query();
		return $this->db->insert_id();
	}
	
	/**
	 * Change staff information
	 * @param int $staff_id
	 * @param array $data_update
	 * @return int affected_rows
	 */
	public function updateStaffByID($staff_id = null, $data_update = array())
	{
		if (!$staff_id || !$data_update) return false;
	
		$this->db->update('sm_staff', $data_update, array('staff_id'=> $staff_id));
// 		print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	/**
	 * change status of staff to deleted
	 * @param string $staff_id
	 * @return int affectedRows
	 */
	public function deleteStaffByID($staff_id = null)
	{
		$this->db->update('sm_staff', array('activity_status' => '2'), array('staff_id'=> $staff_id));
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	public function search($filter = array())
	{
	
		$query = $this->db->select('us.*, co.company_name, co.picassigned_type, ro.role_name')
			->from('sm_staff as us')
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'left')
			->join('sm_staff_companies as sco', 'sco.staff_id = us.staff_id', 'left')
			->join('sm_companies as co', 'co.company_id = sco.company_id', 'left');
	
		$roleJoinType = 'left';
		
		if (!empty($filter['searchtext']) && array_key_exists($filter['search_field'], $this->search_fields) && $filter['search_field'] == 1) {
			$query->where('us.staff_id', $filter['searchtext']);
		} elseif (!empty($filter['searchtext']) && $filter['search_field'] == '0') {
			$query->like('CONCAT(us.sfirstname, us.slastname, us.email, us.phone, us.skype)', $filter['searchtext'], 'both');
		}
	
		if (isset($filter['role_id']) && $filter['role_id'] > 0) {
			$query->where('ur.role_id', $filter['role_id']);
		}
	
		if (isset($filter['company_id']) && $filter['company_id'] > 0) {
			$query->where('sco.company_id', $filter['company_id']);
		}
	
		if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
			$query->where('us.branch_id', $filter['branch_id']);
		}
	
		if (isset($filter['activity_status']) && array_key_exists($filter['activity_status'], self::$activity_status)) {
			$query->where('us.activity_status', $filter['activity_status']);
		}
		
		if (isset($filter['is_default_responsible']) && $filter['is_default_responsible']) {
			$query->where('ro.is_default_responsible', '1')->order_by('us.priority DESC');
			$roleJoinType = 'inner';
		} else {
			$query->order_by('us.activity_status DESC, us.created_at DESC');
		}
		
		$query->join('smacl_roles as ro', 'ur.role_id = ro.role_id', $roleJoinType);
		
		$result = $query->get()->result();
	
// 		print $lastQuery = $this->db->last_query();
	
		return $result;
	}
	
	public function getCompanyPIC($company_id = null, $keyArray = false)
	{
	
		$query = $this->db->select('*')
			->from('sm_staff as us')
			->join('smacl_users_roles as ur', 'us.user_id = ur.user_id', 'inner')
			->join('smacl_roles as ro', 'ur.role_id = ro.role_id', 'inner')
			->join('sm_staff_companies as sco', 'sco.staff_id = us.staff_id', 'inner')
			->where('sco.company_id', $company_id)
			->where('us.activity_status', '0') //0 - active
			->where('ro.is_default_responsible', '1')
			->order_by('us.priority DESC');
	
		$queryResult = $query->get()->result();
		//print $lastQuery = $this->db->last_query();
		
		if ($keyArray == true && $queryResult) {
			$resultArray = array();
			foreach ($queryResult as $item) {
				$resultArray[$item->staff_id] = $item->sfirstname .' '. $item->slastname. ' ('.$item->role_name.')';
			}
			$queryResult = $resultArray;
		}
	
		return $queryResult;
	}

	public function searchUsersByRolesAndCust($role_ids = '', $WebCustId = null)
	{
		if (!$role_ids || !$WebCustId) return false;
		
		$searchQuery = "SELECT us.* 
				FROM `scsm_users` AS `us`
				INNER JOIN `sm_customers_users` AS `cs` ON `us`.`user_id` = `cs`.`user_id` AND `cs`.`WebCustId` = {$WebCustId}
				LEFT JOIN `smacl_users_roles` AS `ur` ON `us`.`user_id` = `ur`.`user_id`
				WHERE `us`.`is_deleted` = '0' AND ur.role_id IN ({$role_ids})
				UNION
				SELECT us.* 
				FROM `scsm_users` AS `us`
				LEFT JOIN `smacl_users_roles` AS `ur` ON `us`.`user_id` = `ur`.`user_id`
				WHERE `us`.`is_deleted` = '0' AND ur.role_id IN ({$role_ids}) 
					AND ur.role_id NOT IN(
						SELECT ur.role_id
						FROM `scsm_users` AS `us`
						INNER JOIN `sm_customers_users` AS `cs` ON `us`.`user_id` = `cs`.`user_id` AND `cs`.`WebCustId` = {$WebCustId}
						LEFT JOIN `smacl_users_roles` AS `ur` ON `us`.`user_id` = `ur`.`user_id`
						WHERE `us`.`is_deleted` = '0' AND ur.role_id IN ({$role_ids})
					)
					AND ur.role_id NOT IN(
						SELECT role_id
						FROM sm_customers_users cu 
						INNER JOIN smacl_users_roles ur on cu.user_id=ur.user_id
						GROUP BY role_id
					)";
		$query = $this->db->query($searchQuery);
// 		print $lastQuery = $this->db->last_query();die;
		return $query->result_array();
	}
	
	public function getNotify($ID = null)
	{
		if (!$ID) return null;
		$query = $this->db->select('*')
		->from('servicetickets.st_notifications')
		->where('ID', $ID)
		->get();
	
// 		echo  $this->db->last_query();
		$result = $query->row_array();
		return  $result;
	}
}