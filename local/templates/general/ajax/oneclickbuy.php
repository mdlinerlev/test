<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "oneclickbuy",
        Array(
            "CACHE_TIME" => "0",
            "PRODUCT_ID" => $request['productId'],
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "popup-1",
            "AJAX_OPTION_JUMP " => "N",
            "AJAX_OPTION_SHADOW" => "N",
            "CACHE_TYPE" => "N",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_URL" => "",
            "IGNORE_CUSTOM_TEMPLATE" => "Y",
            "LIST_URL" => "",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID"),
            "WEB_FORM_ID" => "7"
        )
    );?>

<?


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>