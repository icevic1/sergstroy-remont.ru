<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('pagination_bootstrap'))
{	
	/**
	 * 
	 * @param string $uri
	 * @param number $cur_page
	 * @param number $total_rows
	 * @param number $per_page default 10
	 * @param number $segment default 3
	 */
	function pagination_bootstrap($uri, $cur_page, $total_rows, $per_page = 10, $segment = 3)
	{

		$ci =& get_instance();
		$ci->load->library('pagination');
		
		$config['base_url'] = base_url().$uri;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = (int)$per_page;
		$config['cur_page'] = (int)$cur_page;
		$config['uri_segment'] = $segment;
		$config['use_page_numbers']  = TRUE;
		
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		
		$config['first_link'] = '&laquo;';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		
		$config['last_link'] = '&raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><span>';
		$config['cur_tag_close'] = '</span></li>';
		
		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';
		
		//$pagingConfig['display_pages'] = false;
		// 		$pagingConfig['display_prev_link'] = true;
		// 		$pagingConfig['display_next_link'] = true;
		
// 		return $config;
		$ci->pagination->initialize($config);
		return $ci->pagination;
	}
}

function init_pagination($uri, $total_rows, $per_page = 10, $segment = 5)
{
	$ci =& get_instance();
	$ci->load->library('pagination');
	
	$config['base_url'] = base_url().$uri;
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $per_page;
	$config['uri_segment'] = $segment;
	$config['use_page_numbers']  = TRUE;
	$config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
	$config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li><span><b>";
	$config['cur_tag_close'] = "</b></span></li>";

	$ci->pagination->initialize($config);
	return $ci->pagination;
}