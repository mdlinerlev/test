<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule("iblock")) {
    throw new \Exception('Не загружены модули необходимые для работы компонента');
}

use Ml\Main\Map\MapTable;

if (!MapTable::getEntity()->getConnection()->isTableExists(MapTable::getTableName())) {
    throw new \Exception('Не создан class orm ml_main_map');
}
$ar_main_map = MapTable::getList([
    "order" => ["ID" => "ASC"],
    "filter" => [
        ["LOGIC"=>"OR",
        "STATIC" => true,
        "URL" => "/catalog/"
        ]
    ]
])->fetchAll();

foreach ($ar_main_map as $fields) {
    $param_name_static_files["URL{$fields["URL"]}"] = [
        "PARENT" => "SETTINGS",
        "NAME" => $fields["NAME"].' '.$fields["URL"],
        "TYPE" => "STRING",
        "MULTIPLE" => "N",
        "DEFAULT" =>$fields["NAME"],
        "COLS" => 25
    ];
}
//pr($param_name_static_files);

$arComponentParameters = [
    // группы в левой части окна
    "GROUPS" => [
        "SETTINGS" => [
            "NAME" => Loc::getMessage('MEDIALINE_MAIN_MAP_PROP_SETTINGS'),
            "SORT" => 550,
        ],
    ],
    // поля для ввода параметров в правой части
    "PARAMETERS" =>
        // Произвольный параметр типа СТРОКА
        array_merge($param_name_static_files,['CACHE_TIME' => ['DEFAULT' => 3600]]),
        // Настройки кэширования
        //

];

