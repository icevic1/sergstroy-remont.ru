<?php
class Selfcare_mod extends CI_Model
{
	function __construct(){
		parent::__construct();
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
	
	public function updateCaption($id = null, $l_id = null, $data_update)
	{
		if (!$id || !$l_id || !$data_update) return 0;
		$this->db->where('id', $id);
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
	function get_caption_list($l_id = 1){
		$query="SELECT id, capt_id,IFNULL(translate,caption) caption,caption_type,in_pages FROM sc_caption WHERE l_id={$l_id} ORDER BY created_at desc";
		return $this->db->query($query)->result();
	}
	function get_caption($capt_id){
		$query="SELECT l.l_id,capt_id,caption,translate,l.l_name,caption_type,in_pages FROM sc_caption b INNER JOIN sc_language l ON b.l_id=l.l_id WHERE capt_id='".$capt_id."' ORDER BY l.l_id";
		$result = $this->db->query($query)->result();
// 		print $lastQuery = $this->db->last_query();
		return $result;
	}
	function get_caption_id($id){
		$query="SELECT id, l.l_id,capt_id,caption,translate,l.l_name,caption_type,in_pages FROM sc_caption b INNER JOIN sc_language l ON b.l_id=l.l_id WHERE id='".$id."' ORDER BY l.l_id";
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

	
	//=================DYNAMIC PAGE BLOCK================//
	function get_dynamic_pages(){
		$query="SELECT pg_id,pg_name,pg_type,is_public,pg_url, pg_content FROM sc_dynamic_page ORDER BY pg_type";
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
		$query="INSERT INTO sc_dynamic_page(pg_name,pg_url,is_public,pg_script,created_by,created_date) VALUES ('".$pg_name."','".$pg_url."',".$is_public.",'".$pg_script."','".$login_name."',".$pg_script.",NOW())";
		$this->db->query($query);
		return $this->db->insert_id();
	}
	function update_dynamic_page($pg_id,$pg_name,$pg_url,$is_public,$pg_script,$login_name, $pg_content){
		$query1="UPDATE sc_dynamic_page SET pg_name='".$pg_name."',pg_url='".$pg_url."',is_public=".$is_public.",pg_script='".$pg_script."',modified_by='".$login_name."',pg_script='".$pg_script."',modified_date=NOW() WHERE pg_id=".$pg_id."";
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
//=================DYNAMIC PAGE BLOCK================//
    //==============BLOCK PAGE===============//
    function get_blocks($pg_id){
        $query="SELECT A.block_id,A.block_name,A.remark,CASE WHEN B.pg_id IS NOT NULL THEN 'checked=\"checked\"' ELSE '' END chk  FROM sc_block A LEFT JOIN (SELECT * FROM sc_page_block WHERE pg_id=".$pg_id.") B ON A.block_id=B.block_id";
        return $this->db->query($query)->result();
    }
    function save_block_page($pg_id,$block_id_arr,$user_name){
        $query1="DELETE FROM sc_page_block WHERE pg_id=".$pg_id."";
        $this->db->query($query1);
        $values='';
        for($i=0;$i<sizeof($block_id_arr);$i++){
            $values.="(".$pg_id.",".$block_id_arr[$i].",'".$user_name."',NOW()),";
        }
        $values=rtrim($values,',');
        $query2="INSERT INTO sc_page_block(pg_id,block_id,modified_by,modified_date) VALUES ".$values;
        $this->db->query($query2);
    }
    function get_block($pg_id){
        $query="SELECT block_id FROM sc_page_block WHERE pg_id=".$pg_id."";
        $res=$this->db->query($query)->result_array();
        $block=array();
        if($res){
            foreach($res as $val){
                $block[$val['block_id']]=true;
            }
        }
        return $block;
    }
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

	//==============SAVE CUSTOMER CONTACT EMAIL================//
	function send_email($imsi,$cust_name,$email_from,$email_to,$email_subject,$email_body,$send_status){
		$query="INSERT INTO sc_customer_contact(imsi,cust_name,email_from,email_to,email_subject,email_body,send_status,send_date) VALUES ('".$imsi."','".$cust_name."','".$email_from."','".$email_to."','".$email_subject."','".$email_body."','".$send_status."',NOW())";
		$this->db->query($query);
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

}
?>