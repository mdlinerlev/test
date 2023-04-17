<?php

use Bitrix\Main\Loader;

/**
 * @var JoriqueIblockElementList $this
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

defined('B_PROLOG_INCLUDED') or die;

Loader::includeModule('iblock');

$this->setParams();

CPageOption::SetOptionString('main', 'nav_page_in_session', 'N');

# фильтр
$filterName = $arParams['FILTER_NAME'];
if (!preg_match('/^[A-Za-z_][A-Za-z01-9_]*$/', $filterName) || !isset($GLOBALS[$filterName])) {
	$extFilter = array();
}
else {
	$extFilter = (array)$GLOBALS[$filterName];
}

$arNavParams = $this->getNavArray();
$arNavigation = $this->getNavForCache();
$cacheAddSalt = isset($arParams['CACHE_SALT']) ? $arParams['CACHE_SALT'] : '';

if($this->startResultCache(false, array($extFilter, ($arParams['CACHE_GROUPS']=='N' ? false: $USER->GetGroups()), $arNavigation, $cacheAddSalt))) {
	$isObject = new CIBlockSection;
	$ieObject = new CIBlockElement;

	# ищем сам раздел
	$filter = array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'IBLOCK_ACTIVE' => 'Y',
		'ACTIVE' => 'Y',
		'GLOBAL_ACTIVE' => 'Y',
	);

	$select = array_merge($arParams['SECTION_FIELDS'], $arParams['SECTION_USER_FIELDS']);
	$sectionFound = false;
	if($arParams['SECTION_ID']) {
		$filter['ID'] = $arParams['SECTION_ID'];
		$rsResult = $isObject->GetList(false, $filter, false, $select);
		$arParams['SECTION_URL'] && $rsResult->SetUrlTemplates('', $arParams['SECTION_URL']);
		$arResult = $rsResult->GetNext(true, false);
	}
	elseif($arParams['SECTION_CODE']) {
		$filter['=CODE'] = $arParams['SECTION_CODE'];
		$rsResult = $isObject->GetList(false, $filter, false, $select);
		$arParams['SECTION_URL'] && $rsResult->SetUrlTemplates('', $arParams['SECTION_URL']);
		$arResult = $rsResult->GetNext(true, false);
	}
	else {
		$arResult = array(
			'ID' => 0,
			'IBLOCK_ID' => $arParams['IBLOCK_ID']
		);
	}

	if(!$arResult) {
		$this->AbortResultCache();
		$this->set404();
		return;
	}

	# seo
	if($arResult['ID']) {
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arResult['ID']);
		$arResult['IPROPERTY_VALUES'] = $ipropValues->getValues();
	}
	else {
		$arResult['IPROPERTY_VALUES'] = array();
	}

	# массив для кнопок эрмитажа
	if($arParams['CACHE_GROUPS']!='Y' || $USER->IsAuthorized()) {
		$arResult['BUTTONS'] = CIBlock::GetPanelButtons($arParams['IBLOCK_ID'], 0, $arResult['ID'], array(
			'SECTION_BUTTONS' => true,
			'SESSID' => false
		));
	}

	# массив для breadcrumbs
	if ($arResult['ID'] && $arParams['ADD_SECTIONS_CHAIN']) {
		$arResult['PATH'] = array();
		$rsPath = CIBlockSection::GetNavChain($arResult['IBLOCK_ID'], $arResult['ID']);
		$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
		while($arPath = $rsPath->GetNext()) {
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
			$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
			$arResult['PATH'][] = $arPath;
		}
	}

	# ищем элементы
	$filter = array(
		'SECTION_ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'IBLOCK_LID' => SITE_ID,
		'IBLOCK_ACTIVE' => 'Y',
		//'SECTION_GLOBAL_ACTIVE' => 'Y',
		'ACTIVE' => 'Y',
		'CHECK_PERMISSIONS' => 'Y',
		'MIN_PERMISSION' => 'R',
		'INCLUDE_SUBSECTIONS' => ($arParams['INCLUDE_SUBSECTIONS'] == 'N' ? 'N' : 'Y')
	);
	$filter = array_merge($filter, $extFilter);

	if(!$filter['SECTION_ID'] && $arParams['INCLUDE_SUBSECTIONS']=='Y') {
		unset($filter['SECTION_ID'], $filter['INCLUDE_SUBSECTIONS']);
	}

	$sort = $this->getSortArray();

	//$USER->IsAdmin() && print('<pre>'.print_r($filter, 1).'</pre>');

	$select = $arParams['ELEMENT_FIELDS'];
	if($arParams['ELEMENT_PROPERTIES']) {
		foreach($arParams['ELEMENT_PROPERTIES'] as $property) {
			$property && $select[] = 'PROPERTY_'.$property;
		}
	}

	$rsElements = $ieObject->GetList($sort, $filter, false, $arNavParams, $select);
	$arParams['DETAIL_URL'] && $rsElements->SetUrlTemplates($arParams['DETAIL_URL']);
	$arElements = array();
	while($arElement = $rsElements->GetNext(true, false)) {
		if($arParams['ELEMENT_CONTROLS'] == 'Y') {
			$arButtons = CIBlock::GetPanelButtons(
				$arElement['IBLOCK_ID'],
				$arElement['ID'],
				$arResult['ID'],
				array('SECTION_BUTTONS' => false, 'SESSID' => false, 'CATALOG' => true)
			);
			$arElement['EDIT_LINK'] = $arButtons['edit']['edit_element']['ACTION_URL'];
			$arElement['DELETE_LINK'] = $arButtons['edit']['delete_element']['ACTION_URL'];
		}
		
		$arElements[] = $arElement;
	}
	$arResult['ITEMS'] = $arElements;

	if($arParams['DISPLAY_TOP_PAGER'] || $arParams['DISPLAY_BOTTOM_PAGER']) {
		$arResult['NAV_STRING'] = $rsElements->GetPageNavStringEx($navComponentObject, $arParams['PAGER_TITLE'], $arParams['PAGER_TEMPLATE'], $arParams['PAGER_SHOW_ALWAYS']);
		/** @var CBitrixComponent $navComponentObject */
		$arResult['NAV_CACHED_DATA'] = $navComponentObject->GetTemplateCachedData();
		//$arResult['NAV_RESULT'] = $rsElements;
	}

	$this->SetResultCacheKeys(array(
		'ID',
		'NAV_CACHED_DATA',
		'NAME',
		'PATH',
		'IBLOCK_SECTION_ID',
		'IPROPERTY_VALUES',
		'BUTTONS'
	));

	$this->includeComponentTemplate();
}

# breadcrumbs
if ($arParams['ADD_SECTIONS_CHAIN'] && isset($arResult['PATH']) && is_array($arResult['PATH'])) {
	foreach($arResult['PATH'] as $arPath) {
		if ($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '') {
			$APPLICATION->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
		}
		else {
			$APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
		}
	}
}

# кнопки в панели
if($arResult['BUTTONS']) {
	$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arResult['BUTTONS']));
}



# хз, зачем это
/*$this->SetTemplateCachedData($arResult['NAV_CACHED_DATA']);*/

return $arResult['ID'];