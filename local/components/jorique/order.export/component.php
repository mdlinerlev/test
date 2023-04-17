<?php

/**
 * @global CMain $APPLICATION
 * @var Jorique\Components\OrderExport $this;
 */

$designMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas()
	&& !isset($_GET["mode"])
	&& is_object($GLOBALS["USER"])
	&& $GLOBALS["USER"]->IsAdmin()
;

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

if(!$_GET['pass'] || $_GET['pass'] !== 'uegh1Barcp') {
	return;
}



$ordersNode = $this->xml->createElement('orders');

$filter = array();
//$filter = array('>=DATE_INSERT' => '20.07.2016 15:18:26');
if(trim($_GET['from'])) {
	$filter['>=DATE_INSERT'] = trim($_GET['from']);
}


$orders = CSaleOrder::GetList(array(
	'DATE_INSERT' => 'DESC'
), $filter);

while($order = $orders->Fetch()) {
	$orderNode = $this->addTag($ordersNode, 'order');
	$this->addTag($orderNode, 'id', $order['ID']);
	$this->addTag($orderNode, 'create_time', $order['DATE_INSERT']);
	$this->addTag($orderNode, 'price', $order['PRICE']);
	$this->addTag($orderNode, 'delivery_price', $order['PRICE_DELIVERY']);

	if($order['PAY_SYSTEM_ID']) {
		$payment = CSalePaySystem::GetByID($order['PAY_SYSTEM_ID'], $order['PERSON_TYPE_ID']);
		if($payment) {
			$paymentNode = $this->addTag($orderNode, 'pay_system', $payment['NAME']);
			$paymentNode->setAttribute('id', $payment['ID']);
		}
	}

	if($order['DELIVERY_ID']) {
		$delivery = CSaleDelivery::GetByID($order['DELIVERY_ID']);
		if($delivery) {
			$deliveryNode = $this->addTag($orderNode, 'delivery', $delivery['NAME']);
			$deliveryNode->setAttribute('id', $delivery['ID']);
		}

	}

	# клиент
	$client = $this->addTag($orderNode, 'client');
	$this->addTag($client, 'id', $order['USER_ID']);
	$this->addTag($client, 'person_type', $order['PERSON_TYPE_ID']);

	$props = CSaleOrderPropsValue::GetList(array(), array(
		'ORDER_ID' => $order['ID']
	));
	while($prop = $props->Fetch()) {
		$this->addTag($client, $this->getPropCode($prop), $this->getPropValue($prop));
	}

	# товары
	$productsNode = $this->addTag($orderNode, 'products');

	$products = CSaleBasket::GetList(array(), array(
		'ORDER_ID' => $order['ID']
	));
	while($product = $products->Fetch()) {
		$el = CIBlockElement::GetByID($product['PRODUCT_ID'])->Fetch();

		$productNode = $this->addTag($productsNode, 'product');
		$this->addTag($productNode, 'id', $product['PRODUCT_ID']);
		if($el && $el['XML_ID']) {
			$this->addTag($productNode, 'xml_id', $el['XML_ID']);
		}
		$this->addTag($productNode, 'name', $product['NAME']);
		$this->addTag($productNode, 'quantity', $product['QUANTITY']);
		$this->addTag($productNode, 'price', $product['PRICE']);
	}
}

$this->xml->appendChild($ordersNode);

if(!$designMode) {
	while(ob_get_level() && ob_end_clean());
	$APPLICATION->RestartBuffer();
	header('Content-Type: text/xml; charset=utf-8');
	echo $this->xml->saveXML();
	die;
}