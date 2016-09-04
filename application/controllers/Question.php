<?php
class Question extends Site_Controller
{
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->config('my_config');
		$this->load->helper('pagination');
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->library('Auth');
        $this->load->helper('text');

        $this->load->model('Question_mod');
    }

    public function index()
    {
        $data['itemsList'] = $this->Question_mod->all();
        $data['titlePage'] = 'Отзывы - Все отзывы';
        $data['PAGE_TITLE'] = 'Отзывы :: Все отзывы';
        $data['BODY_CLASS'] = "home";
        $data['CONTENT']='review/index';
        $this->load->view('layout/layout', $data);
    }

    public function store()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
//$this->input->is_ajax_request()
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->form_validation->set_rules('name', 'Имя', "trim|required");
            $this->form_validation->set_rules('phone', 'Телефон', "trim|required");
            $this->form_validation->set_rules('question', 'Отзыв', "trim|required");

            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
//            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() != FALSE)	{

                $data_update = array(
                    'name' => $this->form_validation->set_value('name'),
                    'phone' => $this->form_validation->set_value('phone'),
                    'question' => $this->form_validation->set_value('question'),
                    'ip_address' => $this->input->ip_address()
                );

                $this->Question_mod->store($data_update);

                $this->load->view('partial/client_question', array('succeed'=>true));
//                var_dump("success");die;

            } else {

                $this->load->view('partial/client_question', array('succeed'=>false, 'errors'=> $this->form_validation->error_array()));
//                var_dump($this->form_validation->error_array());die;
//                $this->question_form();
            }
            //var_dump($this->input->post());
        } else {
            redirect('/review');
        }

    }

	function validate_phonenumber($value){
		$value=str_replace('_','',trim($value));
		$value=str_replace(' ','',trim($value));
		if(strlen($value)<10){
			return false;
		}else{
			return $value;
		}
	}

	/**
	 * all my new funcions
	 * @imput: int $staff_id
	 */
	public function ajax_pic_details()
	{
		$staff_id = $this->input->post('staff_id');
		$data['details'] = $this->staff->getPICByID($staff_id);
// 		var_dump($data['details']);die;
		$this->load->view('home/ajax_pic_details', $data);
	}
}