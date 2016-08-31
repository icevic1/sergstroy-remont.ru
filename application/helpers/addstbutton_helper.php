<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('new_ticket_button'))
{
	function new_ticket_button()
	{
// 		$CI =& get_instance();
// 		$CI->load->model('Filter_mod', 'filter');
		$data = array();
		
		return view_loader('helper/new_ticket_button', $data, true);
	}
}

if(!function_exists('view_loader')){

	function view_loader($view, $vars=array(), $output = false){
		$CI = &get_instance();
		return $CI->load->view($view, $vars, $output);
	}
}