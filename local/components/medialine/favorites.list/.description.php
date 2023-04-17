<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => Loc::getMessage('COMPONENT_NAME'),
    "DESCRIPTION" => Loc::getMessage('COMPONENT_DESCRIPTION'),
    "PATH" => array(
        "ID" => "MAIN_EXCEL",
        "CHILD" => array(

        ),
    ),
);
?>