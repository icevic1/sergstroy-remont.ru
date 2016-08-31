<?php
/**
 * 
 * @author Orletchi Victor
 *
 */
class Webservice extends My_Controller 
{
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
		$this->load->helper('url');
		$this->load->model('Dealer_model', 'dealer');     
		$this->load->model('Location_model', 'location');     
		$this->load->model('Inventory_model', 'inventory');
		$this->load->model('Applicant_model', 'applicant');

		$this->staff = $this->session->userdata('staff');
	}
	
	/**
	 * количество форм по статусам:
		"Pending:
		"Approved:
		"Rejected:
	 */
	function dealer_report()
	{
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
// 		echo strtotime('2016-02-01');die;
// 				echo json_encode(array('dealer_id'=>'1', 'lang'=>'en', 'from'=>'1455092584', 'to'=>'1455092584'), true);die;
		$JSON = $this->input->get_post('stringJson');
	
		if ($JSON && ($decoded_JSON = json_decode(urldecode($JSON), true)) && $decoded_JSON['dealer_id']) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
			$date_from = isset($decoded_JSON['from']) && $decoded_JSON['from'] ? date('Y-m-d', $decoded_JSON['from']): null;
			$date_to = isset($decoded_JSON['to']) && $decoded_JSON['to'] ? date('Y-m-d', $decoded_JSON['to']): null;
// 			$decoded_JSON['from'] = $date_from;
// 			$decoded_JSON['to'] = $date_to;
// 			var_dump($decoded_JSON);die;
			
			/**
			 * Filter only in status pending and approved
			 * @var unknown
			 */
			$item = $this->applicant->dealerFormReport($decoded_JSON['dealer_id'], $date_from, $date_to, array(1,2));
			
			if ($item) {
				$items = array_column($item, 'total', 'applicant_status_name');
// 				var_dump($item);die;
				$responseJSON['Error_Code'] = '0';
				$responseJSON['Error_Message'] = 'Success!';
				$responseJSON['Result'] = $items;
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('There are no records found for the requested period', $lang);
			}
		}
	
		header('Content-Type: application/json');
		echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	/**
	 * show rejected forms
	 */
	function reject_report()
	{
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
// 				echo json_encode(array('dealer_id'=>'1'), true);die;
		$JSON = $this->input->get_post('stringJson');
	
		if ($JSON && ($decoded_JSON = json_decode(urldecode($JSON), true)) && $decoded_JSON['dealer_id']) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
			$items = $this->applicant->search(array('dealer_id'=>$decoded_JSON['dealer_id'], 'applicant_status_id'=> 3));
			
			if ($items) {
				/**
				 * extract only needed column from multidimensional array
				 * @var unknown
				 */
				$columns = array_flip(array('applicant_id', 'serial_number', 'reason', 'reject_time'));
				
				$newdata = array();
				array_walk($items, function($val, $k) use (&$newdata, $columns){
					$newdata[] = array_intersect_key($val, $columns);
				});
				
				$responseJSON['Error_Code'] = '0';
				$responseJSON['Error_Message'] = 'Success!';
				$responseJSON['Result'] = $newdata;
				
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Rejected Form was not found!', $lang);
			}
		}
	
		header('Content-Type: application/json');
		echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function auth()
	{			
		$this->load->helper('ngbssquery');
		$this->load->model('Subscriber_mod');
		$this->load->library('Aes');
		$this->load->library('Auth');
		// echo urldecode(json_encode(array('login'=>'orletchi.victor@gmail.com', 'password'=>'terminator4', 'Push_Token'=>'123456')));die;
// 		var_dump($this->translate('Login Failed: please check your credentials', 'kh'));die;
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => $this->translate("Login Failed: please check your credentials"),
		);
		$JSON = $this->input->get_post('stringJson');

		if ($JSON && ($decoded_JSON = json_decode($JSON, true))) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
// 			var_dump($lang);die;
			if (!empty($decoded_JSON['login']) && !empty($decoded_JSON['password'])) {
				$login = $decoded_JSON['login'];
				$password = $decoded_JSON['password'];
				
				/*
				 * Encrypt password and keep to user
				 */
				$this->aes->setKey($this->config->item('aes_key'));
				$this->aes->setBlockSize($this->config->item('aes_size'));
				$this->aes->setData($password);
					
				$encryptedPassword = $this->aes->encrypt();

				/**
				 * Check user Credentials - success
				 */
				if (($staff = $this->auth->loginDealer($login, $encryptedPassword))) {

					if( ($dealer = $this->dealer->getFullDetails($staff['dealer_id']))) {
						$responseJSON['Error_Code'] = '0';
						$responseJSON['Error_Message'] = 'Login succeed!';
						$responseJSON['Result'] = array(
								'Staff_id' => $dealer['dealer_id'],
								'Staff_login' => $staff['login'],
								'Staff_name' => $dealer['dealer_name']
						);
					} else {
						$responseJSON['Error_Code'] = '1';
						$responseJSON['Error_Message'] = $this->translate('The dealer profile was not found!', $lang);
					}
				} else {
					$responseJSON['Error_Code'] = '1';
					$responseJSON['Error_Message'] = $this->translate('Login Failed: please check your credentials', $lang);
				}
			}
		}
		
// 		var_dump($login, $password, $responseJSON);die;
		header('Content-Type: application/json');
		echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function recovery_password()
	{
		$this->load->model('Subscriber_mod');
		$this->load->library('Aes');
		$this->load->library('Auth');
// 		echo urlencode(json_encode(array('login'=>'012345678', 'password'=>'123456', 'Push_Token'=>'123456')));die;
	
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Login Failed: please check your credentials",
		);
	
		if (($JSON = $this->input->get_post('stringJson')) && ($decoded_JSON = json_decode($JSON, true))) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;;
			if (!empty($decoded_JSON['login']) && !empty($decoded_JSON['password'])) {
				$login = $decoded_JSON['login'];
				$password = $decoded_JSON['password'];
				
				$dealer = $this->dealer->getDealerBySallerPhone($login);
// 	var_dump($decoded_JSON, $dealer);die;
	
				if ($dealer) {
					$this->aes->setKey($this->config->item('aes_key'));
					$this->aes->setBlockSize($this->config->item('aes_size'));
					$this->aes->setData($password);
						
					$encryptedPassword = $this->aes->encrypt();
		
					if ($this->auth->recovery_saller_passwrod($login, $encryptedPassword)){
						$responseJSON['Error_Code'] = '0';
						$responseJSON['Error_Message'] = $this->translate('Password was changed successfully!', $lang);
						$responseJSON['Result'] = array(
								'Staff_id' => $dealer['dealer_id'],
								'Staff_login' => $login
						);
					} else {
						$responseJSON['Error_Code'] = '1';
						$responseJSON['Error_Message'] = $this->translate('Password was not saved, please try again later!', $lang);
					}
					
				} else {
					$responseJSON['Error_Code'] = '1';
					$responseJSON['Error_Message'] = $this->translate('Invalid dealer Login!', $lang);
				}
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Login or Password are mandatory!', $lang);
			}
				
		}
	
		// 		var_dump($login, $password, $responseJSON);die;
		header('Content-Type: application/json');
		echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function save_form()
	{			
		// echo urldecode(json_encode(array('login'=>'orletchi.victor@gmail.com', 'password'=>'terminator4', 'Push_Token'=>'123456')));die;
		
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
		$JSON = $this->input->get_post('stringJson');
// 		var_dump(date('Y-m-d H:i:s', '1454684054'), $JSON, json_decode($JSON, true));die;
// 		$decoded_JSON = json_decode(urldecode($JSON), true);
// 		var_dump($decoded_JSON);die;
		
		if ($JSON && ($decoded_JSON = json_decode(urldecode($JSON), true))) {
			if (isset($_GET['debug'])) {var_dump($decoded_JSON); }
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
			if (!empty($decoded_JSON['saller_id'])) {
				
				$applicant_id = isset($decoded_JSON['applicant_id'])? $decoded_JSON['applicant_id']: null;
// 				$decoded_JSON['mSerialNumber'] = '123456789123456000';
				$serialInfo = $this->inventory->getSimDetailsBySerial($decoded_JSON['mSerialNumber']);
				$dealerInfo = $this->dealer->getDealerBySallerID($decoded_JSON['saller_id']);
// 				$phoneInfo = $this->inventory->getDealerBySallerID($decoded_JSON['saller_id']);
				
// 				var_dump($dealerInfo, $serialInfo);die;

				//0-blank sim; 1-SIM Kit
				if ($serialInfo['sim_type'] == 0 && isset($decoded_JSON['mPhoneNumber'])) {
					$phone_number = $decoded_JSON['mPhoneNumber'];
				} elseif ($serialInfo['sim_type'] == 1) {
					$phone_number = $serialInfo['phone_number'];
				} else {
					$responseJSON['Error_Message'] = $this->translate('Entered number does not exists', $lang);
					GOTO END;
				}
				
				$inputData = array (
						'serial_number' => $decoded_JSON['mSerialNumber'],
						'phone_number' => $phone_number,
						'dealer_id' => $dealerInfo['dealer_id'],
						'sales_id' => $decoded_JSON['saller_id'],
						'subscriber_name' => isset($decoded_JSON['mNameOfIndividualSubscriber'])? $decoded_JSON['mNameOfIndividualSubscriber']: null,
						'gender' => isset($decoded_JSON['mGender'])? (string)$decoded_JSON['mGender']: null,
						'date_of_birth' => (isset($decoded_JSON['mDateOfBirth']) && $decoded_JSON['mDateOfBirth']? date('Y-m-d',$decoded_JSON['mDateOfBirth']): null),
						'subscriber_company' => isset($decoded_JSON['mNameOfCompanySubscriber'])? $decoded_JSON['mNameOfCompanySubscriber']: null,
						'contact_name' => isset($decoded_JSON['mContactPerson'])? $decoded_JSON['mContactPerson']: null,
						'applicantion_date' => isset($decoded_JSON['mDate'])? date('Y-m-d', $decoded_JSON['mDate']): null,
						
						'house_number' => $decoded_JSON['mAddressNo'],
						'street' => $decoded_JSON['mAddressStreet'],
						'commune_id' => $decoded_JSON['mSangkatCommune'],
						'district_id' => $decoded_JSON['mKhanDistrict'],
						'city_id' => $decoded_JSON['mCityProvince'],
						
						'contact_number' => $decoded_JSON['mContactTelephone'],
						'fax_number' => $decoded_JSON['mContactFax'],
						'email' => $decoded_JSON['mContactEmail'],
						
						'subscriber_type' => (string)$decoded_JSON['mSubscriberType'], /* 1-individual; 2-company*/
						'is_foreigner' => (string)$decoded_JSON['mCambodianOrForeigner'], /* 0-Cambodian; 1-Foreigner;*/
						'document_type' => (string)$decoded_JSON['mDocumentType'], /*1-cambodian_id_card; 2-government_id_card; 3-valid_passport; 4-monk_id_card; 5-passport_with_valid_visa;  6-registration_certificate;*/
						'document_number' => $decoded_JSON['mDocumentNumber'],
						'document_issue_date' => isset($decoded_JSON['mDateOfIssue'])? date('Y-m-d', $decoded_JSON['mDateOfIssue']): null,
						'GPS_Lat' => $decoded_JSON['mGPS_Lat'],
						'GPS_Lon' => $decoded_JSON['mGPS_Lon'],
						/*'subscriber_sign_date' => $decoded_JSON['mSerialNumber'],
						'legal_sign_date' => $decoded_JSON['mSerialNumber'],*/
						'mAppDateCreate' => isset($decoded_JSON['mAppDateCreate'])? date('Y-m-d H:i:s', $decoded_JSON['mAppDateCreate']): null,
				);
				
// 				var_dump($decoded_JSON, $inputData);
// 				die;
				if ($applicant_id) {
					$affected_row = $this->applicant->update($applicant_id, $inputData);
					
					if(!$affected_row) {
						$responseJSON['Error_Code'] = '1';
						$responseJSON['Error_Message'] = $this->translate('Error: data was not changed! Please try again later!', $lang);
						GOTO END;
					}
				} else {
					$applicant_id = $this->applicant->insert($inputData);
					
					if(!$applicant_id) {
						$responseJSON['Error_Code'] = '1';
						$responseJSON['Error_Message'] = $this->translate('Error: data was not changed! Please try again later!', $lang);
						GOTO END;
					}
				}
								
				if ($applicant_id) {

					//change sim and phone status
					$this->inventory->updateSim($serialInfo['serial_id'], array('serial_status_id' => 3, 'sales_id'=> $decoded_JSON['saller_id'], 'dealer_id'=>$dealerInfo['dealer_id'])); // change status to in use);
					$this->inventory->updatePhoneNumber($phone_number, array('phone_status' => '1')); // change status to Assigned (0-Available; 1-Assigned; 2-Barred;)
					
					$responseJSON['Error_Code'] = '0';
					$responseJSON['Error_Message'] = 'Saved success!';
					$responseJSON['Result'] = array(
							'applicant_id' => $applicant_id
					);
				} 
			}
			
		}
		
		END:
// 		var_dump($login, $password, $responseJSON);die;

		if (isset($_GET['debug'])) {
			var_dump($responseJSON); 
		} else {
			header('Content-Type: application/json');
			echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
		}
	}
	
	function sim_info()
	{
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
// 		echo json_encode(array('mSerialNumber'=>'123456789123456000'), true);die;
		$JSON = $this->input->get_post('stringJson');
		
		if ($JSON && ($decoded_JSON = json_decode($JSON, true)) && $decoded_JSON['mSerialNumber']) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
			$item = $this->inventory->getSimDetailsBySerial($decoded_JSON['mSerialNumber']);
			
			if ($item) {
				$responseJSON['Error_Code'] = '0';
				$responseJSON['Error_Message'] = 'Success!';
				$responseJSON['Result'] = $item;
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Serial number was not found!', $lang);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
	}
	
	function phone_info()
	{
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
// 		echo json_encode(array('mPhoneNumber'=>'015765488'), true);die;
		$JSON = $this->input->get_post('stringJson');
		
		if ($JSON && ($decoded_JSON = json_decode($JSON, true)) && $decoded_JSON['mPhoneNumber']) {
			$lang = isset($decoded_JSON['lang'])? $decoded_JSON['lang'] : null;
			$item = $this->inventory->getPhoneNumberInfo(array('phone_number'=>$decoded_JSON['mPhoneNumber'])); //, 'phone_status'=>Inventory_model::PHONE_STATUS_AVAILABLE
// 			var_dump($item);die;
			
			if ($item && $item['phone_status'] == Inventory_model::PHONE_STATUS_AVAILABLE) {
				$responseJSON['Error_Code'] = '0';
				$responseJSON['Error_Message'] = 'Phone number '. Inventory_model::$PHONE_NUMBER_STATUS[Inventory_model::PHONE_STATUS_AVAILABLE];
				$responseJSON['Result'] = $item;
				
			} elseif ($item && $item['phone_status'] != Inventory_model::PHONE_STATUS_AVAILABLE) {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Entered number cannot be assigned because of wrong status', $lang);
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Entered number does not exists', $lang);
			}
		} else {
			$responseJSON['Error_Code'] = '1';
			$responseJSON['Error_Message'] =  $this->translate('Phone number is missed!', $lang);
		}
		
		header('Content-Type: application/json');
		$response = json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
		
		log_message('debug', $response);
		echo $response;
	}
	
	public function upload_mobile_images() 
	{
		$responseJSON = array(
				"Time"=> time(),
				"Error_Code" => 1,
				"Error_Message" => "Server Error! try later",
		);
		
		log_message('debug', '_FILES \n'.var_export($_FILES, true));
		log_message('debug', '_POST \n'.var_export($_POST, true));
		
		if (isset($_POST['applicant_id']) && $_POST['applicant_id']) {
			$lang = isset($_POST['lang'])?$_POST['lang']: null;
			$applicant_id = $_POST['applicant_id'];
			$applicant_info = $this->applicant->getFullDetails($applicant_id);
			
			if ($applicant_info) {
				if ( ($files = $this->upload_form_images()) ) {
					foreach ($files['files_data'] as $key=>$fileName) {
						$inputData = array();
						
						if (isset($files['errors'][$key])) {
							$responseJSON['Error_Code'] = '1';
							$responseJSON['Error_Message'] = $this->translate('Upload error', $lang);
							$responseJSON['Error'] = $files['errors'][$key];
							GOTO END;
						// success
						} else {
							$inputData['photo_'. (string)($key+1)] = $fileName;
							
							$this->applicant->update($applicant_id, $inputData);
							
							$responseJSON['Error_Code'] = '0';
							$responseJSON['Error_Message'] = 'Saved success!';
							$responseJSON['Result']['file_'.(string)($key)] = base_url("assets/upload/{$fileName}");
						}
					}
				}
				
			} else {
				$responseJSON['Error_Code'] = '1';
				$responseJSON['Error_Message'] = $this->translate('Application form was not found in DB by requested ID', $lang);
			}
		} else {
			$responseJSON['Error_Code'] = '1';
			$responseJSON['Error_Message'] = 'ID not set';
		}
		
		END:
		header('Content-Type: application/json');
		$response = json_encode($responseJSON, JSON_HEX_QUOT | JSON_HEX_TAG);
		
		log_message('debug', $response);
		
		echo $response;
	}
    
	/*
	 * array (
  'file_document' => 
  array (
    'name' => 'document.jpg',
    'type' => 'multipart/form-data',
    'tmp_name' => 'C:\\Windows\\Temp\\phpFD1F.tmp',
    'error' => 0,
    'size' => 258676,
  ),
  'file_form' => 
  array (
    'name' => 'form.jpg',
    'type' => 'multipart/form-data',
    'tmp_name' => 'C:\\Windows\\Temp\\php260.tmp',
    'error' => 0,
    'size' => 275680,
  ),
)
	 */
	protected function upload_form_images() 
	{
		$this->load->library('upload');
		$error = array();
		$number_of_files_uploaded = count($_FILES);
		
		foreach ($_FILES as $varName => $userData) {
			$_FILES['userfile']['name']     = $userData['name'];
			$_FILES['userfile']['type']     = $userData['type'];
			$_FILES['userfile']['tmp_name'] = $userData['tmp_name'];
			$_FILES['userfile']['error']    = $userData['error'];
			$_FILES['userfile']['size']     = $userData['size'];
			
			
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
				$error[] = array('error' => $this->upload->display_errors());
// 				var_dump($error);
			} else {
				$final_files_data[] = $this->upload->data();
				// Continue processing the uploaded data
			}
		}
		
		$return = array(
				'files_data'=> $final_files_data? array_column($final_files_data, 'orig_name') : null,
				'errors' => $error
		);
		
		return $return;
	}
	
	private function translate($text = null, $lang = 'en')
	{
		$langs = array(
				'The dealer profile was not found!' => array('en'=>'The dealer profile was not found!','kh'=>'ពត៌មានរបស់អ្នកចែកចាយមិនត្រូវបានរកឃើញ'),
				'Rejected Form was not found!' => array('en'=>'Rejected Form was not found!','kh'=>'សំណៅបដិសេធន៍មិនត្រូវបានរកឃើញ'),
				'Login Failed: please check your credentials' => array('en'=>'Login Failed: please check your credentials','kh'=>'ចូលកម្មវិធីបរាជ័យ: សូមពិនិត្យមើលឈ្មោះ និង លេខសំងាត់ម្តងទៀត'),
				'Password was changed successfully!' => array('en'=>'Password was changed successfully!','kh'=>'លេខសំងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ'),
				'Password was not saved, please try again later!' => array('en'=>'Password was not saved, please try again later!','kh'=>'លេខសំងាត់មិនត្រូវបានរក្សាទុក , សូមព្យាយាមម្តងទៀត'),
				'Invalid dealer Login!' => array('en'=>'Invalid dealer Login!','kh'=>'ឈ្មោះអ្នកចែកចាយដែលបានបញ្ចូលមិនត្រឹមត្រូវ'),
				'Login or Password are mandatory!' => array('en'=>'Login or Password are mandatory!','kh'=>'ឈ្មោះអ្នកប្រើប្រាស់  រឺ លេខសំងាត់គឺត្រូវតែបញ្ចូល!'),
				'Entered number does not exists' => array('en'=>'Entered number does not exists','kh'=>'លេខដែលបានបញ្ចូលមិនមានក្នុងប្រព័ន្ធ'),
				'Error: data was not changed! Please try again later!' => array('en'=>'Error: data was not changed! Please try again later!','kh'=>'កំហុសឆ្គង : ទិន្នន័យមិនត្រូវបានផ្លាស់ប្តូរ ! សូមព្យាយាមម្តងទៀតនៅពេលក្រោយ'),
				'Serial number was not found!' => array('en'=>'Serial number was not found!','kh'=>'លេខសំគាល់ មិនអាចរកឃើញ'),
				'Entered number cannot be assigned because of wrong status' => array('en'=>'Entered number cannot be assigned because of wrong status','kh'=>'លេខដែលបានបញ្ចូលមិនអាចយកជាការបានទេ ដោយសារតែខុសស្ថានភាព'),
				'Phone number is missed!' => array('en'=>'Phone number is missed!','kh'=>'លេខដែលបានបញ្ចូលមិនត្រូវបានបង្ហាញ'),
				'Upload error' => array('en'=>'Upload error','kh'=>'ដំណើរការ បញ្ជូនទិន្នន័យ មានបញ្ហា'),
				'Application form was not found in DB by requested ID' => array('en'=>'Application form was not found in DB by requested ID','kh'=>'សំណុំបែបបទមិនត្រូវបានរកឃើញនៅក្នុងប្រព័ន្ធតាមរយៈលេខស្មើសុំនេះទេ'),
				'There are no records found for the requested period' => array('en'=>'There are no records found for the requested period','kh'=>'មិនមានទិន្នន័យសំរាប់រយៈពេលដែលស្នើរសុំនេះទេ')
		);
	
		return isset($langs[$text][$lang])? $langs[$text][$lang]: $text;
	}
	
	public function info() {
		phpinfo();
	}
	
}// end Servicetickets class