<?php

namespace Ml\Main\Map;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class MapTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ml_main_map';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('IBLOCK_ID', array(
                'required' => false
            )),
            new Entity\StringField('SECTION_ID', array(
                'required' => false
            )),
            new Entity\StringField('IBLOCK_SECTION_ID', array(
                'required' => false
            )),
            new Entity\StringField('NAME', array(
                'required' => false
            )),
            new Entity\StringField('URL', array(
                'required' => false
            )),
            new Entity\StringField('DEPTH_LEVEL', array(
                'required' => false
            )),
            new Entity\BooleanField('STATIC', array(
                'required' => false
            )),
        );
    }
}