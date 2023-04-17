<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$userGroup = \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups();

$items = unserialize($arResult['PROPERTIES']['PRODUCTS']['~VALUE']);
$itemsId = [];

$itemsResult = [];
$totalPrice = 0;
foreach ($items as $arItem) {
    $itemsId[] = $arItem['id'];
    if ($arPrice = CCatalogProduct::GetOptimalPrice(
        $arItem['id'],
        $arItem['count'],
        $userGroup,
        'N',
        [],
        SITE_ID,
        []
    )) {
        $arItem['price'] = number_format($arPrice['DISCOUNT_PRICE'], 2, ",", " ");
        $arItem['price_sum_val'] = $arPrice['DISCOUNT_PRICE'] * $arItem['count'];
        $arItem['price_sum'] = number_format($arPrice['DISCOUNT_PRICE'] * $arItem['count'], 2, ",", " ");
        $totalPrice += $arPrice['DISCOUNT_PRICE'] * $arItem['count'];
    }
    $itemsResult[$arItem['id']] = $arItem;
}

$arPictures = [];
if (!empty($itemsId)) {
    $iterator = \Bitrix\Iblock\ElementTable::getList([
        'select' => ['ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'NAME'],
        'filter' => ['ID' => $itemsId]
    ]);
    $count = $key = 0;
    $row = 4;
    while ($arItem = $iterator->fetch()) {
        $itemsResult[$arItem['ID']]['name'] = $arItem['NAME'];

        $picture = '';
        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $picture = $arItem['PREVIEW_PICTURE'];
        } elseif ($arItem['DETAIL_PICTURE']) {
            $picture = $arItem['DETAIL_PICTURE'];
        }

        if (!empty($picture)) {
            $file = CFile::ResizeImageGet($picture, ['width'=>600, 'height'=>600], BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $count++;
            $arPictures[$key][$arItem['ID']] = $file['src'];

            if($count >= $row){
                $count = 0;
                $key++;
            }
        }
    }
}

$stock = 0;
$priceDiscount = $totalPrice;
if (intval($arResult['PROPERTIES']['STOCK']['VALUE']) > 0) {
    $stock = $totalPrice / 100 * intval($arResult['PROPERTIES']['STOCK']['VALUE']);
    $priceDiscount -= $stock;
}

$arRequisits = [];
$entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
$iterator = $entity::getByPrimary($arResult['PROPERTIES']['REQUISITE']['VALUE'], [
    'select' => [
        'ID', 'NAME',
        'PROPERTY_LAW_ADDRESS_' => 'LAW_ADDRESS',
        'PROPERTY_UNP_' => 'UNP',
        'PROPERTY_KPP_' => 'KPP',
        'PROPERTY_PAYMENT_ACCOUNT_' => 'PAYMENT_ACCOUNT',
        'PROPERTY_BANK_NAME_' => 'BANK_NAME',
        'PROPERTY_CASH_ACCOUNT_' => 'CASH_ACCOUNT',
        'PROPERTY_BIC_' => 'BIC'
    ],
]);
if($arItem = $iterator->fetch()){
    $arRequisits = [
        'NAME' => $arItem['NAME'],
        'LAW_ADDRESS' => $arItem['PROPERTY_LAW_ADDRESS_VALUE'],
        'INN' => $arItem['PROPERTY_UNP_VALUE'],
        'KPP' => $arItem['PROPERTY_KPP_VALUE'],
        'PC' => $arItem['PROPERTY_PAYMENT_ACCOUNT_VALUE'],
        'BANK_NAME' => $arItem['PROPERTY_BANK_NAME_VALUE'],
        'CA' => $arItem['PROPERTY_CASH_ACCOUNT_VALUE'],
        'BIC' => $arItem['PROPERTY_BIC_VALUE'],
        'IMAGE' => getB2bProfileImage()
    ];
}


$arResult['REQUISITS'] = $arRequisits;
$arResult['WAT_VALUE'] = ($priceDiscount / 100) * 20;
$arResult['WAT'] = number_format(($priceDiscount / 100) * 20, 2, ",", " ");
//$arResult['STOCK'] = number_format($stock, 2, ",", " ");
$arResult['PRICE_FORMATED'] = number_format($totalPrice, 2, ",", " ");
$arResult['PRICE_DISCOUNT_VALUE'] = $priceDiscount;
$arResult['PRICE_DISCOUNT'] = number_format($priceDiscount, 2, ",", " ");
$arResult['ITEMS'] = $itemsResult;
$arResult['PICTURES'] = $arPictures;
