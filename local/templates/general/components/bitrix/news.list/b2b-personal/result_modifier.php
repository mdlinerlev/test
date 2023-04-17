<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$component = $this->getComponent();

$document = $manager = $address = [];
$itemId = 0;
foreach ($arResult['ITEMS'] as $arItem){
    $document = [
        'DOCUMENT' => $arItem['PROPERTIES']['DOCUMENT']['VALUE'],
        'PRICE' => $arItem['PROPERTIES']['PRICE_NAME']['VALUE'],
        'PERIOD' => $arItem['PROPERTIES']['PERIOD']['VALUE']
    ];
    $manager = [
        'NAME' => $arItem['PROPERTIES']['MANAGER']['VALUE'],
        'EMAIL' => $arItem['PROPERTIES']['MANAGER_EMAIL']['VALUE'],
        'PHONE' => $arItem['PROPERTIES']['MANAGER_PHONE']['VALUE']
    ];


    if(!empty($arItem['PROPERTIES']['ACTUAL_ADDRESS']['VALUE'])){
        $address['ACTUAL_ADDRESS'] = $arItem['PROPERTIES']['ACTUAL_ADDRESS']['VALUE'];
    }
    if(!empty($arItem['PROPERTIES']['LAW_ADDRESS']['VALUE'])){
        $address['LAW_ADDRESS'] = $arItem['PROPERTIES']['LAW_ADDRESS']['VALUE'];
    }
    if(!empty($arItem['PROPERTIES']['POST_ADDRESS']['VALUE'])){
        $address['POST_ADDRESS'] = $arItem['PROPERTIES']['POST_ADDRESS']['VALUE'];
    }
    $itemId = $arItem['ID'];
}

$arResult['ADDRESS'] = $address;
$arResult['MANAGER'] = $manager;
$arResult['DOCUMENT'] = $document;
$arResult["ITEM_ID"] = $itemId;

if(is_object($component)) {
    $component->$arResult["ADDRESS"] = $arResult["ADDRESS"];
    $component->$arResult["MANAGER"] = $arResult["MANAGER"];
    $component->$arResult["DOCUMENT"] = $arResult["DOCUMENT"];
    $component->$arResult["ITEM_ID"] = $arResult["ITEM_ID"];
    $component->SetResultCacheKeys(['MANAGER', 'DOCUMENT', 'ADDRESS', 'ITEM_ID']);
}