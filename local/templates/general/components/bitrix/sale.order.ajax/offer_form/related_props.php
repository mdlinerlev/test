<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/props_format.php");

if (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) {
    ?>
    </fieldset>
    <div class="bx_section" style="<?= $style ?>">
        <? /* <h4><?=GetMessage("SOA_TEMPL_RELATED_PROPS")?></h4> */ ?>

        <?= PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"]) ?>
    </div>

<? } else { ?>

    <div class="order__address-text">
        <p>Пункт самовывоза расположен по адресу:<br>Минск, ул. Промышленная, дом 10</p>
        <p>Время работы: Пн-Пт с 9:00 до 22:00</p>
        <p>Справки по телефону:
            <?
            if(CModule::IncludeModule("spectr.targetcontent")){
                $APPLICATION->IncludeComponent("spectr:spectr.target-content", "template1", Array(
                    "COMPONENT_TEMPLATE" => ".default"
                ),
                    false
                );
            } else {
                $APPLICATION->IncludeComponent(
                    "maxby:phones", ".order", Array(), false
                );
            }
            ?>

        </p>
    </div>
    <div id="yandex-map" class="order__map-container">
        <div id="map" style="width:100%; height:100%"></div>
    </div> 
    </fieldset>
<? } ?>
<script>


    ymaps.ready(function () {
        var myMap = new ymaps.Map('map', {
            center: [53.846692, 27.684541],
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

    BX.addCustomEvent('onAjaxSuccess', function () {
    ymaps.ready(function () {
    var myMap = new ymaps.Map('map', {
<? if (intval($loc['ID']) == 3326) { ?>
        center: [53.846129, 27.683193],
<? } ?>
    center: [53.846692, 27.684541],
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
            preset: "twirl#greenStretchyIcon"
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
    }
    );

</script>
