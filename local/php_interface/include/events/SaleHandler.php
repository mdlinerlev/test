<?

use Bitrix\Sale;
use Bitrix\Main;

class SaleHandler
{
    public static $isUpdate = false;

    static function OnAfterOrderSave(Main\Event $event)
    {
        $order = $event->getParameter("ENTITY");
        $isNew = $event->getParameter("IS_NEW");

        $propertyCollection = $order->getPropertyCollection();

        if(!self::$isUpdate){
            $propNumberAndKpId = 0;
            $propNumber = $propKp = '';
            foreach ($propertyCollection->getArray()['properties'] as $arProp) {
                switch ($arProp['CODE']) {
                    case 'NUMBER_1C_AND_KP_ID':
                        $propNumberAndKpId = $arProp['ID'];
                        break;
                    case 'NUMBER_1C':
                        $propNumber = $arProp['VALUE'][0];
                        break;
                    case 'KP_ID':
                        $propKp =  $arProp['VALUE'][0];
                        break;
                }
            }
            if($propNumberAndKpId > 0){
                $prop = $propertyCollection->getItemByOrderPropertyId($propNumberAndKpId);
                $prop->setValue($propNumber.','.$propKp);
            }

            self::$isUpdate = true;
            $order->save();
            self::$isUpdate = false;
        }

        if ($isNew && Main\Loader::includeModule('ml.soap')) {
            $result = \Ml\Soap\Sender\Order::Send($order);
            $sendStatus = (!empty($result['return'])) ? 'Y' : 'N';
            foreach ($propertyCollection->getArray()['properties'] as $arProp) {
                switch ($arProp['CODE']) {
                    case 'SEND_SUCCESS':
                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                        $prop->setValue($sendStatus);
                        break;
                }
            }
            $order->save();
        }
    }

    static function getOptimalPrice($intProductID, $quantity, $arUserGroups, $renewal, $arPrices, $siteID, $arDiscountCoupons){
        $iterator = \Bitrix\Catalog\PriceTable::getList([
            'filter' => ['CATALOG_GROUP.XML_ID' => 'BASE', 'PRODUCT_ID'=>$intProductID]
        ]);
        $arPrice = $iterator->fetch();

        return [
            'RESULT_PRICE' => [
                'PRICE_TYPE_ID' => $arPrice['CATALOG_GROUP_ID'],
                'BASE_PRICE' => $arPrice['PRICE'],
                'CURRENCY' => $arPrice['CURRENCY'],
                "DISCOUNT_PRICE" => $arPrice['PRICE'],
            ],
        ];
    }
}
