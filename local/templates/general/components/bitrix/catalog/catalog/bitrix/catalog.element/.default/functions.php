<?php

if(!function_exists('getColorById')) {
	function getColorById($id) {
		static $inited = false;
		if(!$inited) {
			CModule::IncludeModule('highloadblock');
			$hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
			Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
		}
		$res = ColorReferenceTable::getList(array(
			'filter' => array('=ID' => $id),
			'select' => array(
				'ID', 'UF_NAME', 'UF_XML_ID', 'UF_FILE'
			)
		))->fetch();
		return $res;
	}
}