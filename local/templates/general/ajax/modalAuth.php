<div class="popup-b2b">
    <?$APPLICATION->IncludeComponent(
        "bitrix:system.auth.form",
        "modal",
        Array(
            "FORGOT_PASSWORD_URL" => "",
            "PROFILE_URL" => "",
            "REGISTER_URL" => "",
            "SHOW_ERRORS" => "Y",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
        )
    ); ?>
</div>
<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>