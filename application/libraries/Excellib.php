<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excellib {
	public function __construct()
   	{
      	require_once APPPATH.'/libraries/phpexcel/PHPExcel.php';
       	require_once APPPATH.'/libraries/phpexcel/PHPExcel/IOFactory.php';
   	}	
}
?>