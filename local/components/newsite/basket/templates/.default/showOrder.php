<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var $currentBasket \Newsite\Sale\Basket
 * @var $currentOrder \Newsite\Sale\Order
 * @var $component \CShBasket
 */
$currentBasket = $component::$basket;
$currentOrder = $component::$order;

if (empty($_REQUEST["ORDERHASH"])): ?>
    <script>
        $(document).ready(function () {
            window.location = '<?= $arResult["RESTORE_LINK"]; ?>';
        });
    </script>
    <? return; ?>
<? endif; ?>

<?
/** @var $itemShipment \Bitrix\Sale\Shipment */
foreach ($currentOrder->getShipmentCollection() as $itemShipment ) {
    $shipment = $itemShipment;
    if (!$itemShipment->isSystem()) {
        break;
    }
}
?>


<?
$address = [];
$itemProperty = $currentOrder->getPropertyCollection()->getDeliveryLocation();
if ($itemProperty->getValue())
    $address[] = $itemProperty->getViewHtml();



$orderStepCollection = $currentOrder->getOrderStepCollection();
/** @var $stepPersonal \Newsite\Sale\OrderStep\OrderStep */
$stepPersonal = $orderStepCollection->offsetGet("delivery");
$groupPersonal = $stepPersonal->getGroupIds();
foreach ($groupPersonal as $itemGroupId):
    $itemPropertysGroup = $currentOrder->getPropertyByGroup($itemGroupId);
    foreach ($itemPropertysGroup as $itemProperty):
        $property = $itemProperty->getPropertyObject();
        if ($itemProperty->getValue() && $property->getField("IS_LOCATION") !== 'Y')
            $address[] = $itemProperty->getValue();
    endforeach;
endforeach;

$storesID = $shipment->getStoreId();
if(!empty($storesID)){
    $arResult["STORES"] = $currentOrder->getStoreDelivery((array)$storesID);
    if(!empty($arResult["STORES"][$storesID]))
        $address[] = $arResult["STORES"][$storesID]['NAME'];
}
?>
<div id="basket_form">
<? if (empty($_REQUEST["ORDERHASH"])): ?>
    <div class="js-reload" data-href="<?= $arResult["RESTORE_LINK"]; ?>"></div>
    <? return; ?>
<? endif; ?>


<div class="order-finish__title">Спасибо, ваш заказ оформлен и товар зарезервирован для вас</div>

<div class="order-finish__parameter">
    <div class="order-finish-parameter__key">
        Номер заказа:
    </div>
    <div class="order-finish-parameter__value">
        <?=$currentOrder->getField("ACCOUNT_NUMBER");?>
    </div>
</div>

<?if (!empty($storesID)):?>
<div class="order-finish__parameter order-finish__parameter--address">
    <div class="order-finish-parameter__key">Адрес выдачи:</div>
    <a class="order-finish-parameter__value" href="javascript:void(0)"><?= $arResult["STORES"][$storesID]['ADDRESS']?></a>
    <?if(!empty($arResult["STORES"][$storesID])):?>
        <div id="yandex-map" class="order-finish__map-container">
            <div id="map" style="width:100%; height:100%"></div>
        </div>

        <script data-skip-moving="true">
            ymaps.ready(initMaps);

            var myMap, myPlacemark;

            function initMaps(){
                if(myMap)
                    myMap.destroy();

                myMap = new ymaps.Map('map', {
                    center: [<?=$arResult["STORES"][$storesID]['GPS_N']?>, <?=$arResult["STORES"][$storesID]['GPS_S']?>],
                    zoom: 12
                }, {
                    searchControlProvider: 'yandex#search'
                }),
                    myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                        hintContent: '<?= $arResult["STORES"][$storesID]['ADDRESS']?>',
                        balloonContent: '<?= $arResult["STORES"][$storesID]['DESCRIPTION']?>'
                    }, {
                        iconLayout: 'default#image',
                        iconColor: '#fff'
                    });
                myMap.geoObjects.add(myPlacemark);
            }
        </script>

    <?endif;?>
</div>
<?else:?>

    <div class="order-finish__parameter">
        <div class="order-finish-parameter__key">Справки по&nbsp;телефону:
        </div>
        <div class="order-finish-parameter__value">+7-(499)-380-89-19
        </div>
    </div>


    <?
    foreach ($currentOrder->getPaymentCollection() as $payment):


        ?>
        <div class="order-finish__parameter">
            <div class="order-finish-parameter__key">Оплата заказа:</div>
            <div class="order-finish-parameter__value"><?= $payment->getPaymentSystemName()?></div>
        </div>
        <?

        if (intval($payment->getPaymentSystemId()) > 0 && !$payment->isPaid())
        {
            $paySystemService = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
            if (!empty($paySystemService))
            {
                $arPaySysAction = $paySystemService->getFieldsValues();
                if(strlen($arPaySysAction["ACTION_FILE"]) > 0 && $arPaySysAction["NEW_WINDOW"] == "Y" && $arPaySysAction["IS_CASH"] != "Y")
                {
                    $_SESSION['SALE_ORDER_ID'][$currentOrder->getId()] = $currentOrder->getId();
                    $link = '/personal/order/payment/?ORDER_ID='.$currentOrder->getId().'&hash=&PAYMENT_ID='.$payment->getPaymentSystemId();
                    ?>
                    <script>
                        window.open('<?=$link?>');
                    </script>
                    <div class="shopping-cart__order-action">
                        <a href="<?=$link?>" class="shopping-cart__order-btn btn btn--primary" type="button">Перейти к оплате</a>
                        <div class="shopping-cart__order-action-text">Вы будете перенаправлены на страницу оплаты</div>
                    </div>
                    <?
                }
            }
        }
    endforeach;
    ?>

<?endif;?>
</div>


<?
/** @var \Newsite\Sale\BasketItem $basketItem */
$sBasketItemsID = "";
foreach ($currentBasket->getBasketItems() as $basketItem) {
    if($idProduct = CIBlockElement::GetProperty(12, $basketItem->getProductId(), array("sort" => "asc"), Array("CODE"=>"CML2_LINK"))->Fetch()["VALUE"]) {
        $sBasketItemsID = $sBasketItemsID."ru-".$idProduct.",";
    } else {
        $sBasketItemsID = $sBasketItemsID."ru-".$basketItem->getProductId().",";
    }
}
$sBasketItemsID = substr($sBasketItemsID,0,-1);
global $globalBasketLines;
$globalBasketLines = $sBasketItemsID;
?>
<script>
    (function () {
        var key = '__rtbhouse.lid';
        var lid = window.localStorage.getItem(key);
        if (!lid) {
            lid = '';
            var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
            window.localStorage.setItem(key, lid);
        }
        var body = document.getElementsByTagName("body")[0];
        var ifr = document.createElement("iframe");
        var siteReferrer = document.referrer ? document.referrer : '';
        var siteUrl = document.location.href ? document.location.href : '';
        var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
        var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
        var timestamp = "" + Date.now();
        var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_300_regular-<?=$currentOrder->getField("ACCOUNT_NUMBER");?>_<?=$sBasketItemsID?>&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
        ifr.setAttribute("src", source);
        ifr.setAttribute("width", "1");
        ifr.setAttribute("height", "1");
        ifr.setAttribute("scrolling", "no");
        ifr.setAttribute("frameBorder", "0");
        ifr.setAttribute("style", "display:none");
        ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
        body.appendChild(ifr);
    }());
</script>