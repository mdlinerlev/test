<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        "TEXT"=>[
            'PARENT' => 'BASE',
            'NAME' => GetMessage('T_TEXT'),
            'TYPE' => 'STRING',
            "DEFAULT" => GetMessage('T_TEXT_DEFAULT')
        ],
        "LINK"=>[
            'PARENT' => 'BASE',
            'NAME' => GetMessage('T_LINK'),
            'TYPE' => 'STRING',
            "DEFAULT" => GetMessage('T_LINK_DEFAULT')
        ],
        "TEXT_BUTTON"=>[
            'PARENT' => 'BASE',
            'NAME' => GetMessage('T_TEXT_BUTTON'),
            'TYPE' => 'STRING',
            "DEFAULT" => GetMessage('T_TEXT_BUTTON_DEFAULT')
        ],
        'CACHE_TIME'  => [
            'DEFAULT' => 86400,
        ],
        'CACHE_GROUPS' => [
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => GetMessage('CP_BNL_CACHE_GROUPS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
    ],
];
