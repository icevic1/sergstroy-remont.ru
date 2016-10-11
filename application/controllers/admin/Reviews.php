<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reviews extends Admin_Controller
{
	protected $page_id = 48;

	function __construct() 
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Review_mod', 'Review');
	}

    public function index()
	{
        $data['itemsList'] = $this->Review->all();
		
//        var_dump($data['itemsList']);die;

		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/reviews/index';
		$this->load->view('template/tmpl_admin', $data);
	}

   	/**
	 * Delete staff
	 * @param int $staff_id
	 */
	public function delete($id = null)
	{
		if ($id) {
			$this->Review->delete($id);
		}
		
		redirect('admin/reviews/');
	}
	
    /**
     * Change status to rejected and keep reason
     */
    public function change_field()
    {
        $id = $this->input->get('id');
        $field = $this->input->get('field');
        $value = $this->input->get('value');

//        var_dump($this->input->get('photo_id'));die;

        $response = array('msg'=>"<stron>Внимание</stron> Произошла ошибка!", "code"=>1);
        if ( $id && $field && $this->input->is_ajax_request())
        {
            if($this->Review->update($id, array($field=>$value)))
            {
                $response['msg'] = "<stron>Поздровляю!<strong> Измения были успешна сохранены!";
                $response['code'] = 0;
            }
        }
        else
        {
            $this->output->set_status_header('404');
            show_404();
        }
        echo json_encode($response);
    }
}