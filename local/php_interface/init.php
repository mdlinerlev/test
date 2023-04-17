<?php
require $_SERVER['DOCUMENT_ROOT'].'/local/vendor/autoload.php';

require 'init/constant.php';
require 'init/functions.php';
require 'init/events.php';
require 'init/classes.php';
require 'init/uncached.php';


require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/eventslist.php';

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/classes/Newsite/Base/Autoloader.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/classes/orm/MainMapTable.php';
$psr4 = \Newsite\Base\Autoloader::getInstance();
$psr4->addNamespace('Newsite', $_SERVER['DOCUMENT_ROOT'] . '/local/classes/Newsite/');
$psr4->register();



if ($_REQUEST["basketAction"] == 'deleteall' and CModule::IncludeModule("sale"))
{
CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
}
//task 1216
AddEventHandler("search", "BeforeIndex", Array("SearchExclude", "BeforeIndexHandler"));

class SearchExclude
{
    static function BeforeIndexHandler($arFields)
    {
        if ($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 2)
        {
            $db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
                $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
                $arFields["ITEM_ID"],          // ID индексируемого свойства
                array("sort" => "asc"),       
                Array("!PRODUCT_TYPE"=>false)); 
            if($ar_props = $db_props->Fetch()){
                $arFields["TITLE"] = "";
            }
            //
        }
        return $arFields;
    }
}

//task : Сделать так, чтобы в будущем каждая новая коллекция
// (Каталоги→Номенклатура→Коллекция→[Название группы]→[Название коллекции])
// попадали в выпадающий список «Коллекция» в товаре.
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", Array("MyAddSection", "OnAfterIBlockSectionAddHandler"));

class MyAddSection
{
    // создаем обработчик события "OnAfterIBlockSectionAdd"
    static function OnAfterIBlockSectionAddHandler(&$arFields)
    {
        $arCollectionSectionId = [105,104,103]; // ID  разделов коллекций
        if($arFields["ID"]>0){
            if (in_array( $arFields['IBLOCK_SECTION_ID'], $arCollectionSectionId)) {
                //Метод добавляет новый вариант значения свойства типа "список". Метод статический
                $ibpenum = new CIBlockPropertyEnum;
                if($PropID = $ibpenum->Add(Array('PROPERTY_ID'=>130, 'VALUE'=>$arFields['NAME']))){}

            }

        }
    }
}

//task 1216
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoIBlockAfterSave");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoIBlockAfterSave");
//AddEventHandler("main", "OnAfterEpilog", "DoIBlockAfterSaveTest");


//Если есть торговое предложение, оно активно и количество товаров больше нуля, то устанавливаем поле в самом товаре
function DoIBlockAfterSaveTest($arg1, $arg2 = false){
    $arProductInfo = CIBlockElement::GetList(array(),array('IBLOCK_ID' => 2),
        false, false, array('ID'));
    while ($arProduct = $arProductInfo->GetNext()){
        $arInfo = CIBlockElement::GetList(array(),array('IBLOCK_ID' => 12, 'PROPERTY_CML2_LINK' => $arProduct['ID']),
            false, false, array('ID','PROPERTY_SKLAD', 'CATALOG_QUANTITY', 'PROPERTY_CML2_LINK'));
        $is_active = 200;
        while ($arOffer = $arInfo->GetNext()) {
            if($arOffer['PROPERTY_SKLAD_VALUE'] == 'Y' && $arOffer['CATALOG_QUANTITY'] > 0){
                $is_active = 100;
            }
        }

        CIBlockElement::SetPropertyValuesEx(
            $arProduct['ID'],
            false,
            array(
                "ACTIVE_SKLAD" => $is_active,
            )
        );
    }




}

function DoIBlockAfterSave($arg1, $arg2 = false)
{
    $ELEMENT_ID = false;
    $IBLOCK_ID = false;


    //Check for catalog event
    if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0)
    {
        //Get iblock element
        $rsPriceElement = CIBlockElement::GetList(
            array(),
            array(
                "ID" => $arg2["PRODUCT_ID"],
            ),
            false,
            false,
            array("ID", "IBLOCK_ID")
        );
        if($arPriceElement = $rsPriceElement->Fetch())
        {
            $arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
            if(is_array($arCatalog))
            {
                //Check if it is offers iblock
                if($arCatalog["OFFERS"] == "Y")
                {
                    //Find product element
                    $rsElement = CIBlockElement::GetProperty(
                        $arPriceElement["IBLOCK_ID"],
                        $arPriceElement["ID"],
                        "sort",
                        "asc",
                        array("ID" => $arCatalog["SKU_PROPERTY_ID"])
                    );
                    $arElement = $rsElement->Fetch();
                    if($arElement && $arElement["VALUE"] > 0)
                    {
                        $ELEMENT_ID = $arElement["VALUE"];
                        $IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
                    }
                }
                //or iblock which has offers
                elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0)
                {
                    $ELEMENT_ID = $arPriceElement["ID"];
                    $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
                }
                //or it's regular catalog
                else
                {
                    $ELEMENT_ID = $arPriceElement["ID"];
                    $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
                }
            }
        }
    }
    //Check for iblock event
    elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0)
    {
        //Check if iblock has offers
        $arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
        if(is_array($arOffers))
        {
            $ELEMENT_ID = $arg1["ID"];
            $IBLOCK_ID = $arg1["IBLOCK_ID"];
        }
    }

    if($ELEMENT_ID)
    {
        $IBLOCK_ID = 2;
        $arInfo = CIBlockElement::GetList(array(),array('IBLOCK_ID' => 12, 'PROPERTY_CML2_LINK' => $ELEMENT_ID),
            false, false, array('ID','PROPERTY_SKLAD', 'CATALOG_QUANTITY', 'PROPERTY_CML2_LINK'));
        $is_active = 200;
        while ($arOffer = $arInfo->GetNext()) {
            if($arOffer['PROPERTY_SKLAD_VALUE'] == 'Y' && $arOffer['CATALOG_QUANTITY'] > 0){
                $is_active = 100;
            }
        }

        CIBlockElement::SetPropertyValuesEx(
            $ELEMENT_ID,
            2,
            array(
                "ACTIVE_SKLAD" => $is_active,
            )
        );
    }
}

function clean_expire_cache($path = "") {
    if (!class_exists("CFileCacheCleaner")) {
        require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/cache_files_cleaner.php");
    }
    $curentTime = time();
if (defined("BX_CRONTAB") && BX_CRONTAB === true) $endTime = time() + 5; //Если на кроне, то работает 5 секунд
    else $endTime = time() + 1; //Если на хитах, то не более 1 секунды
//Работаем со всем кэшем
    $obCacheCleaner = new CFileCacheCleaner("all");
if (!$obCacheCleaner->InitPath($path)) {
        //Произошла ошибка
        return "clean_expire_cache();";
    }
    $obCacheCleaner->Start();
    while ($file = $obCacheCleaner->GetNextFile()) {
        if (is_string($file)) {
            $date_expire = $obCacheCleaner->GetFileExpiration($file);
            if ($date_expire) {
                if ($date_expire < $curentTime) {
                    unlink($file);
                }
            }
            if (time() >= $endTime) break;
        }
    }
    if (is_string($file)) {
        return "clean_expire_cache(\"" . $file . "\");";
    }
    else {
        return "clean_expire_cache();";
    }
}

if (!function_exists("PR")) {
    function PR($o, $show = false)
    {
        global $USER;
        if ($USER->isAdmin() || $show) {
            $bt = debug_backtrace();
            $bt = $bt[0];
            $dRoot = $_SERVER["DOCUMENT_ROOT"];
            $dRoot = str_replace("/", "\\", $dRoot);
            $bt["file"] = str_replace($dRoot, "", $bt["file"]);
            $dRoot = str_replace("\\", "/", $dRoot);
            $bt["file"] = str_replace($dRoot, "", $bt["file"]);
            ?>
            <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
                <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                    File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
                </div>
                <pre style='padding:10px;'><? print_r($o) ?></pre>
            </div>
            <?
        } else {
            return false;
        }
    }
}


//отправка в прокси с кучей полей при создании заказа
use Bitrix\Main\Event;  
use Bitrix\Sale;
use Bitrix\Sale\Delivery\Services\Manager;
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'rsOnAddOrder');
function rsOnAddOrder(Event $event) {
	if(!$event->getParameter('IS_NEW')) return;
	
	/** @var Sale\Order $order */
	$order              = $event->getParameter('ENTITY');
	$basket             = $order->getBasket();
	$propertyCollection = $order->getPropertyCollection();

	
	$list = null;
	foreach($basket->getListOfFormatText() as $item) {
		$list .= $item."\n";
	}

	$price       = $order->getPrice();
	$discount    = $order->getDiscountPrice();
	$description = $order->getField('USER_DESCRIPTION');
	$userName  = $phone = $email = $address = $location = null;
	$comment   = "{$description} \n";

	foreach ($propertyCollection as $property) {
		$name  = $property->getField('NAME');
		$code  = $property->getField('CODE');
		$value = $property->getValue();
		// Если в заказе есть какие либо доп. поля, их нужно указать тут.
		switch($code) {
			case 'PHONE':
				$phone = $value;
				break;
			case 'EMAIL':
				$email = $value;
				break;
			case 'FIO':
				$userName = $value;
				break;
			case 'CONTACT_PERSON':
				$userName = $value;
				break;
			case 'LOCATION':
				$location = CSaleLocation::GetByID(CSaleLocation::getLocationIDbyCODE($value));
				if($location['COUNTRY_NAME'] || $location['REGION_NAME'] || $location['CITY_NAME']){
					$comment .= "{$name}: {$location['COUNTRY_NAME']}, {$location['REGION_NAME']}, {$location['CITY_NAME']}".PHP_EOL;
				}
				break;
			default:
				if (is_string($value) or is_int($value)) {
					$comment .= "{$name}: {$value}".PHP_EOL;
				}
		}
	}

	$paymentCollection = $order->getPaymentCollection();
	$paymentName = array_key_exists('0', (array)$paymentCollection) ?  $paymentCollection['0']->getPaymentSystemName() : null;
	$deliverySystemId = $order->getDeliverySystemId();
	$managerById = array_key_exists('0', $deliverySystemId) ? Manager::getById($deliverySystemId['0']) : null;
	$deliveryName = ($managerById &&array_key_exists('NAME', $managerById)) ? $managerById['NAME'] : $deliveryName;

	$comment .= "Список товаров:".PHP_EOL."{$list}".PHP_EOL.PHP_EOL."Способ доставки: {$deliveryName}".PHP_EOL."Способ оплаты: {$paymentName}".PHP_EOL;

	if ($order->getDeliveryPrice() > 0) {
		$comment .= 'Доставка - '.number_format($order->getDeliveryPrice(),0,'',' ')." руб".PHP_EOL;
	}

	if ($discount > 0) {
		$comment .= 'Скидка - '.number_format($discount,0,'',' ')." руб".PHP_EOL;
	}
	$comment .= "Итого - {$price} руб";

	sendToRoistat("Заказ (№{$order->getId()})",$userName,$phone,$email,$comment, $price);
}


function sendToRoistat($title,$name,$phone,$email,$comment, $price = null){
	if (mb_strlen($phone) || mb_strlen($email) ) {
		$roistatData = array(
				'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : null,
				'key' => 'ZGFlY2M0ZjFlMTNlOTI5OWVjZjExYTI5NWYwZGE5MWM6MTY0NTgx',
				'title' => $title,
				'comment' => $comment,
				'name' => $name,
				'phone' => $phone,
				'email' => $email,
				'fields' => array(
					'OPPORTUNITY' => $price
				)
		);
		 file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));
		
	}
}
///
//ROISTAT BEGIN
