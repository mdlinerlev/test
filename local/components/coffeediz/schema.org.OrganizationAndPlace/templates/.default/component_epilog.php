<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/**
 * @var CMain $APPLICATION
 * @var array $arResult
 * @var array $arParams
 */

// callback function
$replacer = function ($matches) use ($APPLICATION) {
    ob_start();
    // тут вставляем разменту, вызовы компонентов, в общем все что нужно вывести
    // в метке #INNER_BLOCK_123# мы можем передать в качестве числа например код инфоблока
    // и использовать его так :?>
    <?$count = $matches[1];
    switch ($count) {
        case 1:
            ?>
            <?$APPLICATION->IncludeComponent(
            "coffeediz:schema.org.ImageObject",
            "",
            Array(
                "CONTENTURL" => $arParams['LOGO'],
                "URL" => $arParams['LOGO_URL'],
                "NAME" => $arParams['LOGO_NAME'],
                "CAPTION" => $arParams['LOGO_CAPTION'],
                "DESCRIPTION" => $arParams['LOGO_DESCRIPTION'],
                "HEIGHT" => $arParams['LOGO_HEIGHT'],
                "WIDTH" => $arParams['LOGO_WIDTH'],
                "TRUMBNAIL_CONTENTURL" => $arParams['LOGO_TRUMBNAIL_CONTENTURL'],
                "TRUMBNAIL_TYPE" => "N",
                "REPRESENTATIVEOFPAGE" => "",
                "PARAM_RATING_SHOW" => "N",
                "ITEMPROP" => "logo",
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        );?>
            <?

            break;

        case 2:
            ?>
            <?$APPLICATION->IncludeComponent(
            "coffeediz:schema.org.ImageObject",
            "",
            Array(
                "CONTENTURL" => $arParams['PHOTO_SRC'][$i],
                //"URL" => $arParams['PHOTO_SRC'][$i],
                "NAME" => $arParams['PHOTO_NAME'][$i],
                "CAPTION" => $arParams['PHOTO_CAPTION'][$i],
                "DESCRIPTION" => $arParams['PHOTO_DESCRIPTION'][$i],
                "HEIGHT" => $arParams['PHOTO_HEIGHT'][$i],
                "WIDTH" => $arParams['PHOTO_WIDTH'][$i],
                "TRUMBNAIL_CONTENTURL" => $arParams['PHOTO_TRUMBNAIL_CONTENTURL'][$i],
                "TRUMBNAIL_TYPE" => "N",
                "REPRESENTATIVEOFPAGE" => "",
                "PARAM_RATING_SHOW" => "N",
                "ITEMPROP" => "photo",
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        );?>
            <?
            break;
    }
    ?>

    <?return ob_get_clean();
};

// находим метку и заменяем ее на результат работы нашей функции
echo preg_replace_callback(
    "/#INNER_BLOCK_([\\d]+)#/is" . BX_UTF_PCRE_MODIFIER,
    $replacer,
    $arResult["CACHED_TPL"]
);