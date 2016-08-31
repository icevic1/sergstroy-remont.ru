<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Kyc extends Site_Controller 
{
	//public $ST_DB;
	public $staff = null;
	private $visitor = null;
	private $debugEmail	 = array('orletchi.victor@gmail.com', 'hasan.mahfuz@cresittel.com', 'ema.vergles@smart.com.kh', 'chhim.sundaly@smart.com.kh', 'heng.sovichet@smart.com.kh', 'sim.sisavuthary@smart.com.kh', 'yuot.sokha@smart.com.kh', 'meng.saphda@smart.com.kh', 'treav.raksmey@smart.com.kh', 'pen.sokha@smart.com.kh');
    
    function __construct()
	{
 		parent::__construct();
		$this->load->helper('cookie');
        $this->load->helper('text');
        $this->load->library('form_validation');		
        $this->load->library('Acl');
		$this->load->helper('form');
		$this->load->helper('pagination');
		$this->load->helper('url');
		$this->load->helper('ngbssquery');
		$this->load->model('Servicetickets_model', 'st');     
		$this->load->model('Selfcare_mod');     
		$this->load->model('Staff_mod');     
		$this->load->model('Subscriber_mod', 'subscriber');
// 		print strtotime('NOW');die;
// 		if(!$this->session->userdata('customer')){
// 			redirect(site_url('account/login'));
// 		}
		
// 		$user_id = $this->session->userdata('staff')->user_id;
		$this->staff = $this->session->userdata('staff');
		$this->visitor = $this->session->userdata('visitor');
// 		var_dump($this->staff);die;
		if (false == $this->acl->can_read(null, 3 /*STS main resources*/)) {
			show_error('You not have permission to view Service Ticket Sistem!', $status_code= 503, 'Permission denied');
		}
		
	}	
	
	function index()
	{			
	    /******* prepare form filter ************/
		
		$current_subscriber = $this->session->userdata('current_subscriber');
	    $data['filteredTickets'] = array();
	    $filter = array();
// 	    var_dump($this->staff['companies']);die;
	    /**
	     * exclude closed status by default
	     */
	    $filter['StatusID'] = -3;
	    
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    	$filter['TypeID'] 		= ($this->input->post('type'))	?	$this->input->post('type')	:	null;
	    	$filter['SubjectID']	= ($this->input->post('subject'))?  $this->input->post('subject'): 	null;
	    	$filter['PriorityID'] 	= ($this->input->post('priority'))?	$this->input->post('priority'):	null;
	    	$filter['CompanyID'] 	= ($this->input->post('company'))? 	$this->input->post('company'): 	null;
	    	$filter['SeverityID'] 	= ($this->input->post('severity'))? $this->input->post('severity'): null;
	    	$filter['StatusID'] 	= ($this->input->post('status'))? 	$this->input->post('status'): 	-3;
	    	$filter['simid'] 		= ($this->input->post('simid'))? 	$this->input->post('simid'): null;
	    	$filter['phone_number'] = ($this->input->post('phone_number'))? $this->input->post('phone_number'): null;
	    	$filter['user_name'] = ($this->input->post('subscriber_name'))? $this->input->post('subscriber_name'): null;
	    	$filter['date_from'] = ($this->input->post('date_from'))? $this->input->post('date_from'): null;
	    	$filter['date_to'] = ($this->input->post('date_to'))? $this->input->post('date_to'): null;
// 	    	var_dump($this->input->post(), $filter);
	    }
	    
	    $data['filter'] = $filter;
	    $data['filteredTickets'] = $this->st->search($filter);
	    
	    $data['SubjectsDT'] 	= array_replace(array(''=>'--All--'), $this->st->GetSubjects(null, true));
	    $data['TypesDT']		= array_replace(array(''=>'--All--'), $this->st->GetAllowedTypes());
	    $data['StatusesDT'] 	= array_replace(array(''=>'--All excl. Closed--'), $this->st->GetStatuses(true));
	    $data['SeveritiesDT'] 	= array_replace(array(''=>'--All--'), $this->st->GetSeverities(true));
	    $data['PrioritiesDT']	= array_replace(array(''=>'--All--'), $this->st->GetPriorities(true));
	    
	    if($this->acl->can_read(null, 22 /*22 Company name*/) && isset($this->staff['companies'])) {
	    	$data['companies'] 	= array_replace(array(''=>'--All--'), $this->staff['companies']);
	    }
// 	    var_dump( $data['filteredTickets']);die;
// 	    $this->load->helper('addstbutton');
		$data['navAddST'] = true;
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Know Your Customer', 'kyc/index/');
		
		$data['PAGE_TITLE'] = 'Know Your Customer';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='kyc/index';
		$this->load->view('layout/layout_st', $data);
        
	}// end index()
    
	//call to fill the second dropdown with the cities  
    public function buildDropGroups()  
    {  
      $TypeID = $this->input->post('typeid',TRUE); 
      $groups = $this->st->GetGroupsByType($TypeID, true);
      if (!$groups) $groups = array();
      echo form_dropdown('GroupID', array_replace(array('' => '-- Please Select --'), $groups), $default = '', 'id="GroupID" size="5" class="form-control"');
    }
    
	//call to fill the second dropdown with the cities  
    public function buildDropSubjects()  
    {  
      $Type = $this->input->post('typeid',TRUE); 
      $GroupID = $this->input->post('GroupID',TRUE)? $this->input->post('GroupID',TRUE): null; 
                    
      $SubjectsDT = $this->st->GetSubjects($Type, false, $GroupID); 
      $output = "<option value=\"\" selected=\"selected\">-- Please Select --</option>";  
      
      foreach ($SubjectsDT as $row) {  
         $output .= "<option value='".$row->SubjectID."'>".$row->SubjectName."</option>";  
      }
        
      echo $output;  
    }// end buildDropSubjects()   
    
    function success()
	{
	     echo 'this form has been successfully submitted with all validation being passed.';
            
	}// end success()
    
	function approveST($INTicketID = null)
	{
		$this->load->helper('email');
		
		$Ticket = $this->st->getFullInfoByTicketID($INTicketID);
		
		$ticket_link = site_url("kyc/detailsST/{$Ticket->TicketID}");
		$approve_link = site_url("kyc/approveST/{$Ticket->TicketID}");
		$reject_link = site_url("kyc/detailsST/{$Ticket->TicketID}?reject=reason");
		
// 		$approveNotifications = $this->st->GetApproveUserNotifyBySubject($Ticket->SubjectID, null, true);
		$AllowApproveTicketUser = $this->st->isAllowApproveRejectTicket($Ticket->TicketID, $this->staff['user_id'], '0', $flag = '1');
		
		/*
		 * When ticket was created it's push first notification to approve, then approve status must be equal with 0
		 * check if current auth. user is what user for 
		 */
		if ($AllowApproveTicketUser) {
			/*
			 * keep approve status
			 */
			$this->st->updateApproveHystory(array('ID'=>$AllowApproveTicketUser['ID'], 'approve_status'=>'1', 'flag'=>'0', 'updated_at'=>date('Y-m-d H:i:s')));

			/*
			 * Get current approv hystory info for extract current role to approve
			 */
			$NotifyByApproveHistoryID = $this->st->getNotifyByApproveHistoryID($AllowApproveTicketUser['ID']);
			
			/*
			 * Calculate how many hours are left
			 */
			$SubjectDT = $this->st->getSubjectByID($Ticket->SubjectID);
			$expirationTime = strtotime("+{$SubjectDT->KpiTimeHours} hours", strtotime($Ticket->CreationDateTime));
			$diff = $expirationTime - time();
			$hoursRemained = intval(floor($diff / 3600));
			
			$form_data = array(
					'ChangedByUserID' => $this->staff['user_id'],
					'StatusID' => (isset(Servicetickets_model::$ApproveStatuses[$AllowApproveTicketUser['approve_order']+1])? Servicetickets_model::$ApproveStatuses[$AllowApproveTicketUser['approve_order']+1]: current(Servicetickets_model::$ApproveStatuses)),
// 					'ProgressComment' => (($this->staff['user_id'] == $NotifyByApproveHistoryID['user_id'])? $NotifyByApproveHistoryID['role_name'] : implode(', ', $this->staff['roles'])) . ' Approved!' ,
					'LeadTimeHours' => $hoursRemained,
					'LastEditDate' => 'NOW()',
			);
			if ($Ticket->forCustStatusID != 12) $form_data['forCustStatusID'] = 12; //In review
			
			$this->st->updateST($Ticket->TicketID, $form_data);
			
			/*
			 * Get account information
			 */
			$current_customer = $this->Customer_model->getWebCustById($Ticket->CompanyID);
			$current_subscriber = null;
			
			$data2view = array('customer'=>$current_customer);
			
			if ($Ticket->TicketType == Servicetickets_model::$TicketType_Subscriber){
				$current_subscriber = $this->subscriber->loadSubscriber($Ticket->AccountID);
				$data2view['subscriber'] = $current_subscriber;
			}
			
			$account_profile = $this->load->view('kyc/subsinfo_email', $data2view, true); //{account_profile}
			
			
			/*
			 * Check if need an other approve and keep it + notify users
			 */
			$nextAllowApproveTicketUser = $this->st->isAllowApproveRejectTicket($Ticket->TicketID, null, '0', $flag = '0');
			
			if ($nextAllowApproveTicketUser) {
				
				/*switch flag to next user to approve*/
				$this->st->updateApproveHystory(array('ID'=>$nextAllowApproveTicketUser['ID'], 'flag'=>'1'));
				
				/* get user and notofication information */
				$NotifyByApproveHistoryID = $this->st->getNotifyByApproveHistoryID($nextAllowApproveTicketUser['ID']);
				if ($NotifyByApproveHistoryID) {
					
					$Ticket = $this->st->getFullInfoByTicketID($INTicketID);
					
					/* Prepare Ticket History */
					$data['filteredTickets'] = $this->st->getTicketChangeLog($Ticket->TicketID);
					$progress_history = $this->load->view('kyc/history_email', $data, true);
					
					$approve_user_name = $NotifyByApproveHistoryID['name'] . ' ('.$NotifyByApproveHistoryID['role_name'].')';
					$created_user_name = $Ticket->InitiatorType == 1? $Ticket->CreatedByID : $Ticket->whoCreateName;
					
					/*
					 * Make replacement array
					 */
					$replacement = array(
							'{account_profile}'=>$account_profile, 
							'{approve_user_name}'=>$approve_user_name, 
							'{ticket_link}'=>$ticket_link, 
							'{approve_link}'=>$approve_link, 
							'{reject_link}'=>$reject_link, 
							'{TicketID}'=>$Ticket->TicketID, 
							'{SubjectName}'=>$Ticket->SubjectName, 
							'{created_user_name}'=>$created_user_name, 
							'{customer_name}'=>$Ticket->CustName, 
							'{phone_number}'=>$Ticket->AccountID, 
							'{ticket_created_time}'=>date('H:i', strtotime($Ticket->CreationDateTime)), 
							'{created_date}'=>date('d-M-Y', strtotime($Ticket->CreationDateTime)), 
							'{progress_comment}'=>$progress_history
					);
					
					
					switch ($Ticket->TicketType) {
						case 0: 
							$replacement['{phone_number}'] = '0'.$Ticket->AccountID; 
							break;
						case 1: // mass full customer
							$groupInfo = Group_Member($Ticket->GroupId);
							$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')'; 
							break;
						case 2: //mass selected
							$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($Ticket->TicketID) . ')'; 
							break;
					}
					
					$subject = str_replace(array_keys($replacement), array_values($replacement), $NotifyByApproveHistoryID['Title']);
					$body = str_replace(array_keys($replacement), array_values($replacement), $NotifyByApproveHistoryID['MailText']);
					
					$this->sendNotifiyMail($NotifyByApproveHistoryID['email'], $subject, $body);
				}
			
				$this->st->createAlert(array(
						'NotifyID' => $nextAllowApproveTicketUser['NotifyID'],
						'TicketID' => $Ticket->TicketID,
						'StaffID' => $nextAllowApproveTicketUser['user_id'],
						'EmailSent'	=> '1',
						'SentAt' => date('Y-m-d H:i:s')
				));

			/*
			 * no need more approve
			 * notify responsible to execution
			 * go to execution
			 */
			} else {

				$subjNotify = $this->st->getSubjectNotifications($Ticket->SubjectID, '3', $limit = 1);
				$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($subjNotify['role_ids'], $Ticket->CompanyID);
				$recipients = array_column($notifyUsers, 'email', 'user_id');
				
				if ($recipients) {
					/* Change status of serviceticket to ready to execution*/
					$this->st->updateST($Ticket->TicketID, array( 
										'StatusID' => Servicetickets_model::$DefaultReadyExecutionStatus, 
										'LastEditDate' => 'NOW()',
										'forCustStatusID' => Servicetickets_model::$DefaultProcessStatus
										)
									);
					
					/* keep execute history and create system alert for */
					foreach ($recipients as $user_id => $notifyUser) {
						// keep alert
						$this->st->createAlert(array (
								'NotifyID' => $subjNotify['ID'],
								'TicketID' => $Ticket->TicketID,
								'StaffID' => $user_id,
								'EmailSent'	=> '1',
								'SentAt' => date('Y-m-d H:i:s')
						));
					
						// keep all subject executant in log
						$this->st->createExecuteHystory(array(
								'NotifyID' => $subjNotify['ID'],
								'TicketID' => $Ticket->TicketID,
								'user_id' => $user_id
						));
					}
					
					$Ticket = $this->st->getFullInfoByTicketID($Ticket->TicketID);
					
					/* Prepare Ticket History */
					$data['filteredTickets'] = $this->st->getTicketChangeLog($Ticket->TicketID);
					$progress_history = $this->load->view('kyc/history_email', $data, true);
					
					$created_user_name = $Ticket->InitiatorType == 1? $Ticket->CreatedByID : $Ticket->whoCreateName;

					/*
					 * Make replacement array
					 */
					$replacement = array(
							'{account_profile}'=>$account_profile,
							'{approve_user_name}'=>$approve_user_name,
							'{ticket_link}'=>$ticket_link,
							'{approve_link}'=>$approve_link,
							'{reject_link}'=>$reject_link,
							'{TicketID}'=>$Ticket->TicketID,
							'{SubjectName}'=>$Ticket->SubjectName,
							'{created_user_name}'=>$created_user_name,
							'{customer_name}'=>$Ticket->CustName,
							'{phone_number}'=>$Ticket->AccountID,
							'{ticket_created_time}'=>date('H:i', strtotime($Ticket->CreationDateTime)),
							'{created_date}'=>date('d-M-Y', strtotime($Ticket->CreationDateTime)),
							'{progress_comment}'=>$progress_history
					);
						
					switch ($Ticket->TicketType) {
						case 0:
							$replacement['{phone_number}'] = '0'.$Ticket->AccountID;
							break;
						case 1: // mass full customer
							$groupInfo = Group_Member($Ticket->GroupId);
							$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
							break;
						case 2: //mass selected
							$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($Ticket->TicketID) . ')';
							break;
					}
						
					$subject = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['Title']);
					$body = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['MailText']);
					
					$this->sendNotifiyMail($recipients, $subject, $body);
				}
				
				
			} // end finish approve do to execution
			redirect("kyc/detailsST/{$Ticket->TicketID}");
		} else {
			show_error("You haven't permission to Approve Service Ticket #{$Ticket->TicketID}", $status_code= 503, 'Permission denied');
		}
	}
	
	function editST($INTicketID = null)
	{
		$this->load->helper('email');
		$this->load->helper('text');
		$data['STicket'] = $Ticket = $this->st->getFullInfoByTicketID($INTicketID);
		
		if (empty($Ticket)) {
			show_error('Service Ticket with ID #'.$INTicketID.'# was not found!', $status_code= 500 );
		}
		
		$data['staff'] = $staff = $this->staff;
		
		$subscriber = $this->subscriber->getByID($Ticket->AccountID, true);
		if (empty($subscriber)) {
			show_error('Subscriber Account ID #'.$Ticket->AccountID.'# was not saved!', $status_code= 500 );
		}

		$data['subscriber'] = $subscriber;
		
		$data['action_title'] = 'Edit Service Ticket';
		$data['choosedSeverityId'] = $Ticket->SeverityID;
		$data['choosedPriorityId'] = $Ticket->PriorityID;
		$data['choosedStatusId'] = $Ticket->StatusID;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|max_length[8]');
			$this->form_validation->set_rules('severity', 'Severity', 'required|trim');
			$this->form_validation->set_rules('priority', 'Priority', 'trim');
			$this->form_validation->set_rules('status', 'Status', 'required|trim|max_length[3]');
			$this->form_validation->set_rules('progress_comment', 'Progress Comment', 'trim|max_length[2000]');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			/* if status is 3 closed, then add require rule to feedback message input */
			if ($this->input->post('status') == Servicetickets_model::$DefaultRejectStatus) {
				$this->form_validation->set_rules('reject_reason', 'Reject reason', 'trim|required|max_length[10000]');
			}
			/**
			 * passed validation proceed to post success logic
			 */
// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() == true) {
				
				/**
				 * Check user permission to cancel Service Ticket
				 */
				$choosedStatus = $this->st->GetStatusByID(set_value('status'));
				if ($choosedStatus->acl_resource_id > 0 && $this->acl->can_modify(null, $choosedStatus->acl_resource_id) == false) {
					show_error("You haven't permission to cancel Service Ticket #".$ticket_id, $status_code= 503, 'Permission denied');
				}
				
				$ticket_id = set_value('ticket_id');
				
				/**
				 * Calculate how many hours are left 
				 */
				$expirationTime = strtotime("+{$Ticket->KpiTimeHours} hours", strtotime($Ticket->CreationDateTime));
				$diff = $expirationTime - time();
				$hoursRemained = intval(floor($diff / 3600));
				
// 				$createdTime = strtotime($Ticket->CreationDateTime);
				
				$form_data = array(
						'SeverityID' => set_value('severity'),
						'ChangedByUserID' => $staff['user_id'],
						'StatusID' => set_value('status'),
						'ProgressComment' => (set_value('progress_comment'))? set_value('progress_comment'):null ,
						'LeadTimeHours' => $hoursRemained,
						'LastEditDate' => 'NOW()',
				);
				
				if ($this->acl->can_modify(null, 6 /*ST priority*/)) {
					$form_data['PriorityID'] = (set_value('priority'))? set_value('priority'): null ;
				}
				
				if (set_value('status') == Servicetickets_model::$DefaultCloseStatus) {
					$form_data['ClosedByID'] = $staff['user_id'];
					$form_data['ClosureDateTime'] = 'NOW()';
					$form_data['LastEditDate'] = 'NOW()';
				}
				
				if ($ticket_id) {
					$this->st->updateST($ticket_id, $form_data);
				}
				
				/* Notify kam and meng satha, the ticket was closed, and keep who close in execution table */
				if (set_value('status') == Servicetickets_model::$DefaultCloseStatus) {
					
					/* Refress ticket information after update */
					$Ticket = $this->st->getFullInfoByTicketID($INTicketID);
					
					$subjNotify = $this->st->getSubjectNotifications($Ticket->SubjectID, '4', $limit = 1);
					$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($subjNotify['role_ids'], $Ticket->CompanyID);

					$recipients = array_column($notifyUsers, 'email', 'user_id');
					
					// keep user where are finished execute this ticket in log
					$this->st->updateExecuteHystory(array(
							'TicketID' => $Ticket->TicketID,
							'user_id' => $this->staff['user_id'],
							'updated_at' => date('Y-m-d H:i:s'),
							'execution_status' => '1'
					));
					
// 					var_dump($recipients, $this->staff['user_id'], $Ticket, $subjNotify, $notifyUsers);die;
					
					/*
					 * Send notification after execution (ST closed)
					 */
					if ($subjNotify && $subjNotify['Type'] == '4' && $recipients) {
						$ticket_link = site_url("kyc/detailsST/{$Ticket->TicketID}");
						
						/* Prepare Ticket History */
						$data['filteredTickets'] = $this->st->getTicketChangeLog($Ticket->TicketID);
						$progress_history = $this->load->view('kyc/history_email', $data, true);
							
						$closed_user_name = $staff['name'] . ' ('.implode(',', $staff['roles']).')';
						$created_user_name = $Ticket->InitiatorType == 1? $Ticket->CreatedByID : $Ticket->whoCreateName;
							
						/* Make replacement array */
						$replacement_from = array('{closed_user_name}','{close_time}', '{close_date}', '{ticket_link}', '{TicketID}', '{SubjectName}', '{created_user_name}', '{customer_name}', '{phone_number}', '{ticket_created_time}', '{created_date}', '{progress_comment}');
						$replacement_to = array($closed_user_name, date('H:i', strtotime($Ticket->ClosureDateTime)), date('d-M-Y', strtotime($Ticket->ClosureDateTime)),  $ticket_link, $Ticket->TicketID, $Ticket->SubjectName, $created_user_name, $Ticket->CustName, $Ticket->AccountID, date('H:i', strtotime($Ticket->CreationDateTime)), date('d-M-Y', strtotime($Ticket->CreationDateTime)), $progress_history);
							
						$subject = str_replace($replacement_from, $replacement_to, $subjNotify['Title']);
						$body = str_replace($replacement_from, $replacement_to, $subjNotify['MailText']);
						
						$this->sendNotifiyMail($recipients, $subject, $body);
						
						/*create site notification for all recipients*/
						foreach ($recipients as $user_id => $notifyUser) {
							// keep alert
							$this->st->createAlert(array (
									'NotifyID' => $subjNotify['ID'],
									'TicketID' => $Ticket->TicketID,
									'StaffID' => $user_id,
									'EmailSent'	=> '1',
									'SentAt' => date('Y-m-d H:i:s')
							));
						}
					}
				}

				/**
				 * keep reject reason 
				 * send notification about rejected ticket
				 */
				$reject_reason = $this->input->post('reject_reason');
				if(set_value('status') == Servicetickets_model::$DefaultRejectStatus && $reject_reason) {
					$this->keep_rejection($ticket_id, $reject_reason);
				}
// 				die;
				redirect('kyc/detailsST/'.$Ticket->TicketID);
			} else {
				$data['choosedSeverityId'] = $this->form_validation->set_value('severity');
				$data['choosedPriorityId'] = $this->form_validation->set_value('priority');
				$data['choosedStatusId'] = $this->form_validation->set_value('status');
				$data['choosedreject_reason'] = $this->input->post('reject_reason');
			}
		} // end if POST
		
		$SeveritiesDT = $this->st->GetSeverities(true);
		$PrioritiesDT = $this->st->GetPriorities(true);

		$data['SeverityOptions'] = array_replace(array('' => '-- Please Select --'), $SeveritiesDT) ;
		$data['PriorityOptions'] = array_replace(array('' => '-- Please Select --'), $PrioritiesDT);
		
		
		/*
		 * Make options for Status dropbox
		 * exclude option from list if hasn't permission to show it
		 * Check if user is in executions list and allow close statous
		 */
		$StatusesDT = $this->st->GetStatuses();
		$isAllowExecuteTicket = $this->st->isAllowExecuteTicket($INTicketID, array_keys($this->staff['roles']));
		$isAllowApproveReject = $this->st->isAllowApproveRejectTicket($INTicketID, $this->staff['user_id']);
		
		$tmpApproveStatuses = Servicetickets_model::$ApproveStatuses;
		if (isset($isAllowApproveReject['approve_order']) && $isAllowApproveReject['approve_status'] == '0') {
			unset($tmpApproveStatuses[$isAllowApproveReject['approve_order']+1]);
		}
		
// 		var_dump($isAllowApproveReject, $isAllowApproveReject['approve_status']);
// 		var_dump((10 == 10 && (!$isAllowApproveReject || ($isAllowApproveReject && in_array($isAllowApproveReject['approve_status'], array('1','2')))) ));die;
		
		$data['StatusOptions'][''] = '-- Please Select --';
		foreach ($StatusesDT as $item) {
			/* check if exist any restiriction to statuses*/
			if (($item->acl_resource_id > 0 && $this->acl->can_view($staff['role_id'], $item->acl_resource_id) == false) 
				/*check if allow to close ticket*/
				|| ($item->StatusID == Servicetickets_model::$DefaultCloseStatus && $isAllowExecuteTicket == false)
				/*Exclude approves statuses from status list */
				|| (in_array($item->StatusID, $tmpApproveStatuses)) 
				|| ($item->StatusID == Servicetickets_model::$DefaultRejectStatus && (!$isAllowApproveReject || ($isAllowApproveReject && in_array($isAllowApproveReject['approve_status'], array('1','2')))) )) {
					continue;
			}
			$data['StatusOptions'][$item->StatusID] = $item->StatusName;
		}
		
		$this->load->helper('Menu');
// 		$data['navbar'] = $this->selfcare_mod->get_group_menu(10);
		$data['PAGE_TITLE'] = 'Service Ticket System';
		$data['CONTENT']='kyc/edit_st_view';
		$this->load->view('template/tmpl_site_sts', $data);
	
	}// end editST()
	
	function newST()
	{
		if ($this->acl->can_write(null, 3 /* STS */) == false) {
			show_error("You haven't permission to create new Service Ticket", $status_code= 503, 'Permission denied');
		}
		$AccountID = null;
		$data['staff'] = $staff = $this->staff;

		//SubjectID=1&account=110001901271&TicketType=2
		$GETAccount = $this->input->get('account');
		$GETTicketType = $this->input->get('TicketType');
		$GETsubjectID = $this->input->get('SubjectID');
// 		var_dump($this->input->post('TicketType'));
		$current_subscriber = $this->session->userdata('current_subscriber');
		$current_customer = $this->session->userdata('current_customer');
		
		

		$data['subscriber'] = $current_subscriber;
		$data['customer'] = $current_customer;
		$data['choosedAccountID'] = $AccountID;
		$data['choosedTicketType'] = '0';
		$groupsList = array();
		
		if($GETsubjectID) {
			if( ($preSubject = $this->st->getSubjectByID($GETsubjectID))) {
				$data['choosedTypeId'] = $preSubject->TypeID;
				$data['choosedSubjectId'] = $preSubject->SubjectID;
				$data['choosedGroupId'] = $preSubject->GroupID;
				$groupsList = $this->st->GetGroupsByType($preSubject->TypeID, true);

				$data['Questions'] = array_column($this->st->getSubjecQuestions($GETsubjectID), 'QTitle', 'QuestionID');
				if ($preSubject->DefSeverityID) $data['choosedSeverityId'] = $preSubject->DefSeverityID;
			} else {
				show_error('Preselected Service type ID #'.$GETsubjectID.'# was not found!', $status_code= 500 );
			}
		}
		
		/*
		 * put staff email if is not visitor
		 */
		if ($this->session->userdata('visitor')) {
			$data['choosedInitiatorEmail'] = $this->st->getEmailByServiceNumber($this->session->userdata('visitor'));
		}
		

// var_dump($custmem_list);die;
// 		var_dump($this->st->getEmailByServiceNumber($this->session->userdata('visitor')));die;
		$data['action_title'] = 'New Service Ticket details';
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
			$this->form_validation->set_rules('InitiatorEmail', 'Feedback Email', 'trim|valid_email'. (($this->session->userdata('visitor'))?'|required':''));
			$this->form_validation->set_rules('type', 'Ticket Type', 'required|trim');
			$this->form_validation->set_rules('GroupID', 'Group', 'required|trim');
			$this->form_validation->set_rules('subject', 'Service Type', 'required|trim');
			$this->form_validation->set_rules('severity', 'Severity', 'required|trim');
			$this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[2000]');
			$this->form_validation->set_rules('TicketType', 'Ticket Type', 'required|trim|is_numeric');
			$this->form_validation->set_rules('AccountID', 'AccountID', 'trim|is_numeric');
			//set_select('blah', 'v1');
// 			var_dump($this->input->post('TicketType'), set_select('TicketType'));die;
 			$this->form_validation->set_rules('new_phone', 'Subscriber', 'callback_check_new_phone');
 			$this->form_validation->set_rules('msubchoosed[]', 'Subscribers', 'callback_check_msubchoosed');

			$POSTSubjectID = $this->input->post('subject');

			if ($POSTSubjectID) {
				$data['Questions'] = array_column($this->st->getSubjecQuestions($POSTSubjectID), 'QTitle', 'QuestionID');
			}
			$Answers = $this->input->post('Answers');

			if ($Answers) {
				$i = 1;
				foreach ($Answers as $QuestionID => $QTitle) {
					$this->form_validation->set_rules("Answers[{$QuestionID}]", "Question {$i}", 'required|trim|max_length[2000]');
					$i++;
				}
			}

// 			var_dump($this->input->post());
// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());
			
			if ($this->form_validation->run() == true) {
				
				$AccountID = set_value('AccountID');
				$SubjectID = set_value('subject');
				$GroupID = (set_value('GroupID'))?set_value('GroupID'): null;
				$defaultToApproval = $this->st->getDefaultApprovalID($SubjectID);
				$ApprovalEntityID = ($defaultToApproval)? $defaultToApproval->ID: null;
				$SubjectDT = $this->st->getSubjectByID($SubjectID);
				$InitiatorEmail = ($this->session->userdata('visitor') == false)? $this->staff['email']: set_value('InitiatorEmail');
				$TicketType = set_value('TicketType');
				$msubchoosed = $this->input->post('msubchoosed');
				
				switch ($TicketType) {
					case '0': $AccountID = $current_subscriber['Subscriber_Info']['PhoneNo'];break;
					case '1': $AccountID = $current_customer['WebCustId'];break;
					case '2': $AccountID = $current_customer['WebCustId'];break;
					case '3': $AccountID = $current_subscriber['Subscriber_Info']['PhoneNo'];$TicketType = '0';break;
					default:
						show_error("Ticket Type was not choosed!", $status_code= 503, 'Ticket Type');
				}
				
				//$customerResponsibleDT = $this->Staff_mod->getResponsibleCustomer($subscriber->company_id);
				//$CSRResponsibleID = (isset($customerResponsibleDT->user_id))? $customerResponsibleDT->user_id : null;
// 				var_dump($subscriber->company_id, $customerResponsibleDT);die;
				$preSubject = $this->st->getSubjectByID($SubjectID);
				
				$form_data = array(
						'TypeID' => set_value('type'),
						'SubjectID' => $SubjectID,
						'GroupID' => $GroupID,
						'CompanyID' => $current_customer['WebCustId'],
						'AccountID' => $AccountID,
						'SeverityID' => set_value('severity'),
						'Description' => set_value('description'),
						'CreatedByID' => ($this->session->userdata('visitor'))? $this->session->userdata('visitor') :$staff['user_id'],
						//'CSRResponsibleID' => $CSRResponsibleID, //KAM
						'StatusID' => $this->st->getDefaultStatusID(),
						'ApprovalEntityID' => $ApprovalEntityID,
						'LeadTimeHours' => $SubjectDT->KpiTimeHours,
						'forCustStatusID' => 1,
						'TicketType' => $TicketType,
						'PriorityID' => (isset($preSubject->DefPriorityID)? $preSubject->DefPriorityID: 1),
						'InitiatorEmail' => $InitiatorEmail,
						'InitiatorType' => ($this->session->userdata('visitor'))? 1 : 0
				);
				//var_dump($form_data);die;
				// run insert model to write data to db
				$ticket_id = $this->st->SaveFormNewST($form_data);
				$form_data['TicketID'] = $ticket_id;
				
				$Answers = $this->input->post('Answers');
				if ($Answers) {
					foreach ($Answers as $QuestionID => $Answer) {
						$this->st->saveTicketAnswer($QuestionID, $ticket_id, $Answer);
					}
				}
				
				/*
				 * Check if ticket type is Mass selected
				 * and keep choosed subscriber numbers
				 */
				if ($TicketType == 2 && $msubchoosed && $ticket_id) {
					$out = array();
					foreach ($msubchoosed as $item) {
						$out[] = array('TicketID'=>$ticket_id, 'AccountID'=>$item);
					}
					$this->st->SaveMassSelectedSubs($out);
				}

				$this->createNotifications($ticket_id);

				redirect('kyc/index/');
			} else {
// 				var_dump(validation_errors());die;
				$data['choosedTypeId'] = $this->form_validation->set_value('type');
				$data['choosedSubjectId'] = $this->form_validation->set_value('subject');
				$data['choosedSeverityId'] = $this->form_validation->set_value('severity');
				$data['choosedGroupId'] = $this->form_validation->set_value('GroupID');
				$data['choosedInitiatorEmail'] = $this->form_validation->set_value('InitiatorEmail');
				$data['loadedAnswers'] = $this->input->post('Answers');
				$data['choosedTicketType'] = $this->input->post('TicketType');
				$data['choosedAccountID'] = $this->input->post('AccountID');
				$data['choosedMsubchoosed'] = $this->input->post('msubchoosed');
				$groupsList = $this->st->GetGroupsByType($data['choosedTypeId'], true);
			}
		} // end if POST
	
		$TypesDT 	  = $this->st->GetAllowedTypes();
		$StatusesDT   = $this->st->GetStatuses(true);
		$SeveritiesDT = $this->st->GetSeverities(true);
		$GroupsDT = $this->st->GetGroups(true);
		
		$choosedTypeId = 0;

		if (isset($data['choosedTypeId'])) {
			$choosedTypeId = $data['choosedTypeId'];
		}
		$SubjectsDT = $this->st->GetSubjects($choosedTypeId, true);
		
		$data['SubjectOptions'] = array_replace(array('' => '-- Please Select --'), $SubjectsDT);
		$data['TypeOptions'] = array_replace(array('' => '-- Please Select --'), $TypesDT);
		$data['SeverityOptions'] = array_replace(array('' => '-- Please Select --'), $SeveritiesDT) ;
		$data['StatusOptions'] = array_replace(array('' => '-- Please Select --'), $StatusesDT);
		$data['GroupOptions'] = array_replace(array('' => '-- Please Select --'), $groupsList); //array_replace(array('' => '-- Please Select --'), $GroupsDT);
		
		$data['current_memberpage'] = $this->session->userdata('current_memberpage')? $this->session->userdata('current_memberpage'): 1;
		
		/* repopulate list of member in current customer to 48 itmes if the number is less and user is not visitor */
		if ($this->session->userdata('visitor') == false && $current_customer['Groups_Info']['GroupDetails']['MemberAmount'] >= 48 && count($current_customer['Groups_Info']['MemberSubscriberList']) < 48 ) {
			$current_customer['Groups_Info'] = Group_Member($current_customer['GroupId'], $data['current_memberpage'], 48);
			$this->session->set_userdata('current_customer', $current_customer);
		}
		
		$data['current_customer'] = $current_customer;
		$data['current_subscriber'] = $current_subscriber;
		
		if( $this->acl->can_read(null, 12 /*Alert system*/)) {
			$data['countActiveAlerts'] = $this->st->countActiveAlerts($this->session->userdata('staff')['user_id']);
		}
		
		$data['navBackLink'] = site_url('kyc/index');
		$data['hideNewTicket'] = true;
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Service Tickets', 'kyc/index/');
		$this->breadcrumbs->push('New Ticket', current_url());
		
		$data['BODY_CLASS'] = "sts";
		$data['PAGE_TITLE'] = 'Service Ticket System';

		if ($this->input->get('debug') == 'def') {
			$data['CONTENT']='kyc/new_st_view_orig';
			$this->load->view('template/tmpl_site_sts', $data);
		} else {
			$data['CONTENT']='kyc/new_st_view';
			$this->load->view('layout/layout_st', $data);
		}
	}// end newST()
	
	function detailsST($INTicketID = null)
	{
		$this->load->helper('text');
		
		$data['STicket'] = $STicket = $this->st->getFullInfoByTicketID($INTicketID);

		if (empty($STicket)) {
			show_error('Service Ticket with ID #'.$INTicketID.'# was not found!', $status_code= 500 );
		}

// 		if ($STicket->CreatedByID) {
// 			$createStaffName = $this->Staff_mod->getByID($STicket->CreatedByID);
// 			$STicket->createStaffName = $createStaffName->staff_name;
// 		}

		if ($STicket->CSRResponsibleID) {
			$CSRResponsibleName = $this->Staff_mod->getByID($STicket->CSRResponsibleID);
			$STicket->CSRResponsibleName = $CSRResponsibleName['name'];
		}

		/*
		 * Get list of approve flow by ticket id
		 */
		if ($this->acl->can_view(null, 32 /*32 ST Approve*/)) {
			$data['TicketApproveStatus'] = $this->st->GetTicketApproveStatus($INTicketID);
		}

		/*
		 * Allow to leave progress comments or close ticket only if status is not closed
		 */
		if ($STicket->StatusID != Servicetickets_model::$DefaultCloseStatus && $this->visitor == false) {
			
			/*
			 * Check if allow to execute ticket
			 */
			$isAllowExecuteTicket = $this->st->isAllowExecuteTicket($INTicketID, array_keys($this->staff['roles']));
			if ($isAllowExecuteTicket) {
				$data['navAddProgress'] = '#add_progress';
			}
			
			/*
			 * Check if current user allowed to edit ticket but is not allowed to execute, then show edit button
			 */
// 			if (!$isAllowExecuteTicket && $this->acl->can_modify(null, 3 /* STS */) == true) {
// 				$data['navEditLink'] = site_url("kyc/editST/{$STicket->TicketID}");
// 			}
			
			/*
			 * Check if current user is allowed to close ticket
			 */
			$isAllowCloseTicket = $this->st->isAllowCloseTicket($INTicketID, array_keys($this->staff['roles']));

			if ($isAllowCloseTicket) {
				$data['navCloseTicket'] = site_url("kyc/closeST/?TicketID={$INTicketID}");
			}
		}
		
// 		$this->load->helper('addstbutton');
// 		$data['navAddST'] = new_ticket_button();
		
// 		$data['ticketHistory'] = $this->st->getHistoryByTicketID($INTicketID);
		
		/*
		 * Get list of change history
		 */
		if ($this->acl->can_view(null, 30 /*30 ST Change log*/)) {
			$data['cahangesLog'] = $this->st->getTicketChangeLog($INTicketID);
		}

		switch ($STicket->TicketType) 
		{
			case 1:
				$groupInfo = Group_Member($STicket->GroupId);
				$data['countMassSelectedSubs'] = $groupInfo['GroupDetails']['MemberAmount'];
				break;
			case 2: 
				$data['countMassSelectedSubs'] = $this->st->countMassSelectedSubs($INTicketID); 
				break;
			default: 
				$data['countMassSelectedSubs'] = 1;
		}
		
		if ($STicket->TicketType == 2) {
			$data['countMassSelectedSubs'] = $this->st->countMassSelectedSubs($INTicketID);
		}
		
		$data['feedbackHistory'] = $this->st->getFeedbackByTicketID($INTicketID, 3);
		
		/*
		 * Remove alert from unviewed list
		 */
		if( $this->acl->can_read(null, 12 /*Alert system*/)) {
			if ($this->acl->can_modify(null, 12 /*12 Alert system*/) && ($alertInfo = $this->st->getAlertByTicketUser($STicket->TicketID, $this->staff['user_id'], $Status = '0')) ) {
				$this->st->updateUserAlert($STicket->TicketID, $this->staff['user_id'], array('IsViewed'=>'1', 'Status'=>'1', 'ViewedAt'=>date('Y-m-d H:i:s')));
			}
		
			$data['countActiveAlerts'] = $this->st->countActiveAlerts($this->session->userdata('staff')['user_id']);
		}

		$data['navBackLink'] = site_url('kyc/index');
		
// 		$data['navCloseTicket'] = site_url('kyc/index');
// 		$data['navAddProgress'] = '#add_progress';

		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home/');
		$this->breadcrumbs->push('Service Tickets', 'kyc/index/');
		$this->breadcrumbs->push($STicket->SubjectName, current_url());
		
		$data['PAGE_TITLE'] = 'Service Ticket System';
		$data['CONTENT']='kyc/details_st_view';
		$data['BODY_CLASS'] = "sts";
		
		if ($this->input->get('debug') == 'def') $this->load->view('template/tmpl_site_sts', $data);
		else $this->load->view('layout/layout_st', $data);
	}
	
	function ajax_search_subs()
	{
		/******* end form filter ************/
		$data['filteredUsers'] = array();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$filter = array();
			$filter['searchtext'] 	= trim($this->input->post('searchtext'));
			$filter['search_field'] = $this->input->post('search_field');
			$filter['company_id'] 	= $this->staff['companies'];
			$filter['is_updated'] 	= '1';
			$data['filteredUsers'] = $filteredUsers = $this->subscriber->search($filter);
		}
		$this->load->view('kyc/ajax_search_subs', $data);
	}
	
	function ajax_get_ticket_related_subs()
	{
		$TicketID = $this->input->get_post('TicketID', true);
		$data['MassSelectedSubs'] = $this->st->MassSelectedSubs($TicketID);
		
// 		var_dump($data['MassSelectedSubs']);die;
		
		$this->load->view('kyc/ajax_get_ticket_related_subs', $data);
	}
	
	public function alerts()
	{
		/******* prepare form filter ************/
		$data['filteredTickets'] = array();
		$filter = array();
		
		$filter['IsViewed'] = '0';
		$filter['StaffID'] 	= $this->staff['user_id'];
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$filter['company_id']	= ($this->input->post('company_id'))?  $this->input->post('company_id'): 	null;
			$filter['IsViewed'] 	= (in_array($this->input->post('IsViewed'), array('0','1')))? $this->input->post('IsViewed'): null;
			$filter['EmailSent'] 	= (in_array($this->input->post('EmailSent'), array('0','1')))? $this->input->post('EmailSent'): null;
			$filter['date_from'] = ($this->input->post('date_from'))? $this->input->post('date_from'): null;
			$filter['date_to'] = ($this->input->post('date_to'))? $this->input->post('date_to'): null;
		}
		
		$data['filter'] = $filter;
		$data['filteredAlerts'] = $this->st->alertsFilter($filter);
	
// 		var_dump($data['filteredAlerts'] );die;
		//$data['companies'] = array_replace(array(''=>'--All--'), $this->staff['companies']);
		
		if( $this->acl->can_read(null, 12 /*Alert system*/)) {
			$data['countActiveAlerts'] = $this->st->countActiveAlerts($this->staff['user_id']);
		}
		
		$data['navBackLink'] = site_url('servicetickets');
// 		$this->load->helper('addstbutton');
// 		$data['navAddST'] = new_ticket_button();

		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Service Tickets', 'kyc/index/');
		$this->breadcrumbs->push('Notifications', 'kyc/alerts/');
		
		$data['PAGE_TITLE'] = 'Service Ticket System: Alerts';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='kyc/alerts';
		if ($this->input->get('debug') == 'def') $this->load->view('template/tmpl_site_sts', $data);
		else $this->load->view('layout/layout_st', $data);
	
	}// end index()
	
	function detailsAlert($INAlertID = null)
	{
		$STAlert = $this->st->getFullAlertInfo($INAlertID);
	
		if (empty($STAlert)) {
			show_error('Service Ticket Alert with ID #'.$INAlertID.'# was not found!', $status_code= 500 );
		}
		
		/**
		 * mark alert as viewed
		 */
		if('0' == $STAlert->IsViewed) {
			$this->st->updateAlert($INAlertID, array('IsViewed'=>'1', 'Status'=>'1', 'ViewedAt'=>date('Y-m-d H:i:s')));
			$STAlert = $this->st->getFullAlertInfo($INAlertID);
		}
		
		if( $this->acl->can_read(null, 12 /*Alert system*/)) {
			$data['countActiveAlerts'] = $this->st->countActiveAlerts($this->staff['user_id']);
		}
// 		var_dump($STAlert);die;
		$data['action_title'] = 'Alert details';
		$data['STAlert'] = $STAlert;
		$data['navBackLink'] = site_url('kyc/alerts');
// 		$this->load->helper('addstbutton');
// 		$data['navAddST'] = new_ticket_button();
	
// 		$this->load->helper('Menu');
// 		$data['navbar'] = $this->selfcare_mod->get_group_menu(10);
		
		// load Breadcrumbs
		$this->load->library('breadcrumbs');
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Service Tickets', 'kyc/index/');
		$this->breadcrumbs->push('Notifications', 'kyc/alerts/');
		$this->breadcrumbs->push('Notification Details', 'kyc/detailsAlert/');
		
		$data['PAGE_TITLE'] = 'Service Ticket System: Notification details';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='kyc/st_alertdetails';
		if ($this->input->get('debug') == 'def') $this->load->view('template/tmpl_site_sts', $data);
		else $this->load->view('layout/layout_st', $data);
	}
	
	function ajax_get_subject()
	{
		$SubjectID = $this->input->get_post('SubjectID');
		$subject = '';
		if ($SubjectID) {
			$subject = $this->st->GetSubjectByID($SubjectID, true);
		}
		
		$data['Questions'] = array_column($this->st->getSubjecQuestions($SubjectID), 'QTitle', 'QuestionID');
		$table = $this->load->view('kyc/ajax_subject_pull', $data, true);
		
		$out = array('subject'=>$subject, 'subject_pull'=>$table);
		
		header('Content-Type: application/json');
		echo json_encode($out, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function ajax_send_feedback()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$TicketID = $this->input->get_post('TicketID');
			$Message = $this->input->get_post('Message');
			$this->send_feedback($TicketID, $Message);
		}
	}
	
	function feedbackhistory($INTicketID = null)
	{
		if(!$this->acl->can_read(null, 25 /*6 ST priority*/)) {
			show_error('You not have permission!', $status_code= 503, 'Permission denied');
		}
		$data['STicket'] = $STicket = $this->st->getFullInfoByTicketID($INTicketID);
		//var_dump($this->acl->can_write(3, 10 /*SIM CARD*/));
		$data['feedbackHistory'] = $this->st->getFeedbackByTicketID($INTicketID);

		$this->load->helper('addstbutton');
		$data['navAddST'] = new_ticket_button();
	
		$data['navBackLink'] = site_url('kyc/detailsST/'.$INTicketID);
	
		$this->load->helper('Menu');

		$data['PAGE_TITLE'] = 'Service Ticket History';
		$data['CONTENT']='kyc/feedbackhistory';
		$this->load->view('template/tmpl_site_sts', $data);
	}
	
	private function send_feedback($TicketID, $Message)
	{
		$this->load->helper('email');
		$STicket = $this->st->getFullInfoByTicketID($TicketID);
		
		if($STicket) {

			/*
			 * get initator email by type
			 */
			if (isset($STicket->InitiatorType) == false || $STicket->InitiatorType == 0) {
				$stafDetails = $this->Staff_mod->getByID($STicket->CreatedByID);
				$emailTo = $stafDetails['email'];
			} elseif ($STicket->InitiatorType > 0 && $STicket->InitiatorEmail) {
				$emailTo = $STicket->InitiatorEmail;
			} else {
				$this->selfcare_mod->save_log('Feedback error: Email notify not send.');
				return false;
			}

			$form_data = array('TicketID'=>$TicketID, 'Email'=>$emailTo, 'Message'=>$Message, 'CreatedBy'=>$this->staff['user_id'], 'BackTo'=>$STicket->CreatedByID);
			$this->st->SaveFeedback($form_data);
			
			$ticket_link = site_url("kyc/detailsST/{$STicket->TicketID}");
			
			$subjNotify = $this->st->getSubjectNotifications($STicket->SubjectID, '5', $limit = 1); if (!$subjNotify) return false;
			$created_user_name = $STicket->InitiatorType ? $STicket->CreatedByID : $STicket->whoCreateName;
			
			/*
			 * Make replacement array
			 */
			$replacement = array(
					'{feedback_message}'=>$Message,
					'{ticket_link}'=>$ticket_link,
					'{TicketID}'=>$STicket->TicketID,
					'{SubjectName}'=>$STicket->SubjectName,
					'{created_user_name}'=>$created_user_name,
					'{customer_name}'=>$STicket->CustName,
					'{phone_number}'=>$STicket->AccountID,
					'{ticket_created_time}'=>date('H:i', strtotime($STicket->CreationDateTime)),
					'{created_date}'=>date('d-M-Y', strtotime($STicket->CreationDateTime)),
					'{action_user_name}'=>$this->staff['name']
			);
			
			switch ($STicket->TicketType) {
				case 0:
					$replacement['{phone_number}'] = '0'.$STicket->AccountID;
					break;
				case 1: // mass full customer
					$groupInfo = Group_Member($STicket->GroupId);
					$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
					break;
				case 2: //mass selected
					$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($STicket->TicketID) . ')';
					break;
			}
			
			$subject = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['Title']);
			$body = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['MailText']);
			
			$this->sendNotifiyMail($STicket->InitiatorEmail, $subject, $body);
		}
	}
	
	private function keep_rejection($TicketID, $reason, $updateTicket = false)
	{
		$this->load->helper('email');
		$STicket = $this->st->getFullInfoByTicketID($TicketID);
		
		if($STicket) {
	
			$isAllowApproveReject = $this->st->isAllowApproveRejectTicket($TicketID, $this->staff['user_id'], '0', $flag = '1');
			if (!$isAllowApproveReject || ($isAllowApproveReject && $isAllowApproveReject['approve_status'] != '0')) return false;

			/*
			 * Keep approve status as Rejected
			 */
			$this->st->updateApproveHystory(array('ID'=>$isAllowApproveReject['ID'], 'approve_status'=>'2', 'flag'=>'0', 'updated_at'=>date('Y-m-d H:i:s')));
			
			/*
			 * Keep reject reason
			 */
			$this->st->SaveReject(array('TicketID'=>$TicketID, 'ApproveHistoryID'=>$isAllowApproveReject['ID'], 'Reason'=>$reason, 'CreatedBy'=>$this->staff['user_id']));
			
			if ($updateTicket == true) {
				/**
				 * Calculate how many hours are left
				 */
				$expirationTime = strtotime("+{$STicket->KpiTimeHours} hours", strtotime($STicket->CreationDateTime));
				$diff = $expirationTime - time();
				$hoursRemained = intval(floor($diff / 3600));
				
				$this->st->updateST($TicketID, array(
						'ClosedByID' => $this->staff['user_id'],
						'ClosureDateTime' => 'NOW()',
						'ChangedByUserID' => $this->staff['user_id'],
						'LastEditDate' => 'NOW()',
						'StatusID' => Servicetickets_model::$DefaultRejectStatus,
						'forCustStatusID' => Servicetickets_model::$DefaultRejectStatus,
						'LeadTimeHours' => $hoursRemained
				));
			}
			/*
			 * Notify users about rejected ticket
			 */
			$subjNotify = $this->st->getSubjectNotifications($STicket->SubjectID, '2', $limit = 1); if (!$subjNotify) return false;
			
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($subjNotify['role_ids'], $STicket->CompanyID); if (!$notifyUsers) return false;
			
			$recipients = array_column($notifyUsers, 'email', 'user_id');
			
			if ($subjNotify['Type'] == '2' && $recipients) {
				$ticket_link = site_url("kyc/detailsST/{$STicket->TicketID}");
				
				/*
				 * Prepare Ticket History
				 */
				$data['filteredTickets'] = $this->st->getTicketChangeLog($STicket->TicketID);
				$progress_history = $this->load->view('kyc/history_email', $data, true);
				
				/*
				 * Get account information
				 */
				$current_customer = $this->Customer_model->getWebCustById($STicket->CompanyID);
				$current_subscriber = null;
				
				$data2view = array('customer'=>$current_customer);
				
				if ($STicket->TicketType == Servicetickets_model::$TicketType_Subscriber){
					$current_subscriber = $this->subscriber->loadSubscriber($STicket->AccountID);
					$data2view['subscriber'] = $current_subscriber;
				}
				
				$account_profile = $this->load->view('kyc/subsinfo_email', $data2view, true); //{account_profile}
				
				$created_user_name = $STicket->InitiatorType ? $STicket->CreatedByID : $STicket->whoCreateName;
				
				/*
				 * Make replacement array
				 */
				$replacement = array(
						'{account_profile}'=>$account_profile,
						'{ticket_link}'=>$ticket_link,
						'{TicketID}'=>$STicket->TicketID,
						'{SubjectName}'=>$STicket->SubjectName,
						'{created_user_name}'=>$created_user_name,
						'{customer_name}'=>$STicket->CustName,
						'{phone_number}'=>$STicket->AccountID,
						'{ticket_created_time}'=>date('H:i', strtotime($STicket->CreationDateTime)),
						'{created_date}'=>date('d-M-Y', strtotime($STicket->CreationDateTime)),
						'{action_user_name}'=>$this->staff['name'],
						'{reject_reason}'=>$reason, 
						'{progress_comment}'=>$progress_history
				);
					
				switch ($STicket->TicketType) {
					case 0:
						$replacement['{phone_number}'] = '0'.$STicket->AccountID;
						break;
					case 1: // mass full customer
						$groupInfo = Group_Member($STicket->GroupId);
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
						break;
					case 2: //mass selected
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($STicket->TicketID) . ')';
						break;
				}
					
				$subject = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['MailText']);
				
				/*create site notification for all recipients*/
				foreach ($recipients as $user_id => $notifyUser) {
					// keep alert
					$this->st->createAlert(array (
							'NotifyID' => $subjNotify['ID'],
							'TicketID' => $STicket->TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
				
				/* send notifications */
				$this->sendNotifiyMail($recipients, $subject, $body);
			}
		}
	}
	
	private function keep_progress($TicketID, $ProgressComment)
	{
		$this->load->helper('email');
		$STicket = $this->st->getFullInfoByTicketID($TicketID);

		if($STicket) {
	
			$isAllowExecuteTicket = $this->st->isAllowExecuteTicket($TicketID, array_keys($this->staff['roles'])); if (!$isAllowExecuteTicket) return false;
			
			/*
			 * Keep executant or processing entity
			 */
			$this->st->updateExecuteHystory(array(
					'TicketID' => $STicket->TicketID,
					'user_id' => $this->staff['user_id'],
					'updated_at' => date('Y-m-d H:i:s'),
					'execution_status' => '1'
			));
				
				
			/*
			 * Calculate how many hours are left
			 */
			$expirationTime = strtotime("+{$STicket->KpiTimeHours} hours", strtotime($STicket->CreationDateTime));
			$diff = $expirationTime - time();
			$hoursRemained = intval(floor($diff / 3600));

			$this->st->updateST($TicketID, array('ChangedByUserID' => $this->staff['user_id'],
					'StatusID' => Servicetickets_model::$DefaultProcessStatus,
					'ProgressComment' => $ProgressComment,
					'LeadTimeHours' => $hoursRemained,
					'LastEditDate' => 'NOW()',)
			);
			
			/*
			 * Notify users about rejected ticket
			 */
			
			$subjNotify = $this->st->getSubjectNotifications($STicket->SubjectID, '13', $limit = 1);if (!$subjNotify) return false;
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($subjNotify['role_ids'], $STicket->CompanyID); if (!$notifyUsers) return false;
			
			$recipients = array_column($notifyUsers, 'email', 'user_id');
				
			if ($subjNotify['Type'] == 13 && $recipients) {
				$ticket_link = site_url("kyc/detailsST/{$STicket->TicketID}");
	
				/*
				 * Prepare Ticket History
				*/
				$data['filteredTickets'] = $this->st->getTicketChangeLog($STicket->TicketID);
				$progress_history = $this->load->view('kyc/history_email', $data, true);

				/*
				 * Get account information
				 */
				$current_customer = $this->Customer_model->getWebCustById($STicket->CompanyID);
				$current_subscriber = null;
				
				$data2view = array('customer'=>$current_customer);
				
				if ($STicket->TicketType == Servicetickets_model::$TicketType_Subscriber){
					$current_subscriber = $this->subscriber->loadSubscriber($STicket->AccountID);
					$data2view['subscriber'] = $current_subscriber;
				}
				
				$account_profile = $this->load->view('kyc/subsinfo_email', $data2view, true); //{account_profile}
				
				$created_user_name = $STicket->InitiatorType == 1? $STicket->CreatedByID : $STicket->whoCreateName;
				$subject_type = $STicket->TypeName;
				
				/*
				 * Make replacement array
				*/
				$replacement = array(
						'{subject_type}'=>$subject_type,
						'{account_profile}'=>$account_profile,
						'{progress_comment}'=>$ProgressComment,
						'{change_history}'=>$progress_history,
						'{ticket_link}'=>$ticket_link,
						'{TicketID}'=>$STicket->TicketID,
						'{SubjectName}'=>$STicket->SubjectName,
						'{created_user_name}'=>$created_user_name,
						'{customer_name}'=>$STicket->CustName,
						'{phone_number}'=>$STicket->AccountID,
						'{ticket_created_time}'=>date('H:i', strtotime($STicket->CreationDateTime)),
						'{created_date}'=>date('d-M-Y', strtotime($STicket->CreationDateTime)),
						'{action_user_name}'=>$this->staff['name']						
				);
					
				switch ($STicket->TicketType) {
					case 0:
						$replacement['{phone_number}'] = '0'.$STicket->AccountID;
						break;
					case 1: // mass full customer
						$groupInfo = Group_Member($STicket->GroupId);
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
						break;
					case 2: //mass selected
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($STicket->TicketID) . ')';
						break;
				}
					
				$subject = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['MailText']);
	
	
				/*create site notification for all recipients*/
				foreach ($recipients as $user_id => $notifyUser) {
					// keep alert
					$this->st->createAlert(array (
							'NotifyID' => $subjNotify['ID'],
							'TicketID' => $STicket->TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
	
				/* send notifications */
				$this->sendNotifiyMail($recipients, $subject, $body);
			}
		}
	}
	
	function add_progress()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$TicketID = $this->input->post('TicketID');
			$ProgressComment = $this->input->post('ProgressComment');
// 			var_dump($TicketID, $ProgressComment);die;
			$this->keep_progress($TicketID, $ProgressComment);
			if ($this->input->is_ajax_request()) {
				return ;
			}
			redirect("kyc/detailsST/{$Ticket->TicketID}");
		}
		show_error('Page not found!', $status_code = 404, 'Try again later');
	}
	
	function rejectST()
	{
		if ($this->input->is_ajax_request() == false) {
			show_error('Page not found!', $status_code = 404, 'Try again later');
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->input->is_ajax_request() == true) {
			$TicketID = $this->input->post('TicketID');
			$ApproveHistoryID = $this->input->post('ApproveHistoryID');
			$rejectReason = $this->input->post('reason');
				
			$this->keep_rejection($TicketID, $rejectReason, $updateTicket = true);
			return '';	
		} else {
			show_error('Page not found!', $status_code = 404, 'Try again later');
		}
	}
	
	function closeST()
	{
		if ($this->input->is_ajax_request() == false) {
			show_error('Page not found!', $status_code = 404, 'Try again later');
		}
		$this->load->helper('email');

		$TicketID = $this->input->get('TicketID');

		$isAllowCloseTicket = $this->st->isAllowCloseTicket($TicketID, array_keys($this->staff['roles']));
		
		/* Notify kam and meng satha, the ticket was closed, and keep who close in execution table */
		if ($isAllowCloseTicket) {
				
			/* Get ticket information*/
			$Ticket = $this->st->getFullInfoByTicketID($TicketID);
			
			/* Calculate how many hours are left */
			$expirationTime = strtotime("+{$Ticket->KpiTimeHours} hours", strtotime($Ticket->CreationDateTime));
			$diff = $expirationTime - time();
			$hoursRemained = intval(floor($diff / 3600));
			
			/* Update ticket status to closed*/
			$this->st->updateST($TicketID, array(
					'ChangedByUserID' => $this->staff['user_id'],
					'StatusID' => Servicetickets_model::$DefaultCloseStatus,
					'forCustStatusID' => Servicetickets_model::$DefaultCloseStatus,
					'LeadTimeHours' => $hoursRemained,
					'ClosedByID' => $this->staff['user_id'],
					'ClosureDateTime' => 'NOW()',
					'LastEditDate' => 'NOW()')
			);
			
			/* 
			 * Check if is complain ticket 
			 * 10 - complaint mesage
			 * 8 - request message
			 */
			$messageType = $Ticket->TypeID == 1 ? 10 : 8;
			$subjNotify = $this->st->getSubjectNotifications($Ticket->SubjectID, $messageType, $limit = 1); if (!$subjNotify) return false;
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($subjNotify['role_ids'], $Ticket->CompanyID); if (!$notifyUsers) return false;

			$recipients = array_column($notifyUsers, 'email', 'user_id');
				
			/* Get fresh ticket information after update */
			$Ticket = $this->st->getFullInfoByTicketID($TicketID);
		
			/*
			 * Send notification after execution (ST closed)
			*/
			if ($recipients) {
				
				/*create site notification for all recipients*/
				foreach ($recipients as $user_id => $notifyUser) {
					// keep alert
					$this->st->createAlert(array (
							'NotifyID' => $subjNotify['ID'],
							'TicketID' => $Ticket->TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
				
				$ticket_link = site_url("kyc/detailsST/{$Ticket->TicketID}");
		
				/* Prepare Ticket History */
				$data['filteredTickets'] = $this->st->getTicketChangeLog($Ticket->TicketID);
				$change_history = $this->load->view('kyc/history_email', $data, true);
				
				/*
				 * Get account information
				 */
				$current_customer = $this->Customer_model->getWebCustById($STicket->CompanyID);
				$current_subscriber = null;
				
				$data2view = array('customer'=>$current_customer);
				
				if ($STicket->TicketType == Servicetickets_model::$TicketType_Subscriber){
					$current_subscriber = $this->subscriber->loadSubscriber($STicket->AccountID);
					$data2view['subscriber'] = $current_subscriber;
				}
				
				$account_profile = $this->load->view('kyc/subsinfo_email', $data2view, true); //{account_profile}
		
				$closed_user_name = $this->staff['name'];
				$created_user_name = $Ticket->InitiatorType == 1? $Ticket->CreatedByID : $Ticket->whoCreateName;

				/* Make replacement array */
				$replacement = array(
						'{account_profile}'=>$account_profile,
						'{approve_user_name}'=>$approve_user_name,
						'{ticket_link}'=>$ticket_link,
						'{TicketID}'=>$Ticket->TicketID,
						'{SubjectName}'=>$Ticket->SubjectName,
						'{created_user_name}'=>$created_user_name,
						'{customer_name}'=>$Ticket->CustName,
						'{phone_number}'=>$Ticket->AccountID,
						'{ticket_created_time}'=>date('H:i', strtotime($Ticket->CreationDateTime)),
						'{created_date}'=>date('d-M-Y', strtotime($Ticket->CreationDateTime)),
						'{change_history}'=>$change_history,
						'{closed_user_name}'=>$closed_user_name,
						'{close_time}'=>date('H:i', strtotime($Ticket->ClosureDateTime)),
						'{close_date}'=>date('d-M-Y', strtotime($Ticket->ClosureDateTime))
				);
				
				switch ($Ticket->TicketType) {
					case 0:
						$replacement['{phone_number}'] = '0'.$Ticket->AccountID;
						break;
					case 1: // mass full customer
						$groupInfo = Group_Member($Ticket->GroupId);
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
						break;
					case 2: //mass selected
						$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($Ticket->TicketID) . ')';
						break;
				}
				
				$subject = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $subjNotify['MailText']);

				$this->sendNotifiyMail($recipients, $subject, $body);
			}
		}
	}
	
	function ajax_get_hprogress_message()
	{
		$ID = $this->input->get('HistoryID');
		$ticketHistory = $this->st->getHistoryByID($ID);
		if (!$ticketHistory) echo '';
		else echo $ticketHistory['ProgressComment'];
	}
	
	function ajax_get_feedback_message()
	{
		$FeedbackID = $this->input->get('FeedbackID');
		$Feedback = $this->st->getFeedbackByID($FeedbackID);
		if (!$Feedback) echo '';
		else echo $Feedback['Message'];
	}
	
	private function get_ticket_answers($TicketID = null) 
	{
		if (!$TicketID) return '';
		$ticketAnswers = $this->st->getTicketAnswers($TicketID);
		
		if (!$ticketAnswers) return '';
		
		$out = "<ol style=\"margin-left: 10px;padding-left: 0;list-style-type:decimal;\">";
		foreach ($ticketAnswers as $Answer) {
			$out .= "<li>{$Answer['QTitle']}<br /><em>{$Answer['Answer']}</em></li>";
		}
		$out .= "</ol>";
		
		return $out;
	}
	
	private function createNotifications($TicketID = null)
	{
		if (!$TicketID) return false;
	
		$this->load->helper('email');
		$Ticket = $this->st->getFullInfoByTicketID($TicketID); if (!$Ticket) return false;
	
		$ticket_link = site_url("kyc/detailsST/{$TicketID}");
		$approve_link = site_url("kyc/approveST/{$TicketID}");
		$reject_link = site_url("kyc/detailsST/{$TicketID}?reject=reason");
	
		$ticket_details = $this->get_ticket_answers($TicketID);
	
		/*
		 * Get account information
		*/
		$current_subscriber = $this->session->userdata('current_subscriber');
		$current_customer = $this->session->userdata('current_customer');
	
		$data2view = array('customer'=>$current_customer);
		if ($Ticket->TicketType == Servicetickets_model::$TicketType_Subscriber) {
			$data2view['subscriber'] = $current_subscriber;
		}
	
		$account_profile = $this->load->view('kyc/subsinfo_email', $data2view, true); //{account_profile}
	
		$created_user_name = $Ticket->InitiatorType == 1? '0'.$Ticket->CreatedByID : $Ticket->whoCreateName;
	
		/*
		 * Make replacement array
		 */
		$replacement = array(
				'{account_profile}'=>$account_profile,
				'{ticket_details}'=>$ticket_details, 
				'{ticket_description}'=>$Ticket->Description, 
				'{approve_link}'=>$approve_link, 
				'{reject_link}'=>$reject_link, 
				'{ticket_link}'=>$ticket_link, 
				'{TicketID}'=>$Ticket->TicketID, 
				'{SubjectName}'=>$Ticket->SubjectName, 
				'{created_user_name}'=>$created_user_name, 
				'{customer_name}'=>$Ticket->CustName, 
				'{phone_number}'=> $Ticket->AccountID, 
				'{ticket_created_time}'=>date('H:i', strtotime($Ticket->CreationDateTime)), 
				'{created_date}'=>date('d-M-Y', strtotime($Ticket->CreationDateTime)) 
		);

		switch ($Ticket->TicketType) {
			case 0:
				$replacement['{phone_number}'] = '0'.$Ticket->AccountID;
				break;
			case 1: // mass full customer
				$groupInfo = Group_Member($Ticket->GroupId);
				$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $groupInfo['GroupDetails']['MemberAmount'] . ')';
				break;
			case 2: //mass selected
				$replacement['{phone_number}'] = 'Multiple numbers ('. (int) $this->st->countMassSelectedSubs($Ticket->TicketID) . ')';
				break;
		}
		
		/*
		 * Notify ST created
		 */
		if (($ticketCreatedNotify = $this->st->getSubjectNotifyByType($Ticket->SubjectID, 1)) ) {
			
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($ticketCreatedNotify['role_ids'], $Ticket->CompanyID);
			$recipients = array_column($notifyUsers, 'email', 'user_id'); //extract recipiens email
			if ($notifyUsers) {
				/*
				 * Prepare Ticket History
				 */
				$data['filteredTickets'] = $this->st->getTicketChangeLog($TicketID);
				$progress_history = $this->load->view('kyc/history_email', $data, true);
				
				$replacement['{progress_comment}'] = $progress_history;
				
				/*
				 * keep alert for each recipients
				 */
				foreach ($recipients as $user_id => $notifyUser) {
					$this->st->createAlert(array (
							'NotifyID' => $ticketCreatedNotify['ID'],
							'TicketID' => $Ticket->TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
				
				$subject = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['MailText']);
				
				$this->sendNotifiyMail($recipients, $subject, $body);
			}
			
			
		} // END Notify ST created
		
		
		/*
		 * Notify ST To approve
		 */
		if (($ticketCreatedNotify = $this->st->getSubjectNotifyByType($Ticket->SubjectID, 2)) ) {
				
			$approveUsers = $this->st->GetApproveUserNotifyBySubject($Ticket->SubjectID);
			
			if ($approveUsers) {

				foreach ($approveUsers as $key => $item) {
						
					// keep users to approve with approve status 0
					$approveHistoryIn = array(
							'NotifyID' => $item['NotifyID'],
							'TicketID' => $TicketID,
							'ApproveID' => $item['ApproveID'],
							'user_id' => $item['user_id']
					);
	
					/*
					 * Only for first element
					*/
					if ($key == '0') {
						$approveHistoryIn['flag'] = '1';
							
						$this->st->createAlert(array(
								'NotifyID' => $item['NotifyID'],
								'TicketID' => $TicketID,
								'StaffID' => $item['user_id'],
								'EmailSent'	=> '1',
								'SentAt' => date('Y-m-d H:i:s')
						));
							
							
						$approve_user_name = $item['name'];
						/*
						 * Prepare Ticket History
						*/
						$data['filteredTickets'] = $this->st->getTicketChangeLog($TicketID);
						$progress_history = $this->load->view('kyc/history_email', $data, true);
							
						$action_user_name = $this->visitor? $this->visitor: $this->staff['name'];
						$created_user_name = $Ticket->InitiatorType == 1? $Ticket->CreatedByID : $Ticket->whoCreateName;
	
						$replacement['{approve_user_name}'] = $approve_user_name;
						$replacement['{action_user_name}'] = $action_user_name;
						$replacement['{reject_reason}'] = '';
						$replacement['{progress_comment}'] = $progress_history;
	
						$subject = str_replace(array_keys($replacement), array_values($replacement), $item['Title']);
						$body = str_replace(array_keys($replacement), array_values($replacement), $item['MailText']);
						
						$this->sendNotifiyMail($item['email'], $subject, $body);
					}
	
					$this->st->createApproveHystory($approveHistoryIn);
				}
	
				/*
				 * Interrupt send more notify, bec. ticket waiting to approve
				 */
				return true;
			}
				
		} // END Notify To approve
		
		
		/*
		 * Notify ST Ready for execution or processing for complaint
		 */
		if (($ticketCreatedNotify = $this->st->getSubjectNotifyByType($Ticket->SubjectID, 4)) ) {
				
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($ticketCreatedNotify['role_ids'], $Ticket->CompanyID);
			$recipients = array_column($notifyUsers, 'email', 'user_id'); //extract recipiens email
			if ($notifyUsers) {
				/*
				 * Change status of serviceticket to
				 * for request: ready to execution
				 * for complain subject type: 2 - precessing
				 * for customer sow status : 2 - processing
				 */
				if ($Ticket->TypeID == 1) {
					$updateTicketParam = array( 'StatusID' => 2, 'forCustStatusID'=>2, 'LastEditDate' => 'NOW()');
				} else {
					$updateTicketParam = array( 'StatusID' => Servicetickets_model::$DefaultReadyExecutionStatus, 'forCustStatusID'=>2, 'LastEditDate' => 'NOW()');
				}
					
				$this->st->updateST($TicketID, $updateTicketParam);

				/*
				 * what notificatiocantion is preseted on current subject by ticket
				*/
// 				$NotifyRecipientID = $this->st->getNotifyRecipientID($ticketCreatedNotify['ID'], $ticketInfo['SubjectID'], $ticketCreatedNotify['role_ids']);
					
				foreach ($recipients as $user_id => $notifyUser) {
					// keep users to execute history
					$this->st->createExecuteHystory(array(
							'NotifyID' => $ticketCreatedNotify['ID'],
							'TicketID' => $TicketID,
							'user_id' => $user_id
					));

					// keep site alert for first user, bec. he receive an email
					$this->st->createAlert(array(
							'NotifyID' => $ticketCreatedNotify['ID'],
							'TicketID' => $TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
					
				$data['filteredTickets'] = $this->st->getTicketChangeLog($TicketID);
				$progress_history = $this->load->view('kyc/history_email', $data, true);
					
				/*
				 * Make replacement array
				*/
				$replacement['{progress_comment}'] = $progress_history;
				
				$subject = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['MailText']);
		
				$this->sendNotifiyMail($recipients, $subject, $body);
			}
				
				
		} // END Notify ST Ready for execution or processing for complaint
		
		//Notify ST to close
		elseif (($ticketCreatedNotify = $this->st->getSubjectNotifyByType($Ticket->SubjectID, 6)) ) 
		{
				
			$notifyUsers = $this->Staff_mod->searchUsersByRolesAndCust($ticketCreatedNotify['role_ids'], $Ticket->CompanyID);
			$recipients = array_column($notifyUsers, 'email', 'user_id'); //extract recipiens email
			if ($notifyUsers) {
				/*
				 * Change status of serviceticket to
				 * for request: ready to execution
				 * for complain subject type: 2 - precessing
				 * for customer sow status : 2 - processing
				 */
				if ($Ticket->TypeID == 1) {
					$updateTicketParam = array( 'StatusID' => 2, 'forCustStatusID'=>2, 'LastEditDate' => 'NOW()');
				} else {
					$updateTicketParam = array( 'StatusID' => Servicetickets_model::$DefaultReadyExecutionStatus, 'forCustStatusID'=>2, 'LastEditDate' => 'NOW()');
				}
					
				$this->st->updateST($TicketID, $updateTicketParam);

				foreach ($recipients as $user_id => $notifyUser) {
					// keep site alert for first user, bec. he receive an email
					$this->st->createAlert(array(
							'NotifyID' => $ticketCreatedNotify['ID'],
							'TicketID' => $TicketID,
							'StaffID' => $user_id,
							'EmailSent'	=> '1',
							'SentAt' => date('Y-m-d H:i:s')
					));
				}
					
				$data['filteredTickets'] = $this->st->getTicketChangeLog($TicketID);
				$progress_history = $this->load->view('kyc/history_email', $data, true);
					
				/*
				 * Make replacement array
				*/
				$replacement['{change_history}'] = $progress_history;
				$replacement['{progress_comment}'] = '';
				$replacement['{subject_type}'] = $Ticket->TypeName;
				$replacement['{action_user_name}'] = $this->staff['name'];
				
				$subject = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['Title']);
				$body = str_replace(array_keys($replacement), array_values($replacement), $ticketCreatedNotify['MailText']);
		
				$this->sendNotifiyMail($recipients, $subject, $body);
			}
				
				
		} // END Notify ST to close
		
		// 		die('done ticket #'.$ticketInfo['TicketID']);
	}
	
	private function sendNotifiyMail($recipients, $subject, $body) 
	{
		$from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Care');
		$to = ($this->debugEmail)? $this->debugEmail : $recipients;
		
		if(send_email($from, $to, $subject, $body, true) == false) {
			$this->selfcare_mod->save_log('Email notify not send.');
		}
	}
	
	public function check_new_phone()
	{
		$new_phone = $this->input->post('new_phone', true);
		$TicketType = $this->input->post('TicketType');
		
		if($TicketType == '3') {
			$new_phone = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $new_phone));
			
			if (!$new_phone) {
				$this->form_validation->set_message('check_new_phone', "Subscriber phone number can't be empty!" );
				return false;
			}

			/* if searched user is urrent user, sotp request ruturn true */
			if (isset($this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo']) 
					&& $new_phone == $this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo']) return true;
			
			/* get all staff customers profile*/
			$staffCustomers = $this->Customer_model->getCustomers(array_keys($this->staff['companies']));
			
			/* get CustId from ngbss related to the input phone number */
			$CustId = GetCorpCustomerData($new_phone, $onlyCustId = true);

			/* search CustId in staff array */
			$custArrayKey = array_search($CustId, array_column($staffCustomers, 'CustId'));
// 			var_dump($CustId, $custArrayKey, $staffCustomers);die;
			if (!$CustId || ($CustId && false == key_exists($custArrayKey, $staffCustomers))) {
				$this->form_validation->set_message('check_new_phone', "Subscriber information by 0{$new_phone} was not found!");
				return false;
			}
			
			/* 
			 * Subscriber found, must load information
			 */
			$current_customer = $staffCustomers[$custArrayKey];
			
			/*
			 * Replace session customer information if is diferent than current session
			 */
			if (isset($this->session->userdata('current_customer')['CustId']) && $current_customer['CustId'] != $this->session->userdata('current_customer')['CustId']) {
				$current_customer['Groups_Info'] = Group_Member($current_customer['GroupId']) or array();
				$this->session->set_userdata('current_customer', $current_customer);
			}
			
			$current_subscriber = $this->subscriber->loadSubscriber($new_phone);
			
			$_POST['TicketType'] = '0';
			
			$this->session->set_userdata('current_subscriber', $current_subscriber);
		}

		return true;
	}
	
	public function check_msubchoosed($val)
	{
		$msubchoosed = $this->input->post('msubchoosed');
		$TicketType = $this->input->post('TicketType');

		if($TicketType == '2' && !$msubchoosed){
			$this->form_validation->set_message('check_msubchoosed', 'You are not choosed any subscriber.');
			return false;
		 } else {
			 return true;
		 } 
	}
	
}// end Servicetickets class