<?php
class Error extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	function index(){
		//$this->load->view('error/index');
// 		print "<pre>";debug_print_backtrace();
		$this->output->set_status_header('404');
// 		show_error('mesage');
		show_404();
	}
}
?>