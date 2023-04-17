<?php
/**
 * User: sasha
 * Date: 27.07.18
 * Time: 17:42
 */
namespace Newsite\Sale;

class Order extends \Bitrix\Sale\Order
{

    /** @var  $orderStepCollection \Newsite\Sale\OrderStep\OrderStepCollection */
    protected $orderStepCollection;
    /** @var Delivery\Services\Base[] $arDeliveryServiceAll */
    protected $arDeliveryServiceAll = array();
    public $arUserResult;


    public function fillOrder()
    {
        $this->initLastOrderData();
        $this->initPersonType();
        $this->getPropertyCollection()->fillPropValue();
        $this->initDelivery();
        $this->initPayment();
    }


    public function initDelivery()
    {
        /* @var $shipmentCollection \Bitrix\Sale\ShipmentCollection */
        $shipmentCollection = $this->getShipmentCollection();
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $this->arDeliveryServiceAll =  $this->getAvailableDeliveries();
        $userDeliveryRequest = $request->get("DELIVERY_ID") ? : intval($this->arUserResult['DELIVERY_ID']);

        if ($userDeliveryRequest > 0 && array_key_exists($userDeliveryRequest, $this->arDeliveryServiceAll)) {
            $deliveryObj = $this->arDeliveryServiceAll[ $userDeliveryRequest ];
        } else {
            $deliveryObj = reset($this->arDeliveryServiceAll);
        }

        $shipment = $shipmentCollection->createItem($deliveryObj);
        $shipment->setFields(
            [
                'CURRENCY'      => $this->getCurrency(),
                'DELIVERY_ID'   => $deliveryObj->getId(),
                'DELIVERY_NAME' => $deliveryObj->getName(),
            ]
        );

        $deliveryStoreList = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresList($deliveryObj->getId());
        $buyerStore = $request->get("BUYER_STORE") ? : intval($this->arUserResult['BUYER_STORE']);
        if (!empty($deliveryStoreList) && array_search($buyerStore, $deliveryStoreList))
        {
            $shipment->setStoreId($buyerStore);
        }


        /** @var $shipmentItemCollection \Bitrix\Sale\ShipmentItemCollection */
        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($this->getBasket() as $item) {
            /**
             * @var $item \Bitrix\Sale\BasketItem
             * @var $shipmentItem \Bitrix\Sale\ShipmentItem
             * @var $item \Bitrix\Sale\BasketItem
             */
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        $shipmentCollection->calculateDelivery();
    }

    public function getAvailableDeliveries()
    {
        /** @var \Bitrix\Sale\Shipment $shipmentItem */
        foreach ($this->getShipmentCollection() as $shipmentItem) {
            if (!$shipmentItem->isSystem()) {
                $shipment = $shipmentItem;
                break;
            }
        }
        if (empty($shipment)) {
            $shipment = $this->getShipmentCollection()->getSystemShipment();
        }

        $availableDeliveries = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);
        return $availableDeliveries;
    }


    /**
     * @param Shipment $shipment
     * @param Delivery\Services\Base[] $deliveryObjects
     * @return null[]|CalculationResult[]
     *
     * @throws Exception
     */
    public function calcDeliveries($deliveryObjects)
    {

        /** @var \Bitrix\Sale\Shipment $shipmentItem */
        foreach ($this->getShipmentCollection() as $shipmentItem) {
            if (!$shipmentItem->isSystem()) {
                $shipment = $shipmentItem;
                break;
            }
        }
        if (empty($shipment)) {
            $shipment = $this->getShipmentCollection()->getSystemShipment();
        }

        $order = $shipment->getParentOrder();

        $calculatedDeliveries = [];

        $deliveryId = $shipment->getDeliveryId();
        $deliveryPrice = $shipment->getField('BASE_PRICE_DELIVERY');

        foreach ($deliveryObjects as $obDelivery) {
            $shipment->setField('DELIVERY_ID', $obDelivery->getId());
            $calculationResult = $obDelivery->calculate($shipment);

            if ($calculationResult->isSuccess()) {
                $shipment->setBasePriceDelivery($calculationResult->getPrice());
                $arShowPrices = $order->getDiscount()
                    ->getShowPrices();

                $data = $calculationResult->getData();
                $data['DISCOUNT_DATA'] = $arShowPrices['DELIVERY'];
                $calculationResult->setData($data);
            }

            $calculatedDeliveries[$obDelivery->getId()] = $calculationResult;
        }

        //restore actual data
        $shipment->setField('DELIVERY_ID', $deliveryId);
        $shipment->setBasePriceDelivery($deliveryPrice);

        return $calculatedDeliveries;
    }

    public function getAvailableDeliveriesStores()
    {
        /** @var \Bitrix\Sale\Shipment $shipmentItem */
        foreach ($this->getShipmentCollection() as $shipmentItem) {
            if (!$shipmentItem->isSystem()) {
                $shipment = $shipmentItem;
                break;
            }
        }
        if (empty($shipment)) {
            $shipment = $this->getShipmentCollection()->getSystemShipment();
        }

        $storesID = $shipment->getStoreId();
        return $storesID;
    }

    public function getStoreDelivery($arStore = []){


        /** @var \Bitrix\Sale\Shipment $shipmentItem */
        foreach ($this->getShipmentCollection() as $shipmentItem) {
            if (!$shipmentItem->isSystem()) {
                $shipment = $shipmentItem;
                break;
            }
        }
        if (empty($shipment)) {
            $shipment = $this->getShipmentCollection()->getSystemShipment();
        }

        $currentDeliveryId = $shipment->getDeliveryId();

        foreach ($this->arDeliveryServiceAll as $itemDelivery) {

            if ($itemDelivery->getId() == $currentDeliveryId){
                $storeService = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresFields($itemDelivery->getId(), true);

                if(!empty($storeService["PARAMS"]["STORES"])) {
                    $arStore = array_merge($arStore, $storeService["PARAMS"]["STORES"]);
                }
            }
        }
        $arStore = array_unique($arStore);

        $resultStore = [];

        if(!empty($arStore)){

            $arStore = array();
            $dbList = \CCatalogStore::GetList(
                array("SORT" => "DESC", "ID" => "DESC"),
                array("ACTIVE" => "Y", "ID" => $arStore, "ISSUING_CENTER" => "Y", "+SITE_ID" => $this->getSiteId()),
                false,
                false,
                array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S", "ISSUING_CENTER", "SITE_ID")
            );
            while ($arStoreTmp = $dbList->Fetch())
            {
                if ($arStoreTmp["IMAGE_ID"] > 0)
                    $arStoreTmp["IMAGE_ID"] = CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);
                else
                    $arStoreTmp["IMAGE_ID"] = null;

                $resultStore[$arStoreTmp["ID"]] = $arStoreTmp;
            }

        }

        return $resultStore;
    }


    public function initPayment()
    {

        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $availablePayments = $this->getAvailablePaySystems();
        $userPaymentRequest = $request->get("PAYMENT_ID")? : intval($this->arUserResult['PAY_SYSTEM_ID']);

        if ($userPaymentRequest > 0 && array_key_exists($userPaymentRequest, $availablePayments)) {
            $paymentInfo = $availablePayments[ $userPaymentRequest ];
        } else {
            $paymentInfo = reset($availablePayments);
        }

        $paymentCollection = $this->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $payment->setFields([
            'PAY_SYSTEM_ID'   => $paymentInfo["ID"],
            'PAY_SYSTEM_NAME' => $paymentInfo["NAME"],
            "SUM"             => $this->getPrice(),
            "CURRENCY"        => $this->getCurrency(),
        ]);
    }

    public function getAvailablePaySystems()
    {
        $payment = \Bitrix\Sale\Payment::create($this->getPaymentCollection());
        $payment->setField('SUM', $this->getPrice());
        $payment->setField("CURRENCY", $this->getCurrency());
        $paySystemsList = \BItrix\Sale\PaySystem\Manager::getListWithRestrictions($payment);

        return $paySystemsList;
    }

    public function initPersonType()
    {
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $availablePersonType = $this->getAvailablePersonType();
        $userPersonTypeRequest = $request->get("PERSON_TYPE") ? : intval($this->arUserResult['PERSON_TYPE_ID']);
        if ($userPersonTypeRequest > 0 && array_key_exists($userPersonTypeRequest, $availablePersonType)) {
            $personTypeId = $userPersonTypeRequest;
        } else {
            $personTypeId = reset(array_keys($availablePersonType));
        }

        $this->setPersonTypeId($personTypeId);
    }

    public function getAvailablePersonType()
    {
        $personTypes = \Bitrix\Sale\PersonType::load(\Bitrix\Main\Context::getCurrent()->getSite());
        return $personTypes;
    }

    public function getPropertyByGroup($groupId)
    {

        $resultGroup = [];

        $propertyCollection = $this->getPropertyCollection();
        $propetyByGroup = $propertyCollection->getGroupProperties($groupId);


        $deliverySystemIds = $this->getDeliverySystemId();
        $paymentSystemIds = $this->getPaymentSystemId();

        foreach ($propetyByGroup as $itemProperty) {
            /** @var $itemProperty \Bitrix\Sale\PropertyValue */
            if ($itemProperty->isUtil()) {
                continue;
            }
            if ($this->checkRelatedProperty($itemProperty, $paymentSystemIds, $deliverySystemIds)) {
                $resultGroup[] = $itemProperty;
            }
        }

        return $resultGroup;
    }

    /**
     * Returns true if current property is valid for selected payment & delivery
     *
     * @param $property
     * @param $arPaymentId
     * @param $arDeliveryId
     * @return bool
     */
    protected function checkRelatedProperty(\Bitrix\Sale\PropertyValue $property, $arPaymentId, $arDeliveryId)
    {
        $okByPs = null;
        $okByDelivery = null;

        if (is_array($property->getRelations())) {
            foreach ($property->getRelations() as $relation) {
                if (empty($okByPs) && $relation['ENTITY_TYPE'] == 'P') {
                    $okByPs = in_array($relation['ENTITY_ID'], $arPaymentId);
                }

                if (empty($okByDelivery) && $relation['ENTITY_TYPE'] == 'D') {
                    $okByDelivery = in_array($relation['ENTITY_ID'], $arDeliveryId);
                }
            }
        }

        return ((is_null($okByPs) || $okByPs) && (is_null($okByDelivery) || $okByDelivery));
    }

    public function getOrderStepCollection()
    {
        if (!isset($this->orderStepCollection)) {
            $this->orderStepCollection = new OrderStep\OrderStepCollection($this);
            $this->orderStepCollection->setStep(new OrderStep\PersonalDataStep());
            $this->orderStepCollection->setStep(new OrderStep\DeliveryDataStep());
            $this->orderStepCollection->setStep(new OrderStep\PaymentDataStep());
            $this->orderStepCollection->setStep(new OrderStep\OtherDataStep());
        }

        return $this->orderStepCollection;
    }

    protected function getLastOrderData()
    {
        $lastOrderData = array();

        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();

        $filter = array(
            'filter' => array(
                'USER_ID' => $this->getUserId(),
                'LID' => $this->getSiteId()
            ),
            'select' => array('ID'),
            'order' => array('ID' => 'DESC'),
            'limit' => 1
        );


        if ($arOrder = $orderClassName::getList($filter)->fetch())
        {
            /** @var Order $lastOrder */
            $lastOrder = $orderClassName::load($arOrder['ID']);
            $lastOrderData['PERSON_TYPE_ID'] = $lastOrder->getPersonTypeId();

            if ($payment = $this->getInnerPayment($lastOrder))
                $lastOrderData['PAY_CURRENT_ACCOUNT'] = 'Y';

            if ($payment = $this->getExternalPayment($lastOrder))
                $lastOrderData['PAY_SYSTEM_ID'] = $payment->getPaymentSystemId();

            if ($shipment = $this->getCurrentShipment($lastOrder))
            {
                $lastOrderData['DELIVERY_ID'] = $shipment->getDeliveryId();
                $lastOrderData['BUYER_STORE'] = $shipment->getStoreId();
                $lastOrderData['DELIVERY_EXTRA_SERVICES'][$shipment->getDeliveryId()] = $shipment->getExtraServices();
                if ($storeFields = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresFields($lastOrderData['DELIVERY_ID'], false))
                    unset($lastOrderData['DELIVERY_EXTRA_SERVICES'][$shipment->getDeliveryId()][$storeFields['ID']]);
            }

            $lastOrderData["ORDER_PROP"] = [];
            $arr = $lastOrder->getPropertyCollection()->getArray();
            foreach ($arr['properties'] as $property)
            {
                if ($property['UTIL'] !== 'Y')
                {
                   $lastOrderData["ORDER_PROP"][$property['CODE']] = is_array($property['VALUE']) ? implode('/', $property['VALUE']) : $property['VALUE'];
                }
            }
        }

        return $lastOrderData;
    }

    protected function initLastOrderData()
    {
        global $USER;

        $showData = array();
        $lastOrderData = $this->getLastOrderData();

        if (!empty($lastOrderData))
        {
            if (!empty($lastOrderData['PERSON_TYPE_ID']))
                $this->arUserResult['PERSON_TYPE_ID'] = $showData['PERSON_TYPE_ID'] = $lastOrderData['PERSON_TYPE_ID'];

            if (!empty($lastOrderData['PAY_SYSTEM_ID']))
                $this->arUserResult['PAY_SYSTEM_ID'] = $showData['PAY_SYSTEM_ID'] = $lastOrderData['PAY_SYSTEM_ID'];

            if (!empty($lastOrderData['DELIVERY_ID']))
                $this->arUserResult['DELIVERY_ID'] = $showData['DELIVERY_ID'] = $lastOrderData['DELIVERY_ID'];

            if (!empty($lastOrderData['BUYER_STORE']))
                $this->arUserResult['BUYER_STORE'] = $showData['BUYER_STORE'] = $lastOrderData['BUYER_STORE'];

            if (!empty($lastOrderData['ORDER_PROP']))
                $this->arUserResult['ORDER_PROP'] = $showData['ORDER_PROP'] = $lastOrderData['ORDER_PROP'];

            $this->arUserResult['LAST_ORDER_DATA'] = $showData;
        }
    }

    public function getCurrentShipment(Order $order)
    {
        /** @var Shipment $shipment */
        foreach ($order->getShipmentCollection() as $shipment)
        {
            if (!$shipment->isSystem())
                return $shipment;
        }

        return null;
    }

    public function getInnerPayment(Order $order)
    {
        /** @var Payment $payment */
        foreach ($order->getPaymentCollection() as $payment)
        {
            if ($payment->getPaymentSystemId() == \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId())
                return $payment;
        }

        return null;
    }

    public function getExternalPayment(Order $order)
    {
        /** @var Payment $payment */
        foreach ($order->getPaymentCollection() as $payment)
        {
            if ($payment->getPaymentSystemId() != \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId())
                return $payment;
        }

        return null;
    }

}