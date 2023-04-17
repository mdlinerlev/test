<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
if (Loader::includeModule('ml.soap')) {
    $wsdl = new \Ml\Soap\Wsdl\Server();
    $wsdl->run();
}

