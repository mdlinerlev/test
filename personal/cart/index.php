<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
if(\Bitrix\Main\Engine\CurrentUser::get()->getId()){
    LocalRedirect('/personal-b2b/cart/');
}
?>
<?$APPLICATION->IncludeComponent(
    "newsite:basket",
    "",
    Array(
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
    ),
    false,
    Array(
        'HIDE_ICONS' => 'Y'
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>