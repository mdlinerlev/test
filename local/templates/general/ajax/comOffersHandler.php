<?

use Bitrix\Main\Context,
    Bitrix\Sale,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

$result = [
    'success' => false,
    'needReload' => true,

    'title' => '',
    'success_message' => '',
    'errorMsg' => '',
];

$userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();

if ($userId > 0) {
    $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BKP)->getEntityDataClass();

    switch ($request['type']) {
        case 'fix':
            $iterator = $entity::getList([
                'select' => ['ID', 'PROPERTY_USER_' => 'USER', 'NAME', 'SORT'],
                'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
            ]);

            while ($arItem = $iterator->fetch()) {
                $el = new CIBlockElement();
                if ($arItem['SORT'] != 600) {
                    $el->Update($arItem['ID'], ['SORT' => 600]);
                    $result['success_message'] .= '<p>Элемент <b>"' . $arItem['NAME'] . '"</b> успешно закреплен</p>';
                } else {
                    $el->Update($arItem['ID'], ['SORT' => 500]);
                    $result['success_message'] .= '<p>Элемент <b>"' . $arItem['NAME'] . '"</b> успешно откреплен</p>';
                }
            }
            break;
        case 'edit':
            $iterator = $entity::getList([
                'select' => [
                    'ID',
                    'NAME',
                    'PROPERTY_USER_' => 'USER',
                    'PROPERTY_PRODUCTS_' => 'PRODUCTS',
                ],
                'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
            ]);
            while ($arItem = $iterator->fetch()) {
                if (!empty($request['items'][$arItem['ID']])) {
                    $requestData = $request['items'][$arItem['ID']];
                    unset($requestData['ID']);

                    $basketItems = [];
                    foreach ($requestData as $key => $arProp) {
                        if (strpos($key, 'BASKET_ITEM') !== false) {
                            $id = str_replace('BASKET_ITEM_', '', $key);
                            $basketItems[] = [
                                'id' => $id,
                                'count' => $arProp,
                            ];
                            unset($requestData[$key]);
                        }
                    }

                    if(!empty($requestData['STOCK']) && $requestData['STOCK'] > 0){
                        $productsIds = $productCount = [];
                        if ($basketItems) {
                            foreach ($basketItems as $basketItem){
                                $productsIds[] = $basketItem['id'];
                                $productCount[$basketItem['id']] = $basketItem['count'];
                            }
                        } else {
                            $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);
                            foreach ($products as $basketItem){
                                $productsIds[] = $basketItem['id'];
                                $productCount[$basketItem['id']] = $basketItem['count'];
                            }
                        }

                        $sum = 0;
                        $price = \Bitrix\Catalog\PriceTable::getList([
                            'select' => ['PRICE', 'PRODUCT_ID'],
                            'filter' => ['PRODUCT_ID' => $productsIds, 'CATALOG_GROUP_ID' => PRICE_TYPE_DEFAULT_ID]
                        ]);
                        while ($arPrice = $price->fetch()){
                            $sum += $arPrice['PRICE'] * $productCount[$arPrice['PRODUCT_ID']];
                        }
                        
                        $filter = [
                            'order' => ['UF_PRIORITY' => 'ASC'],
                            'select' => ['UF_VALUE_BEFORE', 'UF_VALUE_AFTER'],
                            'filter' => ['<=UF_BEFORE' => $sum, '>=UF_AFTER' => $sum],
                            'limit' => 1
                        ];
                        $stockData = getHightloadData(HLBLOCK_ID_B2BSTOCK, $filter);
                        if(!empty($stockData)){
                            $minStock = $stockData[array_key_first($stockData)];

                            if ($minStock['UF_VALUE_BEFORE'] > $requestData['STOCK']) {
                                $result['errorMsg'] = 'Минимальный размер скидки ' . $minStock['UF_VALUE_BEFORE'].'%';
                            }
                            if ($minStock['UF_VALUE_AFTER'] < $requestData['STOCK']) {
                                $result['errorMsg'] = 'Максимальный размер скидки ' . $minStock['UF_VALUE_AFTER'].'%';
                            }
                        } else {
                            $result['errorMsg'] = 'Максимальный размер скидки 0%';
                        }
                    }
                    file_put_contents(__DIR__.'/testProd.txt', print_r($basketItems, 1), FILE_APPEND);

                    $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);
                    file_put_contents(__DIR__.'/testProd.txt', print_r($products, 1), FILE_APPEND);
                    foreach ($basketItems as &$item) {
                        $key = array_search($id, array_column($products, 'id'));
                        $arr = [
                            'width' => $products[$key]['width'],
                            'height' => $products[$key]['height'],
                            'color' => $products[$key]['color'],
                        ];
                        $item = array_merge($item, $arr);

                    }
                    if (!empty($basketItems)) {
                        $requestData['PRODUCTS'] = serialize($basketItems);
                    }

                    if(empty($result['errorMsg'])){
                        CIBlockElement::SetPropertyValuesEx($arItem['ID'], IBLOCK_ID_B2BKP, $requestData);
                        $el = new CIBlockElement();
                        $el->Update($arItem['ID'], ['NAME' => $arItem['NAME']]);
                        $result['success_message'] .= '<p>Элемент <b>"' . $arItem['NAME'] . '"</b> успешно обновлен</p>';
                    }else{
                        $result['needReload'] = true;
                    }
                } else {
                    $result['errorMsg'] .= 'Что-то не так';
                }
            }
            $result['needReload'] = false;
            break;
        case 'print':
            $data = [];
            $iterator = $entity::getList([
                'select' => ['ID', 'NAME', 'PROPERTY_USER_' => 'USER'],
                'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
            ]);
            while ($arItem = $iterator->fetch()) {
                $data[] = '/ajax/reAjax.php?action=pdfGenenarator&ID=' . $arItem['ID'];
            }
            $result['returnUrl'] = $data;
            $result['needReload'] = false;
            break;
        case 'create_order':
            $userPrice = getUserPrice();
            if ($userPrice != PRICE_TYPE_DEFAULT_ID) {

                $siteId = Context::getCurrent()->getSite();
                $currencyCode = CurrencyManager::getBaseCurrency();

                $arOrders = [];
                $arLogMessages = [];

                $iterator = $entity::getList([
                    'select' => [
                        'ID', 'NAME',
                        'PROPERTY_USER_' => 'USER',
                        'PROPERTY_PRODUCTS_' => 'PRODUCTS',
                        'PROPERTY_STOCK_' => 'STOCK',
                        'PROPERTY_COMMENT_' => 'COMMENT',
                    ],
                    'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
                ]);
                $arProductIDs = [];
                while ($arItem = $iterator->fetch()) {
                    $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);


                    foreach ($products as $arProduct) {
                        $arProductIDs[$arProduct['id']] = $arProduct['id'];
                    }
                    $arItem['PROPERTY_COMMENT_VALUE'] = unserialize($arItem['PROPERTY_COMMENT_VALUE']);
                    $arOrders[$arItem['ID']] = [
                        'ID' => $arItem['ID'],
                        'NAME' => $arItem['NAME'],
                        'USER' => $userId,
                        'STOCK' => $arItem['PROPERTY_STOCK_VALUE'],
                        'PRODUCTS' => $products
                    ];
                    if(!empty($request['comment'][$arItem['ID']])) {
                        $arOrders[$arItem['ID']]['COMMENT'] = $request['comment'][$arItem['ID']];
                    } else {
                        $arOrders[$arItem['ID']]['COMMENT'] = $arItem['PROPERTY_COMMENT_VALUE']['TEXT'];
                    }

                }

                $priceIds = [$userPrice, PRICE_TYPE_DEFAULT_ID];

                $arProductPrices = [];
                $iterator = \Bitrix\Catalog\PriceTable::getList([
                    'select' => ['ID', 'PRICE', 'PRODUCT_ID', 'CATALOG_GROUP_ID'],
                    'filter' => ['PRODUCT_ID' => $arProductIDs, 'CATALOG_GROUP_ID' => $priceIds]
                ]);
                while ($arPrice = $iterator->fetch()) {
                    $arProductPrices[$arPrice['PRODUCT_ID']][$arPrice['CATALOG_GROUP_ID']] = $arPrice['PRICE'];
                }

                $arProductData = [];
                $iterator = \Bitrix\Iblock\ElementTable::getList([
                    'select' => ['ID', 'NAME'],
                    'filter' => ['ID' => $arProductIDs]
                ]);
                while ($arItem = $iterator->fetch()) {
                    $arProductData[$arItem['ID']] = $arItem;
                }

                foreach ($arOrders as $arOrder) {
                    if (empty($arOrder['PRODUCTS'])) {
                        $result['errorMsg'] = '<p style="color: red">В "' . $arOrder['NAME'] . '" нет товаров!</p>';
                    } else {
                        $order = Order::create($siteId, $userId);

                        $order->setPersonTypeId(1);
                        $order->setField('CURRENCY', $currencyCode);
                        //$order->setField('STATUS_ID', 'BN'); // статус Новый

                        $productsToOrder = [];
                        //$comment = '';

                        foreach ($arOrder['PRODUCTS'] as $arProduct) {
                            file_put_contents(__DIR__.'/testProdKp.txt', print_r($arProduct, 1), 8);
                            file_put_contents(__DIR__.'/testProdKp.txt', print_r($arProductPrices, 1), 8);
                            if (!empty($arProductPrices[$arProduct['id']][$userPrice])) {
                                if($arProduct['procent'] > 0) {
                                    $productsToOrder[] = [
                                        'ID' => $arProduct['id'],
                                        'COUNT' => $arProduct['count'],
                                        'PRICE' => $arProductPrices[$arProduct['id']][PRICE_TYPE_DEFAULT_ID] + ($arProductPrices[$arProduct['id']][PRICE_TYPE_DEFAULT_ID] * $arProduct['procent'] / 100),
                                        'PRICE_PURCHASE' => $arProductPrices[$arProduct['id']][$userPrice] + ($arProductPrices[$arProduct['id']][$userPrice] * $arProduct['procent'] / 100)
                                    ];
                                } else {
                                    $productsToOrder[] = [
                                        'ID' => $arProduct['id'],
                                        'COUNT' => $arProduct['count'],
                                        'PRICE' => $arProductPrices[$arProduct['id']][PRICE_TYPE_DEFAULT_ID],
                                        'PRICE_PURCHASE' => $arProductPrices[$arProduct['id']][$userPrice]
                                    ];
                                }


                                if(($arProduct['width'] && $arProduct['height']) || $arProduct['color']) {
                                    /*$comment .= $arProductData[$arProduct['id']]['NAME'];
                                    if($arProduct['width'] && $arProduct['height']) {
                                        $comment .= ' ( Высота: '.$arProduct['width']. ', Ширина: '.$arProduct['height'].' )';
                                    }
                                    if($arProduct['color']) {
                                        $comment .= ' ( Цвет RAL: '.$arProduct['color'].' )';
                                    }
                                    $comment .= PHP_EOL;*/
                                }
                            } else {
                                $arLogMessages[$arOrder['NAME']][] = '<b>"' . $arProductData[$arProduct['id']]['NAME'] . '"</b> данный товар не добавлен в заказ. Причина: Отсутствие у товара оптовой цены. Обратитесь к своему менеджеру.';
                            }
                        }

                        if (!empty($productsToOrder)) {
                            /*basket*/
                            $basket = Basket::create($siteId);
                            foreach ($productsToOrder as $arProduct) {
                                $item = $basket->createItem('catalog', $arProduct['ID']);
                                $item->setFields([
                                    'QUANTITY' => $arProduct['COUNT'],
                                    'CURRENCY' => $currencyCode,
                                    'LID' => $siteId,
                                    'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
                                    'PRICE' => $arProduct['PRICE_PURCHASE'],
                                    'CUSTOM_PRICE' => 'Y'
                                ]);
                            }
                            $order->setBasket($basket);

                            /*shipment*/
                            $shipmentCollection = $order->getShipmentCollection();
                            $shipment = $shipmentCollection->createItem();
                            $service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
                            $shipment->setFields([
                                'DELIVERY_ID' => $service['ID'],
                                'DELIVERY_NAME' => $service['NAME'],
                            ]);

                            /*payment*/
                            $paymentCollection = $order->getPaymentCollection();
                            $payment = $paymentCollection->createItem();
                            $paySystemService = PaySystem\Manager::getObjectById(1);
                            $payment->setFields([
                                'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
                                'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
                            ]);

                            /*properties*/
                            $propertyCollection = $order->getPropertyCollection();
                            foreach ($propertyCollection->getArray()['properties'] as $arProp) {
                                switch ($arProp['CODE']) {
                                    case 'KP_ID':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue($arOrder['ID']);
                                        break;
                                    case 'STOCK':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue($arOrder['STOCK']);
                                        break;
                                    case 'STATUS':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue('Новый');
                                        break;
                                    case 'COMMENT':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue(/*$comment*/$arOrder['COMMENT']);
                                        break;
                                }
                            }

                            $phoneProp = $propertyCollection->getPhone();
                            $phoneProp->setValue($phone);
                            $nameProp = $propertyCollection->getPayerName();
                            $nameProp->setValue($name);

                            /*save*/
                            $order->doFinalAction(true);
                            $order_result = $order->save();

                        } else {
                            $result['errorMsg'] = '<p style="color: red">В "' . $arOrder['NAME'] . '" нет товаров!</p>';
                        }
                    }
                }
            } else {
                $result['errorMsg'] = '<p style="color: red">Не выбран прайс. Обратитесь к Вашему менеджеру.</p>';
                $result['title'] = 'Заказ не создан';
            }

            foreach ($arLogMessages as $key => $arLogs) {
                $result['errorMsg'] .= '<p>' . $key . ':</p>';
                $result['errorMsg'] .= '<ul>';
                foreach ($arLogs as $arLog) {
                    $result['errorMsg'] .= '<li> - ' . $arLog . '</li>';
                }
                $result['errorMsg'] .= '</ul>';
            }

            if (!empty($arLogMessages)) {
                $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
                $req = $entity::getList([
                    'select' => ['ID', 'NAME',
                        'PROPERTY_USER_' => 'USER',
                        'PROPERTY_IS_MAIN_' => 'IS_MAIN',
                        'PROPERTY_MANAGER_EMAIL_' => 'MANAGER_EMAIL',
                        'PROPERTY_MANAGER_PHONE_' => 'MANAGER_PHONE',
                        'PROPERTY_MANAGER_' => 'MANAGER'
                    ],
                    'filter' => ['PROPERTY_USER_VALUE' => $userId, 'PROPERTY_IS_MAIN_VALUE' => 1]
                ]);
                if ($arReq = $req->fetch()) {

                    $result['errorMsg'] .= '<p>Контакты менеджера:</p>';
                    $result['errorMsg'] .= '<ul>';
                    $result['errorMsg'] .= '<li><b>ФИО: </b>' . $arReq['PROPERTY_MANAGER_VALUE'] . '</li>';
                    $result['errorMsg'] .= '<li><b>Email: </b> <a href="mailto::' . $arReq['PROPERTY_MANAGER_EMAIL_VALUE'] . '">' . $arReq['PROPERTY_MANAGER_EMAIL_VALUE'] . '</a></li>';
                    $result['errorMsg'] .= '<li><b>Телефон: </b> <a href="tel::+' . NormalizePhone($arReq['PROPERTY_MANAGER_PHONE_VALUE']) . '">' . $arReq['PROPERTY_MANAGER_PHONE_VALUE'] . '</a></li>';
                    $result['errorMsg'] .= '</ul>';
                }

                $result['title'] = 'Заказ создан с ошибками';
            } elseif (empty($result['errorMsg'])) {
                $result['title'] = 'Заказ успешно отправлен';
            }

            break;
        case 'reserve_order':
            $userPrice = getUserPrice();
            if ($userPrice != PRICE_TYPE_DEFAULT_ID) {

                $siteId = Context::getCurrent()->getSite();
                $currencyCode = CurrencyManager::getBaseCurrency();

                $arOrders = [];
                $arLogMessages = [];

                $iterator = $entity::getList([
                    'select' => [
                        'ID', 'NAME',
                        'PROPERTY_USER_' => 'USER',
                        'PROPERTY_PRODUCTS_' => 'PRODUCTS',
                        'PROPERTY_STOCK_' => 'STOCK',
                        'PROPERTY_COMMENT_' => 'COMMENT',
                    ],
                    'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
                ]);
                $arProductIDs = [];
                while ($arItem = $iterator->fetch()) {
                    $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);
                    file_put_contents(__DIR__.'/testProd.txt', print_r($arItem, 1), 8);

                    foreach ($products as $arProduct) {
                        $arProductIDs[$arProduct['id']] = $arProduct['id'];
                    }
                    $arItem['PROPERTY_COMMENT_VALUE'] = unserialize($arItem['PROPERTY_COMMENT_VALUE']);
                    $arOrders[$arItem['ID']] = [
                        'ID' => $arItem['ID'],
                        'NAME' => $arItem['NAME'],
                        'USER' => $userId,
                        'STOCK' => $arItem['PROPERTY_STOCK_VALUE'],
                        'PRODUCTS' => $products,
                    ];
                    if(!empty($request['comment'][$arItem['ID']])) {
                        $arOrders[$arItem['ID']]['COMMENT'] = $request['comment'][$arItem['ID']];
                    } else {
                        $arOrders[$arItem['ID']]['COMMENT'] = $arItem['PROPERTY_COMMENT_VALUE']['TEXT'];
                    }
                }
                 file_put_contents(__DIR__.'/testProd.txt', print_r($arOrders, 1), 8);
                $priceIds = [$userPrice, PRICE_TYPE_DEFAULT_ID];

                $arProductPrices = [];
                $iterator = \Bitrix\Catalog\PriceTable::getList([
                    'select' => ['ID', 'PRICE', 'PRODUCT_ID', 'CATALOG_GROUP_ID'],
                    'filter' => ['PRODUCT_ID' => $arProductIDs, 'CATALOG_GROUP_ID' => $priceIds]
                ]);
                while ($arPrice = $iterator->fetch()) {
                    $arProductPrices[$arPrice['PRODUCT_ID']][$arPrice['CATALOG_GROUP_ID']] = $arPrice['PRICE'];
                }

                $arProductData = [];
                $iterator = \Bitrix\Iblock\ElementTable::getList([
                    'select' => ['ID', 'NAME'],
                    'filter' => ['ID' => $arProductIDs]
                ]);
                while ($arItem = $iterator->fetch()) {
                    $arProductData[$arItem['ID']] = $arItem;
                }

                foreach ($arOrders as $arOrder) {
                    if (empty($arOrder['PRODUCTS'])) {
                        $result['errorMsg'] = '<p style="color: red">В "' . $arOrder['NAME'] . '" нет товаров!</p>';
                    } else {
                        $order = Order::create($siteId, $userId);

                        $order->setPersonTypeId(1);
                        $order->setField('CURRENCY', $currencyCode);
                        //$order->setField('STATUS_ID', 'BN'); // статус Новый

                        $productsToOrder = [];
                        //$comment = '';

                        foreach ($arOrder['PRODUCTS'] as $arProduct) {
                            if (!empty($arProductPrices[$arProduct['id']][$userPrice])) {
                                $productsToOrder[] = [
                                    'ID' => $arProduct['id'],
                                    'COUNT' => $arProduct['count'],
                                    'PRICE' => $arProductPrices[$arProduct['id']][PRICE_TYPE_DEFAULT_ID],
                                    'PRICE_PURCHASE' => $arProductPrices[$arProduct['id']][$userPrice]
                                ];

                                /*if(($arProduct['width'] && $arProduct['height']) || $arProduct['color']) {
                                    $comment .= $arProductData[$arProduct['id']]['NAME'];
                                    if($arProduct['width'] && $arProduct['height']) {
                                        $comment .= ' ( Высота: '.$arProduct['width']. ', Ширина: '.$arProduct['height'].' )';
                                    }
                                    if($arProduct['color']) {
                                        $comment .= ' ( Цвет RAL: '.$arProduct['color'].' )';
                                    }
                                    $comment .= PHP_EOL;
                                }*/
                            } else {
                                $arLogMessages[$arOrder['NAME']][] = '<b>"' . $arProductData[$arProduct['id']]['NAME'] . '"</b> данный товар не добавлен в заказ. Причина: Отсутствие у товара оптовой цены. Обратитесь к своему менеджеру.';
                            }
                        }

                        //$arOrder['COMMENT'] .= PHP_EOL.'Зарезервировать'.PHP_EOL;

                        if (!empty($productsToOrder)) {
                            /*basket*/
                            $product_status = [];
                            $basket = Basket::create($siteId);
                            foreach ($productsToOrder as $arProduct) {
                                $product_status[$arProduct['ID']] = 'Запрос резерва';
                                $item = $basket->createItem('catalog', $arProduct['ID']);
                                $item->setFields([
                                    'QUANTITY' => $arProduct['COUNT'],
                                    'CURRENCY' => $currencyCode,
                                    'LID' => $siteId,
                                    'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
                                    'PRICE' => $arProduct['PRICE_PURCHASE'],
                                    'CUSTOM_PRICE' => 'Y'
                                ]);
                            }
                            $order->setBasket($basket);

                            /*shipment*/
                            $shipmentCollection = $order->getShipmentCollection();
                            $shipment = $shipmentCollection->createItem();
                            $service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
                            $shipment->setFields([
                                'DELIVERY_ID' => $service['ID'],
                                'DELIVERY_NAME' => $service['NAME'],
                            ]);

                            /*payment*/
                            $paymentCollection = $order->getPaymentCollection();
                            $payment = $paymentCollection->createItem();
                            $paySystemService = PaySystem\Manager::getObjectById(1);
                            $payment->setFields([
                                'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
                                'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
                            ]);

                            /*properties*/
                            $propertyCollection = $order->getPropertyCollection();
                            foreach ($propertyCollection->getArray()['properties'] as $arProp) {
                                switch ($arProp['CODE']) {
                                    case 'KP_ID':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue($arOrder['ID']);
                                        break;
                                    case 'STOCK':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue($arOrder['STOCK']);
                                        break;
                                    case 'STATUS':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue('Новый резерв');
                                        break;
                                    case 'COMMENT':
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue($arOrder['COMMENT']);
                                        break;
                                    case 'STATUS_PRODUCTS':
                                        file_put_contents(__DIR__.'/testSoap123.txt', print_r(serialize($product_status), 1), FILE_APPEND);
                                        $prop = $propertyCollection->getItemByOrderPropertyId($arProp['ID']);
                                        $prop->setValue("'".serialize($product_status)."'");
                                        break;
                                }
                            }

                            $phoneProp = $propertyCollection->getPhone();
                            $phoneProp->setValue($phone);
                            $nameProp = $propertyCollection->getPayerName();
                            $nameProp->setValue($name);

                            /*save*/
                            $order->doFinalAction(true);
                            $order_result = $order->save();

                        } else {
                            $result['errorMsg'] = '<p style="color: red">В "' . $arOrder['NAME'] . '" нет товаров!</p>';
                        }
                    }
                }
            } else {
                $result['errorMsg'] = '<p style="color: red">Не выбран прайс. Обратитесь к Вашему менеджеру.</p>';
                $result['title'] = 'Заказ не создан';
            }

            foreach ($arLogMessages as $key => $arLogs) {
                $result['errorMsg'] .= '<p>' . $key . ':</p>';
                $result['errorMsg'] .= '<ul>';
                foreach ($arLogs as $arLog) {
                    $result['errorMsg'] .= '<li> - ' . $arLog . '</li>';
                }
                $result['errorMsg'] .= '</ul>';
            }

            if (!empty($arLogMessages)) {
                $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
                $req = $entity::getList([
                    'select' => ['ID', 'NAME',
                        'PROPERTY_USER_' => 'USER',
                        'PROPERTY_IS_MAIN_' => 'IS_MAIN',
                        'PROPERTY_MANAGER_EMAIL_' => 'MANAGER_EMAIL',
                        'PROPERTY_MANAGER_PHONE_' => 'MANAGER_PHONE',
                        'PROPERTY_MANAGER_' => 'MANAGER'
                    ],
                    'filter' => ['PROPERTY_USER_VALUE' => $userId, 'PROPERTY_IS_MAIN_VALUE' => 1]
                ]);
                if ($arReq = $req->fetch()) {

                    $result['errorMsg'] .= '<p>Контакты менеджера:</p>';
                    $result['errorMsg'] .= '<ul>';
                    $result['errorMsg'] .= '<li><b>ФИО: </b>' . $arReq['PROPERTY_MANAGER_VALUE'] . '</li>';
                    $result['errorMsg'] .= '<li><b>Email: </b> <a href="mailto::' . $arReq['PROPERTY_MANAGER_EMAIL_VALUE'] . '">' . $arReq['PROPERTY_MANAGER_EMAIL_VALUE'] . '</a></li>';
                    $result['errorMsg'] .= '<li><b>Телефон: </b> <a href="tel::+' . NormalizePhone($arReq['PROPERTY_MANAGER_PHONE_VALUE']) . '">' . $arReq['PROPERTY_MANAGER_PHONE_VALUE'] . '</a></li>';
                    $result['errorMsg'] .= '</ul>';
                }

                $result['title'] = 'Заказ создан с ошибками';
            } elseif (empty($result['errorMsg'])) {
                $result['title'] = 'Заказ успешно отправлен';
            }

            break;
        case 'copy':
            $arSelect = ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_*'];
            $arFilter = ['IBLOCK_ID' => IBLOCK_ID_B2BKP, '=PROPERTY_USER' => $userId, 'ID' => $request['itemsId']];
            $iterator = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
            while ($arItem = $iterator->GetNextElement()) {
                $arFields = $arItem->GetFields();
                $arProperties = $arItem->GetProperties();

                $arLoad = [
                    'NAME' => $arFields['NAME'],
                    'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                    'ACTIVE' => 'Y',
                ];
                foreach ($arProperties as $key => $arProp) {
                    $value = $arProp['~VALUE'];
                    if ($arProp['PROPERTY_TYPE'] == 'L') {
                        $value = $arProp['VALUE_ENUM_ID'];
                    }
                    $arLoad['PROPERTY_VALUES'][$arProp['CODE']] = $value;
                }

                $el = new CIBlockElement();
                if (!$itemId = $el->Add($arLoad)) {
                    $result['errorMsg'] = $el->LAST_ERROR;
                } else {
                    $result['success_message'] .= '<p>Элемент <b>"' . $arFields['NAME'] . '"</b> успешно скопирован</p>';
                }
            }
            break;
        case 'del':
            global $DB;
            $iterator = $entity::getList([
                'select' => ['ID', 'PROPERTY_USER_' => 'USER', 'NAME'],
                'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
            ]);
            while ($arItem = $iterator->fetch()) {
                $DB->StartTransaction();
                if (!CIBlockElement::Delete($arItem['ID'])) {
                    $DB->Rollback();
                    $result['errorMsg'] = 'Ошибка удаления элемента';
                    break;
                } else {
                    $result['success_message'] .= '<p>Элемент <b>"' . $arItem['NAME'] . '"</b> успешно удален</p>';
                    $DB->Commit();
                }
            }
            break;
        case 'validate': // используется в корзине
            $_SESSION['COM_OFFER'] = $_POST;
            break;
        case 'create': // используется в корзине
            $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
            $kpBasket = [];
            foreach ($basket as $basketItem) {$arHlElements = HLHelpers::getInstance()->getElementList(6, ['UF_ID' => $basketItem->getID(), 'UF_PRODUCT_ID' => $basketItem->getProductId()]);

                $kpBasket[] = [
                    'id' => $basketItem->getProductId(),
                    'count' => $basketItem->getQuantity(),
                    'width' => $arHlElements[0]['UF_WIDTH'],
                    'height' => $arHlElements[0]['UF_HEIGHT'],
                    'color' => $arHlElements[0]['UF_COLOR'],
                    'procent' => $arHlElements[0]['UF_PROCENT'],

                ];
            }

            $arLoad = [
                'NAME' => 'Коммерческое предложение ' . $request['NUMBER'] . ' от ' . date("d.m.Y"),
                'IBLOCK_ID' => IBLOCK_ID_B2BKP,
                'ACTIVE' => 'Y'
            ];

            $arProperties = $_SESSION['COM_OFFER']['PROPERTIES'];
            $arProperties['USER'] = $userId;
            $arProperties['PRODUCTS'] = serialize($kpBasket);
            $arProperties['STOCK'] = (intval($_SESSION['B2B_STOCK_PERCENT']) > 0) ? intval($_SESSION['B2B_STOCK_PERCENT']) : 0;
            $arProperties['DATE'] = date("d.m.Y");
            $arProperties['COMMENT'] = Array("VALUE" => Array ("TEXT" => $request['comment'], "TYPE" => "text"));
            $arProperties = array_merge($arProperties, $_POST['PROPERTIES']);
            $arLoad['PROPERTY_VALUES'] = $arProperties;

            $el = new CIBlockElement();
            if (!$itemId = $el->Add($arLoad)) {
                $result['errorMsg'] = $el->LAST_ERROR;
            } else {
                CSaleBasket::DeleteAll(Sale\Fuser::getId());
                $result['returnUrl'][] = '/ajax/reAjax.php?action=pdfGenenarator&ID=' . $itemId;
            }
            break;
        case 'del_basket-item':
            $iterator = $entity::getList([
                'select' => ['ID', 'NAME', 'PROPERTY_USER_' => 'USER', 'PROPERTY_PRODUCTS_' => 'PRODUCTS'],
                'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
            ]);
            if ($arItem = $iterator->fetch()) {
                $products = unserialize($arItem['PROPERTY_PRODUCTS_VALUE']);
                foreach ($products as $key => $arProduct) {
                    if ($arProduct['id'] == $request['delId']) {
                        unset($products[$key]);
                    }
                }

                CIBlockElement::SetPropertyValuesEx($arItem['ID'], IBLOCK_ID_B2BKP, ['PRODUCTS' => serialize($products)]);
            }
            break;
    }
} else {
    $result['errorMsg'] = 'Нет доступа';
}

if (empty($result['title'])) {
    if ($result['errorMsg']) {
        $result['title'] = 'Ошибка';
    } else {
        $result['title'] = 'Успешная операция';
    }
}

$result['success'] = (empty($result['errorMsg']) ? true : false);
echo \Bitrix\Main\Web\Json::encode($result);