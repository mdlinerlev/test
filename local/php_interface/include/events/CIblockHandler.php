<?
class CIblockHandler
{
    private static $isUpdate = false;

    static function isNotSpam($arFields)
    {
        if ($arFields['IBLOCK_ID'] == IBLOCK_ID_COMMENTS && !empty($_REQUEST["NAME_USER_FALSE"])) {
            global $APPLICATION;
            $APPLICATION->throwException("spam error");
            return false;
        }
    }

    static function onAfterIblock(&$arFields)
    {
        if(self::$isUpdate == true){
            return;
        }
        if ($arFields['IBLOCK_ID'] == IBLOCK_ID_CATALOG) {
            $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_CATALOG)->getEntityDataClass();
            $iterator = $entity::getByPrimary($arFields['ID'], [
                'filter' => [
                    '!PROPERTY_GLASS_REF_VALUE' => false,
                ],
                'select' => [
                    'ID', 'NAME',
                    'PROPERTY_GLASS_REF_' => 'GLASS_REF',
                    'PROPERTY_GLASS_' => 'GLASS',
                    'PROPERTY_CONFIGURATION_' => 'CONFIGURATION',
                    'PROPERTY_PRODUCT_TYPE_' => 'PRODUCT_TYPE'
                ]
            ]);
            $arData = [];
            while ($arItem = $iterator->fetch()) {
                $arData['CODE'] = $arItem['PROPERTY_GLASS_REF_VALUE'];
                $arData['PROPERTY']['GLASS'] = $arItem['PROPERTY_GLASS_VALUE'];
                $arData['PROPERTY']['CONFIGURATION'] = $arItem['PROPERTY_CONFIGURATION_VALUE'];
                $type = $arItem['PROPERTY_PRODUCT_TYPE_VALUE'];
            }

            if (!empty($arData)) {
                $code = $arData['CODE'];
                if ($type != TYPE_DEKOR) {
                    if ($arData['PROPERTY']['GLASS'] == 1) {
                        $code .= '-osteklennoe';
                    } else {
                        $code .= '-polotno-glukhoe';
                    }
                }

                if(!empty($arData['PROPERTY']['CONFIGURATION'])){
                    $code .= '-'.$arData['PROPERTY']['CONFIGURATION'];
                }

                $code = CUtil::translit($code, 'ru', [
                    'replace_space' => '-',
                    'replace_other' => '-'
                ]);

                self::$isUpdate = true;
                $el = new CIBlockElement();
                $el->Update($arFields['ID'], ['CODE' => $code]);
                self::$isUpdate = false;
            }
        }
    }

    static function SetNumberKp($arFields)
    {
        if ($arFields['IBLOCK_ID'] == IBLOCK_ID_B2BKP) {

            $entity = \Bitrix\Iblock\Iblock::wakeUp($arFields['IBLOCK_ID'])->getEntityDataClass();
            $item = $entity::getByPrimary($arFields['ID'], [
                'select' => ['ID', 'PROPERTY_PRODUCTS_' => 'PRODUCTS']
            ]);
            if ($arItem = $item->fetch()) {
                $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);
                $arItemIds = [];
                $productsData = [];
                $sumPrice = 0;
                $sumPricePurchase = 0;
                foreach ($products as $arItem) {
                    $arItemIds[] = $arItem['id'];
                    $productsData[$arItem['id']] = $arItem['count'];
                }

                $userPrice = getUserPrice();
                $priceCode = [PRICE_TYPE_DEFAULT_ID, $userPrice];

                $iterator = \Bitrix\Catalog\PriceTable::getList([
                    'select' => ['PRICE', 'CATALOG_GROUP_ID', 'PRODUCT_ID'],
                    'filter' => ['PRODUCT_ID' => $arItemIds, 'CATALOG_GROUP_ID' => $priceCode]
                ]);
                $arPrices = [];
                while ($arItem = $iterator->fetch()) {
                    $arPrices[$arItem['PRODUCT_ID']][$arItem['CATALOG_GROUP_ID']] = $arItem['PRICE'];
                }

                foreach ($arPrices as $productID => $arPrice){
                    if(isset($arPrice[$userPrice])){
                        $sumPrice += $arPrice[PRICE_TYPE_DEFAULT_ID] * $productsData[$productID];
                        $sumPricePurchase += $arPrice[$userPrice] * $productsData[$productID];
                    }
                }

                CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], [
                    'NUMBER' => $arFields['ID'],
                    'SUM_W_STOCK' => $sumPrice,
                    'SUM_PURCHASE' => $sumPricePurchase
                ]);
            }
        }
    }
}