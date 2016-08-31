<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('message'))
	{
		function message($id)
		{
			$ci=& get_instance();
			if(isset($ci->message[$id])){
				return $ci->message[$id];
			}
			return '';
		}
	}
	if ( ! function_exists('label'))
	{
		function label($id)
		{
			$ci=& get_instance();
			if(isset($ci->label[$id])){
				return $ci->label[$id];
			}
			return '';
		}
	}
?>