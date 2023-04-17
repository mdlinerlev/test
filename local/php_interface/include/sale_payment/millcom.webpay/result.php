<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
IncludeModuleLangFile(__FILE__);

$LOGIN = CSalePaySystemAction::GetParamValue("SHOP_LOGIN");
$PASSWORD = CSalePaySystemAction::GetParamValue("SHOP_PASSWORD");
$WSB_TEST = CSalePaySystemAction::GetParamValue("WSB_TEST");

$ORDER_ID = IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]);
$TRANSACTION_ID = $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]['PAY_VOUCHER_NUM'];



set_time_limit(0);

$postdata = '*API=&API_XML_REQUEST='.urlencode('
<?xml version="1.0" encoding="ISO-8859-1" ?>
<wsb_api_request>
<command>get_transaction</command>
<authorization>
<username>'.$LOGIN.'</username>
<password>'.md5($PASSWORD).'</password>
</authorization>
<fields><transaction_id>'.$TRANSACTION_ID.'</transaction_id></fields>
</wsb_api_request>');




if ($WSB_TEST)
	$link = "https://sandbox.webpay.by";
else
	$link = "https://billing.webpay.by";

$curl = curl_init($link);	

curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($curl);
curl_close($curl);

if (!$response) return false;

$objXML = new CDataXML();
$objXML->LoadString($response);
$arResult = $objXML->GetArray();


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


$status = $arResult["wsb_api_response"]["#"]["status"][0]['#'];

$payment_type = $arResult["wsb_api_response"]["#"]['fields'][0]['#']["payment_type"][0]['#'];
$amount = $arResult["wsb_api_response"]["#"]['fields'][0]['#']["amount"][0]['#'];
$currency_id = $arResult["wsb_api_response"]["#"]['fields'][0]['#']["currency_id"][0]['#'];
$batch_timestamp = $arResult["wsb_api_response"]["#"]['fields'][0]['#']["batch_timestamp"][0]['#']; 

if (count($arResult)>0 && $status == "success") {
	$arFields = array(
		"PS_STATUS" => (($payment_type == 1 || $payment_type == 4) ? "Y" : "N"),
		"PS_STATUS_CODE" => $payment_type,
		"PS_STATUS_DESCRIPTION" => $orderStatus[$payment_type][0],
		"PS_STATUS_MESSAGE" => $orderStatus[$payment_type][1],
		"PS_SUM" => DoubleVal($amount),
		"PS_CURRENCY" => $currency_id,
		"PS_RESPONSE_DATE" => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)))
	);
} else {
	$error_message = $arResult["wsb_api_response"]["#"]["error"][0]['#']["error_message"][0]['#'];
	$arFields = array(
		"PS_STATUS" => "N",
		"PS_STATUS_CODE" => 0,
		"PS_STATUS_DESCRIPTION" => 'Ошибка',
		"PS_STATUS_MESSAGE" => $error_message,
		"PS_RESPONSE_DATE" => Date(CDatabase::DateFormatToPHP(CLang::GetDateFormat("FULL", LANG)))
	);
}

if (CSaleOrder::Update($ORDER_ID, $arFields)){
	return true;
}

return false;
?>