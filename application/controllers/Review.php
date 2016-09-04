<?php
class Review extends Site_Controller
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

        $this->load->model('Review_mod');
    }

    public function index()
    {
        $data['itemsList'] = $this->Review_mod->all();
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->form_validation->set_rules('name', 'Имя', "trim|required");
            $this->form_validation->set_rules('image', 'Картинка', "trim");
            $this->form_validation->set_rules('review', 'Отзыв', "trim|required");

            $this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
//            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() != FALSE)	{
                $name = $this->form_validation->set_value('name');
                $review = $this->form_validation->set_value('review');



                $data_update = array(
                    'name' => $this->form_validation->set_value('name'),
                    'review' => $this->form_validation->set_value('review'),
                    'ip_address' => $this->input->ip_address(),
                    'is_video' => '0',
                    'published' => '1',
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
            redirect('/review');
        }

    }

    protected function upload_images($inputName = 'userfile')
    {
        $uploadFolder = "/public/images/review";
        $config['upload_path'] = FCPATH . "$uploadFolder/original";
        $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = TRUE;
        $config['file_name'] = time() . '.' . strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ( ! is_dir($this->upload->upload_path)) {
            if ( ! mkdir ($this->upload->upload_path, 0777, TRUE)) {
                $this->set_error('upload_no_filepath', 'error');
                return FALSE;
            }
            if ( ! is_really_writable($this->upload->upload_path)) {
                if ( ! chmod($this->upload->upload_path, 0777)) {
                    $this->set_error('upload_not_writable', 'error');
                    return FALSE;
                }
            }
        }

        if(!$this->upload->do_upload('userfile'))
        {
            $error = array('error' => $this->upload->display_errors());
            echo $error;
            return false; //$this->load->view('submit', $error);
        }
        else
        {
            $data['upload_data'] = array('upload_data' => $this->upload->data());
            $file_name = $this->upload->file_name;

//            $resize_url = $this->image_resize(800, 600, $uploadFolder, $file_name);
            return $thumb_url = $this->image_crop(250, $uploadFolder, $file_name);
        }
    }

    protected function image_resize($new_w, $new_h, $uploadFolder, $file_name)
    {
        $img_src = FCPATH ."$uploadFolder/original/$file_name";
        $img_resize = FCPATH ."$uploadFolder/$file_name";

        $config['image_library'] = 'gd2';
        $config['source_image'] = $img_src;
        $config['new_image'] = $img_resize;
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = FALSE;

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

    protected function image_crop($thumb_size = 250, $uploadFolder, $file_name)
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
            $resize_w = intval($image_width * $thumb_size / $image_height);
            $resize_h = $thumb_size;
        }
        else
        {
            $resize_w = $thumb_size;
            $resize_h = intval($image_height * $thumb_size / $image_width);
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
            'width' => $thumb_size,
            'height' => $thumb_size,
            'x_axis' => intval(round(($resize_w - $thumb_size) / 2)),
            'y_axis' => intval(round(($resize_h - $thumb_size) / 2)),

        );

        $this->image_lib->clear();
        $this->image_lib->initialize($conf_new);

        if ( !$this->image_lib->crop() ){
            echo $this->image_lib->display_errors();
        }

        return "$uploadFolder/thumbs/thumb_$file_name";
    }
	
	//==============FUNCTION RANDOM==============//
	function random_string($length = 6) {  
		$str = "";  
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));  
		$max = count($characters) - 1;  
		for ($i = 0; $i < $length; $i++) {   
			$rand = mt_rand(0, $max);   
			$str .= $characters[$rand];  
		}  
		return $str; 
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

	function edit_password(){
		$data['cust']=$this->session->userdata('cust');
		$data['CONTENT']='account/edit_password';
		$data['block']=$this->selfcare_mod->get_block(2);
		$this->load->view('template/tmpl_site',$data);
	}
	function check_session_exp(){
		$exp=$this->session->userdata('customer')?0:1;
		echo json_encode(array($exp));
	}

	function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
		$reference_array = array();
		foreach($array as $key => $row) {
			$reference_array[$key] = $row[$column];
		}
		array_multisort($reference_array, $direction, $array);
	}

	function export_excel(){
		$this->load->library('excellib');
		$objReader = IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($this->reportspath."/template/template.xlsx");
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$reportspath = realpath(APPPATH.'../public/tmp_report/');
		$objWriter->save($reportspath.'/Carddetails.xlsx');
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