<?php
/**
 * User: sasha
 * Date: 31.07.18
 * Time: 16:42
 */

namespace Newsite\Sale\OrderStep;

class OrderStepCollection
    extends \Bitrix\Sale\Internals\EntityCollection
{

    /** @var  $order \Newsite\Sale\Order */
    protected
        $order = null;

    public static $succesedSteps=array();



    public function __construct(\Newsite\Sale\Order $order)
    {
        $this->order = $order;
    }

    public function getEntityParent()
    {
        return $this->getOrder();
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setStep(\Newsite\Sale\OrderStep\OrderStep $step)
    {
        $this->offsetSet($step->getCode(), $step);
        $step->setCollection($this);
    }

    /**
     *  @return string
     */
    public function getOpenStepCode()
    {

        $curentOpenStep = "";
        $nextIsBreak = false;
        $editStepCode = \Bitrix\Main\Context::getCurrent()->getRequest()->get("stepEdit");
        $finishStepCode = \Bitrix\Main\Context::getCurrent()->getRequest()->get("stepFinish");
        $currentStepCode = \Bitrix\Main\Context::getCurrent()->getRequest()->get("stepCurrent");
        if(!$curentOpenStep && !$finishStepCode && !$editStepCode){
            $editStepCode = 'personal';
        }

        foreach ($this as $itemStep) {
            /** @var $itemStep OrderStep */

            $curentOpenStep = $itemStep->getCode();
            self::$succesedSteps[] = $curentOpenStep;
            if ($itemStep->getStatusStep() != OrderStepStatus::SUCCESS) {
                break;
            }
            if (empty($editStepCode) && empty($finishStepCode) && $itemStep->getCode() == $currentStepCode) {
                break;
            }

            if ($itemStep->getCode() == $editStepCode) {
                break;
            }
            if ($nextIsBreak) {
                break;
            }
            if ($itemStep->getCode() == $finishStepCode) {
                $nextIsBreak = true;
            }
        }
        return $curentOpenStep;
    }


    /**
     * @return bool
     */
    public function isValid()
    {
        $steCollectionIsValid = true;
        foreach ($this as $itemStep) {
            /** @var $itemStep OrderStep */
            if ($itemStep->getStatusStep() != OrderStepStatus::SUCCESS) {

                $steCollectionIsValid = false;
                break;
            }
        }
        return $steCollectionIsValid;
    }

}