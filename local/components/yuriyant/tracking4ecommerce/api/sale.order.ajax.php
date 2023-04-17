<?

namespace YuriyantGA;

use CModule;

define("NO_KEEP_STATISTIC", true);
define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define("PUBLIC_AJAX_MODE", true);
define("NO_AGENT_STATISTIC", true);

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

if (!IsModuleInstalled("yuriyant.tracking4ecommerce") || !CModule::IncludeModule("yuriyant.tracking4ecommerce")) {
    echo 'Module "Tracking 4 Ecommerce" not installed';
    return;
}

$deliveryToPaysystem = "d2p";
$siteID = 's1';
if ($_REQUEST['site']) {
    $siteID = trim(strip_tags($siteID));
}
if ($_REQUEST['deliveryToPaysystem'] && in_array(trim(mb_strtolower($_REQUEST['deliveryToPaysystem'])), ['d2p', 'p2d'])) {
    $deliveryToPaysystem = trim(mb_strtolower($_REQUEST['deliveryToPaysystem']));
}

$eventData = [
    'deliveryToPaysystem' => $deliveryToPaysystem,
    'SITE_ID' => $siteID
];

switch ($_REQUEST['action']) {
    case 'delivery':
        $eventData += ['deliveryID' => (int) $_REQUEST['deliveryID']];
        $event = new \Bitrix\Main\Event("yuriyant.tracking4ecommerce", "OnSaleComponentOrderOneStepDelivery", $eventData);
        $event->send();
        break;
    case 'payment':
        $eventData += ['paysystemID' => (int) $_REQUEST['paysystemID']];
        $event = new \Bitrix\Main\Event("yuriyant.tracking4ecommerce", "OnSaleComponentOrderOneStepPaySystem", $eventData);
        $event->send();
        break;
}
?>
<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>