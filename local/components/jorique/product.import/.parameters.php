<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"IS_CLI" => array(
			"PARENT" => "BASE",
			"NAME" => "Запуск только из шелла",
			"TYPE" => "CHECKBOX",
		),
		"XML_PATH" => array(
			"PARENT" => "BASE",
			"NAME" => "Путь до XML-файла (CLI)",
			"TYPE" => "STRING"
		)
	)
);
?>
