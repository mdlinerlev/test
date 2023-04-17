<?php

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;

/**
 * @var array $arCurrentValues
 * @global CUserTypeManager $USER_FIELD_MANAGER
 */

defined('B_PROLOG_INCLUDED') or die;
Loader::includeModule('iblock');

# инфоблоки
$iblockFilter = $arCurrentValues['IBLOCK_TYPE'] ? array('IBLOCK_TYPE_ID' => $arCurrentValues['IBLOCK_TYPE']) : array();
$rsIblocks = IblockTable::getList(array(
	'select' => array('ID', 'NAME'),
	'filter' => array_merge($iblockFilter, array('ACTIVE' => 'Y'))
));
$iblocks = array();
while ($iblock = $rsIblocks->fetch()) {
	$iblocks[$iblock['ID']] = '[' . $iblock['ID'] . '] ' . $iblock['NAME'];
}

# поля разделов
$sectionFields = CIBlockParameters::GetSectionFieldCode('Поля раздела', 'SELECT');
$sectionFields = array_merge_recursive($sectionFields, array(
	'VALUES' => array('SECTION_PAGE_URL' => 'URL')
));

# пользовательские поля раздела
global $USER_FIELD_MANAGER;
$sectionUserFields = array();
$sectionUserFieldsStr = array();
$userFields = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_' . $arCurrentValues['IBLOCK_ID'] . '_SECTION');
foreach ($userFields as $fieldName => $userField) {
	$sectionUserFields[$fieldName] = $userField['LIST_COLUMN_LABEL'] ?: $fieldName;
	if ($userField['USER_TYPE']['BASE_TYPE'] == 'string') {
		$sectionUserFieldsStr[$fieldName] = $sectionUserFields[$fieldName];
	}
}

# поля элементов
$elementFields = CIBlockParameters::GetFieldCode('Поля элементов', 'SELECT');
$elementFields = array_merge_recursive($elementFields, array(
	'VALUES' => array('DETAIL_PAGE_URL' => 'URL')
));

# свойства элементов
$properties = array();
if ($arCurrentValues['IBLOCK_ID']) {
	$propertyIterator = PropertyTable::getList(array(
		'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
		'filter' => array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y'),
		'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
	));
	while ($property = $propertyIterator->fetch()) {
		$code = (string)$property['CODE'] ?: $property['ID'];
		$propertyName = '[' . $code . ']' . $property['NAME'];

		//if ($property['PROPERTY_TYPE'] != PropertyTable::TYPE_FILE) {
		$properties[$code] = $propertyName;
		//}

	}
	unset($code, $propertyName, $property, $propertyIterator);
}

# сортировка
$sortFields = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);
$sortDirs = array(
	'asc' => 'по возрастанию',
	'desc' => 'по убыванию'
);


$arComponentParameters = array(
	'GROUPS' => array(
		'SELECT' => array(
			'NAME' => 'Выборка'
		)
	),
	'PARAMETERS' => array(
		'AJAX_MODE' => array(),
		'IBLOCK_TYPE' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Тип инфоблока',
			'TYPE' => 'LIST',
			'VALUES' => CIBlockParameters::GetIBlockTypes(),
			'REFRESH' => 'Y',
		),
		'IBLOCK_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Инфоблок',
			'TYPE' => 'LIST',
			'ADDITIONAL_VALUES' => 'Y',
			'VALUES' => $iblocks,
			'REFRESH' => 'Y',
		),
		'SECTION_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => 'ID раздела',
			'TYPE' => 'STRING'
		),
		'SECTION_CODE' => array(
			'PARENT' => 'BASE',
			'NAME' => 'CODE раздела',
			'TYPE' => 'STRING'
		),
		'FILTER_NAME' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Имя фильтра',
			'TYPE' => 'STRING'
		),
		'INCLUDE_SUBSECTIONS' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Выводить элементы подразделов',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),
		'SORT_FIELD' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Поле для сортировки',
			'TYPE' => 'LIST',
			'VALUES' => $sortFields,
			'ADDITIONAL_VALUES' => 'Y',
			'DEFAULT' => 'sort',
		),
		'SORT_ORDER' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Направление сортировки',
			'TYPE' => 'LIST',
			'VALUES' => $sortDirs,
			'DEFAULT' => 'asc',
			'ADDITIONAL_VALUES' => 'Y',
		),
		'SORT_FIELD2' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Поле для сортировки 2',
			'TYPE' => 'LIST',
			'VALUES' => $sortFields,
			'ADDITIONAL_VALUES' => 'Y',
			'DEFAULT' => 'id',
		),
		'SORT_ORDER2' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Направление сортировки 2',
			'TYPE' => 'LIST',
			'VALUES' => $sortDirs,
			'DEFAULT' => 'desc',
			'ADDITIONAL_VALUES' => 'Y',
		),
		'URL_404' => array(
			'PARENT' => 'BASE',
			'NAME' => 'URL 404 страницы',
			'TYPE' => 'STRING',
			'DEFAULT' => '/404.php'
		),
		'ADD_SECTIONS_CHAIN' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Добавлять разделы в breadcrumbs',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		"SECTION_URL" => CIBlockParameters::GetPathTemplateParam(
			"SECTION",
			"SECTION_URL",
			'URL раздела',
			"",
			"URL_TEMPLATES"
		),
		"DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
			"DETAIL",
			"DETAIL_URL",
			'URL элементов',
			"",
			"URL_TEMPLATES"
		),
		'ELEMENT_CONTROLS' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Кнопки управления элементами',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),
		'SECTION_FIELDS' => $sectionFields,
		'SECTION_USER_FIELDS' => array(
			'PARENT' => 'SELECT',
			'NAME' => 'Пользовательские поля раздела',
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'Y',
			'VALUES' => $sectionUserFields
		),
		'ELEMENT_FIELDS' => $elementFields,
		'ELEMENT_PROPERTIES' => array(
			'PARENT' => 'SELECT',
			'NAME' => 'Свойства элементов',
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'Y',
			'VALUES' => $properties
		),
		'CACHE_TIME' => array('DEFAULT' => 3600),
		'CACHE_GROUPS' => array(
			'PARENT' => 'CACHE_SETTINGS',
			'NAME' => 'Учитывать права доступа при кешировании',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),
		'CACHE_FILTER' => array(
			'PARENT' => 'CACHE_SETTINGS',
			'NAME' => 'Кешировать при установленном фильтре',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),
		'CACHE_SALT' => array(
			'PARENT' => 'CACHE_SETTINGS',
			'NAME' => 'Дополнительная строка для ID кеша',
			'TYPE' => 'STRING'
		)
	)
);

# параметры навигации
CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage('Элементы!'), true, false);
$arComponentParameters['PARAMETERS']['PAGE_ELEMENT_COUNT'] = array(
	'PARENT' => 'PAGER_SETTINGS',
	'NAME' => 'Элементов на странице',
	'TYPE' => 'STRING',
	'DEFAULT' => '30',
);

/*echo '<pre>';
print_r($arComponentParameters);
echo '</pre>';*/