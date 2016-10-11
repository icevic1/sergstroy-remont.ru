<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Questions extends Admin_Controller
{
	protected $page_id = 49;

	function __construct() 
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Question_mod', 'Question');
	}

    public function index()
	{
        $data['itemsList'] = $this->Question->all();
		
//        var_dump($data['itemsList']);die;

		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/questions/index';
		$this->load->view('template/tmpl_admin', $data);
	}

   	/**
	 * Delete staff
	 * @param int $staff_id
	 */
	public function delete($id = null)
	{
		if ($id) {
			$this->Question->delete($id);
		}
		
		redirect('admin/questions/');
	}
}