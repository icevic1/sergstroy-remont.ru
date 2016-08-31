<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//======================GENERATE XML REQUEST====================//
	if ( ! function_exists('request'))
	{
		function request($method,$url,$params=array())
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$soap_action=$ci->config->item('server_ip').'/acc/'.$method;
			$xml ='<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<'.$method.' xmlns:acc="http://www.sigvalue.com/acc">';
		if(!empty($params)){
			foreach($params as $key=>$val){
				$xml.='
	<'.$key.'>'.$val.'</'.$key.'>';
			}
		}
		$xml.='
</'.$method.'>
</soap:Body>
</soap:Envelope>';
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8'; 
			$ci->nusoap_client->soap_defencoding = 'UTF-8'; 
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
			$result=$ci->nusoap_client->send($xml, $soap_action,$ci->config->item('conn_timeout'),$ci->config->item('resp_timeout'));
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$method,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			}else{
				if($ci->nusoap_client->getError()){
					//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES));
					$ci->selfcare_mod->save_api_log('getError Error '.$method,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				}else{
					$ci->selfcare_mod->save_api_log($method,htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
			return $result;
		}
	}
?>