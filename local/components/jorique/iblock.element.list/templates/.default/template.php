<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

defined('B_PROLOG_INCLUDED') or die;

if($arParams['DISPLAY_TOP_PAGER']) {
	echo $arResult['NAV_STRING'];
}

/*echo '<pre>';
print_r($arResult);
echo '</pre>';*/

foreach($arResult['ITEMS'] as $item) {
	$this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT'));
	$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => 'Удалить?'));
	
	echo '<pre id="'.$this->GetEditAreaId($item['ID']).'">';
	print_r($item);
	echo '</pre>';
}

if($arParams['DISPLAY_BOTTOM_PAGER']) {
	echo $arResult['NAV_STRING'];
}