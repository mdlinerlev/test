<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//$this->IncludeComponentTemplate();

$APPLICATION->AddBufferContent(function() {
	global $APPLICATION;

	if (!isset($GLOBALS['promotionalHeadBanner']) || (count($GLOBALS['promotionalHeadBanner']) == 0)) return;

	ob_start();
	$this->IncludeComponentTemplate();
	$template = ob_get_contents();
	ob_end_clean();

	return $template;
});