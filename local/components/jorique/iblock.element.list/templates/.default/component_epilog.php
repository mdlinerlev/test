<?php

/**
 * @var array $arResult
 * @global CMain $APPLICATION
 */

# метатеги
$iv = $arResult['IPROPERTY_VALUES'];
if(!empty($iv['SECTION_META_KEYWORDS'])) {
	$APPLICATION->SetPageProperty('KEYWORDS', $iv['SECTION_META_KEYWORDS']);
}
if(!empty($iv['SECTION_META_DESCRIPTION'])) {
	$APPLICATION->SetPageProperty('DESCRIPTION', $iv['SECTION_META_DESCRIPTION']);
}

# титл
if(!empty($iv['SECTION_META_TITLE'])) {
	$APPLICATION->SetPageProperty('TITLE', $iv['SECTION_META_TITLE']);
}

# заголовок на странице
if(!empty($iv['SECTION_PAGE_TITLE'])) {
	$APPLICATION->SetTitle($iv['SECTION_PAGE_TITLE']);
}
elseif($arResult['NAME']) {
	$APPLICATION->SetTitle($arResult['NAME']);
}