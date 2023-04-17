<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

$arComponentParameters = array(
	'GROUPS' => array(
	),
	'PARAMETERS' => array(
		'TOKEN' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_TOKEN'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'IGQVJWQUNFN2owN0ViTGZAHSzBZAY0E4RVZACa21SOUFtdFVBUkF4UVVEUjRrU3JtR01uMm1RdzNzYVNFVmJsWVd2T1BPVl9kNnNXLWkzdkZA5LTkxMXVvM0U3WGtwWndKanZAXVFJQdkVVdFRseTNzWjZArYwZDZD',
		),
		'TITLE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_TITLE'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('T_INSTAGRAM_TITLE_VALUE'),
		),
		'ALL_TITLE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_ALL_TITLE'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('T_INSTAGRAM_ALL_TITLE_VALUE'),
		),
		'ITEMS_COUNT' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_ITEMS_COUNT'),
			'TYPE' => 'STRING',
			'DEFAULT' => '8',
		),
		'ITEMS_VISIBLE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_ITEMS_VISIBLE'),
			'TYPE' => 'LIST',
			'TYPE' => 'STRING',
			'DEFAULT' => '4',
		),
        'TEXT_LENGTH' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('T_INSTAGRAM_TEXT_LENGTH'),
			'TYPE' => 'STRING',
			'DEFAULT' => '400',
		),
		'CACHE_TIME'  =>  array(
			'DEFAULT' => 86400,
		),
		'CACHE_GROUPS' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => GetMessage('CP_BNL_CACHE_GROUPS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
	),
);
