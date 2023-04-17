<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("MEDIALINE_MAIN_MAP_COMPONENT"),
    "DESCRIPTION" => Loc::getMessage("MEDIALINE_MAIN_MAP_COMPONENT_DESCRIPTION"),
    "COMPLEX" => "N",
    "PATH" => [
        "ID" => 'medialine',
        "NAME" => Loc::getMessage("MEDIALINE_MAIN_MAP_COMPONENT_PATH_NAME"),
    ],
];
?>
