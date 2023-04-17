<?php
/**
 * User: sasha
 * Date: 01.08.18
 * Time: 12:57
 */

namespace Newsite\Sale\OrderStep;

use Newsite\Sale\PropertyGroupType;

class DeliveryDataStep extends OrderStep
{
    public function getCode()
    {
        return "delivery";
    }

    public function getGroupIds()
    {
        return [PropertyGroupType::GROUP_DELIVERY_FIZ, PropertyGroupType::GROUP_DELIVERY_UR];
    }
}