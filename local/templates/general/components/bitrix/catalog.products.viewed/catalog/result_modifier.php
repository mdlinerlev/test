<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

pr($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE']);
foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = [];
    $prop = $arItem['DISPLAY_PROPERTIES'];
    if(!empty($arItem['OFFERS'])) {
        /*array_multisort(
            array_column($arItem['OFFERS'], 'PROPERTY_SKLAD_VALUE'), SORT_DESC,
            array_column($arItem['OFFERS'], 'CATALOG_PRICE_1'), SORT_ASC,
            array_column($arItem['OFFERS'], 'ID'), SORT_DESC,
            $arItem['OFFERS']);*///fix warning

        $arItem = current($arItem['OFFERS']);
        $arItem['DISPLAY_PROPERTIES'] = $prop;
    }

    $preview = $arItem['PREVIEW_PICTURE'] ? : $arItem['DETAIL_PICTURE'];

    if (!empty($preview)) {
        $type = BX_RESIZE_IMAGE_EXACT;
        if($preview['WIDTH'] >= $preview['HEIGHT']){
            $type = BX_RESIZE_IMAGE_PROPORTIONAL_ALT;
        }
        //pr($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE']);
        if(($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] == 'Купе' || $arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] == 'Распашная двойная' || $arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] == 'Купе двойная') && $arItem['DISPLAY_PROPERTIES']['DOUBLE_IMAGE']['VALUE'] != 1) {
            $arItem['PICTURE'] = CFile::ResizeImageGet($preview['ID'], array(
                //'width' => 200,
                //'height' => 280
            ), $type  );
        } else {
            $arItem['PICTURE'] = CFile::ResizeImageGet($preview['ID'], array(
                //'width' => 135,
                //'height' => 280
            ), $type);
        }

    }
}
unset($arItem);

$ids = array_column($arResult['ITEMS'], 'ID');
if(!empty($ids)) {
    $rs = CIBlockElement::GetList([], ['IBLOCK_ID' => [$arParams['IBLOCK_ID'], IBLOCK_ID_OFFERS], '=ID' => $ids], false, false, ['ID', 'DETAIL_PAGE_URL']);
    while($o = $rs->getNext()){
        $arResult['DETAIL_PAGE_URL'][$o['ID']] = $o['DETAIL_PAGE_URL'];
    }
}

foreach ($arResult['ITEMS'] as $key => $arItem) {

    if($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] != 'Распашная двойная' && $arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] != 'Купе двойная') {

    } else {

    }
    $arResult['ITEMS'][$key]['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem['PREVIEW_PICTURE']['SRC']);
}
?>

