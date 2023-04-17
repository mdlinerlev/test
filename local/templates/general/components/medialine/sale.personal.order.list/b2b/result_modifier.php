<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Bitrix\Sale;

Loc::loadMessages(__FILE__);

$arNeedProps = [
    'KP_ID', 'NUMBER_1C', 'COMMENT', 'STATUS', 'STOCK'
];

$arOrderProps = [];
$arBasketItems = [];
if (!empty($arNeedProps)) {
    foreach ($arResult["ORDERS"] as $arOrder) {
        $order = Bitrix\Sale\Order::load($arOrder['ORDER']['ID']);
        $properties = $order->getPropertyCollection();
        foreach ($properties->getArray()['properties'] as $arProp) {
            if (in_array($arProp['CODE'], $arNeedProps)) {
                $arOrderProps[$arOrder['ORDER']['ID']][$arProp['CODE']] = $arProp['VALUE'][0];
            }
        }

        foreach ($arOrder['BASKET_ITEMS'] as $arBasketItem){
            $arBasketItems[$arBasketItem['PRODUCT_ID']] = $arBasketItem['PRODUCT_ID'];
        }
    }
}

$arPrices = [];

$userPrice = getUserPrice();
$arUserPrices = [
    $userPrice, PRICE_TYPE_DEFAULT_ID
];

$iterator = \Bitrix\Catalog\PriceTable::getList([
    'filter' => ['PRODUCT_ID' => $arBasketItems, 'CATALOG_GROUP_ID' => $arUserPrices]
]);
while ($arPrice = $iterator->fetch()){
    $arPrices[$arPrice['PRODUCT_ID']][$arPrice['CATALOG_GROUP_ID']] = $arPrice['PRICE'];
}

if (is_array($arResult["ORDERS"]) && !empty($arResult["ORDERS"])) {
    foreach ($arResult["ORDERS"] as &$order) {
        $order['ORDER']['PROPERTIES'] = $arOrderProps[$order['ORDER']['ID']];

        foreach ($order['BASKET_ITEMS'] as $arBasketItem){
            if(isset( $arPrices[$arBasketItem['PRODUCT_ID']][PRICE_TYPE_DEFAULT_ID])){
                $sum += ($arPrices[$arBasketItem['PRODUCT_ID']][PRICE_TYPE_DEFAULT_ID] * $arBasketItem['QUANTITY']);
            }
        }

        if($arOrderProps[$order['ORDER']['ID']]['STOCK']) {
            $sum = $sum - (($sum / 100) * $arOrderProps[$order['ORDER']['ID']]['STOCK']);
        }

        $order['ORDER']['PRICE_BASE_DISCOUNT'] = CurrencyFormat($sum, $order['ORDER']['CURRENCY']);
    }
}

$entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
$iterator = $entity::getList([
    'select' => [
        'ID',
        'PROPERTY_IS_MAIN_' => 'IS_MAIN',
        'PROPERTY_USER_' => 'USER',
        'PROPERTY_MANAGER_PHONE_' => 'MANAGER_PHONE',
        'PROPERTY_MANAGER_' => 'MANAGER',
    ],
    'filter' => ['PROPERTY_IS_MAIN_VALUE' => 1, 'PROPERTY_USER_VALUE' => \Bitrix\Main\Engine\CurrentUser::get()->getId()]
]);
if ($arItem = $iterator->fetch()) {
    $arResult['MANAGER'] = [
        'NAME' => $arItem['PROPERTY_MANAGER_VALUE'],
        'PHONE' => $arItem['PROPERTY_MANAGER_PHONE_VALUE']
    ];
} ?>