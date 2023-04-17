<?php

/**
 * User: sasha
 * Date: 30.07.18
 * Time: 11:12
 */
namespace Newsite\Sale;

class BasketItem extends \Bitrix\Sale\BasketItem
{

    public static function getCalculatedFields()
    {
        $result = [
            'IMAGE',
            'ARTICLE'
        ];
        return array_merge($result, parent::getCalculatedFields());
    }

}