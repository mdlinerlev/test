<?
$APPLICATION->IncludeComponent(
    "newsite:search.title",
    "search_main_menu_new",
    array(
        "NUM_CATEGORIES" => "1",
        "TOP_COUNT" => "5",
        "CHECK_DATES" => "Y",
        "SHOW_OTHERS" => "N",
        "PAGE" => SITE_DIR . "catalog/",
        "CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
        "CATEGORY_0" => array(
            0 => "iblock_catalog",
        ),
        "CATEGORY_0_iblock_catalog" => array(
            0 => "2",
        ),
        "CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
        "SHOW_INPUT" => "Y",
        "INPUT_ID" => "title-search-input-2",
        "PLACEHOLDER" => "Какую дверь будем искать?",
        "CONTAINER_ID" => "search-top",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "SHOW_PREVIEW" => "Y",
        "PREVIEW_WIDTH" => "75",
        "PREVIEW_HEIGHT" => "75",
        "CONVERT_CURRENCY" => "Y",
        "COMPONENT_TEMPLATE" => "search_main_menu",
        "ORDER" => "date",
        "USE_LANGUAGE_GUESS" => "N",
        "PRICE_VAT_INCLUDE" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "CURRENCY_ID" => MAIN_CURRENCY,
        "CATEGORY_0_iblock_offers" => array(
            0 => "all",
        )
    ),
    false
);
