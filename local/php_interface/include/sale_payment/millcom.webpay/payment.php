<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
IncludeModuleLangFile(__FILE__);

//return url
//price format
$SERVER_NAME_tmp = "";
if (defined("SITE_SERVER_NAME"))
	$SERVER_NAME_tmp = SITE_SERVER_NAME;
if (strlen($SERVER_NAME_tmp)<=0)
	$SERVER_NAME_tmp = COption::GetOptionString("main", "server_name", "");
	
/*
$dateInsert = (strlen(CSalePaySystemAction::GetParamValue("DATE_INSERT")) > 0) ? CSalePaySystemAction::GetParamValue("DATE_INSERT") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["DATE_INSERT"];
$orderID = (strlen(CSalePaySystemAction::GetParamValue("ORDER_ID")) > 0) ? CSalePaySystemAction::GetParamValue("ORDER_ID") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"];
$shouldPay = (strlen(CSalePaySystemAction::GetParamValue("SHOULD_PAY")) > 0) ? CSalePaySystemAction::GetParamValue("SHOULD_PAY") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SHOULD_PAY"];
$currency = (strlen(CSalePaySystemAction::GetParamValue("CURRENCY")) > 0) ? CSalePaySystemAction::GetParamValue("CURRENCY") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"];
$sucUrl = (strlen(CSalePaySystemAction::GetParamValue("SUCCESS_URL")) > 0) ? CSalePaySystemAction::GetParamValue("SUCCESS_URL") : "http://".$SERVER_NAME_tmp;
$failUrl = (strlen(CSalePaySystemAction::GetParamValue("FAIL_URL")) > 0) ? CSalePaySystemAction::GetParamValue("FAIL_URL") : "http://".$SERVER_NAME_tmp;
*/

$WSB_TEST = CSalePaySystemAction::GetParamValue("WSB_TEST");
$WSB_STOREID = CSalePaySystemAction::GetParamValue("WSB_STOREID");
$WSB_ORDER_NUM = (strlen(CSalePaySystemAction::GetParamValue("WSB_ORDER_NUM")) > 0) ? CSalePaySystemAction::GetParamValue("WSB_ORDER_NUM") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"];
$WSB_TOTAL = (strlen(CSalePaySystemAction::GetParamValue("WSB_TOTAL")) > 0) ? CSalePaySystemAction::GetParamValue("WSB_TOTAL") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SHOULD_PAY"];

$WSB_CURRENCY_ID = (strlen(CSalePaySystemAction::GetParamValue("WSB_CURRENCY_ID")) > 0) ? CSalePaySystemAction::GetParamValue("WSB_CURRENCY_ID") : $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["WSB_CURRENCY_ID"];
$WSB_LANGUAGE_ID = (strlen(CSalePaySystemAction::GetParamValue("WSB_LANGUAGE_ID")) > 0) ? CSalePaySystemAction::GetParamValue("WSB_LANGUAGE_ID") : 'russian';
$WSB_SEED = time();
$SECRET_KEY = CSalePaySystemAction::GetParamValue("SECRET_KEY");


$WSB_RETURN_URL = CSalePaySystemAction::GetParamValue("WSB_RETURN_URL");
$WSB_CANCEL_RETURN_URL = CSalePaySystemAction::GetParamValue("WSB_CANCEL_RETURN_URL");
//$WSB_NOTIFY_URL = 'http://'.$_SERVER['SERVER_NAME'].substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']))."/result_rec.php";
$WSB_NOTIFY_URL = CSalePaySystemAction::GetParamValue("WSB_NOTIFY_URL");

$WSB_TOTAL = CSalePaySystemAction::GetParamValue("WSB_TOTAL");
$WSB_TOTAL = roundEx($WSB_TOTAL,2);
$WSB_SIGNATURE = sha1($WSB_SEED.$WSB_STOREID.$WSB_ORDER_NUM.$WSB_TEST.$WSB_CURRENCY_ID.$WSB_TOTAL.$SECRET_KEY);
//$WSB_SIGNATURE = md5($WSB_SEED.$WSB_STOREID.$WSB_ORDER_NUM.$WSB_TEST.$WSB_CURRENCY_ID.$WSB_TOTAL.$SECRET_KEY);

$WSB_EMAIL = CSalePaySystemAction::GetParamValue("WSB_EMAIL");
$WSB_PHONE = CSalePaySystemAction::GetParamValue("WSB_PHONE");

$WSB_VERSION = 2;

$OrderID = $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"];
//$arItems = CSaleBasket::GetByID($OrderID);

$dbBasketItems = CSaleBasket::GetList(
	array("NAME" => "ASC"),
	array("ORDER_ID" => $OrderID),
	false,
	false,
	array("ID", "NAME", "QUANTITY", "PRICE")
);

	$rsSites = CSite::GetByID(SITE_ID);
	$arSite = $rsSites->Fetch();

	$WSB_STORE = $arSite['NAME'];





$accountNumber = (strlen($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ACCOUNT_NUMBER"]) > 0) ? $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ACCOUNT_NUMBER"] : $WSB_ORDER_NUM;
?>

<?if ($WSB_TEST):?>
<form method="POST" action="https://securesandbox.webpay.by" target="_blank">
<?else:?>
<form method="POST" action="https://payment.webpay.by" target="_blank">
<?endif;?>

<?=GetMessage("WEBPAY_FORM_TITLE");?><br>
<?=GetMessage("WEBPAY_INVOICE_NUMBER");?><?=$accountNumber.' от '.$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["DATE_INSERT"] ?><br>
<?=GetMessage("WEBPAY_SUMM_PAY");?>: <b><?echo SaleFormatCurrency($WSB_TOTAL, $WSB_CURRENCY_ID) ?></b><br>
<br>
	<input type="hidden" name="*scart">
	<input type="hidden" name="wsb_storeid" value="<?= $WSB_STOREID ?>">
	<input type="hidden" name="wsb_store" value="<?= $WSB_STORE ?>">
	<input type="hidden" name="wsb_order_num" value="<?= $WSB_ORDER_NUM ?>">
	<input type="hidden" name="wsb_currency_id" value="<?= $WSB_CURRENCY_ID ?>">
	<input type="hidden" name="wsb_version" value="<?= $WSB_VERSION ?>">
	<input type="hidden" name="wsb_language_id" value="<?= $WSB_LANGUAGE_ID ?>">
	<input type="hidden" name="wsb_seed" value="<?= $WSB_SEED ?>">
	<input type="hidden" name="wsb_signature" value="<?= $WSB_SIGNATURE ?>">
	<input type="hidden" name="wsb_return_url" value="<?= "http://".$_SERVER['SERVER_NAME'].$WSB_RETURN_URL ?>">
	<input type="hidden" name="wsb_cancel_return_url" value="<?= "http://".$_SERVER['SERVER_NAME'].$WSB_CANCEL_RETURN_URL ?>">
	<input type="hidden" name="wsb_notify_url" value="<?= "http://".$_SERVER['SERVER_NAME'].$WSB_NOTIFY_URL ?>">
	<input type="hidden" name="wsb_test" value="<?= $WSB_TEST ?>">

<? if ($GLOBALS["SALE_INPUT_PARAMS"]['ORDER']['PRICE_DELIVERY']): ?>
	<input type="hidden" name="wsb_shipping_price" value="<?= roundEx($GLOBALS["SALE_INPUT_PARAMS"]['ORDER']['PRICE_DELIVERY'],2); ?>">
<? endif; ?>

<? if ($GLOBALS["SALE_INPUT_PARAMS"]['ORDER']['DISCOUNT_VALUE']): ?>
	<input type="hidden" name="wsb_discount_price" value="<?= roundEx($GLOBALS["SALE_INPUT_PARAMS"]['ORDER']['DISCOUNT_VALUE'],2); ?>">
<? endif; ?>

<? $key = 0; ?>
<? while ($arItem = $dbBasketItems->Fetch()): ?>
	<input type="hidden" name="wsb_invoice_item_name[<?= $key ?>]" value="<?= htmlspecialchars($arItem['NAME']) ?>">
	<input type="hidden" name="wsb_invoice_item_quantity[<?= $key ?>]" value="<?= $arItem['QUANTITY'] ?>">
	<input type="hidden" name="wsb_invoice_item_price[<?= $key ?>]" value="<?= roundEx($arItem['PRICE'],2) ?>">
<? $key++; ?>
<? endwhile; ?>
	<input type="hidden" name="wsb_total" value="<?= $WSB_TOTAL ?>">
	<input type="hidden" name="wsb_email" value="<?= $WSB_EMAIL ?>">
	<input type="hidden" name="wsb_phone" value="<?= $WSB_PHONE ?>">
	<input type="submit" value="<?=GetMessage("WEBPAY_SUBMIT");?>">
	
</form>


