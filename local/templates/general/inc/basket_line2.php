<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
    "newsite:basketSmall",
    ".default",
    array(
        "COMPONENT_TEMPLATE"     => ".default",
        "AJAX_MODE"              => "Y",
        "AJAX_OPTION_JUMP"       => "N",
        "AJAX_OPTION_STYLE"      => "Y",
        "AJAX_OPTION_HISTORY"    => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
    ),
    false
); ?>
