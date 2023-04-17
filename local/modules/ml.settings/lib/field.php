<?php

namespace Ml\Settings;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;

class Field
{
    private $field;

    /**
     * Field constructor.
     * @param $class string
     * @param $field
     */
    public function __construct($class, $field) {
        try {

            /** @var $class DataManager */
            #Module::autoLoad($class);

            $this->field = $class::getEntity()->getField($field);
        } catch (ArgumentException $e) {
        } catch (SystemException $e) {
        }
    }

    public function modify($value) {

        $class = get_class($this->field);

        switch ($class) {
            case DateField::class:
                if($value != ''){
                    $value = new Date($value);
                }else{
                    $value = new \Bitrix\Main\Type\Date('0000-00-00', 'Y-m-d');
                }
                break;
            case DatetimeField::class:
                if($value != ''){
                    $value = new DateTime($value);
                }else{
                    $value = new \Bitrix\Main\Type\DateTime('0000-00-00 00:00:00', 'Y-m-d H:i:s');
                }
                break;
        }

        return $value;
    }
}
