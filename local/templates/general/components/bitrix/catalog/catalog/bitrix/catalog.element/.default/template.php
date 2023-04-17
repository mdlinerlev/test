<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//__($arResult);
$config = $arResult['PROPERTIES']['CONFIGURATION']['VALUE'];
$type = $arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID'];
$isDoor = in_array($type, array(TYPE_INTERIOR_DOORS, TYPE_EXTERIOR_DOORS));
$productInOrder = array_key_exists($arResult["ID"], (array)$_SESSION["BASKET"]["ORDER_ITEMS"]);
$arDinamicHits = array();
$dveri = strstr($arResult['SECTION']['SECTION_PAGE_URL'], 'mezhkomnatnye_dveri') || strstr($arResult['SECTION']['SECTION_PAGE_URL'], 'vkhodnye_dveri');
if(!$dveri && $arResult['ACTIVE'] == 'N'){
    header('Location: ' . $arResult['SECTION']['SECTION_PAGE_URL'], true, 301);
    exit;
}
?>

<?php
$ml_dekor = false;
if ($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID'] == TYPE_DEKOR) $ml_dekor = true;
?>

<? foreach ($arResult["OFFERS"] as $offer):?>
    <? foreach ($offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE"] as $index => $prop): ?>
        <?$hitColor = strlen($offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE_XML_ID"][$index]) > 7 ? "#ff652e" : $offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE_XML_ID"][$index];
        $arDinamicHits[] = array("HIT" =>$prop, "OFFER" => $offer["ID"], "COLOR" => $hitColor) ;?>
    <? endforeach;?>
<? endforeach; ?>

<?if ($arParams["IS_AJAX"]):?>
    <?CJSCore::Init("currency");?>
    <script src="/bitrix/templates/general/assets/js/scripts.js"></script>
    <script src="/bitrix/templates/general/components/jorique/catalog.section/similar_products/script.js"></script>
<?endif;?>

<div class="detail-product <?= !empty($arResult['OFFERS']) ? " detail-product-offers" : ""?>" data-product-id="<?= $arResult['ID'] ?>" <?="type-".$type?>
    <?
    $str_col = '';
    if (is_array($arResult["PROPERTIES"]["COLLECTION"]["VALUE_ENUM_ID"])) {
        foreach ($arResult["PROPERTIES"]["COLLECTION"]["VALUE_ENUM_ID"] as $index => $item) {
            $str_col .= $item.",";
        }
        ?> data-collections="<?=$str_col?>" <?
    } else  {
        ?> data-collections="<?=$arResult["PROPERTIES"]["COLLECTION"]["VALUE_ENUM_ID"]?>" <?
    }
    ?>
>
    <section class="product-top">
        <div class="content-container">
            <div class="product-top__title-container">
                <div class="product-top__title-inner">
                    <div class="product-top__title-row">
                        <h1 class="product-top__title" id="pagetitle1"><?= $arResult['META_TAGS']['TITLE'] ? : $arResult['NAME']; ?></h1>

                        <div class="product-top__instore" style="display:none;">
                            <?
                                offersIterator($arResult, function ($item, $dataString) {
                                    if ($item['CATALOG_QUANTITY']) {
                                        echo '<div class="product-top__availability product-top__availability--available"' . $dataString . '>'.GetMessage('CT_BCS_AVAILABLE').'</div>';
                                    } else {
                                        echo '<div class="product-top__availability_not not_product-top__availability--available"' . $dataString . '>'.GetMessage('CT_BCS_NOT_AVAILABLE').'</div>';
                                    }
                                });
                                offersIterator($arResult, function ($item, $dataString) {
                                    $compareUrl = $item['COMPARE_URL'] . '&ajax_action=Y';
                                    itc\CUncachedArea::show('productCompareBlock', array('id' => $item['ID'], 'dataString' => $dataString, 'url' => $compareUrl));
                                });
                            ?>
                        </div>
                    </div>
                    <div class="icon-best">
                    <div class="icon-tooltip">
                            <img src="/bitrix/templates/general/assets/img/1.png" alt="">
                            <div class="icon-tooltip-text">
                            Belwooddoors — 9-кратный лауреат конкурса «Лучшие товары РБ»
                            </div>
                            </div>
                        <div class="icon-tooltip">
                            <img src="/bitrix/templates/general/assets/img/2.png" alt="">
                            <div class="icon-tooltip-text">
                            Мы уверены в качестве продукции, поэтому гарантия на все товары — 2 года
                            </div>
                        </div>
                        <div class="icon-tooltip">
                            <img src="/bitrix/templates/general/assets/img/25.png" alt="">
                            <div class="icon-tooltip-text">
                            С 1999 года производим качественные двери из лучших материалов
                            </div>
                        </div>
                    </div>
                </div>
                <?
                offersIterator($arResult, function ($item, $dataString) {
                    $article = $item['PROPERTIES']['ARTICLE']['VALUE'];
                    $article = $article ? GetMessage('CT_BCS_ARTICLE', array('#ARTICLE#' => $article)) : false;
                    if ($article) {
                        echo '<div class="product-top__number"' . $dataString . '>' . $article . '</div>';
                    }
                });
                ?>
            </div>
        </div>
    </section>


    <?  if ($isDoor || ($ml_dekor == true)) {
        $bgString = '';
        if (!empty($arResult['BGS'])) {

            $first = current($arResult['BGS']);
            $bgImage = $first['PICTURE_DETAIL'];
            $bgColor = $first['PROPERTY_COLOR_VALUE'];

            $videoSmallImage = $first['PICTURE_SMALL_DETAIL'];

            $bgString = ' data-src="'.$bgImage['src'].'" style="background-image: url(' . SITE_TEMPLATE_PATH . '/preload.svg' . '); background-color: #' . $bgColor . ';"';
        }
        ?>
        <?global $confDoor;
        $confDoor = 'Распашная двойная';?>
        <section class="item_main">
            <div class="product_view">
                <div class="product_img <?=($arResult['PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arResult['PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1? 'double_image' : '');?> <?=$ml_dekor == true?'dekor':''?>">

                    <?if($arResult['PROPERTIES']['LINK_VALUE']['VALUE']){?>
                        <a href="<?=$arResult['PROPERTIES']['LINK_VALUE']['VALUE']?>"
                            <?if($arResult['PROPERTIES']['LINK_NEW_WINDOW']['VALUE'] == 'Y'){?>
                                target="_blank"
                            <?}?>
                           style="right: 0; margin-top: 0px; background-color: #ff652e; color: #fff; position: absolute; z-index: 20;"
                           class="sku-value select-sku-value size-value"
                        ><img src="<?= SITE_TEMPLATE_PATH ?>/img/svg/eye.svg" class="presentation">
                            <?=$arResult['PROPERTIES']['LINK_TEXT']['VALUE']?></a>
                    <?}?>

                    <div class="product-top__badge-container">
                        <?
                        $props = $arResult['PROPERTIES'];
                        $topBadges = '';

                        if ($props['SALELEADER']['VALUE']) {
                            $topBadges .= '<div class="catalog-item__label catalog-item__label--dark">'.GetMessage('CT_BCS_SALELEADER').'</div>';
                        }
                        if ($props['NEWPRODUCT']['VALUE']) {
                            $topBadges .= '<div class="catalog-item__label">'.GetMessage('CT_BCS_NEWPRODUCT').'</div>';
                        }
                        if ($props['FREE']['VALUE']) {
                            $topBadges .= '<div class="catalog-item__label catalog-item__label--dark">'.GetMessage('CT_BCS_FREE').'</div>';
                        }
                        if ($props['SPECIALOFFER']['VALUE']) {
                            $topBadges .= '<div class="catalog-item__label catalog-item__label--dark">'.GetMessage('CT_BCS_DISCOUNT_DIFF_PERCENT').'</div>';
                        }
                        foreach ($arDinamicHits as $hit) {
                            $topBadges .= '<div class="catalog-item__label dinamic-hit" data-dinamic-hit="'.$hit["OFFER"].'" style="--before-background: '.$hit['COLOR'].'; background-color: '.$hit['COLOR'].';">'.$hit["HIT"].'</div>';
                        }
                        echo $topBadges;
                        ?>
                        <?/* offersIterator($arResult, function ($item, $dataString) {
                            if ($item['PROPERTIES']['SKLAD']['VALUE'] == 'Y') {
                                echo '<div class="product-top__badge product-top__badge--sklad"'.$dataString.'>'.GetMessage('CT_BCS_SKLAD').'</div>';
                            }
                            ?>

                        <? }) */?>
                    </div>

                    <?offersIterator($arResult, function ($item, $dataString) use ($arResult, $type) {
                        global $confDoor;
                        $bigImage = $item['BIG_IMAGE'] ?: $arResult['BIG_IMAGE'];
                        if (!$bigImage) {
                          return;
                        }
                        global $item_img;
                        $item_img = $bigImage['src'];
                        $smallImage = $item['SMALL_IMAGE'] ?: $arResult['SMALL_IMAGE'];

                        /*$twoLeafImage = $item['BIG_TWO_LEAF_PHOTO'] ?: $arResult['BIG_TWO_LEAF_PHOTO'];
                        if ($twoLeafImage) {
                            $twoLeafSmallImage = $item['SMALL_TWO_LEAF_PHOTO'] ?: $arResult['SMALL_TWO_LEAF_PHOTO'];
                        }*/

                        # внешняя сторона
                        $innerImage = false;
                        if ($type == TYPE_EXTERIOR_DOORS) {
                            $innerImage = $item['BIG_INNER_PHOTO'] ?: $arResult['BIG_INNER_PHOTO'];
                            if ($innerImage) {
                                $innerSmallImage = $item['SMALL_INNER_PHOTO'] ?: $arResult['SMALL_INNER_PHOTO'];
                            }
                        }

                        $imgTitle = (
                            isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) && $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                            ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
                            : $arResult['NAME']
                        );
                        $imgAlt = (
                            isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']) && $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] != ''
                            ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
                            : $arResult['NAME']
                        );

                        ?>
                        <div class="product-door"<?= $dataString ?>>
                            <div class="product-preview__big-images">
                                <?if($arResult['PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arResult['PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1) {
                                    $bigOrig = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 398, 'height' => 485), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $bigOrig['src'] ?>"
                                         class="product-preview__door-image one-leaf-image"
                                         width="470" height="470" alt="<?= $imgAlt ?>" title="<?= $imgTitle?>">
                                <?}?>
                                <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $bigImage['src'] ?>"
                                     class="product-preview__door-image one-leaf-image"
                                     width="470" height="470" alt="<?= $imgAlt ?>" title="<?= $imgTitle?>">
                                <?php if ($innerImage) { ?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $innerImage['src'] ?>" class="product-preview__door-image inner-image hidden" width="470" height="470">
                                <?php } ?>

                            </div>

                            <div class="product-preview__small-images">

                                <?if($arResult['PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arResult['PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1) {
                                    $smallOrig = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array( 'width' => 244, 'height' => 244 ), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $smallOrig['src'] ?>" class="product-preview__door-image-small one-leaf-image" width="244" height="244">
                                <?}?>
                                <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $smallImage['src'] ?>" class="product-preview__door-image-small one-leaf-image" width="244" height="244">
                                <?/*if ($twoLeafImage) {?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $twoLeafSmallImage['src'] ?>" class="product-preview__door-image-small two-leaf-image hidden" width="244" height="244">
                                <?}*/?>
                                <?if ($innerImage) {?>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $innerSmallImage['src'] ?>" class="product-preview__door-image-small inner-image hidden" width="244" height="244">
                                <?}?>
                            </div>
                        </div>
                        <?
                    });
                    ?>

                    <?
                    $video = $arResult['PROPERTIES']['VIDEO']['VALUE'];
                    $photos = $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'];
                    ?>
                    <? if(!empty($video) || !empty($photos)): ?>
                        <div class="product__gallery">
                            <div class="product-gallery__inner toggle-block js-product-gallery__slider">
                                <?if(!empty($video)): ?>
								<?/* // Микроразметка coffeediz:schema.org.Video
                                    $dateTemp = ($arResult["DATE_CREATE"])?$arResult["DATE_CREATE"]:$arResult["DATE_ACTIVE_FROM"];
                                    $a = date_parse_from_format('d.m.Y G:i:s',$dateTemp);
                                    $timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['day'], $a['year']);
                                    $dateTemp1 = date('Y-m-d\TH:i:sO', $timestamp);

                                    $APPLICATION->IncludeComponent(
                                        "coffeediz:schema.org.Video",
                                        "myVideo",
                                        Array(
                                            "ALLOWCOUNTRIES" => "",    // Перечень стран, в которых доступно данное видео (В ОСТАЛЬНЫХ ЗАПРЕЩЕНО)
                                            "AUTHOR_PERSON_ADDITIONALNAME" => "",    // Отчество
                                            "AUTHOR_PERSON_EMAIL" => "",    // E-mail
                                            "AUTHOR_PERSON_FAMILYNAME" => "",    // Фамилия
                                            "AUTHOR_PERSON_IMAGEURL" => "",    // URL фото персоны
                                            "AUTHOR_PERSON_JOBTITLE" => "",    // Должность
                                            "AUTHOR_PERSON_NAME" => "",    // Имя
                                            "AUTHOR_PERSON_PHONE" => "",    // Телефон
                                            "AUTHOR_PERSON_URL" => "",    // URL страниц, связанных с персоной
                                            "AUTHOR_PERSON_URL_SAMEAS" => "",    // URL ОФИЦИАЛЬНЫХ страниц, связанных с персоной
                                            "CAPTION" => $arResult["NAME"],    // Подпись к видео
                                            "CONTENT_ID" => "",    // Идентификатор видео
                                            "CONTENT_URL" => $arResult["DETAIL_PAGE_URL"],    // Адрес, по которому доступен файл с видео-роликом
                                            "DESCRIPTION" => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]):strip_tags($arResult["PREVIEW_TEXT"]),    // Описание
                                            "DISALLOWCOUNTRIES" => "",    // Перечень стран, в которых НЕдоступно данное видео (В ОСТАЛЬНЫХ РАЗРЕШЕНО)
                                            "DUBBING" => "",    // Студия, дублировавшая видео
                                            "DURATION" => "100",    // Продолжительность видео (PTччHммMссS)
                                            "FEED_URL" => "",    // Адрес XML-фида для данной страницы
                                            "GENRE" => array(    // Жанр
                                                0 => "",
                                                1 => "",
                                            ),
                                            "IMAGEURL" => "",    // URL Оффициального Изображения (постера и т.п.)
                                            "IN_LANGUAGE" => "ru",    // Язык видео
                                            "IS_FAMILY_FRIENDLY" => "Y",    // Можно смотреть детям
                                            "IS_OFFICIAL" => "N",    // Официальное видео
                                            "KEYWORDS" => array(    // Ключевые слова, Теги
                                                0 => "двери межкомнатные, двери входные, двери от производителя",
                                            ),
                                            "LICENSE" => "Common license",    // Тип лицензии, по которой распространяется видео
                                            "NAME" => $arResult["NAME"],    // Название
                                            "PARAM_RATING_SHOW" => "N",    // Выводить рейтинг
                                            "PRODUCTCOMPANY_TYPE" => "Organization",    // Тип описания Компании-Производитель видео
                                            "PRODUCTIONCOUNTRY" => "3166-2:BY",    // Страна-производитель (в формате ISO 3166-1)
                                            "SHOW" => "Y",    // Не отображать на сайте
                                            "STATUS" => "published",    // Статус
                                            "SUBTITLE_IN_LANGUAGE" => "",    // Язык субтитров
                                            "SUBTITLE_URL" => "",    // Адрес, по которому расположен файл с субтитрами
                                            "THUNBNAIL_IMAGEURL" => $videoSmallImage["src"],    // URL Изображения предпросмотра
                                            "UPLOAD_DATE" => $dateTemp1,    // дата загрузки видео-ролика на сайт в формате ISO 8601 (ГГГГ-ММ-ДД)
                                            "URL" => str_replace("watch?v=","embed/",$arResult["PROPERTIES"]["VIDEO"]["VALUE"])."?enablejsapi=1",    // Ссылка на видео
                                            "COMPONENT_TEMPLATE" => ".default",
                                            "THUNBNAIL_IMAGE_NAME" => $arResult["NAME"],    // Название Изображения предпросмотра
                                            "THUNBNAIL_IMAGE_CAPTION" => $arResult["NAME"],    // Подпись к Изображению предпросмотра
                                            "THUNBNAIL_IMAGE_DESCRIPTION" => ($arResult["DETAIL_TEXT"])?$arResult["DETAIL_TEXT"]:$arResult["PREVIEW_TEXT"],    // Описание изображения предпросмотра
                                            "THUNBNAIL_IMAGE_HEIGHT" => "244",    // Высота изображения предпросмотра (px)
                                            "THUNBNAIL_IMAGE_WIDTH" => "113",    // Ширина изображения предпросмотра (px)
                                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_2" => "LocalBusiness",    // Тип Организации
                                            "PRODUCTCOMPANY_ORGANIZATION_NAME" => "ОДО «Беллесизделие»",    // Название компании
                                            "PRODUCTCOMPANY_ORGANIZATION_DESCRIPTION" => "Белорусская компания-производитель межкомнатных и входных дверей. ",    // Краткое описание компании
                                            "PRODUCTCOMPANY_ORGANIZATION_SITE" => "belwooddoors.by",    // Сайт компании
                                            "PRODUCTCOMPANY_ORGANIZATION_PHONE" => array(    // Телефон компании
                                                0 => "+375(17)388-15-58",
                                                1 => "+375(17)346-22-48",
                                                2 => "+375(44)779-07-72",
                                                3 => "+375(44)712-12-48",
                                                4 => "",
                                            ),
                                            "PRODUCTCOMPANY_ORGANIZATION_POST_CODE" => "220075",    // Почтовый индекс компании
                                            "PRODUCTCOMPANY_ORGANIZATION_COUNTRY" => "Беларусь",    // Страна компании
                                            "PRODUCTCOMPANY_ORGANIZATION_REGION" => "Минск и МО",    // Регион Компании
                                            "PRODUCTCOMPANY_ORGANIZATION_LOCALITY" => "",    // Город Компании
                                            "PRODUCTCOMPANY_ORGANIZATION_ADDRESS" => "ул. Промышленная, 10, комн. 20",    // Адрес компании
                                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_3" => "Store",    // Тип Организации
                                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_4" => "HomeGoodsStore",    // Тип Организации
                                        ),
                                        false,
                                        array(
                                            "HIDE_ICONS" => "N"
                                        )
									);*/?>
                                    <a href="<?= $video ?>" class="product-gallery__link product-gallery__link__video">
                                        <img src="/images/play.png" class="product-gallery__image" width="70" height="70"/>
                                    </a>
                                <? endif;?>
                                <? if (!empty($photos)): ?>
                                    <? foreach ($photos as $photoId):?>
                                        <a href="<?= $photoId['PICTURE']['src'] ?>" class="product-gallery__link">
                                            <img src="<?= $photoId['PICTURE_SMALL']['src'] ?>" class="product-gallery__image" width="70" height="70"/>
                                        </a>
                                    <? endforeach; ?>
                                <? endif;?>
                            </div>
                        </div>
                    <? endif;?>
                </div>

                <div class="product_params">

                    <div class="product_params_top">
                        <?if ($type == TYPE_INTERIOR_DOORS) {?>
                            <div class="product-center">
                                <div class="product_params__title">Конфигурация двери</div>
                                <?$arOrderConfigs = [1 => 'Распашная', 2 => 'Распашная двойная', 3 => 'Купе', 4 => 'Купе двойное'];

                                foreach ($arOrderConfigs as $config_name) {
                                    if ($arResult['PROPERTIES']['GLASS']['VALUE'] && isset($arResult['ALL_CONFIGS']['GLAZED'][$config_name])){
                                        $config_item = $arResult['ALL_CONFIGS']['GLAZED'][$config_name];
                                    }elseif (!$arResult['PROPERTIES']['GLASS']['VALUE'] && isset($arResult['ALL_CONFIGS']['NO_GLAZED'][$config_name])){
                                        $config_item = $arResult['ALL_CONFIGS']['NO_GLAZED'][$config_name];
                                    }else{
                                        $config_name = '';
                                    }
                                    $isActive = (!is_array($config_item) && $config_item == $config_name) ? true : false;

                                    switch ($config_name) {
                                        case 'Распашная':
                                            ?>
                                            <div class="tooltip1">
                                                <a href="<?= $isActive ? 'javascript:void(0);' : $config_item['DETAIL_PAGE_URL']?>" class="product-top__button img_button <?= $isActive ? 'active' : ''?>">
                                                    <img src="/bitrix/templates/general/images/gif/config_door_1.gif" title="<?= GetMessage('CT_BCS_CONFIG_TYPE_1')?>" width="45" height="45">
                                                </a>
                                            </div>
                                            <?break;
                                        case 'Распашная двойная':
                                            ?>
                                            <div class="tooltip1">
                                                <a href="<?= $isActive ? 'javascript:void(0);' : $config_item['DETAIL_PAGE_URL']?>" class="product-top__button img_button <?= $isActive ? 'active' : ''?>">
                                                    <img src="/bitrix/templates/general/images/gif/config_door_2.gif" title="<?= GetMessage('CT_BCS_CONFIG_TYPE_2')?>" width="45" height="45">
                                                </a>
                                            </div>
                                            <?break;
                                        case 'Купе':
                                            ?>
                                            <div class="tooltip1">
                                                <a href="<?= $isActive ? 'javascript:void(0);' : $config_item['DETAIL_PAGE_URL']?>" class="product-top__button img_button <?= $isActive ? 'active' : ''?>">
                                                    <img src="/bitrix/templates/general/images/gif/config_door_3.gif" title="<?= GetMessage('CT_BCS_CONFIG_TYPE_3')?>" width="45" height="45">
                                                </a>
                                            </div>
                                            <?break;
                                        case 'Купе двойное':
                                            ?>
                                            <div class="tooltip1">
                                                <a href="<?= $isActive ? 'javascript:void(0);' : $config_item['DETAIL_PAGE_URL']?>" class="product-top__button img_button <?= $isActive ? 'active' : ''?>">
                                                    <img src="/bitrix/templates/general/images/gif/config_door_4.gif" title="<?= GetMessage('CT_BCS_CONFIG_TYPE_4')?>" width="45" height="45">
                                                </a>
                                            </div>
                                            <?break;
                                    }
                                }?>
                            </div>
                        <?}?>
                    </div>

                    <?
                    $propsOrder = array('SIZE', 'COLOR', 'GLASS_COLOR');

                    # размеры
                    if ($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
                        foreach ($arResult['SKU_PROPS'] as $prop) {
                            if ($prop['CODE'] == 'SIZE') {
                                if ($prop['VALUES']) {
                                    $thisVals = array();
                                    $arOffersValues = array();
                                    foreach ($prop['VALUES'] as $val) {
                                        # проверяем, есть ли такое предложение
                                        foreach ($arResult['JS_OFFERS'] as $offer) {

                                            if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                if($val['ID'] == 92) {
                                                    $thisVals['_' . $val['ID']] = ['NAME' =>$val['NAME'], 'SORT' => 550];
                                                } else {
                                                    $thisVals['_' . $val['ID']] = ['NAME' =>$val['NAME'], 'SORT' => $offer['SORT']];
                                                }

                                                break;
                                            }
                                        }
                                    }

                                    foreach ($arPropRez as $k => $v){
                                        foreach ($thisVals as &$v1){
                                            if($k == $v1['NAME']){
                                                $v1['SORT'] = $v;
                                            }
                                        }
                                    }
                                    uasort($thisVals, function($a, $b){
                                        $a = $a['SORT'];
                                        $b = $b['SORT'];
                                        if ($a == $b) {
                                            return 0;
                                        }
                                        return ($a < $b) ? -1 : 1;
                                    });
									if($_GET['dev'] == 'Y'){echo '<pre>', print_r($thisVals),'</pre>';}
                                    if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                        ?>
                                        <div class="first-base product-filter__select-container ">
                                            <div class="product_params__title">Размер <?php if($ml_dekor==false) echo "двери"; ?></div>
                                            <div class="sku-wrapper">
                                                <div class="filter__select-box filter-size-canvas">
                                                    <?$checkColor = 0?>
                                                    <?foreach ($thisVals as $key => $val) {
                                                        if(stripos($val['NAME'], 'нестанда') !== false) $checkColor = 1;
                                                        if(stripos($val['NAME'], 'нестанда') === false || $USER->GetID() > 0)
                                                        echo '<a class="sku-value select-sku-value size-value" href="javascript:void(0);" data-prop-offer="'.$arOffersValues[$key].'" data-size="'. $checkColor .'" data-id="' . $prop['ID'] . '_' . substr($key, 1) . '">' . $val['NAME'] . '</a><br>';
                                                    }?>
                                                </div>
                                                <?
                                                global $USER;
                                                if($checkColor && $USER->GetID() > 0) {?>
                                                    <div class="hidden select-size">
                                                        <div>Выберите размер двери</div>
                                                        <div class="inputs-d">
                                                            <div>Высота</div>
                                                            <input
                                                                    type="number"
                                                                    id="heigth-door"
                                                                    placeholder="от 1800 до 2400"
                                                                    min="1800"
                                                                    max="2400"
                                                                    step="50"
                                                                    onBlur="checkHeigth(this)">
                                                        </div>
                                                        <div class="inputs-d">
                                                            <div>Ширина</div>
                                                            <input
                                                                    type="number"
                                                                    id="width-door"
                                                                    placeholder="от 400 до 1000"
                                                                    min="400"
                                                                    max="1000"
                                                                    step="50"
                                                                    onBlur="checkHeigth(this)">
                                                        </div>
                                                    </div>
                                                <?}?>
                                            </div>
                                        </div>

                                        <div class="second-base product-filter__select-container product-filter__select-container--second">
                                            <div class="hidden">
                                                <label for="product-size2" class="product-filter__label product_params__title"><?= GetMessage('CT_BCS_SIZE_POLOTNA2')?></label>
                                                <select id="product-size2" class="product-filter__select product-filter__select--size"></select>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }
                                //break;
                            }
                            if ($prop['CODE'] == 'SIZE_DOOR') {
                                if ($prop['VALUES']) {
                                    $thisVals = array();
                                    $arOffersValues = array();
                                    foreach ($prop['VALUES'] as $val) {
                                        # проверяем, есть ли такое предложение
                                        foreach ($arResult['JS_OFFERS'] as $offer) {

                                            if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                $thisVals['_' . $val['ID']] = $val['NAME'];
                                                break;
                                            }
                                        }
                                    }
                                    if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                        ?>
                                        <div class="first-base product-filter__select-container ">
                                            <div class="product_params__title"><?=$prop['NAME']?></div>
                                            <div class="sku-wrapper">
                                                <div class="filter__select-box filter-size-canvas">
                                                    <?foreach ($thisVals as $key => $val) {
                                                        //if(stripos($val, 'нестан') === false || $USER->isAdmin)
                                                        echo '<a class="sku-value select-sku-value size-value" href="javascript:void(0);" data-prop-offer="'.$arOffersValues[$key].'" data-id="' . $prop['ID'] . '_' . substr($key, 1) . '">' . $val . '</a><br>';
                                                    }?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="second-base product-filter__select-container product-filter__select-container--second">
                                            <div class="hidden">
                                                <label for="product-size2" class="product-filter__label product_params__title"><?= GetMessage('CT_BCS_SIZE_POLOTNA2')?></label>
                                                <select id="product-size2" class="product-filter__select product-filter__select--size">м</select>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }
                            }
                            if ($prop['CODE'] == 'WIDTH_PANEL') {
                                if ($prop['VALUES']) {
                                    $thisVals = array();
                                    $arOffersValues = array();
                                    foreach ($prop['VALUES'] as $val) {
                                        # проверяем, есть ли такое предложение
                                        foreach ($arResult['JS_OFFERS'] as $offer) {

                                            if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                $thisVals['_' . $val['ID']] = $val['NAME'];
                                                break;
                                            }
                                        }
                                    }
                                    if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                        ?>
                                        <div class="first-base product-filter__select-container ">
                                            <div class="product_params__title"><?=$prop['NAME']?></div>
                                            <div class="sku-wrapper">
                                                <div class="filter__select-box filter-size-canvas">
                                                    <?foreach ($thisVals as $key => $val) {
                                                        echo '<a class="sku-value select-sku-value size-value" href="javascript:void(0);" data-prop-offer="'.$arOffersValues[$key].'" data-id="' . $prop['ID'] . '_' . substr($key, 1) . '">' . $val . '</a><br>';
                                                    }?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="second-base product-filter__select-container product-filter__select-container--second">
                                            <div class="hidden">
                                                <label for="product-size2" class="product-filter__label product_params__title"><?= GetMessage('CT_BCS_SIZE_POLOTNA2')?></label>
                                                <select id="product-size2" class="product-filter__select product-filter__select--size"></select>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }
                            }
                        }
                    }
                    ?>

                    <?if ($type == TYPE_EXTERIOR_DOORS){

                        # сторона открывания
                        if ($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
                            foreach ($arResult['SKU_PROPS'] as $prop) {
                                if ($prop['CODE'] == 'SIDE') {
                                    if ($prop['VALUES']) {
                                        $thisVals = array();
                                        $arOffersValues=array();
                                        foreach ($prop['VALUES'] as $val) {
                                            # проверяем, есть ли такое предложение
                                            foreach ($arResult['JS_OFFERS'] as $offer) {
                                                if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                    $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                    $thisVals['_' . $val['ID']] = $val['NAME'];
                                                    break;
                                                }
                                            }
                                        }
                                        if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                            ?>
                                            <div class="product-filter__door-open-side">
                                                <span class="product-filter__label product_params__title"><?= GetMessage('CT_BCS_STORONA_OPEN')?></span>
                                                <div class="product-filter__door-type sku-wrapper">
                                                    <?foreach ($thisVals as $key => $val) {
                                                        $ke = $key;
                                                        $key = substr($key, 1);
                                                        if (!in_array($key, array(OPEN_SIDE_LEFT, OPEN_SIDE_RIGHT))) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <a data-id="<?= $prop['ID'] . '_' . $key ?>" data-prop-offer="<?=$arOffersValues[$ke]?>" class="sku-value side-type product-filter__type product-filter__type--<?= ($key == OPEN_SIDE_LEFT) ? 'left' : 'right' ?>">
                                                            <?= $val ?>
                                                        </a>
                                                        <?
                                                    }?>
                                                </div>
                                            </div>
                                            <?
                                        }
                                    }
                                    break;
                                }
                            }
                        }

                    }?>

                    <?if ($arResult['GLASS_REF']) { ?>
                        <div class="product-filter__door-type">
                            <div class="product_params__title">Остекление</div>
                            <?if ($arResult['PROPERTIES']['GLASS']['VALUE']) { ?>
                                <a href="javascript:void(0);" class="glass-type product-filter__type active">
                                    <?= GetMessage('CT_BCS_GLASS_REF_TYPE_1')?>
                                </a>
                                <a href="<?= $arResult['GLASS_REF']['DETAIL_PAGE_URL'] ?>" class="glass-type product-filter__type">
                                    <?= GetMessage('CT_BCS_GLASS_REF_TYPE_2')?>
                                </a>
                            <?} else {?>
                                <a href="<?= $arResult['GLASS_REF']['DETAIL_PAGE_URL'] ?>" class="glass-type product-filter__type">
                                    <?= GetMessage('CT_BCS_GLASS_REF_TYPE_1')?>
                                </a>
                                <a href="javascript:void(0);" class="glass-type product-filter__type active">
                                    <?= GetMessage('CT_BCS_GLASS_REF_TYPE_2')?>
                                </a>
                            <?}?>
                        </div>
                    <?}?>

                    <?if ($arResult['OFFERS'] && $arResult['SKU_PROPS']) {

                        $skuMap = array(
                          'COLOR' => array('NAME' => GetMessage('CT_BCS_SKU_COLOR'), 'CLASS' => 'product-filter__color filter-main-color'),
                          'GLASS_COLOR' => array('NAME' => GetMessage('CT_BCS_SKU_GLASS_COLOR'), 'CLASS' => 'product-filter__color product-filter__color--second filter-glass-color'),
                          'COLOR_OUT' => array('NAME' => GetMessage('CT_BCS_SKU_COLOR_OUT'), 'CLASS' => 'product-filter__color'),
                          'COLOR_IN' => array('NAME' => GetMessage('CT_BCS_SKU_COLOR_IN'), 'CLASS' => 'product-filter__color product-filter__color--second'),
                          'HARDWARE_COLOR' => array('NAME' => 'Цвет фурнитуры', 'CLASS' => 'product-filter__color product-filter__color--second'),
                        );

                        if($ml_dekor==true) $skuMap["COLOR"]["NAME"] = "Цвет";

                        foreach ($skuMap as $skuProp => $sku) {
                            foreach ($arResult['SKU_PROPS'] as $prop) {
                                if ($prop['CODE'] == $skuProp) {
                                    if ($prop['VALUES']) {
                                        $thisVals = array();
                                        $arOffersValues = array();
                                        foreach ($prop['VALUES'] as $val) {
                                            # проверяем, есть ли такое предложение
                                            foreach ($arResult['JS_OFFERS'] as $offer) {
                                                if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                    $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                    $thisVals['_' . $val['ID']] = ['NAME' =>$val['NAME'], 'SORT' => $offer['SORT'],'XML' => $val['XML_ID']];
                                                    break;
                                                }
                                            }
                                        }

                        foreach ($arPropRez as $k => $v){
                            foreach ($thisVals as &$v1){
                                if($k == $v1['XML']){
                                    $v1['SORT'] = $v;
                                }
                            }
                        }
                        uasort($thisVals, function($a, $b){
                            $a = $a['SORT'];
                            $b = $b['SORT'];
                            if ($a == $b) {
                                return 0;
                            }
                            return ($a < $b) ? -1 : 1;
                        });
                                        if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                            ?>
                                            <div class="<?= $sku['CLASS'] ?>">
                                                <div class="product_params__title"><?= $sku['NAME'] ?></div>
                                                <div class="product-filter-color__inner sku-wrapper">
                                                    <?$checkColor = 0?>
                                                    <?foreach ($thisVals as $key => $val) {
                                                        $checkRal = 0;
                                                        if(stripos($val['NAME'], 'заказ') !== false) {
                                                            $checkColor = 1;
                                                            $checkRal = 1;
                                                        }
                                                        $colorId = substr($key, 1);
                                                        $colorFile =  $prop['VALUES'][$colorId]['PICT_SMALL']['src'];
                                                        if(!$colorFile){
                                                            $colorFile =  '/upload/no-glass.png';
                                                            if($skuProp != 'HARDWARE_COLOR')
                                                            $val['NAME'] = 'Без cтекла';
                                                        }
                                                        if(stripos($val['NAME'], 'заказ') === false || $USER->GetID() > 0)
                                                        echo '
                                                            <div class="tooltip1">
                                                                <a data-id="' . $prop['ID'] . '_' . $colorId . '" class="sku-value product-filter-color__link" '.($prop['CODE'] == 'COLOR_IN' ? ' data-inner="true" ' : '').' title="" data-ral="'. $checkRal .'">
                                                                    <img src="' . SITE_TEMPLATE_PATH . '/preload.svg" data-src="' . $colorFile . '" alt="' . $val['NAME'] . '" width="32" height="32"/>
                                                                    <span class="tooltiptext">' . $val['NAME'] . '</span>
                                                                </a>
                                                            </div>';
                                                    }?>
                                                    <?if($checkColor && $USER->GetID() > 0) {?>
                                                        <?php
                                                        $hlID = 7;
                                                        $fieldName = "UF_GROUP";
                                                        $arValues = HLHelpers::getInstance()->getFieldValues($fieldName);
                                                        $group = [];
                                                        foreach ($arValues as $arGroup) {
                                                            $group[$arGroup['ID']] = $arGroup['VALUE'];
                                                        }
                                                        $arHlElements = HLHelpers::getInstance()->getElementList($hlID, [], ['UF_GROUP' => 'ASC']);
                                                        foreach ($arHlElements as $elem) {
                                                            $colorRef[$group[$elem['UF_GROUP']]][] = $elem;
                                                        }?>

                                                        <div class="bx-filter-block hidden color-ral" data-role="bx_filter_block">
                                                            <div>Выберите цвет или введите RAL</div>
                                                            <input type="text" id="test-razmer" placeholder="1000" onBlur="checkColor(this)">
                                                            <div class="bx-filter-parameters-box-container">
                                                                <?foreach ($colorRef as $group => $list) {?>
                                                                    <div class="filters__colors">
                                                                        <div class="filters__color-block">
                                                                            <div style="background-color: <?=$list[0]['UF_COLOR']?>;float:none" class="filters-color-block__checkbox checkbox checkbox-group">
                                                                                <input type="checkbox" name="<?=$group?>"  value="Y">
                                                                                <label for="<?=$group?>" class="tooltip-link tooltip-top">
                                                                                    <div class="filters-color-block__tooltip tooltip"><?=$group?></div>
                                                                                </label>
                                                                            </div>
                                                                            <a class="filters-color-block__title"><?=$group?></a>
                                                                            <div class="filters-color-block__inner">
                                                                                <div class="filters-color-block__list">
                                                                                    <?foreach ($list as $color) {?>
                                                                                        <div style="background-color:  <?=$color['UF_COLOR']?>"
                                                                                             class="filters-color-block__checkbox checkbox checkbox-color">
                                                                                            <input type="checkbox" name="<?=$color['UF_COLOR_RAL']?>" value="<?=$color['UF_COLOR_RAL']?>">
                                                                                            <label for="<?=$color['UF_COLOR_RAL']?>" class="tooltip-link tooltip-top">
                                                                                                <div class="filters-color-block__tooltip tooltip"><?=$color['UF_COLOR_NAME']?> (<span><?=$color['UF_COLOR_RAL']?></span>)</div>
                                                                                            </label>
                                                                                        </div>
                                                                                    <?}?>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                <?}?>
                                                            </div>
                                                            <div style="clear: both"></div>
                                                        </div>

                                                        <script>
                                                            $('.ajax-popup-link-color2').magnificPopup({
                                                                type: 'ajax',
                                                                closeOnBgClick: false
                                                            });
                                                            $(document).on('click', '.checkbox-color', function() {
                                                                setTimeout(function (){
                                                                    setDetailPrices(true,30);
                                                                }, 100);

                                                                console.log('cccccccccccclick');
                                                                $('#test-razmer').val($(this).children('[type="checkbox"]').val());
                                                                $('.color-ral .checkbox').removeClass('has-checked');
                                                                $(this).addClass('has-checked');
                                                                $(this).parents('.filters-color-block__inner').siblings('.checkbox-group').addClass('has-checked');
                                                            });
                                                        </script>
                                                    <?}?>
                                                </div>
                                            </div>
                                            <?
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                    }?><!---->
                    <? if(!empty($arResult['ICON_PROPERTIES'])):?>
                        <div class="first-base product-filter__select-container characteristics-el">
                            <span class="product_params__title">Повышенная: </span>
                            <span class="characteristics-el-span">
                                <?foreach ($arResult['ICON_PROPERTIES'] as $val) {?>
                                    <a href="<?=$val['LINK'];?>" target="_blank"><?=trim($val['NAME']);?></a><?if($val !== end($arResult['ICON_PROPERTIES'])):?>, <?endif?>
                                <? }?>
                            </span>
                        </div>
                    <? endif ?>



                </div>

                <div class="product_order">
                    <div class="product-filter__submit">

                        <? if($arResult['ACTIVE'] != 'N'):?>
                            <div class="product-top__instore" style="display:none;">
                                <?
                                    offersIterator($arResult, function ($item, $dataString) {
                                        if ($item['CATALOG_QUANTITY']) {
                                            echo '<div class="product-top__availability product-top__availability--available"' . $dataString . '>'.GetMessage('CT_BCS_AVAILABLE').'</div>';
                                        } else {
                                            echo '<div class="product-top__availability_not not_product-top__availability--available"' . $dataString . '>'.GetMessage('CT_BCS_NOT_AVAILABLE').'</div>';
                                        }
                                    });
                                    offersIterator($arResult, function ($item, $dataString) {
                                        $compareUrl = $item['COMPARE_URL'] . '&ajax_action=Y';
                                        itc\CUncachedArea::show('productCompareBlock', array('id' => $item['ID'], 'dataString' => $dataString, 'url' => $compareUrl));
                                    });
                                ?>
                            </div>

                            <?if ($type == TYPE_INTERIOR_DOORS  || ($type == TYPE_EXTERIOR_DOORS && count($arResult['COMPLECT']) > 0)) { ?>
                                <div class="product-filter__price-block">
                                    <?offersIterator($arResult, function ($item, $dataString) use ($arParams) {
                                        ?>
                                        <div class="product-filter-submit__prices"<?= $dataString ?>>
                                            <span class="product-filter-submit__price product-filter-submit__price--new">
                                                <?= ($item['CONFIG'] == 'Купе' || $item['CONFIG'] == 'Купе двойное') ? "от&nbsp" : "";?>
                                                <span class="total-price"><?= SaleFormatCurrency($item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'], MAIN_CURRENCY, true) ?></span>
                                                <?=MAIN_CURRENCY_TEXT?>
                                            </span>
                                            <? if ($item['MIN_PRICE']['VALUE_NOVAT'] > $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT']):?>
                                                <span class="product-filter-submit__price product-filter-submit__price--old" data-old-price="<?= SaleFormatCurrency($item['MIN_PRICE']['VALUE_NOVAT'], MAIN_CURRENCY, true) ?>">
                                                    <?= ($item['CONFIG'] == 'Купе' || $item['CONFIG'] == 'Купе двойное') ? "от&nbsp" : "";?>
                                                    <span class="total-price_old" data-old-price="<?= SaleFormatCurrency($item['MIN_PRICE']['VALUE_NOVAT'], MAIN_CURRENCY, true) ?>">
                                                        <?= SaleFormatCurrency($item['MIN_PRICE']['VALUE_NOVAT'], MAIN_CURRENCY, true) ?>
		                                            </span>
                                                    <?=MAIN_CURRENCY_TEXT?>
                                                    <span class="product-filter-price-tabs__badge">-<?= round((1 - $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] / $item['MIN_PRICE']['VALUE_NOVAT']) * 100) ?><?=GetMessage("CT_BCS_FREE_ICON")?></span>
                                                </span>
                                            <?endif;?>
                                            <? if ($arParams['USER']) { ?>
                                                <span class="amount_text">В наличии: <?= $item['CATALOG_QUANTITY'] ?> <?= $item['CATALOG_MEASURE_NAME'] ?></span>
                                            <? } ?>
                                        </div>
                                        <?
                                    });
                                    ?>

                                    <div class="product-filter-submit__quantity quantity">
                                        <div class="quantity__container">
                                            <label for="total-quantity-input" class="sr-only"><?=GetMessage("CT_COMPLECT_COUNT")?></label>
                                            <a class="quantity__button quantity__button--minus">-</a>
                                            <input <?= $type == TYPE_INTERIOR_DOORS ? ' data-max="7"' : '' ?> type="text"
                                                value="1"
                                                id="total-quantity-input"
                                                maxlength="5"
                                                class="quantity__input not-auto-init"
                                            />
                                            <a class="quantity__button quantity__button--plus">+</a>
                                        </div>
                                    </div>
                                </div>

                                <?
                                $img = $arResult['BIG_IMAGE']['src'];
                                if(!empty($arResult['OFFERS'][0]['BIG_IMAGE'])){
                                    $img = $arResult['OFFERS'][0]['BIG_IMAGE']['src'];
                                }

                                $sku = $arResult['PROPERTIES']['ARTICLE']['VALUE'];
                                if(!empty($arResult['OFFERS'])){
                                    $sku = $arResult['OFFERS'][0]['PROPERTIES']['ARTICLE']['VALUE'];
                                }

                                $APPLICATION->IncludeComponent(
                                    "coffeediz:schema.org.Product",
                                    "myProduct",
                                    Array(
                                        "AGGREGATEOFFER" => "N",    // Набор из нескольких предложений
                                        "DESCRIPTION" => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]): (($arResult["PREVIEW_TEXT"])? strip_tags($arResult["PREVIEW_TEXT"]) : $arResult["NAME"] ) ,    // Описание Товара
                                        "ITEMAVAILABILITY" => "InStock",    // Доступность
                                        "ITEMCONDITION" => "NewCondition",    // Состояние товара
                                        "NAME" => $arResult['NAME'],    // Название Товара
                                        "IMAGE" => $img,
                                        "URL" => $arResult["DETAIL_PAGE_URL"],
                                        "SKU" => $sku,
                                        "PARAM_RATING_SHOW" => "N",    // Выводить рейтинг
                                        "PAYMENTMETHOD" => array(    // Способ оплаты
                                            0 => "VISA",
                                            1 => "MasterCard",
                                            2 => "Cash",
                                            3 => "CheckInAdvance",
                                        ),
                                        "PRICE" => ceil($arResult["OFFERS"][0]["MIN_PRICE"]["DISCOUNT_VALUE_NOVAT"]),    // Цена
                                        "PRICECURRENCY" => MAIN_CURRENCY,    // Валюта
                                        "SHOW" => "Y",    // Не отображать на сайте
                                    ),
                                    false
                                );?>

                                <div class="product-filter__price-tabs">
                                    <div class="detail-price-base product-filter-price-tabs__tab active  js-view-order-toggler">
                                        <?if ($config == 'Купе' || $config == 'Купе двойное'):?>
                                            <div class="product-filter-price-tabs__title">
                                                <?=GetMessage('CT_ZA_COMPLECT')?>
                                            </div>
                                        <?else:?>
                                            <?if($arResult['XML_ID'] == 'c7e2de6b-77ae-11ed-92c3-000c295257b20' || $arResult['XML_ID'] == '527d435d-2448-11ed-8f71-005056bb745a0') {?>
                                                <div class="product-filter-price-tabs__title leaf-switch-text"
                                                     data-oneleaf-text="<?=GetMessage('CT_ZA_POLOTNO3')?>" data-twoleaf-text="<?=GetMessage('CT_ZA_POLOTNO2')?>"><?=GetMessage('CT_ZA_POLOTNO3')?>
                                                </div>
                                            <?} else {?>
                                                <div class="product-filter-price-tabs__title leaf-switch-text"
                                                     data-oneleaf-text="<?=GetMessage('CT_ZA_POLOTNO')?>" data-twoleaf-text="<?=GetMessage('CT_ZA_POLOTNO2')?>"><?=GetMessage('CT_ZA_POLOTNO')?>
                                                </div>
                                            <?}?>
                                        <?endif;?>
                                        <? offersIterator($arResult, function ($item, $dataString) { ?>
                                            <? $discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT']; ?>
                                            <div<?= $dataString ?>>
                                                <div class="product-filter-price-tabs__discount"
                                                     data-base-price="<?= $discountPrice ?>">
                                                    <?= ($item['CONFIG'] == 'Купе' || $item['CONFIG'] == 'Купе двойное') ? "от&nbsp" : "";?>
                                                    <div class="product-filter-price-tabs__price product-filter-price-tabs__price--new left-base-price">
                                                        <?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <? }); ?>
                                    </div>
                                    <?foreach ( $arResult['COMPLECT'] as $index => $complect):?>
                                        <div class="product-filter-price-tabs__tab-wrap  fade-block  fade-block--popup  js-fade  js-accordion">
                                            <div class="detail-price-complect product-filter-price-tabs__tab tooltip-link tooltip-top  tooltip-right  js-view-order-toggler">
                                                <div class="product-filter-price-tabs__title"><?= $complect['NAME'] ? : GetMessage("CT_ZA_COMPLECT")?></div>
                                                <?
                                                offersIterator($arResult, function ($item, $dataString) use ($index) {
                                                    $complectPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
                                                    foreach ($item['COMPLECT'][$index]['ITEMS'] as $key => $el){
                                                        if(empty($el['NAME'])) continue;
                                                        $complectPrice += $el['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] * $el['COUNT'];
                                                    }
                                                    ?>
                                                    <div class="product-filter-price-tabs__discount"<?= $dataString ?>>
                                                        <?= ($item['CONFIG'] == 'Купе' || $item['CONFIG'] == 'Купе двойное') ? "от&nbsp" : "";?>
                                                        <div class="product-filter-price-tabs__price product-filter-price-tabs__price--new complect-full-price" data-base-price="<?= $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] ?>">
                                                            <?= SaleFormatCurrency($complectPrice, MAIN_CURRENCY, true); ?> <?=MAIN_CURRENCY_TEXT?>
                                                        </div>
                                                    </div>
                                                    <?
                                                });
                                                ?>

                                                <a class="product-filter-price-tabs__configure  fa fa-angle-down  js-accordion__toggler"></a>
                                            </div>

                                            <div class="product-filter__complect  js-accordion__content">
                                                <?offersIterator($arResult, function ($item, $dataString) use ($index) { ?>
                                                    <div class="product-filter-complect__inner complect-inner"<?= $dataString ?> data-show="<?=!empty($item['COMPLECT'][$index]['ITEMS'])?>">
                                                        <table class="product-filter-complect__table">
                                                            <tr class="product-filter-complect__row">
                                                                <td class="product-filter-complect__cell product-filter-complect__cell--title">
                                                                    <?=GetMessage("CT_POLOTNO")?>
                                                                </td>
                                                                <td class="product-filter-complect__cell product-filter-complect__cell--price">
                                                                    <div <?= $dataString?>><?= SaleFormatCurrency($item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'], MAIN_CURRENCY, false)?></div>
                                                                </td>
                                                                <td class="product-filter-complect__cell product-filter-complect__cell--quantity quantity">
                                                                    <div class="quantity__container simple-quantity">1</div>
                                                                </td>
                                                            </tr>
                                                            <? foreach ($item['COMPLECT'][$index]['ITEMS'] as $key => $el): if(empty($el['NAME'])) continue;?>
                                                                <tr class="product-filter-complect__row" title="<?= $el['TITLE_NAME'] ?>">
                                                                    <td class="product-filter-complect__cell product-filter-complect__cell--title"><?= $el['NAME'] ?></td>
                                                                    <td class="product-filter-complect__cell product-filter-complect__cell--price"><?= SaleFormatCurrency($el['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'], $el['PRICE']['RESULT_PRICE']['CURRENCY']); ?></td>
                                                                    <td class="product-filter-complect__cell product-filter-complect__cell--quantity quantity">
                                                                        <div class="quantity__container">
                                                                            <label for="quantity-input" class="sr-only"><?=GetMessage("CT_COMPLECT_COUNT")?></label>
                                                                            <a class="quantity__button quantity__button--minus">-</a>
                                                                            <input data-code="<?= $key ?>" type="text"
                                                                                data-id="<?= $el['ID'] ?>"
                                                                                data-price="<?= $el['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] ?>"
                                                                                data-defval="<?= $el['COUNT'] ?>"
                                                                                value="<?= $el['COUNT'] ?>" maxlength="3"
                                                                                class="quantity__input not-auto-init complect-count-input"
                                                                            />
                                                                            <a class="quantity__button quantity__button--plus">+</a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?endforeach;?>
                                                        </table>
                                                    </div>
                                                <?});?>
                                            </div>


                                            <? if(!empty($complect['HINT'])): ?>
                                                <div class="fade-block__toggler  js-fade__toggler">
                                                    <svg class="icon  icon--info"><use xlink:href="#icon-info"></use></svg>
                                                </div>
                                                <div class="fade-block__content  js-fade__content">
                                                    <div class="fade-block__toggler  fade-block__toggler--close  js-fade__toggler"></div>
                                                    <? includeEditHtmlFile($complect['HINT'], 'edit');?>
                                                </div>
                                            <? endif; ?>
                                        </div>
                                    <?endforeach;?>
                                </div>

                            <?} else {?>

                                <div class="product-filter__price">
                                    <?offersIterator($arResult, function ($item, $dataString) use ($arParams) {
                                        $price = $item['MIN_PRICE']['VALUE_NOVAT'];
                                        $discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
                                        ?>
                                        <div <?= $dataString ?>>
                                            <div class="product-filter-price__discount">
                                                <span class="product-filter-price-tabs__price product-filter-price-tabs__price--new" data-base-price="<?= $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] ?>">
                                                    <?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY, true) ?>
                                                </span>
                                                <?=MAIN_CURRENCY_TEXT?>
                                            </div>

                                            <?if ($price != $discountPrice) { ?>
                                                <div class="product-filter-price-tabs__base">
                                                    <span class="product-filter-price-tabs__number total-price_old"><?= SaleFormatCurrency($price, MAIN_CURRENCY, true) ?></span>
                                                    <?=MAIN_CURRENCY_TEXT?>
                                                    <span class="product-filter-price-tabs__badge">-<?= round((1 - $discountPrice / $price) * 100) ?><?=GetMessage("CT_BCS_FREE_ICON")?></span>
                                                </div>
                                            <?}?>
                                            <? if ($arParams['USER']) { ?>
                                                <span class="amount_text">В наличии: <?= $item['CATALOG_QUANTITY'] ?> <?= $item['CATALOG_MEASURE_NAME'] ?></span>
                                            <? } ?>
                                        </div>
                                        <?
                                    });
                                    ?>
                                </div>

                            <?}?>

                        <?else: ?>

                            <div class="product-top__availability product-top__availability--no-active">
                                <!--?= GetMessage('CT_BCS_NO_ACTIVE')?-->Модель доступна для заказа при количестве более 50 шт
                            </div>

                        <?endif;?>


                        <? if(!empty($arResult['BANNER'])):?>
                            <div class="product-banner">
                                <? foreach($arResult['BANNER'] as $banner): ?>
                                    <a href="<?= $banner['PROPERTY_LINK_VALUE']?>" target="_self">
                                        <img src="<?= $banner['PREVIEW_PICTURE']?>" width="350" alt="<?= $banner['NAME']?>"/>
                                    </a>
                                <? endforeach; ?>
                            </div>
                        <?endif;?>


                        <? if($arResult['ACTIVE'] != 'N'):?>

                            <?if ($config == 'Купе' || $config == 'Купе двойное'):?>
                                <div class="order-splittable-door">
                                    <script data-b24-form="click/31/uxr5zk" data-skip-moving="true">
                                        (function(w,d,u){
                                            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                                            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                                        })(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_31_uxr5zk.js');
                                    </script>
                                    <div class="popup-link nowrap  button  button--secondary  button--w-icon">
                                        <?=  GetMessage("CT_TITLE_BUTTON_PHONE")?>
                                    </div>
                                </div>
                            <?else:?>
                                <noindex>
                                <?offersIterator($arResult, function ($item, $dataString) use ($arParams, $arResult) {
                                    $class = 'product-filter-submit__button product-filter-submit__button--submit button  door-to-cart';
                                    $productInOrder = array_key_exists($item["ID"], (array) $_SESSION["BASKET"]["ORDER_ITEMS"]);
                                    ?>
                                    <div class="product-filter__btns">
                                        <?if ($item['CATALOG_QUANTITY']):?>
                                            <button rel="nofollow" href="javascript:void(0)" data-offer-id="<?= $item['ID'] ?>" data-id="<?= $item['ID'] ?>"
                                                    class="<?= $class. ' detail-to-cart'?><?=$productInOrder ? " hidden" : ""?>">
                                                <?= GetMessage('CT_TITLE_BUY')?>
                                                <svg class="icon icon-small-cart">
                                                    <use xlink:href="#shopping_cart_line"></use>
                                                </svg>
                                            </button>
                                            <? if ($arParams['USER']) { ?>
                                                <button class="favorites-btn js-favorite-add"
                                                        data-offer-id="<?= $item['ID'] ?>"
                                                        data-id="<?= $item['ID'] ?>">
                                                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/favorites-icon.svg"
                                                         alt="">
                                                </button>
                                            <? } ?>
                                        <?else:?>
                                            <button rel="nofollow" href="javascript:void(0)" data-offer-id="<?= $item['ID'] ?>" data-id="<?= $item['ID'] ?>"
                                                    class="<?= $class . ' detail-to-cart tooltip-link tooltip-top'?><?=$productInOrder ? " hidden" : ""?>" <?=$dataString?>>
                                                <?= GetMessage("CT_TITLE_BUTTON_PHONE")?>
                                                <svg class="icon icon-small-cart">
                                                    <use xlink:href="#shopping_cart_line"></use>
                                                </svg>
                                                <div class="tooltip" style="display:none;"><?= GetMessage("CT_TEXT_TOOLTIP_BUY")?></div>
                                            </button>
                                            <? if ($arParams['USER']) { ?>
                                                <button class="favorites-btn js-favorite-add"
                                                        data-offer-id="<?= $item['ID'] ?>"
                                                        data-id="<?= $item['ID'] ?>">
                                                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/favorites-icon.svg"
                                                         alt="">
                                                </button>
                                            <? } ?>
                                        <?endif;?>
                                        <a rel="nofollow" href="/personal/cart/" data-offer-id="<?= $item['ID'] ?>" data-id="<?= $item['ID'] ?>" class="<?= $class?><?=$productInOrder ? "" : " hidden"?>"><?= GetMessage('CT_TITLE_CART')?></a>
                                    </div>
                                    <?
                                });?>
                                </noindex>
                            <?endif;?>

                            <div class="one-click">
                              <script data-b24-form="click/10/yv9mob" data-skip-moving="true">(function(w,d,u){var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);})(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_10_yv9mob.js');</script>
                                <div data-product_id="<?=$arResult['ID']?>" class="one-click-buy button button--secondary">
                                    <?=  GetMessage("CT_TITLE_ONECLICK_BUY")?>
                                </div>
                            </div>

                            <div class="product-filter__footer">
                               <script data-b24-form="click/9/5qnm9v" data-skip-moving="true">(function(w,d,u){var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);})(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_9_5qnm9v.js');</script>
                                <a class="popup-link product-buttons__button--measurement">
                                    <span><?=GetMessage("CT_MEASUREMENT_BUTTON")?></span>
                                    <svg class="icon icon-ruler">
                                        <use xlink:href="#icon-ruler"></use>
                                    </svg>
                                </a>
                            </div>
                        <?endif;?>


                        <? if(!empty($arResult['FILE_DOWNLOAD'])):?>
                            <div class="product-filter__footer product-filter__footer-doc">
                                <? foreach ($arResult['FILE_DOWNLOAD'] as $file): ?>
                                    <div class="product-document-item">
                                        <a href="<?= $file['LINK']; ?>" download class="popup-link product-buttons__button--measurement">
                                            <span><?= $file['TITLE']; ?></span>

                                            <svg version="1.1" id="Capa_1"  class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                 viewBox="0 0 296 296" style="enable-background:new 0 0 296 296;" xml:space="preserve">
                                                <g><polygon points="231.855,125 172.5,125 172.5,0 124.5,0 124.5,125 65.812,125 147.471,239.018     "/><rect x="32.5" y="261" width="231" height="25"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                            </svg>
                                        </a>
                                    </div>
                                <? endforeach;  ?>
                            </div>
                        <?endif;?>

                    </div>
                </div>
            </div>
        </section>
    <?}?>


    <section class="product">
        <div class="content-container">
            <?if ($isDoor || ($ml_dekor == true)) { ?>
                <?if (!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")):?>
                    <div class="accordion product-delivery-description-mobile  js-accordion">
                        <a class="accordion__toggler  js-accordion__toggler" href="#">
                            <i class="accordion__icon"></i>
                            <span>Доставка и оплата</span>
                        </a>
                        <div class="accordion__content  js-accordion__content">
                            <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:main.include", "", Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/product-delivery-payment-description.php"
                                )
                            );
                            ?>
                        </div>
                    </div>
                <?endif;?>

                <?if (!!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")):?>
                    <div class="tabs  js-tabs">
                        <div class="tabs__navigation">
                            <div class="tabs__toggle  active-tab  js-tabs__toggle" data-tabs-item="1">
                                <span>Описание</span>
                            </div>

                            <div class="tabs__toggle  js-tabs__toggle" data-tabs-item="2">
                                <span>Похожие модели</span>
                            </div>

                            <?if(count((array)$arResult["PROPERTIES"]["SALOONS_LIST"]["VALUE"]) > 0 && count((array)$arResult["PROPERTIES"]["SALOONS_LIST"]["ADDRESSES"]) > 0):?>
                                <div class="tabs__toggle js-tabs__toggle" data-tabs-item="3">
                                    <span>Образец в салонах</span>
                                </div>
                            <?endif;?>

                            <div class="tabs__toggle  js-tabs__toggle" data-tabs-item="4">
                                <span>Доставка и оплата</span>
                            </div>

                            <?if (!empty($arResult["COMPLETED_PROJECTS"])):?>
                                <div class="tabs__toggle  js-tabs__toggle" data-tabs-item="5">
                                    <span>Реализованные объекты</span>
                                </div>
                            <?endif;?>


                        </div>

                        <div class="tabs__list" id="all_characteristic">
                            <div class="tabs__content  active-tab  js-tabs__tab" data-tabs-item="1">
                                <div class="product__title">Характеристики</div>
                                <ul class="product-info-parameters__list">
                                    <?
                                    $dpMap = array(
                                        'FURNITURE' => array(
                                            'BOOLEAN' => $isDoor
                                        ),
                                        'WIDTH' => array(
                                            'SIZE' => true,
                                            'NAME' => ($isDoor) ? GetMessage('CT_BCS_TITLE_PROP_T_POLOTNO') : GetMessage('CT_BCS_TITLE_PROP_T_DOSKA')
                                        )
                                    );
                                    $arar = ["COUNTRY", "COLLECTION", "MATERIAL", "STYLE", "TYPE","GUARANTEE", "Construction", "inside", "Filling","structure", "KOL_KON_YP",
                                    "TERMORAZRV", "Tolshchina_polotna", "Tolshchina_metalla_polotnaP", "Tolshchina_metalla_korobki", "Kolichestvo_zamkov", "Protivosyemnyye_rigeli",
                                    "Rebra_zhestkosti", "Kolichestvo_petel", "tolshchina_korobki", "Uteplitel_korobki", "Vneshnyaya_panel", "Exterior_finish", "Tsvet_vneshney",
                                    "Vnutrennyaya_panel", "Vnutrennyaya_otdelka", "Tsvet_vnutrenney", "Tsvet_vneshney_korobki", "Tsvet_vnutrenney_korobki", "Handles", "Zamok_osnovnoy", "Klass_zashchity_osnovnogo_zamka", "Tip_osnovnogo_zamka",
                                    "Zamok_dopolnitelnyy", "Klass_zashchity", "Tip_dopolnitelnogo_zamka", "Deviatory", "Glazok", "Dvernyye_petli_tip", "Oblast_primeneniya", "Otkryvaniye", "Nochnoy_zamok", "Ugol_otkryvaniya", "Komplektatsiya",
                                    "Regulyator_pritvora", "KONSTRUKCII"];
                                    $ararr = ["COLOR", "SIZE","ves_btutto", "slab_thickness", "razmer_v_ypalovke", "GLASS_COLOR"];
                                    $ararrr = ["slab_thickness" => ' мм', "razmer_v_ypalovke" => ' мм', "ves_btutto" => ' кг',"SIZE" => ' м'];

                                    if(1) {
                                        foreach ($arResult['OFFERS'][0]['PROPERTIES'] as $prop) {
                                            if (!in_array($prop['CODE'], $ararr)){
                                                continue;
                                            }

                                            if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                                <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$prop['ID']?>">
                                                    <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                    <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                                </li>
                                            <?} elseif ($prop['VALUE']) {
                                                if($prop['USER_TYPE']=='directory'){
                                                    $tableName = $prop['USER_TYPE_SETTINGS']['TABLE_NAME'];

                                                    foreach ($arResult['OFFERS'] as $keys => $props) {
                                                        $XML_ID = $arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                        if(isset($arResult['HIGHLOAD_VALUES'][$tableName][$XML_ID])){?>
                                                            <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$props['ID']?>">
                                                                <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                                <div class="product-info-parameters__value"><?= $arResult['HIGHLOAD_VALUES'][$tableName][$XML_ID]['UF_NAME']; ?><?=$ararrr[$prop['CODE']]?></div>
                                                            </li>
                                                        <?}
                                                    }
                                                }else{
                                                    foreach ($arResult['OFFERS'] as $keys => $props) {
                                                        $val = $arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                        if($val) {?>
                                                            <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$props['ID']?>">
                                                                <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                                <div class="product-info-parameters__value"><?= $val; ?><?=$ararrr[$prop['CODE']]?></div>
                                                            </li>
                                                        <?}
                                                    }?>

                                                <?}
                                            }
                                        }
                                    }

                                    foreach ($arResult['DISPLAY_PROPERTIES'] as $prop) {
                                        if (!in_array($prop['CODE'], $arar)){
                                            continue;
                                        }
                                        if ($prop['CODE'] == 'BOX_SQUARE') {
                                            $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                                        }

                                        if (isset($dpMap[$prop['CODE']])) {
                                            if ($dpMap[$prop['CODE']]['BOOLEAN']) {
                                                $prop['VALUE'] = $prop['VALUE'] ? GetMessage('CT_BCS_TITLE_PROP_VALUE_Y') : GetMessage('CT_BCS_TITLE_PROP_VALUE_N');
                                            }
                                            if ($dpMap[$prop['CODE']]['NAME']) {
                                                $prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
                                            }

                                            if ($dpMap[$prop['CODE']]['SIZE']) {
                                                offersIterator($arResult, function ($item, $dataString) {
                                                    $sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
                                                    if (sizeof($sizes) == 2) {
                                                        ?>
                                                        <li class="product-info-parameters__item"<?= $dataString ?>>
                                                            <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_HEIGHT')?></div>
                                                            <div class="product-info-parameters__value"><?= round($sizes[1] / 10) ?></div>
                                                        </li>
                                                        <li class="product-info-parameters__item"<?= $dataString ?>>
                                                            <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_WIDTH')?></div>
                                                            <div class="product-info-parameters__value"><?= round($sizes[0] / 10) ?></div>
                                                        </li>
                                                    <?}
                                                });
                                            }
                                        }
                                        ?>
                                        <? if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                            <li class="product-info-parameters__item">
                                                <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                            </li>
                                        <?} elseif ($prop['VALUE']) { ?>
                                            <li class="product-info-parameters__item">
                                                <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                <div class="product-info-parameters__value"><?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $prop['VALUE'] ?></div>
                                            </li>
                                        <?}?>
                                    <?}?>
                                </ul>

                                <div class="product__title">Описание</div>
                                <?if ($arResult['DETAIL_TEXT'] || $arResult['PREVIEW_TEXT']) { ?>
                                    <div class="product-info__text"><?= $arResult['DETAIL_TEXT'] ?: $arResult['PREVIEW_TEXT'] ?></div>
                                <?}?>
                                <?if (!empty($arResult["PROPERTIES"]["FILE_DESCRIPTION"]["VALUE"])) :?>
                                    <ul class="link-list">
                                        <?$res = CFile::GetList(array("FILE_SIZE"=>"desc"), ["@ID" => array_values($arResult["PROPERTIES"]["FILE_DESCRIPTION"]["VALUE"])]);?>
                                        <?while($res_arr = $res->GetNext()) :?>
                                            <li class="link-list__item">
                                                <a href="/upload/<?=$res_arr["SUBDIR"]?>/<?=$res_arr["FILE_NAME"]?>" class="link-list__link"><?=$res_arr["ORIGINAL_NAME"]?></a>
                                            </li>
                                        <?endwhile;?>
                                    </ul>
                                <?endif;?>
                            </div>

                            <div class="tabs__content  js-tabs__tab" data-tabs-item="2">
                                <div class="product__title">Похожие модели дверей</div>
                                <img class="similar_ajax_loader" src="<?= SITE_TEMPLATE_PATH ?>/preload.svg">
                                <div id="similar_ajax"></div>
                            </div>

                            <?if(count((array)$arResult["PROPERTIES"]["SALOONS_LIST"]["VALUE"]) > 0 || count((array)$arResult["PROPERTIES"]["SALOONS_LIST"]["ADDRESSES"]) > 0):?>
                                <div class="tabs__content  js-tabs__tab" data-tabs-item="3">
                                    <!--<div class="product__title">Наличие в магазинах</div>-->
                                    <ul class="product-info-availability__list _w100">
                                        <li class="product-info-availability__item product-info__header">
                                            <div class="product-info-availability__address">Адрес</div>
                                            <div class="product-info-availability__hours">Режим работы</div>
                                            <div class="product-info-availability__link">Показать на карте</div>
                                        </li>
                                        <? foreach ($arResult["PROPERTIES"]["SALOONS_LIST"]["ADDRESSES"] as $address): ?>
                                            <li class="product-info-availability__item">
                                                <div class="product-info-availability__address not-header-address"><?= $address["ADDRESS"] ?></div>
                                                <div class="product-info-availability__hours not-header-time"><?= $address["WORKING"] ?></div>
                                                <div class="product-info-availability__link not-header-button">
                                                    <button class="ajax-form" data-href="<? $arResult["TEMPLATE_DIR"] ?>/popup-map.php?coords=<?= $address["COORDS"] ?>" data-map-ccords="<?= $address["COORDS"] ?>" data-class="w820 b2b-wrapper">Показать на карте</button>
                                                </div>
                                            </li>

                                        <?endforeach;?>
                                    </ul>
                                </div>
                            <?endif;?>

                            <div class="tabs__content  js-tabs__tab" data-tabs-item="4">
                                <div class="product-delivery-description">
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include", "", Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-delivery-description.php"
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="product-delivery-description">
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include", "", Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-payment-description.php"
                                        )
                                    );
                                    ?>
                                </div>
                            </div>

                            <?if (!empty($arResult["COMPLETED_PROJECTS"])):?>
                                <div class="tabs__content  js-tabs__tab" data-tabs-item="5">
                                    <div class="product__title">Реализованные объекты</div>
                                    <div>
                                        <?foreach($arResult["COMPLETED_PROJECTS"] as $arItem):?>
                                            <article class="news__item">
                                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__inner">
                                                    <div style="background-image:url(<?=$arItem["PHOTO"]["src"];?>)" class="news__image">
                                                    </div>
                                                    <div class="news__text-container">
                                                        <div class="news__text"><?echo $arItem["NAME"]?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </article>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            <?endif;?>


                        </div>
                    </div>
                <?else:?>

                    <div class="tabs  js-tabs">
                        <div class="tabs__navigation">
                            <?php if($ml_dekor==true) { ?>
                                <div class="tabs__toggle  active-tab  js-tabs__toggle" data-tabs-item="1">
                                    <span>Описание и характеристика</span>
                                </div>
                            <?php } else { ?>
                                <div class="tabs__toggle  active-tab  js-tabs__toggle" data-tabs-item="1">
                                    <span>Описание</span>
                                </div>
                                <div class="tabs__toggle js-tabs__toggle" data-tabs-item="2">
                                    <span>Характеристики</span>
                                </div>
                            <?php } ?>
                            <?php if ($ml_dekor == false) { ?>
                                <div class="tabs__toggle js-tabs__toggle" data-tabs-item="3">
                                    <span>Фурнитура</span>
                                </div>
                            <?php } ?>
                            <div class="tabs__toggle js-tabs__toggle" data-tabs-item="4">
                                <span>Доставка</span>
                            </div>
                            <div class="tabs__toggle js-tabs__toggle" data-tabs-item="5">
                                <span>Оплата</span>
                            </div>
                            <?php if ($ml_dekor == false) { ?>
                                <div class="tabs__toggle js-tabs__toggle" data-tabs-item="6">
                                    <span>Установка</span>
                                </div>
                            <?php }?>
                        </div>
                        <div class="tabs__list" id="all_characteristic">

                            <div class="tabs__content  active-tab  js-tabs__tab" data-tabs-item="1">
                                <?php if ($ml_dekor==true) { ?>
                                    <div class="product__title"><?= GetMessage('CT_BCS_TITLE_PROP')?></div>
                                    <ul class="product-info-parameters__list">
                                        <?$arar = ["PRODUCT_TYPE"];
                                        $ararr = ["COLOR", "SIZE","ves_btutto", "slab_thickness", "razmer_v_ypalovke", "GLASS_COLOR", "SIZE_DOOR", "WIDTH_PANEL"];
                                        $ararrr = ["slab_thickness" => ' мм', "razmer_v_ypalovke" => ' мм', "ves_btutto" => ' кг',"SIZE" => ' м', "SIZE_DOOR" => ' м', "WIDTH_PANEL" => ' м'];

                                        if(1) {
                                            foreach ($arResult['OFFERS'][0]['PROPERTIES'] as $prop) {
                                                if (!in_array($prop['CODE'], $ararr)){
                                                    continue;
                                                }

                                                if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                                    <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$prop['ID']?>">
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                        <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                                    </li>
                                                <?} elseif ($prop['VALUE']) {
                                                    if($prop['USER_TYPE']=='directory'){
                                                        $tableName = $prop['USER_TYPE_SETTINGS']['TABLE_NAME'];

                                                        foreach ($arResult['OFFERS'] as $keys => $props) {
                                                            $XML_ID = $arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                            if(isset($arResult['HIGHLOAD_VALUES'][$tableName][$XML_ID])){?>
                                                                <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$props['ID']?>">
                                                                    <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                                    <div class="product-info-parameters__value"><?= $arResult['HIGHLOAD_VALUES'][$tableName][$XML_ID]['UF_NAME']; ?><?=$ararrr[$prop['CODE']]?></div>
                                                                </li>
                                                            <?}
                                                        }
                                                    }else{
                                                        foreach ($arResult['OFFERS'] as $keys => $props) {
                                                            $val = $arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                            if($val) {?>
                                                                <li class="product-info-parameters__item" style="display: none;" data-offer-id="<?=$props['ID']?>">
                                                                    <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                                    <div class="product-info-parameters__value"><?= $val; ?><?=$ararrr[$prop['CODE']]?></div>
                                                                </li>
                                                            <?}
                                                        }?>

                                                    <?}
                                                }
                                            }
                                            foreach ($arResult['PROPERTIES'] as $prop) {
                                                if (!in_array($prop['CODE'], $arar)){
                                                    continue;
                                                }
                                                if($prop['VALUE']) {?>
                                                    <li class="product-info-parameters__item">
                                                        <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                        <div class="product-info-parameters__value"><?= $prop['VALUE'] ?></div>
                                                    </li>
                                                <?}
                                            }
                                        }



                                        $dpMap = array(
                                            'FURNITURE' => array(
                                                'BOOLEAN' => $isDoor
                                            ),
                                            'WIDTH' => array(
                                                'SIZE' => true,
                                                'NAME' => ($isDoor) ? GetMessage('CT_BCS_TITLE_PROP_T_POLOTNO') : GetMessage('CT_BCS_TITLE_PROP_T_DOSKA')
                                            )
                                        );
                                        foreach ($arResult['DISPLAY_PROPERTIES'] as $prop) { ?>
                                            <?
                                            if ($prop['CODE'] == 'BOX_SQUARE') {
                                                $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                                            }

                                            if (isset($dpMap[$prop['CODE']])) {
                                                if ($dpMap[$prop['CODE']]['BOOLEAN']) {
                                                    $prop['VALUE'] = $prop['VALUE'] ? GetMessage('CT_BCS_TITLE_PROP_VALUE_Y') : GetMessage('CT_BCS_TITLE_PROP_VALUE_N');
                                                }
                                                if ($dpMap[$prop['CODE']]['NAME']) {
                                                    $prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
                                                }

                                                if ($dpMap[$prop['CODE']]['SIZE']) {
                                                    offersIterator($arResult, function ($item, $dataString) {
                                                        $sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
                                                        if (sizeof($sizes) == 2) {
                                                            ?>
                                                            <li class="product-info-parameters__item"<?= $dataString ?>>
                                                                <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_HEIGHT')?></div>
                                                                <div class="product-info-parameters__value"><?= round($sizes[1] / 10) ?></div>
                                                            </li>
                                                            <li class="product-info-parameters__item"<?= $dataString ?>>
                                                                <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_WIDTH')?></div>
                                                                <div class="product-info-parameters__value"><?= round($sizes[0] / 10) ?></div>
                                                            </li>
                                                            <?
                                                        }
                                                    });
                                                }
                                            }
                                            ?>

                                            <? if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                                <li class="product-info-parameters__item">
                                                    <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                    <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                                </li>
                                            <?} elseif ($prop['VALUE']) {?>
                                                <li class="product-info-parameters__item">
                                                    <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                    <div class="product-info-parameters__value"><?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $prop['VALUE'] ?></div>
                                                </li>
                                            <?}?>
                                        <?}?>
                                    </ul>
                                <?php } ?>

                                <div class="product-info__descr">
                                    <?if ($arResult['DETAIL_TEXT'] || $arResult['PREVIEW_TEXT']) { ?>
                                        <div class="product__title"><?= GetMessage('CT_BCS_TITLE_DETAIL_TEXT')?></div>
                                        <div class="product-info__text"><?= $arResult['DETAIL_TEXT'] ?: $arResult['PREVIEW_TEXT'] ?></div>
                                    <?}?>
                                </div>
                            </div>

                            <?php if ($ml_dekor==false) { ?>
                                <div class="tabs__content js-tabs__tab" data-tabs-item="2">
                                    <div class="product__title"><?= GetMessage('CT_BCS_TITLE_PROP')?></div>
                                    <ul class="product-info-parameters__list">
                                        <?$ararr = ["COLOR", "SIZE", "ves_btutto","slab_thickness", "GLASS_COLOR", "razmer_v_ypalovke"];

                                        if($USER->isAdmin()) {
                                            foreach ($arResult['OFFERS'][0]['PROPERTIES'] as $prop) {
                                                if (!in_array($prop['CODE'], $ararr)){
                                                    continue;
                                                }

                                                if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                                    <li class="product-info-parameters__item">
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                        <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                                    </li>
                                                <?} elseif ($prop['VALUE']) {
                                                    if($prop['USER_TYPE']=='directory'){
                                                        $a = $prop['USER_TYPE_SETTINGS'];
                                                        $tableName = $a['TABLE_NAME'];
                                                        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array("filter" => array('TABLE_NAME' => $tableName)))->fetch();
                                                        if (isset($hlblock['ID'])) {
                                                            $arrProps = [];
                                                            foreach ($arResult['OFFERS'] as $keys => $props) {
                                                                $XML_ID=$arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                                $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                                                                $entity_data_class = $entity->getDataClass();
                                                                $res = $entity_data_class::getList( array('filter'=>array( 'UF_XML_ID' => $XML_ID,)) );
                                                                if ($item = $res->fetch()) {
                                                                    if(!in_array($item['UF_NAME'], $arrProps))
                                                                        $arrProps[] = $item['UF_NAME'];

                                                                }
                                                            }
                                                            if(isset($arrProps)) {?>
                                                                <li class="product-info-parameters__item">
                                                                    <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                                    <div class="product-info-parameters__value"><?= implode(', ', $arrProps); ?></div>
                                                                </li>
                                                            <?}
                                                        }
                                                    }else{
                                                        $arrProps = [];
                                                        foreach ($arResult['OFFERS'] as $keys => $props) {
                                                            $val = $arResult['OFFERS'][$keys]['PROPERTIES'][$prop['CODE']]['VALUE'];
                                                            if(!in_array($val, $arrProps)) $arrProps[] = $val;
                                                        }?>
                                                        <li class="product-info-parameters__item">
                                                            <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                            <div class="product-info-parameters__value"><?= implode(', ', $arrProps); ?></div>
                                                        </li>
                                                    <?}
                                                }
                                            }
                                        }



                                        $dpMap = array(
                                            'FURNITURE' => array(
                                                'BOOLEAN' => $isDoor
                                            ),
                                            'WIDTH' => array(
                                                'SIZE' => true,
                                                'NAME' => ($isDoor) ? GetMessage('CT_BCS_TITLE_PROP_T_POLOTNO') : GetMessage('CT_BCS_TITLE_PROP_T_DOSKA')
                                            )
                                        );
                                        foreach ($arResult['DISPLAY_PROPERTIES'] as $prop) { ?>
                                            <?
                                            if ($prop['CODE'] == 'BOX_SQUARE') {
                                                $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                                            }

                                            if (isset($dpMap[$prop['CODE']])) {
                                                if ($dpMap[$prop['CODE']]['BOOLEAN']) {
                                                    $prop['VALUE'] = $prop['VALUE'] ? GetMessage('CT_BCS_TITLE_PROP_VALUE_Y') : GetMessage('CT_BCS_TITLE_PROP_VALUE_N');
                                                }
                                                if ($dpMap[$prop['CODE']]['NAME']) {
                                                    $prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
                                                }

                                                if ($dpMap[$prop['CODE']]['SIZE']) {
                                                    offersIterator($arResult, function ($item, $dataString) {
                                                        $sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
                                                        if (sizeof($sizes) == 2) {
                                                            ?>
                                                            <li class="product-info-parameters__item"<?= $dataString ?>>
                                                                <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_HEIGHT')?></div>
                                                                <div class="product-info-parameters__value"><?= round($sizes[1] / 10) ?></div>
                                                            </li>
                                                            <li class="product-info-parameters__item"<?= $dataString ?>>
                                                                <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_WIDTH')?></div>
                                                                <div class="product-info-parameters__value"><?= round($sizes[0] / 10) ?></div>
                                                            </li>
                                                            <?
                                                        }
                                                    });
                                                }
                                            }
                                            ?>

                                            <? if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                                <li class="product-info-parameters__item">
                                                    <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                                    <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                                </li>
                                            <?} elseif ($prop['VALUE']) {?>
                                                <li class="product-info-parameters__item">
                                                    <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                                    <div class="product-info-parameters__value"><?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $prop['VALUE'] ?></div>
                                                </li>
                                            <?}?>
                                        <?}?>
                                    </ul>
                                </div>
                            <?php } ?>

                            <?php if ($ml_dekor == false) { ?>
                                <div class="tabs__content js-tabs__tab" data-tabs-item="3">
                                    <?if(!empty($arResult['SECTION']) && $dveri ) {
                                        $APPLICATION->IncludeComponent(
                                            "bitrix:catalog.section.list",
                                            "fartnitura",
                                            Array(
                                                "ADD_SECTIONS_CHAIN" => "N",
                                                "CACHE_GROUPS" => "Y",
                                                "CACHE_TIME" => "36000000",
                                                "CACHE_TYPE" => "A",
                                                "COMPONENT_TEMPLATE" => "fartnitura",
                                                "COUNT_ELEMENTS" => "N",
                                                "IBLOCK_ID" => "2",
                                                "IBLOCK_TYPE" => "catalog",
                                                "SECTION_CODE" => "",
                                                "SECTION_FIELDS" => array(0 => "NAME", 1 => "PICTURE", 2 => "",),
                                                "SECTION_ID" => "22",
                                                "SECTION_URL" => "",
                                                "SECTION_USER_FIELDS" => array(0 => "", 1 => "",),
                                                "SHOW_PARENT_NAME" => "Y",
                                                "TOP_DEPTH" => "2",
                                                "VIEW_MODE" => "LINE"
                                            )
                                        );
                                    }?>
                                </div>
                            <?php } ?>

                            <div class="tabs__content js-tabs__tab" data-tabs-item="4">
                                <div class="product-delivery-description">
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-delivery-description.php"
                                        )
                                    );?>
                                </div>
                            </div>

                            <div class="tabs__content js-tabs__tab" data-tabs-item="5">
                                <div class="product-delivery-description">
                                    <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-payment-description.php"
                                        )
                                    );?>
                                </div>
                            </div>

                            <?php if ($ml_dekor == false) { ?>
                                <div class="tabs__content js-tabs__tab" data-tabs-item="6">
                                    <div class="product-delivery-description">
                                        <?
                                        if (!empty($arResult['PROPERTIES']['MONTAGE_HIDDEN']['VALUE'])) {
                                            $APPLICATION->IncludeComponent(
                                                "bitrix:main.include", "", Array(
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "inc",
                                                    "EDIT_TEMPLATE" => "",
                                                    "PATH" => "/include/product-montage-hidden-description.php"
                                                )
                                            );
                                        } else {
                                            $APPLICATION->IncludeComponent(
                                                "bitrix:main.include", "", Array(
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "inc",
                                                    "EDIT_TEMPLATE" => "",
                                                    "PATH" => "/include/product-montage-description.php"
                                                )
                                            );
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?endif;?>

            <?} else {?>

                <?/*if (!empty($arResult['PROPERTIES']['VIDEO']['VALUE'])) {
                    ?>
                    <div class="product_video">
                        <div>
                            <a class="video_link" onclick="" href="#">
                                <img class="video_img bump" alt="" src="/images/play.png"><?=GetMessage('CT_VIDEO')?>
                            </a>
                        </div>
                    </div>
                    <? // Микроразметка coffeediz:schema.org.Video
                    $dateTemp = ($arResult["DATE_CREATE"])?$arResult["DATE_CREATE"]:$arResult["DATE_ACTIVE_FROM"];
                    $a = date_parse_from_format('d.m.Y G:i:s',$dateTemp);
                    $timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['day'], $a['year']);
                    $dateTemp1 = date('Y-m-d\TH:i:sO', $timestamp);

                    $APPLICATION->IncludeComponent(
                        "coffeediz:schema.org.Video",
                        "myVideo",
                        Array(
                            "ALLOWCOUNTRIES" => "",    // Перечень стран, в которых доступно данное видео (В ОСТАЛЬНЫХ ЗАПРЕЩЕНО)
                            "AUTHOR_PERSON_ADDITIONALNAME" => "",    // Отчество
                            "AUTHOR_PERSON_EMAIL" => "",    // E-mail
                            "AUTHOR_PERSON_FAMILYNAME" => "",    // Фамилия
                            "AUTHOR_PERSON_IMAGEURL" => "",    // URL фото персоны
                            "AUTHOR_PERSON_JOBTITLE" => "",    // Должность
                            "AUTHOR_PERSON_NAME" => "",    // Имя
                            "AUTHOR_PERSON_PHONE" => "",    // Телефон
                            "AUTHOR_PERSON_URL" => "",    // URL страниц, связанных с персоной
                            "AUTHOR_PERSON_URL_SAMEAS" => "",    // URL ОФИЦИАЛЬНЫХ страниц, связанных с персоной
                            "CAPTION" => $arResult["NAME"],    // Подпись к видео
                            "CONTENT_ID" => "",    // Идентификатор видео
                            "CONTENT_URL" => $arResult["DETAIL_PAGE_URL"],    // Адрес, по которому доступен файл с видео-роликом
                            "DESCRIPTION" => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]):strip_tags($arResult["PREVIEW_TEXT"]),    // Описание
                            "DISALLOWCOUNTRIES" => "",    // Перечень стран, в которых НЕдоступно данное видео (В ОСТАЛЬНЫХ РАЗРЕШЕНО)
                            "DUBBING" => "",    // Студия, дублировавшая видео
                            "DURATION" => "100",    // Продолжительность видео (PTччHммMссS)
                            "FEED_URL" => "",    // Адрес XML-фида для данной страницы
                            "GENRE" => array(    // Жанр
                                0 => "",
                                1 => "",
                            ),
                            "IMAGEURL" => "",    // URL Оффициального Изображения (постера и т.п.)
                            "IN_LANGUAGE" => "ru",    // Язык видео
                            "IS_FAMILY_FRIENDLY" => "Y",    // Можно смотреть детям
                            "IS_OFFICIAL" => "N",    // Официальное видео
                            "KEYWORDS" => array(    // Ключевые слова, Теги
                                0 => "двери межкомнатные, двери входные, двери от производителя",
                            ),
                            "LICENSE" => "Common license",    // Тип лицензии, по которой распространяется видео
                            "NAME" => $arResult["NAME"],    // Название
                            "PARAM_RATING_SHOW" => "N",    // Выводить рейтинг
                            "PRODUCTCOMPANY_TYPE" => "Organization",    // Тип описания Компании-Производитель видео
                            "PRODUCTIONCOUNTRY" => "3166-2:BY",    // Страна-производитель (в формате ISO 3166-1)
                            "SHOW" => "Y",    // Не отображать на сайте
                            "STATUS" => "published",    // Статус
                            "SUBTITLE_IN_LANGUAGE" => "",    // Язык субтитров
                            "SUBTITLE_URL" => "",    // Адрес, по которому расположен файл с субтитрами
                            "THUNBNAIL_IMAGEURL" => $videoSmallImage["src"],    // URL Изображения предпросмотра
                            "UPLOAD_DATE" => $dateTemp1,    // дата загрузки видео-ролика на сайт в формате ISO 8601 (ГГГГ-ММ-ДД)
                            "URL" => str_replace("watch?v=","embed/",$arResult["PROPERTIES"]["VIDEO"]["VALUE"])."?enablejsapi=1",    // Ссылка на видео
                            "COMPONENT_TEMPLATE" => ".default",
                            "THUNBNAIL_IMAGE_NAME" => $arResult["NAME"],    // Название Изображения предпросмотра
                            "THUNBNAIL_IMAGE_CAPTION" => $arResult["NAME"],    // Подпись к Изображению предпросмотра
                            "THUNBNAIL_IMAGE_DESCRIPTION" => ($arResult["DETAIL_TEXT"])?$arResult["DETAIL_TEXT"]:$arResult["PREVIEW_TEXT"],    // Описание изображения предпросмотра
                            "THUNBNAIL_IMAGE_HEIGHT" => "244",    // Высота изображения предпросмотра (px)
                            "THUNBNAIL_IMAGE_WIDTH" => "113",    // Ширина изображения предпросмотра (px)
                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_2" => "LocalBusiness",    // Тип Организации
                            "PRODUCTCOMPANY_ORGANIZATION_NAME" => "ОДО «Беллесизделие»",    // Название компании
                            "PRODUCTCOMPANY_ORGANIZATION_DESCRIPTION" => "Белорусская компания-производитель межкомнатных и входных дверей. ",    // Краткое описание компании
                            "PRODUCTCOMPANY_ORGANIZATION_SITE" => "belwooddoors.by",    // Сайт компании
                            "PRODUCTCOMPANY_ORGANIZATION_PHONE" => array(    // Телефон компании
                                0 => "+375(17)388-15-58",
                                1 => "+375(17)346-22-48",
                                2 => "+375(44)779-07-72",
                                3 => "+375(44)712-12-48",
                                4 => "",
                            ),
                            "PRODUCTCOMPANY_ORGANIZATION_POST_CODE" => "220075",    // Почтовый индекс компании
                            "PRODUCTCOMPANY_ORGANIZATION_COUNTRY" => "Беларусь",    // Страна компании
                            "PRODUCTCOMPANY_ORGANIZATION_REGION" => "Минск и МО",    // Регион Компании
                            "PRODUCTCOMPANY_ORGANIZATION_LOCALITY" => "",    // Город Компании
                            "PRODUCTCOMPANY_ORGANIZATION_ADDRESS" => "ул. Промышленная, 10, комн. 20",    // Адрес компании
                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_3" => "Store",    // Тип Организации
                            "PRODUCTCOMPANY_ORGANIZATION_TYPE_4" => "HomeGoodsStore",    // Тип Организации
                        ),
                        false,
                        array(
                            "HIDE_ICONS" => "N"
                        )
                    );
                }*/?>

                <div class="product__gallery-large">
                    <?
                    $photos = array();
                    if ($arResult['GALLERY_DETAIL']) {
                        $photos[] = ['GALLERY_PICTURE' => $arResult['GALLERY_DETAIL'], 'GALLERY_PICTURE_SMALL' =>  $arResult['GALLERY_DETAIL_SMALL']];
                    }
                    if ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                        $photos = array_merge($photos, $arResult['PROPERTIES']['MORE_PHOTO']['VALUE']);
                    }

                    $imgTitle = (
                        isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) && $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                        ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
                        : $arResult['NAME']
                    );
                    $imgAlt = (
                        isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']) && $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] != ''
                        ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
                        : $arResult['NAME']
                    );
                    ?>

                    <?if ($photos) {?>
                        <a class="product-gallery-large__inner">
                            <div class="product-gallery-large__container product-gallery-large__container--bottom">
                                <div>
                                    <img class="product-gallery-large__image"/>
                                </div>
                            </div>
                            <div class="product-gallery-large__container product-gallery-large__container--top">
                                <div>
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $photos[0]['GALLERY_PICTURE']['src'] ?>" class="product-gallery-large__image" alt="<?= $imgAlt ?>"  title="<?= $imgTitle ?>"/>
                                </div>
                            </div>
                        </a>
                        <?global $item_img;
                        $item_img = $photos[0]['GALLERY_PICTURE']['src'];?>
                        <?if (sizeof($photos) > 1) { ?>
                            <div class="product-gallery-large__previews">
                                <?foreach ($photos as $key => $photo) {?>
                                    <a data-medium="<?= $photo['GALLERY_PICTURE']['src'] ?>" href="<?= $photo['GALLERY_PICTURE']['src'] ?>" class="product-gallery-large-previews__item<?= $key ? '' : ' active' ?>">
                                        <div>
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" data-src="<?= $photo['GALLERY_PICTURE_SMALL']['src'] ?>" class="product-gallery-large-previews__image"/>
                                        </div>
                                    </a>
                                <?}?>
                            </div>
                        <?}?>
                    <?}?>
                </div>

                <div class="product__price">
                    <div class="product-price__inner">
                        <div class="product-price__block">
                            <div class="product-price__title"><?= ($type == TYPE_FLOOR) ? GetMessage('CT_PRICE_COL_UNAKOVKA') : GetMessage('CT_PRICE_COL') ?></div>
                            <?offersIterator($arResult, function ($item, $dataString) use ($arParams) {
                                $price = $item['MIN_PRICE']['VALUE_NOVAT'];
                                $discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
                                ?>
                                <div<?= $dataString ?>>
                                    <div class="product-filter-price-tabs__discount" style="display: block" data-base-price="<?= $discountPrice ?>">
                                        <?= ($item['CONFIG'] == 'Купе' || $item['CONFIG'] == 'Купе двойное') ? "от&nbsp" : "";?>
                                        <div class="product-filter-price-tabs__price product-filter-price-tabs__price--new left-base-price">
                                            <?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY) ?></br>
                                        </div>
                                        <? if ($arParams['USER']) { ?>
                                            <span class="amount_text">В наличии: <?= $item['CATALOG_QUANTITY'] ?> <?= $item['CATALOG_MEASURE_NAME'] ?></span>
                                        <? } ?>
                                    </div>
                                </div>
                                <?
                            });?>
                        </div>

                        <?if ($type == TYPE_FLOOR && $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']) {
                            offersIterator($arResult, function ($item, $dataString) use ($arResult) {
                                $price = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
                                $mPrice = round($price / $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']);
                                ?>
                                <div class="product-price__block product-price__block--small"<?= $dataString ?>>
                                    <div class="product-price__title"><?= GetMessage('CT_BCS_TITLE_PRODUCT_PRICE')?></div>
                                    <div class="product-price__discount">
                                        <span><?= SaleFormatCurrency($mPrice, MAIN_CURRENCY, true) ?></span>
                                        <?=MAIN_CURRENCY_TEXT?>
                                    </div>
                                </div>
                                <?
                            });
                        }?>

                        <?foreach ($arResult['SKU_PROPS'] as $prop) {
                            if ($prop['CODE'] == 'COLOR') {
                                if ($prop['VALUES']) {
                                    $thisVals = array();
                                    $arOffersValues = array();
                                    foreach ($prop['VALUES'] as $val) {
                                        # проверяем, есть ли такое предложение
                                        foreach ($arResult['JS_OFFERS'] as $offer) {
                                            if ($offer['TREE'] && isset($offer['TREE']['PROP_' . $prop['ID']]) && $offer['TREE']['PROP_' . $prop['ID']] == $val['ID']) {
                                                $thisVals['_' . $val['ID']] = $val['NAME'];
                                                $arOffersValues['_' . $val['ID']] = $offer['ID'];
                                                break;
                                            }
                                        }
                                    }

                                    if ($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
                                        ?>
                                        <div class="product-price__block product-price__block--small">
                                            <div class="product-price__title"><?= GetMessage('CT_BCS_TITLE_PROP_COLOR')?></div>
                                            <div class="product-price__colors sku-wrapper">
                                                <?foreach ($thisVals as $key => $val) {
                                                    $colorId = substr($key, 1);
                                                    $colorFile =  $prop['VALUES'][$colorId]['PICT_SMALL']['src'];
                                                    echo '<a data-id="' . $prop['ID'] . '_' . $colorId . '" title="' . $val . '" style="background-image: url(' . $colorFile . ')" class="sku-value product-price__color-link" data-prop-offer="'.$arOffersValues[$key].'"></a>';
                                                }?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                }
                                break;
                            }
                        }?>

                        <div class="product-price__block">
                            <div class="product-price__title"><?= ($type == TYPE_FLOOR) ? GetMessage('CT_PRICE_COL_UNAKOVKA') : GetMessage('CT_COMPLECT_COUNT') ?>
                                :
                            </div>
                            <div class="product-price__quantity quantity">
                                <div class="quantity__container">
                                    <label for="quantity-input" class="sr-only"><?=GetMessage("CT_COMPLECT_COUNT")?></label>
                                    <a class="quantity__button quantity__button--minus disabled">-</a>
                                    <input type="text" value="1" id="total-quantity-input" maxlength="5" class="quantity__input"/>
                                    <a class="quantity__button quantity__button--plus">+</a>
                                </div>
                            </div>
                        </div>

                        <?if ($type == TYPE_FLOOR && $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']) {
                            offersIterator($arResult, function ($item, $dataString) use ($arResult) {
                                $square = $arResult['PROPERTIES']['BOX_SQUARE']['VALUE'];
                                ?>
                                <div class="product-price__block product-price__block--area"<?= $dataString ?>>
                                    <label for="product-area-<?= $item['ID'] ?>"
                                           class="product-price__title"><?= GetMessage('CT_BCS_TITLE_PROP_PLOSHAD')?>:</label>
                                    <input data-square="<?= $square ?>" value="<?= str_replace('.', ',', $square) ?>"
                                           id="product-area-<?= $item['ID'] ?>"
                                           class="total-square product-price__input product-price__input--area"
                                    /><?= GetMessage('CT_BCS_TITLE_PROP_PLOSHAD_M2')?>
                                </div>
                                <?
                            });
                        }?>

                        <?offersIterator($arResult, function ($item, $dataString) {?>
                            <div class="product-price__block">
                                <div class="product-price__submit_old"<?= $dataString ?>>
                                    <?if(\Bitrix\Main\Engine\CurrentUser::get()->getId()){?>
                                        <button class="favorites-btn js-favorite-add"
                                                data-id="<?= $item['ID'] ?>">
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/images/favorites-icon.svg"
                                                 alt="">
                                        </button>
                                    <?}?>
                                    <? itc\CUncachedArea::show('productCartBlock', array('id' => $item['ID'], 'quantity' => $item['CATALOG_QUANTITY'], 'dataString' => $dataString, 'notFilter' => true)); ?>
                                </div>
                            </div>
                        <?});?>
                    </div>
                </div>

                <script data-b24-form="click/27/4mfezi" data-skip-moving="true">
                    (function(w,d,u){
                        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                    })(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_27_4mfezi.js');
                </script>

                <a class="product__button product__button--consulting button popup-link"><span><?= GetMessage('CT_CONSULTING_BUTTON')?></span></a>

                <div class="accordion product-delivery-description-mobile  js-accordion">
                    <a class="accordion__toggler  js-accordion__toggler" href="#">
                        <i class="accordion__icon"></i>
                        <span>Доставка и оплата</span>
                    </a>
                    <div class="accordion__content  js-accordion__content">
                        <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:main.include", "", Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/product-delivery-payment-description.php"
                                )
                            );
                        ?>
                    </div>
                </div>

                <div class="tabset">
                    <?php if ($ml_dekor == true) { ?>
                    	<input type="radio" name="tabset" id="tab2" aria-controls="content2">
                    	<label class="js-tab-label" for="tab2">Описание и характеристики</label>
                    	<input type="radio" name="tabset" id="tab4" aria-controls="content4">
		        <label for="tab4">Доставка</label>
		        <input type="radio" name="tabset" id="tab5" aria-controls="content5">
		        <label for="tab5">Оплата</label>
                    <?php } else { ?>
                        <?if($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID'] == TYPE_FINDINGS) {?>
                            <input type="radio" name="tabset" id="tab2" aria-controls="content2" checked>
                            <label class="js-tab-label" for="tab2">Описание</label>
                            <input type="radio" name="tabset" id="tab3" aria-controls="content3">
                            <label class="js-tab-label 2" for="tab3" id="characteristic">Характеристики</label>
                        <?} else {?>
                            <input type="radio" name="tabset" id="tab2" aria-controls="content2">
                            <label class="js-tab-label" for="tab2">Описание</label>
                            <input type="radio" name="tabset" id="tab3" aria-controls="content3" checked>
                            <label class="js-tab-label 3" for="tab3" id="characteristic">Характеристики</label>
                        <?}?>

		        <input type="radio" name="tabset" id="tab4" aria-controls="content4">
		        <label for="tab4">Доставка</label>
		        <input type="radio" name="tabset" id="tab5" aria-controls="content5">
		        <label for="tab5">Оплата</label>
                    <?php } ?>

                    <div class="tab-panels">
                        <section id="content2" class="tab-panel 1">
                            <div class="product-info__descr">
                                <?if ($arResult['DETAIL_TEXT'] || $arResult['PREVIEW_TEXT']) { ?>
                                    <div class="product__title"><?= GetMessage('CT_BCS_TITLE_DETAIL_TEXT')?></div>
                                    <div class="product-info__text"><?= $arResult['DETAIL_TEXT'] ?: $arResult['PREVIEW_TEXT'] ?></div>
                                <?}?>
                            </div>

                            <?php if ($ml_dekor == true) { ?>
                            	<div class="product__title"><?= GetMessage('CT_BCS_TITLE_PROP')?></div>
                            <ul class="product-info-parameters__list">
                                <?
                                $dpMap = array(
                                    'FURNITURE' => array(
                                        'BOOLEAN' => $isDoor
                                    ),
                                    'WIDTH' => array(
                                        'SIZE' => true,
                                        'NAME' => ($isDoor) ? GetMessage('CT_BCS_TITLE_PROP_T_POLOTNO') : GetMessage('CT_BCS_TITLE_PROP_T_DOSKA')
                                    )
                                );
                                foreach ($arResult['DISPLAY_PROPERTIES'] as $prop) {
                                    if ($prop['CODE'] == 'BOX_SQUARE') {
                                        $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                                    }

                                    if (isset($dpMap[$prop['CODE']])) {
                                        if ($dpMap[$prop['CODE']]['BOOLEAN']) {
                                            $prop['VALUE'] = $prop['VALUE'] ? GetMessage('CT_BCS_TITLE_PROP_VALUE_Y') : GetMessage('CT_BCS_TITLE_PROP_VALUE_N');
                                        }
                                        if ($dpMap[$prop['CODE']]['NAME']) {
                                            $prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
                                        }

                                        if ($dpMap[$prop['CODE']]['SIZE']) {
                                            offersIterator($arResult, function ($item, $dataString) {
                                                $sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
                                                if (sizeof($sizes) == 2) {
                                                    ?>
                                                    <li class="product-info-parameters__item"<?= $dataString ?>>
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_HEIGHT')?></div>
                                                        <div class="product-info-parameters__value"><?= round($sizes[1] / 10) ?></div>
                                                    </li>
                                                    <li class="product-info-parameters__item"<?= $dataString ?>>
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_WIDTH')?></div>
                                                        <div class="product-info-parameters__value"><?= round($sizes[0] / 10) ?></div>
                                                    </li>
                                                    <?
                                                }
                                            });
                                        }
                                    }
                                    ?>

                                    <? if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                        <li class="product-info-parameters__item">
                                            <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                            <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                        </li>
                                    <?} elseif ($prop['VALUE']) {?>
                                        <li class="product-info-parameters__item">
                                            <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                            <div class="product-info-parameters__value">
                                                <?$tempProp = !empty($prop['DISPLAY_VALUE']) ? $prop['DISPLAY_VALUE'] : $prop['VALUE'];?>
                                                <?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $tempProp ?>
                                            </div>
                                        </li>
                                    <?}?>
                                <?}?>
                            </ul>
                            <?php } ?>

                        </section>

                        <?php if ($ml_dekor == false) { ?>
                        	<section id="content3" class="tab-panel">
                            <div class="product__title"><?= GetMessage('CT_BCS_TITLE_PROP')?></div>
                            <ul class="product-info-parameters__list">
                                <?
                                $dpMap = array(
                                    'FURNITURE' => array(
                                        'BOOLEAN' => $isDoor
                                    ),
                                    'WIDTH' => array(
                                        'SIZE' => true,
                                        'NAME' => ($isDoor) ? GetMessage('CT_BCS_TITLE_PROP_T_POLOTNO') : GetMessage('CT_BCS_TITLE_PROP_T_DOSKA')
                                    )
                                );
                                foreach ($arResult['DISPLAY_PROPERTIES'] as $prop) {
                                    if ($prop['CODE'] == 'BOX_SQUARE') {
                                        $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                                    }

                                    if (isset($dpMap[$prop['CODE']])) {
                                        if ($dpMap[$prop['CODE']]['BOOLEAN']) {
                                            $prop['VALUE'] = $prop['VALUE'] ? GetMessage('CT_BCS_TITLE_PROP_VALUE_Y') : GetMessage('CT_BCS_TITLE_PROP_VALUE_N');
                                        }
                                        if ($dpMap[$prop['CODE']]['NAME']) {
                                            $prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
                                        }

                                        if ($dpMap[$prop['CODE']]['SIZE']) {
                                            offersIterator($arResult, function ($item, $dataString) {
                                                $sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
                                                if (sizeof($sizes) == 2) {
                                                    ?>
                                                    <li class="product-info-parameters__item"<?= $dataString ?>>
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_HEIGHT')?></div>
                                                        <div class="product-info-parameters__value"><?= round($sizes[1] / 10) ?></div>
                                                    </li>
                                                    <li class="product-info-parameters__item"<?= $dataString ?>>
                                                        <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_WIDTH')?></div>
                                                        <div class="product-info-parameters__value"><?= round($sizes[0] / 10) ?></div>
                                                    </li>
                                                    <?
                                                }
                                            });
                                        }
                                    }
                                    ?>

                                    <? if ($prop['CODE'] == 'GLASS' && !!strstr($arResult["ORIGINAL_PARAMETERS"]["CURRENT_BASE_PAGE"], "mezhkomnatnye_dveri")) { ?>
                                        <li class="product-info-parameters__item">
                                            <div class="product-info-parameters__key"><?= GetMessage('CT_BCS_TITLE_PROP_ISPOLNITEL')?></div>
                                            <div class="product-info-parameters__value"><?= $prop['VALUE'] != 1 ? GetMessage('CT_BCS_GLASS_REF_TYPE_2') : GetMessage('CT_BCS_GLASS_REF_TYPE_1'); ?></div>
                                        </li>
                                    <?} elseif ($prop['VALUE']) {?>
                                        <li class="product-info-parameters__item">
                                            <div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
                                            <div class="product-info-parameters__value">
                                                <?$tempProp = !empty($prop['DISPLAY_VALUE']) ? $prop['DISPLAY_VALUE'] : $prop['VALUE'];?>
                                                <?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $tempProp ?>
                                            </div>
                                        </li>
                                    <?}?>
                                <?}?>
                            </ul>
                        </section>
                        <?php } ?>

                        <section id="content4" class="tab-panel">
                            <div class="product-delivery-description">
                                <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include", "", Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-delivery-description.php"
                                        )
                                    );
                                ?>
                            </div>
                        </section>

                        <section id="content5" class="tab-panel">
                            <div class="product-delivery-description">
                                <?
                                    $APPLICATION->IncludeComponent(
                                        "bitrix:main.include", "", Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "inc",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/product-payment-description.php"
                                        )
                                    );
                                ?>
                            </div>
                        </section>
                    </div>
                </div>

            <?}?>
        </div>
    </section>
</div>

<script type="text/javascript">
    var currency_text = '<?=MAIN_CURRENCY_TEXT?>';
    $(function () {
        initDetailProduct();
    });
</script>

<?
# js offers
$jsPrices = array();
$jsOffers = array();
$jsOffers[$arResult['ID']] = array();

if (!is_array($arResult['JS_OFFERS'])) {
    $arResult['JS_OFFERS'] = array();
}
foreach ($arResult['JS_OFFERS'] as $jsOffer) {
    $tree = array();
    foreach ($jsOffer['TREE'] as $key => $val) {
        $tree[] = (int)str_replace('PROP_', '', $key) . '_' . $val;
    }
    $jsOffers[$arResult['ID']][$jsOffer['ID']] = $tree;

    # цена
    if ($arResult['OFFERS']) {
        foreach ($arResult['OFFERS'] as $offer) {
            if ($offer['ID'] == $jsOffer['ID']) {
                $jsPrices[$offer['ID']] = $offer['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
            }
        }
    }
}
?>

<script>
    (function () {
        var offers = <?= json_encode($jsOffers) ?>, k;
        window.jsOffers = window.jsOffers || {};
        for (k in offers) {
            if (offers.hasOwnProperty(k)) {
                window.jsOffers[k] = offers[k];
            }
        }
        window.jsPrices = <?= json_encode($jsPrices) ?>;
    })();
</script>
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
        var source = "https://creativecdn.com/tags?type=iframe&id=pr_B6MjHKf0gPbC1LVhT75b_offer_by-<?=$arResult["ID"]?>&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + encodeURIComponent(timestamp);
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

<script type="text/javascript">
	$(document).ready(function(){
		var ml_dekor = <?php if ($ml_dekor === true) echo 1; else echo 0; ?>;
		if (ml_dekor == 0) return false;
		$(".product-info-parameters__item").each(function(i, el){
			function setActiveSizeProp(el) {
				$(".product-filter-color__link.active").each(function(index, val){
					el.parentElement.lastElementChild.textContent = $(".select-sku-value.size-value.active")[0].textContent;
				});
			}
			switch(el.firstElementChild.textContent) {
				case 'Цвет':
					$(".product-filter-color__link.active").each(function(index, val){
						el.firstElementChild.parentElement.lastElementChild.textContent = val.lastElementChild.textContent;
					});
					break;
				case 'Размер полотна':
					setActiveSizeProp(el.firstElementChild);
					break;
				case 'Размер':
					setActiveSizeProp(el.firstElementChild);
					break;
				default:
					break;
			}
		});

		$(".sku-value").click(function(){
			if (ml_dekor == 1) {
				if ($(this).hasClass("size-value")) {
					var size_val_set = $(this).text();
					let parameters = $(".product-info-parameters__key");
					parameters.each(function(i, el){
						if ((el.textContent == "Размер полотна") || (el.textContent == "Размер")) {
							el.parentElement.childNodes[3].textContent = size_val_set;
						}

					});
				}
				$(".product-info-parameters__item").each(function(index, val){
					if (val.firstElementChild.textContent == "Цвет") {
						val.lastElementChild.textContent = $(".sku-value.product-filter-color__link.active span")[0].textContent;
					}
				});
			}
		});

		$(".product-info-parameters__key").each(function(i, el){
			if (el.textContent == "Размер полотна") el.textContent = "Размер";
		});
	});
</script>
