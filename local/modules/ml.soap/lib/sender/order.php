<?

namespace Ml\Soap\Sender;

use Bitrix\Catalog\PriceTable;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Application;
use Bitrix\Sale;

class Order extends Connect
{
    private static Sale\Order $order;

    public static function Send(Sale\Order $order)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        self::$order = $order;
        $arPropertiesValue = self::GetProperties(['COMMENT', 'KP_ID', 'STATUS', 'STOCK']);
        file_put_contents(__DIR__.'/testSoap.txt', print_r($arPropertiesValue, 1), FILE_APPEND);
        $arProducts = self::GetProducts($arPropertiesValue['STOCK']['VALUE'][0]);
        $userGuid = self::GetUserGuid();
        $userData = self::GetUserRequsits($order->getUserId());
        $userPrice = self::GetUserPrice();

        $loadList = [
            'site_id' => $order->getId(),
            'client_guid' => $userGuid,
            'order_date' => $order->getDateInsert()->toString(),
            'price' => $arProducts['sum'],
            'price_type' => $userPrice,
            'status' => $arPropertiesValue['STATUS']['VALUE'][0],
            'dogovor' => ($userData['PROPERTY_DOCUMENT_NUMBER_VALUE']) ?: '',
            'comment' => ($arPropertiesValue['COMMENT']['VALUE'][0]) ?: '',
            'items' => $arProducts['items']
        ];

        if ($request['dev'] != 'Y') {
            file_put_contents(__DIR__.'/testSoap.txt', print_r($loadList, 1), FILE_APPEND);
            //return false;
            return self::Exec('createOrder', $loadList);
        } else {
            pr($loadList);
        }
        return false;
    }

    private static function GetProperties(array $arNeedPropCode)
    {
        $orderProps = self::$order->getPropertyCollection();
        $arPropertiesValue = [];
        foreach ($orderProps->getArray()['properties'] as $arProp) {
            if (in_array($arProp['CODE'], $arNeedPropCode)) {
                $arPropertiesValue[$arProp['CODE']] = $arProp;
            }
        }

        return $arPropertiesValue;
    }

    private static function GetProducts($stock)
    {
        $productIds = $arProducts = $arBasketItems = [];
        $basket = self::$order->getBasket();
        foreach ($basket as $arBasketItem) {
            $productIds[] = $arBasketItem->getProductId();
            $arBasketItems[$arBasketItem->getProductId()] = [
                'COUNT' => $arBasketItem->getQuantity(),
            ];
        }

        $userPrice = getUserPrice();
        $arPriceIds = [
            PRICE_TYPE_DEFAULT_ID,
            $userPrice
        ];
        $iterator = PriceTable::getList([
            'select' => ['PRODUCT_ID', 'PRICE', 'CATALOG_GROUP_ID'],
            'filter' => ['CATALOG_GROUP_ID' => $arPriceIds, 'PRODUCT_ID' => $productIds]
        ]);
        while ($arPrice = $iterator->fetch()){
            $arBasketItems[$arPrice['PRODUCT_ID']]['PRICE'][$arPrice['CATALOG_GROUP_ID']] = $arPrice['PRICE'];
        }

        $allSum = 0;
        $iterator = \Bitrix\Iblock\ElementTable::getList([
            'select' => ['ID', 'XML_ID'],
            'filter' => ['ID' => $productIds]
        ]);
        while ($arItem = $iterator->fetch()) {
            $price = (isset($arBasketItems[$arItem['ID']]['PRICE'][$userPrice])) ? $arBasketItems[$arItem['ID']]['PRICE'][$userPrice] : $arBasketItems[$arItem['ID']]['PRICE'][PRICE_TYPE_DEFAULT_ID];
            $count = $arBasketItems[$arItem['ID']]['COUNT'];
            $sumPrice = $price*$count;

            $allSum += $sumPrice;

            $arProducts['unit'][] = [
                'guid' => $arItem['XML_ID'],
                'count' => $count,
                'price_wat' => $price,
                'summ_wat' => $sumPrice,
            ];
        }

        return ['items' => $arProducts, 'sum' => $allSum];
    }

    private static function GetUserGuid()
    {
        $userGuid = '';
        $userId = self::$order->getUserId();
        $user = \Bitrix\Main\UserTable::getByPrimary($userId, [
            'select' => ['XML_ID']
        ]);
        if ($arUser = $user->fetch()) {
            $userGuid = $arUser['XML_ID'];
        }

        return $userGuid;
    }

    private static function GetUserRequsits(int $userId)
    {
        $entity = Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
        $data = [];
        $item = $entity::getList([
            'select' => ['ID', 'PROPERTY_IS_MAIN_' => 'IS_MAIN', 'PROPERTY_DOCUMENT_NUMBER_' => 'DOCUMENT_NUMBER', 'PROPERTY_USER_' => 'USER'],
            'filter' => ['PROPERTY_IS_MAIN_VALUE' => 1, 'PROPERTY_USER_VALUE' => $userId]
        ]);
        if ($arItem = $item->fetch()) {
            $data = $arItem;
        }

        return $data;
    }

    private static function GetUserPrice(){
        $userId = self::$order->getUserId();

        $priceName = '';
        $price = getUserPrice($userId);
        $iterator = \Bitrix\Catalog\GroupLangTable::getList([
            'filter' => ['CATALOG_GROUP_ID' => $price, 'LANG' => 'ru']
        ]);
        if($arPrice = $iterator->fetch()){
            $priceName = $arPrice['NAME'];
        }
        return $priceName;
    }
}
