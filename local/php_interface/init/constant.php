<?php
define('NO_IMAGE_SRC', '/images/no_photo.png');
define('SITE_DEFAULT_TEMPLATE_PATH', '/bitrix/templates/.default');
define('CURPAGE', $APPLICATION->GetCurPage());
define('IBLOCK_ID_CATALOG', 2);
define('IBLOCK_ID_OFFERS', 12);
define('IBLOCK_ID_BRANDS', 4);
define('IBLOCK_ID_INTERIORS', 13);
define('IBLOCK_ID_COMMENTS', 5);
define('IBLOCK_ID_PROJECTS', 15);
define('SECTION_ID_FURNITURA', 22);
define('IB_ID_STATI', 21);
/*b2b profile*/
define('IBLOCK_ID_B2BPROFILE', 33);
define('IBLOCK_ID_B2BADDRESS', 32);
define('IBLOCK_ID_B2BKP', 34);
# типы товаров
define('TYPE_INTERIOR_DOORS', 23);
define('TYPE_EXTERIOR_DOORS', 24);
define('TYPE_FINDINGS', 25);
define('TYPE_FLOOR', 26);
define('TYPE_DEKOR', 437);


# id св-в
define('SIZE_DOOR', 267);
define('WIDTH_PANEL', 268);
define('CONFIGURATION', 167);

# стороны открывания дверей
define('OPEN_SIDE_LEFT', 33);
define('OPEN_SIDE_RIGHT', 34);

# разделы фонов интерьеров
define('INTERIOR_SECTION_INTERIOR', 46);
define('INTERIOR_SECTION_EXTERIOR', 47);

# раздел планок и реек
define('PANEL_SECTION', 330);

# св-во для связи проектов с дверьми
define('PROP_CODE_USED_DOORS', 'USED_DOORS');

define('HLBLOCK_ID_COLORS', 1);
define('HLBLOCK_ID_COLOR_GROUPS', 3);
define('HLBLOCK_ID_B2BSTOCK', 5);

define('CATALOG_MENU_IBLOCK_ID', 23);

define('MAIN_CURRENCY', 'RUB');
define('MAIN_CURRENCY_TEXT', 'руб.');

define('B2B_GROUP', 11);
define('PRICE_TYPE_DEFAULT_ID', 1);
define('LANDING_ID', '9e31e2c3-ad58-4ac1-8b2a-30bef70fe238');
define('IBLOCK_ID_BANNER_ITEMS', 29);

$arWaterMark = [
    [
        'name' => 'watermark',
        'position' => 'bottomright', // Положение
        'type' => 'image',
        'size' => 'medium',
        'file' => $_SERVER['DOCUMENT_ROOT'].'/upload/watermark.png', // Путь к картинке
        //  'fill' => 'exact',
    ]
];
define('PICTURE_WATER_MARK', $arWaterMark);

define('IBLOCK_ID_ICON', 31);