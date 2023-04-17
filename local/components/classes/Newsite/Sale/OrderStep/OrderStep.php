<?php
/**
 * User: sasha
 * Date: 31.07.18
 * Time: 16:43
 */

namespace Newsite\Sale\OrderStep;

abstract class OrderStep extends \Bitrix\Sale\Internals\CollectableEntity
{
    abstract function getCode();
    abstract function getGroupIds();

    public function getStatusStep()
    {
        $resultValidate = $this->validate();
        return $resultValidate->isSuccess() ? OrderStepStatus::SUCCESS : OrderStepStatus::ERROR;
    }

    /** @return \Bitrix\Sale\Result */
    public function validate()
    {

        $resultValidate = new \Bitrix\Sale\Result();
        $resultValidateProps = $this->validateProps();

        if (!$resultValidateProps->isSuccess()) {
            $resultValidate->addErrors( $resultValidateProps->getErrors() );
        }

        return $resultValidate;
    }

    protected function validateProps()
    {
        $result = new \Bitrix\Sale\Result();
        /** @var \Newsite\Sale\Order $order */
        $order = $this->getCollection()->getOrder();
        global $USER;
        $groupIds = (array)$this->getGroupIds();
        foreach ($groupIds as $itemGroupId) {
            $itemPropertysGroup = $order->getPropertyByGroup($itemGroupId);
            foreach ($itemPropertysGroup as $itemProperty) {
                /** @var $itemProperty \Bitrix\Sale\PropertyValue */
                $resultItemProp = $itemProperty->checkValue( $itemProperty->getField("CODE"), $itemProperty->getValue() );
                if (!$resultItemProp->isSuccess()) {
                    $result->addErrors( $resultItemProp->getErrors() );
                }
                $resultItemProp = $itemProperty->checkRequiredValue( $itemProperty->getField("CODE"), $itemProperty->getValue() );
                if (!$resultItemProp->isSuccess()) {
                    $result->addErrors( $resultItemProp->getErrors() );
                }
            }
        }
        return $result;
    }

}