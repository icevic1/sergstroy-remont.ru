<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Galleries extends Admin_Controller
{
	protected $page_id = 47;

	function __construct() 
	{
		parent::__construct();
		$this->CI = &get_instance();
		$this->load->library('cryptastic');
		$this->load->library('Acl');
		$this->load->model('Gallery_mod', 'Gallery');
		$this->load->model('Staff_mod', 'Staff');
	}
	
	function index()
	{
        $data['itemsList'] = $this->Gallery->all();
		
//        var_dump($data['itemsList']);die;

		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/galleries/index';
		$this->load->view('template/tmpl_admin', $data);
	}

    public function store()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->form_validation->set_rules('name', 'Название', "trim|required");
            $this->form_validation->set_rules('user_id', 'Привязка', "trim|numeric");
            $this->form_validation->set_rules('description', 'Описание', "trim");
            $this->form_validation->set_rules('published', 'Описание', "trim|numeric");

            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
//            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() != FALSE)	{
               /* $name = $this->form_validation->set_value('name');
                $user_id = $this->form_validation->set_value('user_id');
                $description = $this->form_validation->set_value('description');
                $published = $this->form_validation->set_value('description');*/

                $data_update = array(
                    'name' => $this->form_validation->set_value('name'),
                    'user_id' => $this->form_validation->set_value('user_id'),
                    'description' => $this->form_validation->set_value('description'),
                    'published' => $this->form_validation->set_value('description'),
                );

                if ( ($image_path = $this->upload_images($inputName = 'userfile')) ) {
                    $data_update['image'] = $image_path;
                }

                $this->Review_mod->store($data_update);
                redirect('/review');

            } else {
                $this->index();
            }
            //var_dump($this->input->post());
        } else {
//            redirect('/review');
        }

    }

	public function edit($get_id = null)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
        $data['loadedItem'] = array();

		$page_action = 'Добовляем новы';
		if ($get_id) {
			$loadedItem = $this->Gallery->get($get_id);
			if ($loadedItem) {
				$data['loadedItem'] = $loadedItem;
				$page_action = 'Правим';
			} else {
				show_error("Нету Альбома с номером <b>{$get_id}</b>, перепроверьте ваш запрос!", $status_code= 500 );
			}
		}
//		var_dump($data['loadedItem']);die;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->form_validation->set_rules('album[id]', 'ID', "trim|numeric");
            $this->form_validation->set_rules('album[name]', 'Название', "trim|required");
            $this->form_validation->set_rules('album[user_id]', 'Привязка', "trim|required|numeric");
            $this->form_validation->set_rules('album[description]', 'Описание', "trim");
            $this->form_validation->set_rules('album[published]', 'Опубликовать', "trim|numeric");

//            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            //var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
            if ($this->form_validation->run() != FALSE)	{
                /* $name = $this->form_validation->set_value('name');
                 $user_id = $this->form_validation->set_value('user_id');
                 $description = $this->form_validation->set_value('description');
                 $published = $this->form_validation->set_value('description');*/



                $data_update = array(
                    'name' => $this->form_validation->set_value('album[name]'),
                    'user_id' => $this->form_validation->set_value('album[user_id]'),
                    'description' => $this->form_validation->set_value('album[description]'),
                    'published' => $this->form_validation->set_value('album[published]'),
                );
//                var_dump($data_update);die;
                if ($get_id) {
                    $this->Gallery->save($get_id, $data_update);
                } else {
                    $this->Gallery->store($data_update);
                }
                redirect('/admin/galleries');

            } else {
                $data['loadedItem'] = $this->input->post('album');
            }
            //var_dump($this->input->post());
        }

        $usersList = $this->Staff->searchUsers(array(), 'array');
        $usersOptions = array_replace(array(''=>'---'), array_column($usersList, 'name', 'user_id'));

		$data['usersOptions'] = $usersOptions;
	
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'] . ' ' .$page_action;
		
		$data['CONTENT'] = 'admin/galleries/edit';
		$this->load->view('template/tmpl_admin', $data);
	}

	/**
	 * Delete staff
	 * @param int $staff_id
	 */
	public function delete($id = null)
	{
		if ($id) {
			$this->Gallery->delete($id);
		}
		
		redirect('admin/galleries/');
	}
	
	function ajax_get_staff()
	{
		$filter = array();
		
		$filter['is_default_responsible'] = true;
		$filter['company_id'] 	= $this->input->post('company_id', null);
		$filter['branch_id']	= $this->input->post('branch_id', null);
		$filter['activity_status'] 	= '0';
		
		$companyInfo = $this->Masteracc_model->getCompanyByID($filter['company_id']);
		
		$filteredUsers = ($companyInfo)? $this->Staff_mod->search($filter): null;
		
		
		$out = json_encode(array('companyInfo'=>$companyInfo, 'companyPics'=>$filteredUsers));
// 		if ($filter['company_id']) {
			
		
// 			var_dump($filter, $filteredUsers);
			
// 			if ($filteredUsers) {
// 				$dropDown = array('0' => 'Choose a PIC');
// 				foreach ($filteredUsers as $item) {
// 					$dropDown[$item->staff_id] = $item->staff_name;
// 				}
// 			}
// 		}
	
// 		echo form_dropdown('staff_id', $dropDown, 0);
		header('Content-Type: application/json');
		echo $out;
	}
}