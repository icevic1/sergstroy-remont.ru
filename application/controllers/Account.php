<?php
class Account extends My_Controller 
{
	private $debugEmail	 = null; //'orletchi.victor@gmail.com';
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		$this->load->config('my_config');
		$this->load->helper('webservice');
		$this->load->library('cryptastic');
		$this->load->library('Auth');
		$this->load->model('Staff_mod', 'staff');
		$this->load->library('Aes');
	}
	
	function index()
	{
		if($this->session->userdata('staff') || $this->session->userdata('visitor') == true){
			redirect('home');
		}
// 		$this->load->helper('email');
// 		var_dump(send_email($from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Telecom'), array('orletchi.victor@gmail.com'), 'test subject', 'test body', true));die;
		
		$this->load->library('Acl');
		$data['BODY_CLASS']="login";
		$data['PAGE_TITLE']="Account";
		$data['LOGIN']=true;
// 		var_dump($this->acl->can_read(null, 3 /*STS main resources*/));die;
		$err_msg = $this->session->userdata('msg');
		$this->session->unset_userdata('msg');
		
		$data['err_msg'] = $err_msg;
		
		$data['CONTENT'] = 'account/login';
		if(isset($_GET['layout']) && $_GET['layout'] == 'def') {
			$this->load->view('template/tmpl_login',$data);	
		} else{
			$this->load->view('layout/layout_login',$data);	
		}
	}
	
	function login()
	{
// 		GetCorpCustomerData('69333265', true)
		if($this->session->userdata('staff')){
			redirect('home');
		}
		$this->load->helper('ngbssquery');
		$this->load->model('Subscriber_mod');

		$data['CONTENT']='account/login';
		$isSub = false;
		
		$this->form_validation->set_rules('txt_email', 'email', 'trim|required|valid_email');

		$this->form_validation->set_rules('txt_pwd', 'Password', 'trim|required');
// 		$this->form_validation->set_rules('txt_word', 'Captcha', 'trim|required|callback_check_captcha');
		$this->form_validation->set_rules('txt_word', 'Captcha', 'trim');
		
		$this->form_validation->set_message('required', 'msg_required_field');
		$this->form_validation->set_message('password_check', 'msg_validate_pwd');
		$this->form_validation->set_message('check_captcha', 'msg_invalid_captcha');
	
		$login_email = $this->input->post ( 'txt_email' );
		$next_url = urldecode($this->input->post('next_url'));
		$pwd = $this->input->post ( 'txt_pwd' );
		$rem = $this->input->post ( 'chk_remember' );

// 		$staff_acc = $this->auth->login($login_email, $encryptedPassword);
// 		var_dump($staff_acc);die;
		
		if($this->form_validation->run() !== false) {
			/*
			 * Encrypt password and keep to user
			 */
			$this->aes->setKey($this->config->item('aes_key'));
			$this->aes->setBlockSize($this->config->item('aes_size'));
			$this->aes->setData($pwd);
			
			$encryptedPassword = $this->aes->encrypt();
			
			if (($staff_acc = $this->auth->login($login_email, $encryptedPassword))){
				
				if($rem) {
					$this->session->sess_expiration = 60*60*24;    // expires in 1 day
				}
				$staff = $this->staff->getByID($staff_acc->user_id, true);
				$staff['roles'] = $this->staff->getUserRoles($staff_acc->user_id, true);

				unset($staff['password']);
				
				$this->session->set_userdata('staff', $staff);
				$redirect_to = ($next_url)?$next_url:'home';
			} else {
				$this->session->set_userdata('msg', label('msg_invalid_login'));
				$redirect_to = 'account'.(($next_url)? '/?current_url='.urlencode($next_url):'');

				redirect ($redirect_to);
			}
// 			print $redirect_to;
			
			$this->load->library('Acl');
			
			/*
			 * Redirect to main page if has access
			 * or if have next url and allow to sts, then go to sts else to home
			 */
			if ($this->acl->can_view(null, 1 /*1 Main page*/)) {
				redirect(($next_url)?$next_url:'home');
				
			/*
			 * Not have permision to any content, show error 
			 */
			} else {
				show_error("You haven't permission to see this page", $status_code= 503, 'Permission denied');
			}
			
// 			redirect($redirect_to);
		} else {
			$msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong><ul>';
// 			$errors = $this->form_validation->error_array ();var_dump($errors);die;
			
			if (form_error ( 'txt_email' ) || form_error ( 'txt_pwd' )) {
				$msg .= "<li> " . label('msg_invalid_login') . "</li>";
			}
			
			if (form_error ( 'txt_word' )) {
				$msg .= "<li> " . label('msg_invalid_login') . "</li>";
			}
			
			$msg .= '</ul></div>';
			$this->session->set_userdata ( 'msg', $msg );
			redirect ('account'.(($next_url)? '/?current_url='.urlencode($next_url):''));
		}
	}

	function logout()
	{
		$this->selfcare_mod->save_customer_log('logout');
		$this->session->unset_userdata ( "staff" );
		$this->session->unset_userdata ( "visitor" );
		$this->session->sess_destroy();
		redirect(site_url('account'));
	}
	
	
	function check_account()
	{
		$out = array('status'=>1, 'msg'=>'Error: an error has occurred!');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->load->helper('email');
			$this->load->helper('ngbssquery');
			$this->load->model('Staff_mod');
			$this->load->model('Subscriber_mod');
			
			$login_name = $this->input->post('login_name'); //orletchi.victor@gmail.com
			$captcha = $this->input->post('captcha');
			
// 			var_dump($this->input->post());
// 			var_dump(filter_var($login_name, FILTER_VALIDATE_EMAIL));
			
			if( $this->check_captcha($captcha)) { 
			
				if (is_numeric($login_name)) {
					
					$serviceNumber = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $login_name));
					
					/* Check if login name is phone number and if have customer group information*/
// 					$SubsInfo = Subscriber_Info($serviceNumber);
					
					if (false == GetCorpCustomerData($serviceNumber, true) ) {
						$out['status'] = 1;
						$out['msg'] = 'Sorry recovery password by this phone number is not available.';
						GOTO END;
					}
					
					$verify_code = rand(10000, 99999);
					$this->selfcare_mod->save_verify_code($serviceNumber, $account_type = '1', $verify_code, $status = '0');
					
					if(false == send_sms($serviceNumber, "Smart Care verification code is: {$verify_code}\nThank you for using Smart services.")) {
						$out['status'] = 1;
						$out['msg'] = 'Sorry we can not send verification code to this phone number. Please verify phone number is right and try again!';
						GOTO END;
					}
					
					$out['status'] = 0;
					$out['msg'] = '<strong>Succeed!</strong><br />We have sent SMS with verification code to your phone number.<br /> Please check and enter in the field below.'; //.$verify_code;
	
				/* PIC or KAM recovery passord, send confirm code to email */
				} elseif (filter_var($login_name, FILTER_VALIDATE_EMAIL) && ($staffInfo = $this->Staff_mod->getByEmail($login_name))) {
	
					$notify = $this->staff->getNotify(14); //Verification code
					$verify_code = rand(10000, 99999);
					
					$this->selfcare_mod->save_verify_code($staffInfo['user_id'], $account_type = '0', $verify_code, $status = '0');
					
					$replacement = array('{verification_code}' => $verify_code, '{home_link}' => site_url());
	
					$from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Care');
					$to = $this->debugEmail? $this->debugEmail : $staffInfo['email'];
					
					$subject = $notify['Title'];
					$body = str_replace(array_keys($replacement), array_values($replacement), $notify['MailText']);
					
					if(send_email($from, $to, $subject, $body, true) == false) {
						$this->selfcare_mod->save_log('Email approve not send.');
					}
					
					$out['status'] = 0;
					$out['msg'] = '<strong>Succeed!</strong><br />We have sent the verification code to your e-mail address.<br /> Please check and enter in the field below.'; //.$verify_code;
	// 				var_dump($body, $notify, $staffInfo);
					
				} else {
					$out['msg'] = 'Please specify the account login name correctly.';
				}
			} else {
				$out['msg'] = message('msg_invalid_captcha');
			}
		}
		END:
		header('Content-Type: application/json');
		echo json_encode($out, JSON_HEX_QUOT | JSON_HEX_TAG);
// 		encrypt_java('123')
	}
	
	function verify_code()
	{
		$out = array('status'=>1, 'msg'=>'Error: an error has occurred!');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if ($this->input->is_ajax_request()) {

				$this->load->model('Staff_mod');
				$this->load->model('Subscriber_mod');

				$login_name = $this->input->post('login_name'); //'orletchi.victor@gmail.com';
				$verify_code = $this->input->post('verify_code');
				
				if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
					$account_type = Staff_mod::$ACCOUNT_TYPE;
				} else {
					$login_name = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $login_name));
					$account_type = Subscriber_mod::$ACCOUNT_TYPE;
				}

				if($this->selfcare_mod->check_verify_code($login_name, $verify_code, $account_type)) {
					$out = array('status'=> 0, 'msg'=>'<strong>Succeed!</strong><br />Verification code are matched successfully. Please set your new password and press save button.');
				} else {
					$out = array('status'=> 1, 'msg'=>'<strong>Failed!</strong><br />Sorry, verification code are not match. Please try again.');
				}
			} //end is ajax
		} //end is post
		
		header('Content-Type: application/json');
		echo json_encode($out, JSON_HEX_QUOT | JSON_HEX_TAG);
	}	
	
	function save_password()
	{
		$out = array('status'=>1, 'msg'=>'Error: an error has occurred!');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// 			if ($this->input->is_ajax_request()) {
				
				$this->load->model('Staff_mod');
				$this->load->model('Subscriber_mod');
				$this->load->helper('email');
				$this->load->helper('ngbssquery');
				// var_dump($this->input->post());
				
				$login_name = $this->input->post('login_name'); //'orletchi.victor@gmail.com';
				$verify_code = $this->input->post('verify_code');
				$new_password = $this->input->post('new_password');

				if ($new_password) {
					if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
						$account_type = Staff_mod::$ACCOUNT_TYPE;
					} else {
						$account_type = Subscriber_mod::$ACCOUNT_TYPE;
						$login_name = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $login_name));
					}

					if($this->selfcare_mod->check_verify_code($login_name, $verify_code, $account_type)) {
						if (is_numeric($login_name)) {
							
							/*
							 * Password validation
							 */
							if ($new_password && false == preg_match('/^[0-9]{6}$/', $new_password)) {
								$out['status'] = 1;
								$out['msg'] = '<strong>Failed!</strong><br />Password should have 6 digits. Try again.';
								GOTO END;
							}
							/* Check if phone number have customer group information*/
							if (false == ChangeSubscriberPassword($login_name, $new_password)) {
								$out['status'] = 1;
								$out['msg'] = '<strong>Failed!</strong><br />Sorry password can not be changed. Try later.';
								GOTO END;
							}
								
							$this->selfcare_mod->save_verify_code($login_name, $account_type = '1', $verify_code, $status = '1');
							
							/* Send SMS */
							send_sms($login_name, "Your new password for Smart Care is: {$new_password}\nThank you for using Smart services.");
							
							$out['status'] = 0;
							$out['msg'] = '<strong>Succeed!</strong><br />Smart Care password successfuly changed.<br />Thank you for using Smart services.';
						
							/* PIC or KAM recovery passord, send confirm code to email */
						} elseif (filter_var($login_name, FILTER_VALIDATE_EMAIL) && ($staffInfo = $this->Staff_mod->getByEmail($login_name))) {
						
							$staffInfo = $this->Staff_mod->getByEmail($login_name);
							
							/*
							 * save status of current verification code to used
							 */
							$this->selfcare_mod->save_verify_code($staffInfo['user_id'], $account_type, $verify_code, $status = '1');
							
							/*
							 * Encrypt password and keep to user
							 */
							$this->aes->setKey($this->config->item('aes_key'));
							$this->aes->setBlockSize($this->config->item('aes_size'));
							$this->aes->setData($new_password);
							
							$encryptedPassword = $this->aes->encrypt();
							
							$this->Staff_mod->updateUser($staffInfo['user_id'], array('password'=>$encryptedPassword));
							
							/*
							 * Get and prepare notification to send to user, about his password was changed
							 */
							$notify = $this->staff->getNotify(15); //The password has been changed
							
							$replacement = array('{new_password}' => $new_password, '{home_link}' => site_url());
							
							$from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Care');
							$to = $this->debugEmail? $this->debugEmail : $staffInfo['email'];
							
							$subject = $notify['Title'];
							$body = str_replace(array_keys($replacement), array_values($replacement), $notify['MailText']);
							
							if(send_email($from, $to, $subject, $body, true) == false) {
								$this->selfcare_mod->save_log('Email approve not send.');
							}
			
							$out = array('status'=> 0, 'msg'=>'<strong>Succeed!</strong><br />We have sent the new password to your e-mail address.');
							
						}
						
					} else {
						$out = array('status'=> 1, 'msg'=>'<strong>Failed!</strong><br />Sorry, verification code are not match. Please try again.');
					}
				} else {
					$out = array('status'=> 1, 'msg'=>'<strong>Failed!</strong><br />Sorry, the password has not been blank. Please try again.');
// 				} // end if not passwrod
			} //end is ajax
		} //end is post
		
		END:
		header('Content-Type: application/json');
		echo json_encode($out, JSON_HEX_QUOT | JSON_HEX_TAG);
		
		/*
		 $this->load->library('Aes');
		
		 $imputText = "571728";
		 $imputKey = "1234567890111110";
		 $blockSize = 128;
		 $aes = new AES($imputText, $imputKey, $blockSize);
		 $aes->setIV('1234567890abcdef');
		 $enc = $aes->encrypt();
		 $aes->setData($enc);
		 $dec=$aes->decrypt();
		
		 echo $imputText."<br/>";
		 echo "After encryption: ".$enc."<br/>";
		 echo "After decryption: ".$dec."<br/>";
		 die;
		 */
	}
	
	function recovery_password()
	{
		$out = array('status'=>1, 'msg'=>'Error: an error has occurred!');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($this->input->is_ajax_request()) {
	
			$this->load->helper('email');
			$this->load->helper('ngbssquery');
			$this->load->model('Staff_mod');
			$this->load->model('Subscriber_mod');
	
			$login_name = $this->input->post('login_name'); //'orletchi.victor@gmail.com';
			$verify_code = $this->input->post('verify_code');
	
			if (filter_var($login_name, FILTER_VALIDATE_EMAIL)) {
				$account_type = Staff_mod::$ACCOUNT_TYPE;
			} else {
				$account_type = Subscriber_mod::$ACCOUNT_TYPE;
			}
	
			if($this->selfcare_mod->check_verify_code($login_name, $verify_code, $account_type) && ($staffInfo = $this->Staff_mod->getByEmail($login_name))) {
				
				/*
				 * save status of current verification code to used
				*/
				$this->selfcare_mod->save_verify_code($staffInfo['user_id'], $account_type, $verify_code, $status = '1');
				
				/*
				 * Encrypt password and keep to user
				*/
				$this->aes->setKey($this->config->item('aes_key'));
				$this->aes->setBlockSize($this->config->item('aes_size'));
				$this->aes->setData($staffInfo['password']);
				//orletchi.victor@gmail.com
				$decryptedPassword = $this->aes->decrypt();
				//var_dump($encryptedPassword, $staffInfo);die;
				
				/*
				 * Get and prepare notification to send to user, about his password was changed
				*/
				$notify = $this->staff->getNotify(16); //The password has been changed
				
				$replacement = array('{password}' => $decryptedPassword, '{home_link}' => site_url());
				
				$from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Care');
				$to = $this->debugEmail? $this->debugEmail : $staffInfo['email'];
				
				$subject = $notify['Title'];
				$body = str_replace(array_keys($replacement), array_values($replacement), $notify['MailText']);
				
				if(send_email($from, $to, $subject, $body, true) == false) {
					$this->selfcare_mod->save_log('Email approve not send.');
				}
				
				$out = array('status'=> 0, 'msg'=>'<strong>Succeed!</strong><br />We have sent the password to your e-mail address.');
				
			} else {
				$out = array('status'=> 1, 'msg'=>'<strong>Failed!</strong><br />Sorry, verification code are not match. Please try again.');
			}
		} //end is ajax
		} //end is post
	
		header('Content-Type: application/json');
		echo json_encode($out, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function get_captcha(){
		//$word=$this->selfcare_mod->get_captcha();
		$RandomStr = md5(microtime());// md5 to generate the random string
		$ResultStr = substr($RandomStr,0,5);//trim 5 digit
		$word=$ResultStr;
		$this->session->set_userdata('word',$word);
		$img_src=str_replace('system', 'public',BASEPATH).'images/captcha.png';
		$img_desc=str_replace('system', 'public',BASEPATH).'captcha/'.$word.'.png';
		$config['source_image'] =$img_src;
		$config['wm_text'] = $word;
		$config['wm_type'] = 'text';
		$config['wm_font_color'] = '000000';
		$config['wm_hor_alignment'] = 'center';
		$config['new_image'] = $img_desc;
		$this->load->library('image_lib', $config);
		$this->image_lib->watermark();
		return $word;
	}

	function check_captcha($word){
		if($this->session->userdata('word')){
			return $word==$this->session->userdata('word')?true:false;
		}else{
			return false;
		}
		//return $this->selfcare_mod->check_captcha($word);
	}

	function generate_captcha(){
		/*$this->remove_captcha();*/
		$word=$this->get_captcha();
		$capt_img='<img src="'.base_url('public/captcha/'.$word.'.png').'" border="0" />';
	
		//$captcha=$this->selfcare_mod->get_captcha();
		//$capt_img='<div class="ot-captcha-img">'.$captcha.'</div>';
		echo json_encode(array('captcha'=>$capt_img));
	}
}