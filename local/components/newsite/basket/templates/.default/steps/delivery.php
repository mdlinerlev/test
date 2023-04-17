<div class="basket_order__step">
    <div class="basket_order__title">Доставка</div>
    <div class="basket_order__content">
        <div class="tabset">
            <?
            $currentDeliveryId = end(array_filter($currentOrder->getDeliverySystemId()));
            $description = null;
            $storeInfo  = [];
            foreach ($arResult["AVAILABLE_DELIVERIES"] as $itemDelivery):
                $storeService = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresFields($itemDelivery->getId(), true);
                $deliveryName =  $itemDelivery->isProfile() ? $itemDelivery->getNameWithParent() : $itemDelivery->getName();
                if( $currentDeliveryId == $itemDelivery->getId()) {
                    $description = $itemDelivery->getDescription();
                    $storeInfo = end(array_filter((array)$storeService['PARAMS']['STORES']));
                    if(!empty($storeInfo))
                        $storeInfo = $arResult['STORES'][$storeInfo];
                }


            ?>
                <input
                    class="tabset_inp js_select_change_submit"
                    type="radio"
                    value="<?= $itemDelivery->getId() ?>"
                    name="DELIVERY_ID"
                    id="DELIVERY_ID_<?=$itemDelivery->getId()?>"
                    <?= $currentDeliveryId == $itemDelivery->getId() ? "checked" : "" ?>
                >
                <label for="DELIVERY_ID_<?=$itemDelivery->getId()?>" class="tabset_checkbox">
                    <?= $deliveryName?>
                </label>
            <?endforeach;?>

            <div class="tab-panels">
                <section class="tab-panel">
                    <div class="basket_order__descr">

                        <?
                        if($currentDeliveryId == 11 ){
                            $storeInfo = [
                                'GPS_N' => '43.356567,',
                                'GPS_S' => '76.940354',
                                'ADDRESS' => 'г. Алматы, Бекмаханов 2/14',
                                'DESCRIPTION' => 'г. Алматы, Бекмаханов 2/14',
                            ];
                        }
                        ?>


                        <? include __DIR__ . '/../prop.php'; ?>
                        <?
                        $phone = false;
                        ob_start();
                        if (CModule::IncludeModule("spectr.targetcontent")):
                            $APPLICATION->IncludeComponent("spectr:spectr.target-content", "template1", Array(
                                "COMPONENT_TEMPLATE" => ".default"
                            ),
                                false
                            );
                        else:
                            echo '<a class="zphone" href="tel:+375173881558"><span></span></a>';
                        endif;
                        $phone = ob_get_contents();
                        ob_end_clean();

                        ?>
                        <span>
                        <?= str_replace('#PHONE#', $phone, $description)?>
                        </span>
                        <?if(!empty($storeInfo)):?>
                            <div id="yandex-map" class="order-finish__map-container">
                                <div id="map" style="width:100%; height:100%"></div>
                            </div>
                            <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU&onload=initMaps" data-skip-moving=true></script>
                            <script data-skip-moving="true">

                                var myMap, myPlacemark;

                                function initMaps(){
                                    if(myMap)
                                        myMap.destroy();

                                    myMap = new ymaps.Map('map', {
                                        center: [<?=$storeInfo['GPS_N']?>, <?=$storeInfo['GPS_S']?>],
                                        zoom: 12
                                    }, {
                                        searchControlProvider: 'yandex#search'
                                    }),
                                        myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                                            hintContent: '<?= $storeInfo['ADDRESS']?>',
                                            balloonContent: '<?= $storeInfo['DESCRIPTION']?>'
                                        }, {
                                            iconLayout: 'default#image',
                                            iconColor: '#fff'
                                        });
                                    myMap.geoObjects.add(myPlacemark);
                                }
                            </script>

                        <?endif;?>
                    </div>
                </section>
            </div>

        </div>

    </div>
</div>