<?
require_once 'tools.php';
require_once 'autoload.php';
$eventManager = \Bitrix\Main\EventManager::getInstance();
/*sale*/
$eventManager->addEventHandler("sale", "OnSaleOrderSaved", ['SaleHandler', 'OnAfterOrderSave'], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/SaleHandler.php');

/*main*/
$eventManager->addEventHandler("main", "OnProlog", "SiteInitAction", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/onProlog.php', 1);
$eventManager->addEventHandler("main", "OnEndBufferContent", "linksReplace", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/OnEndBufferContent.php');
$eventManager->addEventHandler("main", "OnEndBufferContent", "replaceSpaces", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/OnEndBufferContent.php');
$eventManager->addEventHandler("main", "OnEpilog", "SeoEpilogMethods", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/onEpilog.php');

/*iblock*/
$eventManager->addEventHandler("iblock", "OnBeforeIBlockElementAdd", ["CIblockHandler", "isNotSpam"], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/CIblockHandler.php', 1);
$eventManager->addEventHandler("iblock", "OnIBlockPropertyBuildList", ["CPropertyElementEnum", "GetUserTypeDescription"], $_SERVER['DOCUMENT_ROOT'] . "/local/props/stringElementEnum/stringElementEnum.php");
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ["CIblockHandler", "onAfterIblock"], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/CIblockHandler.php', 1);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ["CIblockHandler", "onAfterIblock"], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/CIblockHandler.php', 1);
$eventManager->addEventHandler("iblock", "OnIBlockPropertyBuildList", ["PropertyTypePrice", "GetUserTypeDescription"], $_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/events/PropertyTypePrice.php");
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementAdd", ["CIblockHandler", "SetNumberKp"], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/CIblockHandler.php', 1);
$eventManager->addEventHandler("iblock", "OnAfterIBlockElementUpdate", ["CIblockHandler", "SetNumberKp"], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/CIblockHandler.php', 1);

/*catalog*/
//$eventManager->addEventHandler("catalog", "OnBeforeProductAdd", "OnBeforeIBlockElement", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/onBeforeProduct.php', 1);
//$eventManager->addEventHandler("catalog", "OnBeforeProductUpdate", "OnBeforeIBlockElement", $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/onBeforeProduct.php', 1);
$eventManager->addEventHandler('catalog', 'OnGetOptimalPrice', ['SaleHandler','getOptimalPrice'], $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/SaleHandler.php');

/*sphinx*/
$eventManager->AddEventHandler("sphinx", "OnBeforeXmlAddRow", ["CoptimizatorCorrectSphinxFilter", "CorectRowData"], $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events/OnBeforeFilterSet.php");

/*hightload*/
$eventManager->addEventHandler(
    '',
    'B2bstocksOnBeforeUpdate',
    ['B2bStockHandler', 'FieldValidation'],
    $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events/b2bStockHandler.php'
);