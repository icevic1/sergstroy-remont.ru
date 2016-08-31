<?php
/**
 * Subscriber model
 * @author victor
 *
 */
class Subscriber_mod extends CI_Model {

	public static $def_members_perpage = 10;
	public static $search_fields = array('0'=>'--all--', '1' => 'ID', '2'=>'Name', '3'=>'Email','4'=>'Phone');
	public static $sc_status = array('0'=>'Imported','1'=>'Active', '2'=>'Suspended', '3'=>'Deleted'); //0-imported; 1-active; 2-suspended;3-deleted
	public static $ACCOUNT_TYPE = '1'; //0-pic, kam, other; 1-subscriber
	
    function __construct()
	{
		parent::__construct();
	}

	public function loadSubscriber($PnoneNo = null)
	{
		$this->load->helper('ngbssquery');
	
		
		if (!$PnoneNo) return null;
		
		$Subscriber_Info = Subscriber_Info($PnoneNo);
		
		if (!$Subscriber_Info) return null;
		
		$current_subscriber = array(
				'Subscriber_Info' => $Subscriber_Info,
				'Tariff_Info' => Tariff_Plan($PnoneNo),
				'Service_Info'=> Service_Info($PnoneNo),
				'Balance_info'=> Balance_Info($PnoneNo),
				'Bill_Info'=>  Bill_Info($PnoneNo),
				'Free_Info'	=> Free_Unit($PnoneNo)//,
			//	'CDR_Info' => CDR_Info($PnoneNo)
		);
		
		return $current_subscriber;
	}
	
	public function getFirstCustSubscriber($WebCustId = null, $GroupId = null)
	{
		$this->load->helper('ngbssquery');

		if ($WebCustId && !$GroupId)  {
			$customer = $this->Customer_model->getWebCustById($WebCustId);
			$GroupId = $customer['GroupId'];
		}
		
		if (!$GroupId) return null;
		
		$Group_Members = Group_Member($GroupId); 
		
		if (!$Group_Members) return null;
// 		var_dump(current($Group_Members['MemberSubscriberList']), key($Group_Members['MemberSubscriberList']));
		$subsmember = key($Group_Members['MemberSubscriberList']);
	
		return $subsmember;
	}
	
	public function getByID($subs_id = null, $full = false)
	{
        
		$query = $this->db->select('su.*')
			->from('sm_subscribers as su')
			->where('su.subs_id', $subs_id)
			->limit(1);
		
		if ($full) {
			$query->join('sm_companies as co', 'co.company_id = su.company_id', 'left')->select('co.company_name');
			$query->join('sm_branches as br', 'br.branch_id = su.branch_id', 'left')->select('br.branch_name');
			$query->join('sm_staff as st', 'st.staff_id = su.staff_id', 'left')->select('st.staff_name');
			$query->join('sm_departments as dp', 'dp.dep_id = su.dep_id', 'left')->select('dp.dep_name');
		}
		
		$result = $query->get()->row();
// 		print $lastQuery = $this->db->last_query();
        
		return $result;
	}
	
	
	/**
	 * Change subscriber information
	 * @param int $subs_id
	 * @param array $data_update
	 * @return int affected_rows
	 */
	public function updateSubscriberByID($subs_id = null, $data_update = array())
	{
		if (!$subs_id || !$data_update) return false;
	
		$this->db->update('sm_subscribers', $data_update, array('subs_id'=> $subs_id));
// 		print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	/**
	 * change subscriber status to deleted
	 * @param string $subs_id
	 * @return int affectedRows
	 */
	public function deleteSubscriberByID($subs_id = null)
	{
		$this->db->update('sm_subscribers', array('sc_status' => '3'), array('subs_id'=> $subs_id));
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	public function search($filter = array(), $per_page = null, $page = null)
	{
		$this->db->select('SQL_CALC_FOUND_ROWS su.*', false)
			->from('sm_subscribers as su')
			->join('sm_companies as co', 'co.company_id = su.company_id', 'left') ->select("co.company_name")
			->join('sm_branches as br', 'br.branch_id = su.branch_id', 'left')->select("br.branch_name")
			->join('sm_departments as dep', 'dep.dep_id = su.dep_id', 'left')->select("dep.dep_name")
			->join('sm_staff as st', 'st.staff_id = su.staff_id', 'left')->select("st.staff_name, st.sfirstname, st.slastname");
		

		/**
		 * '0'=>'--all--', '1' => 'ID', '2'=>'Name', '3'=>'Email','4'=>'Phone'
		 */
		if (!empty($filter['searchtext']) && array_key_exists($filter['search_field'], self::$search_fields) ) {
			
			switch ($filter['search_field']) {
				case 0: $this->db->like("CONCAT(su.firstname, ' ', su.lastname,' ', su.email,' ', su.phone)", $filter['searchtext'], 'both'); break;
				case 1: $this->db->where('su.subs_id', $filter['searchtext']); break;
				case 2: $this->db->like("CONCAT(su.firstname,' ', su.lastname)", $filter['searchtext'], 'both'); break;
				case 3: $this->db->where('su.email', $filter['searchtext']); break;
				case 4: $this->db->where('su.phone', $filter['searchtext']); break;
			}
		}
	
		if (isset($filter['company_id']) && !is_array($filter['company_id']) && $filter['company_id'] > 0) {
			$this->db->where('su.company_id', $filter['company_id']);
		} elseif (isset($filter['company_id']) && is_array($filter['company_id'])) {
			$this->db->where("su.company_id IN (".implode(',', array_keys($filter['company_id'])).')', NULL, FALSE);
		}
	
		if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
			$this->db->where('su.branch_id', $filter['branch_id']);
		} 
		
		if (isset($filter['staff_id']) && $filter['staff_id'] > 0) {
			$this->db->where('su.staff_id', $filter['staff_id']);
		}
		
// 		$filter['sc_status'] = '1';
		if (isset($filter['sc_status']) && array_key_exists($filter['sc_status'], self::$sc_status)) {
			$this->db->where('su.sc_status', $filter['sc_status']);
		}
	
		$this->db->order_by('su.sc_status DESC, su.imported_at DESC');
		
// 		$range = (($page-1) * $per_page < 0)? 0: (($page-1) * $per_page);
		
// 		$this->db->limit($per_page, $range);

		if (!is_null($page)) $this->db->limit($per_page, $page);
		
		$query = $this->db->get();
		$result = array();
// print $this->db->last_query();
		if ($query->num_rows() > 0) {
			
			$result = $query->result();
			if (!is_null($page)) {
				$result['FOUND_ROWS'] = (int)$this->db->query('SELECT FOUND_ROWS() AS rows_count')->row()->rows_count;
			}
		}
		
		return $result;
	}
	
	/* public function init_pagination($uri,$total_rows,$per_page=10,$segment=5)
	{
		$ci                          =& get_instance();
		
		$config['base_url'] = base_url().$uri;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = $segment;
		$config['use_page_numbers']  = TRUE;
		$config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li><span><b>";
		$config['cur_tag_close'] = "</b></span></li>";
		
		$ci->pagination->initialize($config);
		return $config;
	} */
	
	public static function getStatusByID($sc_status = null)
	{
		if (false == array_key_exists($sc_status, self::$sc_status)) return false;
	
		return self::$sc_status[$sc_status];
	}

	public function getCompanySubscribers($company_id = null, $limit = 0)
	{
		$query = $this->db->select('su.*')
			->from('sm_subscribers as su')
			->where('su.company_id', $company_id)
			->limit($limit)
			->get();
		if ($limit == 1) return $query->row();
		return $query->result();
	}
	
	public function getSubscribersStatus($status_type, $status_id, $limit=0)
	{
		$query = $this->db
			->from('sc_subscriber_status')->select('status_name')
			->where('status_type', (string)$status_type)
			->where('status_id', (string)$status_id);
		
		$query = $query->get();
		return $query->row('status_name');
	}
	/*
	public function getSubscribersOffer($offer_id, $limit=0)
	{
		$query = $this->db
		->from('sm_offers')->select('web_name')
		->where('offering_id', (string)$offer_id);
	
		$query = $query->get();
		return $query->row('web_name');
	}
	*/
}