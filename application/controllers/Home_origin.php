<?php
class Home_origin extends Site_Controller 
{
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->config('my_config');
		$this->load->helper('webservice');
		$this->load->helper('ngbssquery');
		$this->load->helper('pagination');
		$this->load->library('cryptastic');
		$this->load->model('Selfcare_mod');
		$this->load->library('Acl');
		$this->load->library('Auth');
		$this->load->model('Staff_mod', 'staff');
		$this->load->model('Subscriber_mod', 'subscriber');
		$this->load->model('Servicetickets_model', 'st');
		$this->load->helper('text');

		/*
		 * If have access only to sts redirect to sts
		*/
		if (false == $this->acl->can_view(null, 1 /*1 Main page*/) && $this->acl->can_view(null, 3 /*3 STS*/)) {
			redirect('servicetickets/index');
				
		/*
		 * Not have permision to any content, show error
		*/
		} elseif (false == $this->acl->can_view(null, 1 /*1 Main page*/) && false == $this->acl->can_view(null, 3 /*3 STS*/)) {
			show_error("You haven't permission to see this page", $status_code= 503, 'Permission denied');
		}
	}
	
	function index()
	{
		$this->load->model('Customer_model');	
		$this->load->helper('number');
		$this->load->library('Aes');
		
// 		var_dump(strpos(strtolower('Free_On_net_SMS'), 'sms') !== false);die;
// 		$Balance_Info = Service_Info('15400926');
// 		var_dump($Balance_Info);die;
		
		
// 		$this->aes->setKey($this->config->item('aes_key'));
// 		$this->aes->setBlockSize($this->config->item('aes_size'));
// 		$this->aes->setData('123456');
// 		$encryptedPassword = $this->aes->encrypt();
//98630372 10206198  hasan: 968576334 my: 963602313
// 		
//'20151201000000'
// 		echo date('Ymd000000', strtotime('+1 month', strtotime('20151101000000')));
// 		10206205

// 		var_dump(ChangeSubscriberPassword('963602313', '395855-'));die;
// 		var_dump(GetCorpCustomerData('98630372', true));die;
// 		var_dump(Subscriber_Info('10212648'));
// 		var_dump( Balance_Info('10212660'));die;
// 		var_dump( Group_Member('211016129393', 1, 48));die;

		//var_dump(send_sms('963602313', 'test'));die;

// 		var_dump(send_sms('963602313', 'test'));die;
		
		$staff_company_subscribers = array();
		$staff_company_id = null;
		$current_subscriber = null;
		
		$current_memberpage = $this->session->userdata('current_memberpage')? $this->session->userdata('current_memberpage'): 1;

		if ($this->session->userdata('staff') && false == $this->session->userdata('visitor')) {
			
			if (isset($this->session->userdata('staff')['companies'])) {
				$changeSubFlag = false;
				if (($this->input->get('CustId') && !isset($this->session->userdata('current_customer')['WebCustId'])) || ($this->input->get('CustId') && isset($this->session->userdata('current_customer')['WebCustId']) && $this->input->get('CustId') != $this->session->userdata('current_customer')['WebCustId'])) 
				{
					$active_WebCustId = $this->input->get('CustId');
					$current_customer = $this->Customer_model->getWebCustById($active_WebCustId);
					if ($current_customer && !array_key_exists($active_WebCustId, $this->session->userdata('staff')['companies'])) {
						show_404();
					}
					$staff_Group_Member = Group_Member($current_customer['GroupId']) or array();
					$current_customer['Groups_Info'] = $staff_Group_Member;
					$changeSubFlag = true;
					$this->session->set_userdata('current_customer', $current_customer);
					$this->session->set_userdata('current_memberpage', 1);
				} 
				elseif (isset($this->session->userdata('current_customer')['WebCustId'])) 
				{
					$active_WebCustId = $this->session->userdata('current_customer')['WebCustId'];
					$current_customer = $this->session->userdata('current_customer');
					$staff_Group_Member = $this->session->userdata('current_customer')['Groups_Info'];
				} 
				else 
				{
					$staff_companies = $this->session->userdata('staff')['companies'];
					$current_customer = $this->get_cust_with_members($staff_companies);
					
					if ($current_customer) {
						$changeSubFlag = true;
						$staff_Group_Member = $current_customer['Groups_Info'];
						$this->session->set_userdata('current_customer', $current_customer);
						$this->session->set_userdata('current_memberpage', 1);
					} else {
						show_error('You have none customers for administration<br />Please contact administrator or goto <a href="'.site_url('servicetickets').'">Service Ticket System</a>', $status_code = 404);
					}
				}

				if (!$this->session->userdata('current_subscriber') 
						|| ($this->input->get('subs_id') && (isset($this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo']) && $this->input->get('subs_id') != $this->session->userdata('current_subscriber')['Subscriber_Info']['PhoneNo'])) 
						|| ($changeSubFlag == true) 
						){

					if ($this->input->get('subs_id')) {
						$currentServiceNumber = $this->input->get('subs_id');
						
						$current_customer['Groups_Info'] = Group_Member($current_customer['GroupId'], $current_memberpage) or array();
						$this->session->set_userdata('current_customer', $current_customer);
						
					} else {
// 						$currentsubsmember = current($staff_Group_Member);

						$firstServiceNumber = null;
						if (isset($staff_Group_Member['MemberSubscriberList']) && is_array($staff_Group_Member['MemberSubscriberList'])) {
							$firstServiceNumber = key($staff_Group_Member['MemberSubscriberList']); //$currentsubsmember['MemberServiceNumber'];
						}
						
						$currentServiceNumber = $firstServiceNumber;
					}
					
					$current_subscriber = $this->subscriber->loadSubscriber($currentServiceNumber);
					
					if ($current_subscriber) {
						$this->session->set_userdata('current_subscriber', $current_subscriber);
					} else {
						$this->session->set_userdata('msg', "Sorry, the subscriber information are not loaded!");
					}
					
				} else {
					$current_subscriber = $this->session->userdata('current_subscriber');
				}
			}
			
			if( $this->acl->can_read(null, 12 /*Alert system*/)) {
				$data['countActiveAlerts'] = $countActiveAlerts = $this->st->countActiveAlerts($this->session->userdata('staff')['user_id']);
			}
			
			$data['customer_pics'] = $this->staff->getCustUsers($current_customer['WebCustId'], '1');
			$data['customer_kams'] = $this->staff->getCustUsers($current_customer['WebCustId'], '2');
// 			var_dump($this->session->userdata['staff']['roles'], $this->acl->can_read(null, 14 /*Corporate Sales Contacts*/));die;
		
		} else {
			$ServiceNumber = $this->session->userdata('visitor');
			$current_subscriber = $this->session->userdata('current_subscriber');
			$current_customer = $this->session->userdata('current_customer');
			
			if (false == $current_customer) {
				/* get CustId from ngbss related to the input phone number */
				$CustId = GetCorpCustomerData($ServiceNumber, $onlyCustId = true);
				$current_customer = $this->Customer_model->getCustById($CustId, 'array');
				if ($current_customer) $this->session->set_userdata('current_customer', $current_customer);
			}
			
			if (false == $current_subscriber) {
				$current_subscriber = $this->subscriber->loadSubscriber($ServiceNumber);
				if ($current_subscriber) $this->session->set_userdata('current_subscriber', $current_subscriber);
			}
			
			$data['customer_pics'] = $this->staff->getCustUsers($current_customer['WebCustId'], '1');
// 			var_dump($data['customer_pics']);die;
		}

		if (isset($current_customer['Groups_Info']['MemberSubscriberList']) && count($current_customer['Groups_Info']['MemberSubscriberList']) > 10) {
			$current_customer['Groups_Info']['MemberSubscriberList'] = array_combine(array_slice(array_keys($current_customer['Groups_Info']['MemberSubscriberList']), 0, 10), array_slice($current_customer['Groups_Info']['MemberSubscriberList'], 0, 10));
			$this->session->set_userdata('current_customer', $current_customer);
			$current_memberpage = 1;
			$this->session->set_userdata('current_memberpage', $current_memberpage);
		}
		
		$data['current_customer'] = $current_customer;
		$data['current_subscriber'] = $current_subscriber;
		$data['current_memberpage'] = $current_memberpage;
		
		if ($this->input->get('debug') == 'subs') var_dump($current_subscriber);
		if ($this->input->get('debug') == 'cust') var_dump($current_customer);
		if ($this->input->get('debug') == 'staff') var_dump($this->session->userdata('staff'));
		
		$current_offering_id = isset($current_subscriber['Tariff_Info']['OfferingId'])? $current_subscriber['Tariff_Info']['OfferingId']: null;
		$data['offer_plans'] = $this->Offer_model->getGroups($offer_type = 1, $current_offering_id);
		$data['offer_services'] = $this->Offer_model->getGroups($offer_type = 2);
// 		var_dump($data['offer_services'] );die;
// 		$GroupsOffers = $this->Offer_model->getGroupsOffers(15);

		//var_dump($this->acl->can_read(null, 9 /*Search subscribers*/));die;
		
		$data['PAGE_TITLE'] = "Home";
		$data['BODY_CLASS'] = "site";
		$data['block'] = $this->selfcare_mod->get_block(4);
		
		//$data['CONTENT']='home/index-smart';
		
		if ($this->input->get('debug') == 'def')
			$this->load->view('template/tmpl_site', $data);
		else 
			$this->load->view('layout/layout_site', $data);
	}
	
	protected function get_cust_with_members($staff_companies)
	{
		$this->load->model('Customer_model');
		$active_WebCustId = key($staff_companies);
		$current_customer = $this->Customer_model->getWebCustById($active_WebCustId);
	
		if ($current_customer) {
			/**
			 * commented when run without internet
			 */
			$staff_Group_Member = Group_Member($current_customer['GroupId']);
			$current_customer['Groups_Info'] = $staff_Group_Member;
		
			if (!$staff_Group_Member) {
				next($staff_companies);
				$current_customer = $this->get_cust_with_members($staff_companies);
			}
	
			return $current_customer;
		}
	}
	//===============REFRESH PAGE==================//
	function refresh()
	{
		$this->session->unset_userdata('current_subscriber');
		$this->session->unset_userdata('current_customer');
		$this->session->unset_userdata('current_memberpage');
		redirect('home');
	}
	
	//==============FUNCTION RANDOM==============//
	function random_string($length = 6) {  
		$str = "";  
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));  
		$max = count($characters) - 1;  
		for ($i = 0; $i < $length; $i++) {   
			$rand = mt_rand(0, $max);   
			$str .= $characters[$rand];  
		}  
		return $str; 
	}
	function validate_phonenumber($value){
		$value=str_replace('_','',trim($value));
		$value=str_replace(' ','',trim($value));
		if(strlen($value)<10){
			return false;
		}else{
			return $value;
		}
	}
	function print_test($res){
		echo '<pre>';
		print_r($res);
		echo '</pre>';
		exit;
	}

	function edit_password(){
		$data['cust']=$this->session->userdata('cust');
		$data['CONTENT']='account/edit_password';
		$data['block']=$this->selfcare_mod->get_block(2);
		$this->load->view('template/tmpl_site',$data);
	}
	function check_session_exp(){
		$exp=$this->session->userdata('customer')?0:1;
		echo json_encode(array($exp));
	}

	function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
		$reference_array = array();
		foreach($array as $key => $row) {
			$reference_array[$key] = $row[$column];
		}
		array_multisort($reference_array, $direction, $array);
	}

	function export_excel(){
		$this->load->library('excellib');
		$objReader = IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($this->reportspath."/template/template.xlsx");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$reportspath = realpath(APPPATH.'../public/tmp_report/');
		$objWriter->save($reportspath.'/Carddetails.xlsx');
	}
	
	/**
	 * get customer infomation
	 * @imput: int $staff_id
	 */
	public function ajax_customer_info()
	{
		$data['current_customer'] = $this->session->userdata('current_customer');
// 		var_dump($data['details']);die;
		$this->load->view('home/ajax_customer_info', $data);
	}
	
	/**
	 * all my new funcions
	 * @imput: int $staff_id
	 */
	public function ajax_pic_details()
	{
		$staff_id = $this->input->post('staff_id');
		$data['details'] = $this->staff->getPICByID($staff_id);
// 		var_dump($data['details']);die;
		$this->load->view('home/ajax_pic_details', $data);
	}
	
	/**
	 * get kam information
	 * @imput: int $user_id
	 */
	public function ajax_kam_details()
	{
		$data['details'] = $data['detail_list'] = array();
		$user_id = $this->input->post('user_id');
		
		if ($user_id) {
			$data['details'] = $this->staff->getKAMByID($user_id);
		} else {
			$data['detail_list'] = $this->staff->getCustUsers($this->session->userdata['current_customer']['WebCustId'], '2');
		}
// 			var_dump($data['details']);die;
		$this->load->view('home/ajax_kam_details', $data);
	}
	
	/**
	 * get offer details table
	 * @imput: int $web_offer_id
	 */
	public function ajax_offer_details()
	{
		$this->load->model('Offer_model');
		$web_offer_id = $this->input->get_post('webofferid');
		$offerDetails = $this->Offer_model->getOffer($web_offer_id);
		if ($offerDetails && !empty($offerDetails['remark'])) echo $offerDetails['remark'];
		else echo '<em>No data</em>';
	}
	
	/**
	 * get group additon information
	 * @imput: int $groupid
	 */
	public function ajax_offer_groupdetails()
	{
		$this->load->model('Offer_model');
		$groupid = $this->input->get_post('groupid');
		$offerDetails = $this->Offer_model->getGroup($groupid);
		if ($offerDetails && !empty($offerDetails['addition'])) echo $offerDetails['addition'];
		else echo '<em>No data</em>';
	}
	
	/**
	 * get group additon information
	 * @imput: int $groupid
	 */
	public function ajax_bills_details()
	{
		$WebCustId = $this->input->get_post('webcustid');
		$billtype = $this->input->get_post('billtype');
		//$data['QueryBalanceResult'] = $this->Balance_Info();
		//$data['BillInfo']= $this->Bill_Info();
		$data['WebCustId'] = $WebCustId;
		$data['billtype'] = $billtype;
		
		$current_subscriber = $this->session->userdata('current_subscriber');
		$data['current_subscriber'] = $current_subscriber;

		$this->load->view('home/ajax_bill_details', $data);
	}
	

	public function ajax_cdr_details()
	{
		$WebCustId = $this->input->get_post('webcustid');

		$data['WebCustId'] = $WebCustId;

		if ($this->input->post('date_from')) {
			$Startdate = date('YmdHis', strtotime($this->input->post('date_from')));
			$data['date_from'] = $this->input->post('date_from');
		} else {
			$Startdate =  date('YmdHis', strtotime('-30 days'));
			$data['date_from'] = date('Y-m-d', strtotime('-30 days'));
		}
		
		if ($this->input->post('date_to')) {
			$Enddate = date('YmdHis', strtotime($this->input->post('date_to')));
			$data['date_to'] = $this->input->post('date_to');
		} else {
			$Enddate =  date('YmdHis');
			$data['date_to'] = date('Y-m-d');
		}
		
		//var_dump($Startdate,$Enddate);
		$page = $this->uri->segment(3);
		$current_cdrpage = $page? $page: 1;
	    $perpage = 20;
	    
		$current_subscriber = $this->session->userdata('current_subscriber');
		$current_cdr = $this->cdr->loadCDR($current_subscriber['Subscriber_Info']['PhoneNo'],$current_cdrpage,$perpage,$Startdate,$Enddate); //$this->input->get('subs_id'));
		
		$data['current_cdrpage'] = $current_cdrpage;
		$data['current_subscriber'] = $current_subscriber;
		$data['current_cdr'] = $current_cdr;
 		//var_dump($current_cdr);die;
		$data['page'] = $page;
		$data['Startdate']=$Startdate;
		$data['Enddate']=$Enddate;
		$data['CDRAmount'] = $current_cdr['CDR_Info']['TotalCDRNum'];
	   
		$this->load->view('home/ajax_cdr_details', $data);
		
		
	}
	/*
	public function ajax_cdr_search()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//$sdate = $this->input->post('date_from');
			//	$edate = $this->input->post('date_to');
			$sdate= ($this->input->post('date_from'))? $this->input->post('date_from'): null;
			$edate = ($this->input->post('date_to'))? $this->input->post('date_to'): null;
		}
	
		if($sdate && $sdate==null){
			$Startdate =date('YmdHis', $sdate);
		}
		else
		{
			$Startdate = date('YmdHis', strtotime('-30 days'));
	
		}
	
		if($edate && $edate==null){
			$Enddate =date('YmdHis', $edate);
		}
		else
		{
			$Enddate = date('YmdHis');
	
		}
		//var_dump($Startdate,$Enddate);
		$page = $this->uri->segment(3);
		$current_cdrpage = $page? $page: 1;
		$perpage=20;
			
		//var_dump($Startdate,$Enddate);die;
		$current_subscriber = $this->session->userdata('current_subscriber');
		$current_cdr = $this->cdr->loadCDR($current_subscriber['Subscriber_Info']['PhoneNo'],$current_cdrpage,$perpage,$Startdate,$Enddate); //$this->input->get('subs_id'));
	
	
		$data['current_cdrpage'] = $current_cdrpage;
		$data['current_subscriber'] = $current_subscriber;
		$data['current_cdr'] = $current_cdr;
		//var_dump($current_cdr);die;
		$data['page'] = $page;
		$data['Startdate']=$Startdate;
		$data['Enddate']=$Enddate;
		$data['CDRAmount'] = $current_cdr['CDR_Info']['TotalCDRNum'];
	
	
		$this->load->view('home/ajax_cdr_details', $data);
	
	
	
	}
	*/
	
	/**
	 * get group members count
	 * @imput: int $groupid
	 */
	public function ajax_gmem_counter()
	{
		$this->load->model('Customer_model');
		
		$staff = $this->session->userdata('staff');
		
		if (false == isset($staff['mgroup_counter']) && false == $this->session->userdata('visitor')) {
		
			if (isset($staff['companies'])) {
				$staff_companies = $this->session->userdata('staff')['companies'];
			}
			
			if (isset($this->session->userdata('current_customer')['WebCustId']) ) {
				$WebCustId = $this->session->userdata('current_customer')['WebCustId'];
				$currGroupAmount = $this->session->userdata('current_customer')['Groups_Info']['GroupDetails']['MemberAmount'];
			}
			
			unset($staff_companies[$WebCustId]);
			
			$customers = $this->Customer_model->getCustomers(array_keys($staff_companies));
			
			$staff['mgroup_counter'] = array();
			$staff['mgroup_counter'][$WebCustId] = $currGroupAmount;
			if ($customers) {
				foreach ($customers as $item) {
					if (!$item['GroupId']) continue;
					$gmem_data = Group_Member($item['GroupId'], 1, 2); if (!$gmem_data) continue;
					$staff['mgroup_counter'][$item['WebCustId']] = $gmem_data['GroupDetails']['MemberAmount'];
				}
			}
			
			$this->session->set_userdata('staff', $staff);
		}
		
		echo json_encode($staff['mgroup_counter']);
	}
	
	function request11()
	{
// 		echo md5(time());die;
		/*
		$url=$this->config->item('server_ip').$this->config->item('get_drs');
		$params=array('CustomerNumber'=>$cust_number,'SubscriberNumber'=>$sub_number,'DRType'=>'PDR','StartDate'=>$start_date,'EndDate'=>$end_date,'PageNumber'=>$page_num);
		$res=request('SelfCare_GetDRs',$url,$params);
		 * */
		$ci=& get_instance();
		$ci->load->library("Nusoap_Lib");
		$ci->load->config('my_config');
		$ci->load->model('selfcare_mod');
		$soap_action = 'GetCorpCustomerData';// $ci->config->item('server_ip').'/acc/'.$method;
// 		$soap_action = 'QueryPurchasedSupplementaryOffering';// $ci->config->item('server_ip').'/acc/'.$method;
		$url = 'http://10.12.5.207:8060/crm/services/OrderQuery';
// 		$url = 'http://10.12.8.14:7080/custom/SELFCARE/HWBSS_Offering';

		$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:quer="http://crm.huawei.com/query/" xmlns:bas="http://crm.huawei.com/basetype/">
   <soapenv:Header/>
   <soapenv:Body>
      <quer:GetCorpCustomerDataRequest>
         <quer:RequestHeader>
            <bas:Version>1</bas:Version>
            <bas:TransactionId>2015100723595212454855054293229</bas:TransactionId>
            <bas:ProcessTime>20151007235952</bas:ProcessTime>
            <bas:ChannelId>28</bas:ChannelId>
            <bas:TechnicalChannelId>53</bas:TechnicalChannelId>
            <bas:TenantId>101</bas:TenantId>
            <bas:AccessUser>5060</bas:AccessUser>
            <bas:AccessPwd>d+7pCWDu8Dt7vxVMvEudvQ==</bas:AccessPwd>
         </quer:RequestHeader>
         <quer:GetCorpCustomerBody>
            <quer:CustomerId>1010001973495</quer:CustomerId>
         </quer:GetCorpCustomerBody>
      </quer:GetCorpCustomerDataRequest>
   </soapenv:Body>
</soapenv:Envelope>';
		
		var_dump($xml);
		$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
		$ci->nusoap_client = new nusoap_client($url);
		$ci->nusoap_client->defencoding = 'UTF-8';
		$ci->nusoap_client->soap_defencoding = 'UTF-8';
		$ci->nusoap_client->decode_utf8 = false;
		$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
		
		$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
		
		var_dump($result, $ci->nusoap_client->fault, $ci->nusoap_client->faultstring, $ci->nusoap_client->getError());
// var_dump($method,htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES), $ci->nusoap_client->getError());
// 		echo $ci->nusoap_client->request;
		
// 		echo $xml;
		die;
		
		if($ci->nusoap_client->fault)
		{
			//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
			$ci->selfcare_mod->save_api_log('fault Error '.$method,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
			return array();
		}else{
			if($ci->nusoap_client->getError()){
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('getError Error '.$method,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			}else{
				$ci->selfcare_mod->save_api_log($method,htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
			}
		}
		return $result;
	}
	
}