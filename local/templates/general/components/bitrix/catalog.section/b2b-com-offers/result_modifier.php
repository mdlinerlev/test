<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$arResult['PROPERTY_LIST_VAL']['STATUS'] = getPropertyListVariant('STATUS', $arParams['IBLOCK_ID'], ['ID', 'VALUE', 'PROPERTY_ID']);
$arResult['PROPERTY_LIST_VAL']['PAYMENT_TYPE'] = getPropertyListVariant('PAYMENT_TYPE', $arParams['IBLOCK_ID'], ['ID', 'VALUE', 'PROPERTY_ID']);

$arProducts = [];
foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['PROPERTIES']['PRODUCTS']['VALUE'])) {
        $stock = intval($arItem['PROPERTIES']['STOCK']['VALUE']);
        $products = unserialize($arItem['PROPERTIES']['PRODUCTS']['~VALUE']);

        $totalPrice = 0;
        foreach ($products as &$product){
            $arProducts[$product['id']] = $products;

            $arPrice = CCatalogProduct::GetOptimalPrice(
                $product['id'],
                $product['count'],
                [],
                'N',
                [],
                SITE_ID,
                false
            );
            $totalPrice += ($arPrice['DISCOUNT_PRICE'] * $product['count']);
        }

        $discPrice = $totalPrice;
        $stockVal = 0;
        if(intval($stock) > 0){
            $stockVal = $totalPrice / 100 * $stock;
            $discPrice = $totalPrice - $stockVal;
        }

        $arItem['TOTAL_PRICE'] = CurrencyFormat($totalPrice, 'RUB');
        $arItem['STOCK'] = CurrencyFormat($stockVal, 'RUB');
        $arItem['DISCOUNT_PRICE'] = CurrencyFormat($discPrice, 'RUB');
    }
}