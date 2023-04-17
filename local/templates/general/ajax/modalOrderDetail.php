<?$APPLICATION->IncludeComponent(
    "bitrix:sale.personal.order.detail",
    "b2b_modal_detail",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "N",
        "CUSTOM_SELECT_PROPS" => array(
        ),
        "DISALLOW_CANCEL" => "N",
        "ID" => $_REQUEST["ID"],
        "PATH_TO_CANCEL" => "",
        "PATH_TO_COPY" => "",
        "PATH_TO_LIST" => "",
        "PATH_TO_PAYMENT" => "payment.php",
        "PICTURE_HEIGHT" => "110",
        "PICTURE_RESAMPLE_TYPE" => "1",
        "PICTURE_WIDTH" => "110",
        "PROP_1" => array(
        ),
        "PROP_2" => array(
        ),
        "REFRESH_PRICES" => "N",
        "RESTRICT_CHANGE_PAYSYSTEM" => array(
            0 => "0",
        ),
        "SET_TITLE" => "N",
        "COMPONENT_TEMPLATE" => "b2b_modal_detail"
    ),
    false
);?>
