<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$config['msg_succeed']="Save succeed.";
	$config['msg_delete']="Delete succeed.";
	$config['msg_save_fail']='Save fail.';
	$config['conn_timeout']=400;
	$config['resp_timeout']=400;
	
	$config['smpp_from'] = '888';
	$config['smpp_host'] = '10.12.9.70';
	$config['smpp_port'] = 15019;
	$config['smpp_user'] = 'creics';
	$config['smpp_password'] = 'cr35it';
	$config['smpp_system_type'] = '';
	
	$config['aes_key'] = '1234567890111110';
	$config['aes_size'] = 128;
	
	$config['email_config'] = array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.smart.com.kh',
			'smtp_port' => 25,
			'smtp_crypto' => '',
			'smtp_user' => '',
			'smtp_pass' => '',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'wordwrap' => TRUE
	);
	
	if(getenv('ENV_DEV')){
		//==========================OSNOVA======================//
		//$config['server_ip']='http://192.168.6.90:90/ws_server_test/server.php';
		$config['login']='';
		
		/*$config['email_config']=array('protocol' => 'smtp','smtp_host' => 'smtp.gmail.com','smtp_port' => 465,'smtp_crypto' => 'ssl',
				'smtp_user' => 'chivtimeng@gmail.com','smtp_pass' => '000007it','mailtype'  => 'html','charset'   => 'utf-8',
				'wordwrap' => TRUE
		);*/
	}else{

		//$config['email_config']=array('protocol' => 'smtp','smtp_host' => '93.180.136.36','smtp_port' => 25,'_smtp_auth' => false, 'mailtype'  => 'html','charset'   => 'utf-8','wordwrap' => TRUE);
	}
?>
