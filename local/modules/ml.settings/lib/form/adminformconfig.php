<?php

namespace Ml\Settings\Form;


use Bitrix\Iblock\SectionTable;
use Ml\Settings\Model\PropertyTable;
use Ml\Settings\Module;
use Bitrix\Main\Loader;

class AdminFormConfig extends Module
{
    public static function getSiteProperties(bool $all = false)
    {
        $options = self::GetOptions();

        $data[0] = 'Не выбрано';
        if (!empty($options['IBLOCK_ID']) && Loader::includeModule('iblock')) {

            $iblocks = [];
            $iblocks[] = $options['IBLOCK_ID'];
            if(!empty($options['IBLOCK_OFFERS_ID'])){
                $iblocks[] = $options['IBLOCK_OFFERS_ID'];
            }

            $filter = ['IBLOCK_ID' => $iblocks, 'ACTIVE' => 'Y'];
            if (!$all) {
                $iterator = PropertyTable::getList([
                    'select' => ['PROPERTY'],
                    'group' => 'PROPERTY',
                    'cache' => ['ttl' => 3600]
                ]);
                while ($arProp = $iterator->fetch()){
                    $arPropertiesCode[] = $arProp['PROPERTY'];
                }
                if ($arPropertiesCode) {
                    $filter['CODE'] = $arPropertiesCode;
                } else {
                    $filter['CODE'] = 0;
                }
            }

            $iterator = \Bitrix\Iblock\PropertyTable::getList([
                'select' => ['ID', 'CODE', 'NAME'],
                'filter' => $filter
            ]);
            while ($arProp = $iterator->fetch()) {
                $data[$arProp['CODE']] = '[' . $arProp['CODE'] . '] ' . $arProp['NAME'];
            }
        }
        return $data;
    }

    public static function getSiteSections(bool $all = false)
    {
        $data = [];
        $options = self::GetOptions();
        $data[0] = 'Не выбрано';
        if (!empty($options['IBLOCK_ID'])) {

            $filter = ['IBLOCK_ID' => $options['IBLOCK_ID']];
            if(!$all){
                $iterator = PropertyTable::getList([
                    'select' => ['SECTION'],
                    'group' => 'SECTION',
                    'cache' => ['ttl' => 3600]
                ]);
                while ($arSection = $iterator->fetch()){
                    $arSectionsIds[] = $arSection['SECTION'];
                }

                if($arSectionsIds){
                    $filter['ID'] = $arSectionsIds;
                }else{
                    $filter['ID'] = 0;
                }
            }

            $iterator = \CIBlockSection::GetTreeList($filter, ['ID', 'NAME', 'DEPTH_LEVEL']);
            while ($section = $iterator->GetNext()) {
                $dots = str_repeat('. ', --$section['DEPTH_LEVEL']);
                $data[$section['ID']] = $dots . $section['NAME'];
            }

        }

        return $data;
    }

    public static function getConfig()
    {
        return [
            'Property' => [
                'fields' => [
                    'ID' => [
                        'widget' => 'number',
                        'readonly' => true,
                        'hide_when_create' => true
                    ],
                    'SECTION' => [
                        'widget' => 'enum',
                        'values' => self::getSiteSections(true)
                    ],
                    'PROPERTY' => [
                        'widget' => 'enum',
                        'values' => self::getSiteProperties(true)
                    ],
                    'PROPERTY_VALUE' => [
                        'widget' => 'string',
                    ],
                    'LINK' => [
                        'widget' => 'string'
                    ],
                    'NEW_WINDOW' => [
                        'widget' => 'boolean'
                    ]
                ],
                'columns' => [
                    ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true],
                    ['id' => 'SECTION', 'name' => 'Раздел сайта', 'sort' => 'SECTION', 'default' => true, 'editable' => true, 'type' => 'checkbox'],
                    ['id' => 'PROPERTY', 'name' => 'Код свойства', 'sort' => 'PROPERTY', 'default' => true],
                    ['id' => 'PROPERTY_VALUE', 'name' => 'Значение свойства', 'sort' => 'PROPERTY_VALUE', 'default' => true],
                    ['id' => 'LINK', 'name' => 'Ссылка', 'sort' => 'LINK', 'default' => true],
                    ['id' => 'NEW_WINDOW', 'name' => 'Открывать в новом окне', 'sort' => 'NEW_WINDOW', 'default' => true],
                ],
                'filter' => [
                    ['id' => 'ID', 'name' => 'ID', 'type' => 'number', 'default' => true],
                    [
                        'id' => 'SECTION',
                        'name' => 'Раздел',
                        'type' => 'list',
                        'default' => true,
                        'params' => ['multiple' => 'Y'],
                        'items' => self::getSiteSections()
                    ],
                    [
                        'id' => 'PROPERTY',
                        'name' => 'Свойство сайта',
                        'type' => 'list',
                        'default' => true,
                        'params' => ['multiple' => 'Y'],
                        'items' => self::getSiteProperties()
                    ],
                    ['id' => 'PROPERTY_VALUE', 'name' => 'Значение свойства', 'type' => 'text', 'default' => true],
                    ['id' => 'LINK', 'name' => 'Ссылка', 'sort' => 'LINK', 'default' => true],
                    ['id' => 'NEW_WINDOW', 'name' => 'Открывать в новом окне', 'default' => true],
                ],
            ],
        ];
    }
}