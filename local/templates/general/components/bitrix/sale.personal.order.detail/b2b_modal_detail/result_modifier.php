<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Sale\Order;

$cp = $this->__component;
if (is_object($cp)) {
    CModule::IncludeModule('iblock');

    if (empty($arResult['ERRORS']['FATAL'])) {

        $hasDiscount = false;
        $hasProps = false;
        $productSum = 0;
        $basketRefs = array();

        $arNeedProps = [
            'KP_ID', 'NUMBER_1C', 'COMMENT', 'STOCK'
        ];
        $arOrderProps = [];
        $order = Bitrix\Sale\Order::load($arResult['ID']);
        $properties = $order->getPropertyCollection();
        foreach ($properties->getArray()['properties'] as $arProp) {
            if (in_array($arProp['CODE'], $arNeedProps)) {
                $arOrderProps[$arProp['CODE']] = $arProp['VALUE'][0];
            }
        }

        $arResult['PROPERTIES'] = $arOrderProps;
        $noPict = array(
            'SRC' => $this->GetFolder() . '/images/no_photo.png'
        );

        if (is_readable($nPictFile = $_SERVER['DOCUMENT_ROOT'] . $noPict['SRC'])) {
            $noPictSize = getimagesize($nPictFile);
            $noPict['WIDTH'] = $noPictSize[0];
            $noPict['HEIGHT'] = $noPictSize[1];
        }
        if (isset($arResult["BASKET"])) {
            foreach ($arResult["BASKET"] as $k => &$prod) {
                if (floatval($prod['DISCOUNT_PRICE']))
                    $hasDiscount = true;
                // move iblock props (if any) to basket props to have some kind of consistency
                if (isset($prod['IBLOCK_ID'])) {
                    $iblock = $prod['IBLOCK_ID'];
                    if (isset($prod['PARENT']))
                        $parentIblock = $prod['PARENT']['IBLOCK_ID'];
                    foreach ($arParams['CUSTOM_SELECT_PROPS'] as $prop) {
                        $key = $prop . '_VALUE';
                        if (isset($prod[$key])) {
                            // in the different iblocks we can have different properties under the same code
                            if (isset($arResult['PROPERTY_DESCRIPTION'][$iblock][$prop]))
                                $realProp = $arResult['PROPERTY_DESCRIPTION'][$iblock][$prop];
                            elseif (isset($arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop]))
                                $realProp = $arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop];
                            if (!empty($realProp))
                                $prod['PROPS'][] = array(
                                    'NAME' => $realProp['NAME'],
                                    'VALUE' => htmlspecialcharsEx($prod[$key])
                                );
                        }
                    }
                }
                // if we have props, show "properties" column
                if (!empty($prod['PROPS']))
                    $hasProps = true;
                $productSum += $prod['PRICE'] * $prod['QUANTITY'];
                $basketRefs[$prod['PRODUCT_ID']][] =& $arResult["BASKET"][$k];
                if (!isset($prod['PICTURE']))
                    $prod['PICTURE'] = $noPict;
            }
        }

        $arResult['HAS_DISCOUNT'] = $hasDiscount;
        $arResult['HAS_PROPS'] = $hasProps;

        $arResult['PRODUCT_SUM_FORMATTED'] = SaleFormatCurrency($productSum, $arResult['CURRENCY']);
    }
}

$prodIds = [];
foreach ($arResult['BASKET'] as $key => &$arBasketItem) {
    $prodIds[] = $arBasketItem['PRODUCT_ID'];
    $arBasketItem['PRICE'] = $arBasketItem['PRICE'] - ($arBasketItem['PRICE'] / 100 * $arResult['PROPERTIES']['STOCK']);
    $sum = $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'];
    $arBasketItem['FORMATED_SUM'] = SaleFormatCurrency($sum, $arResult['CURRENCY']);
}

$userPrice = getUserPrice();
$prices = [
    PRICE_TYPE_DEFAULT_ID,
    $userPrice
];

$arPrices = [];
$iterator = \Bitrix\Catalog\PriceTable::getList([
    'filter' => ['PRODUCT_ID' => $prodIds, 'CATALOG_GROUP_ID' => $prices]
]);
while ($arPrice = $iterator->fetch()) {
    $arPrices[$arPrice['PRODUCT_ID']][$arPrice['CATALOG_GROUP_ID']] = $arPrice['PRICE'];
}

$priceName = '';
$iterator = \Bitrix\Catalog\GroupLangTable::getList([
    'filter' => ['LANG' => 'ru', 'CATALOG_GROUP_ID' => $userPrice],
    'select' => ['NAME','CATALOG_GROUP_ID']
]);
if($arPrice = $iterator->fetch()){
    $priceName = $arPrice['NAME'];
}

$arResult['PRICE_NAME'] = $priceName;
$arResult['PRICE_TYPE'] = $userPrice;
$arResult['PRICES'] = $arPrices;
?>