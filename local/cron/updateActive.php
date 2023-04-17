<?$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/ext_www/belwooddoors.ru";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);
$IBLOCK_ID = 2;

use Bitrix\Main\Loader;

Loader::includemodule("iblock");

$arSelect = Array("ID", "ACTIVE");
$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($arFields = $res->GetNext()) {
    $el = new CIBlockElement;
    $testUpdate = true;
    $el->Update($arFields["ID"], ["ACTIVE" => $arFields['ACTIVE']]);
    $testUpdate = false;
}



