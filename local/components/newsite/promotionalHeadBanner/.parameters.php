<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$ext = 'jpg,jpeg,png';

$arComponentParameters = array(
    "GROUPS" => array(
        "COLOR_SETTING" => array(
            "NAME" => "Цветовая настройка",
            "SORT" => "500",
        )
    ),
    "PARAMETERS" => array(
        "ACTIVE" => array(
            "PARENT" => "BASE",
            "NAME" => "Активность баннера",
            "TYPE" => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT" => "Y",
        ),
        "LINK_FOR_PROMOTIONAL" => array(
            "PARENT" => "BASE",
            "NAME" => "Ссылка",
            "TYPE" => "STRING",
            "DEFAULT" => "#",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'MY_DATA' => array(
            'NAME' => "Дата конца акции",
            'TYPE' => 'CUSTOM',
            'JS_FILE' => '/local/components/newsite/promotionalHeadBanner/settings/settings.js',
            'JS_EVENT' => 'OnMySettingsEdit',
            'JS_DATA' => json_encode(array('set' => "Календарь")),
            'DEFAULT' => null,
            'PARENT' => 'BASE',
        ),
        /*'BANNER' => array(
            "PARENT" => "BASE",
            "NAME" => 'Выберите баннер (1920 x 58):',
            "TYPE" => "FILE",
            "FD_TARGET" => "F",
            "FD_EXT" => $ext,
            "FD_UPLOAD" => true,
            "FD_USE_MEDIALIB" => true,
            "FD_MEDIALIB_TYPES" => Array()
        ),
        'MOB_BANNER' => array(
            "PARENT" => "BASE",
            "NAME" => 'Выберите мобильный баннер (1024 x 103):',
            "TYPE" => "FILE",
            "FD_TARGET" => "F",
            "FD_EXT" => $ext,
            "FD_UPLOAD" => true,
            "FD_USE_MEDIALIB" => true,
            "FD_MEDIALIB_TYPES" => Array()
        ),*/
        'BACK_COLOR' => array(
            "PARENT" => "COLOR_SETTING",
            "NAME" => "Цвет счётчика",
            "TYPE" => "STRING",
            "DEFAULT" => "#ef8b43",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'SLESH_COLOR' => array(
            "PARENT" => "COLOR_SETTING",
            "NAME" => "Цвет слешей",
            "TYPE" => "STRING",
            "DEFAULT" => "#ffae73",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'BACKGROUND_COLOR' => array(
            "PARENT" => "COLOR_SETTING",
            "NAME" => "Цвет фона",
            "TYPE" => "STRING",
            "DEFAULT" => "#ffae73",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'TITLE_COLOR' => array(
            "PARENT" => "COLOR_SETTING",
            "NAME" => "Цвет заголовка",
            "TYPE" => "STRING",
            "DEFAULT" => "#ffae73",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'DESCRIPTION_COLOR' => array(
            "PARENT" => "COLOR_SETTING",
            "NAME" => "Цвет описания",
            "TYPE" => "STRING",
            "DEFAULT" => "#ffae73",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'MAIN_TEXT' => array(
            "PARENT" => "BASE",
            "NAME" => "Главный текст (до 25 символов)",
            "TYPE" => "STRING",
            "DEFAULT" => "#ef8b43",
            "ADDITIONAL_VALUES" => "Y",
        ),
        'ADDITIONAL_TEXT' => array(
            "PARENT" => "BASE",
            "NAME" => "Доп. текст (до 75 символов)",
            "TYPE" => "STRING",
            "DEFAULT" => "#ef8b43",
            "ADDITIONAL_VALUES" => "Y",
        ),
    ),
);
?>
<script type="text/javascript">
    var interval = setInterval(
        function () {

            if (document.querySelector('input[data-bx-property-id="MAIN_TEXT"]') != "undefined" && document.querySelector('input[data-bx-property-id="ADDITIONAL_TEXT"]') != "undefined") {
                document.querySelector('input[data-bx-property-id="MAIN_TEXT"]').setAttribute("maxlength", 25)
                document.querySelector('input[data-bx-property-id="ADDITIONAL_TEXT"]').setAttribute("maxlength", 75)
                clearInterval(interval)
            }
        }
    , 500);
</script>