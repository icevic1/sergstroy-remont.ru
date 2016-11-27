<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Home extends Site_Controller 
{
	//public $ST_DB;
	public $staff = null;
	private $visitor = null;
	private $debugEmail	 = array('orletchi.victor@gmail.com');
    
    function __construct()
	{
 		parent::__construct();
		$this->load->helper('cookie');
        $this->load->helper('text');
//        $this->load->helper('translate_helper');
        $this->load->library('form_validation');
        $this->load->library('Acl');
		$this->load->helper('form');
		$this->load->helper('pagination');
		$this->load->helper('url');
        $this->load->model('Photo_mod', 'Photo');
        $this->load->model('Gallery_mod', 'Gallery');


		$this->Client = $this->session->userdata('Client');
	}	
	
	function index()
	{
        $this->load->model('Review_mod', 'Review');
        $reviewItems = $this->Review->all(array('is_video'=>'0','on_home'=>'1','published'=>'1'));
        $reviewVideoItems = $this->Review->all(array('is_video'=>'1','on_home'=>'1','published'=>'1'));
//        $data['albumPhotos'] = $albumPhotos = $this->Gallery->getPhotos($gallery_id = 2);
        $data['projectsPhotos'] = $projectsPhotos = $this->Gallery->filterPhotos(array('gallery_type'=>'1', 'published'=>'1', 'selected'=>'1'));
//        var_dump($projectsPhotos);

        $data['reviewItems'] = $reviewItems;
        $data['reviewVideoItems'] = $reviewVideoItems;

        $data['client'] = $this->Client;
		$data['PAGE_TITLE'] = 'Ремонт не может быть скучным';
		$data['BODY_CLASS'] = "home";
		$data['CONTENT']='home/index';
		$this->load->view('layout/layout', $data);
	}

    public function price_list()
    {
//        $data['itemsList'] = $this->Review_mod->all();
        $data['titlePage'] = 'Цены - Список цен';
        $data['PAGE_TITLE'] = 'Цены :: Прайслист';
        $data['BODY_CLASS'] = "home";
        $data['CONTENT']='home/price_list';
        $this->load->view('layout/layout', $data);
    }
	
	protected function upload_form_images() 
	{
		$this->load->library('upload');
		
		$number_of_files_uploaded = count($_FILES['applicant']['name']);
		
		// Faking upload calls to $_FILE
		for ($i = 1; $i <= $number_of_files_uploaded; $i++) {
			if (!isset($_FILES['applicant']['name']['photo_'.$i]) || $_FILES['applicant']['error']['photo_'.$i] != 0) {
				continue;
			}
		
			$_FILES['userfile']['name']     = $_FILES['applicant']['name']['photo_'.$i];
			$_FILES['userfile']['type']     = $_FILES['applicant']['type']['photo_'.$i];
			$_FILES['userfile']['tmp_name'] = $_FILES['applicant']['tmp_name']['photo_'.$i];
			$_FILES['userfile']['error']    = $_FILES['applicant']['error']['photo_'.$i];
			$_FILES['userfile']['size']     = $_FILES['applicant']['size']['photo_'.$i];
			 
			$random_digit = rand(00,99999);
			$ext = strtolower(substr($_FILES['userfile']['name'], strpos($_FILES['userfile']['name'],'.'), strlen($_FILES['userfile']['name'])-1));
			$file_name = $random_digit . $ext;
			 
// 			var_dump($_FILES, $_FILES['userfile']['name'], $file_name);die;
			 
			$config = array(
					'file_name'     => $file_name,
					'allowed_types' => 'jpg|jpeg|png|gif',
					'max_size'      => 3000,
					'overwrite'     => FALSE,
					'upload_path'   => FCPATH . 'assets/upload/' /* real path to upload folder ALWAYS */
			);
			 
			$this->upload->initialize($config);
			 
			if ( ! $this->upload->do_upload()) {
				$error = array('error' => $this->upload->display_errors());
				var_dump($error);
			} else {
				$final_files_data[] = $this->upload->data();
				// Continue processing the uploaded data
			}
		} //endfor;
// 		var_dump($final_files_data);die;
		return $final_files_data? array_column($final_files_data, 'orig_name') : null;
		
	}
	
	/**
	 * Change status to approved
	 */
	public function get_events()
	{
        $photosEvents = array();
		if ($this->input->is_ajax_request()) {
//			$applicant_id = $this->input->post('applicant_id');
            $photosEvents = $this->Gallery->getEvents(array('user_id'=> $this->session->userdata('staff')['user_id']));
            $photosEvents = array_column($photosEvents, null, 'event_date');

            $photosEvents = array_map(function($item) {
                return array('number'=>$item['number'], 'url'=>site_url("/home/gallery_event_photos/{$item['id']}"));
            }, $photosEvents);

//        $data['photosEvents'] =
//var_dump( $photosEvents);die;
		} else {
            $this->output->set_status_header('404');
            show_404();
        }
		echo json_encode($photosEvents);
	}
	
	/**
	 * Change status to rejected and keep reason
	 */
	public function gallery_event_photos($gallery_id)
	{
        $data['albumPhotos'] = $albumPhotos = $this->Gallery->getPhotos($gallery_id);
        $this->load->view('home/gallery_event_photos', $data);
	}
	
}