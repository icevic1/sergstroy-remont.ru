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
		$this->load->model('Photo_mod', 'Photo');
		$this->load->model('Staff_mod', 'Staff');
	}

    public function index()
	{
        $data['itemsList'] = $this->Gallery->all();
		
//        var_dump($data['itemsList']);die;

		$data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
		$data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
		$data['PAGE_TITLE'] = $data['per_page']['page_name'];
		$data['CONTENT']='admin/galleries/index';
		$this->load->view('template/tmpl_admin', $data);
	}

	public function view($gallery_id)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if ($gallery_id) {
            $albumItem = $this->Gallery->get($gallery_id);
            if ($albumItem) {
                $data['albumItem'] = $albumItem;
                $data['albumPhotos'] = $albumPhotos = $this->Gallery->getPhotos($gallery_id);
            } else {
                show_error("Нету Альбома с номером <b>{$gallery_id}</b>, перепроверьте ваш запрос!", $status_code= 500 );
            }
        }
//var_dump($albumItem);die;
        $data['menus'] = $this->selfcare_mod->get_menu($this->login_name);
        $data['per_page'] = $this->selfcare_mod->get_perm_per_page($this->login_name, $this->page_id);
        $data['PAGE_TITLE'] = $data['per_page']['page_name'];
        $data['CONTENT']='admin/galleries/view';
        $this->load->view('template/tmpl_admin', $data);
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
            $this->form_validation->set_rules('album[event_date]', 'Дата События', "trim|date");
            $this->form_validation->set_rules('album[user_id]', 'Привязка', "trim|required|numeric");
            $this->form_validation->set_rules('album[description]', 'Описание', "trim");
            $this->form_validation->set_rules('album[published]', 'Опубликовать', "trim|numeric");

//            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            //var_dump($this->form_validation->run(), $this->form_validation->error_array());die;
            if ($this->form_validation->run() != FALSE)	{

                $data_update = array(
                    'name' => $this->form_validation->set_value('album[name]'),
                    'user_id' => $this->form_validation->set_value('album[user_id]'),
                    'event_date' => $this->form_validation->set_value('album[event_date]'),
                    'description' => $this->form_validation->set_value('album[description]'),
                    'published' => $this->form_validation->set_value('album[published]'),
                );

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
	
    public function upload()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->form_validation->set_rules('gallery_id', 'ID Галерея', "trim|required");
            $this->form_validation->set_rules('images[]', 'Картинки', "trim");

//            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
//            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
//            var_dump($_POST, $_FILES, $this->form_validation->run(), $this->form_validation->error_array());die;
            if ($this->form_validation->run() != FALSE)	{
                $gallery_id = $this->form_validation->set_value('gallery_id');
                $data_update = array('gallery_id' => $gallery_id);

                if ( ($images_path = $this->upload_images($inputName = 'images', $gallery_id)) ) {
//                    var_dump($images_path);die;
                    $uploadedCount = count($images_path);
                    if ($uploadedCount)
                    foreach ($images_path as $item) {
                        $data_update = array_replace(array('gallery_id' => $gallery_id), $item);
                        $this->Photo->store($data_update);
                    }
                    $this->session->set_userdata("msg", "<strong>Поздравляем!</strong> Успешно загруженно {$uploadedCount} фотографий!");

                } else {
                    echo "Error: file not uploade!";die;
                }
//                var_dump($data_update, $image_path);die;

                redirect("/admin/galleries/view/{$gallery_id}/#photo_container");

            } else {
                $this->view();
            }
            //var_dump($this->input->post());
        } else {
            var_dump($this->form_validation->error_array());
//            redirect(current_url());
        }

    }

    protected function upload_images($inputName = 'images', $gallery_id)
    {
        $uploadFolder = "/public/images/galleries";
        $uploadFolderFull = FCPATH . "$uploadFolder/original";
        $final_files_data = array();
        /*$config['upload_path'] = FCPATH . "$uploadFolder/original";
        $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $config['file_name'] = time() . '.' . strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));;*/

        $this->load->library('upload');
//        $this->upload->initialize($config);
        if ( ! is_dir($uploadFolderFull)) {
            if ( ! mkdir ($uploadFolderFull, 0777, TRUE)) {
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
            if ( ! is_really_writable($uploadFolderFull)) {
                if ( ! chmod($uploadFolderFull, 0777)) {
                    $this->set_error('upload_not_writable', 'error');
                    return FALSE;
                }
            }
        }

        $number_of_files_uploaded = isset($_FILES[$inputName])?count($_FILES[$inputName]['name']): 0;

        // Faking upload calls to $_FILE
        if ($number_of_files_uploaded)
        for ($i = 0; $i < $number_of_files_uploaded; $i++) {

            /*var_dump($i,$inputName, $_FILES[$inputName]['name'][$i],
            !isset($_FILES[$inputName]['name'][$i]) ,
            $_FILES[$inputName]['error'][$i] != 0,
            $_FILES);
            die;*/

            if (!isset($_FILES[$inputName]['name'][$i]) || $_FILES[$inputName]['error'][$i] != 0) {
                echo "Error: File $i was not found!";
                continue;
            }

            $_FILES['userfile']['name']     = $_FILES[$inputName]['name'][$i];
            $_FILES['userfile']['type']     = $_FILES[$inputName]['type'][$i];
            $_FILES['userfile']['tmp_name'] = $_FILES[$inputName]['tmp_name'][$i];
            $_FILES['userfile']['error']    = $_FILES[$inputName]['error'][$i];
            $_FILES['userfile']['size']     = $_FILES[$inputName]['size'][$i];
//            var_dump($_FILES);die;
            /*$random_digit = rand(00,99999);
            $ext = strtolower(substr($_FILES['userfile']['name'], strpos($_FILES['userfile']['name'],'.'), strlen($_FILES['userfile']['name'])-1));
            $file_name = $random_digit . $ext;*/
            $file_name = time(). '_' .rand(00,99999) ."_{$gallery_id}". '.' . strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));



            $config = array(
                'file_name'     => $file_name,
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 10000,
                'encrypt_name'     => TRUE,
                'overwrite'     => TRUE,
                'upload_path'   => FCPATH . "$uploadFolder/original"
            );

            $this->upload->initialize($config);
//            var_dump($_FILES['userfile'], $file_name);
            if ( ! $this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
                var_dump($error);
                return false; //$this->load->view('submit', $error);
            } else {

                // Continue processing the uploaded data
//                $data['upload_data'] = array('upload_data' => $this->upload->data());
                $file_name = $this->upload->file_name;

                $resize_url = $this->image_resize(1024, 768, $uploadFolder, $file_name);
                $thumb_url = $this->image_crop(260,180, $uploadFolder, $file_name);
                // var_dump($resize_url);die;

                $final_files_data[] = array('photo'=>$resize_url, 'thumb' => $thumb_url);
            }
        } //endfor;

        return $final_files_data;
    }

    protected function image_resize($new_w, $new_h, $uploadFolder, $file_name)
    {
        $img_src = FCPATH ."$uploadFolder/original/$file_name";
        $img_resize = FCPATH ."$uploadFolder/$file_name";

        $config['image_library'] = 'gd2';
        $config['source_image'] = $img_src;
        $config['new_image'] = $img_resize;
//        $config['create_thumb'] = true;
//        $config['thumb_marker'] = "_{$new_w}x{$new_h}";
        $config['maintain_ratio'] = false;
        $settings['quality'] = '100%';
//        $config['master_dim'] = 'height';

        list($image_width, $image_height) = getimagesize($img_src);

        if ($image_width > $image_height) {
            $resize_w = intval($image_width * $new_h / $image_height);
            $resize_h = $new_h;
        }
        else
        {
            $resize_w = $new_w;
            $resize_h = intval($image_height * $new_w / $image_width);
        }

        $config['width'] = $resize_w;
        $config['height'] = $resize_h;

        $this->load->library('image_lib');
        $this->image_lib->clear();
        $this->image_lib->initialize($config);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
            return false;
        }

        return "$uploadFolder/$file_name";
    }

    protected function image_crop($thumb_size_x = 260, $thumb_size_y = 180, $uploadFolder, $file_name)
    {
        $img_src = FCPATH ."$uploadFolder/original/$file_name";
        $img_thumb = FCPATH ."$uploadFolder/thumbs/thumb_$file_name";

        if ( ! is_dir(FCPATH ."$uploadFolder/thumbs/")) {
            if ( ! mkdir (FCPATH ."$uploadFolder/thumbs/", 0777, TRUE)) {
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
        }

        $config['image_library'] = 'gd2';
        $config['source_image'] = $img_src;
        $config['new_image'] = $img_thumb;
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = FALSE;

        list($image_width, $image_height) = getimagesize($img_src);

        if ($image_width > $image_height) {
            $resize_w = intval($image_width * $thumb_size_y / $image_height);
            $resize_h = $thumb_size_y;
        }
        else
        {
            $resize_w = $thumb_size_x;
            $resize_h = intval($image_height * $thumb_size_x / $image_width);
        }

        $config['width'] = $resize_w;
        $config['height'] = $resize_h;

        $this->load->library('image_lib');
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
            return false;
        }

        // reconfigure the image lib for cropping
        $conf_new = array(
            'image_library' => 'gd2',
            'source_image' => $img_thumb,
//            'new_image' => $img_thumb,
            'create_thumb' => false,
            'maintain_ratio' => FALSE,
            'width' => $thumb_size_x,
            'height' => $thumb_size_y,
            'x_axis' => intval(round(($resize_w - $thumb_size_x) / 2)),
            'y_axis' => intval(round(($resize_h - $thumb_size_y) / 2)),
        );

        $this->image_lib->clear();
        $this->image_lib->initialize($conf_new);

        if ( !$this->image_lib->crop() ){
            echo $this->image_lib->display_errors();
        }

        return "$uploadFolder/thumbs/thumb_$file_name";
    }

    /**
     * Change status to rejected and keep reason
     */
    public function delete_photo($photo_id)
    {
        $this->load->helper("file");
        /*$details = $this->Photo->get($photo_id);
        $filePath = FCPATH . $details['photo'];
        var_dump( get_file_info(FCPATH . $details['photo'])['name']);die;*/

        $response = array('msg'=>"<stron>Внимание</stron> Произошла ошибка при удаление!", "code"=>1);
        if ( $photo_id && $this->input->is_ajax_request() && ($details = $this->Photo->get($photo_id)))
        {
            if($this->Photo->delete($photo_id))
            {
                $fileName = get_file_info(FCPATH . $details['photo'])['name'];
//                $path = preg_replace('/\/+/', '/', FCPATH ."/public/images/galleries/original/{$fileName}");// Combine multiple slashes into a single slash
                if ($fileName && file_exists(FCPATH ."/public/images/galleries/original/{$fileName}"))
                    unlink(FCPATH ."/public/images/galleries/original/{$fileName}");
                if (file_exists(FCPATH . $details['thumb']))
                    unlink(FCPATH . $details['thumb']);
                if (file_exists(FCPATH . $details['photo']))
                    unlink(FCPATH . $details['photo']);

                $response['msg'] = "<stron>Поздровляю!<strong> Фотография была успешна удалена!";
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