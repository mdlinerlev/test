<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule('iblock');

$arSelect = ['ID', 'XML_ID'];
$arFilter = ['IBLOCK_ID' => IBLOCK_ID_CATALOG];

$iterator = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
$arItems = $arOffers = $arResult = [];

while ($arItem = $iterator->GetNext()){
    $arItems[$arItem['ID']] = $arItem['XML_ID'];
}

$arSelect = ['ID', 'XML_ID', 'IBLOCK_ID', 'PROPERTY_CML2_LINK'];
$arFilter = ['IBLOCK_ID' => IBLOCK_ID_OFFERS];
$iterator = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
while ($arItem = $iterator->GetNext()){
    $arResult[$arItem['XML_ID']] = $arItems[$arItem['PROPERTY_CML2_LINK_VALUE']];
}

file_put_contents(__DIR__.'/import.txt', print_r(serialize($arResult), 1));