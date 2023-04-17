<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$userGroup = \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups();

$items = unserialize($arResult['PROPERTIES']['PRODUCTS']['~VALUE']);
$itemsId = [];

$userPrice = getUserPrice();
$arUserPrices = [
    $userPrice, PRICE_TYPE_DEFAULT_ID
];

$itemsResult = [];
foreach ($items as $arItem) {
    //pr($arItem);
    if(!empty($arItem['procent'])) {
        $proc = $arItem['procent'];
    }
    $itemsId[] = $arItem['id'];
    $itemsResult[$arItem['id']] = $arItem;
}

$iterator = \Bitrix\Catalog\PriceTable::getList([
    'select' => ['PRICE', 'CATALOG_GROUP_ID', 'PRODUCT_ID'],
    'filter' => ['PRODUCT_ID' => $itemsId, 'CATALOG_GROUP_ID' => $arUserPrices]
]);
while ($arPrice = $iterator->fetch()){
    if (intval($arResult['PROPERTIES']['STOCK']['VALUE']) > 0 && $arPrice['CATALOG_GROUP_ID'] == PRICE_TYPE_DEFAULT_ID) {
        $arPrice['PRICE'] = $arPrice['PRICE'] / 100 * (100 - intval($arResult['PROPERTIES']['STOCK']['VALUE']));
    }
    if($arPrice['CATALOG_GROUP_ID'] == PRICE_TYPE_DEFAULT_ID){
        $sum = $arPrice['PRICE']*$itemsResult[$arPrice['PRODUCT_ID']]['count'];
        $sum += $sum * $itemsResult[$arPrice['PRODUCT_ID']]['procent'] / 100;
        $itemsResult[$arPrice['PRODUCT_ID']]['price_sum'] = CurrencyFormat(getWatPrice($sum)['~PRICE'], 'RUB');
        $itemsResult[$arPrice['PRODUCT_ID']]['price_sum_wat'] = CurrencyFormat($sum, 'RUB');

        $totalPrice += $sum;
    }

    if($arPrice['CATALOG_GROUP_ID'] == $userPrice){
        $sum = $arPrice['PRICE']*$itemsResult[$arPrice['PRODUCT_ID']]['count'];
        $sum += $sum * $itemsResult[$arPrice['PRODUCT_ID']]['procent'] / 100;
        $itemsResult[$arPrice['PRODUCT_ID']]['price_purchase'] = CurrencyFormat($sum, 'RUB');
    }

    $itemsResult[$arPrice['PRODUCT_ID']]['prices'][$arPrice['CATALOG_GROUP_ID']] = CurrencyFormat($arPrice['PRICE'] + $arPrice['PRICE'] * $itemsResult[$arPrice['PRODUCT_ID']]['procent'] / 100, 'RUB');
}

$iterator = \Bitrix\Iblock\ElementTable::getList([
    'select' => ['ID', 'NAME', 'DETAIL_PICTURE', 'PREVIEW_PICTURE'],
    'filter' => ['ID' => $itemsId]
]);
while ($arItem = $iterator->fetch()) {
    $itemsResult[$arItem['ID']]['id'] = $arItem['ID'];
    $itemsResult[$arItem['ID']]['name'] = $arItem['NAME'];
    $itemsResult[$arItem['ID']]['picture'] = ($arItem['DETAIL_PICTURE']) ? : $arItem['PREVIEW_PICTURE'];
    if(isset($itemsResult[$arItem['ID']]['width']) && isset($itemsResult[$arItem['ID']]['height'])) {
        $itemsResult[$arItem['ID']]['name'] .= ' ( Высота: '.$itemsResult[$arItem['ID']]['width']. ', Ширина: '.$itemsResult[$arItem['ID']]['height'].' )';
        $itemsResult[$arItem['ID']]['check'] = 1;
    }

    if(isset($itemsResult[$arItem['ID']]['color'])) {
        $itemsResult[$arItem['ID']]['name'] .= ' ( Цвет RAL: '.$itemsResult[$arItem['ID']]['color'].' )';
        $itemsResult[$arItem['ID']]['check'] = 1;
    }
}

$stock = 0;
/*$priceDiscount = $totalPrice;
if (intval($arResult['PROPERTIES']['STOCK']['VALUE']) > 0) {
    $stock = $totalPrice / 100 * intval($arResult['PROPERTIES']['STOCK']['VALUE']);
    $priceDiscount -= $stock;
}*/


$arResult['PRICE_TYPE'] = $userPrice;
$arResult['ITEMS'] = $itemsResult;
$arResult['DISCOUNT_PRICE'] = CurrencyFormat($totalPrice, 'RUB');