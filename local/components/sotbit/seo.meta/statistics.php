<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Sotbit\Seometa\SeometaStatisticsTable;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

global $APPLICATION;

$moduleId = "sotbit.seometa";

if (!Loader::includeModule($moduleId) || !Loader::includeModule('iblock')) {
    return false;
}

$context = Application::getInstance()->getContext();
$base_url = $context->getRequest()->getHttpHost();

$cookie = $APPLICATION->get_cookie("sotbit_seometa_statistic");
$from = $_REQUEST['from'];
$url = $_REQUEST['to'];
$referer_domain = '';
if(!$from){
    $referer_domain = explode('//', $from);
    $referer_domain = explode('/',$referer_domain[1]);
    $referer_domain = $referer_domain[0];
}
$sources = Option::get($moduleId,
    'SOURCE_' . SITE_ID,
    "yandex.ru\ngoogle.ru\nwww.yahoo.com\nwww.rambler.ru"
);
$sources = explode("\n",$sources);
$so = [];
foreach($sources as $s){
    $so[] = str_replace(
        [
            chr(13),
            chr(9),
            ' '
        ],
        '',
        $s
    );
}
if (!empty($cookie) && $cookie == bitrix_sessid() && SeometaStatisticsTable::getBySessId($cookie)) {
    $stat = SeometaStatisticsTable::getBySessId($cookie);
    $stat['PAGES_COUNT']++;
    SeometaStatisticsTable::update($stat['ID'],$stat);
    $APPLICATION->set_cookie('sotbit_seometa_statistic', bitrix_sessid(), time()+3*60*60);
} elseif(in_array($referer_domain,$so)) {
    $APPLICATION->set_cookie('sotbit_seometa_statistic', bitrix_sessid(), time()+3*60*60);
    $d = SeometaStatisticsTable::add([
        'DATE_CREATE' => new \Bitrix\Main\Type\DateTime(),
        'URL_FROM'=>$referer_domain,
        'URL_TO'=>$url,
        'SESS_ID'=>bitrix_sessid(),
        'CONDITION_ID'=>$condition_id,
        'PAGES_COUNT'=>1,
    ]);
}