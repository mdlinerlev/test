<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Context,
    Bitrix\Sale,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

$result = [
    'success' => false,
    'needReload' => true,

    'title' => '',
    'success_message' => '',
    'errorMsg' => '',
];

$arFields['UF_ID'] =  $_REQUEST['ID'];
$arFields['UF_PRODUCT_ID'] = $_REQUEST['PRODUCT_ID'];
if(isset($_REQUEST['WIDTH']) && $_REQUEST['WIDTH'] > 0)
    $arFields['UF_WIDTH'] = (int)$_REQUEST['WIDTH'];

if(isset($_REQUEST['HEIGHT']) && $_REQUEST['HEIGHT'] > 0)
    $arFields['UF_HEIGHT'] = (int)$_REQUEST['HEIGHT'];

if(isset($_REQUEST['COLOR']) && $_REQUEST['COLOR'] > 0)
    $arFields['UF_COLOR'] = (int)$_REQUEST['COLOR'];

if(isset($_REQUEST['PROCENT']) && $_REQUEST['PROCENT'] > 0)
    $arFields['UF_PROCENT'] = (int)$_REQUEST['PROCENT'];
else
    $arFields['UF_PROCENT'] = 0;

$hlID = 6;
$id = 0;
$arHlElements = HLHelpers::getInstance()->getElementList($hlID, ['UF_ID' => $_REQUEST['ID'], 'UF_PRODUCT_ID' => $_REQUEST['PRODUCT_ID']]);
foreach ($arHlElements as $element) {
    if($element['ID']) {
        $id = $element['ID'];
        break;
    }
}

if($id) {
    $isUpd = HLHelpers::getInstance()->updateElement($hlID, $id, $arFields);
} else {
    $id = HLHelpers::getInstance()->addElement($hlID, $arFields);
    // при false ошибка будет в HLHelpers::$LAST_ERROR
}


file_put_contents(__DIR__.'/testReq.txt', print_r($id, 1), FILE_APPEND);
$result['success'] = $id;
echo \Bitrix\Main\Web\Json::encode($result);