<?php
class Log extends Admin_Controller {
	var $page_id=1;
	function index(){
		//echo $this->login_name;
		$data['menus']=$this->selfcare_mod->get_menu($this->login_name);
		$data['per_page']=$this->selfcare_mod->get_perm_per_page($this->login_name,$this->page_id);
		$data['PAGE_TITLE']=$data['per_page']['page_name'];
		//$data['logs']=$this->selfcare_mod->get_log();
		$data['CONTENT']='admin/log/index';
		$this->load->view('template/tmpl_admin',$data);
	}
	function source(){
		$iDisplayStart = $this->input->get_post('iDisplayStart');
        $iDisplayLength = $this->input->get_post('iDisplayLength');
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols');
        $sSearch = $this->input->get_post('sSearch');
        $sEcho = $this->input->get_post('sEcho');
		$from_date=$this->input->get_post('from_date');
		$to_date=$this->input->get_post('to_date');
		$query="SELECT * FROM sc_api_log WHERE DATE_FORMAT(created_date, '%Y-%m-%d')>='".$from_date."' AND DATE_FORMAT(created_date, '%Y-%m-%d')<='".$to_date."'";
		//Searching
		if(isset($sSearch) && !empty($sSearch))
        {
			$query.=" AND (log LIKE '%".$sSearch."%' OR method LIKE '%".$sSearch."%' OR request_xml LIKE '%".$sSearch."%' OR response_xml LIKE '%".$sSearch."%' )";
        }
		$query_1=$query;
		// Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
			$query.=" LIMIT ".$iDisplayLength." OFFSET ".$iDisplayStart."";
        }
		$data=$this->selfcare_mod->get_api_log($query,$sEcho,$query_1);

        echo json_encode($data);
	}
}