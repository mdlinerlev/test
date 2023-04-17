<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="news-list">
    <?if($arParams["DISPLAY_TOP_PAGER"]):?>
    	<?=$arResult["NAV_STRING"]?><br />
    <?endif;?>
             
    <script type="text/javascript">
        // ymaps.ready(init);
        var myMap;

        var groups = [
            {
                items: [
                    <?foreach($arResult["ITEMS"] as $arItem):
                        $coord[$arItem["ID"]] = explode(',', $arItem["PROPERTIES"]["REAL_MAP"]["VALUE"]);?>
                        {
                            center:[<?=$arItem["PROPERTIES"]["REAL_MAP"]["VALUE"]?>],
                            description: "<?=$arItem["PROPERTIES"]["ADDRESS"]["~VALUE"]?>",
                            name:"<?=$arItem["NAME"]?><br><?=$arItem["PROPERTIES"]["ADDRESS"]["~VALUE"]?>"
                        },
                    <?  endforeach;?>
                ]
            },
        ];
     
        function initMaps() {
            // Создание экземпляра карты.
            var myMap = new ymaps.Map('map', {
                    center: [50.443705, 30.530946],
                    zoom: 10
                }),
                // Контейнер для меню.
                menu = $('<ul class="menu"/>');

            // Перебираем все группы.
            for (var i = 0, l = groups.length; i < l; i++) {
                createMenuGroup(groups[i]);
            }

            function createMenuGroup (group) {
                // Пункт меню.
                var menuItem = $('<li><a href="#">' + group.name + '</a></li>'),
                // Коллекция для геообъектов группы.
                    collection = new ymaps.GeoObjectCollection(null, { preset: group.style }),
                // Контейнер для подменю.
                    submenu = $('<ul class="submenu"/>');

                // Добавляем коллекцию на карту.
                myMap.geoObjects.add(collection);

                // Добавляем подменю.
                menuItem
                    .append(submenu)
                    // Добавляем пункт в меню.
                    .appendTo(menu)
                    // По клику удаляем/добавляем коллекцию на карту и скрываем/отображаем подменю.
                    .find('a')
                    .toggle(function () {
                        myMap.geoObjects.remove(collection);
                        submenu.hide();
                    }, function () {
                        myMap.geoObjects.add(collection);
                        submenu.show();
                    });

                // Перебираем элементы группы.
                for (var j = 0, m = group.items.length; j < m; j++) {
                    createSubMenu(group.items[j], collection, submenu);
                }
            }

            function createSubMenu (item, collection, submenu) {
                // Пункт подменю.
                var submenuItem = $('<li><a href="#">' + item.name + '</a></li>'),
                // Создаем метку.
                    placemark = new ymaps.Placemark(item.center, { balloonContent: item.name });

                // Добавляем метку в коллекцию.
                collection.add(placemark);
                // Добавляем пункт в подменю.
                submenuItem
                    .appendTo(submenu)
                    // При клике по пункту подменю открываем/закрываем баллун у метки.
                    .find('a')
                    .toggle(function () {
                        placemark.balloon.open();
                    }, function () {
                        placemark.balloon.close();
                    });
            }

            // Добавляем меню в тэг BODY.
            menu.appendTo($('body'));
            // Выставляем масштаб карты чтобы были видны все группы.
            myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true});
        }
    </script>   
      

    <div id="yandex-map" class="stores__map">
        <div id="map" style="width:100%;height:100%"></div>
    </div>  
    <div class="stores__list">  
        <?foreach($arResult["ITEMS"] as $arItem):?>
        	<?
        	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        	?>
            <article class="stores__item">
                <div class="stores__gallery">
                    <h2 class="stores__address"><?=$arItem["NAME"]?> <?=$arItem["PROPERTIES"]["ADDRESS"]["~VALUE"]?>
                        <a target="_blank" href="<?=$arItem["PROPERTIES"]["HREF_FOR_MAP"]["VALUE"]?>" class="stores__address-link"><?=$arItem["PROPERTIES"]["ADDRESS"]["~VALUE"]?></a>
                    </h2>
                    <div class="gallery_img_wrap">
                        <div class="img_wrap stores__gallery">
                            <?//pr($arItem["DISPLAY_PROPERTIES"]["PHOTO_FASAD"]["FILE_VALUE"]);?>
                            <?if(isset($arItem["DISPLAY_PROPERTIES"]["PHOTO_FASAD"]["FILE_VALUE"][0])) {?>
                               <?foreach($arItem["DISPLAY_PROPERTIES"]["PHOTO_FASAD"]["FILE_VALUE"] as $arProperty):?>
                                   <? $file = CFile::ResizeImageGet($arProperty, array('width'=>200, 'height'=>185), BX_RESIZE_IMAGE_PROPORTIONAL, false); ?>
                                   <a href="<?=$arProperty["SRC"]?>" class="stores__image-link"><img src="<?=$file["src"]?>" class="stores__image"/></a>
                            <?endforeach;?>
                            <?} else {?>
                                <? $file = CFile::ResizeImageGet($arItem["DISPLAY_PROPERTIES"]["PHOTO_FASAD"]["FILE_VALUE"], array('width'=>200, 'height'=>185), BX_RESIZE_IMAGE_PROPORTIONAL, false); ?>
                                   <a href="<?=$arProperty["SRC"]?>" class="stores__image-link"><img src="<?=$file["src"]?>" class="stores__image"/></a>
                                <?}?>
                        </div>
                        <div class="contact__wrap">
                            <div class="stores__block">
                                <div class="wrap stores__wrap__phones">
                                    <div class="stores__title"><img src="https://belwooddoors.ru/upload/medialibrary/38c/67p3vfa438itp141djcpqj2jiusbk5oa.png" alt="">Телефон</div>
                                    <div class="stores__phone">
                                        <?=$arItem["DISPLAY_PROPERTIES"]["PHONES"]["DISPLAY_VALUE"]?>
                                    </div>
                                </div>
                                <div class="wrap stores__wrap__time">
                                    <div class="stores__title"><img src="https://belwooddoors.ru/upload/medialibrary/b1a/hxeexjgppok6ofkqtmw6474rfzzln4ha.png" alt="">Режим работы</div>
                                    <div class="stores__text">
                                        <?=$arItem["DISPLAY_PROPERTIES"]["WORKING"]["DISPLAY_VALUE"]?>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
                <div class="stores__block__widget">
                    <?=$arItem["DISPLAY_PROPERTIES"]["FEEDBACK"]["~VALUE"]["TEXT"]?>
                </div>
           </article>
           <div class="stores__item-line"></div>
        <?endforeach;?>
    </div>

    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    	<br /><?=$arResult["NAV_STRING"]?>
    <?endif;?>
</div>

<div class="section-block">
	<?= $arResult["SECTION"]["PATH"][0]["DESCRIPTION"];?>
</div>

<script>
    $(document).ready(function() {
        //$(window).on('scroll', function(){
        /*var keyFrame = 0;
        var timeFrame = 3000;
        var timeFrameOut = 0;
        var $iframe = $('iframe:eq('+keyFrame+')');
        $iframe.attr('src', $iframe.attr('data-src'));
            $('.stores__list iframe').on('load', function () {
                console.log($(this).index());
                console.log(keyFrame);
                //if($(this).index() == keyFrame) {
                    keyFrame++;
                    timeFrameOut = timeFrame;
                    if (keyFrame > 5) {
                        timeFrameOut = 4000 * keyFrame/2;
                    }
                    
                    setTimeout(function (){
                        console.log(keyFrame);
                        
                        $iframe = $('iframe:eq('+keyFrame+')');
                        if($iframe && $('.stores__list iframe').length > keyFrame) {
                            $iframe.attr('src', $iframe.attr('data-src'));
                        }
                    }, timeFrameOut);
                //}
                
            });
        //});

*/      var arrInd = [];
        $(window).scroll(function() {
            
            $('.stores__list iframe').each(function( index ) {
                //console.log(arrInd);
                $elem = elem_in_visible_area(this);
                if ( $elem && !arrInd.includes(index)) {
                    arrInd.push(index);
                    //console.log($elem);
                    $elem.attr('src', $elem.attr('data-src'));
                    //console.log($elem);
                }
            });
        })
    });

    function elem_in_visible_area(selector) {
    let elem_p = $(selector),
        elem_p_height = elem_p.height(),
        offset_top_el = elem_p.offset().top,
        offset_bottom_el = offset_top_el + elem_p.height(),
        scrolled = $(window).scrollTop(),
        scrolled_bottom = scrolled + $(window).height();
    if (scrolled_bottom > offset_top_el && offset_bottom_el > scrolled) {
        return elem_p;
    }
    return false;
}



</script>