<?

namespace Ml\Soap\Wsdl\Action;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Iblock\ElementTable;
use Bitrix\Sale;
use Mpdf\Log\Context;
//use HLHelpers;

class Order
{
    private static $error = '';

    public static function Update(array $request)
    {
        global $USER;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'data' => $request], 1), FILE_APPEND);
        $arReq = [
            'site_id', 'number_1c'
        ];
        if (self::CheckRequired($arReq, $request)) {
            $order = Sale\Order::load(intval($request['site_id']));
            if (!empty($order)) {
                $messUser = 0;
                $statusProducts = [];
                $statusProductsTemp  = [];
                $order->setField('XML_ID', $request['number_1c']);

                $paySumm = floatval($request['pay']);
                $paymentCollection = $order->getPaymentCollection();
                if ($paySumm > 0) {
                    $onePayment = $paymentCollection[0];

                    $onePayment->setField('SUM', $paySumm);
                    if ($order->getPrice() == $paySumm) {
                        $onePayment->setPaid("Y");
                    } else if ($order->getPrice() < $paySumm) {
                        self::$error = 'Сумма оплаты больше суммы товаров ' . $onePayment->getSum() . ' ' . $paymentCollection->getPaidSum();
                        return [
                            'errorMsg' => self::$error
                        ];
                    }
                }

                $items = $arProductIDs = $basketItems = [];
                if (isset($request['items_list']['unit'][0])) {
                    foreach ($request['items_list']['unit'] as $arItem) {
                        $items[$arItem['guid']] = $arItem['count'];
                        $arProductIDs[] = $arItem['guid'];
                        if(isset($arItem['status'])) {
                            $statusProductsTemp[$arItem['guid']] = $arItem['status'];
                        }

                    }
                } else {
                    $items[$request['items_list']['unit']['guid']] = $request['items_list']['count'];
                    $arProductIDs[] = $request['items_list']['unit']['guid'];
                    if(isset($request['items_list']['unit']['status'])) {
                        $statusProductsTemp[$request['items_list']['unit']['guid']] = $request['items_list']['unit']['status'];
                    }
                }
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'data' => $statusProductsTemp], 1), FILE_APPEND);
                $basket = $order->getBasket();
                foreach ($basket as $basketItem) {
                    $basketItems[$basketItem->getProductId()] = [
                        'BASKET_ID' => $basketItem->getId(),
                        'COUNT' => $basketItem->getQuantity(),
                    ];
                }

                $iterator = ElementTable::getList([
                    'select' => ['ID', 'XML_ID', 'NAME'],
                    'filter' => ['XML_ID' => $arProductIDs]
                ]);

                $orderProps = $order->getPropertyCollection();
                $prop = $orderProps->getItemByOrderPropertyCode('STATUS_PRODUCTS');
                $old_status = unserialize(str_replace("'",'', $prop->getValue()));

                while ($arItem = $iterator->fetch()) {
                    if (isset($basketItems[$arItem['ID']])) {
                        if ($items[$arItem['XML_ID']] != $basketItems[$arItem['ID']]['COUNT']) {
                            $basketItem = $basket->getItemById($basketItems[$arItem['ID']]['BASKET_ID']);
                            $basketItem->setFields([
                                'QUANTITY' => $basketItems[$arItem['ID']]['COUNT']
                            ]);
                        }
                    } else {
                        $item = $basket->createItem('catalog', $arItem['ID']);

                        $item->setFields([
                            'QUANTITY' => $items[$arItem['XML_ID']],
                            'CURRENCY' => CurrencyManager::getBaseCurrency(),
                            'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                            'NAME' => $arItem['NAME'],
                            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                        ]);
                    }
                    if(isset($statusProductsTemp[$arItem['XML_ID']])) {
                        $statusProducts[$arItem['ID']] = $statusProductsTemp[$arItem['XML_ID']];
                        if(stripos($old_status[$arItem['ID']] , 'К обеспечению') !== false && stripos($statusProducts[$arItem['ID']], 'Резервировать на складе') !== false){
                            $messUser = $request['number_1c'];
                        }
                        if(stripos($statusProducts[$arItem['ID']], 'К обеспечению') !== false && stripos($old_status[$arItem['ID']] , 'Резервировать на складе') !== false){
                            $messUser = $request['number_1c'];
                        }
                    }
                    unset($items[$arItem['XML_ID']]);
                }

                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'data' => $statusProducts], 1), FILE_APPEND);
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'data' => $messUser], 1), FILE_APPEND);

                foreach ($orderProps->getArray()['properties'] as $arProp) {
                    switch ($arProp['CODE']) {
                        case 'NUMBER_1C':
                            $prop = $orderProps->getItemByOrderPropertyId($arProp['ID']);
                            $prop->setValue($request['number_1c']);
                            break;
                        case 'COMMENT':
                            $prop = $orderProps->getItemByOrderPropertyId($arProp['ID']);
                            $prop->setValue($request['comment']);
                            break;
                        case 'STATUS':
                            $prop = $orderProps->getItemByOrderPropertyId($arProp['ID']);
                            $prop->setValue($request['status']);
                            break;
                        case 'STATUS_PRODUCTS':
                            $prop = $orderProps->getItemByOrderPropertyId($arProp['ID']);
                            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'data' => "'".serialize($statusProducts)."'"], 1), FILE_APPEND);
                            $prop->setValue("'".serialize($statusProducts)."'");
                            break;
                    }
                }


                $rsUsers = \CUser::GetList(($by2="personal_country"), ($order2="desc"), ["XML_ID" => $request['client_guid']]);
                if($arUser = $rsUsers->Fetch()){
                    $userId = $arUser['ID'];
                }
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'user_id' => $userId], 1), FILE_APPEND);
                if(!empty($messUser)) {

                    $arFieldsHl = [
                        'UF_NUMBER_1C' => $messUser,
                        'UF_ORDER_ID' => $request['site_id'],
                        'UF_USER_ID' => $userId,
                    ];

                    $id = \HLHelpers::getInstance()->addElement(8, $arFieldsHl);
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/order_1c.log', print_r(['time' => date('d.m.Y'), 'id_ig_hl' => $id], 1), FILE_APPEND);
                }

                $order->save();
            } else {
                self::$error = 'Заказ с таким id не найден';
            }
        }

        return [
            'errorMsg' => self::$error
        ];
    }


    private static function CheckRequired(array $fields, array $request)
    {
        $isSuccess = true;
        foreach ($fields as $arField) {
            if (empty($request[$arField])) {
                self::$error .= 'Не заполнено обязательное поле ' . $arField;
                $isSuccess = false;
            }
        }
        return $isSuccess;
    }
}