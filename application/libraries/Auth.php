<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Auth
{

	function __construct()
	{
	     $CI =& get_instance();
	
	     //load libraries
	     $CI->load->database();
	     $CI->load->library("session");
	}
	
	function get_userdata() 
	{
		$CI = & get_instance ();
		
		if (! $this->logged_in ()) {
			return false;
		} else {
			$query = $CI->db->get_where ( "sm_staff", array (
					"staff_id" => $CI->session->userdata ( "staff_id" ) 
			) );
			return $query->row ();
		}
	}
	
	function logged_in() 
	{
		$CI = & get_instance ();
		if ($CI->session->userdata("staff")) return true;
		elseif ($CI->session->userdata("visitor")) return true;
		else return false;
	}
	
	function login($email, $password = null) 
	{
		$CI = & get_instance ();
		if (!$password) return false;
		$data = array (
				"email" => $email/* 'orletchi.victor@gmail.com', // */ ,
// 				"password" => md5 ( $password ) 
				"password" => $password
		);
		
		$query = $CI->db->get_where ("scsm_users", $data );
// 		var_dump ($data, $query->row());
// 		die ();
		if ($query->num_rows () !== 1) {
			return false;
		} else {
			$data = array ("logined_at" => date ( "Y-m-d H:i:s" ));
			
			$CI->db->update("scsm_users", $data );
			
			// store user id in the session
// 			$CI->session->set_userdata ( "staff_id", $query->row ()->staff_id );
			
			return $query->row ();
		}
	}
	
	function loginSub($subID, $password) 
	{
		$CI = & get_instance ();
		
		$data = array (
				"subs_id" => $subID,
				"password" => md5 ( $password ) 
		);
		
		$query = $CI->db->get_where("sm_subscribers", $data);
// 		var_dump ($data, $query->row());
// 		die ();
		if ($query->num_rows () !== 1) {
			return false;
		} else {
			$data = array (
					"logined_at" => date ( "Y-m-d H:i:s" ) 
			);
			
			$CI->db->update ( "sm_subscribers", $data );
			
			// store user id in the session
// 			$CI->session->set_userdata ( "staff_id", $query->row ()->staff_id );
			
			return $query->row ();
		}
	}
	
	function loginDealer($login, $password = null)
	{
		$CI = & get_instance ();
		if (!$password) return false;
	
		$query = $CI->db->get_where ("dealer_sallers", array (
				"login" => $login,
				"password" => $password
		) );
		// 		var_dump ($data, $query->row());
		// 		die ();
		if ($query->num_rows () !== 1) {
			return false;
		} else {
			$CI->db->update("dealer_sallers", array ("last_login" => date ( "Y-m-d H:i:s" ) ));
			return $query->row_array();
		}
	}
	
	public function recovery_saller_passwrod ($login = null, $encryptedPassword = null)
	{
		if (!$login || !$encryptedPassword) return false;
		$CI = & get_instance ();
		$CI->db->where('login', $login);
		$CI->db->limit(1);
		$CI->db->update("dealer_sallers", array ('password'=>$encryptedPassword, "change_password_time" => date ( "Y-m-d H:i:s" ) ));
		return $CI->db->affected_rows();
	}
	
	function logout() 
	{
		$CI = & get_instance ();
		$CI->session->unset_userdata ( "staff" );
	}
	
	function register($email, $password) 
	{
		$CI = & get_instance ();
		
		// ensure the email is unique
		if ($this->can_register ( $email )) {
			$data = array (
					"email" => $email,
					"password" => sha1 ( $password ) 
			);
			
			$CI->db->insert ( "users", $data );
			
			return true;
		}
		
		return false;
	}
	
	function can_register($email) 
	{
		$CI = & get_instance ();
		
		$query = $CI->db->get_where ( "users", array (
				"email" => $email 
		) );
		
		return ($query->num_rows () < 1) ? true : false;
	}
}