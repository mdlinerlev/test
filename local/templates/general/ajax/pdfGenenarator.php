<?
ini_set("pcre.backtrack_limit", "5000000");
$mpdf = new \Mpdf\Mpdf();
//$mpdf->showImageErrors = true;
$entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BKP)->getEntityDataClass();
$item = $entity::getByPrimary($request['ID'], [
   'select' => ['ID', 'NAME']
]);
if($arItem = $item->fetch()){
    $mpdf->SetTitle($arItem['NAME']);
}

ob_start();
$APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "comOfferPdf",
    Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_ELEMENT_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "BROWSER_TITLE" => "-",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_CODE" => "",
        "ELEMENT_ID" => $request['ID'],
        "FIELD_CODE" => array("",""),
        "IBLOCK_ID" => IBLOCK_ID_B2BKP,
        "IBLOCK_TYPE" => "personalb2b",
        "IBLOCK_URL" => "",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Страница",
        "PROPERTY_CODE" => array("ADDRESS","SALON_ADDRESS","CITY","DATE","CLIENT_NAME","CLIENT","NUMBER","SIGNATURE","USER","STOCK","STATUS","TABLE_TEXT_1","TABLE_TEXT_2","PHONE","TYPE","PAYMENT_TYPE","PRODUCTS","FIXED",""),
        "SET_BROWSER_TITLE" => "N",
        "SET_CANONICAL_URL" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "STRICT_SECTION_CHECK" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_SHARE" => "N"
    )
);
$template = ob_get_clean();

if($request['dev'] == 'Y'){
    echo $template;
}else{
    $mpdf->WriteHTML($template);
    $mpdf->Output();
}
