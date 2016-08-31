<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//======================SEND EMAIL===================//
	if ( ! function_exists('send_email'))
	{
		function send_email($from,$to,$subject,$message, $debug = false)
		{
			$ci=& get_instance();
			$ci->load->config('my_config');
// 			var_dump($ci->config->item('email_config'));die;
			$ci->load->library('email', $ci->config->item('email_config'));
// 			var_dump( $ci->config->item('email_config'));die;
			
			$ci->email->set_newline("\r\n");
			if (is_array($from) && isset($from['email']) && isset($from['name'])) { 
				$ci->email->from($from['email'], $from['name']);
			} else {
				$ci->email->from($from);
			}
			$ci->email->to($to);
			$ci->email->subject($subject);
			$ci->email->message($message);
			
			if(!($sendResponse = $ci->email->send(false)) && $debug) {
				show_error($ci->email->print_debugger());
			}
			
			return $sendResponse;
		}
	}