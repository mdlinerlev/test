<?php
/**
 * User: sasha
 * Date: 01.08.18
 * Time: 12:57
 */

namespace Newsite\Sale\OrderStep;

use Newsite\Sale\PropertyGroupType;

class PersonalDataStep extends OrderStep
{
    public function getCode()
    {
        return "personal";
    }

    public function getGroupIds()
    {
        return [PropertyGroupType::GROUP_USER_FIZ, PropertyGroupType::GROUP_USER_UR ];
    }
}