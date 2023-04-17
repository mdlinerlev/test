<?php
/**
 * User: sasha
 * Date: 31.07.18
 * Time: 14:11
 */
namespace Newsite\Sale;

class PropertyValueCollection extends \Bitrix\Sale\PropertyValueCollection
{

    /** @return \Newsite\Sale\PropertyValue|NULL */
    public function getItemPropertyByCode($code)
    {
        $property = null;
        foreach ($this as $itemProperty) {
            if ($itemProperty->getField("CODE") == $code) {
                $property = $itemProperty;
                break;
            }
        }

        return $property;
    }

    public function _getUserBy($field, $value) {
        if( empty( $field ) && empty( $value ) ) {
            return false;
        }
        return \Bitrix\Main\UserTable::getList(array(
            'filter'    => array($field => $value),
            'select'    => array('NAME', 'EMAIL', 'PERSONAL_MOBILE'),
            'limit'     => 1
        ))->fetch();
    }

    public function fillPropValue()
    {
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $requestState = $request->get("PROP");

        $arUser = $this->_getUserBy('=ID', $GLOBALS['USER']->GetID());

        foreach ($this as $itemProp) {
            $valueProp = '';
            /** @var $itemProp \Bitrix\Sale\PropertyValue */
            if ($requestState[$itemProp->getField('CODE')]) {
                $valueProp = $requestState[$itemProp->getField('CODE')];
            }

            $arProperty = $itemProp->getProperty();

            if ($arProperty['IS_LOCATION'] === 'Y') {
                if(!empty($locationId))
                    $valueProp = \CSaleLocation::getLocationCODEbyID($locationId);
            }

            if (empty($valueProp) && empty($requestState)) {
                $prop = $this->order->arUserResult['ORDER_PROP'];
                if(!empty($prop[$itemProp->getField('CODE')])) {
                    $valueProp = $prop[$itemProp->getField('CODE')];
                } elseif(!empty($arUser[$itemProp->getField('CODE')])) {
                    $valueProp = $arUser[$itemProp->getField('CODE')];
                } else {
                    $valueProp = $arProperty['DEFAULT_VALUE'];
                }
            }

            if (!empty($valueProp)) {
                $itemProp->setValue($valueProp);
            }
        }

    }

}