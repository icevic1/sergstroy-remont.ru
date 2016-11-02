<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class My_Controller extends CI_Controller 
{
	var $message;
	var $label;
	var $var_session;
	var $l_id;
	var $segm = 0;
	
	function __construct() 
	{
		parent::__construct ();
		$this->load->model ( 'selfcare_mod' );
		$this->load->library ( 'user_agent' );

		$language = $this->selfcare_mod->get_language_by_short_name ( $this->lang->lang () );
		$this->l_id = $language ? $language ['l_id'] : 1;
		$this->segm = $this->uri->segment ( 1, '' ) == 'en' ? 4 : 3;

		$this->message = array(); //$this->selfcare_mod->get_messages ( $this->l_id );
		$this->label = $this->selfcare_mod->get_captions ( $this->l_id );
		
		//$this->var_session = $this->selfcare_mod->get_session_variable ();
		$this->load->helper ( 'translate' );
	}
	
	public function empty2null($array = array())
	{
		return array_map(function($value) {
			switch (true) {
				case ($value===''): $out = NULL; BREAK;
				case ($value==='0' || $value===0): $out = $value; BREAK;
				default: $out = $value;
			}
			return $out;
		}, $array);
	}
	
	public function null2empty($array = array())
	{
		return array_map(function($value) {
			return (is_null($value))? '': $value;
		}, $array);
	}
}

class Admin_Controller extends My_Controller 
{
	var $login_name;
	
	function __construct() 
	{
		parent::__construct ();
		
		if (! $this->session->userdata ( 'login_name' ) && ! strstr ( current_url (), 'admin/account' )) {
			redirect ( site_url ( 'admin/account' ) );
		}
		$this->login_name = $this->session->userdata ( 'login_name' );
	}
	
}

class Site_Controller extends My_Controller 
{
	function __construct() 
	{
		parent::__construct ();

        $this->load->model('SiteSettings_mod', 'SiteSettings');
        //get your data
        $global_data = array('siteSettings'=> $this->SiteSettings->get());

        //Send the data into the current view
        $this->load->vars($global_data);

		/*if (! $this->input->is_ajax_request ()) {
		    print "require login"; die;
// 			var_dump($this->session->userdata ( 'visitor'));die;
			if ((!$this->session->userdata('staff' ) && !$this->session->userdata('visitor' )) && ! strstr(current_url(), 'account' )) {
				
				$current_url = $_SERVER['QUERY_STRING'] ? uri_string() .'?'.$_SERVER['QUERY_STRING'] : uri_string();
// 				var_dump($url, current_url(), uri_string());die;
				$current_url = urlencode($current_url);
				redirect ('/account?current_url='.$current_url);
			}
		}*/
	}
}