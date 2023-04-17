<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->arResult["DATE_BEFORE"] = date('Y-m-d',strtotime($arParams["MY_DATA"]));

if ($arParams["ACTIVE"] == "Y" &&
    (strtotime($this->arResult["DATE_BEFORE"]) > strtotime(date("Y-m-d ")))
) {
    $GLOBALS['promotionalHeadBanner'] = array_merge($this->arParams, $this->arResult); // сохр. для использования в микроразметке event
    $this->IncludeComponentTemplate($this->arResult["TEMPLATE"]);
}
