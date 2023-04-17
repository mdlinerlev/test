<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
if (!empty($arResult['ITEMS'])){
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
define("ML_DEKOR_CONST", false);
unset($currencyList, $templateLibrary);

$skuTemplate = array();
if (!empty($arResult['SKU_PROPS'])) {
    foreach ($arResult['SKU_PROPS'] as $arProp) {

        $propId = $arProp['ID'];
        $skuTemplate[$propId] = array(
            'SCROLL' => array(
                'START' => '',
                'FINISH' => '',
            ),
            'FULL' => array(
                'START' => '',
                'FINISH' => '',
            ),
            'ITEMS' => array()
        );
        $templateRow = '';
        if ('TEXT' == $arProp['SHOW_MODE']) {
            $skuTemplate[$propId]['SCROLL']['START'] = '<div class="catalog-item-aside__block bx_item_detail_size full" id="#ITEM#_prop_' . $propId . '_cont">' .
                '<span class="catalog-item-aside__title">' . htmlspecialcharsbx($arProp['NAME']) . '</span>' .
                '<div class="catalog-item-aside__links catalog-item-aside__params" id="#ITEM#_prop_' . $propId . '_list" style="width: #WIDTH#;">';
            $skuTemplate[$propId]['SCROLL']['FINISH'] = '' .
                '<div class="bx_slide_left" id="#ITEM#_prop_' . $propId . '_left" data-treevalue="' . $propId . '" style=""></div>' .
                '<div class="bx_slide_right" id="#ITEM#_prop_' . $propId . '_right" data-treevalue="' . $propId . '" style=""></div>' .
                '</div></div>';

            $skuTemplate[$propId]['FULL']['START'] = '<div class="catalog-item-aside__block bx_item_detail_size" id="#ITEM#_prop_' . $propId . '_cont">' .
                '<span class="catalog-item-aside__title">' . htmlspecialcharsbx($arProp['NAME']) . '</span>' .
                '<div class="catalog-item-aside__links catalog-item-aside__params" id="#ITEM#_prop_' . $propId . '_list" style="width: #WIDTH#;">';;
            $skuTemplate[$propId]['FULL']['FINISH'] = '' .
                '<div class="bx_slide_left" id="#ITEM#_prop_' . $propId . '_left" data-treevalue="' . $propId . '" style="display: none;"></div>' .
                '<div class="bx_slide_right" id="#ITEM#_prop_' . $propId . '_right" data-treevalue="' . $propId . '" style="display: none;"></div>' .
                '</div></div>';
            foreach ($arProp['VALUES'] as $value) {
                if(isset($value['XML_ID'])) {
                    $propArrs[$value['ID']] = $value['XML_ID'];
                } else {
                    $propArrs[$value['ID']] = $value['NAME'];
                }
                $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                $skuTemplate[$propId]['ITEMS'][$value['ID']] = '<a data-offer-id="#OFFER_ID#" data-perent="#PRODUCT_ID#" data-treevalue="' . $propId . '_' . $value['ID'] .
                    '" data-onevalue="' . $value['ID'] . '" class="catalog-item-aside__link" title="' . $value['NAME'] . '">' . $value['NAME'] . '</a>';
            }
            unset($value);
        } elseif ('PICT' == $arProp['SHOW_MODE']) {
            $skuTemplate[$propId]['SCROLL']['START'] = '<div class="catalog-item-aside__block bx_item_detail_scu full" id="#ITEM#_prop_' . $propId . '_cont">' .
                '<span class="catalog-item-aside__title">' . htmlspecialcharsbx($arProp['NAME']) . '</span>' .
                '<div class="catalog-item-aside__colors catalog-item-aside__params" id="#ITEM#_prop_' . $propId . '_list">';
            $skuTemplate[$propId]['SCROLL']['FINISH'] = '' .
                '<div class="bx_slide_left" id="#ITEM#_prop_' . $propId . '_left" data-treevalue="' . $propId . '" style=""></div>' .
                '<div class="bx_slide_right" id="#ITEM#_prop_' . $propId . '_right" data-treevalue="' . $propId . '" style=""></div>' .
                '</div></div>';

            $skuTemplate[$propId]['FULL']['START'] = '<div class="catalog-item-aside__block bx_item_detail_scu" id="#ITEM#_prop_' . $propId . '_cont">' .
                '<span class="catalog-item-aside__title">' . htmlspecialcharsbx($arProp['NAME']) . '</span>' .
                '<div class="catalog-item-aside__colors catalog-item-aside__params" id="#ITEM#_prop_' . $propId . '_list" >';
            $skuTemplate[$propId]['FULL']['FINISH'] = '' .
                '<div class="bx_slide_left" id="#ITEM#_prop_' . $propId . '_left" data-treevalue="' . $propId . '" style="display: none;"></div>' .
                '<div class="bx_slide_right" id="#ITEM#_prop_' . $propId . '_right" data-treevalue="' . $propId . '" style="display: none;"></div>' .
                '</div></div>';
            foreach ($arProp['VALUES'] as $value) {
                if(isset($value['XML_ID'])) {
                    $propArrs[$value['ID']] = $value['XML_ID'];
                } else {
                    $propArrs[$value['ID']] = $value['NAME'];
                }
                $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                //$skuTemplate[$propId]['ITEMS'][$value['ID']] = '<a data-treevalue="'.$propId.'_'.$value['ID'].'" data-onevalue="'.$value['ID'].'"'.'class="catalog-item-aside__color" style="background-image:url(\''.$value['PICT']['SRC'].'\');" title="" data-tooltip="'.$value['NAME'].'"></a>';
                //pr($arProp);
                $skuTemplate[$propId]['ITEMS'][$value['ID']] = '<div class="tooltip1"><a data-offer-id="#OFFER_ID#" data-perent="#PRODUCT_ID#" data-treevalue="' . $propId . '_' . $value['ID'] .
                    '" data-onevalue="' . $value['ID'] . '"' .
                    'class="catalog-item-aside__color" title=""><img class="imgcolor" src="' . SITE_TEMPLATE_PATH . '/preload.svg" data-src="' . $value['PICT']['SRC'] . '" alt="" /><span class="tooltiptext">' . $value['NAME'] . '</span></a></div>';
            }
            unset($value);
        }
    }
    unset($templateRow, $arProp);
}

if ($arParams["DISPLAY_TOP_PAGER"]) {
    ?><? echo $arResult["NAV_STRING"]; ?><?
}

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$ibFirstSectionIdCode = (!empty($arResult["~IBLOCK_SECTION_ID"]) && !empty($arResult['SECTION_ITEMS'][$arResult["~IBLOCK_SECTION_ID"]])) ? $arResult['SECTION_ITEMS'][$arResult["~IBLOCK_SECTION_ID"]]['CODE'] : false;
?>


<div class="catalog__list <?= $ibFirstSectionIdCode == 'napolnye_pokrytiya' ? ' catalog--floor' : '' ?>">


    <div class="catalog__list_inner <?=$arResult['HEIGHT300']?'xxs-item-height-300':''?>">
        <?
        foreach ($arResult['ITEMS'] as $key => $arItem) {
            $arAllPRopVal = [];
            foreach ($arItem['JS_OFFERS'] as $v) {
                foreach ($v['SKY_PROP'] as $k => $item) {

                    $arAllPRopVal[$item] = $item;
                }
            }
        }

        foreach ($arAllPRopVal as $v) {
            $min = 500;
            foreach ($arItem['JS_OFFERS'] as $offer) {
                if ($offer['SKY_PROP'][$v]) {
                    $sort = $offer['SORT'];
                    if ($sort < $min) {
                        $min = $sort;
                        $arPropRez[$v] = $min;
                    }
                }
            }
        }
        foreach ($arResult['ITEMS'] as $key => $arItem) {
            $arDinamicHits = [];
            foreach ($arItem["OFFERS"] as $offer) {
                foreach ($offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE"] as $index => $prop) {
                    $hitColor = strlen($offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE_XML_ID"][$index]) > 7 ? "#ff652e" : $offer["PROPERTIES"]["DINAMIC_HITS"]["VALUE_XML_ID"][$index];
                    $arDinamicHits[] = array("HIT" => $prop, "OFFER" => $offer["ID"], "COLOR" => $hitColor);
                }
            }

            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);

            $APPLICATION->IncludeComponent("coffeediz:schema.org.Product", "catProducts", Array(
                "COMPONENT_TEMPLATE" => ".default",
                "SHOW" => "Y",    // Не отображать на сайте
                "NAME" => $arItem["NAME"],    // Название Товара
                "DESCRIPTION" => $arItem["NAME"] . " " . $arItem["NAME"] . " " . $arItem["NAME"],    // Описание Товара
                "AGGREGATEOFFER" => "N",    // Набор из нескольких предложений
                "PRICE" => $arItem["MIN_PRICE"]["VALUE"],    // Цена
                "PRICECURRENCY" => $arItem["MIN_PRICE"]["CURRENCY"],    // Валюта
                "IMAGE" => UrlUtils::getCanonicalUrl() . $arItem["PREVIEW_PICTURE_ORIG"]["SRC"],
                "ITEMAVAILABILITY" => "InStock",    // Доступность
                "ITEMCONDITION" => "NewCondition",    // Состояние товара
                "PAYMENTMETHOD" => "",    // Способ оплаты
                "PARAM_RATING_SHOW" => "N",    // Выводить рейтинг
            ),
                false
            );

            $strMainID = $this->GetEditAreaId($arItem['ID']);

            $sectionId = !empty($arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]]) ? $arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]] : false;
            //$linkProduct =  "/catalog/{$sectionId["CODE"]}/{$arItem["CODE"]}/";
            $linkProduct = $arItem["DETAIL_PAGE_URL"];
            //dump($linkProduct);

            $firstSectionItemCode = !empty($arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]]) ? $arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]]['CODE'] : false;

            //$dveri = in_array($firstSectionItemCode, array('mezhkomnatnye_dveri', 'vkhodnye_dveri'));
            $dveri = strstr($arItem["DETAIL_PAGE_URL"], 'mezhkomnatnye_dveri') || strstr($arItem["DETAIL_PAGE_URL"], 'vkhodnye_dveri');


            $shadow = false;
            if (in_array($arItem['PROPERTIES']['PRODUCT_TYPE']['VALUE_XML_ID'], ['923582043facfa2ff4394a15430e34c7', '6e0954f419342e26e9e81b5ba8d543ac']))
                $shadow = true;

            $ml_dekor = false;
            if ($arItem['PROPERTIES']['PRODUCT_TYPE']['VALUE_XML_ID'] == '8a10341c718b76a99ec4109c946e48e5') {
                $ml_dekor = true;
                $shadow = true;
            }
            //define("ML_DEKOR_CONST", $ml_dekor);


            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID . '_pict',
                'SECOND_PICT' => $strMainID . '_secondpict',
                'STICKER_ID' => $strMainID . '_sticker',
                'SECOND_STICKER_ID' => $strMainID . '_secondsticker',
                'QUANTITY' => $strMainID . '_quantity',
                'QUANTITY_DOWN' => $strMainID . '_quant_down',
                'QUANTITY_UP' => $strMainID . '_quant_up',
                'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                'BUY_LINK' => $strMainID . '_buy_link',
                'BASKET_ACTIONS' => $strMainID . '_basket_actions',
                'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
                'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
                'COMPARE_LINK' => $strMainID . '_compare_link',

                'PRICE' => $strMainID . '_price',
                'DSC_PERC' => $strMainID . '_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
                'PROP_DIV' => $strMainID . '_sku_tree',
                'PROP' => $strMainID . '_prop_',
                'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
            );

            $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

            $productTitle = $arItem['NAME'];
            $imgTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                : $arItem['NAME']
            );
            $imgAlt = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']
                : $arItem['NAME']
            );

            $minPrice = $arItem['MIN_PRICE'];

            if ($arItem['CATALOG_MEASURE_RATIO'] > 1)
                $minPrice['PRINT_DISCOUNT_VALUE'] .= '/м<sup>2</sup>';
            ?>
            <? $confDoor = 'Распашная двойная'; ?>
            <div class="catalog-item <?= ($arItem['PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arItem['PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1 ? 'double_image' : ''); ?>"
                 id="<? echo $strMainID; ?>" data-product-id="<?= $arItem['ID'] ?>">

                <div class="catalog-item__inner <?= empty($shadow) ? ' catalog-item__bg_small' : '' ?>">

                    <div class="catalog-item__bg"></div>

                    <div class="catalog-item__top">
                        <div class="catalog-item__image-container">
                            <div>
                                <?
                                $hash = $arItem['IBLOCK_ID'] == IBLOCK_ID_OFFERS ? '#offer' . $arItem['ID'] : '';
                                list($width, $height, $type, $attr) = getimagesize($_SERVER["DOCUMENT_ROOT"] . $arItem['PREVIEW_PICTURE']['SRC']);
                                ?>
                                <a href="<?= $linkProduct . $hash ?>"
                                   class="catalog-item__image-link">
                                    <? if ($arItem['PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arItem['PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1) { ?>

                                        <?//pr($arItem['PREVIEW_PICTURE_ORIG']);?>
                                        <img id="<? echo $arItemIDs['PICT']; ?>_1"
                                             class="catalog-item__image <?= $dveri ? 'dveri' : '' ?>"
                                            <? //= $shadow ? ' style="opacity: 0;" ' : ''?>
                                             data-src="<? echo $arItem['PREVIEW_PICTURE_ORIG']['src']; ?>"
                                             src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" alt="<?= $imgAlt ?>"
                                             title="<?= $imgTitle ?>"
                                        >
                                    <? } ?>
                                    <img id="<? echo $arItemIDs['PICT']; ?>"
                                         class="catalog-item__image <?= $dveri ? 'dveri' : '' ?>"
                                        <? //= $shadow ? ' style="opacity: 0;" ' : ''?>
                                         data-src="<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>"
                                         src="<?= SITE_TEMPLATE_PATH ?>/preload.svg" alt="<?= $imgAlt ?>"
                                         title="<?= $imgTitle ?>"
                                    >
                                    <? if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES'])) { ?>
                                        <div class="catalog-item__badge-container">
                                            <?
                                            $haveOffers = (isset($arItem['OFFERS']) || !empty($arItem['OFFERS']));
                                            $boolShowOfferProps = ('Y' == $arParams['PRODUCT_DISPLAY_MODE'] && $arItem['OFFERS_PROPS_DISPLAY']);
                                            if ($haveOffers && $boolShowOfferProps) { ?>
                                                <span id="<? echo $arItemIDs['DISPLAY_PROP_DIV']; ?>"
                                                      class="catalog-item__label_offer" style="display: none;"></span>
                                            <? } ?>

                                            <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp) {
                                                if (($arOneProp['CODE'] == 'SALELEADER') && ($arOneProp['VALUE'])) {
                                                    ?>
                                                    <div class="catalog-item__label catalog-item__label--dark"><?= GetMessage('CT_BCS_SALELEADER') ?></div>
                                                    <?
                                                } elseif (($arOneProp['CODE'] == 'NEWPRODUCT') && ($arOneProp['VALUE'])) {
                                                    ?>
                                                    <div class="catalog-item__label"><?= GetMessage('CT_BCS_NEWPRODUCT') ?></div>
                                                    <?
                                                } elseif (($arOneProp['CODE'] == 'FREE') && ($arOneProp['VALUE'])) {
                                                    ?>
                                                    <div class="catalog-item__label catalog-item__label--dark"><?= GetMessage('CT_BCS_FREE') ?></div>
                                                    <?
                                                } elseif (($arOneProp['ID'] == 'SPECIALOFFER') && ($arOneProp['VALUE'])) {
                                                    ?>
                                                    <div class="catalog-item__label catalog-item__label--dark"><?= GetMessage('CT_BCS_DISCOUNT_DIFF_PERCENT') ?></div>
                                                    <?
                                                }
                                            }
                                            foreach ($arDinamicHits as $hit) {
                                                echo '<div class="catalog-item__label dinamic-hit" data-dinamic-hit="' . $hit["OFFER"] . '" style="--before-background: ' . $hit['COLOR'] . '; background-color: ' . $hit['COLOR'] . ';">' . $hit["HIT"] . '</div>';
                                            }
                                            ?>
                                        </div>
                                    <? } ?>
                                </a>
                            </div>
                        </div>

                        <div class="catalog-item__title-container">
                            <a class="catalog-item__title" href="<?= $linkProduct . $hash ?>"
                               title="<? echo $productTitle; ?>"><? echo $productTitle; ?></a>
                        </div>

                        <div class="catalog-item__price">
                            <?
                            if (!empty($minPrice)) {
                                if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
                                    echo GetMessage(
                                        'CT_BCS_TPL_MESS_PRICE_SIMPLE_MODE',
                                        array(
                                            '#PRICE#' => $minPrice['PRINT_DISCOUNT_VALUE'],
                                            '#MEASURE#' => GetMessage(
                                                'CT_BCS_TPL_MESS_MEASURE_SIMPLE_MODE',
                                                array(
                                                    '#VALUE#' => $minPrice['CATALOG_MEASURE_RATIO'],
                                                    '#UNIT#' => $minPrice['CATALOG_MEASURE_NAME']
                                                )
                                            )
                                        )
                                    );
                                } else {
                                    ?>
                                    <div class="catalog-item-price__discount" id="<? echo $arItemIDs['PRICE']; ?>"><?
                                        echo $minPrice['PRINT_DISCOUNT_VALUE']; ?>
                                    </div>
                                    <?
                                }
                                if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE']) {
                                    ?>
                                    <div class="catalog-item-price__base">
                                    <?
                                    if (!$arItem["OFFERS"]) {
                                        ?>
                                        <span class="catalog-item-price__number"><? echo $minPrice['PRINT_VALUE']; ?></span>
                                        <?
                                    } ?>

                                    <span class="catalog-item-price__badge"><?= $minPrice['DISCOUNT_DIFF_PERCENT']; ?>
                                        <?= GetMessage('CT_BCS_DISCOUNT_DIFF_PERCENT') ?>
                                            </span>
                                    </div><?
                                }
                            }
                            unset($minPrice);
                            ?>
                            <? if ($arParams['USER']) { ?>
                                <? if (!empty($arItem['OFFERS'])) { ?>
                                    <span>В наличиии: <span class="js-quantity_offer"
                                                            data-offer-id="<?= $arItem['ID'] ?>"
                                        >
                                            <?= $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]['CATALOG_QUANTITY'] ?>
                                            <?= $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]['CATALOG_MEASURE_NAME'] ?>
                                        </span>
                                    </span>
                                <? } else { ?>
                                    <span>В наличиии: <?= $arItem['CATALOG_QUANTITY'] ?> <?= $arItem['CATALOG_MEASURE_NAME'] ?></span>
                                <? } ?>
                            <? } ?>
                        </div>
                    </div>

                    <?
                    $showSubscribeBtn = false;
                    $compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCS_TPL_MESS_BTN_COMPARE'));
                    if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) {
                        ?>
                        <div class="catalog-item__bottom"><?
                            if ($arItem['CAN_BUY']) {
                                ?>
                                <div class="catalog-item__bottom-btns">
                                    <!-- Заказать в 1 клик попап -->
                                    <div class="one-click">
                                        <script data-b24-form="click/29/1blk4d" data-skip-moving="true">
                                            (function (w, d, u) {
                                                var s = d.createElement('script');
                                                s.async = true;
                                                s.src = u + '?' + (Date.now() / 180000 | 0);
                                                var h = d.getElementsByTagName('script')[0];
                                                h.parentNode.insertBefore(s, h);
                                            })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_29_1blk4d.js');
                                        </script>
                                        <div data-product_id="<?= $arItem['ID'] ?>" class="one-click-buy button">
                                            Заказать в
                                            1 клик
                                        </div>
                                    </div>
                                    <? if ($arParams['USER']) { ?>
                                        <button class="favorites-btn js-favorite-add"
                                                data-id="<?= $arItem['ID'] ?>">
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/images/favorites-icon.svg" alt="">
                                        </button>
                                    <? } ?>
                                </div>

                                <a class="catalog-item__button catalog-item__button--more button button--secondary"
                                   href="<?= $linkProduct ?>"><?= GetMessage("CT_BCS_TPL_MESS_BTN_DETAIL") ?></a>
                                <?
                            }
                            ?>
                            <div style="clear: both;"></div>
                        </div>

                    <?
                    $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties){
                    ?>
                        <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
                            <?
                            if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                                foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                    ?>
                                    <input type="hidden"
                                           name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                           value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
                                    <?
                                    if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
                                        unset($arItem['PRODUCT_PROPERTIES'][$propID]);
                                }
                            }
                            $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                            ?>
                        </div>
                    <?
                    }
                    $arJSParams = array(
                        'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                        'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
                        'SHOW_ADD_BASKET_BTN' => false,
                        'SHOW_BUY_BTN' => true,
                        'SHOW_ABSENT' => true,
                        'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                        'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                        'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
                        'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                        'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                        'PRODUCT' => array(
                            'ID' => $arItem['ID'],
                            'NAME' => $productTitle,
                            'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
                            'CAN_BUY' => $arItem["CAN_BUY"],
                            'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                            'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                            'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                            'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                            'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                            'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                            'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
                        ),
                        'BASKET' => array(
                            'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                            'EMPTY_PROPS' => $emptyProductProperties,
                            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
                        ),
                        'VISUAL' => array(
                            'ID' => $arItemIDs['ID'],
                            'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
                            'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                            'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                            'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                            'PRICE_ID' => $arItemIDs['PRICE'],
                            'BUY_ID' => $arItemIDs['BUY_LINK'],
                            'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
                            'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                            'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                            'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
                        ),
                        'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                    );
                    if ($arParams['DISPLAY_COMPARE']) {
                        $arJSParams['COMPARE'] = array(
                            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                            'COMPARE_PATH' => $arParams['COMPARE_PATH']
                        );
                    }
                    unset($emptyProductProperties);
                    ?>
                        <script type="text/javascript">
                            var <? echo $strObName; ?> =
                            new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            console.log(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                        </script><?
                    } else {
                    if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']) {
                    $canBuy = $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['CAN_BUY'];
                    if (!empty($dveri) || ($ml_dekor == true)){
                    if ($arItem['OFFERS_PROPS_DISPLAY']) {
                        foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer) {
                            $strProps = '';
                            if (!empty($arJSOffer['DISPLAY_PROPERTIES'])) {
                                foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp) {

                                    if (($arOneProp['CODE'] == 'SALELEADER') && ($arOneProp['VALUE'])) {
                                        $strProps .= '<div class="catalog-item__label catalog-item__label--dark">' . GetMessage('CT_BCS_SALELEADER') . '</div>';
                                    } elseif (($arOneProp['CODE'] == 'NEWPRODUCT') && ($arOneProp['VALUE'])) {
                                        $strProps .= '<div class="catalog-item__label">' . GetMessage('CT_BCS_NEWPRODUCT') . '</div>';
                                    } elseif (($arOneProp['CODE'] == 'FREE') && ($arOneProp['VALUE'])) {
                                        $strProps .= '<div class="catalog-item__label catalog-item__label--dark">' . GetMessage('CT_BCS_FREE') . '</div>';
                                    } elseif (($arOneProp['ID'] == 'SPECIALOFFER') && ($arOneProp['VALUE'])) {
                                        $strProps .= '<div class="catalog-item__label catalog-item__label--dark">' . GetMessage('CT_BCS_DISCOUNT_DIFF_PERCENT') . '</div>';
                                    }

                                }
                            }
                            $arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                        }
                    }
                    ?>
                        <div class="catalog-item__bottom">
                            <? if (!$canBuy): ?>
                                <div id="<? echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>"
                                     class="bx_catalog_item_controls_blockone">
                                                <span class="bx_notavailable">
                                                    <? echo('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE')); ?>
                                                </span>
                                </div>
                            <? endif; ?>
                            <div class="catalog-item__bottom-btns">
                                <!-- Заказать в 1 клик попап -->
                                <div class="one-click">
                                    <script data-b24-form="click/29/1blk4d" data-skip-moving="true">
                                        (function (w, d, u) {
                                            var s = d.createElement('script');
                                            s.async = true;
                                            s.src = u + '?' + (Date.now() / 180000 | 0);
                                            var h = d.getElementsByTagName('script')[0];
                                            h.parentNode.insertBefore(s, h);
                                        })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_29_1blk4d.js');
                                    </script>
                                    <div data-product_id="<?= $arItem['ID'] ?>" class="one-click-buy button">Заказать в
                                        1
                                        клик
                                    </div>
                                </div>
                                <? if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) { ?>
                                    <button class="favorites-btn js-favorite-add" data-item-id="<?= $arItem['ID'] ?>"
                                            data-id="<?= $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['ID'] ?>">
                                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/favorites-icon.svg" alt="">
                                    </button>
                                <? } ?>
                            </div>

                            <a class="catalog-item__button catalog-item__button--more button button--secondary"
                               href="<?= $linkProduct ?>"><?= GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL') ?></a>

                            <?/*
                                        if ($arParams['DISPLAY_COMPARE']) {
                                            ?>
                                            Сравнение в каталоге
                                            <?
                                            if (!in_array($arItem["ID"], $GLOBALS["compare_lists"])) {
                                                ?>
                                                <div id="<?= $arItemIDs["BASKET_ACTIONS"] ?>" class="catalog-item__compare">
                                                    <a rel="nofollow" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"
                                                       href="javascript:void;<? //= str_replace('/catalog/compare/', '', $arItem["COMPARE_URL"])
                                                       ?>">
                                                        <span class="compare_icon catalog-item-compare__text--default"></span>
                                                        <span class="catalog-item-compare__text catalog-item-compare__text--default"><? echo $compareBtnMessage; ?></span>
                                                    </a>
                                                </div>
                                                <?
                                            } else {
                                                ?>
                                                <div id="<?= $arItemIDs["BASKET_ACTIONS"] ?>" class="catalog-item__compare active">
                                                    <a rel="nofollow" id="<? echo $arItemIDs['COMPARE_LINK']; ?>"
                                                       href="/catalog/compare/">
                                                        <span class="catalog-item-compare__text catalog-item-compare__text--active"><? // echo $compareBtnMessage;
                                                            ?><?= GetMessage('CT_BCS_TPL_MESS_BTN_COMPARE_ADD')?>
                                                        </span>
                                                    </a>
                                                </div>
                                                <?
                                            } ?>
                                            <?
                                        }*/
                            ?>
                            <div style="clear: both;"></div>
                        </div>
                        <?
                    }
                        unset($canBuy);
                    }
                    else {
                        ?>
                        <div class="catalog-item__bottom">
                            <a class="catalog-item__button catalog-item__button--more button"
                               href="<?= $linkProduct ?>"><?
                                echo('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL')); ?>
                            </a>
                        </div>
                    <?
                    }

                    if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']){
                    //   echo 'test';
                    if (!empty($arItem['OFFERS_PROP'])){
                    $arSkuProps = array();
                    if (!empty($dveri) || ($ml_dekor == true)){
                    ?>
                        <div class="catalog-item__aside bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
                            <?

                            foreach ($skuTemplate as $propId => $propTemplate) {
                                //pr($propTemplate['ITEMS']);
                                if (!isset($arItem['SKU_TREE_VALUES'][$propId]))
                                    continue;
                                $valueCount = count($arItem['SKU_TREE_VALUES'][$propId]);

                                foreach ($propTemplate['ITEMS'] as $id => $value) {
                                    if (!isset($arItem['SKU_TREE_VALUES'][$propId][$id])) {
                                        $newArr[$id]['VAL'] = $value;
                                    }
                                    $newArr[$id]['VAL'] = $value;
                                    if(!isset($arPropRez[strip_tags($value)])) {
                                        if($id = 92) {
                                            $newArr[$id]['SORT'] = 0;
                                        } else {
                                            $newArr[$id]['SORT'] = $arPropRez[$propArrs[$id]];
                                        }

                                    } else {
                                        $newArr[$id]['SORT'] = $arPropRez[strip_tags($value)];
                                    }

                                }
                                uasort($newArr, function($a, $b){
                                    $a = $a['SORT'];
                                    $b = $b['SORT'];
                                    if ($a == $b) {
                                        return 0;
                                    }
                                    return ($a < $b) ? -1 : 1;
                                });
                                if(!empty($newArr[339])) {
                                    $tempCol = $newArr[339];
                                    unset($newArr[339]);
                                    $newArr[339] = $tempCol;
                                }

                                $fullWidth = '100%';
                                $itemWidth = '20%';
                                $rowTemplate = $propTemplate['FULL'];
                                unset($valueCount);

                                echo str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $fullWidth), $rowTemplate['START']);
                                global $USER;
                                foreach ($newArr as $value => $valueItem) {
                                    if( ($value == 92 || $value == 339 || $value == 231) && !$USER->GetID() > 0) continue;
                                    if (!isset($arItem['SKU_TREE_VALUES'][$propId][$value]))
                                        continue;
                                    foreach ($arItem["OFFERS"] as $index => $OFFER) {
                                        if ($OFFER["TREE"] && isset($OFFER['TREE']['PROP_' . $propId]) && $OFFER['TREE']['PROP_' . $propId] == $value) {
                                            $offer_id = $OFFER["ID"];
                                            break;
                                        }
                                    }
                                    echo str_replace(array('#ITEM#_prop_', '#WIDTH#', '#OFFER_ID#', '#PRODUCT_ID#'), array($arItemIDs['PROP'], $itemWidth, $offer_id, $arItem["ID"]), $valueItem['VAL']);
                                }
                                unset($value, $valueItem);
                                echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $rowTemplate['FINISH']);
                            }
                            unset($propId, $propTemplate);
                            foreach ($arResult['SKU_PROPS'] as $arOneProp) {
                                if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
                                    continue;
                                $arSkuProps[] = array(
                                    'ID' => $arOneProp['ID'],
                                    'SHOW_MODE' => $arOneProp['SHOW_MODE'],
                                    'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
                                );
                            }
                            foreach ($arItem['JS_OFFERS'] as &$arOneJs) {
                                $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = 1000000;
                                if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT']) {
                                    $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-' . $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] . '%';
                                    $arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-' . $arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] . '%';
                                }
                            }
                            unset($arOneJs);
                            ?>
                        </div>
                    <?
                    }
                    if(isset($arResult['ITEMS'][$key]['OFFER_SELECTED2'])) {
                        $selected = $arResult['ITEMS'][$key]['OFFER_SELECTED2'];
                    } else {
                        $selected = $arItem['OFFERS_SELECTED'];
                    }
                    $arJSParams = array(
                        'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                        'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
                        'SHOW_ADD_BASKET_BTN' => false,
                        'SHOW_BUY_BTN' => true,
                        'SHOW_ABSENT' => true,
                        'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
                        'SECOND_PICT' => $arItem['SECOND_PICT'],
                        'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                        'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                        'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                        'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
                        'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                        'DEFAULT_PICTURE' => array(
                            'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                            'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                        ),
                        'VISUAL' => array(
                            'ID' => $arItemIDs['ID'],
                            'PICT_ID' => $arItemIDs['PICT'],
                            'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                            'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                            'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                            'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                            'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                            'PRICE_ID' => $arItemIDs['PRICE'],
                            'TREE_ID' => $arItemIDs['PROP_DIV'],
                            'TREE_ITEM_ID' => $arItemIDs['PROP'],
                            'BUY_ID' => $arItemIDs['BUY_LINK'],
                            'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'] ?: 'asd',
                            'DSC_PERC' => $arItemIDs['DSC_PERC'],
                            'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                            'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                            'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                            'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
                        ),
                        'BASKET' => array(
                            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                            'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
                            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
                        ),
                        'PRODUCT' => array(
                            'ID' => $arItem['ID'],
                            'NAME' => $productTitle
                        ),
                        'OFFERS' => $arItem['JS_OFFERS'],
                        'OFFER_SELECTED' => $selected,
                        'TREE_PROPS' => $arSkuProps,
                        'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                    );
                    if ($arParams['DISPLAY_COMPARE']) {
                        $arJSParams['COMPARE'] = array(
                            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                            'COMPARE_PATH' => $arParams['COMPARE_PATH']
                        );
                    }
                    ?>
                        <script>
                            var <? echo $strObName; ?> =
                            new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            console.log(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            BX.addCustomEvent('onAjaxSuccess', function () {
                                var <? echo $strObName; ?> =
                                new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            });
                        </script>
                    <?
                    }
                    } else {
                    $arJSParams = array(
                        'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                        'SHOW_QUANTITY' => false,
                        'SHOW_ADD_BASKET_BTN' => false,
                        'SHOW_BUY_BTN' => false,
                        'SHOW_ABSENT' => false,
                        'SHOW_SKU_PROPS' => false,
                        'SECOND_PICT' => $arItem['SECOND_PICT'],
                        'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                        'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                        'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
                        'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
                        'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
                        'DEFAULT_PICTURE' => array(
                            'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                            'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                        ),
                        'VISUAL' => array(
                            'ID' => $arItemIDs['ID'],
                            'PICT_ID' => $arItemIDs['PICT'],
                            'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                            'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                            'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                            'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                            'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                            'PRICE_ID' => $arItemIDs['PRICE'],
                            'TREE_ID' => $arItemIDs['PROP_DIV'],
                            'TREE_ITEM_ID' => $arItemIDs['PROP'],
                            'BUY_ID' => $arItemIDs['BUY_LINK'],
                            'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                            'DSC_PERC' => $arItemIDs['DSC_PERC'],
                            'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                            'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                            'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                            'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
                        ),
                        'BASKET' => array(
                            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                            'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
                            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
                        ),
                        'PRODUCT' => array(
                            'ID' => $arItem['ID'],
                            'NAME' => $productTitle
                        ),
                        'OFFERS' => array(),
                        'OFFER_SELECTED' => 0,
                        'TREE_PROPS' => array(),
                        'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
                    );
                    if ($arParams['DISPLAY_COMPARE']) {
                        $arJSParams['COMPARE'] = array(
                            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                            'COMPARE_PATH' => $arParams['COMPARE_PATH']
                        );
                    }
                    ?>
                        <script type="text/javascript">
                            var <? echo $strObName; ?> =
                            new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                            console.log(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
                        </script>
                        <?
                    }
                    }
                    ?>
                </div>
            </div>
        <? } ?>
    </div>


    <div style="clear: both;"></div>


    <div class="show-more">
        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]) { ?>
            <? echo $arResult["NAV_STRING"]; ?>
        <? } ?>
    </div>
    <? } ?>


    <script type="text/javascript">
        BX.message({
            BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
            BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
            ADD_TO_BASKET_OK: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            TITLE_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR') ?>',
            TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS') ?>',
            TITLE_SUCCESSFUL: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
            BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE') ?>',
            BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
            COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK') ?>',
            COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
            COMPARE_TITLE: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE') ?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
            SITE_ID: '<? echo SITE_ID; ?>'
        });
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
            var source = "https://creativecdn.com/tags?type=iframe&id=pr_B6MjHKf0gPbC1LVhT75b_category2_<?=$arResult["ID"]?>&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + encodeURIComponent(timestamp);
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
        var ml_dekor = <?php if ($ml_dekor == true) echo 1; else echo 0; ?>;
        if (ml_dekor == 1) {
            $(".catalog-item-aside__title").each(function (i, el) {
                if (el.textContent == "Размер полотна") el.textContent = "Размер";
            });
        }
    </script>
