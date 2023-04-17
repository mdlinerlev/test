<?php
/**
 * User: sasha
 * Date: 01.08.18
 * Time: 12:57
 */

namespace Newsite\Sale\OrderStep;

use Newsite\Sale\PropertyGroupType;

class PaymentDataStep extends OrderStep
{
    public function getCode()
    {
        return "payment";
    }

    public function getGroupIds()
    {
        return [PropertyGroupType::GROUP_PAYMENT_FIZ,PropertyGroupType::GROUP_PAYMENT_UR];
    }
}