<?php
set_time_limit(120);

header('Content-Type: text/html; charset=utf-8');

require_once "roistat.php";

$fieldsId = isset($_REQUEST['data']['FIELDS']['ID']) ? $_REQUEST['data']['FIELDS']['ID'] : null;

if(empty($fieldsId)) die("ID NULL");
sleep(20);
 
$roistat = new Roistat();

$lead = $roistat -> cUrl(WEBHOOK_BITRIX."/crm.lead.get/",array('ID' => $fieldsId), "POST");
 
	$phone = isset($lead['result']['PHONE'][0]['VALUE']) ? $roistat->setPhone($lead['result']['PHONE'][0]['VALUE']) : null;
	$email = isset($lead['result']['EMAIL'][0]['VALUE']) ? $lead['result']['EMAIL'][0]['VALUE'] : null;
	$visit_id = $fields = null;
	echo  $phone;
	echo  $email;
	$currentDate = new DateTime();
	$toDate = $currentDate->format('Y-m-d');
	$currentDate->modify( '-1days' );
	$fromDate = $currentDate->format('Y-m-d');
	$proxyleads = $roistat->cUrl("cloud.roistat.com/api/v1/project/proxy-leads?project=".PROJECT."&key=".KEY_PROJECT, array(
			'period' => "{$fromDate}-{$toDate}"
	));
	
	$timeNow = new Datetime();
	$timeNow->modify( '-3hours' );

	$crmStatuses = $roistat -> cUrl(WEBHOOK_BITRIX."/crm.status.list/", array(), "POST");
	$sources = [];
	foreach ($crmStatuses['result'] as $statusValue) {
		if ($statusValue['ENTITY_ID'] == 'SOURCE'){
			$sources[$statusValue['STATUS_ID']] = $statusValue['NAME'];
		}
	}
	
	if(!empty($proxyleads)){
		foreach ($proxyleads['ProxyLeads'] as $lead) {
	
			$timeCreate = new Datetime($lead['creation_date']);
			$diff = $timeNow->diff($timeCreate);
       
			if((($roistat->setPhone($lead['phone']) == $phone && mb_strlen($lead['phone'])) || ($lead['email'] == $email  && mb_strlen($lead['email'])))  && $diff->format('%y%d%m%h') < 1){
				$fields = isset($lead['order_fields']) ? $lead['order_fields'] : null;
				$visit_id= $lead['roistat'];
				break;
			}
	
		} 
	}
	 
	if($visit_id || $fields){
		$fields['UF_CRM_1620376902'] =$visit_id;
		foreach($fields as  $key => $val) {
			$newKey = str_replace ( ["deal_","lead_"], "", $key);
			if($newKey != $key) {
				$fields[$newKey] = $val;
				unset($fields[$key]);
			}
		}
		 
		if(array_key_exists('SOURCE_ID', $fields)){
			$sourceName = $fields['SOURCE_ID'];
			$sourceId = array_search($sourceName, $sources);
			if($sourceId) $fields['SOURCE_ID'] = $sourceId;
		}
		
		$lead = $roistat -> cUrl(WEBHOOK_BITRIX."/crm.lead.update/",array('id' => $fieldsId,'fields' => $fields), "POST");
	}
	