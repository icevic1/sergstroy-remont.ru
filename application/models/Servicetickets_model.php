<?php

class Servicetickets_model extends CI_Model {

	public $ST_DB;
	public static $TicketType_Subscriber = '0';
	public static $TicketType_Customer = '1';
	protected $defaultStausID = 1;
	protected $st_db_name;
	protected $sc_db_name;
	public static $KpiTimeHours = array(24=>24, 48=>48, 72=>72);
	public static $MethodType = array('0'=>'Site', '1'=>'Email', '2'=>'SMS'); //0-to City; 1-to email; 2-to sms
	public static $ApproveStatuses = array('1'=>'7', '2'=>'8', '3'=>'9'); //'7'=>'Approve 1', '8'=>'Approve 2', '9'=>'Approve 3'
	public static $NotificationType = array('0'=>'Notification', '1'=>'Approve', '2'=>'Reject', '3'=>'Ready for execution', '4'=>'Execution done', 
			'5'=>'Notify Feedback to Initiator', '6'=>'Notify SLA Left', '7'=>'Processing', '8'=>'CLOSED Request', '9'=>'Notification Complaint',
			'10'=>'CLOSED Complaint', '11'=>'Complain Processing (execution)', '12'=>'Complain Progress review (after execute)', '13'=>'To close',
			'14'=> 'Verification code', '15'=> 'Password has been changed', '16'=> 'Recovery password'
	); 
	public static $DefaultCloseStatus = '3';
	public static $DefaultProcessStatus = 2;
	public static $DefaultRejectStatus = '10';
	public static $DefaultReadyExecutionStatus = '11';
    
    function __construct()
	{
		parent::__construct();
        $this->ST_DB = $this->load->database('servicetickets',TRUE);
        
        //$db2 = $this->load->database('servicetickets', TRUE);
        $this->st_db_name = $this->ST_DB->database;
        //$db2->close();
         
        //$this->load->database('selfcare', true);
        $this->sc_db_name = $this->db->database;
	}
	
	function getDefaultStatusID()
    {
        return $this->defaultStausID;
    }
    // --------------------------------------------------------------------

      /** 
       * function SaveForm()
       *
       * insert form data
       * @param $form_data - array
       * @return Bool - TRUE or FALSE
       */

	function SaveFormNewST($form_data)
	{
		if (empty($form_data)) return false;
		$this->ST_DB->insert('st_servicetickets', $form_data);
        return $this->ST_DB->insert_id();		
        
	}// end SaveForm()
	
	function updateST($ticket_id = null, $data_update = array())
	{
		if (empty($ticket_id) || empty($data_update)) return false;
		
		if (isset($data_update['ClosureDateTime']) && $data_update['ClosureDateTime'] == 'NOW()') {
			$this->ST_DB->set('ClosureDateTime', 'NOW()', FALSE);
			unset($data_update['ClosureDateTime']);
		}
		
		if (isset($data_update['LastEditDate']) && $data_update['LastEditDate'] == 'NOW()') {
			$this->ST_DB->set('LastEditDate', 'NOW()', FALSE);
			unset($data_update['LastEditDate']);
		}
		
		$this->ST_DB->where('TicketId', $ticket_id);
		$this->ST_DB->limit(1);
		$this->ST_DB->update('st_servicetickets', $data_update);
// 		print $lastQuery = $this->ST_DB->last_query();
		return $this->ST_DB->affected_rows();
	}// end SaveForm()
    
    public function GetTicketByID($TicketID)
    {        
        $columns = "TicketID, TypeID, SubjectID, SeverityID, PriorityID, GroupID, StatusID, CreatedByID, ClosedByID, CustomerName,";
        $columns .="SubscriberName, AccountID, SIMID, PhoneNumber, Description, CreationDateTime, LastEditDate, CSRResponsibleID,";
        $columns .="ProgressComment, ApprovalEntityID, LeadTimeHours, DATE_FORMAT(CreationDateTime,('%d.%m.%Y')) AS CreationDate,";
        $columns .=" DATE_FORMAT(CreationDateTime,('%H:%i')) AS CreationTime, ChangedByUserID";
        $this->ST_DB-> select('*');
        $this->ST_DB-> from('st_servicetickets');
        $this->ST_DB-> where('TicketID',$TicketID);  
        
        $query = $this->ST_DB-> get();
			
		if ($query->num_rows () > 0) {
			return $query->row ();
		} else {
			return FALSE;
		}
        
    }// end GetTicketByID()
    
    public function getFullInfoByTicketID($TicketID = null)
    {
    	
    	$query = $this->ST_DB-> select('st.*, 
    			ty.TypeName, ty.TypeDescription, 
    			sb.SubjectName, sb.SubjectDescription, sb.KpiTimeHours, 
    			sv.SeverityName, sv.SeverityDescription, 
    			pr.PriorityName, pr.PriorityDescription,
    			ss.StatusName, ss.StatusDescription,
    			gr.GroupName')
    			->from("{$this->st_db_name}.st_servicetickets as st")
    			->join("{$this->st_db_name}.st_types as ty", 'st.TypeID = ty.TypeID', 'LEFT')
    			->join("{$this->st_db_name}.st_subjects as sb", 'st.SubjectID = sb.SubjectID', 'LEFT')
    			->join("{$this->st_db_name}.st_severities as sv", 'st.SeverityID = sv.SeverityID', 'LEFT')
    			->join("{$this->st_db_name}.st_priorities as pr", 'st.PriorityID = pr.PriorityID', 'LEFT')
    			->join("{$this->st_db_name}.st_statuses as ss", 'st.StatusID = ss.StatusID', 'LEFT')
    			->join("{$this->st_db_name}.st_statuses as ss2", 'st.forCustStatusID = ss2.StatusID', 'LEFT')->select('ss2.StatusName as forCustStatusName')
    			->join("{$this->st_db_name}.st_groups as gr", 'st.GroupID = gr.GroupID', 'LEFT')
    			
//     			->join("{$this->sc_db_name}.sm_subscribers as us", 'st.AccountID = us.subs_id', 'LEFT')
    			->join("{$this->sc_db_name}.sm_customers as cus", 'st.CompanyID = cus.WebCustId', 'LEFT')->select("cus.CustName, cus.AgreementId, cus.GroupId")
    			->join("{$this->sc_db_name}.scsm_users as sf", 'st.CreatedByID = sf.user_id', 'LEFT')->select("sf.name as whoCreateName", false)
    			->join("{$this->sc_db_name}.scsm_users as sfe", 'st.ChangedByUserID = sfe.user_id', 'LEFT')->select("sfe.name as whoEditeName", false)
    			->join("{$this->sc_db_name}.scsm_users as sfc", 'st.ClosedByID = sfc.user_id', 'LEFT')->select("sfc.name as whoCloseName", false)
//     			->join('smacl_roles as ro', 'ro.role_id = pe.role_id', 'inner')
    			->where('TicketID', $TicketID);
    	
    	$result = $query->get()->row();
//     	print $lastQuery = $this->ST_DB->last_query();
    	
    	return $result;
    }
    
    public function GetSubjectTypeByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_types')
	    	->where('TypeID', $ID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function GetTypes($keyArray = false, $asArray = false)
    {        
        $this->ST_DB->select('t.*');
        $this->ST_DB->from('st_types as t');

        $query = $this->ST_DB->get();
   
        //return $ST_DB->last_query(); 
        //return $query -> num_rows();

        if($query->num_rows() > 0)
        {
        	if ($asArray) {
        		$result = $query->result_array();
        		if ($keyArray == true) {
        			$result = array_column($array, 'TypeName', 'TypeID');
        		}
        	} else {
	        	$result = $query->result();
	        	if ($keyArray == true) {
	        		$outAssociated = array();
	        		foreach ($result as $item) {
	        			$outAssociated[$item->TypeID] = trim($item->TypeName);
	        		}
	        		$result = $outAssociated;
	        	}
        	}
           return $result;
        } else {
           return FALSE;
        }
        
    }// end GetTypes()
    
    public function GetAllowedTypes()
    {        

    	$result = array();
        $allTypes = $this->GetTypes($keyArray = false, $asArray = true);
   
        if (is_array($allTypes)) {
	        $filtered_array = array_filter($allTypes, function ($element) {
	        	return $this->acl->can_read(null, $element['resource_id']);
	        } );
	        if ($filtered_array)
	        $result = array_column($filtered_array, 'TypeName', 'TypeID');
        }
        return $result;
    }// end GetAllowedTypes()
    
    /**
     * Insert new statuses
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addSubjectType($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_types', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update status by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateSubjectType($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_types', $data_update, $where = array('TypeID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete status by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteSubjectType($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_types', array('TypeID' => $ID), 1);
    }
    
    /**
     * Get all subjects from ST DB
     * @param string $TypeID
     * @param string $keyArray
     * @return multitype:string |multitype:
     */
    public function GetSubjects($TypeID = null, $keyArray = false, $GroupID = null)
    {      
    	
        $this->ST_DB->select("sub.*, GROUP_CONCAT(ro.role_name SEPARATOR ', ') as `notifications_to`");
        $this->ST_DB->from('st_subjects as sub');
        $this->ST_DB->join('st_notify_recipients as nr', 'sub.SubjectID = nr.SubjectID', 'LEFT'); 
        $this->ST_DB->join('st_notifications as n', "nr.NotifyID = n.ID AND n.Type = '0'", 'LEFT');
        $this->ST_DB->join("{$this->sc_db_name}.smacl_roles as ro", 'nr.role_id = ro.role_id', 'LEFT');
        $this->ST_DB->join("{$this->sc_db_name}.smacl_roles as ro2", 'sub.close_role_id = ro2.role_id', 'LEFT')->select('ro2.role_name as close_role_name');
        $this->ST_DB->join('st_types as stp', 'sub.TypeID = stp.TypeID', 'LEFT')->select('stp.TypeName');
        $this->ST_DB->join('st_groups as gr', 'sub.GroupID = gr.GroupID', 'LEFT')->select('gr.GroupName');
        $this->ST_DB->join('st_priorities as pr', 'sub.DefPriorityID = pr.PriorityID', 'LEFT')->select('pr.PriorityName');
        $this->ST_DB->join('st_severities as sv', 'sub.DefSeverityID = sv.SeverityID', 'LEFT')->select('sv.SeverityName');
        
        $this->ST_DB->group_by('`sub`.`SubjectID`');
        $this->ST_DB->order_by('sub.SubjectID DESC');
        
        if (!is_null($TypeID)) {
        	$this->ST_DB->where('sub.TypeID', $TypeID);  
        }
        
        if (!is_null($GroupID)) {
        	$this->ST_DB->where('sub.GroupID', $GroupID);  
        }
        
        $query = $this->ST_DB->get();
   		
//         print $this->ST_DB->last_query();die;
        
        if($query -> num_rows() > 0) {
           $result = $query->result();
        	if ($keyArray == true) {
        		$outAssociated = array();
        		foreach ($result as $item) {
        			$outAssociated[$item->SubjectID] = trim($item->SubjectName);
        		}
        		$result = $outAssociated;
        	}
           return $result;
        } else {
           return array();
        }
        
    }// end GetSubjects()
    
    public function GetStatuses($keyArray = false)
    {        
        $this->ST_DB->select('*')->from('st_statuses');

        $query = $this->ST_DB->get();
			
			// return $this->ST_DB->last_query();
			// return $query -> num_rows();
		if ($query->num_rows () > 0) {
			$result = $query->result ();
			if ($keyArray == true) {
				$outAssociated = array ();
				foreach ( $result as $item ) {
					$outAssociated [$item->StatusID] = trim($item->StatusName);
				}
				$result = $outAssociated;
			}
			return $result;
		} else {
			return FALSE;
		}
        
    }// end GetStatuses()
    
    public function GetStatusByID($StatusID = null, $asArray = false)
    {
    	if (!$StatusID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_statuses')
	    	->where('StatusID', $StatusID)
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
    
    	$this->ST_DB->insert('st_statuses', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
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
    
    	$this->ST_DB->update('st_statuses', $data_update, $where = array('StatusID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
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
    	return $this->ST_DB->delete('st_statuses', array('StatusID' => $ID), 1);
    }
    
    public function GetSeverityByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
    	->from('st_severities')
    	->where('SeverityID', $ID)
    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function GetSeverities($keyArray = false)
    {        
        $this->ST_DB->select('SeverityID, SeverityName, SeverityDescription');
        $this->ST_DB->from('st_severities');  

        $query = $this->ST_DB->get();
		if ($query->num_rows () > 0) {
			$result = $query->result ();
			if ($keyArray == true) {
				$outAssociated = array ();
				foreach ( $result as $item ) {
					$outAssociated [$item->SeverityID] = trim($item->SeverityName);
				}
				$result = $outAssociated;
			}
			return $result;
		} else {
			return FALSE;
		}
        
    }// end GetSeverities()
    
    /**
     * Insert new statuses
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addSeverity($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_severities', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update status by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateSeverity($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_severities', $data_update, $where = array('SeverityID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete status by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteSeverity($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_severities', array('SeverityID' => $ID), 1);
    }
    
    public function GetPriorityByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_priorities')
	    	->where('PriorityID', $ID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function GetPriorities($keyArray = false)
    {        
        $this->ST_DB->select('PriorityID, PriorityName, PriorityDescription');
        $this->ST_DB->from('st_priorities');  

        $query = $this->ST_DB-> get();
		if ($query->num_rows () > 0) {
			$result = $query->result ();
			if ($keyArray == true) {
				$outAssociated = array ();
				foreach ( $result as $item ) {
					$outAssociated[$item->PriorityID] = trim($item->PriorityName);
				}
				$result = $outAssociated;
			}
			return $result;
		} else {
			return FALSE;
		}
        
    }// end GetPriorities()
    
    /**
     * Insert new statuses
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addPriority($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_priorities', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update status by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updatePriority($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_priorities', $data_update, $where = array('PriorityID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete status by ID
     * @param string $ID
     * @return boolean
     */
    public function deletePriority($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_priorities', array('PriorityID' => $ID), 1);
    }
    
    public function GetAPEntities($keyArray = false)
    {        
        $this->ST_DB-> select('ID, EntityName, EntityDescription');
        $this->ST_DB-> from('st_approvalentities');  

        $query = $this->ST_DB-> get();
		if ($query->num_rows () > 0) {
			$result = $query->result ();
			if ($keyArray == true) {
				$outAssociated = array ();
				foreach ( $result as $item ) {
					$outAssociated [$item->ID] = trim($item->EntityName . ' ' . $item->EntityDescription);
				}
				$result = $outAssociated;
			}
			return $result;
		} else {
			return FALSE;
		}
        
    }// end GetAPEntities()
    
    public function getSubjectByID($SubjectID = null, $asArray = false)
    {
    	if (!$SubjectID) return null;
    	
    	$query = $this->ST_DB->select('*')
	    	->from('st_subjects')
	    	->where('SubjectID', $SubjectID);
    
    	$result = ($asArray) ? $query->get()->row_array() : $query->get()->row();
    	 
    	return $result;
    }
    
    /**
     * Insert new subject
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addSubject($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_subjects', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update subject by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateSubject($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_subjects', $data_update, $where = array('SubjectID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete subject by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteSubject($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_subjects', array('SubjectID' => $ID), 1);
    }
    
    public function search($filter = array())
    {
//     	$db2 = $this->load->database('servicetickets', TRUE);
    	$st_db_name = $this->ST_DB->database;
//     	$db2->close();
    	
//     	$this->load->database('selfcare', true);
    	$sc_db_name = $this->db->database;

    	$query = $this->ST_DB->select('st.*, sb.*')
    			->from("{$this->st_db_name}.st_servicetickets as st")
    			->join("{$this->st_db_name}.st_types as ty", 'st.TypeID = ty.TypeID', 'LEFT')->select('ty.TypeName')
    			->join("{$this->st_db_name}.st_subjects as sb", 'st.SubjectID = sb.SubjectID', 'LEFT')
    			->join("{$this->st_db_name}.st_severities as sv", 'st.SeverityID = sv.SeverityID', 'LEFT')->select('sv.SeverityName')
    			->join("{$this->st_db_name}.st_priorities as pr", 'st.PriorityID = pr.PriorityID', 'LEFT')->select('pr.PriorityName')
    			->join("{$this->st_db_name}.st_statuses as ss", 'st.StatusID = ss.StatusID', 'LEFT')->select('ss.StatusName')
    			->join("{$this->st_db_name}.st_statuses as ss2", 'st.forCustStatusID = ss2.StatusID', 'LEFT')->select('ss2.StatusName as forCustomerStausName')
    			->join("{$this->st_db_name}.st_groups as gr", 'st.GroupID = gr.GroupID', 'LEFT')->select('gr.GroupName');
    
    	if (!empty($filter['phone_number'])) {
    		$query->like('st.AccountID', $filter['phone_number']);
    	}
    
    	if (isset($filter['TypeID']) && $filter['TypeID'] > 0) {
    		$query->where('st.TypeID', $filter['TypeID']);
    	}
    
    	if (isset($filter['SubjectID']) && $filter['SubjectID'] > 0) {
    		$query->where('st.SubjectID', $filter['SubjectID']);
    	}
    
    	if (isset($filter['PriorityID']) && $filter['PriorityID'] > 0) {
    		$query->where('st.PriorityID', $filter['PriorityID']);
    	}
    
    	if (isset($filter['SeverityID']) && $filter['SeverityID'] > 0) {
    		$query->where('st.SeverityID', $filter['SeverityID']);
    	}
    	if (isset($filter['StatusID'])) {
    		if ($filter['StatusID'] > 0) $query->where('st.StatusID', $filter['StatusID']);
    		if ($filter['StatusID'] < 0) $query->where('st.StatusID != ', abs($filter['StatusID']));
    	}
    	
    	if (isset($filter['date_to'])) {
    		$date_to = $this->ST_DB->escape_like_str($filter['date_to']);
    		$query->where("(`st`.`CreationDateTime` <= '{$date_to}' OR `st`.`LastEditDate` <= '{$date_to}' OR `st`.`ClosureDateTime` <= '{$date_to}')");
    	}
    	
    	if (isset($filter['date_from'])) {
    		$date_from = $this->ST_DB->escape_like_str($filter['date_from']);
    		$query->where("(`st`.`CreationDateTime` >= '{$date_from}' OR `st`.`LastEditDate` >= '{$date_from}' OR `st`.`ClosureDateTime` >= '{$date_from}')");
    	}
    	
    	    
    	$result = $query->get()->result();
    
//     	print $lastQuery = $this->ST_DB->last_query();
    
    	return $result;
    }
    
    public function getHistoryByTicketID($TicketID = null)
    {
    	if (!$TicketID) return null;
    	
    	$st_db_name = $this->ST_DB->database;
    	$sc_db_name = $this->db->database;
    
    	$query = $this->ST_DB->select('st.*')
	    	->from("{$this->st_db_name}.st_servicetickets_hist as st")
	    	->join("{$this->st_db_name}.st_types as ty", 'st.TypeID = ty.TypeID', 'LEFT')->select('ty.TypeName')
	    	->join("{$this->st_db_name}.st_subjects as sb", 'st.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	->join("{$this->st_db_name}.st_groups as gr", 'st.GroupID = gr.GroupID', 'LEFT')->select('gr.GroupName')
	    	->join("{$this->st_db_name}.st_severities as sv", 'st.SeverityID = sv.SeverityID', 'LEFT')->select('sv.SeverityName')
	    	->join("{$this->st_db_name}.st_priorities as pr", 'st.PriorityID = pr.PriorityID', 'LEFT')->select('pr.PriorityName')
	    	->join("{$this->st_db_name}.st_statuses as ss", 'st.StatusID = ss.StatusID', 'LEFT')->select('ss.StatusName')
	    	//->join("{$this->sc_db_name}.sm_subscribers as us", 'st.AccountID = us.subs_id', 'LEFT')
	    	->join("{$this->sc_db_name}.sm_customers as com", 'st.CompanyID = com.WebCustId', 'LEFT')->select('com.CustName')
	    	->join("{$this->sc_db_name}.scsm_users as sf", 'st.CreatedByID = sf.user_id', 'LEFT')->select("sf.name as whoCreateName", false)
	    	->join("{$this->sc_db_name}.scsm_users as sfe", 'st.ChangedByUserID = sfe.user_id', 'LEFT')->select("sfe.name as whoEditeName", false)
	    	->join("{$this->sc_db_name}.scsm_users as sfc", 'st.ClosedByID = sfc.user_id', 'LEFT')->select("sfc.name as whoCloseName", false)
    		->where('st.TicketID', $TicketID)
    		->order_by('st.LastEditDate DESC, st.ClosureDateTime DESC');
    	
//     	$query->join("{$this->sc_db_name}.sm_staff as sf", 'st.CreatedByID = sf.staff_id', 'LEFT');
    		//->select("CONCAT(sf.sfirstname, ' ', sf.slastname) as whoCreateName");
//     	$query->join("{$this->sc_db_name}.sm_staff as sfe", 'st.ChangedByUserID = sfe.staff_id', 'LEFT')
//     		->select("concat(sfe.sfirstname, ' ', sfe.slastname) as whoEditeName");
    	
    	$result = $query->get()->result();
    
//     	print $this->ST_DB->last_query();
//     	$result = $query->result();
    	return $result;
    }
    
    public function getHistoryByID($HistoryID = null)
    {
    	if (!$HistoryID) return null;
    	
    	$query = $this->ST_DB->select('st.*')
	    	->from("{$this->st_db_name}.st_servicetickets_hist as st")
    		->where('st.HistoryID', $HistoryID);

    	$result = $query->get()->row_array();
    
//     	print $this->ST_DB->last_query();
//     	$result = $query->result();
    	return $result;
    }
    
    public function GetNotifications($keyArray = false)
    {
    	$st_db_name = $this->ST_DB->database;
    	$sc_db_name = $this->db->database;
    
    	$query = $this->ST_DB->select('nt.*')
	    	->from("{$this->st_db_name}.st_notifications as nt");
	    	//->join("{$this->st_db_name}.st_subjects as sb", 'nt.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	//->join("{$this->sc_db_name}.smacl_roles as sfr", 'nt.role_id = sfr.role_id', 'LEFT')->select("sfr.role_name")
    		//->order_by('nt.SubjectID ASC, nt.Order ASC');
    	
    	$result = $query->get()->result_array();

    	if ($keyArray == true) {
    		$outAssociated = array ();
    		foreach ( $result as $item ) {
    			$outAssociated [$item['ID']] = self::$NotificationType[$item['Type']] .': '. $item['Title'];
    		}
    		$result = $outAssociated;
    	}
    	
//     	print $this->ST_DB->last_query();
//     	$result = $query->result();
    	return $result;
    }
    
    public function GetSubjectNotifications($SubjectID = null, $Type = null, $limit = null)
    {
    	if (!$SubjectID) return false;
//     	$whereCond = ($Type)? "AND n.Type = '{$Type}'": '';
    	
    	$query = $this->ST_DB->select("nt.*, GROUP_CONCAT(ro.role_id SEPARATOR ',') as `role_ids`, GROUP_CONCAT(ro.role_name SEPARATOR ', ') as `role_name`")
	    	->from("{$this->st_db_name}.st_notifications as nt")
	    	->join("{$this->st_db_name}.st_notify_recipients as nr", 'nt.ID = nr.NotifyID', 'LEFT')
	    	->join("{$this->sc_db_name}.smacl_roles as ro", 'nr.role_id = ro.role_id', 'LEFT')
	    	->join("{$this->st_db_name}.st_subjects as sb", 'nr.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	->where("sb.SubjectID", $SubjectID)
	    	->group_by('nt.ID')
    		->order_by('nt.Order ASC');
    	
    	if (!is_null($Type))  $query->where("nt.Type", (string)$Type);
    	
    	if (!is_null($limit) && $limit > 0) {
    		$query->limit($limit);
    	}
    	
    	$query = $query->get();
    	
    	if (!is_null($limit) && $limit == 1) {
    		$result = $query->row_array();
    	} else {
    		$result = $query->result_array();
    	}
    	
    
//     	print $this->ST_DB->last_query();
//     	$result = $query->result();
    	return $result;
    }
    
    public function getSubjectNotifyByType($SubjectID = null, $RealType = null)
    {
    	if (!$SubjectID || !$RealType) return false;
    	 
    	$query = $this->ST_DB->select("nt.*, GROUP_CONCAT(ro.role_id SEPARATOR ',') as `role_ids`, GROUP_CONCAT(ro.role_name SEPARATOR ', ') as `role_name`")
	    	->from("{$this->st_db_name}.st_notifications as nt")
	    	->join("{$this->st_db_name}.st_notify_recipients as nr", 'nt.ID = nr.NotifyID', 'LEFT')
	    	->join("{$this->sc_db_name}.smacl_roles as ro", 'nr.role_id = ro.role_id', 'LEFT')
	    	->join("{$this->st_db_name}.st_subjects as sb", 'nr.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	->where("sb.SubjectID", $SubjectID)
	    	->where("nt.RealType", $RealType)
	    	->group_by('nt.ID');
    	 
    	$query = $query->get();
    	 
    	$result = $query->row_array();
    	//print $this->ST_DB->last_query();
    	return $result;
    }
    
    public function GetSubjectNotifyRoles($SubjectID = null, $NotifyID = false)
    {
    	if (!$SubjectID || !$NotifyID) return false;
//     	$whereCond = ($Type)? "AND n.Type = '{$Type}'": '';
    	
    	$query = $this->ST_DB->select("nr.role_id")
	    	->from("{$this->st_db_name}.st_notify_recipients as nr")
	    	->where("nr.NotifyID", $NotifyID)
	    	->where("nr.SubjectID", $SubjectID);
    	
    	$result = $query->get()->result_array();
    
//     	print $this->ST_DB->last_query();
//     	$result = $query->result();
    	return $result;
    }
    
    public function GetNotifyByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_notifications')
	    	->where('ID', $ID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    /**
     * Insert new Notify
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addNotify($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_notifications', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update Notify by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateNotify($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_notifications', $data_update, $where = array('ID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete Notify by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteNotify($ID = null)
    {
    	if (!$ID) return false;
    	
    	$this->deleteNotifyRoles($ID);
    	return $this->ST_DB->delete('st_notifications', array('ID' => $ID), 1);
    }
    
    public function getSubjectNotifications_bk($SubjectID = null, $asArray = false)
    {
    	if (!$SubjectID) return null;
    	$query = $this->ST_DB->select('*')
    	->from('st_notifications')
    	->where('SubjectID', $SubjectID)
    	->order_by('Order')
    	->get();
    
    	$result = ($asArray) ? $query->result_array() : $query->result();
    	return  $result;
    }
    
    public function alertsFilter($filter = null, $asArray = false)
    {
    	$query = $this->ST_DB->select('al.*')
    			->from("{$this->st_db_name}.st_alerts as al")
    			->join("{$this->st_db_name}.st_notifications as nt", 'al.NotifyID = nt.ID', 'inner')->select('nt.Title')
//     			->join("{$this->st_db_name}.st_subjects as sb", 'nt.SubjectID = sb.SubjectID', 'LEFT')
    			->join("{$this->sc_db_name}.scsm_users as u", 'al.StaffID = u.user_id', 'LEFT')->select("u.name")
    			
    			->join("{$this->st_db_name}.st_servicetickets as st", 'al.TicketID = st.TicketID', 'LEFT')
//     			->join("{$this->sc_db_name}.sm_subscribers as us", 'st.AccountID = us.subs_id', 'LEFT')
    			->join("{$this->sc_db_name}.sm_customers as c", 'st.CompanyID = c.WebCustId', 'LEFT')->select('c.WebCustId, c.CustName')
    			->order_by('al.IsViewed ASC');
    
    
    	if (isset($filter['StaffID']) && $filter['StaffID'] > 0) {
    		$query->where('al.StaffID', $filter['StaffID']);
    	}
    
    	if (isset($filter['IsViewed'])) {
    		$query->where('al.IsViewed', $filter['IsViewed']);
    	}
    	
    	if (isset($filter['EmailSent'])) {
    		$query->where('al.EmailSent', $filter['EmailSent']);
    	}
    	
    	if (isset($filter['date_to'])) {
    		$date_to = $this->ST_DB->escape_like_str($filter['date_to']);
    		$query->where("(DATE(`al`.`CreatedAt`) <= '{$date_to}')");
    	}
    	
    	if (isset($filter['date_from'])) {
    		$date_from = $this->ST_DB->escape_like_str($filter['date_from']);
			$query->where("(DATE(`al`.`CreatedAt`) >= '{$date_from}')");    	
    	}
    	
    	if (isset($filter['company_id'])) {
    		$query->where('c.WebCustId', $filter['company_id']);
    	}
    	 
    	$result = $query->get()->result();
    
//     	print $lastQuery = $this->ST_DB->last_query();
    
    	return $result;
    }
    
    public function countActiveAlerts($StaffID = null)
    {
    	if (!$StaffID) return 0;
    
    	$this->ST_DB->from('st_alerts')
    				->where('StaffID', $StaffID)
    				->where('IsViewed', '0');
    
    	return $this->ST_DB->count_all_results();
    }
    
    public function createAlert($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_alerts', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    public function createApproveHystory($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_approve_history', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    public function updateApproveHystory($data = array())
    {
    	if (!$data || (isset($data['ID']) && !$data['ID'])) return false;
    	$affectedRows = $this->ST_DB->update('st_approve_history', $data, $where = array('ID'=>$data['ID']), $limit = 1);
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $affectedRows;
    }
    
    public function GetTicketApproveStatus($TicketID = null, $user_id = null, $approve_status = null)
    {
    	if (!$TicketID) return null;
    	$query = $this->ST_DB->from("{$this->st_db_name}.st_approvalentities as ap")->select('ap.approve_order, ap.SubjectID, ap.role_id')
	    	->join("{$this->st_db_name}.st_approve_history as aph", 'ap.ID = aph.ApproveID', 'INNER')->select('aph.*')
	    	->join("{$this->sc_db_name}.smacl_roles as ro", 'ap.role_id = ro.role_id', 'INNER')->select("ro.role_name")
	    	->where('aph.TicketID', $TicketID)
    		->order_by('ap.approve_order ASC');
    	
    	if ($user_id) $query->where('aph.user_id', (int)$user_id);
    	if (!is_null($approve_status)) $query->where('aph.approve_status', (string)$approve_status);
    	
    	$query = $query->get();
    	
//     	print $lastQuery = $this->ST_DB->last_query();
    	$result = $query->result_array();
    	return  $result;
    }
    
    public function createExecuteHystory($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_executive_history', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    public function updateExecuteHystory($data_update = array())
    {
    	if (!$data_update) return array();
    
    	$this->ST_DB->update('st_executive_history', $data_update, $where = array('TicketID'=>$data_update['TicketID'], 'user_id'=>$data_update['user_id']), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function getAlertByTicketUser($TicketID = null, $user_id = null, $IsViewed = null)
    {
    	if (!$TicketID || !$user_id) return null;
    	
    	$query = $this->ST_DB->select('*')
	    	->from("st_alerts")
	    	->where('StaffID', $user_id)
	    	->where('TicketID', $TicketID);
    
	    if (!is_null($IsViewed)) 
	    	$query->where('IsViewed', (string) $IsViewed);
	    
    	$result = $query->get()->row();
    
//     	print $lastQuery = $this->ST_DB->last_query();
    
    	return $result;
    }
    
    public function getFullAlertInfo($AlertID = null, $asArray = false)
    {
    	$query = $this->ST_DB->select('al.*')
	    	->from("{$this->st_db_name}.st_alerts as al")
	    	->join("{$this->st_db_name}.st_servicetickets as st", 'al.TicketID = st.TicketID', 'LEFT')->select('st.AccountID, st.TicketType')
	    	->join("{$this->st_db_name}.st_notifications as nt", 'al.NotifyID = nt.ID', 'LEFT')->select('nt.Title')
	    	->join("{$this->st_db_name}.st_subjects as sb", 'st.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName, sb.KpiTimeHours')
	    	->join("{$this->st_db_name}.st_severities as sv", 'st.SeverityID = sv.SeverityID', 'LEFT')->select('sv.SeverityName')
	    	->join("{$this->st_db_name}.st_priorities as pr", 'st.PriorityID = pr.PriorityID', 'LEFT')->select('pr.PriorityName')
	    	->join("{$this->sc_db_name}.sm_customers as c", 'st.CompanyID = c.WebCustId', 'LEFT')->select('c.WebCustId, c.CustName')
	    	->join("{$this->sc_db_name}.scsm_users as u", 'al.StaffID = u.user_id', 'LEFT')->select("u.name")
// 	    	->join("{$this->sc_db_name}.sm_subscribers as sub", 'st.AccountID = sub.subs_id', 'LEFT')->select("sub.subs_id, CONCAT(sub.firstname,' ', sub.lastname) as subs_name", false)
	    	->where('al.AlertID', $AlertID);
    
    	$result = $query->get()->row();
    
//     	print $lastQuery = $this->ST_DB->last_query();
    
    	return $result;
    }
    
    public function updateAlert($id, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_alerts', $data_update, $where = array('AlertID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function updateUserAlert($TicketID = null, $user_id = null, $data_update = array())
    {
    	if (!$TicketID || !$user_id || !$data_update) return false;
    
    	$this->ST_DB->update('st_alerts', $data_update, $where = array('TicketID'=>$TicketID, 'StaffID'=>$user_id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function getDefaultApprovalID($SubjectID = null)
    {
    	$query = $this->ST_DB->select('*')
    	->from('st_approvalentities')
    	->where('SubjectID', $SubjectID)
    	->order_by('approve_order ASC')->limit(1);
    
    	$result = $query->get()->row();
    	 
    	return ($result)? $result: null;
    }
    
    public function GetApproveentities($SubjectID = null, $exclID = null)
    {
    	$query = $this->ST_DB->select('ap.*')
	    	->from("{$this->st_db_name}.st_approvalentities as ap")
	    	->join("{$this->st_db_name}.st_subjects as sb", 'ap.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	->join("{$this->sc_db_name}.smacl_roles as sfr", 'ap.role_id = sfr.role_id', 'LEFT')->select("sfr.role_name")
	    	->order_by('ap.SubjectID, ap.approve_order');
    	
    	if ($SubjectID) $query->where('ap.SubjectID', $SubjectID);
    	if ($exclID) $query->where('ap.ID !=', $exclID);
    	
    	$result = $query->get()->result();
//     	print $this->ST_DB->last_query();
    	return $result;
    }
    
    public function GetSimpleApproveentitiesBySubject($SubjectID = null)
    {
    	$query = $this->ST_DB->select('ap.*')
	    	->from("{$this->st_db_name}.st_approvalentities as ap")
	    	->where('ap.SubjectID', $SubjectID)
	    	->order_by('ap.SubjectID, ap.approve_order');
    	
    	$result = $query->get()->result_array();
//     	print $this->ST_DB->last_query();
    	return $result;
    }
    
    public function GetApproveentityByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_approvalentities')
	    	->where('ID', $ID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function GetApproveUserNotifyBySubject($SubjectID = null, $approve_status = null, $both = false)
    {
    	if (!$SubjectID) return false;
    	$query = $this->ST_DB->select('ap.*, ap.ID as ApproveID')
    	->from("{$this->st_db_name}.st_approvalentities as ap")
    	->join("{$this->st_db_name}.st_notify_recipients as nr", 'ap.SubjectID = nr.SubjectID AND ap.role_id = nr.role_id', 'LEFT')
    	->join("{$this->st_db_name}.st_notifications as n", 'nr.NotifyID = n.ID', 'LEFT')->select('n.*, n.ID as NotifyID')
    	
//     	->join("{$this->st_db_name}.st_approve_history as aph", 'ap.ID = aph.ApproveID', 'LEFT')->select('aph.TicketID, aph.approve_status, aph.ID as ApproveHistoryID')
    	->join("{$this->sc_db_name}.smacl_roles as ro", 'ap.role_id = ro.role_id', 'INNER')->select("ro.role_name")
    	->join("{$this->sc_db_name}.smacl_users_roles as ur", 'ro.role_id = ur.role_id', 'INNER')
    	->join("{$this->sc_db_name}.scsm_users as u", 'ur.user_id = u.user_id', 'INNER')->select("u.user_id, u.email, u.name")
    	
    	->where('ap.SubjectID', $SubjectID)
    	->order_by('ap.approve_order');
    	
//     	if ($both) {
//     		$query->where("(aph.approve_status IS NULL OR aph.approve_status ='0')", null, false);
//     	} else {
// 	    	if (is_null($approve_status)) $query->where('aph.approve_status IS NULL', null, false);
// 	    	else $query->where('aph.approve_status', (string)$approve_status);
//     	}	
    	$query = $query->get();
//     	print $this->ST_DB->last_query(); 
    	$result = $query->result_array();
    	return $result;
    }
    
    /**
     * Insert new Notify
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addApproveentity($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_approvalentities', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update Notify by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateApproveentity($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    	$this->ST_DB->update('st_approvalentities', $data_update, $where = array('ID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function saveApproveentity($SubjectID, $role_id, $approve_order)
    {
    	if (!$SubjectID || !$role_id) return array();
    	$searchQuery = "INSERT INTO st_approvalentities (SubjectID, role_id, approve_order) VALUES ({$SubjectID}, {$role_id}, {$approve_order})
  						ON DUPLICATE KEY UPDATE role_id = {$role_id};";
    	$query = $this->ST_DB->query($searchQuery);
    	
    	$afftectedRows = $this->ST_DB->affected_rows();
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete Notify by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteApproveentity($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_approvalentities', array('ID' => $ID), 1);
    }
    
    public function deleteSubjectApproveentity($SubjectID = null, $approve_order = null)
    {
    	if (!$SubjectID) return false;
    	$where =  array('SubjectID' => $SubjectID);
    	
    	if (!is_null($approve_order)) {
    		$where['approve_order >'] = $approve_order;
    	}
    	return $this->ST_DB->delete('st_approvalentities', $where);
    }
    
    
    /*----------- Services Groups -------------------*/
    
    public function GetGroupByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
    	->from('st_groups')
    	->where('GroupID', $ID)
    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function GetGroups($keyArray = false)
    {
    	$this->ST_DB->select('*');
    	$this->ST_DB->from('st_groups');
    
    	$query = $this->ST_DB->get();
    	if ($query->num_rows () > 0) {
    		$result = $query->result ();
    		if ($keyArray == true) {
    			$outAssociated = array ();
    			foreach ( $result as $item ) {
    				$outAssociated [$item->GroupID] = trim($item->GroupName);
    			}
    			$result = $outAssociated;
    		}
    		return $result;
    	} else {
    		return FALSE;
    	}
    
    }// end GetSeverities()
    
    public function GetGroupsByType($TypeID = null, $keyArray = false)
    {
    	if (!$TypeID) return null;
    	
    	$query = $this->ST_DB->distinct()
    		->from('st_groups as g')->select('g.*')
    		->join("st_subjects as sb", 'g.GroupID = sb.GroupID', 'INNER')
    		->join("st_types as t", 'sb.TypeID = t.TypeID', 'INNER')
    		->where('t.TypeID', $TypeID);
    	
    	$query = $this->ST_DB->get();
    	
    	$result = $query->result_array();
    	
    	if ($keyArray == true && $result) {
    		$result = array_column($result, 'GroupName', 'GroupID');
    	}
    	return $result;
    }
    
    /**
     * Insert new statuses
     * @param unknown $data_insert
     * @return int last insert id
     */
    public function addGroup($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_groups', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    /**
     * Update by ID
     * @param string $id
     * @param array $data_update
     * @return multitype:|int affected rows
     */
    public function updateGroup($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    
    	$this->ST_DB->update('st_groups', $data_update, $where = array('GroupID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    /**
     * Delete status by ID
     * @param string $ID
     * @return boolean
     */
    public function deleteGroup($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_groups', array('GroupID' => $ID), 1);
    }

    /**
     * function keep feedback()
     *
     * insert form data
     * @param $form_data - array
     * @return Bool - TRUE or FALSE
     */
    
    function SaveFeedback($form_data = array())
    {
    	if (empty($form_data)) return false;
    	$this->ST_DB->insert('st_feedback', $form_data);
    	return $this->ST_DB->insert_id();
    
    }// end SaveForm()
    
    
    public function getFeedbackByID($FeedbackID = null, $limit = null)
    {
    	if (!$FeedbackID) return null;
    	 
    	$query = $this->ST_DB->select('fb.*')
	    	->from("{$this->st_db_name}.st_feedback as fb")
	    	->where('fb.FeedbackID', $FeedbackID);
    	
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    	
    	$result = $query->row_array();
    	return $result;
    }
    
    public function getFeedbackByTicketID($TicketID = null, $limit = null)
    {
    	if (!$TicketID) return null;
    	 
    	$st_db_name = $this->ST_DB->database;
    	$sc_db_name = $this->db->database;
    
    	$query = $this->ST_DB->select('fb.*')
	    	->from("{$this->st_db_name}.st_feedback as fb")
	    	->join("{$this->sc_db_name}.scsm_users as sf", 'fb.CreatedBy = sf.user_id', 'LEFT')->select("sf.name as CreatedByName", false)
	    	->join("{$this->sc_db_name}.scsm_users as sfb", 'fb.BackTo = sfb.user_id', 'LEFT')->select("sfb.name as BackToName", false)
	    	->where('fb.TicketID', $TicketID)
	    	->order_by('fb.CreatedAt DESC');
    	
    	if ($limit)  $query->limit($limit);
    	
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    	
    	$result = $query->result();
    	return $result;
    }
    
    public function getNotifyRoles($NotifyID = null, $keyArray = false)
    {
    	if (!$NotifyID) return array();
    
    	$query = $this->db->select('*')
	    	->from("{$this->st_db_name}.st_notify_recipients nr")
	    	->join("{$this->sc_db_name}.smacl_roles as r", "nr.role_id = r.role_id", 'INNER')->select('r.role_name')
	    	->where('nr.NotifyID', $NotifyID);
    
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
     * Delete notify roles
     * @param string $NotifyID
     * @param string $role_id
     * @return int affectedRows
     */
    public function deleteNotifyRoles($NotifyID = null, $SubjectID = null, $role_id = null)
    {
    	if (!$NotifyID || !$SubjectID) return false;
   
    	$where = array('NotifyID' => (int)$NotifyID, 'SubjectID'=>(int)$SubjectID);
    
    	if ($role_id) $where['role_id'] = (int)$role_id;
    
    	$this->db->delete("{$this->st_db_name}.st_notify_recipients", $where);
//     	print $lastQuery = $this->db->last_query();
    	return $this->db->affected_rows();
    }
    
    public function getNotifyRecipientID($NotifyID = null, $SubjectID = null, $role_id = null)
    {
    	if (!$NotifyID || !$SubjectID) return false;
    	 
    	$query = $this->ST_DB->select('*')
	    	->from("{$this->st_db_name}.st_notify_recipients")
	    	->where('NotifyID', $NotifyID)
	    	->where('SubjectID', $SubjectID);
    	
    	if ($role_id) $query->where("role_id IN ({$role_id})", null, false);
    	
    	$result = $query->get()->row_array();
    	
    	//print $lastQuery = $this->db->last_query();
    	return $result;
    }
    
    /**
     * Add new role to notify message
     * @param array $form_data
     * @return int last insert id
     */
    public function insertNotifyRoles($form_data = array())
    {
    	if (empty($form_data)) return false;
    	$this->db->insert("{$this->st_db_name}.st_notify_recipients", $form_data);
    
//     	print $lastQuery = $this->db->last_query();
    	return $this->db->insert_id();
    }
    
    /* Executive entities */
    public function getDefaultExecutantID($SubjectID = null)
    {
    	$query = $this->ST_DB->select('*')
    	->from('st_executiveentities')
    	->where('SubjectID', $SubjectID)
    	->order_by('executatnt_order ASC')->limit(1);
    
    	$result = $query->get()->row();
    
    	return ($result)? $result: null;
    }
    
    public function GetExecutiveentities($SubjectID = null, $exclID = null)
    {
    
    	$query = $this->ST_DB->select('ee.*')
	    	->from("{$this->st_db_name}.st_executiveentities as ee")
	    	->join("{$this->st_db_name}.st_subjects as sb", 'ee.SubjectID = sb.SubjectID', 'LEFT')->select('sb.SubjectName')
	    	->join("{$this->sc_db_name}.smacl_roles as sfr", 'ee.role_id = sfr.role_id', 'LEFT')->select("sfr.role_name")
	    	->order_by('ee.SubjectID, ee.executant_order');
    	 
    	if ($SubjectID) $query->where('ee.SubjectID', $SubjectID);
    	if ($exclID) $query->where('ee.ID !=', $exclID);
    	 
    	$result = $query->get()->result();
    	//     	print $this->ST_DB->last_query();
    	return $result;
    }
    
    public function GetExecutiveentityByID($ID = null, $asArray = false)
    {
    	if (!$ID) return null;
    	$query = $this->ST_DB->select('*')
	    	->from('st_executiveentities')
	    	->where('ID', $ID)
	    	->get();
    
    	$result = ($asArray) ? $query->row_array() : $query->row();
    	return  $result;
    }
    
    public function addExecutiveentity($data_insert = array())
    {
    	if (!$data_insert) return false;
    
    	$this->ST_DB->insert('st_executiveentities', $data_insert);
    	$insert_id = $this->ST_DB->insert_id();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $insert_id;
    }
    
    public function updateExecutiveentity($id = null, $data_update = array())
    {
    	if (!$id || !$data_update) return array();
    	$this->ST_DB->update('st_executiveentities', $data_update, $where = array('ID'=>$id), $limit = 1);
    	$afftectedRows = $this->ST_DB->affected_rows();
    	//print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }

    public function deleteExecutiveentity($ID = null)
    {
    	if (!$ID) return false;
    	return $this->ST_DB->delete('st_executiveentities', array('ID' => $ID), 1);
    }
    
    public function deleteSubjectExecutiveentity($SubjectID = null)
    {
    	if (!$SubjectID) return false;
    	return $this->ST_DB->delete('st_executiveentities', array('SubjectID' => $SubjectID));
    }
    
    public function GetTicketExecutiveentities($TicketID = null)
    {
    
    	$query = $this->ST_DB->select('ee.*')
	    	->from("{$this->st_db_name}.st_executiveentities as ee")
	    	->join("{$this->st_db_name}.st_subjects as sb", 'ee.SubjectID = sb.SubjectID', 'INNER')->select('sb.SubjectName')
	    	->join("{$this->st_db_name}.st_servicetickets as st", 'sb.SubjectID = st.SubjectID', 'INNER')->select('st.TicketID')
	    	->join("{$this->sc_db_name}.smacl_roles as sfr", 'ee.role_id = sfr.role_id', 'INNER')->select("sfr.role_name")
	    	->order_by('ee.executant_order');
    
    	if ($TicketID) $query->where('st.TicketID', $TicketID);
    
    	$result = $query->get()->result_array();
// 		print $this->ST_DB->last_query();
    	return $result;
    }
    
    public function isAllowExecuteTicket($TicketID = null, $roles = array())
    {
    
    	$query = $this->ST_DB->select('ee.*')
	    	->from("{$this->st_db_name}.st_executiveentities as ee")
	    	->join("{$this->st_db_name}.st_subjects as sb", 'ee.SubjectID = sb.SubjectID', 'INNER')->select('sb.SubjectName')
	    	->join("{$this->st_db_name}.st_servicetickets as st", 'sb.SubjectID = st.SubjectID', 'INNER')->select('st.TicketID')
	    	->join("{$this->sc_db_name}.smacl_roles as sfr", 'ee.role_id = sfr.role_id', 'INNER')->select("sfr.role_name")
	    	->order_by('ee.executant_order');

    	if ($TicketID) $query->where('st.TicketID', $TicketID);
    	if ($roles) $query->where_in('ee.role_id', $roles);
    	
    	$query = $query->get();
//     	print $this->ST_DB->last_query();
    	$result = $query->result_array();
		
    	return $result;
    }
    
    /**
     * 
     * @param string $TicketID
     * @param string $user_id
     * @param string $approve_status  0-waiting to approve, 1-approved, 2-rejected
     * @return unknown
     */
    public function isAllowApproveRejectTicket($TicketID = null, $user_id = null, $approve_status = null, $flag = null)
    {
    
    	$query = $this->ST_DB->select('ah.*')
	    	->from("{$this->st_db_name}.st_approve_history as ah")
	    	->join("{$this->st_db_name}.st_approvalentities as ae", 'ah.ApproveID = ae.ID', 'INNER')->select('ae.approve_order')
	    	->join("{$this->st_db_name}.st_servicetickets as st", 'ah.TicketID = st.TicketID', 'INNER')->select('st.SubjectID');
// 	    	->join("{$this->sc_db_name}.smacl_users_roles as ur", 'ah.user_id = ur.user_id', 'INNER')->select("ur.role_id");

    	if ($TicketID) $query->where('ah.TicketID', $TicketID);
    	if ($user_id) $query->where('ah.user_id', $user_id);
    	if (!is_null($approve_status)) $query->where('ah.approve_status', (string)$approve_status);
    	if (!is_null($flag)) $query->where('ah.flag', (string)$flag);
    	
    	$query = $query->get();
//     	print $this->ST_DB->last_query();
    	$result = $query->row_array();
		
    	return $result;
    }
    
    /**
     * 
     * @param string $TicketID
     * @param string $user_id
     * @param string $approve_status  0-waiting to approve, 1-approved, 2-rejected
     * @return unknown
     */
    public function isAllowCloseTicket($TicketID = null, $role_ids = array())
    {
    	if (!$TicketID || !$role_ids) return false;
    	
    	$TicketExecutiveentities = $this->GetTicketExecutiveentities($TicketID);
    	
    	$query = $this->ST_DB->select('s.*')
	    	->from("{$this->st_db_name}.st_subjects as s")
	    	->join("{$this->st_db_name}.st_servicetickets as st", 's.SubjectID = st.SubjectID', 'INNER')
	    	->where('st.TicketID', $TicketID)
	    	->where('st.StatusID !=', self::$DefaultCloseStatus)
	    	->where_in('s.close_role_id', $role_ids);
    	
    	/*
    	 * check if this ticket subject are executive entities, then check if in executive history this ticket is done
    	 */
		if ($TicketExecutiveentities) {
    		$query->join("{$this->st_db_name}.st_executive_history AS eh", "st.TicketID = eh.TicketID AND eh.execution_status = '1'", 'INNER');
    	}
    	
    	$query = $query->get();
//     	print $this->ST_DB->last_query();
    	$result = $query->row_array();
		
    	return $result;
    }
    
    /**
     * Keep ticket reject reason 
     *
     * insert form data
     * @param $form_data - array
     * @return Bool - TRUE or FALSE
     */
    function SaveReject($form_data = array())
    {
    	if (empty($form_data)) return false;
    	$this->ST_DB->insert('st_reject_history', $form_data);
    	return $this->ST_DB->insert_id();
    
    }
    
    
    public function getRejectByID($ReasonID = null, $limit = null)
    {
    	if (!$ReasonID) return null;
    
    	$query = $this->ST_DB->select('rh.*')
    	->from("{$this->st_db_name}.st_reject_history as rh")
    	->where('rh.ReasonID', $ReasonID);
    	 
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    	 
    	$result = $query->row_array();
    	return $result;
    }
    
    public function getRejectByTicketID($TicketID = null, $limit = null)
    {
    	if (!$TicketID) return null;
    
    	$query = $this->ST_DB->select('rh.*')
    	->from("{$this->st_db_name}.st_reject_history as rh")
    	->join("{$this->st_db_name}.st_servicetickets as st", 'rh.TicketID = st.TicketID', 'INNER')
    	->join("{$this->sc_db_name}.scsm_users as u", 'rh.CreatedBy=u.user_id', 'LEFT')->select("u.*")
    	->where('rh.TicketID', $TicketID)
    	->order_by('rh.CreatedAt DESC');
    	 
    	if ($limit)  $query->limit($limit);
    	 
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    	 
    	$result = $query->result_array();
    	return $result;
    }
    
    public function getNotifyByApproveHistoryID($ApproveID = '')
    {
    	if (!$ApproveID) return false;
    
    	$searchQuery = "SELECT
						`ah`.*,
						`n`.*,
						u.name, ro.role_name
					FROM 	`servicetickets`.`st_approve_history` AS `ah`
					INNER JOIN `servicetickets`.`st_notifications` AS `n` ON `ah`.`NotifyID` = `n`.`ID`
					INNER JOIN `selfcare_smart`.`scsm_users` AS `u` ON `ah`.`user_id` = `u`.`user_id`
					INNER JOIN `selfcare_smart`.`smacl_users_roles` AS `ur` ON `u`.`user_id` = `ur`.`user_id`
					INNER JOIN `selfcare_smart`.`smacl_roles` AS `ro` ON `ur`.`role_id` = `ro`.`role_id`
					WHERE `ah`.`ID` = {$ApproveID}
					GROUP BY u.user_id";
    	$query = $this->db->query($searchQuery);
//     			print $lastQuery = $this->db->last_query();die;
    	return $query->row_array();
    }
    
    public function getSubjecQuestions($SubjectID = null)
    {
    	if (!$SubjectID) return null;
    
    	$query = $this->ST_DB->from("st_subject_question as sq")->select('sq.*')
    			->where('sq.SubjectID', $SubjectID)
    			->order_by('sq.QOrder ASC');
    
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    
    	$result = $query->result_array();
    	return $result;
    }
    
    public function saveSubjectQuestion($SubjectID, $QTitle, $QOrder)
    {
    	if (!$SubjectID || !$QTitle) return array();
    	$searchQuery = "INSERT INTO st_subject_question (SubjectID, QTitle, QOrder) VALUES (?, ?, ?)
    						ON DUPLICATE KEY UPDATE QTitle = ?;";
    	$query = $this->ST_DB->query($searchQuery, array($SubjectID, $QTitle, $QOrder, $QTitle));
    	 
    	$afftectedRows = $this->ST_DB->affected_rows();
// 		print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function deleteSubjectQuestion($SubjectID = null, $QOrder = null)
    {
    	if (!$SubjectID) return false;
    	
    	$this->ST_DB->where('SubjectID', $SubjectID);
    	
    	if (!is_null($QOrder)) {
    		$this->ST_DB->where_in('QOrder', $QOrder);
    	}
    	$affectedRows = $this->ST_DB->delete('st_subject_question');
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $affectedRows;
    }
    
    public function getTicketAnswers($TicketID = null)
    {
    	if (!$TicketID) return null;
    
    	$query = $this->ST_DB->from("st_subject_answers as ta")->select('ta.*')
	    	->join("st_subject_question as sq", 'ta.QuestionID = sq.QuestionID', 'INNER')->select('sq.QTitle, sq.QOrder')
	    	->where('ta.TicketID', $TicketID)
    		->order_by('sq.QOrder ASC');
    
    	$query = $query->get();
    	//print $this->ST_DB->last_query();
    
    	$result = $query->result_array();
    	return $result;
    }

    public function saveTicketAnswer($QuestionID, $TicketID, $Answer)
    {
    	if (!$QuestionID || !$TicketID || !$Answer) return array();
    	$searchQuery = "INSERT INTO st_subject_answers (QuestionID, TicketID, Answer) VALUES (?, ?, ?)
    						ON DUPLICATE KEY UPDATE Answer = ?;";
    	$query = $this->ST_DB->query($searchQuery, array($QuestionID, $TicketID, $Answer, $Answer));
    
    	$afftectedRows = $this->ST_DB->affected_rows();
    	// 		print $lastQuery = $this->ST_DB->last_query();
    	return $afftectedRows;
    }
    
    public function getEmailByServiceNumber($serviceNumber = null)
    {
    	if (!$serviceNumber) return null;
    	$query = $this->ST_DB->select('st.*')
    			->from("st_servicetickets as st")
    			->where('st.AccountID', $serviceNumber)
    			->where('st.InitiatorEmail !=', '');
    
    	$query = $query->get();
//     	print $lastQuery = $this->ST_DB->last_query();
    	return $query->row('InitiatorEmail');;
    }
    
    public function getTicketChangeLog($TicketID = null)
    {
    	if (!$TicketID) return null;
    	 
    	$query = $this->ST_DB->select("cl.*, 
    			(CASE cl.field_name 
	    			WHEN 'StatusID' THEN 'Status' 
	    			WHEN 'SeverityID' THEN 'Severity' 
	    			WHEN 'ClosedByID' THEN 'Closed By'
	    			WHEN 'ProgressComment' THEN 'Progress Comment'
	    			ELSE cl.field_name END) as field_name,
    			(CASE cl.field_name
					WHEN 'StatusID' THEN (SELECT StatusName FROM st_statuses WHERE StatusID = cl.old_value)
					WHEN 'SeverityID' THEN (SELECT SeverityName FROM st_severities WHERE SeverityID = cl.old_value)
					WHEN 'ClosedByID' THEN (SELECT selfcare_smart.scsm_users.name FROM selfcare_smart.scsm_users WHERE selfcare_smart.scsm_users.user_id = cl.old_value)
					ELSE cl.old_value END ) AS old_value,
    			(CASE cl.field_name
					WHEN 'StatusID' THEN (SELECT StatusName FROM st_statuses WHERE StatusID = cl.new_value)
					WHEN 'SeverityID' THEN (SELECT SeverityName FROM st_severities WHERE SeverityID = cl.new_value)
					WHEN 'ClosedByID' THEN (SELECT selfcare_smart.scsm_users.name FROM selfcare_smart.scsm_users WHERE selfcare_smart.scsm_users.user_id = cl.new_value)
					ELSE cl.new_value END ) AS new_value
    			", false)
	    	->from("{$this->st_db_name}.st_changes_log as cl")
	    	->join("{$this->st_db_name}.st_servicetickets as st", 'cl.TicketID = st.TicketID', 'INNER')
	    	
	    	->join("{$this->sc_db_name}.scsm_users as us", 'cl.who_change = us.user_id', 'LEFT')->select("IFNULL(us.name, 'SYSTEM') as who_change", false)
	    	
	    	->where('st.TicketID', $TicketID)
	    	->order_by('cl.modified_at DESC');
    	
    	$query = $query->get();
    
//     	print $this->ST_DB->last_query();
    	$result = $query->result_array();
    	return $result;
    }
    
    /**
     * Keep ticket reject reason
     *
     * insert form data
     * @param $form_data - array
     * @return Bool - TRUE or FALSE
     */
    function SaveMassSelectedSubs($form_data = array())
    {
    	if (empty($form_data)) return false;
    	$this->ST_DB->insert_batch('st_mass_accounts', $form_data);
    	return $this->ST_DB->insert_id();
    
    }
    
    public function countMassSelectedSubs($TicketID = null)
    {
    	if (!$TicketID) return 0;
    
    	return $this->ST_DB->from('st_mass_accounts')->where('TicketID', $TicketID)->count_all_results();
    }
    
    public function MassSelectedSubs($TicketID = null)
    {
    	if (!$TicketID) return array();
    
    	$query = $this->ST_DB->select('*')
    		->from('st_mass_accounts')
    		->where('TicketID', $TicketID);
    	
    	$query = $query->get();
    	
    	//     	print $this->ST_DB->last_query();
    	$result = $query->result_array();
    	return $result;
    }
    
}// end Servicetickets_model class