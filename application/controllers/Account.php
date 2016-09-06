<?php
class Account extends My_Controller 
{
	private $debugEmail	 = null; //'orletchi.victor@gmail.com';
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->library('form_validation');
		$this->load->config('my_config');
		$this->load->library('cryptastic');
		$this->load->library('Auth');
		$this->load->model('Client_mod', 'Client');
		$this->load->library('Aes');
	}
	
	function index()
	{
        redirect('/#client_login');
		if($this->session->userdata('Client')){
			redirect('/');
		}
// 		$this->load->helper('email');
// 		var_dump(send_email($from = array('email' => 'smart.care@smart.com.kh', 'name' => 'Smart Telecom'), array('orletchi.victor@gmail.com'), 'test subject', 'test body', true));die;
		
		$this->load->library('Acl');
		$data['BODY_CLASS']="login";
		$data['PAGE_TITLE']="Account";
		$data['LOGIN']=true;
// 		var_dump($this->acl->can_read(null, 3 /*STS main resources*/));die;
		$err_msg = $this->session->userdata('msg');
		$this->session->unset_userdata('msg');
		
		$data['err_msg'] = $err_msg;
		
		$data['CONTENT'] = 'account/login';
		if(isset($_GET['layout']) && $_GET['layout'] == 'def') {
			$this->load->view('template/tmpl_login',$data);	
		} else{
			$this->load->view('layout/layout_login',$data);	
		}
	}

    function login()
    {
        if($this->session->userdata('Client')){
            redirect('/#client_login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->set_rules('user_name', 'Имя', 'trim|required');
            $this->form_validation->set_rules('user_phone', 'Телефон', 'trim|required');

            //var_dump($this->form_validation->run(),$this->form_validation->error_array());die;
            if ($this->form_validation->run() !== false) {

                $user_name = $this->form_validation->set_value('user_name');
                $user_phone = $this->form_validation->set_value('user_phone');

                if (($client = $this->Client->checkClient($user_name, $user_phone))) {

                    $this->session->sess_expiration = 60 * 60 * 24;// expires in 1 day
                    $this->session->set_userdata('Client', $client);
                    redirect('/#client_login');
                } else {
                    $this->session->set_userdata('msg', 'Введенное имя и/или номер не совпадают! Пожалуйста попробуйте еще раз.');
                    redirect('/#client_login');
                }
            } else {
                $this->session->set_userdata('msg', 'Введенное имя и/или номер не совпадают! Пожалуйста попробуйте еще раз.');
                redirect('/#client_login');
            }
        } else {
            show_error("У вас нет доступа просматривать эту страницу", $status_code = 503, 'Отказано в доступе');
        }
    }
	
	function logout()
	{
//		$this->selfcare_mod->save_customer_log('logout');
		$this->session->unset_userdata("Client");
		$this->session->sess_destroy();
		redirect('/');
	}

	function get_captcha(){
		//$word=$this->selfcare_mod->get_captcha();
		$RandomStr = md5(microtime());// md5 to generate the random string
		$ResultStr = substr($RandomStr,0,5);//trim 5 digit
		$word=$ResultStr;
		$this->session->set_userdata('word',$word);
		$img_src=str_replace('system', 'public',BASEPATH).'images/captcha.png';
		$img_desc=str_replace('system', 'public',BASEPATH).'captcha/'.$word.'.png';
		$config['source_image'] =$img_src;
		$config['wm_text'] = $word;
		$config['wm_type'] = 'text';
		$config['wm_font_color'] = '000000';
		$config['wm_hor_alignment'] = 'center';
		$config['new_image'] = $img_desc;
		$this->load->library('image_lib', $config);
		$this->image_lib->watermark();
		return $word;
	}

	function check_captcha($word){
		if($this->session->userdata('word')){
			return $word==$this->session->userdata('word')?true:false;
		}else{
			return false;
		}
		//return $this->selfcare_mod->check_captcha($word);
	}

	function generate_captcha(){
		/*$this->remove_captcha();*/
		$word=$this->get_captcha();
		$capt_img='<img src="'.base_url('public/captcha/'.$word.'.png').'" border="0" />';
	
		//$captcha=$this->selfcare_mod->get_captcha();
		//$capt_img='<div class="ot-captcha-img">'.$captcha.'</div>';
		echo json_encode(array('captcha'=>$capt_img));
	}
}