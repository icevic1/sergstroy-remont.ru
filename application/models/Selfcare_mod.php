<?php
class Selfcare_mod extends CI_Model
{
	function __construct(){
		parent::__construct();
	}
	
	public function check_verify_code($account_id = null, $verify_code = null, $account_type = null)
	{
		if (!$account_id || !$verify_code || !in_array($account_type, array('0', '1'))) return false;
	
		$query = $this->db->from("scsm_verify_code as vc")->select('vc.*')
			->where('vc.verify_code', $verify_code)
			->where('vc.account_type', $account_type)
			->where('vc.status', '0');
	
		if ($account_type == Staff_mod::$ACCOUNT_TYPE) {
			$query->join("scsm_users as u", 'vc.account_id = u.user_id', 'INNER');
			$query->where('u.email', $account_id);
		} else {
			$query->where('vc.account_id', $account_id);
		}
		
		$query = $query->get();
// 		print $this->db->last_query();
	
		$result = $query->row_array();
		return $result;
	}
		
	public function save_verify_code($account_id, $account_type = '0', $verify_code = null, $status = '0')
	{
		if (!$account_id) return null;
		
		$Query = "INSERT INTO scsm_verify_code (account_id, account_type, verify_code, status) VALUES (?, ?, ?, ?)
    						ON DUPLICATE KEY UPDATE verify_code = ?, status = ?, updated_at = NOW();";
		
		$query = $this->db->query($Query, array($account_id, $account_type, $verify_code, $status, $verify_code, $status));
	
		$afftectedRows = $this->db->affected_rows();
		// 		print $lastQuery = $this->ST_DB->last_query();
		return $afftectedRows;
	}
	
	
	public function getMenuItems() 
	{
		$query = $this->db->select('*')
			->from('sm_menu')
			->where("parent_id", null)
			->where("published", '1')
			->order_by('item_order');
		
		$queryResult = $query->get();
// 		print $lastQuery = $this->db->last_query();
		return $queryResult->result();
	}
	
	public function getActiveMenuItem($controller = null, $action = null) 
	{
		$action = ($action == 'index')? null : $action;
		$query = $this->db->select('*')
			->from('sm_menu')
// 			->where("parent_id IS NOT", null, false)
			->where("controller", $controller)
			->where("action", $action);
		
		$queryResult = $query->get();
// 		print $lastQuery = $this->db->last_query();
		return $queryResult->row();
	}
	
	public function updateCaption($capt_id = null, $l_id = null, $data_update) 
	{
		if (!$capt_id || !$l_id || !$data_update) return 0;
		$this->db->where('capt_id', $capt_id);
		$this->db->where('l_id', $l_id);
		$this->db->limit(1);
		$this->db->update('sc_caption', $data_update);
		$afftectedRows = $this->db->affected_rows();
// 		print $lastQuery = $this->db->last_query();
		return $afftectedRows;
	}
	
	public function addCaption($data_update) {
		if (!$data_update) return 0;
		$this->db->insert('sc_caption', $data_update);
		print $lastQuery = $this->db->last_query();
		return true;
	}
	
	public function deleteCaption($capt_id = null) {
		if (!$capt_id) return false;
		$this->db->delete('sc_caption', array('capt_id' => $capt_id));
// 		print $this->db->last_query();
		return true;
	}
	
	//============API LOG================//
	function save_log($log){
		$query="INSERT INTO sc_api_log(ip,log,created_date) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$log."',NOW())";
		//$this->db->query($query);
	}
	function save_api_log($method,$req_xml,$resp_xml){
		$query="INSERT INTO sc_api_log(ip,method,request_xml,response_xml,created_date,log_type) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$method."','".$req_xml."','".$resp_xml."',NOW(),'xml')";
		$this->db->query($query);
	}
	function get_log(){
		$query="SELECT * FROM sc_api_log";
		return $this->db->query($query)->result();
	}
	function get_api_log($query,$sEcho,$query_1){
		$output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0,
            'aaData' => array()
        );		
		$res=$this->db->query($query);
		
		$total=$this->db->query($query_1)->num_rows();
		$output['iTotalRecords']=$total;
		$output['iTotalDisplayRecords']=$total;
		if($res->num_rows()>0){
			foreach($res->result_array() as $item){
				$row = array();
				
				if($item['log_type']=='xml'){
					$st='<a href="#" class="cls-detail">'.$item['method'].' API</a>';
					$st.='<div style="display:none">';
					$st.='<hr/>';
					$st.='<b>Request</b><br/>';
					$st.='<pre class="prettyprint linenums"><code class="language-xml">';
					$st.= $item['request_xml'];
					$st.='</code></pre>';
					$st.='<b>Response</b><br/>';
					$st.='<pre class="prettyprint linenums"><code class="language-xml">';
					$st.= $item['response_xml'];
					$st.='</code></pre>';
					$st.='</div>';
					$row[]=$st;
				}else{
					$row[]=htmlspecialchars_decode($item['log']);
				}
				$row[]=$item['ip'];
				$row[]=$item['created_date'];
				$output['aaData'][] = $row;
			}
		}
		return $output; 
	}
	//==============GET MENU================//
	function get_menu($username){
		/* $query="SELECT p.page_id,page_name,page_url,page_icon FROM sc_page p 
		INNER JOIN sc_per_page pp ON p.page_id=pp.page_id 
		WHERE pp.user_name='{$username}' AND pp.per_show=1 AND p.page_parent=0 and p.page_group != '110'
		ORDER BY page_order";print $query;var_dump($this->db->query($query)->result());
		return $this->db->query($query)->result(); */
		
		$query = $this->db->select('p.page_id,page_name,page_url,page_icon')
			->from('sc_page p')
			->join('sc_per_page pp', 'p.page_id=pp.page_id', 'INNER')
			->where('pp.user_name', $username)
			->where('pp.per_show', 1)
			->where('p.page_parent', 0)
			->where('p.page_group !=', '10')
			->order_by('p.page_order');
		$query = $query->get();
// 		print $this->db->last_query();
		
		return $query->result();
		
	}
	function get_page_menu($username, $pageId){
		$query="SELECT p.page_id,page_name,page_url,page_icon FROM sc_page p 
		INNER JOIN sc_per_page pp ON p.page_id=pp.page_id 
		WHERE pp.user_name='{$username}' AND pp.per_show=1 AND p.page_parent={$pageId} and p.page_group != '10'
		ORDER BY page_order";
		return $this->db->query($query)->result();
	}
	public function get_group_menu($groupId){
		$query="SELECT p.page_id,page_name,page_url,page_icon FROM sc_page p
		INNER JOIN sc_per_page pp ON p.page_id=pp.page_id
		WHERE p.page_group='{$groupId}' AND pp.per_show=1
		ORDER BY page_order";
		return $this->db->query($query)->result();
	}
	//==============GET PERMISSION PER PAGE===============//
	function get_perm_per_page($user_name,$page_id){
		$query="SELECT pp.page_id,pp.per_show,p.page_name,pp.per_save,pp.per_update,pp.per_delete,p.page_name,p.page_icon FROM sc_per_page pp INNER JOIN sc_page p ON pp.page_id=p.page_id WHERE pp.user_name='".$user_name."' AND pp.page_id=".$page_id."";
		return $this->db->query($query)->row_array();
	}
	//==============GET LANGUAGE===============//
	function get_language(){
		$query="SELECT l_id,l_name,l_short_name,is_default FROM sc_language WHERE is_deleted=0 ORDER BY is_default DESC";
		return $this->db->query($query)->result();
	}
	function get_language_by_short_name($l_short_name){
		$query="SELECT l_id FROM sc_language WHERE is_deleted=0 AND l_short_name='".$l_short_name."'";
		return $this->db->query($query)->row_array();
	}
	//=============GET MESSAGE LIST==============//
	function get_message_list(){
		$query="SELECT msg_id,IFNULL(translate,msg) msg,in_pages,msg_type,msg_icon FROM sc_message WHERE l_id=1 ORDER BY l_id";
		return $this->db->query($query)->result();
	}
	function get_message($msg_id){
		$query="SELECT l.l_id,msg_id,msg,translate,l.l_name FROM sc_message m INNER JOIN sc_language l ON m.l_id=l.l_id WHERE msg_id='".$msg_id."' ORDER BY l.l_id";
		return $this->db->query($query)->result();
	}
	//=============SAVE MESSAGE====================//
	function update_message($translate,$l_id,$msg_id,$modified_by){
		$query="UPDATE sc_message SET translate='".$translate."',modified_by='".$modified_by."',modified_date=NOW() WHERE l_id=".$l_id." AND msg_id='".$msg_id."'";
		$this->db->query($query);
	}
	//============= CAPTION ================//
	function get_caption_list($l_id = 2){
		$query="SELECT capt_id,IFNULL(translate,caption) caption,caption_type,in_pages FROM sc_caption WHERE l_id={$l_id} ORDER BY capt_id";
		return $this->db->query($query)->result();
	}
	function get_caption($capt_id){
		$query="SELECT l.l_id,capt_id,caption,translate,l.l_name,caption_type,in_pages FROM sc_caption b INNER JOIN sc_language l ON b.l_id=l.l_id WHERE capt_id='".$capt_id."' ORDER BY l.l_id";
		$result = $this->db->query($query)->result();
// 		print $lastQuery = $this->db->last_query();
		return $result;
	}
	function get_captions($l_id){
		$query="SELECT capt_id,caption,translate FROM sc_caption WHERE l_id=".$l_id."";
		$res=$this->db->query($query)->result_array();
		$caption=array();
		if($res){
			foreach($res as $val){
				$caption[$val['capt_id']]=empty($val['translate'])?$val['caption']:$val['translate'];
			}
		}
		return $caption;
	}
	function update_caption($translate,$l_id,$capt_id,$modified_by){
		$query="UPDATE sc_caption SET translate='".$this->db->escape_str($translate)."',modified_by='".$modified_by."',modified_date=NOW() WHERE l_id=".$l_id." AND capt_id='".$capt_id."'";
		$this->db->query($query);
	}
	//===========END CAPTION==============//
	//============GET MESSAGE============//
	function get_messages($l_id){
		$query="SELECT msg_id,msg,translate FROM sc_message WHERE l_id=".$l_id."";
		$res=$this->db->query($query)->result_array();
		$message=array();
		if($res){
			foreach($res as $val){
				$message[$val['msg_id']]=empty($val['translate'])?$val['msg']:$val['translate'];
			}
		}
		return $message;
	}
	//============SEARCH CITYID FROM HE==============//
	function get_user_location_by_pgw_and_user($ip_pgw,$user_ip){
		$db2 = $this->load->database('cresittelosnova', TRUE);
		$query="SELECT block_address,city_id FROM iqof4x0zn0vapm_iplocation WHERE is_deleted=0 AND ip_pgw='".$ip_pgw."'";
		$ips=$db2->query($query)->result();
		$db2->close();
		$this->load->database('selfcare', TRUE);
		$city_id='';
		foreach($ips as $ip){
			$db_ip_arr=explode('.',$ip->block_address);
			$db_ip=$db_ip_arr[0].'.'.$db_ip_arr[1];
			$usr_ip_arr=explode('.',$user_ip);
			$usr_ip=$usr_ip_arr[0].'.'.$usr_ip_arr[1];
			if($db_ip==$usr_ip){
				$city_id=$ip->city_id;
				break;
			}
		}
		return $city_id;
	}
	//==================GET CITY==========//
	function get_city($city_id,$l_id){
		$db2 = $this->load->database('cresittelosnova', TRUE);
		/*if($l_id==1){
			$query="SELECT name FROM iqof4x0zn0vapm_city WHERE acc_city_id=".$city_id." AND state=1 AND id<>-1";
		}else{
			//$l_id=1;
			$query="SELECT f.value name FROM iqof4x0zn0vapm_falang_content f
INNER JOIN  iqof4x0zn0vapm_city c ON c.id=f.reference_id AND f.reference_table='city' 
WHERE f.language_id=".$l_id." AND c.acc_city_id=".$city_id."";
		}*/
		$query="SELECT name FROM iqof4x0zn0vapm_city WHERE acc_city_id=".$city_id." AND state=1 AND id<>-1";
		$result=$db2->query($query)->row_array();
		$db2->close();
		$this->load->database('selfcare', TRUE);
		return $result;
	}
	function get_city_list($l_id){
		$db2 = $this->load->database('cresittelosnova', TRUE);
		/*if($l_id==2){
			$query="SELECT id,name FROM iqof4x0zn0vapm_city";
		}else{
			$l_id=2;
			$query="SELECT c.acc_city_id id,f.value name FROM iqof4x0zn0vapm_falang_content f
INNER JOIN  iqof4x0zn0vapm_city c ON c.id=f.reference_id AND f.reference_table='city' 
WHERE f.language_id=".$l_id."";
		}*/
		$query="SELECT acc_city_id id,name FROM iqof4x0zn0vapm_city WHERE state=1 AND id<>-1";
		$result=array('-1'=>'*');
		if($db2->query($query)->num_rows()>0){
			foreach($db2->query($query)->result_array() as $val){
				$result[$val['id']]=$val['name'];
			}
		}
		$db2->close();
		$this->load->database('selfcare', TRUE);
		return $result;
	}
	//===========SAVE TRANSACTION==========//
	function save_transaction($sub_number){
		$query="INSERT INTO sc_transaction(sub_number,modified_date) VALUES('".$sub_number."',NOW())";
		$this->db->query($query);
	}
	//===========SAVE PAYMENT==========//
	function save_payment($sub_number){
		$query="INSERT INTO sc_payment(sub_number,modified_date) VALUES('".$sub_number."',NOW())";
		$this->db->query($query);
	}
	//==========GET TRANSACTION============//
	function get_transaction(){
		$query="SELECT * FROM sc_transaction";
		return $this->db->query($query)->result();
	}
	//==========GET PAYMENT============//
	function get_payment(){
		$query="SELECT * FROM sc_payment";
		return $this->db->query($query)->result();
	}
	//===========GET FAQ================//
	function get_faq_list($l_short_name){
		$query="SELECT d.faq_id,d.question,d.answer,f.post_date FROM sc_faq f INNER JOIN sc_faq_detail d ON f.faq_id=d.faq_id AND f.is_deleted=0 INNER JOIN sc_language l
		ON d.l_id=l.l_id WHERE l.l_short_name='".$l_short_name."'";
		return $this->db->query($query)->result();
	}
	//===========GET FAQ DETAIL================//
	function get_faq_detail($faq_id){
		$query="SELECT l_id,faq_id,question,answer FROM sc_faq_detail WHERE faq_id=".$faq_id."";
		return $this->db->query($query)->result();
	}
	//==========SAVE FAQ===========//
	function save_faq($user){
		$query="INSERT INTO sc_faq(post_by,post_date,is_deleted) VALUES ('".$user."',NOW(),0)";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	function update_faq($faq_id,$user){
		$query="UPDATE sc_faq SET post_by='".$user."',post_date=NOW() WHERE faq_id=".$faq_id."";
		$this->db->query($query);
	}
	function save_faq_detail($l_id,$faq_id,$question,$answer){
		$query="REPLACE INTO sc_faq_detail(l_id,faq_id,question,answer) VALUES (".$l_id.",".$faq_id.",'".$question."','".$answer."')";
		$this->db->query($query);
	}
	function save_verify_subscriber($cust_number,$email,$verify_code,$verify_encrypt_code,$imsi,$city_id){
		$query="INSERT INTO sc_verify_subscriber (cust_number,email,verify_code,verify_encrypt_code,imsi,city_id,created_date) VALUES('".$cust_number."','".$email."','".$verify_code."','".$verify_encrypt_code."','".$imsi."','".$city_id."',NOW())";
		$this->db->query($query);
	}
	function check_confirm_code($code){
		$msg=array();
		$query="SELECT TIMESTAMPDIFF(HOUR, created_date, NOW()) duration,cust_number,email,imsi,city_id FROM sc_verify_subscriber WHERE verify_code='".$code."'";
		$result=$this->db->query($query);
		if($result->num_rows()>0){
			$row=$result->row_array();
			if($row['duration']>24){
				$msg=array('err'=>1,'msg'=>'Your url is expired.');
			}else{
				$msg=array('err'=>0,'msg'=>'Successed.','cust_number'=>$row['cust_number'],'email'=>$row['email'],'imsi'=>$row['imsi'],'city_id'=>$row['city_id']);
			}
		}
		else{
			$msg=array('err'=>2,'msg'=>'Invalid Confirmation');
		}
		return $msg;
	}
	function update_verify_email($verified_code){
		$query="UPDATE sc_verify_subscriber SET modified_date=NOW() WHERE verify_code='".$verified_code."'";
		$this->db->query($query);
	}
		//=================USER BLOCK=======================//
	function get_users(){
		$qry="SELECT user_id,user_name, password FROM sc_user WHERE is_deleted=0 and is_admin=0";
		return $this->db->query($qry)->result();
	}
	function get_user($user_id){
		$qry="SELECT user_id,user_name, password FROM sc_user WHERE is_deleted=0 AND user_id='".$user_id."'";
		return $this->db->query($qry)->row_array();
	}
	function save_user($user_name,$pwd,$login_name){
		if(!$this->is_exists("sc_user","user_name='".$user_name."' AND is_deleted=0")){
			$query="INSERT INTO sc_user (user_name,password,is_deleted,created_by,created_date,is_admin) VALUES('".$user_name."','".$pwd."',0,'".$login_name."',NOW(),0)";
			$this->db->query($query);
			return 'Save user succeed.';
		}else{
			return 'User already exists.';
		}
	}
	function update_user($user_id,$user_name,$pwd,$login_name){
		$query="UPDATE sc_user SET user_name='".$user_name."',password='".$pwd."',modified_by='".$login_name."',modified_date=NOW() WHERE user_id=".$user_id."";
		$this->db->query($query);
		return 'Update user succeed.';
	}
	function delete_user($user_id,$login_name){
		$query="UPDATE sc_user SET is_deleted=1,modified_by='".$login_name."',modified_date=NOW() WHERE user_id=".$user_id."";
		if($this->db->query($query)){
			return 'Delete user succeed.';
		}else{
			return 'Can not delete user.';
		}
	}
	function check_user($user_name,$pwd){
		$msg=array();
		$query="SELECT password FROM sc_user WHERE user_name='".$user_name."' AND is_deleted=0";
		$res=$this->db->query($query);
		if($res->num_rows()>0){
			$row=$res->row_array();
			$key = $this->config->item('encryption_key');						
			$password = $this->cryptastic->decrypt($row['password'], $key, true);
			if($pwd==$password){
				$msg=array('err'=>0,'msg'=>'Login Succeed');
			}else{
				$msg=array('err'=>1,'msg'=>'Invalid Password');
			}
		}else{
			$msg=array('err'=>1,'msg'=>'Invalid Username');
		}
		return $msg;
	}
	//==========GET PROMOTION (PROMOTION=TARIFF=PRICEPLAN)===========//
	/**
	 * to delete
	 */
	function get_promotion($acc_ordinal,$cityid,$cosid){
		$query="SELECT * FROM v_product_catalog_offer WHERE city_id='".$cityid."' AND acc_ordinal='".$acc_ordinal."' AND cosid='".$cosid."' AND is_expired=0";
		return $this->db->query($query)->row_array();
	}
		//================GET VARIABLE================//
	function get_gb_variales(){
		$query="SELECT var_id,var_key,var_val,var_desc,gv.scope_id,scope FROM sc_variable gv INNER JOIN sc_variable_scope vs ON gv.scope_id=vs.scope_id WHERE is_deleted=0";
		return $this->db->query($query)->result();
	}
	function get_gb_variale($var_id){
		$query="SELECT var_id,var_key,var_val,scope_id,var_desc FROM sc_variable WHERE var_id=".$var_id."";
		return $this->db->query($query)->row_array();
	}
	function save_variable($var_key,$var_val,$var_desc,$scope_id,$login_name){
		$query="INSERT INTO sc_variable(var_key,var_val,var_desc,scope_id,created_by,created_date,is_deleted) VALUES ('".$var_key."','".$var_val."','".$var_desc."','".$scope_id."','".$login_name."',NOW(),0)";
		if($this->db->query($query)){
			return 'Save variable succeed.';
		}else{
			return 'Can not save variable.';
		}
		
	}
	function update_variable($var_id,$var_key,$var_val,$var_desc,$scope_id,$login_name){
		$query="UPDATE sc_variable SET var_key='".$var_key."',var_val='".$var_val."',modified_by='".$login_name."',modified_date=NOW(),var_desc='".$var_desc."',scope_id='".$scope_id."' WHERE var_id=".$var_id."";
		if($this->db->query($query)){
			return 'Update variable succeed.';
		}else{
			return 'Can not update variable.';
		}
	}
	function delete_variable($var_id){
		$query="UPDATE sc_variable SET is_deleted=1,modified_by='".$login_name."',modified_date=NOW() WHERE var_id=".$var_id."";
		if($this->db->query($query)){
			return 'Delete variable succeed.';
		}else{
			return 'Can not delete variable.';
		}
	}
	function get_scope_list(){
		$query="SELECT scope_id,scope FROM sc_variable_scope";
		return $this->result_key_val($this->db->query($query),'scope_id','scope');
	}
	function result_key_val($result,$key,$val){
		$data=array();
		if ($result->num_rows() > 0){
			 foreach ($result->result_array() as $row){
				 $data[$row[$key]] = $row[$val];
			}
		} 
		return $data;
	}
	//=================GET SUBSCRIBER================//
	function get_subscribers(){
		$query="SELECT * FROM sc_subscriber_login";
		return $this->db->query($query)->result();
	}
	function get_subscriber($sub_number){
		$query="SELECT * FROM sc_subscriber_login WHERE sub_number='".$sub_number."'";
		return $this->db->query($query)->row_array();
	}
	function get_session_variable(){
		$query="SELECT var_key,var_val FROM sc_variable WHERE scope_id=1 AND is_deleted=0";
		$res=$this->db->query($query);
		return $this->result_key_val($res,'var_key','var_val');
	}
	//=============TRY LOGIN=============//
	function save_tried_login($ip){
		$query="REPLACE INTO sc_tried_login(ip) VALUES('".$ip."')";
		$this->db->query($query);
	}
	function count_tried_login($ip){
		$query="UPDATE sc_tried_login SET num_tried_login=IFNULL(num_tried_login,0)+1 WHERE ip='".$ip."'";
		$this->db->query($query);
	}
	function start_delay_login($ip){
		$query="UPDATE sc_tried_login SET start_delay_time=NOW() WHERE ip='".$ip."'";
		$this->db->query($query);
	}
	function get_tried_login($ip){
		$query="SELECT *,CASE WHEN start_delay_time IS NOT NULL THEN TIMESTAMPDIFF(HOUR, start_delay_time, NOW()) ELSE start_delay_time END duration FROM sc_tried_login WHERE ip='".$ip."'";
		return $this->db->query($query)->row_array();
	}
	function clear_tried_login($ip){
		$query="DELETE FROM sc_tried_login WHERE ip='".$ip."'";
		$this->db->query($query);
	}
	//===========TRY LOGIN==========//
	
	//=================DYNAMIC PAGE BLOCK================//
	function get_dynamic_pages(){
		$query="SELECT pg_id,pg_name,pg_type,is_public,pg_url FROM sc_dynamic_page ORDER BY pg_type";
		return $this->db->query($query)->result();
	}
	function get_dynamic_page($pg_id){
		$query="SELECT pg_id,pg_name,pg_type,is_public,pg_script,pg_url FROM sc_dynamic_page WHERE pg_id=".$pg_id."";
		return $this->db->query($query)->row_array();
	}
	function get_dynamic_page_by_name($l_short_name,$pg_name){
		$query="SELECT dc.pg_header,dc.pg_content,d.pg_script,d.pg_type,d.pg_id
 FROM sc_dynamic_page d INNER JOIN sc_dynamic_page_html dc
ON d.pg_id=dc.pg_id INNER JOIN sc_language l ON dc.l_id=l.l_id AND l.is_deleted=0 WHERE l.l_short_name='".$l_short_name."' AND d.pg_name='".$pg_name."' AND d.is_public=1";
		return $this->db->query($query)->row_array();
	}
	function get_dmp_by_name($l_short_name,$pg_id){
		$query="SELECT dc.pg_header,dc.pg_content,d.pg_type
 FROM sc_dynamic_page d INNER JOIN sc_dynamic_page_html dc
ON d.pg_id=dc.pg_id INNER JOIN sc_language l ON dc.l_id=l.l_id AND l.is_deleted=0 WHERE l.l_short_name='".$l_short_name."' AND d.pg_id='".$pg_id."' AND d.is_public=1";
		return $this->db->query($query)->row_array();
	}
	function get_dynamic_detail_page($pg_id){
		$query="SELECT l.l_id,l.l_name,l.l_short_name,d.pg_content,d.pg_type,pg_header FROM sc_language l LEFT JOIN 
(SELECT h.l_id,h.pg_header,h.pg_content,p.pg_type FROM sc_dynamic_page_html h INNER JOIN sc_dynamic_page p ON p.pg_id=h.pg_id WHERE p.pg_id=".$pg_id.") d 
ON l.l_id=d.l_id WHERE l.is_deleted=0 ORDER BY l.l_id";
		return $this->db->query($query)->result();
	}
	function get_tmpl_general(){
		$query="SELECT l.l_id,l.l_name,l.l_short_name,t.logo,t.contact_number,t.footer,t.close_page FROM sc_tmpl_general t INNER JOIN sc_language l ON t.l_id=l.l_id WHERE l.is_deleted=0";
		return $this->db->query($query)->result();
	}
	function get_tmpl_general_by_lang($l_short_name){
		$query="SELECT t.logo,t.contact_number,t.footer,t.close_page FROM ld_tmpl_general t INNER JOIN ld_language l ON t.l_id=l.l_id WHERE l.is_deleted=0 AND l.l_short_name='".$l_short_name."'";
		return $this->db->query($query)->row_array();
	}
	function update_tmpl_general($l_id,$contact_number,$footer,$login_name){
		$query="UPDATE sc_tmpl_general SET contact_number='".$contact_number."',footer='".$footer."',modified_by='".$login_name."',modified_date=NOW() WHERE l_id=".$l_id."";
		$this->db->query($query);
	}
	function create_dynamic_page($pg_name,$pg_url,$is_public,$pg_script,$login_name){
		$query="INSERT INTO sc_dynamic_page(pg_name,pg_url,is_public,pg_script,created_by,created_date) VALUES ('".$pg_name."','".$pg_url."',".$is_public.",'".$pg_script."','".$login_name."',NOW())";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	function update_dynamic_page($pg_id,$pg_name,$pg_url,$is_public,$pg_script,$login_name){
		$query1="UPDATE sc_dynamic_page SET pg_name='".$pg_name."',pg_url='".$pg_url."',is_public=".$is_public.",pg_script='".$pg_script."',modified_by='".$login_name."',modified_date=NOW() WHERE pg_id=".$pg_id."";
		$this->db->query($query1);
		return $pg_id;
	}
	function delete_dynamic_page($pg_id){
		$query="DELETE FROM sc_dynamic_page_html WHERE pg_id=".$pg_id."";
		if($this->db->query($query)){
			return 'Delete page succeed.';
		}else{
			return 'Can not delete page.';
		}
	}
	function save_dynamic_page_detail($pg_id,$l_id,$pg_content){
		$query="INSERT INTO sc_dynamic_page_html(pg_id,l_id,pg_content) VALUES (".$pg_id.",".$l_id.",'".$pg_content."')";
		$this->db->query($query);
	}
	//================SUBSCRIBER STATUS=================//
	function get_subscriber_statuses(){
		$query="SELECT DISTINCT status_id,IFNULL(translate,status_name) status_name FROM sc_subscriber_status WHERE l_id=1";
		return $this->db->query($query)->result();
	}
	function get_subscriber_status($status_id){
		$query="SELECT s.l_id,l.l_short_name,status_id,translate,desc_translate,l_name FROM sc_subscriber_status s INNER JOIN sc_language l ON s.l_id=l.l_id WHERE status_id=".$status_id."";
		return $this->db->query($query)->result();
	}
	
	function get_subscriber_status_l($status_type, $status_id)
	{
// 		$querytext="SELECT status_id,IFNULL(translate,status_name) status_name,desc_translate description FROM sc_subscriber_status WHERE l_id=".$l_id." AND status_id=".$status_id."";
		//$querytext="SELECT * FROM sc_subscriber_status WHERE l_id=".$l_id." AND status_id=".$status_id."";
		$querytext="select status_name from sc_subscriber_status where status_type=".$status_type." and status_id='".$status_id."'";
		
		$query = $this->db->query($querytext); 
// 		$this->load->model('Selfcare_mod','',true);
// 		$this->load->database('selfcare_smart');
		
// 		$this->db->select('*');
// 		$this->db->from('sc_subscriber_status');
// 		$this->db->where('l_id', $l_id);
// 		$this->db->where('status_id', $status_id);
// 		$query = $this->db->get();
// 		$query_result = 1 ; //$query->row();
		$query_result = $query->row_array();
		
// 		var_dump($query_result, $this->db->conn_id, $this->db);die;
// 		$row = $query->row_array(1);//->row_array();
		return $query_result;
	}
	
	
	function get_subscriber_billing_status($l_id,$status_id){
		$query="SELECT translate FROM sc_subscriber_billing_status WHERE l_id=".$l_id." AND acc_status_id='".$status_id."'";
		return $this->db->query($query)->row_array();
	}
	function update_subscriber_status($translate,$desc_translate,$l_id,$status_id,$login_name){
		$query="UPDATE sc_subscriber_status SET translate='".$translate."',desc_translate='".$desc_translate."',modified_by='".$login_name."',modified_date=NOW() WHERE l_id=".$l_id." AND status_id=".$status_id."";
		$this->db->query($query);
	}
	//==============END SUBSCRIBER STATUS================//
	//=============CUSTOMER====================//
	function save_customer_visit($cust_number){
		$query="INSERT INTO sc_customer_visit(cust_number,ip,visit_date) VALUES('".$cust_number."','".$_SERVER['REMOTE_ADDR']."',NOW())";
		$this->db->query($query);
	}
	//==============END CUSTOMER==============//
	//===============================PERMISSION BLOCK=============================//
	function get_per_page($user_name){
		$query="SELECT p.page_id,page_name,CASE per_show WHEN 1 THEN 'checked=\"checked\"' ELSE '' END per_show,
CASE per_save WHEN 1 THEN 'checked=\"checked\"' ELSE '' END per_save,
CASE per_update WHEN 1 THEN 'checked=\"checked\"' ELSE '' END per_update,
CASE per_delete WHEN 1 THEN 'checked=\"checked\"' ELSE '' END per_delete FROM sc_page p 
LEFT JOIN (SELECT page_id,per_show,per_save,per_update,per_delete FROM sc_per_page WHERE user_name='".$user_name."') pp
ON p.page_id=pp.page_id ORDER BY p.page_order";
		return $this->db->query($query)->result();
	}
	function remove_per_page($user_name){
		$query="DELETE FROM sc_per_page WHERE user_name='".$user_name."'";
		$this->db->query($query);
	}
	function save_per_page($page_id,$user_name,$view,$save,$update,$delete){
		$query="INSERT INTO sc_per_page VALUES(".$page_id.",'".$user_name."',".$view.",".$save.",".$update.",".$delete.")";
		$this->db->query($query);
	}
	//==========================END PERMISSION BLOCK=================//
	//===============CUSTOMER==================//
	function get_customers(){
		$query="SELECT cust_number FROM sc_customer";
		return $this->db->query($query)->result();
	}
	function save_customer_log($log){
		$cust_number=isset($this->session->userdata['cust']['cust_number'])?$this->session->userdata['cust']['cust_number']:'';
		$query="INSERT INTO sc_customer_log (cust_number,log,ip,browser,version,created_date) VALUES('".$cust_number."','".$log."','".$_SERVER['REMOTE_ADDR']."','".$this->agent->browser()."','".$this->agent->version()."',NOW())";
		$this->db->query($query);
	}
	//=============END CUSTOMER==========//
	//=============GET SUBSCRIBER PAYMENT HISTORY=============//
	
	//=============GET SUBSCRIBER TRAFFIC=============//
	function get_subscriber_traffic($sub_number){
		$query="SELECT * FROM sc_subscriber_traffic WHERE SubsNumber='".$sub_number."'";
		return $this->db->query($query)->result_array();
	}
	//===========END GET SUBSCRIBER TRAFFIC=============//
	//============GET CUSTOMER TYPE============//
	function get_customer_type($l_id,$cust_type_id){
		$query="SELECT cust_type_id,cust_type_name,template FROM sc_customer_type WHERE cust_type_id=".$cust_type_id." AND l_id=".$l_id."";
		//echo $query;exit;
		return $this->db->query($query)->row_array();
	}
	//==============SAVE CUSTOMER CONTACT EMAIL================//
	function send_email($imsi,$cust_name,$email_from,$email_to,$email_subject,$email_body,$send_status){
		$query="INSERT INTO sc_customer_contact(imsi,cust_name,email_from,email_to,email_subject,email_body,send_status,send_date) VALUES ('".$imsi."','".$cust_name."','".$email_from."','".$email_to."','".$email_subject."','".$email_body."','".$send_status."',NOW())";
		$this->db->query($query);
	}
	//==========FUNCTION LOAD TRAFIC===============//
	function get_trafics($start_date,$end_date,$sub_number){return array();
		$query="CALL sp_select_traffic('".$start_date."','".$end_date."','subs_number=''".$sub_number."''')";
		$res=$this->db->query($query);
		if(!$res) return array();
// 		mysqli_more_results($this->db->conn_id);
		return $res->result();
	}
	function get_buy_in_bulk($pp_id){
		$query="SELECT * FROM v_product_catalog_buyinbulk WHERE pp_id=".$pp_id." ORDER BY pp_id,renewals";
		return $this->db->query($query)->result();
	}
	function get_buy_in_bulk_detail($acc_ordinal){
		$query="SELECT * FROM sc_buyinbulk WHERE acc_ordinal=".$acc_ordinal."";
		return $this->db->query($query)->row_array();
	}
	//===========GET GLOBAL VARIABLE=============//
	function get_variables($scope_id){
		$query="SELECT var_key,var_val FROM sc_variable WHERE scope_id=".$scope_id."";
		return $this->db->query($query)->result_array();
	}
	function get_variable($var_key){
		$query="SELECT * FROM sc_variable WHERE var_key='".$var_key."'";
		return $this->db->query($query)->row_array();
	}
	
	function get_variable_val($var_key){
		$query="SELECT var_val FROM sc_variable WHERE var_key='".$var_key."'";
		$result = $this->db->query($query)->row_array();
		return (empty($result['var_val']))? '': $result['var_val'] ;
	}
	
	
	/**=================PRODUCT CATALOG==================
	 * required
	 */
	public function get_product_catalog_offer_list($group_id = null)
	{
		$query = $this->db->select('*')
			->from('sc_product_catalog_offer as pcof')
			->join('sc_product_catalog_group as pcg', 'pcg.group_id = pcof.group_id', 'left')
			->order_by('pcof.acc_pp_id, pcof.group_id');
		
		if ($group_id) $query->where('pcof.group_id', $group_id);
		
		$query = $query->get();
		return $query->result();
	}
	
	public function get_pp_byID($pp_id = null)
	{
		$query = $this->db->select('*')
			->from('sc_product_catalog_offer')
			->where('pp_id', $pp_id)
			->get();
		return $query->row_array();
	}
	
	/**
	 * Insert new site candidate
	 * @param array $fieldsVal
	 * @return int last insert id
	 */
	public function addNewPP($fieldsVal = array())
	{
		if (!$fieldsVal) return false;
	
		$this->db->insert('sc_product_catalog_offer', $fieldsVal);
		//     	print $lastQuery = $this->db->last_query();
		$insert_id = $this->db->insert_id();
	
		return $insert_id;
	}
	
	/**
	 * Update site candidate details
	 * @param array $fieldsVal
	 * @param strung $candidateID
	 * @return int affected rows
	 */
	public function updatePP($pp_id = null, $fieldsVal = array() )
	{
		if (!$pp_id || !$fieldsVal) return false;
	
		$this->db->update('sc_product_catalog_offer', $fieldsVal, array('pp_id'=> $pp_id), 1);
		//print $lastQuery = $this->db->last_query();
		return $this->db->affected_rows();
	}
	
	
	
	function get_buyinbulk_list($pp_id){
		$query="SELECT bib.*,CASE WHEN pb.bib_id IS NOT NULL THEN 'checked' ELSE '' END chk FROM sc_buyinbulk bib 
LEFT JOIN (SELECT * FROM sc_product_catalog_buyinbulk WHERE pp_id=".$pp_id.") pb 
ON bib.bib_id=pb.bib_id ORDER BY bib_id";
		return $this->db->query($query)->result();
	}
	function save_buyinbulk($pp_id,$vals){
		$query1="DELETE FROM sc_product_catalog_buyinbulk WHERE pp_id='".$pp_id."'";
		$this->db->query($query1);
		if($vals){
			$query2="INSERT INTO sc_product_catalog_buyinbulk VALUES ".$vals."";
			$this->db->query($query2);
		}
	}

	function get_pp_group_list(){
		$query="SELECT group_id,group_name FROM sc_product_catalog_group";
		$res=$this->db->query($query);
		$groups=array();
		if($res->num_rows()>0){
			foreach($res->result_array() as $g){
				$groups[$g['group_id']]=$g['group_name'];
			}
		}
		return $groups;
	}
	function get_product_catalog_list($gp_id){
		$query="SELECT acc_ordinal,pp_name FROM sc_product_catalog WHERE group_id=".$gp_id."";
		return $this->option_item($query);
	}
	function get_cos_list(){
		$query="SELECT cosid,cust_type_name FROM sc_customer_type WHERE l_id=2";
		return $this->option_item($query,array('-1'=>'*'));
	}
	function save_product_catalog_offer($pp_id,$acc_ordinal,$web_name,$city_id,$cosid,$home_quota,$home_speed_in_quota,$home_speed_after_quota,$roam_quota,$roam_speed_in_quota,$roam_speed_after_quota,$pp_cost,$duration,$activation_date,$expiration_date,$version){
		if($pp_id){
			$query="UPDATE sc_product_catalog_offer SET acc_ordinal=".$acc_ordinal.",web_name='".$web_name."',city_id=".$city_id.",cosid=".$cosid.",home_quota='".$home_quota."',home_speed_in_quota='".$home_speed_in_quota."',home_speed_after_quota='".$home_speed_after_quota."',roam_quota='".$roam_quota."',roam_speed_in_quota='".$roam_speed_in_quota."',roam_speed_after_quota='".$roam_speed_after_quota."',pp_cost='".$pp_cost."',duration='".$duration."',activation_date='".$activation_date."',expiration_date='".$expiration_date."',version='".$version."' WHERE pp_id=".$pp_id."";
			$this->db->query($query);
		}else{
			if(!$this->is_exists("sc_product_catalog_offer","acc_ordinal=".$acc_ordinal." AND city_id=".$city_id." AND cosid=".$cosid)){
				$query="INSERT INTO sc_product_catalog_offer(acc_ordinal,web_name,city_id,cosid,home_quota,home_speed_in_quota,home_speed_after_quota,roam_quota,roam_speed_in_quota,roam_speed_after_quota,pp_cost,duration,activation_date,expiration_date,version,is_expired,is_deleted) VALUES (".$acc_ordinal.",'".$web_name."',".$city_id.",".$cosid.",'".$home_quota."','".$home_speed_in_quota."','".$home_speed_after_quota."','".$roam_quota."','".$roam_speed_in_quota."','".$roam_speed_after_quota."','".$pp_cost."','".$duration."','".$activation_date."','".$expiration_date."','".$version."',0,0)";
				$this->db->query($query);
				$pp_id=$this->db->insert_id();
			}
		}
		return $pp_id;
	}
	function get_product_catalog_offer($pp_id,$l_id){
		$query="SELECT F.*,IFNULL(T.t_web_name,F.web_name) t_web_name FROM v_product_catalog_offer F 
LEFT JOIN (SELECT * FROM sc_product_catalog_offer_translate WHERE l_id=".$l_id.") T
ON F.pp_id=T.pp_id WHERE F.pp_id=".$pp_id."";
		return $this->db->query($query)->row_array();
	}
	function delete_product_catalog_offer($pp_id){
		$query="UPDATE sc_product_catalog_offer SET is_deleted=1 WHERE pp_id=".$pp_id."";
		$this->db->query($query);
		$query="DELETE FROM sc_product_catalog_buyinbulk WHERE pp_id=".$pp_id."";
		$this->db->query($query);
	}
	/**
	 * required function
	 */
	function get_offer_promotion($promoids = '1,2,3,4')
	{
// 		var_dump($promoids,$city_id,$cosid,$l_id);die;
		
		$query = "SELECT *
				FROM  sc_product_catalog_offer
				WHERE acc_pp_id IN ({$promoids})
				ORDER BY acc_pp_id";
		return $this->db->query($query)->result();
	}
	/*function get_price_plan($pp_id,$l_id){
		$query="SELECT F.*,IFNULL(T.t_web_name,F.web_name) web_name FROM v_product_catalog_offer F 
LEFT JOIN (SELECT * FROM sc_product_catalog_offer_translate WHERE l_id=".$l_id.") T
ON F.pp_id=T.pp_id  WHERE F.pp_id=".$pp_id."";
		return $this->db->query($query)->row_array();
	}*/
	function get_price_plan($pp_id){
		$query="SELECT * FROM v_product_catalog_offer WHERE pp_id=".$pp_id."";
		return $this->db->query($query)->row_array();
	}
	function get_pp_buy_in_bulk($pp_id,$bib_ordinal){
		$query="SELECT * FROM v_product_catalog_buyinbulk WHERE pp_id=".$pp_id." AND bib_ordinal=".$bib_ordinal."";
		return $this->db->query($query)->row_array();
	}
	function get_offer_pricing_options($opts,$city_id,$cosid,$l_id=0){ return array();
		$query="CALL sp_get_pricing_option('{$opts}','{$city_id}',{$cosid},{$l_id});";
// 		$query="CALL testprocedure();";
		$res = $this->db->query($query);
// 		$call = $this->db->call_function('sp_get_pricing_option', $opts, $city_id, $cosid, $l_id);
// 		$call = $this->db->call_function('testprocedure');
// 		$result = $res->result();
// 		$gg = $this->db->last_query();

// 		$result = array('fields'=>$res->list_fields(),
// 				'result'=>$res->result_array(),
// 				'last'=>$res->last_row(),
// 				'first'=>$res->first_row());
		
// 		var_dump($res->num_rows(), $result);die;
		
// 		$res = $this->db->query($query);
// 		$num_rows = $res->num_rows();
// 		$res->first_row();
		if($res->num_rows() > 0){
			$result = array('fields'=>$res->list_fields(),
					   'result'=>$res->result_array(),
					   'last'=>$res->last_row(),
					   'first'=>$res->first_row());
		}else{
			$result = array();
		}
// 		var_dump($result);
		return $result;
	}
	function get_pc_buy_in_bulk($pp_id,$l_id=0){	return array();
		$query="CALL sp_get_buyinbulk(".$pp_id.",".$l_id.");";
		$res = $this->db->query($query);
// 		if(!$res) return array();
// 		mysqli_more_results($this->db->conn_id);
		//return $res->result_array();
		if($res->num_rows()> 0){
			$res=array('fields'=>$res->list_fields(),
					   'result'=>$res->result_array(),
					   'last'=>$res->last_row(),
					   'first'=>$res->first_row());
		}else{
			$res=array();
		}
		return $res;
	}
	function get_pricing_option($promoid,$city_id,$cosid){
		$query="SELECT acc_ordinal,web_name,pp_cost,CASE WHEN is_roaming=1 THEN roam_quota ELSE home_quota END home_quota,CASE WHEN is_roaming=1 THEN roam_speed_in_quota ELSE home_speed_in_quota END home_speed_in_quota,group_name FROM v_product_catalog_offer WHERE acc_ordinal=".$promoid." AND city_id=".$city_id." AND cosid=".$cosid." AND is_expired=0 AND group_id=3";
		return $this->db->query($query)->row_array();
	}
	//==================END PRODUCT CATALOG==============//
	//==================RECOMMENDATION======================//
	function get_recommendation($account_number,$subs_number,$latest_activate_date){return array();
		$query="CALL sp_select_recommendation('".$account_number."','".$subs_number."','".$latest_activate_date."')";
		$res=$this->db->query($query);
// 		mysqli_next_result($this->db->conn_id);
// 		mysqli_more_results($this->db->conn_id);
		return $res->result();
		/*$query="CALL sp_select_recommendation_01('".$from_date."','".$to_date."','".$account_number."')";
		$res=$this->db->query($query);
		mysqli_next_result($this->db->conn_id);
		if($res->num_rows()>0){
			return $res->result();
		}else{
			$query="CALL sp_select_recommendation_02('".$from_date."','".$to_date."','".$account_number."','".$latest_activate_date."')";
			$res=$this->db->query($query);		
			mysqli_next_result($this->db->conn_id);
			return $res->result();
		}*/
	}
	function get_pc_translate($pp_id){
		$query="SELECT L.l_id,L.l_name,F.pp_id,F.t_web_name FROM sc_language L LEFT JOIN (SELECT * FROM sc_product_catalog_offer_translate WHERE pp_id=".$pp_id.") F ON L.l_id=F.l_id WHERE L.is_deleted=0";
		return $this->db->query($query)->result();
	}
	function save_pc_translate($pp_id,$values){
		$query1="DELETE FROM sc_product_catalog_offer_translate WHERE pp_id=".$pp_id."";
		$this->db->query($query1);
		$query2="INSERT INTO sc_product_catalog_offer_translate VALUES ".$values;
		$this->db->query($query2);
	}
	//========================HELP FUNCTION==================//
	function save_test(){
		$query="INSERT INTO sc_test(test_date) VALUES (NOW())";
		$this->db->query($query);
	}
	function is_exists($table,$where){
		$query="SELECT * FROM ".$table." WHERE ".$where;
		return $this->db->query($query)->num_rows()>0?TRUE:FALSE;
	}
	function write_log($log){
		$fp = fopen('C:\log.txt', 'w');
		fwrite($fp, $log);
		fclose($fp);
	}
	function import_pc($values){
		$query="INSERT INTO sc_product_catalog_offer(city_id,cosid,web_name,home_quota,home_speed_in_quota,home_speed_after_quota,roam_quota,roam_speed_in_quota,roam_speed_after_quota) VALUES ".$values;
		$this->db->query($query);
	}
	function import_pc_offer($values){
		$query="INSERT INTO sc_product_catalog_offer(pp_id,acc_ordinal,city_id,cosid,web_name,home_quota,home_speed_in_quota,home_speed_after_quota,roam_quota,roam_speed_in_quota,roam_speed_after_quota,pp_cost,duration) VALUES ".$values;
		$this->db->query($query);
	}
	function import_pp_bib($values){
		$query="INSERT INTO sc_product_catalog_buyinbulk(pp_id,bib_id) VALUES ".$values;
		$this->db->query($query);
	}
	function import_bib($values){
		$query="INSERT INTO sc_buyinbulk(bib_id,acc_ordinal,web_name,renewals,discount) VALUES ".$values;
		$this->db->query($query);
	}
	function option_item($query,$default=array()){
		$res=$this->db->query($query);
		$items=$default;
		if($res->num_rows()>0){
			$fields=$res->list_fields();
			foreach($res->result_array() as $val){
				$items[$val[$fields[0]]]=$val[$fields[1]];
			}
		}
		return $items;
	}
	function check_bib($city_id,$cosid){
		$query="SELECT * FROM sc_product_catalog_buyinbulk WHERE pp_id IN (SELECT pp_id FROM sc_product_catalog_offer WHERE city_id='".$city_id."' AND cosid='".$cosid."')";
		return $this->db->query($query)->num_rows()?1:0;
	}
	//==============PRODUCT CATALOG===============//
	function get_product_items(){
		$query="SELECT * FROM v_product_catalog";
		return $this->db->query($query)->result();
	}
	function get_product_item($acc_ordinal){
		$query="SELECT * FROM v_product_catalog WHERE acc_ordinal=".$acc_ordinal."";
		return $this->db->query($query)->row_array();
	}
	function get_product_group_option(){
		$query="SELECT group_id,group_name FROM sc_product_catalog_group";
		return $this->option_item($query);
	}
	function update_product_item($id,$acc_ordinal,$pp_name,$group_id,$is_roaming,$login_id){
		$query="UPDATE sc_product_catalog SET acc_ordinal=".$acc_ordinal.",pp_name='".$pp_name."',group_id=".$group_id.",is_roaming=".$is_roaming.",modified_by='".$login_id."',modified_date=NOW() WHERE acc_ordinal=".$id."";
		return $this->db->query($query);
	}
	function save_product_item($acc_ordinal,$pp_name,$group_id,$is_roaming,$login_id){
		try
		{
			$query="INSERT INTO sc_product_catalog(acc_ordinal,pp_name,group_id,is_roaming,created_date,created_by,is_deleted) VALUES (".$acc_ordinal.",'".$pp_name."',".$group_id.",".$is_roaming.",'".$login_id."',NOW(),0)";
			return $this->db->query($query);
		}catch (Exception $e) {
			//$e->getMessage()
			return 0;
			
		}
	}
	function delete_product_item($acc_ordinal,$login_id){
		$query="UPDATE sc_product_catalog SET is_deleted=1,modified_by='".$login_id."',modified_date=NOW() WHERE acc_ordinal=".$acc_ordinal."";
		return $this->db->query($query);
	}
	//===========END PRODUCT CATALOG==============//
	//==============BUY IN BULK================//
	function get_buy_in_bulks(){
		$query="SELECT bib_id,acc_ordinal,web_name,renewals,discount FROM sc_buyinbulk WHERE is_deleted=0";
		return $this->db->query($query)->result();
	}
	function get_buyinbulk_detail($bib_id){
		$query="SELECT bib_id,acc_ordinal,web_name,renewals,discount FROM sc_buyinbulk WHERE is_deleted=0 AND bib_id=".$bib_id."";
		return $this->db->query($query)->row_array();
	}
	function update_buyinbulk($bib_id,$acc_ordinal,$web_name,$renewals,$discount,$login_id){
		$query="UPDATE sc_buyinbulk SET acc_ordinal=".$acc_ordinal.",web_name='".$web_name."',renewals=".$renewals.",discount=".$discount.",modified_by='".$login_id."',modified_date=NOW() WHERE bib_id=".$bib_id."";
		return $this->db->query($query);
	}
	function save_buy_in_bulk($acc_ordinal,$web_name,$renewals,$discount,$login_id){
		try
		{
			$query="INSERT INTO sc_buyinbulk(acc_ordinal,web_name,renewals,discount,created_date,created_by,is_deleted) VALUES (".$acc_ordinal.",'".$web_name."',".$renewals.",".$discount.",'".$login_id."',NOW(),0)";
			return $this->db->query($query);
		}catch (Exception $e) {
			//$e->getMessage()
			return 0;
			
		}
	}
	function delete_buyinbulk($bib_id,$login_id){
		$query="UPDATE sc_buyinbulk SET is_deleted=1,modified_by='".$login_id."',modified_date=NOW() WHERE acc_ordinal=".$bib_id."";
		return $this->db->query($query);
	}
	//=============END BUY IN BULK===========//
	//============FUNCTION CAPTCHA===========//
	function generate_captcha(){
		$chars = "abcdefghijklmnopqrstuvwxyz123456789";
		$vals='';
		for($i=1;$i<=500;$i++){
			$word = '';
			for ($a = 0; $a <= 4; $a++) {
				$b = rand(0, strlen($chars) - 1);
				$word .= $chars[$b];
			}
			$vals.="('".$word."'),";
		}
		$vals=rtrim($vals,',');
		$query="INSERT INTO sc_captcha VALUES ".$vals;
		$this->db->query($query);
	}
	function get_captcha(){
		$query="SELECT captcha FROM sc_captcha ORDER BY RAND() LIMIT 1";
		$row=$this->db->query($query)->row_array();
		return $row['captcha'];
	}
	function check_captcha($captcha){
		$query="SELECT captcha FROM sc_captcha WHERE captcha='".$captcha."'";
		return $this->db->query($query)->num_rows()>0?true:false;
	}
	
	//===========END CAPTCHA===============//
	//=================DUE DATE=================//
	function get_due_dates(){
		$query="SELECT dd.l_id,dd.status_id,ss.status_name,IFNULL(dd.due_date_translate,dd.due_date_text) due_date_text FROM sc_due_date dd 
INNER JOIN sc_subscriber_status ss ON dd.l_id=ss.l_id AND dd.status_id=ss.status_id
WHERE dd.l_id=1";
		return $this->db->query($query)->result();
	}
	function get_due_date($statud_id){
		$query="SELECT dd.l_id,dd.status_id,ss.status_name,dd.due_date_translate,l.l_name FROM sc_due_date dd 
INNER JOIN sc_subscriber_status ss ON dd.l_id=ss.l_id AND dd.status_id=ss.status_id INNER JOIN sc_language l ON dd.l_id=l.l_id
WHERE dd.status_id=".$statud_id."";
		return $this->db->query($query)->result();
	}
	function update_due_date($l_id,$status_id,$translate,$modified_by){
		$query="UPDATE sc_due_date SET due_date_translate='".$translate."',modified_by='".$modified_by."',modified_date=NOW() WHERE l_id=".$l_id." AND status_id='".$status_id."'";
		$this->db->query($query);
	}
	function due_date($l_id){
		$query="SELECT status_id,IFNULL(due_date_translate,due_date_text) due_date_text FROM sc_due_date WHERE l_id=".$l_id." ORDER BY status_id";
		$res=$this->db->query($query);
	
		$data=array();
		if(!$res) return $data;
		if($res->num_rows()>0){
			foreach($res->result_array() as $d){
				$data[$d['status_id']]=$d['due_date_text'];
			}
		}
		return $data;
	}
	function get_wb_tariff($city_id,$cosid){
		$query="SELECT * FROM v_product_catalog_offer WHERE group_id=1 AND city_id=".$city_id." AND cosid=".$cosid." AND is_expired=0 ORDER BY acc_ordinal";
		return $this->db->query($query)->result_array();
	}
	function get_wb_bibs($pp_id){
		$query="SELECT * FROM v_product_catalog_buyinbulk WHERE pp_id=".$pp_id." ORDER BY renewals";
		return $this->db->query($query)->result_array();
	}
	function get_wb_bib($pp_id,$bib_ordinal){
		$query="SELECT * FROM v_product_catalog_buyinbulk WHERE pp_id=".$pp_id." AND bib_ordinal=".$bib_ordinal."";
		return $this->db->query($query)->row_array();
	}
	function get_pp_web_name($city_id,$pp_name_dr,$cosid){
		$query="SELECT web_name FROM v_product_catalog_offer WHERE city_id=20 AND cosid=1 AND pp_name_dr='".$pp_name_dr."'";
		return $this->db->query($query)->row_array();
	}
	function get_wp_pricing_options($city_id,$cosid,$l_id=0){
		$query="SELECT pp_id, web_name,pp_cost,CASE WHEN is_roaming=1 THEN roam_quota ELSE home_quota END home_quota,duration,CASE WHEN is_roaming=1 THEN roam_speed_in_quota ELSE home_speed_in_quota END home_speed_in_quota,acc_ordinal FROM v_product_catalog_offer F WHERE group_id=3 AND city_id=".$city_id." AND cosid=".$cosid." AND is_expired=0 ";
		return $this->db->query($query)->result_array();
	}
	function save_login_customer($cust_number){
		$query="INSERT INTO sc_login_customer(cust_number) VALUES('".$cust_number."')";
		$this->db->query($query);
	}
	function delete_login_customer($cust_number){
		$query="DELETE FROM sc_login_customer WHERE cust_number='".$cust_number."'";
		$this->db->query($query);
	}
	function is_login_customer($cust_number){
		$query="SELECT cust_number FROM sc_login_customer WHERE cust_number='".$cust_number."'";
		return $this->db->query($query)->num_rows()>0?true:false;
	}
	function get_refund_discount($acc_ordinal,$type){
		$query="SELECT web_name,discount FROM sc_refund_discount WHERE acc_ordinal=".$acc_ordinal." AND type='".$type."' AND is_deleted=0";
		return $this->db->query($query)->row_array();
	}
	/* function save_verify_email($cust_number,$imsi,$city_id,$email,$verify_code){
		$query="INSERT INTO ld_verify_email(cust_number,imsi,city_id,email,verify_code,created_date) VALUES('".$cust_number."','".$imsi."',".$city_id.",'".$email."','".$verify_code."',NOW())";
		$db2 = $this->load->database('landingpage', TRUE);
		$db2->query($query);
		$db2->close();
		$this->load->database('selfcare', TRUE);
	}
	function update_verify_email1($cust_number,$verify_code){
		$query="UPDATE ld_verify_email SET verify_code='".$verify_code."',created_date=NOW() WHERE cust_number='".$cust_number."'";
		$db2 = $this->load->database('landingpage', TRUE);
		$db2->query($query);
		$db2->close();
		$this->load->database('selfcare', TRUE);
	}
	function get_verify_email($cust_number){
		$query="SELECT email FROM ld_verify_email WHERE cust_number='".$cust_number."' AND modified_date IS NULL";
		$db2 = $this->load->database('landingpage', TRUE);
		$res=$db2->query($query)->row_array();
		$db2->close();
		$this->load->database('selfcare', TRUE);
		return $res;
	}
	function get_setup_email_detail($email_id,$l_id){
		$query="SELECT subject,body FROM ld_setup_email_detail WHERE email_id=".$email_id." AND ".$l_id."";
		$res=array();
		$db2 = $this->load->database('landingpage', TRUE);
		$res=$db2->query($query)->row_array();
		$db2->close();
		$this->load->database('selfcare', TRUE);
		return $res;
	} */
	//================END DUE DATE===================//
	//=============Refund AND Permanent==========//
	function get_refund_discount_list(){
		$query="SELECT * FROM sc_refund_discount WHERE is_deleted=0";
		return $this->db->query($query)->result();
	}
	function get_refund_discount_detail($bib_id){
		$query="SELECT * FROM sc_refund_discount WHERE bib_id=".$bib_id."";
		return $this->db->query($query)->row_array();
	}
	function update_refund_discount($bib_id,$acc_ordinal,$web_name,$discount,$type,$login_id){
		$query="UPDATE sc_refund_discount SET acc_ordinal=".$acc_ordinal.",web_name='".$web_name."',discount=".$discount.",type='".$type."',modified_by='".$login_id."',modified_date=NOW() WHERE bib_id=".$bib_id."";
		
		$this->db->query($query);
	}
	function save_refund_discount($acc_ordinal,$web_name,$discount,$type,$login_id){
		$query="INSERT INTO sc_refund_discount(acc_ordinal,web_name,discount,type,created_by,created_date,is_deleted) VALUES (".$acc_ordinal.",'".$web_name."',".$discount.",'".$type."','".$login_id."',NOW(),0)";
		$this->db->query($query);
	}
	function delete_refund_discount($bib_id,$login_id){
		$query="UPDATE sc_refund_discount SET is_deleted=1,modified_by='".$login_id."',modified_date=NOW() WHERE bib_id=".$bib_id."";
		$this->db->query($query);
	}
	//============END Refund AND Permanent
}
?>