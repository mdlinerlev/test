<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/* @var $USER CUser */
/* @var $this CShBasket */
/* @var $APPLICATION CMain */

$this->initCompParams();
$this->action = $this->prepareAction();

$this->doAction($this->action);

$this->GetImages();

$this->includeComponentTemplate($this->arResult["TEMPLATE"]);