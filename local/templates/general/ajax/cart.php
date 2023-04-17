<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if(!isset($_POST['cart'])) die;

$cart = json_decode($_POST['cart'], true);
if(!is_array($cart) || !$cart) {
	return;
}

CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');
$fuserId = CSaleBasket::GetBasketUserID();

foreach($cart as $item) {
	if(!is_array($item) || sizeof($item) < 2) {
		return;
	}
	$id = $item[0];
	$quantity = $item[1];
	if(!$quantity) {
        $quantity = 1;
	}

	$props = array();
	if(sizeof($item) > 2) {
		for($i = 2; $i < sizeof($item); $i++) {
			if(is_array($item[$i])) {
				$props[] = $item[$i];
			}
		}
	}


	# добавляем товар в кол-ве quantity
	ob_start();
	Add2BasketByProductID($id, $quantity, array(), $props);
	ob_end_clean();
}

$currency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();

# выводим результат
$output = array();
$outputBuy = array();
$outputDelay = array();
$count = $countAll = $allSum = $allSumClean = 0;

$items = CSaleBasket::GetList(array(), array(
	"FUSER_ID" => CSaleBasket::GetBasketUserID(),
	"LID" => SITE_ID,
	"ORDER_ID" => "NULL"
), false, false, array(
	"ID", "PRODUCT_ID", "QUANTITY", "PRICE", "CAN_BUY", "DELAY", "DISCOUNT_PRICE"
));
while($item = $items->Fetch()) {
	if($item['CAN_BUY'] != 'Y') continue;
	$price = $item['PRICE'];
	$cleanPrice =  $item['PRICE']+$item['DISCOUNT_PRICE'];
	$sum = $price*$item['QUANTITY'];
	$cleanSum = $cleanPrice*$item['QUANTITY'];
	$itemOutput = array(
		'id' => (int)$item['ID'],
		'product_id' => (int)$item['PRODUCT_ID'],
		'quantity' => $item['QUANTITY'],
		'price' => $price,
		'price_clean' => $cleanPrice,
		'price_formatted' => SaleFormatCurrency($price, $currency, true),
		'price_formatted_clean' => SaleFormatCurrency($cleanPrice, $currency),
		'sum' => $sum,
		'sum_clean' => $cleanSum,
		'sum_formatted' => SaleFormatCurrency($sum, $currency, true),
		'sum_formatted_clean' => SaleFormatCurrency($cleanSum, $currency)
	);
	if($item['DELAY'] != 'Y') {
		$count++;
		$countAll += $item['QUANTITY'];
		$allSum += $sum;
		$allSumClean += $cleanSum;
		$outputBuy[]  = $itemOutput;
	}
	else {
		$outputDelay[] = $itemOutput;
	}
}

$output = array(
	'success' => true,
	'count' => $count,
	'count_all' => $countAll,
	'allsum' => $allSum,
	'allsum_clean' => $allSumClean,
	'allsum_formatted' => SaleFormatCurrency($allSum, $currency, true),
	'allsum_formatted_clean' => SaleFormatCurrency($allSumClean, $currency),
	'buy' => $outputBuy,
	'delay' => $outputDelay
);

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($output);