<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('ml.soap')) return;

Loc::loadMessages(__FILE__);

$aMenu = array(
    "parent_menu" => "global_menu_services",
    "sort" => 160,
    'text' => "Настройки soap от MediaLine",
    'title' => "Настройки soap от MediaLine",
    "icon" => "sys_menu_icon",
    "page_icon" => "sys_menu_icon",
    "items_id" => "ml_soap",
    'section' => "ml_soap",
    'menu_id' => 'ml_soap',
    "items" => [
        [
            'sort' => 1000,
            'url' => '/bitrix/admin/settings.php?lang=ru&mid=ml.soap&mid_menu=1',
            'text' => "Настройки",
            'title' => "Настройки",
            'items_id' => 'ml_soap_settings',
            'icon' => 'sale_menu_icon',
        ],
    ]

);

return $aMenu;