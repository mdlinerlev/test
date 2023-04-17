<?php
if (isset($_GET['zd_echo'])) { exit($_GET['zd_echo']); }

require_once __DIR__ . '/lib/zadarma/Handler.php';
require_once  __DIR__ .'/roistat.php';

use \Zadarma\Handler;

writeToLog($_POST, 'POST');

// Webhook data
$data  = isset($_POST['result']) ? json_decode($_POST['result']) : null;
$event = isset($_POST['event'])  ? $_POST['event'] : null;


// Check event
if($event !== 'CALL_TRACKING') {
    die('Ignore event');
}

writeToLog($data, 'Webhook data');

//
try {
    $handler = new Handler();

    foreach ($data as $call) {
        $callTime           = new DateTime(date('Y-m-d H:i:s', $call->start));
        $callTimeForRoistat = new DateTime(date('Y-m-d H:i:s', $call->start));
		$country = $call->country  ? $call->country : 'RU';
        // Список звонков
        $list = $handler->request(Handler::TYPE_GET, '/v1/statistics/pbx/', array(
            'from'  => $call->caller_id,
            'start' => $callTime->format('Y-m-d H:i:s'),
            'end'   => $callTime->modify('+4 hours')->format('Y-m-d H:i:s'),
        ))->result;
       // file_put_contents(__DIR__ . '/list.log', print_r($list, true), FILE_APPEND);

        // Выборка звонка
        $calls = array_filter($list['stats'], function($item) use ($call) {
            $phone = preg_replace('/\D/', '', $item['sip']);

            return $phone == $call->caller_id;
        });
        //file_put_contents(__DIR__ . '/calls.log', print_r($calls, true), FILE_APPEND);
        // Поиск записи с разговором
        $callAnswered = array_filter($calls, function($item) {
            $status  = $item['disposition'];
            $seconds = $item['seconds'];

            return ($status == 'answered' && $seconds > 0);
        });

        // Если есть записи с ответом, берем последнюю (хронология ID)
        // Если пусто, то берем последнюю из всего списка (хронология ID)
        if(!empty($callAnswered)) {
            $callApi = array_pop($callAnswered);
        } else {
            $callApi = array_pop($calls);
        }
     //   file_put_contents(__DIR__ . '/log.log', print_r($call, true), FILE_APPEND);
        // Запись звонка в историю
		$roistat = new Roistat();
		$roistatCalltracking = $roistat -> setCall($call->caller_id,$call->caller_did, $callTimeForRoistat->modify('-3 hours')->format('Y-m-d H:i:s'),'ANSWER', null, $call->roistat_visit, null, null, $country);
      //  file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "log.log", "После записи в историю" . PHP_EOL,FILE_APPEND);
      //  file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "log.log", var_export($roistatCalltracking, true) . PHP_EOL,FILE_APPEND);
        //Обновление визита у лида
		$lead = $roistat -> cUrl(WEBHOOK_BITRIX."/crm.duplicate.findbycomm/",array(
            'type' => 'PHONE',
            'values' => array($call->caller_id),
            'entity_type' => 'LEAD'
        ), "POST");
	 
        if(isset($res['result']['LEAD'][0])){
            $result = $roistat -> cUrl(WEBHOOK_BITRIX."/crm.lead.update/",array('id' => $res['result']['LEAD'][0],'fields' => array('UF_CRM_1620376902'=> $call->roistat_visit)), "POST");
        }
		
		// собираем данные для лога
		 $lead = array(
          'id' => $lead->ID,
          'UF_CRM_1620376902' => $lead->UF_CRM_1620376902,
          'roistat' => $call->roistat_visit
        ); 
        logi($lead,'Обновление лида',__DIR__.'/lead_update.txt');

        // ID звонка
        $callId = $roistatCalltracking['phoneCall']['id'];

        // Данные для обновления записи в истории звонков Roistat
        $roistatCallUpdateData = array(
            'id'       => $callId,
            'status'   => getStatus($callApi['disposition']),
            'duration' => $callApi['seconds'],
        );

        // Запись звонка
        $link = null;
        if($callApi['is_recorded'] == 'true') {
            $record = $handler->request(Handler::TYPE_GET, '/v1/pbx/record/request/', array(
                'call_id' => $callApi['call_id'],
            ))->result;

            $roistatCallUpdateData['link'] = $record['link'];
        }

        // Обновление записи звонка в Roistat
        $roistat -> updateCall($roistatCallUpdateData, $country);
    }

    echo 'ok';
} catch (\Exception $e) {
    // Любая ошибка в ответе API выбрасывает исключение
    echo 'Error: '.$e->getMessage();
}

// ----------------------------------------------------------------------------

// Получить статус звонка
function getStatus($status) {
    $statuses = array(
        'answered'  => 'ANSWER',
        'busy'      => 'BUSY',
        'cancel'    => 'DONTCALL',
        'no answer' => 'NOANSWER',
        'failed'    => 'CONGESTION',
    );

    if(array_key_exists($status, $statuses)) {
        return $statuses[$status];
    }

    return null;
}

// ----------------------------------------------------------------------------

function writeToLog($data, $title = '') {
    $log = "\n------------------------\n";
    $log .= date("d.m.Y G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents(__DIR__ . '/log_data_zadarma_new.log', $log, FILE_APPEND);
    return true;
}

function logi($val,$name,$fileName = null){
	$statusLog = 1;
	if($statusLog > 0){
		$file = $fileName!=null?$fileName:'test.txt';
		if (@file_exists($file)) {
			$size = @filesize($file);
			if ($size > 2500*1024) {
				@unlink($file);
			}
		}
		$data = date('Y-m-d H:i:s');
		$result = "\n[$name ($data)]\n".print_r($val,true);
		//file_put_contents($file, $result."\n##########################################\n" , FILE_APPEND);
	}
}