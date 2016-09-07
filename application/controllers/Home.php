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
        $this->load->library('form_validation');		
        $this->load->library('Acl');
		$this->load->helper('form');
		$this->load->helper('pagination');
		$this->load->helper('url');


		$this->Client = $this->session->userdata('Client');
	}	
	
	function index()
	{
        $this->load->model('Review_mod', 'Review');
        $reviewItems = $this->Review->all(array('is_video'=>'0','on_home'=>'1','published'=>'1'));
        $reviewVideoItems = $this->Review->all(array('is_video'=>'1','on_home'=>'1','published'=>'1'));
//        var_dump($reviewVideoItems, $reviewItems);

        $data['reviewItems'] = $reviewItems;
        $data['reviewVideoItems'] = $reviewVideoItems;

        $data['client'] = $this->Client;
		$data['PAGE_TITLE'] = 'Ремонт не может быть скучным';
		$data['BODY_CLASS'] = "home";
		$data['CONTENT']='home/index';
		$this->load->view('layout/layout', $data);
	}
	
	function profile($ID = null)
	{
		$item = $this->dealer->getFullDetails($ID);

		if (empty($item)) {
			show_error('Dealer with ID #'.$ID.'# was not found!', $status_code= 500 );
		}
		
// 		var_dump($item);die;
		$data['item'] = $item;
		$data['navBackLink'] = site_url('dealer/');
		$data['navEditLink'] = site_url('dealer/save/'.$ID);
		
		
		// add breadcrumbs
		$this->breadcrumbs->push('Home', 'home');
		$this->breadcrumbs->push('Dealer Registration Platform', 'dealer/');
		$this->breadcrumbs->push('Dealer Profile', 'dealer/profile');
	
		$data['PAGE_TITLE'] = 'eKYC - Dealer Registration Platform';
		$data['BODY_CLASS'] = "sts";
		$data['CONTENT']='dealer/profile';
		$this->load->view('layout/layout_st', $data);
	}
    
	function save($ID = null)
	{
		$this->load->helper('text');
		
		$required = '';
		if ($ID) {
			$dbdata = $this->applicant->getFullDetails($ID);
// 			var_dump($dbdata);die;
			if (empty($dbdata)) {
				show_error('Applicantion Form with ID #'.$ID.'# was not found!', $status_code= 500 );
			}
			$data['choosed'] = $dbdata;
			
			$required = '|required';
		}
// var_dump($data['choosed_status_id']);die;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			$this->form_validation->set_rules('applicant[applicant_id]', 'Applicant ID', 'trim|numeric|max_length[5]'.$required);
			
			$this->form_validation->set_rules('applicant[serial_number]', 'Serial', 'trim|required|numeric|max_length[19]');
			$this->form_validation->set_rules('applicant[phone_number]', 'phone_number', 'trim|required|numeric');
			$this->form_validation->set_rules('applicant[dealer_id]', 'Dealer', 'trim|required|numeric|max_length[128]');
			
			$this->form_validation->set_rules('applicant[sales_id]', 'sales_id', 'trim');
			$this->form_validation->set_rules('applicant[gender]', 'gender', 'trim|max_length[3]');
			$this->form_validation->set_rules('applicant[subscriber_name]', 'subscriber_name', 'trim|max_length[64]');
			$this->form_validation->set_rules('applicant[date_of_birth]', 'date_of_birth', 'trim');
			$this->form_validation->set_rules('applicant[subscriber_company]', 'subscriber_company', 'trim');
			$this->form_validation->set_rules('applicant[contact_name]', 'contact_name', 'trim');
			$this->form_validation->set_rules('applicant[contact_number]', 'contact_number', 'trim|numeric');
			$this->form_validation->set_rules('applicant[fax_number]', 'fax_number', 'trim|numeric');
			$this->form_validation->set_rules('applicant[email]', 'email', 'trim|valid_email');
			
			$this->form_validation->set_rules('applicant[house_number]', 'House Number', 'trim|max_length[32]');
			$this->form_validation->set_rules('applicant[street]', 'Street', 'trim|max_length[256]');
			$this->form_validation->set_rules('applicant[commune_id]', 'Commune', 'trim|numeric');
			$this->form_validation->set_rules('applicant[district_id]', 'District', 'trim|numeric');
			$this->form_validation->set_rules('applicant[city_id]', 'City', 'trim|numeric');
			
			$this->form_validation->set_rules('applicant[subscriber_type]', 'subscriber_type', 'trim|alpha|max_length[12]');
			$this->form_validation->set_rules('applicant[is_foreigner]', 'is_foreigner', 'trim|numeric|max_length[1]');
			$this->form_validation->set_rules('applicant[document_type]', 'document_type', 'trim|numeric|max_length[1]');
			$this->form_validation->set_rules('applicant[document_number]', 'document_number', 'trim|max_length[12]');
			$this->form_validation->set_rules('applicant[document_issue_date]', 'document_issue_date', 'trim');
			$this->form_validation->set_rules('applicant[photo_1]', 'photo_1', 'trim');
			$this->form_validation->set_rules('applicant[photo_2]', 'photo_2', 'trim');
			$this->form_validation->set_rules('applicant[photo_3]', 'photo_3', 'trim');
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> ', '</div>');
			
// 			log_message('debug', 'Some variable was correctly set \n '. var_export($_FILES, true));
// 			var_dump($ret, var_export($_FILES, true));die;
			
			/**
			 * passed validation proceed to post success logic
			 */
// 			var_dump($_FILES,  $this->form_validation->run(), $this->form_validation->error_array());
			if ($this->form_validation->run() == true) {
				
				$inputData = $this->empty2null($this->input->post('applicant'));
				$applicant_id = $this->form_validation->set_value('applicant[applicant_id]');
				
				if ( ($files = $this->upload_form_images()) ) {
					foreach ($files as $key=>$fileName) {
						$inputData['photo_'. (string)($key+1)] = $fileName;
					}
				}
				
// 				var_dump($inputData);die;
				if ($applicant_id) {
					$this->applicant->update($applicant_id, $inputData);
				} else {
					$applicant_id = $this->applicant->insert($inputData);
				}
				
				redirect('home/save/'.$applicant_id);
			} else {
				$data['choosed'] = $this->input->post('applicant');
			}
		} // end if POST
		
		$data['districts'] = $this->location->getDistricts(true);
	    $data['communes'] = $this->location->getCommunes(true);
	    $data['cities'] = $this->location->getCities(true);
	    $data['phone_numbers'] = $this->inventory->search_numbers(null, true);
	    $data['serial_numbers'] = $this->inventory->search(null, true);
	    $data['dealers'] = $this->dealer->search(null, true);

	    // add breadcrumbs
		$this->breadcrumbs->push('Application Forms', 'home/');
		if ($ID) {
			$this->breadcrumbs->push('Change Profile', 'home/save/'.$ID);
			$data['ACTION_TITLE'] = 'Application Form Profile';
		} else {
			$this->breadcrumbs->push('New Application Form', 'home/save/');
			$data['ACTION_TITLE'] = 'New Application Form';
		}
	    
		$data['navBackLink'] = site_url('home/');
	    $data['BODY_CLASS'] = "sts";
		$data['PAGE_TITLE'] = 'eKYC - '.$data['ACTION_TITLE'];
		$data['CONTENT']='home/save';
		$this->load->view('layout/layout_st', $data);
	}// end save()
	
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
	public function approve_ajax() 
	{
		$response = array('result'=>1);
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$applicant_id = $this->input->post('applicant_id');

			if($applicant_id && ($item = $this->applicant->getFullDetails($applicant_id))) {
				$this->applicant->update($applicant_id, array('applicant_status_id'=>'2'));
				$response['result'] = 0;
			}
		}
		echo json_encode($response);
	}
	
	/**
	 * Change status to rejected and keep reason
	 */
	public function reject_ajax() 
	{
		$response = array('result'=>1);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$applicant_id = $this->input->post('reject_applicant_id');
			$reason = $this->input->post('reason');

			if($reason && $applicant_id && ($item = $this->applicant->getFullDetails($applicant_id))) {
				$this->applicant->update($applicant_id, array('applicant_status_id'=>'3'));
				$this->applicant->appendRejectReason(array('applicant_id'=>$applicant_id, 'user_id'=> $this->session->userdata('staff')['user_id'], 'reason'=>$reason));
				$response['result'] = 0;
			}
		}
		echo json_encode($response);
	}
	
}// end Servicetickets class