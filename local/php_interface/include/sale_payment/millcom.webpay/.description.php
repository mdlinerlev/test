<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?

IncludeModuleLangFile(__FILE__);

$psTitle = "WebPay";
$psDescription = "<a href=\"http://webpay.by\" target=\"_blank\">http://webpay.by</a>";



$arPSCorrespondence = array(
		"WSB_STOREID" => array(
				"NAME" => GetMessage("WEBPAY_WSB_STOREID_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_STOREID_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		),
		"WSB_CURRENCY_ID" => array(
				"NAME" => GetMessage("WEBPAY_WSB_CURRENCY_ID_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_CURRENCY_ID_DESCR"),
				"VALUE" => "CURRENCY",
				"TYPE" => "ORDER"
		),
		"SECRET_KEY" => array(
				"NAME" => GetMessage("WEBPAY_SECRET_KEY_NAME"),
				"DESCR" => GetMessage("WEBPAY_SECRET_KEY_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		),  
		"WSB_ORDER_NUM" => array(
				"NAME" => GetMessage("WEBPAY_WSB_ORDER_NUM_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_ORDER_NUM_DESCR"),
				"VALUE" => "ID",
				"TYPE" => "ORDER"
		),
		"WSB_TEST" => array(
				"NAME" => GetMessage("WEBPAY_WSB_TEST_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_TEST_DESCR"),
				"VALUE" => "1",
				"TYPE" => ""
		),
		"WSB_TOTAL" => array(
				"NAME" => GetMessage("WEBPAY_WSB_TOTAL_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_TOTAL_DESCR"),
				"VALUE" => "SHOULD_PAY",
				"TYPE" => "ORDER"
		),
		"WSB_LANGUAGE_ID" => array(
				"NAME" => GetMessage("WEBPAY_WSB_LANGUAGE_ID_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_LANGUAGE_ID_DESCR"),
				"VALUE" => "russian",
				"TYPE" => ""
		),
    "WSB_NOTIFY_URL" => array(
				"NAME" => GetMessage("WEBPAY_WSB_NOTIFY_URL_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_NOTIFY_URL_DESCR"),
				"VALUE" => "/webpay/result.php",
				"TYPE" => ""
		),
    "WSB_RETURN_URL" => array(
				"NAME" => GetMessage("WEBPAY_WSB_RETURN_URL_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_RETURN_URL_DESCR"),
				"VALUE" => "/webpay/ok.php",
				"TYPE" => ""
		),
    "WSB_CANCEL_RETURN_URL" => array(
				"NAME" => GetMessage("WEBPAY_WSB_CANCEL_RETURN_URL_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_CANCEL_RETURN_URL_DESCR"),
				"VALUE" => "/webpay/error.php",
				"TYPE" => ""
		),
		"WSB_CUSTOMER_NAME" => array(
				"NAME" => GetMessage("WEBPAY_WSB_CUSTOMER_NAME_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_CUSTOMER_NAME_DESCR"),
				"VALUE" => "FIO",
				"TYPE" => "PROPERTY"
		), 
		"WSB_CUSTOMER_ADDRESS" => array(
				"NAME" => GetMessage("WEBPAY_WSB_CUSTOMER_ADDRESS_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_CUSTOMER_ADDRESS_DESCR"),
				"VALUE" => "ADDRESS",
				"TYPE" => "PROPERTY"
		),
		"WSB_SERVICE_DATE" => array(
				"NAME" => GetMessage("WEBPAY_WSB_SERVICE_DATE_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_SERVICE_DATE_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		), 
		"WSB_ORDER_TAG" => array(
				"NAME" => GetMessage("WEBPAY_WSB_ORDER_TAG_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_ORDER_TAG_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		), 
		"WSB_EMAIL" => array(
				"NAME" => GetMessage("WEBPAY_WSB_EMAIL_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_EMAIL_DESCR"),
				"VALUE" => "EMAIL",
				"TYPE" => "PROPERTY"
		),
		"WSB_PHONE" => array(
				"NAME" => GetMessage("WEBPAY_WSB_PHONE_NAME"),
				"DESCR" => GetMessage("WEBPAY_WSB_PHONE_DESCR"),
				"VALUE" => "PHONE",
				"TYPE" => "PROPERTY"
		),
		"SHOP_LOGIN" => array(
				"NAME" => GetMessage("WEBPAY_SHOP_LOGIN_NAME"),
				"DESCR" => GetMessage("WEBPAY_SHOP_LOGIN_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		),
		"SHOP_PASSWORD" => array(
				"NAME" => GetMessage("WEBPAY_SHOP_PASSWORD_NAME"),
				"DESCR" => GetMessage("WEBPAY_SHOP_PASSWORD_DESCR"),
				"VALUE" => "",
				"TYPE" => ""
		)
	);
?>
