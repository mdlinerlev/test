<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
IncludeModuleLangFile(__FILE__);

$batch_timestamp = $_POST['batch_timestamp'];
$currency_id = $_POST['currency_id'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'];
$order_id = $_POST['order_id'];
$site_order_id = IntVal($_POST['site_order_id']);
$transaction_id = $_POST['transaction_id'];
$payment_type = $_POST['payment_type'];
$rrn = $_POST['rrn'];

$SecretKey = CSalePaySystemAction::GetParamValue("SECRET_KEY");




$bCorrectPayment = True;

if(!($arOrder = CSaleOrder::GetByID($site_order_id))) {
	$bCorrectPayment = False;
}

$arOrder = CSaleOrder::GetByID($site_order_id);





if ($bCorrectPayment)
	CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"]);

if($bCorrectPayment) {
	$check = md5($batch_timestamp.$currency_id.$amount.$payment_method.$order_id.$site_order_id.$transaction_id.$payment_type.$rrn.$SecretKey);
	if ($_POST['wsb_signature'] != $check) { header("HTTP/1.0 404 Not Found"); die; }
}



$orderStatus = array(
	'1' => array('Completed', GetMessage("WEBPAY_ORDER_STATUS_C")),
	'2' => array('Declined', GetMessage("WEBPAY_ORDER_STATUS_D")),
	'3' => array('Pending', GetMessage("WEBPAY_ORDER_STATUS_P")),
	'4' => array('Authorized', GetMessage("WEBPAY_ORDER_STATUS_A")),
	'5' => array('Refunded', GetMessage("WEBPAY_ORDER_STATUS_R")),
	'6' => array('System', GetMessage("WEBPAY_ORDER_STATUS_S")),
	'7' => array('Voided', GetMessage("WEBPAY_ORDER_STATUS_V")),
	'8' => array('Failed', GetMessage("WEBPAY_ORDER_STATUS_F"))
);


if($bCorrectPayment) {
	$arFields = array(
		"PS_STATUS" => (($payment_type == 1 || $payment_type == 4) ? "Y" : "N"),
		"PS_STATUS_CODE" => $payment_type,
		"PS_STATUS_DESCRIPTION" => $orderStatus[$payment_type][0],
		"PS_STATUS_MESSAGE" => $orderStatus[$payment_type][1],
		"PS_SUM" => DoubleVal($amount),
		"PS_CURRENCY" => $currency_id,
		"PS_RESPONSE_DATE" => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG))),
		"PAY_VOUCHER_NUM" => $transaction_id,
		"PAY_VOUCHER_DATE" => date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)),$batch_timestamp)		
	);
	
	if ($arOrder["PAYED"] != "Y" && $arFields["PS_STATUS"] == "Y" && Doubleval($arOrder["PRICE"]) == DoubleVal($amount))
	{
		CSaleOrder::PayOrder($arOrder["ID"], "Y", true, true, 0, array("PAY_VOUCHER_NUM" => $transaction_id));
	}

	if(!empty($arFields))
		CSaleOrder::Update($arOrder["ID"], $arFields);

}

$APPLICATION->RestartBuffer();
header("HTTP/1.0 200 OK");                           
die();
?>