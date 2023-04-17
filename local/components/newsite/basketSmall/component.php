<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/* @var $APPLICATION CMain */
/* @var $this  CNewsiteBasketSmall */
/* @var $DB CDatabase */

if (defined("HIDE_QBASKET")) {

}

$this->initCompParams();
$this->initOrder();
$this->GetImages();

$this->arResult["TEMPLATE"] = "";

$this->IncludeComponentTemplate($this->arResult["TEMPLATE"]);

