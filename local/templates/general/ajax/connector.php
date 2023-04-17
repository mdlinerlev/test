<?php
if($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	$dir = realpath(dirname(__FILE__));
	$file = str_replace('..', '', $_REQUEST['act'].'.php');
	$path = $dir.DIRECTORY_SEPARATOR.$file;
	$path = preg_replace(array('/(\.+\/)/'), array(''), $path);
	$path = str_replace('/', DIRECTORY_SEPARATOR, $path);

	if(isset($_REQUEST['act']) && file_exists($path)) {
		require $path;
	}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
}