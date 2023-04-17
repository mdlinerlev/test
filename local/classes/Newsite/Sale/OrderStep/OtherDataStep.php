<?php
/**
 * User: sasha
 * Date: 01.08.18
 * Time: 12:57
 */

namespace Newsite\Sale\OrderStep;

use Newsite\Sale\PropertyGroupType;

class OtherDataStep extends OrderStep
{
    public function getCode()
    {
        return "other";
    }

    public function getGroupIds()
    {
        return [PropertyGroupType::GROUP_OTHER_FIZ, PropertyGroupType::GROUP_OTHER_UR];
    }
}