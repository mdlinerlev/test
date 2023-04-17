<?
$entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
$user = \Bitrix\Main\Engine\CurrentUser::get();

$req = $entity::getList([
    'select' => [
        'ID', 'NAME',
        'PROPERTY_USER_' => 'USER',
        'PROPERTY_IS_MAIN_' => 'IS_MAIN',
        'PROPERTY_MANAGER_EMAIL_' => 'MANAGER_EMAIL',
        'PROPERTY_EMAIL_' => 'EMAIL'
    ],
    'filter' => ['PROPERTY_USER_VALUE' => $user->getId(), 'PROPERTY_IS_MAIN_VALUE' => 1]
]);

$emailOut = $emailIn = '';

if($arItem = $req->fetch()){
    $emailOut = $arItem['PROPERTY_MANAGER_EMAIL_VALUE'];
    $emailIn = $arItem['PROPERTY_EMAIL_VALUE'];
}
?>
<div class="popup-b2b">
<?$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "b2b-popup",
    array(
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",

        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "N",
        "CHAIN_ITEM_LINK" => "",
        "CHAIN_ITEM_TEXT" => "",
        "EDIT_URL" => "",
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "LIST_URL" => "",
        "SEF_MODE" => "N",
        "SUCCESS_URL" => "",
        "USE_EXTENDED_ERRORS" => "N",
        "WEB_FORM_ID" => "8",
        "COMPONENT_TEMPLATE" => "b2b-popup",
        "VARIABLE_ALIASES" => array(
            "WEB_FORM_ID" => "WEB_FORM_ID",
            "RESULT_ID" => "RESULT_ID",
        ),
        'VALUES' => [
            'EMAIL_IN' => $emailIn,
            'EMAIL_OUT' => $emailOut
        ]
    ),
    false
);?>
</div>

