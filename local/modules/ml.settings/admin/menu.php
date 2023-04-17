<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('ml.settings')) return;

Loc::loadMessages(__FILE__);

$aMenu = array(
    "parent_menu" => "global_menu_services",
    "sort" => 160,
    'text' => "Настройки сайта от MediaLine",
    'title' => "Настройки сайта от MediaLine",
    "icon" => "sys_menu_icon",
    "page_icon" => "sys_menu_icon",
    "items_id" => "ml_settings",
    'section' => "ml_settings",
    'menu_id' => 'ml_settings',
    "items" => [
        [
            'sort' => 100,
            'url' => '/bitrix/admin/settings.php?lang=ru&mid=ml.settings&mid_menu=1',
            'text' => "Настройки",
            'title' => "Настройки",
            'items_id' => 'ml_settings',
            'icon' => 'sale_menu_icon',
        ],
        [
            'sort' => 200,
            'url' => '/bitrix/admin/ml_settings_list.php?lang=ru&ENTITY=Property',
            'text' => "Свойства",
            'title' => "Свойства",
            'items_id' => 'ml_settings',
            'icon' => 'sale_menu_icon',
        ],
    ]

);

return $aMenu;