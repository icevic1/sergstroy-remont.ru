<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Servicetickets extends Admin_Controller 
{
	var $page_id = 36;
	private $def_viw_path = 'admin/servicetickets/';
	
	function __construct() 
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Staff_mod');
		$this->load->model('Servicetickets_model', 'st');
		$this->load->model('Masteracc_model');
	}
	
	function index()
	{
		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['menus_page'] = $this->selfcare_mod->get_page_menu($this->login_name, $this->page_id);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['users'] = $this->selfcare_mod->get_users();
		$data['CONTENT'] = $this->def_viw_path.'index';
		$this->load->view('template/tmpl_admin',$data);
	}
	
	function subjects()
	{
	
		$data['subjects'] = $this->st->GetSubjects();
// 	var_dump($data['subjects']);
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 37);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'subjects';
		$this->load->view('template/tmpl_admin', $data);
	}

	public function editSubject($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$data['subject'] = $this->st->getSubjectByID($ID, true);
		if ($data['subject']) {
			$data['approveentities'] = $this->st->GetApproveentities($ID);
			$data['executiveentities'] = $this->st->GetExecutiveentities($ID);
			$data['notifications'] = $this->st->GetSubjectNotifications($ID);
			$data['loadedQuestions'] = array_column($this->st->getSubjecQuestions($ID), 'QTitle', 'QOrder');
		}
		
// 		var_dump( $data['loadedQuestions']);die;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('SubjectID', 'SubjectID', 'trim|numeric');
			$this->form_validation->set_rules('SubjectName', 'Service Type Name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('SubjectDescription', 'Description', 'trim');
			$this->form_validation->set_rules('TypeID', 'Ticket Type', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('GroupID', 'Service Group', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('close_role_id', 'Closure entity', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('DefSeverityID', 'Default Severity', 'trim|is_natural');
			$this->form_validation->set_rules('DefPriorityID', 'Default Priority', 'trim|is_natural');
			$this->form_validation->set_rules('KpiTimeHours', 'KPI Time', 'trim|required|is_natural_no_zero');
			
			if ($this->form_validation->run() != FALSE)	{
				$form_SubjectID = $this->form_validation->set_value('SubjectID');
				
				$data_update = array(
						'SubjectName' => $this->form_validation->set_value('SubjectName'),
						'SubjectDescription' => $this->form_validation->set_value('SubjectDescription'),
						'TypeID' => (int)$this->form_validation->set_value('TypeID'),
						'GroupID' => (int)$this->form_validation->set_value('GroupID'),
						'close_role_id' => (int)$this->form_validation->set_value('close_role_id'),
						'DefSeverityID' => ($this->form_validation->set_value('DefSeverityID')?$this->form_validation->set_value('DefSeverityID'): null),
						'DefPriorityID' => ($this->form_validation->set_value('DefPriorityID')?$this->form_validation->set_value('DefPriorityID'): null),
						'KpiTimeHours' => $this->form_validation->set_value('KpiTimeHours'),
					);
				
				/**
				 * try if SubjectID persist, then update Subject with form data 
				 */
				if ($form_SubjectID) {
					$afftectedRows = $this->st->updateSubject($form_SubjectID, $data_update);
				} else {
					$ID = $this->st->addSubject($data_update);
				}
				
				/**
				 * Keep approve order configuratioin for
				 */
				
				if ($ID) {
					$inputApproveentities = array_filter($this->input->post('approveentities'));
					$initialApproveentities = $this->st->GetSimpleApproveentitiesBySubject($ID);
					$newCount = count($inputApproveentities);
					$iniCount = count($initialApproveentities);
					
					if ($inputApproveentities)
					foreach ($inputApproveentities as $order=>$role_id) {
						$this->st->saveApproveentity($ID, $role_id, $order);
					}
					
					/*
					 * If initial approve entity count is more that newest, then remove its from DB
					 */
					if($iniCount > 0 && $iniCount > $newCount) {
						$toDelete = $newCount - 1;
// 						var_dump($iniCount, $newCount, $toDelete);die;
						$this->st->deleteSubjectApproveentity($ID, $toDelete);
					}
					
					$Questions = $this->input->post('Questions');
					$QuestionsNew = array_filter($Questions);
					$QuestionsToDelete = array_diff_key($Questions, $QuestionsNew);
					
					if ($QuestionsNew)
					foreach ($QuestionsNew as $order=>$QTitle) {
						$this->st->saveSubjectQuestion($ID, $QTitle, $order);
					}
					
					if ($QuestionsToDelete)
					$this->st->deleteSubjectQuestion($ID, array_keys($QuestionsToDelete));
// 					var_dump($Questions, $QuestionsNew , array_diff_key($Questions, $QuestionsNew));die;
				}
				
				/**
				 * Keep ticket executant order configuratioin for
				 */
				$inputExecutiveentitiess = array_filter($this->input->post('executiveentities'));
// 				var_dump($inputExecutiveentitiess);die;
				if ($ID) {
					$this->st->deleteSubjectExecutiveentity($ID);
					if ($inputExecutiveentitiess)
					foreach ($inputExecutiveentitiess as $order=>$role_id) {
						$this->st->addExecutiveentity(array('SubjectID'=>$ID, 'role_id'=>$role_id, 'executant_order'=>$order));
					}
				}
				
				redirect($this->def_viw_path . 'editSubject/'.$ID);
			} else {
				$data['subject'] = $this->input->post();
				$data['approveentities'] = $this->input->post('approveentities');
				$data['executiveentities'] = $this->input->post('executiveentities');
				$data['loadedQuestions'] = $this->input->post('Questions');
			}
		}
		
		$data['st_types'] = $this->st->GetTypes(true);
		$data['subjectTypes'] = array_replace(array('0' => '---'), $data['st_types']);
		$data['subjectGroups'] = array_replace(array('0' => '---'), $this->st->GetGroups(true));
		$data['subjectPriorities'] = array_replace(array('0' => '---'), $this->st->GetPriorities(true));
		$data['subjectSeverities'] = array_replace(array('0' => '---'), $this->st->GetSeverities(true));
		$data['roles'] = array_replace(array(''=>'...'), Acl::simpleRoleArray());
		
		
		
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 37);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editSubject';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteSubject($ID = null)
	{
		if ($ID) {
			$this->st->deleteSubject($ID);
		}
	
		redirect($this->def_viw_path.'subjects/');
	}
	
	function statuses()
	{
		$data['statuses'] = $this->st->GetStatuses();
// 			var_dump($data['statuses']);
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 38);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'statuses';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editStatus($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['status'] = $this->st->GetStatusByID($ID, true);
// 		var_dump($data['status']);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
			$this->form_validation->set_rules('StatusID', 'StatusID', 'trim|numeric');
			$this->form_validation->set_rules('StatusName', 'Status Name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('StatusDescription', 'Status Description', 'trim');
				
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('StatusID');
	
				$data_update = array(
					'StatusName' => $this->form_validation->set_value('StatusName'),
					'StatusDescription' => $this->form_validation->set_value('StatusDescription'),
				);
	
				/**
				 * try if StatusID persist, then update Subject with form data
				*/
				if ($form_ID) {
					$afftectedRows = $this->st->updateStatus($form_ID, $data_update);
				} else {
					$this->st->addStatus($data_update);
				}
	
				redirect($this->def_viw_path . 'statuses/');
			} else {
				$data['status'] = $this->input->post();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 38);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editStatus';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteStatus($ID = null)
	{
		if ($ID) {
			$this->st->deleteStatus($ID);
		}
	
		redirect($this->def_viw_path.'statuses/');
	}
	
	function subjectTypes()
	{
		$data['types'] = $this->st->GetTypes();
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 39);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'subjectTypes';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editSubjectType($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['SubjectType'] = $this->st->GetSubjectTypeByID($ID, true);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('TypeID', 'TypeID', 'trim|numeric');
			$this->form_validation->set_rules('TypeName', 'TypeName', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('TypeDescription', 'TypeDescription', 'trim');
	
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('TypeID');
	
				$data_update = array(
						'TypeName' => $this->form_validation->set_value('TypeName'),
						'TypeDescription' => $this->form_validation->set_value('TypeDescription')
				);
	
				if ($form_ID) {
					$afftectedRows = $this->st->updateSubjectType($form_ID, $data_update);
				} else {
					$this->st->addSubjectType($data_update);
				}

				redirect($this->def_viw_path . 'subjectTypes/');
			} else {
				$data['status'] = $this->input->post();
			}
		}
		
		$data['severities'] = array_replace(array(''=>'---'), $this->st->GetSeverities(true));
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 39);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editSubjectType';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteSubjectType($ID = null)
	{
		if ($ID) {
			$this->st->deleteSubjectType($ID);
		}
	
		redirect($this->def_viw_path.'subjectTypes/');
	}
	
	function severities()
	{
		$data['severities'] = $this->st->GetSeverities();
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 40);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'severities';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editSeverity($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['loadedItem'] = $this->st->GetSeverityByID($ID, true);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('SeverityID', 'SeverityID', 'trim|numeric');
			$this->form_validation->set_rules('SeverityName', 'TypeName', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('SeverityDescription', 'SeverityDescription', 'trim');
	
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('SeverityID');
	
				$data_update = array(
						'SeverityName' => $this->form_validation->set_value('SeverityName'),
						'SeverityDescription' => $this->form_validation->set_value('SeverityDescription'),
				);
				
				if ($form_ID) {
					$afftectedRows = $this->st->updateSeverity($form_ID, $data_update);
				} else {
					$this->st->addSeverity($data_update);
				}
	
				redirect($this->def_viw_path . 'severities/');
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 40);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editSeverity';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteSeverity($ID = null)
	{
		if ($ID) {
			$this->st->deleteSeverity($ID);
		}
	
		redirect($this->def_viw_path.'severities/');
	}
	
	function priorities()
	{
		$data['allItems'] = $this->st->GetPriorities();
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 41);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'priorities';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editPriority($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['loadedItem'] = $this->st->GetPriorityByID($ID, true);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('PriorityID', 'PriorityID', 'trim|numeric');
			$this->form_validation->set_rules('PriorityName', 'Priority Name', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('PriorityDescription', 'Priority Description', 'trim');
	
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('PriorityID');
	
				$data_update = array(
						'PriorityName' => $this->form_validation->set_value('PriorityName'),
						'PriorityDescription' => $this->form_validation->set_value('PriorityDescription'),
				);
				
				if ($form_ID) {
					$afftectedRows = $this->st->updatePriority($form_ID, $data_update);
				} else {
					$this->st->addPriority($data_update);
				}
	
				redirect($this->def_viw_path . 'priorities/');
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 41);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editPriority';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deletePriority($ID = null)
	{
		if ($ID) {
			$this->st->deletePriority($ID);
		}
	
		redirect($this->def_viw_path.'priorities/');
	}
	
	function notifications()
	{
		$data['allItems'] = $this->st->GetNotifications();
// 		var_dump($data['allItems']);
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 42);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'notifications';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editSubjectNotify($SubjectID = null, $ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$data['loadedItem'] = $this->st->GetNotifyByID($ID, true);
		if ($data['loadedItem']) {
			$NotifyID = $data['loadedItem']['ID'];
			$data['loadedItem']['roles'] = $this->st->getNotifyRoles($NotifyID, 'values');
		} else {
			$data['loadedItem']['SubjectID'] = $SubjectID;
		}
		
// 	var_dump($SubjectID, $ID );
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('ID', 'ID', 'trim|numeric');
			$this->form_validation->set_rules('SubjectID', 'Subject', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('roles[]', 'Roles', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('AlertSite', 'AlertSite', 'trim|required|is_natural');
			$this->form_validation->set_rules('Type', 'Notification Type', 'trim|is_natural');
			$this->form_validation->set_rules('AlertEmail', 'AlertEmail', 'trim|required|is_natural');
			$this->form_validation->set_rules('Title', 'Title', 'trim|required|min_length[2]|max_length[200]');
			$this->form_validation->set_rules('SiteText', 'Site notify text', 'trim');
			$this->form_validation->set_rules('MailText', 'Mail notify body', 'trim');

// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('ID');
				$inputRoles = $this->input->post('roles');
	
				$data_update = array(
						'SubjectID' => $this->form_validation->set_value('SubjectID'),
						'Type' => $this->form_validation->set_value('Type'),
						'AlertSite' => $this->form_validation->set_value('AlertSite'),
						'AlertEmail' => $this->form_validation->set_value('AlertEmail'),
						'Title' => $this->form_validation->set_value('Title'),
						'SiteText' => $this->form_validation->set_value('SiteText'),
						'MailText' => $this->form_validation->set_value('MailText'),
				);
	
				if ($form_ID) {
					$afftectedRows = $this->st->updateNotify($form_ID, $data_update);
				} else {
					$NotifyID = $this->st->addNotify($data_update);
				}
				
				
				/**
				 * clean all notify roles before new population
				 */
				$deleteResult = $this->st->deleteNotifyRoles($NotifyID, null);
// 				var_dump($deleteResult, $NotifyID, $inputRoles);die;
				foreach ($inputRoles as $roleItem) {
					if (Acl::getSimpleRoleById($roleItem) == false) continue;
					$this->st->insertNotifyRoles(array('NotifyID'=>$NotifyID, 'role_id'=>$roleItem));
				}
	
				redirect($this->def_viw_path . 'editSubject/'.$SubjectID);
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
		
		$data['subjects'] = array_replace(array(''=>'---'), $this->st->GetSubjects(null, true));
		$data['roles'] = array_replace(array('0' => '---'), Acl::simpleRoleArray());
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 42);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editNotify';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function set_notify_recipients($SubjectID = null, $NotifyID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$data['loadedItem']['SubjectID'] = $SubjectID;
		$data['loadedItem']['NotifyID'] = $NotifyID;
		$SubjectNotifyRoles = $this->st->GetSubjectNotifyRoles($SubjectID, $NotifyID);
		$data['loadedItem']['roles'] = $SubjectNotifyRoles? array_column($SubjectNotifyRoles, 'role_id') : array();
		
// 	var_dump($SubjectID, $NotifyID, $data['loadedItem']['roles']);die;
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('NotifyID', 'ID', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('OldNotifyID', 'ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('SubjectID', 'Subject', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('roles[]', 'Roles', 'trim|required');

// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() != FALSE)	{
				$OldNotifyID = $this->form_validation->set_value('OldNotifyID');
				$NotifyID = $this->form_validation->set_value('NotifyID');
				$inputRoles = $this->input->post('roles');
				$SubjectID = $this->form_validation->set_value('SubjectID');
	
				/**
				 * clean all notify roles before new population
				 */
				$deleteResult = $this->st->deleteNotifyRoles($OldNotifyID, $SubjectID);
// 				var_dump($deleteResult,$OldNotifyID, $NotifyID,$SubjectID, $inputRoles);die;
				foreach ($inputRoles as $roleItem) {
					if (Acl::getSimpleRoleById($roleItem) == false) continue;
					$this->st->insertNotifyRoles(array('NotifyID'=>$NotifyID, 'SubjectID'=>$SubjectID , 'role_id'=>$roleItem));
				}

				redirect($this->def_viw_path . 'editSubject/'.$SubjectID);
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
		
		$data['notifyList'] = array_replace(array('' => '...'), $this->st->GetNotifications(true));
		$data['roles'] = array_replace(array(''=>'...'), Acl::simpleRoleArray());
// 	var_dump($data['notifyList']);die;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 42);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'set_notify_recipient';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function editNotify($ID = null, $SubjectID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$data['loadedItem'] = $this->st->GetNotifyByID($ID, true);
		if ($data['loadedItem']) {
			$NotifyID = $data['loadedItem']['ID'];
			$data['loadedItem']['roles'] = $this->st->getNotifyRoles($NotifyID, 'values');
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('ID', 'ID', 'trim|numeric');
			$this->form_validation->set_rules('Type', 'Notification Type', 'trim|is_natural');
			$this->form_validation->set_rules('Title', 'Title', 'trim|required|min_length[2]|max_length[200]');
			$this->form_validation->set_rules('SiteText', 'Site notify text', 'trim|required|min_length[2]');
			$this->form_validation->set_rules('MailText', 'Mail notify body', 'trim');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('ID');
				$inputRoles = $this->input->post('roles');
	
				$data_update = array(
						'Type' => $this->form_validation->set_value('Type'),
						'Title' => $this->form_validation->set_value('Title'),
						'SiteText' => $this->form_validation->set_value('SiteText'),
						'MailText' => $this->form_validation->set_value('MailText'),
				);
	
				if ($form_ID) {
					$afftectedRows = $this->st->updateNotify($form_ID, $data_update);
				} else {
					$NotifyID = $this->st->addNotify($data_update);
				}
				
				/**
				 * clean all notify roles before new population
				 */
				$this->st->deleteNotifyRoles($NotifyID, null);
				foreach ($inputRoles as $roleItem) {
					if (Acl::getSimpleRoleById($roleItem) == false) continue;
					$this->st->insertNotifyRoles(array('NotifyID'=>$NotifyID, 'role_id'=>$roleItem));
				}
	
				redirect($this->def_viw_path . 'notifications/');
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
		
		$data['subjects'] = array_replace(array(''=>'---'), $this->st->GetSubjects(null, true));
		$data['roles'] = array_replace(array('0' => '---'), Acl::simpleRoleArray());
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 42);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editNotify';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteNotify($ID = null)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$ID = $this->input->post('ID');
			$SubjectID = $this->input->post('SubjectID');
			$this->st->deleteNotifyRoles($ID, $SubjectID);
		} else {
			$this->st->deleteNotify($ID);
			redirect($this->def_viw_path.'notifications/');
		}
	}
	
	function approveentities()
	{
		$data['allItems'] = $this->st->GetApproveentities();
// 				var_dump($data['allItems']);
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 43);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'approveentities';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	function editApproveentity($GETSubjectID = null, $GETID = null)
	{
		$SubjectID = null;
		if ($GETSubjectID) {
			$subjectDetails = $this->st->getSubjectByID($GETSubjectID, $asArray = true);
			if ($subjectDetails) {
				$data['loadedSubject'] = $subjectDetails;
				$SubjectID = $subjectDetails['SubjectID'];
			} else {
				show_error("Service ticket subject <b>{$GETSubjectID}</b> was not found!", $status_code= 500 );
			}
		}
		
		$ID = null;
		$page_action = 'Add new Approve entity';
		if ($GETID) {
			$entityDetails = $this->st->GetApproveentityByID($GETID, $asArray = true);
			if ($entityDetails) {
				$data['loadedItem'] = $entityDetails;
				$ID = $entityDetails['ID'];
				$page_action = 'Edit Approve entity';
			} else {
				show_error("Approve entity <b>{$GETID}</b> was not found!", $status_code= 500 );
			}
		}
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$inputData = $this->input->post('approveentity');
			$this->form_validation->set_rules('approveentity[ID]', 'Edit ID', 'trim|numeric');
			$this->form_validation->set_rules('approveentity[SubjectID]', 'Service Type', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('approveentity[role_id]', 'Role', "trim|required|is_natural_no_zero");
			$this->form_validation->set_rules('approveentity[approve_order]', 'Approve order', "trim|required|is_natural");
				
			$this->form_validation->set_message('is_natural_no_zero', 'The %s field need be choosed!');
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
// 			var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
			if ($this->form_validation->run() != FALSE)	{
	
				/**
				 * add new or Update existing candidate
				 */
				if ($entityDetails) {
					$this->st->updateApproveentity($entityDetails['ID'], $inputData);
				} else {
					$ID = $this->st->addApproveentity($inputData);
				}
	
				if ($this->input->post('stayonpage')) redirect('admin/servicetickets/editApproveentity/'.$inputData['SubjectID']);
				else redirect('admin/servicetickets/approveentities/');
			} else {
				$data['loadedItem'] = $this->input->post('approveentity');
			}
		}
	
// 		$choosedTFields = array(); //array(''=>'---');
// 		if (!empty($data['loadedItem']['tableName'])) {
// 			$tableName = $data['loadedItem']['tableName'];
// 			$tableFields = $this->Filter_mod->getTableFieldsList($tableName);
				
// 			foreach ($tableFields as $item) {
// 				if ('siteID' == $item || ($tableName != 'smi_sites' && 'siteName' == $item)) continue;
// 				$choosedTFields[$item] = ucfirst(trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $item)));
// 			}
	
// 		}

		$data['relItems'] = ($SubjectID)?$this->st->GetApproveentities($SubjectID, $exclID = $ID):null;
	
		$data['subjects'] = array_replace(array(''=>'---'), $this->st->GetSubjects($Type = null, true));
		$data['roles'] = array_replace(array('0' => '---'), Acl::simpleRoleArray($full = false));

		$data['navBackLink'] = site_url('admin/servicetickets/approveentities/');
		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, 43);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		$data['CONTENT'] = $this->def_viw_path.'editApproveentity';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	public function deleteApproveentity($ID = null)
	{
		if ($ID) $this->st->deleteApproveentity($ID);
	
		redirect('admin/servicetickets/approveentities/');
	}
	
	/**
	 * Service group action: show all groups
	 */
	function groups()
	{
		$data['groups'] = $this->st->GetGroups();
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 44);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'groups';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	/**
	 * Edit service group action or add new
	 */
	public function editGroup($ID = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	
		$data['loadedItem'] = $this->st->GetGroupByID($ID, true);
	
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
			$this->form_validation->set_rules('GroupID', 'Group ID', 'trim|numeric');
			$this->form_validation->set_rules('GroupName', 'GroupName', 'trim|required|min_length[2]|max_length[100]');
			$this->form_validation->set_rules('GroupDescription', 'Description', 'trim');
	
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
			if ($this->form_validation->run() != FALSE)	{
				$form_ID = $this->form_validation->set_value('GroupID');
	
				$data_update = array(
						'GroupName' => $this->form_validation->set_value('GroupName'),
						'GroupDescription' => $this->form_validation->set_value('GroupDescription'),
				);
	
				if ($form_ID) {
					$afftectedRows = $this->st->updateGroup($form_ID, $data_update);
				} else {
					$this->st->addGroup($data_update);
				}
	
				redirect($this->def_viw_path . 'groups/');
			} else {
				$data['loadedItem'] = $this->input->post();
			}
		}
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, 44);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT'] = $this->def_viw_path.'editGroup';
		$this->load->view('template/tmpl_admin', $data);
	}
	
	/**
	 * Delete service group action
	 * @param IN string $ID
	 */
	public function deleteGroup($ID = null)
	{
		if ($ID) {
			$this->st->deleteGroup($ID);
		}
	
		redirect($this->def_viw_path.'groups/');
	}
}