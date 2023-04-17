<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$arComponentDescription = array(
	"NAME" => GetMessage("T_MEDIALINE_INSTAGRAM_NAME"),
	"DESCRIPTION" => GetMessage("T_MEDIALINE_INSTAGRAM_DESCRIPTION"),
	"ICON" => "/images/news-list.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "medialine",
		"NAME" => GetMessage("MEDIALINE")
	),
	"COMPLEX" => "N"
);
?>