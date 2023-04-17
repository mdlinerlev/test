<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
	?>
	<div class="order-finish__title"><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></div>
	
        <div class="order-finish__parameter">
            <div class="order-finish-parameter__key">Номер заказа:
            </div>
            <div class="order-finish-parameter__value">
                <?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?>
            </div>
    <?//= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
				
				<?//= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
        </div>
        <?if ($arResult["ORDER"]["PRICE_DELIVERY"] == 0){?>
        <div class="order-finish__parameter order-finish__parameter--address">
            <div class="order-finish-parameter__key">Адрес выдачи:
              </div>
           <a class="order-finish-parameter__value" href="javascript:void(0)">Минск, ул. Промышленная, дом 10</a>
           <div id="yandex-map" class="order-finish__map-container">
            <script>

        
            ymaps.ready(function () {
                var myMap = new ymaps.Map('map', {
                        center: [53.846129, 27.683193],
                        zoom: 12
                    }, {
                        searchControlProvider: 'yandex#search'
                    }),
                    myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                        hintContent: 'Собственный значок метки',
                        balloonContent: 'точка самовывоза'
                    }, {
                        // Опции.
                        // Необходимо указать данный тип макета.
                        iconLayout: 'default#image',
                        iconColor: '#fff'
                        // Своё изображение иконки метки.
                        //iconImageHref: 'images/myIcon.gif',
                        // Размеры метки.
                        //iconImageSize: [20, 22],
                        // Смещение левого верхнего угла иконки относительно
                        // её "ножки" (точки привязки).
                        //iconImageOffset: [-3, -42]
                    });

                myMap.geoObjects.add(myPlacemark);
            });
            </script>
               
               <div id="map" style="width:100%; height:100%"></div>
           </div>
        </div>
            
            
            <?}?>
    <div class="order-finish__parameter">
              <div class="order-finish-parameter__key">Справки по&nbsp;телефону:
              </div>
              <div class="order-finish-parameter__value">+375(17)388-15-58
              </div>
            </div>
	<?
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		

		<div class="order-finish__parameter">
					<div class="order-finish-parameter__key"><?=GetMessage("SOA_TEMPL_PAY")?>:</div>
					<?//=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
					<div class="order-finish-parameter__value"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div>
                </div>
			<?
			if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
			{
				?>
			
						<?
						$service = \Bitrix\Sale\PaySystem\Manager::getObjectById($arResult["ORDER"]['PAY_SYSTEM_ID']);

						if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
						{
							?>
							<script language="JavaScript">
								window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>&PAYMENT_ID=<?=$arResult['ORDER']["PAYMENT_ID"]?>');
							</script>
							<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&PAYMENT_ID=".$arResult['ORDER']["PAYMENT_ID"]))?>
							<?
							if (CSalePdf::isPdfAvailable() && $service->isAffordPdf())
							{
								?><br />
								<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&PAYMENT_ID=".$arResult['ORDER']["PAYMENT_ID"]."&pdf=1&DOWNLOAD=Y")) ?>
								<?
							}
						}
						else
						{
							if ($service)
							{
								/** @var \Bitrix\Sale\Order $order */
								$order = \Bitrix\Sale\Order::load($arResult["ORDER_ID"]);

								/** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
								$paymentCollection = $order->getPaymentCollection();

								/** @var \Bitrix\Sale\Payment $payment */
								foreach ($paymentCollection as $payment)
								{
									if (!$payment->isInner())
									{
										$context = \Bitrix\Main\Application::getInstance()->getContext();
										$service->initiatePay($payment, $context->getRequest());
										break;
									}
								}
							}
							else
							{
								echo '<span style="color:red;">'.GetMessage("SOA_TEMPL_ORDER_PS_ERROR").'</span>';
							}
						}
						?>
				
				<?
			}
			?>
	
		<?
	}
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
