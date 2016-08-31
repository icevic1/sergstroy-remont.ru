<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//======================GENERATE XML REQUEST====================//
	if ( ! function_exists('Subscriber_Info'))
	{
		function Subscriber_Info($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
			
			$soap_action = 'QuerySubscriberInformation';// $ci->config->item('server_ip').'/acc/'.$method;
			$url = 'http://10.12.5.205:7080/custom/SELFCARE/HWBSS_Subscriber';
			
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sub="http://www.huawei.com/bss/soaif/interface/SubscriberService/" xmlns:com="http://www.huawei.com/bss/soaif/interface/common/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <sub:QuerySubInfoReqMsg>
				         <com:ReqHeader>
				            <com:TransactionId>2003001111111135</com:TransactionId>
				            <com:Channel>28</com:Channel>
				            <com:PartnerId>101</com:PartnerId>
				            <com:ReqTime>20150803193005</com:ReqTime>
				            <com:AccessUser>5060</com:AccessUser>
				            <com:AccessPassword>d+7pCWDu8Dt7vxVMvEudvQ==</com:AccessPassword>
				         </com:ReqHeader>
				         <sub:AccessInfo>
				            <com:ObjectIdType>4</com:ObjectIdType>
				            <com:ObjectId>'.$subid.'</com:ObjectId>
				         </sub:AccessInfo>
				      </sub:QuerySubInfoReqMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
			
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8'; 
			$ci->nusoap_client->soap_defencoding = 'UTF-8'; 
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
			
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
			
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// var_dump(, $result['Subscriber']['AdditionalProperty']);
			if(isset($result['RspHeader']['ReturnCode']) && $result['RspHeader']['ReturnCode'] === '0000'){
					
				$ret = array (
						'PhoneNo' => $result['Subscriber']['ServiceNum'],
						'ICCID' => $result['Subscriber']['ICCID'],
						'SubType' => $result['Subscriber']['SubType'], /* 0 means Prepaid; 1 means postpaid; 2 means hybrids*/
						'SubStatus' => $ci->subscriber->getSubscribersStatus('SubStatus', $result['Subscriber']['SubStatus']),
						);
				
				if (isset($result['Subscriber']['EffectiveDate'])) {
					$ret['EffectiveDate'] = $result['Subscriber']['EffectiveDate'];
				}
				
				if (isset($result['Subscriber']['AdditionalProperty'][0])) {
					$keyIndex = array_search('SUB_PASSWORD', array_column($result['Subscriber']['AdditionalProperty'], 'Code'));
					if (isset($result['Subscriber']['AdditionalProperty'][$keyIndex])) {
						$ret['SUB_PASSWORD'] = $result['Subscriber']['AdditionalProperty'][$keyIndex]['Value'];
					}
				}
					
				return $ret;
			}
			
			return null;
		}
	}
	
	if ( ! function_exists('Tariff_Plan'))
	{
		function Tariff_Plan($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'QueryPurchasedPrimaryOffering';
			$url = 'http://10.12.5.205:7080/custom/SELFCARE/HWBSS_Offering';
				
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:off="http://www.huawei.com/bss/soaif/interface/OfferingService/" xmlns:com="http://www.huawei.com/bss/soaif/interface/common/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <off:QueryPurchasedPrimaryOfferingReqMsg>
				         <com:ReqHeader>
				            <com:TransactionId>2003001111111135</com:TransactionId>
				            <com:Channel>28</com:Channel>
				            <com:PartnerId>101</com:PartnerId>
				            <com:ReqTime>20150803193005</com:ReqTime>
				            <com:AccessUser>5060</com:AccessUser>
				            <com:AccessPassword>d+7pCWDu8Dt7vxVMvEudvQ==</com:AccessPassword>
				         </com:ReqHeader>
				         <off:AccessInfo>
				             <com:ObjectIdType>4</com:ObjectIdType>
				            <com:ObjectId>'.$subid.'</com:ObjectId>
				         </off:AccessInfo>
				      </off:QueryPurchasedPrimaryOfferingReqMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}

			if(isset($result['RspHeader']['ReturnCode']) && $result['RspHeader']['ReturnCode'] === '0000') {
				$ret = array (
						'OfferingId'   => $result['PrimaryOffering']['OfferingId']['OfferingId'],
						'OfferingName' => $result['PrimaryOffering']['OfferingName'],
						'EffectiveDate'=>  $result['PrimaryOffering']['EffectiveDate'],
						'ExpireDate'=>  $result['PrimaryOffering']['ExpireDate'],
						'Status'=>  $result['PrimaryOffering']['Status']
				);
				return $ret;
			}
				
			return null;
		}
	}
	
	if ( ! function_exists('Service_Info'))
	{
		function Service_Info($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'QueryPurchasedSupplementaryOffering';
			$url = 'http://10.12.5.205:7080/custom/SELFCARE/HWBSS_Offering';
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:off="http://www.huawei.com/bss/soaif/interface/OfferingService/" xmlns:com="http://www.huawei.com/bss/soaif/interface/common/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <off:QueryPurchasedSupplementaryOfferingReqMsg>
				         <com:ReqHeader>
				             <com:TransactionId>2003001111111135</com:TransactionId>
				            <com:Channel>28</com:Channel>
				            <com:PartnerId>101</com:PartnerId>
				            <com:ReqTime>20150803193005</com:ReqTime>
				            <com:AccessUser>5060</com:AccessUser>
				            <com:AccessPassword>d+7pCWDu8Dt7vxVMvEudvQ==</com:AccessPassword>
				         </com:ReqHeader>
				         <off:AccessInfo>
				             <com:ObjectIdType>4</com:ObjectIdType>
				            <com:ObjectId>'.$subid.'</com:ObjectId>
				         </off:AccessInfo>
				      </off:QueryPurchasedSupplementaryOfferingReqMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
				
			if(isset($result['RspHeader']['ReturnCode']) && $result['RspHeader']['ReturnCode'] === '0000'){
				if (false == isset($result['SupplementaryOffering'][0])) {
					$services = array($result['SupplementaryOffering']);
				} else {
					$services = $result['SupplementaryOffering'];
				}
				
				
				$result_array = array();
				array_walk($services, function($itemVal, $itemKey) use (&$result_array) {
					return $result_array[$itemVal['OfferingId']['OfferingId']] = $itemVal['OfferingName'];
				}, $result_array);
				
				return $result_array;
				/* make from 
				 * 0 => 
				    array (size=5)
				      'OfferingId' => 
				        array (size=2)
				          'OfferingId' => string '190853430' (length=9)
				          'PurchaseSeq' => string '152300000003' (length=12)
				      'OfferingName' => string 'Dollar 0 unlimited CUG' (length=22)
				      'EffectiveDate' => string '20140930112419' (length=14)
				      'ExpireDate' => string '20991231095959' (length=14)
				      'Status' => string 'C01' (length=3)
				  TO: 
				  array(190853430 => string 'Dollar 0 unlimited CUG')
				  */
			}
				
			return null;
		}
	}
	
	if ( ! function_exists('Balance_Info'))
	{
		function Balance_Info($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'QueryBalance';// $ci->config->item('server_ip').'/acc/'.$method;
			$url = 'http://10.12.5.205:7080/CRM/CBSInterface_AR_Services';
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <ars:QueryBalanceRequestMsg>
				         <RequestHeader>
				   	<cbs:Version>1</cbs:Version>
					<cbs:BusinessCode>1</cbs:BusinessCode><cbs:MessageSeq>1</cbs:MessageSeq>
					<cbs:OwnershipInfo>
					<cbs:BEID>101</cbs:BEID>
					</cbs:OwnershipInfo>
					<cbs:AccessSecurity>
					<cbs:LoginSystemCode>5060</cbs:LoginSystemCode>
					<cbs:Password>r8q0a5WwGNboj9I35XzNcQ==</cbs:Password>
					</cbs:AccessSecurity>
				       </RequestHeader>
				         <QueryBalanceRequest>
				            <ars:QueryObj>
				               <!--You have a CHOICE of the next 2 items at this level-->
				               <ars:SubAccessCode>
				               <arc:PrimaryIdentity>'.$subid.'</arc:PrimaryIdentity>
				               </ars:SubAccessCode>
				             </ars:QueryObj>
				         </QueryBalanceRequest>
				      </ars:QueryBalanceRequestMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// var_dump($result);
			if(isset($result['ResultHeader']['ResultCode']) && $result['ResultHeader']['ResultCode'] === '0'){
				$out = array();
				
				if (isset($result['QueryBalanceResult']['AcctList']['OutStandingList'])) {
					if (false == isset($result['QueryBalanceResult']['AcctList']['OutStandingList'][0])) {
						$result['QueryBalanceResult']['AcctList']['OutStandingList'] = array($result['QueryBalanceResult']['AcctList']['OutStandingList']);
					}
					
					$OutStandingList = $result['QueryBalanceResult']['AcctList']['OutStandingList'];
					$OutStandingAmount = 0;
					array_walk($OutStandingList, function(&$val, $k) use (&$OutStandingAmount){
						$OutStandingAmount += $val['OutStandingDetail']['OutStandingAmount'];
						//$val['OutStandingDetail']['OutStandingAmount'] = number_format($val['OutStandingDetail']['OutStandingAmount']/100000000, 2);
					}); 
					
					/*
					 * Order by begin date
					 */
					$name = 'BillCycleBeginTime';
					usort($result['QueryBalanceResult']['AcctList']['OutStandingList'], function ($a, $b) use(&$name){return (strtotime($a[$name]) < strtotime($b[$name]))? 1: -1;});
					
					$out = array (
						'OutStandingAmount' => number_format($OutStandingAmount/100000000, 2),
						'OutStandingList'=>  $result['QueryBalanceResult']['AcctList']['OutStandingList']
					);
				}
				
				if (isset($result['QueryBalanceResult']['AcctList']['AccountCredit'])) {
					$out['TotalCreditAmount'] = number_format($result['QueryBalanceResult']['AcctList']['AccountCredit']['TotalCreditAmount']/ 100000000, 2);
					$out['TotalRemainAmount'] = number_format($result['QueryBalanceResult']['AcctList']['AccountCredit']['TotalRemainAmount']/ 100000000, 2);
				}
				
				return $out;
			}
				
			return null;
		}
	}
	
	if ( ! function_exists('Bill_Info'))
	{
		function Bill_Info($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$startdate=date('YmdHis', strtotime('-180 days'));
			$enddate=date('YmdHis');
			$soap_action = 'QueryInvoice';
			$url = 'http://10.12.5.205:7080/CRM/CBSInterface_AR_Services';
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <ars:QueryInvoiceRequestMsg>
				         <RequestHeader>
				         <cbs:Version>1</cbs:Version>
					<cbs:BusinessCode>1</cbs:BusinessCode><cbs:MessageSeq>1</cbs:MessageSeq>
					<cbs:OwnershipInfo>
					<cbs:BEID>101</cbs:BEID>
					</cbs:OwnershipInfo>
					<cbs:AccessSecurity>
					<cbs:LoginSystemCode>5060</cbs:LoginSystemCode>
					<cbs:Password>r8q0a5WwGNboj9I35XzNcQ==</cbs:Password>
					</cbs:AccessSecurity>
				         </RequestHeader>
				         <QueryInvoiceRequest>
				            <ars:AcctAccessCode>
				               <!--You have a CHOICE of the next 3 items at this level-->
				               <arc:PrimaryIdentity>'.$subid.'</arc:PrimaryIdentity>
				          
				            </ars:AcctAccessCode>
				            <!--You have a CHOICE of the next 3 items at this level-->
				             <ars:TimePeriod>
				               <ars:StartTime>'.$startdate.'</ars:StartTime>
				               <ars:EndTime>'.$enddate.'</ars:EndTime>
				            </ars:TimePeriod>
				  
				         </QueryInvoiceRequest>
				      </ars:QueryInvoiceRequestMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}

			if(isset($result['ResultHeader']['ResultCode']) && $result['ResultHeader']['ResultCode'] === '0' && isset($result['QueryInvoiceResult']['InvoiceInfo']) ){
				/*
				 * Order by begin date
				 */
				$name = 'BillCycleID';
				usort($result['QueryInvoiceResult']['InvoiceInfo'], function ($a, $b) use(&$name){return (strtotime($a[$name]) < strtotime($b[$name]))? 1: -1;});
				
				return $result['QueryInvoiceResult']['InvoiceInfo'];
			}
				
			return null;
		}
	}
	
	if ( ! function_exists('Free_Unit'))
	{
		function Free_Unit($subid)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'QueryFreeUnit';
			$url = 'http://10.12.5.205:7080/CRM/CBSInterface_BB_Services';
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bbs="http://www.huawei.com/bme/cbsinterface/bbservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:bbc="http://www.huawei.com/bme/cbsinterface/bbcommon">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <bbs:QueryFreeUnitRequestMsg>
				         <RequestHeader>
				     <cbs:Version>1</cbs:Version>
					<cbs:BusinessCode>1</cbs:BusinessCode><cbs:MessageSeq>1</cbs:MessageSeq>
					<cbs:OwnershipInfo>
					<cbs:BEID>101</cbs:BEID>
					</cbs:OwnershipInfo>
					<cbs:AccessSecurity>
					<cbs:LoginSystemCode>5060</cbs:LoginSystemCode>
					<cbs:Password>r8q0a5WwGNboj9I35XzNcQ==</cbs:Password>
					</cbs:AccessSecurity>
				         </RequestHeader>
				         <QueryFreeUnitRequest>
				            <bbs:QueryObj>
				                 <bbs:SubAccessCode>
				                <bbc:PrimaryIdentity>'.$subid.'</bbc:PrimaryIdentity>
				                 </bbs:SubAccessCode>
				                    </bbs:QueryObj>
				         </QueryFreeUnitRequest>
				      </bbs:QueryFreeUnitRequestMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
			
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// 			var_dump($result);die;	
			if(isset($result['ResultHeader']['ResultCode']) && $result['ResultHeader']['ResultCode'] === '0' && isset($result['QueryFreeUnitResult']['FreeUnitItem'])){
				return isset($result['QueryFreeUnitResult']['FreeUnitItem'][0])? $result['QueryFreeUnitResult']['FreeUnitItem'] : array($result['QueryFreeUnitResult']['FreeUnitItem']);
			} 
			return null;
		}
	}
	
	if ( ! function_exists('Group_Member'))
	{
		/**
		 * Get list of customer members
		 * @param number $GroupId
		 * @param number $page
		 * @param number $perpage
		 * @param number $ServiceNumber
		 * @return unknown[]|NULL
		 */
		function Group_Member($GroupId, $page = 1, $perpage = 10, $ServiceNumber = null)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'GetGroupMemberData';
			$url = 'http://10.12.5.207:8060/crm/services/OrderQuery';
			
			$where = '';
			if ($ServiceNumber) {
				$where .= "<quer:ServiceNumber>{$ServiceNumber}</quer:ServiceNumber>";
			}
			
			if ($page < 2) $StartRow = 1;
			else $StartRow = $page * $perpage;
			$where .= "<quer:StartRow>{$StartRow}</quer:StartRow>";
			
// 			var_dump($where); die;  
			
			/* <!--You may enter the following 7 items in any order-->
			<quer:GroupId>211016129393</quer:GroupId>
			<!--Optional:-->
			<quer:ServiceNumber>?</quer:ServiceNumber>
			<!--Optional:-->
			<quer:StartRow>1</quer:StartRow>
			<!--Optional:-->
			<quer:PageSize>200</quer:PageSize>
			<!--Optional:-->
			<quer:IncludePaymentRelation>?</quer:IncludePaymentRelation>
			<!--Optional:-->
			<quer:IncludeMemberOffers>?</quer:IncludeMemberOffers> */
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:quer="http://crm.huawei.com/query/" xmlns:bas="http://crm.huawei.com/basetype/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <quer:GetGroupMemberDataRequest>
				         <quer:RequestHeader>
				           <bas:Version>1</bas:Version>
				            <bas:TransactionId>2015100723595212454855054293229</bas:TransactionId>
				            <bas:ProcessTime>20151007235952</bas:ProcessTime>
				            <bas:ChannelId>28</bas:ChannelId>
				            <bas:TechnicalChannelId>53</bas:TechnicalChannelId>
				            <bas:TenantId>101</bas:TenantId>
				            <bas:AccessUser>5060</bas:AccessUser>
				            <bas:AccessPwd>d+7pCWDu8Dt7vxVMvEudvQ==</bas:AccessPwd>
				         </quer:RequestHeader>
				         <quer:GetGroupMemberBody>
				            <!--You may enter the following 7 items in any order-->
				            <quer:GroupId>'.$GroupId.'</quer:GroupId>
				            '.$where.'
				            <quer:PageSize>'.$perpage.'</quer:PageSize>
				        </quer:GetGroupMemberBody>
				      </quer:GetGroupMemberDataRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
			//var_dump($ci->nusoap_client->fault, $ci->nusoap_client->faultstring, $ci->nusoap_client->getError());die;	
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('Fault '.$soap_action, htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES), htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('Error '.$soap_action, htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES), htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES), htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
			
			// var_dump($result);die;
			
			if(isset($result['ResponseHeader']['RetCode']) && $result['ResponseHeader']['RetCode'] === '0' && isset($result['GetGroupMemberDataBody']['GetGroupDataList']['GroupMembers']['MemberSubscriberList'])){
				
				$MemberSubscriberList = $result['GetGroupMemberDataBody']['GetGroupDataList']['GroupMembers']['MemberSubscriberList'];
				unset($result['GetGroupMemberDataBody']['GetGroupDataList']['GroupMembers']);
				
				/* convert member list to array, if result is only one member*/
				if (false == isset($MemberSubscriberList[0]) && $result['GetGroupMemberDataBody']['GetGroupDataList']['MemberAmount'] < 2) {
					$MemberSubscriberList = array($MemberSubscriberList);
				}
				
				$mlist = array_column($MemberSubscriberList, 'MemberStatus', 'MemberServiceNumber');

				return array ('GroupDetails' => $result['GetGroupMemberDataBody']['GetGroupDataList'],
						'MemberSubscriberList' => $mlist);
			}
			return null;
		}
	}
	
	if ( ! function_exists('GetCorpCustomerData'))
	{
		/**
		 * Get customer information by subscriber phone number
		 * @param string $ServiceNumber
		 * @param string $onlyCustId
		 * @return multitype:|unknown|NULL
		 */
		function GetCorpCustomerData($ServiceNumber = null, $onlyCustId = false)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			$soap_action = 'GetCorpCustomerData';
			$url = 'http://10.12.5.207:8060/crm/services/OrderQuery';
			
			/* $where = '';
			if ($ServiceNumber) {
				$where = "<quer:ServiceNumber>{$ServiceNumber}</quer:ServiceNumber>";
			} */
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:quer="http://crm.huawei.com/query/" xmlns:bas="http://crm.huawei.com/basetype/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <quer:GetCorpCustomerDataRequest>
				         <quer:RequestHeader>
				            <bas:Version>1</bas:Version>
				            <bas:TransactionId>2015100723595212454855054293229</bas:TransactionId>
				            <bas:ProcessTime>20151007235952</bas:ProcessTime>
				            <bas:ChannelId>28</bas:ChannelId>
				            <bas:TechnicalChannelId>53</bas:TechnicalChannelId>
				            <bas:TenantId>101</bas:TenantId>
				            <bas:AccessUser>5060</bas:AccessUser>
				            <bas:AccessPwd>d+7pCWDu8Dt7vxVMvEudvQ==</bas:AccessPwd>
				         </quer:RequestHeader>
				         <quer:GetCorpCustomerBody>
				            <!--You may enter the following 10 items in any order-->
				            <quer:MemberServiceNumber>'.$ServiceNumber.'</quer:MemberServiceNumber>
				            <!--<quer:CustomerId>?</quer:CustomerId>
             				<quer:GroupId>?</quer:GroupId>-->
				         </quer:GetCorpCustomerBody>
				      </quer:GetCorpCustomerDataRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// 			var_dump($result);die;
			if(isset($result['ResponseHeader']['RetCode']) && $result['ResponseHeader']['RetCode'] === '0' && isset($result['GetCorpCustomerBody']['GetCorpCustomerDataList'])){
				//identify cust info where CustomerType is 1 (means group customer, if 0 is own customer)
				if (isset($result['GetCorpCustomerBody']['GetCorpCustomerDataList'][0])) {
					$CustInfo = array_column($result['GetCorpCustomerBody']['GetCorpCustomerDataList'], 'CustInfo');
				} else {
					$CustInfo = array($result['GetCorpCustomerBody']['GetCorpCustomerDataList']['CustInfo']);
					$result['GetCorpCustomerBody']['GetCorpCustomerDataList'] = $CustInfo;
				}
				
				$CustBaseInfo = array_column($CustInfo, 'CustBaseInfo');
				$CustomerTypeKey = array_search('1', array_column($CustBaseInfo, 'CustomerType'));
				$CustomerId = $CustBaseInfo[$CustomerTypeKey]['CustomerId'];
	
				if ($onlyCustId == true) {
					return $CustomerId;
				} else {
					return $result['GetCorpCustomerBody']['GetCorpCustomerDataList'][$CustomerTypeKey];
				}
			}
			return null;
		}
	}
	
	
	if ( ! function_exists('AuthenticateSubscriber'))
	{
		/**
		 * Get customer information by subscriber phone number
		 * @param string $ServiceNumber
		 * @param string $onlyCustId
		 * @return multitype:|unknown|NULL
		 */
		function AuthenticateSubscriber($ServiceNumber = null, $password = false)
		{
			$ServiceNumber = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $ServiceNumber));
			
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
			
			$encription_helper = APPPATH . '../assets/encryption.jar';
			//chmod($encription_helper, '0777');
			$enc_pass = trim(shell_exec("java -jar {$encription_helper} {$password}"));

			/* phone: 10212648
			password: 571728
			encrypt: M3l8owDLRV7StWVvYdTqWQ== */
			
			$soap_action = 'AuthenticateSubscriber';
			$url = 'http://10.12.5.205:7080/custom/SELFCARE/HWBSS_Subscriber';
		
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sub="http://www.huawei.com/bss/soaif/interface/SubscriberService/" xmlns:com="http://www.huawei.com/bss/soaif/interface/common/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <sub:AuthenticateSubReqMsg>
				         <com:ReqHeader>
				           <com:TransactionId>2003001111111135</com:TransactionId>
				            <com:Channel>28</com:Channel>
				            <com:PartnerId>101</com:PartnerId>
				            <com:ReqTime>20150803193005</com:ReqTime>
				            <com:AccessUser>5060</com:AccessUser>
				            <com:AccessPassword>d+7pCWDu8Dt7vxVMvEudvQ==</com:AccessPassword>
				         </com:ReqHeader>
				         <sub:AccessInfo>
				             <com:ObjectIdType>4</com:ObjectIdType>
				            <com:ObjectId>'.$ServiceNumber.'</com:ObjectId>
				         </sub:AccessInfo>
				         <sub:Password>'.$enc_pass.'</sub:Password>
				      </sub:AuthenticateSubReqMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}

			if(isset($result['RspHeader']['ReturnCode']) && $result['RspHeader']['ReturnCode'] === '0000' && $result['RspHeader']['ReturnMsg'] === 'Success.'){
				return true;
			} else {
				return false;
			}
		}
	}

	if ( ! function_exists('ChangeSubscriberPassword'))
	{
		function ChangeSubscriberPassword($serviceNumber, $newPassword)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
			
			if (!$serviceNumber || !$newPassword) return false;
			
			if(false == GetCorpCustomerData($serviceNumber, true)) return false;
			$subsInfo = Subscriber_Info($serviceNumber); if (!$subsInfo) return false;
			
			$old_password = $subsInfo['SUB_PASSWORD'];
			$new_password = encrypt_java($newPassword);
			
			if ($old_password === $new_password) return true;
			
// 			var_dump($old_password, $new_password, $subsInfo);die;
				
			$soap_action = 'ChangeSubscriberPassword';// $ci->config->item('server_ip').'/acc/'.$method;
			$url = 'http://10.12.5.205:7080/custom/SELFCARE/HWBSS_Subscriber';
				
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sub="http://www.huawei.com/bss/soaif/interface/SubscriberService/" xmlns:com="http://www.huawei.com/bss/soaif/interface/common/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <sub:ChangeSubPasswordReqMsg>
				         <com:ReqHeader>
				            <com:TransactionId>2003001111111135</com:TransactionId>
				            <com:Channel>28</com:Channel>
				            <com:PartnerId>101</com:PartnerId>
				            <com:ReqTime>20150803193005</com:ReqTime>
				            <com:AccessUser>5060</com:AccessUser>
				            <com:AccessPassword>d+7pCWDu8Dt7vxVMvEudvQ==</com:AccessPassword>
				         </com:ReqHeader>
				         <sub:AccessInfo>
				            <com:ObjectIdType>4</com:ObjectIdType>
				            <com:ObjectId>'.$serviceNumber.'</com:ObjectId>
				         </sub:AccessInfo>
				         <sub:OldPassword>'.$old_password.'</sub:OldPassword>
				         <sub:NewPassword>'.$new_password.'</sub:NewPassword>
				      </sub:ChangeSubPasswordReqMsg>
				   </soapenv:Body>
				</soapenv:Envelope>';
				
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
				
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
				
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// 			var_dump($ci->nusoap_client->faultstring,$ci->nusoap_client->getError(), $result);
			if(isset($result['RspHeader']['ReturnCode']) && $result['RspHeader']['ReturnCode'] === '0000'){
				return true;
			}
				
			return false;
		}
	}

	function recursive_array_search($needle,$haystack) {
		foreach($haystack as $key=>$value) {
			$current_key=$key;
			if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
				return $current_key;
			}
		}
		return false;
	}
	
	if ( ! function_exists('encrypt_java'))
	{
		function encrypt_java($password = false)
		{
			$ci=& get_instance();
			$ci->load->config('my_config');

			$encription_helper = APPPATH . '../assets/encryption.jar';
			//chmod($encription_helper, '0777');
			$enc_pass = trim(shell_exec("java -jar {$encription_helper} {$password}"));
				
			/* phone: 10212648
				password: 571728
				encrypt: M3l8owDLRV7StWVvYdTqWQ== */
			return $enc_pass;
		}
	}
	
	if ( ! function_exists('send_sms'))
	{
		function send_sms($PhoneNo = null, $Message = null)
		{
			if (!$PhoneNo || !$Message) return false;
			
			$PhoneNo = preg_replace('/^(855|0|\+)*([1-9]*)/', '$2',  preg_replace('/\s/', '', $PhoneNo));
			
			$ci =& get_instance();

			$ci->load->library('SMPPClass');
			$ci->smppclass->SetSender($ci->config->item('smpp_from'));
			$ci->smppclass->Start($ci->config->item('smpp_host'), $ci->config->item('smpp_port'), $ci->config->item('smpp_user'), $ci->config->item('smpp_password'), $ci->config->item('smpp_system_type'));
			
			if (false == $ci->smppclass->TestLink()) return false;
				
			$sendStatus = $ci->smppclass->Send("855{$PhoneNo}", $Message);
			$ci->smppclass->End();
			
			return $sendStatus;
		}
	}
	
	
	if ( ! function_exists('CDR_Info'))
	{
		function CDR_Info($subid, $page = 1, $perpage = 20,$startdate ,$enddate)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
			$ci->load->model('CDR_mod');
			
			$soap_action = 'QueryCDR';// $ci->config->item('server_ip').'/acc/'.$method;
			$url = 'http://10.12.5.205:7080/CRM/CBSInterface_BB_Services';
	
		
		//	var_dump($subid,$startdate, $enddate);die;
			if ($page < 2) $StartRow = 1;
			else $StartRow = $page * $perpage;
			$where = "<bbs:BeginRowNum>{$StartRow}</bbs:BeginRowNum>";
			
			
		//	$and="<bbs:ServiceCategory>{$servicetype}</bbs:ServiceCategory>";	
		
			
			$xml ='<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bbc="http://www.huawei.com/bme/cbsinterface/bbcommon" xmlns:bbs="http://www.huawei.com/bme/cbsinterface/bbservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon">
 				   <SOAP-ENV:Header/>
  				   <SOAP-ENV:Body>
    				  <bbs:QueryCDRRequestMsg>
      					<RequestHeader>
        				  <cbs:Version>1.0</cbs:Version>
        				  <cbs:MessageSeq>20151116110829950642</cbs:MessageSeq>
                          <cbs:AccessSecurity>
          				  <cbs:LoginSystemCode>5060</cbs:LoginSystemCode>
          				  <cbs:Password>r8q0a5WwGNboj9I35XzNcQ==</cbs:Password>
        				  </cbs:AccessSecurity>
                       </RequestHeader>
                       <QueryCDRRequest>
        				  <bbs:SubAccessCode>
          				  <bbc:PrimaryIdentity>'.$subid.'</bbc:PrimaryIdentity>
        				  </bbs:SubAccessCode>
        				  <bbs:TimePeriod>
				          <bbs:StartTime>'.$startdate.'</bbs:StartTime>
				          <bbs:EndTime>'.$enddate.'</bbs:EndTime>
					      </bbs:TimePeriod>
				        
					      <bbs:TotalCDRNum>0</bbs:TotalCDRNum>
					   
          				  '.$where.'
          				 
          			     <bbs:FetchRowNum>'.$perpage.'</bbs:FetchRowNum>
      					</QueryCDRRequest>
   					 </bbs:QueryCDRRequestMsg>
  				</SOAP-ENV:Body>
				</SOAP-ENV:Envelope>';
	
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
	
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
	
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
				
			//var_dump($result);die;
				if(isset($result['ResultHeader']['ResultCode']) && $result['ResultHeader']['ResultCode'] === '0' &&  isset($result['QueryCDRResult']['CDRInfo'])){
					
				//	$name = 'StartTime';
				//	usort($result['QueryCDRResult']['CDRInfo'], function ($a, $b) use(&$name){return (strtotime($a[$name]) > strtotime($b[$name]))? -1: 1;});
				
					
					
					return (isset($result['QueryCDRResult'])?$result['QueryCDRResult']:null);
				}
				
				//return $ret;
				
			return null;
		}
	}
	
	if ( ! function_exists('GetAccountDetailList'))
	{
		function GetAccountDetailList($CustId)
		{
			$ci=& get_instance();
			$ci->load->library("Nusoap_Lib");
			$ci->load->config('my_config');
			$ci->load->model('selfcare_mod');
			$ci->load->model('Subscriber_mod', 'subscriber');
				
			if (!$CustId ) return false;
				
			$soap_action = 'GetAccountDetailList';// $ci->config->item('server_ip').'/acc/'.$method;
			$url = 'http://10.12.5.207:8060/crm/services/OrderQuery';
	
			$xml ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:quer="http://crm.huawei.com/query/" xmlns:bas="http://crm.huawei.com/basetype/">
				   <soapenv:Header/>
				   <soapenv:Body>
				      <quer:GetAccountDetailListRequest>
				         <quer:RequestHeader>
				  		  <bas:Version>1</bas:Version>
				            <bas:TransactionId>2015100723595212454855054293229</bas:TransactionId>
				            <bas:ProcessTime>20151007235952</bas:ProcessTime>
				            <bas:ChannelId>28</bas:ChannelId>
				            <bas:TechnicalChannelId>53</bas:TechnicalChannelId>
				            <bas:TenantId>101</bas:TenantId>
				            <bas:AccessUser>5060</bas:AccessUser>
				            <bas:AccessPwd>d+7pCWDu8Dt7vxVMvEudvQ==</bas:AccessPwd>
				         </quer:RequestHeader>
				         <quer:GetAccountDetailListBody>
				            <quer:CustomerId>'.$CustId.'</quer:CustomerId>
				         </quer:GetAccountDetailListBody>
				      </quer:GetAccountDetailListRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';
	
			$xml = preg_replace('~\s*(<([^>]*)>[^<]*</\2>|<[^>]*>)\s*~','$1',$xml);
			$ci->nusoap_client = new nusoap_client($url);
			$ci->nusoap_client->defencoding = 'UTF-8';
			$ci->nusoap_client->soap_defencoding = 'UTF-8';
			$ci->nusoap_client->decode_utf8 = false;
			$ci->nusoap_client->serializeEnvelope($xml,'',array(),'document', 'literal');
	
			$result = $ci->nusoap_client->send($xml, $soap_action, $ci->config->item('conn_timeout'), $ci->config->item('resp_timeout'));
	
			if($ci->nusoap_client->fault)
			{
				//$ci->selfcare_mod->save_log(htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				$ci->selfcare_mod->save_api_log('fault Error '.$soap_action,htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
				return array();
			} else {
				if($ci->nusoap_client->getError()){
					$ci->selfcare_mod->save_api_log('getError Error '.$soap_action,htmlspecialchars($ci->nusoap_client->getError(), ENT_QUOTES),htmlspecialchars($ci->nusoap_client->faultstring, ENT_QUOTES));
					return array();
				} else {
					$ci->selfcare_mod->save_api_log($soap_action, htmlspecialchars($ci->nusoap_client->request, ENT_QUOTES),htmlspecialchars($ci->nusoap_client->response, ENT_QUOTES));
				}
			}
// 			var_dump($result);die;
			if(isset($result['ResponseHeader']['RetCode']) && $result['ResponseHeader']['RetCode'] === '0'){
				return isset($result['GetAccountDetailListBody']['GetAccountDetailList'][0])? $result['GetAccountDetailListBody']['GetAccountDetailList'] : array($result['GetAccountDetailListBody']['GetAccountDetailList']);
			}
	
			return false;
		}
	}
	
	function getGroupAccountCode($InvoiceDate = null, $GroupDetails = array()) 
	{
		if (!$InvoiceDate || !$GroupDetails) return null;
		
		$GroupAccountId = $GroupDetails['AccountId'];
		$AccountDetailList = GetAccountDetailList($GroupDetails['CustomerId']);
		
		$key = array_search($GroupAccountId, array_column($AccountDetailList, 'AccountId'));
		$GroupAccountDetails = $AccountDetailList[$key];
		
		// 2015-11-01 > 2010-06-01 = AccountId 	  (411016129393)
		// 2015-11-01 < 2015-12-01 = AccountCode  (1.6015235)
		// 2015-11-01 > 2015-09-01 = AccountCode  (411016129393)
		
		//410009258206_20151101_Master_detail.pdf
		$AccEffectiveDate = date('Ym', strtotime($GroupAccountDetails['EffectiveDate'])) .$GroupAccountDetails['BillCycleTypeID'] ; //20100603104014
		
		if (strtotime($InvoiceDate) > strtotime($AccEffectiveDate)) {
			$AccountCode = $GroupAccountDetails['AccountId'];
		} else {
			$AccountCode = $GroupAccountDetails['AccountCode'];
		}
		
// 		var_dump($month, $GroupAccountDetails, $GroupDetails, $AccountDetailList);die;
		
		return $AccountCode;
	}
	
	
	if ( ! function_exists('float_format'))
	{
		/**
		 * 
		 * @param float $val
		 * @param number $min
		 * @param number $max
		 * @return number|unknown
		 */
		function float_format($val, $min = 2, $max = 4) 
		{
			$result = round($val, $min);
			if ($result == 0 && $min < $max) {
				return float_format($val, ++$min, $max);
			} else {
				return number_format($result, $min);
			}
		}
	}