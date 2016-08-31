<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('show_menu'))
{
	function show_menu()
	{
		$CI =& get_instance();
		$CI->load->model('Selfcare_mod');
		$data['menu_items'] = $CI->Selfcare_mod->getMenuItems();
		$data['active_item'] = $CI->Selfcare_mod->getActiveMenuItem($CI->router->fetch_class(), $CI->router->fetch_method());
// 		var_dump($data['active_item'] , $data['menu_items'],  $CI->router->fetch_class(), $CI->router->fetch_method());
		return view_loader('helper/menu_view', $data, true);
	}
}

if(!function_exists('view_loader')){

	function view_loader($view, $vars=array(), $output = false){
		$CI = &get_instance();
		return $CI->load->view($view, $vars, $output);
	}
}