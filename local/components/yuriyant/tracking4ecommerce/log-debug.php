<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

global $USER;

if (!$USER->IsAdmin()) {
    die("Access denied");
}

if (!IsModuleInstalled("yuriyant.tracking4ecommerce") || !CModule::IncludeModule("yuriyant.tracking4ecommerce")) {
    echo 'Module "Tracking 4 Ecommerce" not installed';
    return;
}

if (!defined('LOG_FILENAME') && $debug) {
    define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . YURIYANT_GA_TRACKING_MODULE_NAME . "-log.txt");
}

$handle = fopen(LOG_FILENAME, "r");
if (!$handle) {
    echo 'Журнал не найден : ' . LOG_FILENAME;
    return;
}

$i = 0;
$logStatistic = [];
while (($line = fgets($handle)) !== false) {
    if (preg_match('(----------)', $line)) {
        $newBlock = true;
    }
    if (preg_match('[Date:]', $line)) {
        $date = strtok(explode(':', $line, 2)[1], " ");
    }
    if (preg_match('[HIT_PAYLOAD]', $line)) {
        $payLoad = explode('>', $line, 2)[1];
        parse_str($payLoad, $payloadInfo);

        // universal stat
        $statKey = $payloadInfo['ea'] . ' (' . $payloadInfo['pa'] . ')';
        if (!isset($logStatistic[$date][$statKey])) {
            $logStatistic[$date][$statKey] = 0;
        }
        $logStatistic[$date][$statKey] ++;
        //        
        if (trim($payloadInfo['pa']) === 'purchase') {
            if (Yuriyant\Modules\GoogleAnalitycs\UserParams::isValidGoogleID($payloadInfo['cid'])) {
                $logStatistic[$date]['purchase GA customer'] ++;
            } else {
                $logStatistic[$date]['purchase BX customer'] ++;
            }
            $logStatistic[$date]['purchaseOrders'][] = $payloadInfo['ti'];
        }
        if (trim($payloadInfo['pa']) === 'refund') {
            $logStatistic[$date]['refundOrders'][] = $payloadInfo['ti'];
        }
        ksort($logStatistic[$date]);
    }
    $i++;
}
fclose($handle);


foreach ($logStatistic as &$report) {
    if ($report['purchaseOrders']) {
        $report['purchaseOrders'] = implode(', ', $report['purchaseOrders']);
    }
    if ($report['refundOrders']) {
        $report['refundOrders'] = implode(', ', $report['refundOrders']);
    }
}

if (class_exists('dBug')) {
    new dBug($logStatistic);
} else {
    echo '<pre>' . print_r($logStatistic, 1) . '</pre>';
}
