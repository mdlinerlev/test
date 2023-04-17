<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $APPLICATION;

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/components/bitrix/news/projects/bitrix/news.detail/.default/fancybox.umd.js" );
//$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/components/bitrix/news/projects/bitrix/news.detail/.default/scripts.js" );
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/components/bitrix/news/projects/bitrix/news.detail/.default/fancybox.css", true);

?>