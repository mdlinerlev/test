<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager;

$request = Application::getInstance()->getContext()->getRequest();

/*breadcrumbs*/
$breadcrumbsCode = explode('/', CURPAGE) ?>

<?php
$arSelect = Array("ID", 'CODE', 'UF_SECTION_CHAIN', 'NAME');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], 'SECTION_CODE' => $breadcrumbsCode[2] );
$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);
while($ob = $res->GetNext())
{
    $dataSectionBreadcrumbs[] = $ob;
}

$sectionParentCode = $breadcrumbsCode[2]; //код верхней родительской секции
$sectionCode = $breadcrumbsCode[3]; //код текущуй секции

foreach($dataSectionBreadcrumbs as $key => $value) {
    if($value['CODE'] == $sectionCode){
        if(!empty($value['UF_SECTION_CHAIN']) && $sectionCode !== 'filter'){
            // pr($dataSectionBreadcrumbs);
            $idParentSection = $value['UF_SECTION_CHAIN']; //ID родительского раздела из свойства
        }
    }
}

foreach($dataSectionBreadcrumbs as $key => $value) {
    if($value['CODE'] == $breadcrumbsCode[2]){
        $upperParentBreadcrumbs['NAME'] = $value['NAME'];
        $upperParentBreadcrumbs['PATH'] = "/catalog/$value[CODE]/";
    }

    if($value['ID'] == $idParentSection){
        $parentBreadcrumbs['NAME'] = $value['NAME']; //забираем родительские имя и код - для формирования хлебных крошек
        $parentBreadcrumbs['PATH'] = "/catalog/$breadcrumbsCode[2]/$value[CODE]/";
    }

    if($value['CODE'] == $breadcrumbsCode[3]){
        $breadcrumbs['NAME'] = $value['NAME']; //забираем родительские имя и код - для формирования хлебных крошек
        $breadcrumbs['PATH'] = "/catalog/$breadcrumbsCode[2]/$value[CODE]/";
    }
}
$pathParentSection = '/' . $breadcrumbsCode[1]. '/' . $breadcrumbsCode[2] . '/' . $codeBreadcrumbs . '/';

if(!empty($parentBreadcrumbs)){
    $APPLICATION->AddChainItem($upperParentBreadcrumbs['NAME'], $upperParentBreadcrumbs['PATH']);
    $APPLICATION->AddChainItem($parentBreadcrumbs['NAME'],  $parentBreadcrumbs['PATH']);
    $APPLICATION->AddChainItem($breadcrumbs['NAME'],  $breadcrumbs['PATH']);
}
/*end breadcrumbs*/

?>
    <h1 class="catalog__title">
        <? if (mb_strpos($APPLICATION->GetCurPage(), '/filter/') !== false||
            empty($arCurSection["UF_TITLE_H1"] && empty($arCurSection["NAME"]))) {
            $APPLICATION->ShowTitle(false);
        } else {
            echo $arCurSection["UF_TITLE_H1"] ?: $arCurSection["NAME"];
        } ?>
    </h1>

    <div class="catalog-wrapper">

        <? if ($isFilter || $isSidebar): ?>

            <? if ($isFilter): ?>
                <aside class="sidebar sidebar--filters">
                    <?
                    global $smartPreFilter;
                    $smartPreFilter = array (
                        ">CATALOG_PRICE_1" => 0
                    );
                    $isFilter = false;
                    foreach ($arFilter123 as $key => $arFilter){
                        if(strpos($key, 'PROPERTY_167') !== false){
                            $isFilter = true;
                            break;
                        }
                    }

                    if (!$isFilter) {
                        $arFilter123['!PROPERTY_CONFIGURATION'] = [
                            'Распашная двойная',
                            'Купе',
                            'Купе двойное'
                        ];

                    }
                    ?>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:catalog.smart.filter",
                        "100up_filter",
                        array(
                            "PREFILTER_NAME" => "smartPreFilter",
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "SECTION_ID" => $arCurSection['IBLOCK_SECTION_ID'] && $arCurSection['IBLOCK_SECTION_ID'] != SECTION_ID_FURNITURA ? $arCurSection['IBLOCK_SECTION_ID'] : $arCurSection["ID"],
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "SAVE_IN_SESSION" => "N",
                            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                            "XML_EXPORT" => "Y",
                            "SECTION_TITLE" => "NAME",
                            "SECTION_DESCRIPTION" => "DESCRIPTION",
                            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                            "SEF_MODE" => $arParams["SEF_MODE"],
                            "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                        ),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    ); ?>

                    <?php
                    if ($_GET['avail'] == 1) {
                        $GLOBALS[$arParams["FILTER_NAME"]][] = array(
                            'LOGIC' => 'OR',
                            array('>CATALOG_QUANTITY' => 0),
                            array('=ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
                                'IBLOCK_ID' => IBLOCK_ID_OFFERS,
                                'ACTIVE' => 'Y',
                                '>CATALOG_QUANTITY' => 0
                            )))
                        );
                        //$GLOBALS[$arParams["FILTER_NAME"]]["OFFERS"] = array('>CATALOG_QUANTITY' => 0);
                    }
                    if ($_GET['discount'] == 1) {
                        $GLOBALS[$arParams["FILTER_NAME"]][] = array(
                            'LOGIC' => 'OR',
                            array('>PROPERTY_OLD_PRICE' => 0),
                            array('=ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
                                'IBLOCK_ID' => IBLOCK_ID_OFFERS,
                                'ACTIVE' => 'Y',
                                '>PROPERTY_OLD_PRICE' => 0
                            )))
                        );
                    }
                    ?>
                </aside>
            <? endif; ?>

            <? if ($isSidebar): ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => $arParams["SIDEBAR_PATH"],
                        "AREA_FILE_RECURSIVE" => "N",
                        "EDIT_MODE" => "html",
                    ),
                    false,
                    array('HIDE_ICONS' => 'Y')
                ); ?>
            <? endif ?>

        <? endif ?>

        <div class="catalog__content">

            <?
            if (ModuleManager::isModuleInstalled("sale")) {
                $arRecomData = array();
                $recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
                $obCache = new CPHPCache();
                if ($obCache->InitCache(36000, serialize($recomCacheID), "/sale/bestsellers")) {
                    $arRecomData = $obCache->GetVars();
                } elseif ($obCache->StartDataCache()) {
                    if (Loader::includeModule("catalog")) {
                        $arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
                        $arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
                    }
                    $obCache->EndDataCache($arRecomData);
                }

                if (!empty($arRecomData) && $arParams['USE_GIFTS_SECTION'] === 'Y') {
                    $APPLICATION->IncludeComponent(
                        "bitrix:sale.gift.section",
                        ".default",
                        array(
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            "PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
                            "SHOW_NAME" => "Y",
                            "SHOW_IMAGE" => "Y",
                            "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                            "MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
                            "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                            "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                            "TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "ORDER_FILTER_NAME" => "arOrderFilter",
                            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action") . '_sgs',
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                            "USE_PRODUCT_QUANTITY" => 'N',
                            "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
                            "OFFER_TREE_PROPS_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                            "ADDITIONAL_PICT_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
                            "ADDITIONAL_PICT_PROP_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],

                            "SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                            "SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
                            "TEXT_LABEL_GIFT" => $arParams['GIFTS_SECTION_LIST_TEXT_LABEL_GIFT'],
                            "HIDE_BLOCK_TITLE" => $arParams['GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE'],
                            "BLOCK_TITLE" => $arParams['GIFTS_SECTION_LIST_BLOCK_TITLE'],
                            "PAGE_ELEMENT_COUNT" => $arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
                            "LINE_ELEMENT_COUNT" => $arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],

                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        )
                        + array(
                            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                }
            }

            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

            $arSort = array(
                'sort' => array(
                    "NAME" => GetMessage("CP_SORT_POPULAR"),
                    "LINK" => "sort=sort&method=asc",
                    "ORDER" => "asc",
                    "PROPERTY" => "sort"
                ),
                'property_MINIMUM_PRICE' => array(
                    "NAME" => GetMessage("CP_SORT_PRICE_ASC"),
                    "LINK" => "sort=property_MINIMUM_PRICE&method=asc",
                    "ORDER" => "asc",
                    "PROPERTY" => "property_MINIMUM_PRICE"
                ),
                'name' => array(
                    "NAME" => GetMessage("CP_SORT_PRICE_DESC"),
                    "LINK" => "sort=property_MINIMUM_PRICE&method=desc",
                    "ORDER" => "desc",
                    "PROPERTY" => "property_MINIMUM_PRICE"
                ),
            );

            $arOrder = array(
                'desc' => array(
                    "NAME" => GetMessage("CP_SORT_ORDER_DESC"),
                    "LINK" => "method=desc",
                ),
                'asc' => array(
                    "NAME" => GetMessage("CP_SORT_ORDER_ASC"),
                    "LINK" => "method=asc",
                )
            );

            $sortField = 'sort';

            if (!empty($request['sort']) && !empty($arSort[$request['sort']])) {
                $sortField = $request['sort'];
            }

            $sortOrder = 'asc';

            if (!empty($request['method']) && !empty($arOrder[$request['method']])) {
                $sortOrder = $request['method'];
            }

            $arParams["ELEMENT_SORT_FIELD"] = $sortField;
            $arParams["ELEMENT_SORT_ORDER"] = $sortOrder;

            global $arFilter123;
            $isFilter = false;
            foreach ($arFilter123 as $key => $arFilter){
                if(strpos($key, 'PROPERTY_'.CONFIGURATION) !== false){
                    $isFilter = true;
                    break;
                }
            }

            if (!$isFilter) {
                $arFilter123['!PROPERTY_CONFIGURATION'] = [
                    'Распашная двойная',
                    'Купе',
                    'Купе двойное'
                ];

            }
            $arFilter123['>CATALOG_PRICE_1'] = 0;
            //pr($arFilter123);
            ?>
            <?
            $APPLICATION->IncludeComponent(
                "sotbit:seo.meta.tags",
                ".default",
                Array(
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CNT_TAGS" => "",
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "SECTION_ID" => $arCurSection['IBLOCK_SECTION_ID'] ?: $arCurSection["ID"],
                    "SORT" => "CONDITIONS",
                    "SORT_ORDER" => "desc",
                    "COMPONENT_TEMPLATE" => ".default",
                ), false,
                array("HIDE_ICONS" => "Y")
            );
            ?>
            <? if (!empty($arCurSection['UF_SLIDERS'])):
                global $arSliderFilter;
                $arSliderFilter['=ID'] = $arCurSection['UF_SLIDERS'];
                ?>
                <? $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "section_slider",
                array(
                    "TITLE_NAME" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "COMPONENT_TEMPLATE" => "section_slider",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "arSliderFilter",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "28",
                    "IBLOCK_TYPE" => "content",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "10",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array(
                        0 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "SORT",
                    "SORT_BY2" => "ID",
                    "SORT_ORDER1" => "DESC",
                    "SORT_ORDER2" => "DESC",
                    "STRICT_SECTION_CHECK" => "N"
                ),
                false,
                array(
                    "ACTIVE_COMPONENT" => "Y"
                )
            ); ?>
            <? endif; ?>


            <?
            if (empty($arCurSection['UF_BANNER']) && !empty($arCurSection['IBLOCK_SECTION_ID'])) {

                $arFilter = array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ACTIVE" => "Y",
                    "GLOBAL_ACTIVE" => "Y",
                    "ID" => $arCurSection["IBLOCK_SECTION_ID"]
                );
                $obCache = new CPHPCache();
                if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
                    $arCurSectionParent = $obCache->GetVars();
                } elseif ($obCache->StartDataCache()) {
                    $arCurSectionParent = array();
                    if (Loader::includeModule("iblock")) {
                        $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID", "UF_BANNER", "UF_BANNER_LINK", "UF_BANNER_2", "UF_BANNER_2_LINK"));

                        if (defined("BX_COMP_MANAGED_CACHE")) {
                            global $CACHE_MANAGER;
                            $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                            if ($arCurSectionParent = $dbRes->Fetch())
                                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);

                            $CACHE_MANAGER->EndTagCache();
                        } else {
                            if (!$arCurSectionParent = $dbRes->Fetch())
                                $arCurSectionParent = array();
                        }
                    }
                    $obCache->EndDataCache($arCurSectionParent);
                }
                if (!isset($arCurSectionParent))
                    $arCurSectionParent = array();

                if (!empty($arCurSectionParent)) {
                    $arCurSection['UF_BANNER'] = $arCurSectionParent['UF_BANNER'];
                    $arCurSection['UF_BANNER_LINK'] = $arCurSectionParent['UF_BANNER_LINK'];
                    $arCurSection['UF_BANNER_2'] = $arCurSectionParent['UF_BANNER_2'];
                    $arCurSection['UF_BANNER_2_LINK'] = $arCurSectionParent['UF_BANNER_2_LINK'];
                }
            }

            ?>


            <?
            $banner = $arCurSection;

            if (empty($banner['UF_BANNER']) && !empty($banner["IBLOCK_SECTION_ID"])) {


                $arFilter = array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ACTIVE" => "Y",
                    "GLOBAL_ACTIVE" => "Y",
                );

                $arFilter["ID"] = $banner["IBLOCK_SECTION_ID"];

                $obCache = new CPHPCache();
                if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
                    $arCurSectionParent = $obCache->GetVars();
                } elseif ($obCache->StartDataCache()) {
                    $arCurSectionParent = array();
                    if (Loader::includeModule("iblock")) {
                        $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID", "CODE", "IBLOCK_SECTION_ID", 'UF_SLIDERS', "UF_FILTER", "UF_DESCR", "UF_DESCR_HTML", "DESCRIPTION", "UF_BANNER", "UF_BANNER_LINK", "UF_BANNER_2", "UF_BANNER_2_LINK"));

                        if (defined("BX_COMP_MANAGED_CACHE")) {
                            global $CACHE_MANAGER;
                            $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                            if ($arCurSectionParent = $dbRes->Fetch())
                                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);

                            $CACHE_MANAGER->EndTagCache();
                        } else {
                            if (!$arCurSectionParent = $dbRes->Fetch())
                                $arCurSectionParent = array();
                        }
                    }
                    $obCache->EndDataCache($arCurSectionParent);
                }
                if (!isset($arCurSectionParent))
                    $arCurSectionParent = array();

                if (!empty($arCurSectionParent['UF_BANNER'])) {
                    $arCurSection['UF_BANNER'] = $arCurSectionParent['UF_BANNER'];
                    $arCurSection['UF_BANNER_2'] = $arCurSectionParent['UF_BANNER_2'];
                    $arCurSection['UF_BANNER_LINK'] = $arCurSectionParent['UF_BANNER_LINK'];
                    $arCurSection['UF_BANNER_2_LINK'] = $arCurSectionParent['UF_BANNER_2_LINK'];
                }

            }

            ?>


            <div class="section-image__list">
                <? if (!empty($arCurSection['UF_BANNER'])): ?>
                    <div class="section-image__item">
                        <? if (!empty($arCurSection['UF_BANNER_LINK'])): ?>
                        <a href="<?= $arCurSection['UF_BANNER_LINK']; ?>">
                            <? endif; ?>
                            <img src="<?= CFile::GetPath($arCurSection['UF_BANNER']) ?>" alt="">
                            <? if (!empty($arCurSection['UF_BANNER_LINK'])): ?>
                        </a>
                    <? endif; ?>
                    </div>
                <? endif; ?>

                <? if (!empty($arCurSection['UF_BANNER_2'])): ?>
                    <div class="section-image__item">
                        <? if (!empty($arCurSection['UF_BANNER_2_LINK'])): ?>
                        <a href="<?= $arCurSection['UF_BANNER_2_LINK']; ?>">
                            <? endif; ?>
                            <img src="<?= CFile::GetPath($arCurSection['UF_BANNER_2']) ?>" alt="">
                            <? if (!empty($arCurSection['UF_BANNER_2_LINK'])): ?>
                        </a>
                    <? endif; ?>
                    </div>
                <? endif; ?>
            </div>

            <div class="catalog__topbar">

                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "CACHE_TYPE" => 'A',
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                        "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                        "ADD_SECTIONS_CHAIN" => (!empty($parentBreadcrumbs) ? 'N' : 'Y'),
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                ); ?>

                <!--            <h1 class="catalog__title">--><? //= $APPLICATION->ShowTitle(false); ?><!--</h1>-->


                <a class="catalog__filter-link">Фильтр</a>

                <div class="catalog__sort-links">
                    <div class="catalog__sort-container">
                        <ul class="catalog-sort-popup__list">
                            <? foreach ($arSort as $key => $sort): ?>
                                <li class="catalog-sort-popup__item <? if ($arParams['ELEMENT_SORT_FIELD'] == $sort['PROPERTY'] && $arParams['ELEMENT_SORT_ORDER'] == $sort['ORDER']) { ?>active<? } ?>">
                                    <a rel="nofollow"
                                       href="<?= $APPLICATION->GetCurPageParam($sort['LINK'], ['sort', 'method'], false) ?>"
                                       class="catalog-sort-popup__link"><?= $sort['NAME'] ?></a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                    <div class="catalog__sort-container">
                        <a rel="nofollow" href="javascript:void(0)"
                           class="catalog__sort-link catalog__sort-link--down"><?= !empty($arOrder[$sortOrder]) ? $arOrder[$sortOrder]['NAME'] : $arOrder['asc']['NAME'] ?></a>
                        <div class="catalog__sort-popup">
                            <ul class="catalog-sort-popup__list">
                                <? foreach ($arOrder as $order): ?>
                                    <li class="catalog-sort-popup__item">
                                        <a rel="nofollow"
                                           href="<?= $APPLICATION->GetCurPageParam((!empty($arSort[$sortField]) ? $arSort[$sortField]['LINK'] : $arSort['property_MINIMUM_PRICE']['LINK']) . '&' . $order['LINK'], ['sort', 'method'], false) ?>"
                                           class="catalog-sort-popup__link"><?= $order['NAME'] ?></a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>


            <? $APPLICATION->ShowViewContent('catalog_filter_chips'); ?>

            <? if ((!empty($arCurSection["UF_DESCR"]) || !empty($arCurSection["UF_DESCR_HTML"])) && empty($arResult['VARIABLES']['SMART_FILTER_PATH'])): ?>
                <div class="catalog-text__descr text-content">
                    <?=(!empty($arCurSection["UF_DESCR_HTML"])) ? $arCurSection["~UF_DESCR_HTML"] : $arCurSection["~UF_DESCR"]; ?>
                </div>
            <? endif; ?>
            <? $APPLICATION->ShowViewContent('sotbit_seometa_top_desc'); ?>

            <?
            /*if($arParams["USE_COMPARE"]=="Y")
            {
                */ ?><!--<? /*$APPLICATION->IncludeComponent(
			"bitrix:catalog.compare.list",
			"",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"NAME" => $arParams["COMPARE_NAME"],
				"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
				"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
				"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_ccl",
				"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
				'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED']) ? $arParams['COMPARE_POSITION_FIXED'] : '',
				'POSITION' => isset($arParams['COMPARE_POSITION']) ? $arParams['COMPARE_POSITION'] : ''
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);*/ ?>--><? /*
	}*/

            if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
                $basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
            else
                $basketAction = (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '');

            $intSectionID = 0;
            ?>

            <?
            // Подгрузка контента  при скролинге
            if (array_key_exists("is_ajax", $_REQUEST) && $_REQUEST["is_ajax"] == "y") {
                $APPLICATION->RestartBuffer();
            }
            ?>
            <?foreach ($arFilter123 as $keys => $test) {
                if($keys == '>=CATALOG_PRICE_1') {
                    $arFilter123['>=PROPERTY_MINIMUM_PRICE'] = $test;
                }
                if($keys == '<=CATALOG_PRICE_1]') {
                    $arFilter123['<=PROPERTY_MAXIMUM_PRICE'] = $test;
                }
                if(count((array)$test) == 2 && !empty((array)$test[1])) {
                    $arFilter123['>=PROPERTY_MINIMUM_PRICE'] = $test[0];
                    $arFilter123['<=PROPERTY_MAXIMUM_PRICE'] = $test[1];
                }
            }?>

            <? $intSectionID = $APPLICATION->IncludeComponent(
                "jorique:catalog.section",
                "cat",
                array(
                    "USER" => $USER->isAuthorized(),
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                    "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                    "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                    "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                    "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                    "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "MESSAGE_404" => $arParams["MESSAGE_404"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"],
                    "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                    "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                    "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                    "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                    "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                    "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                    "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                    "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                    "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                    "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                    "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                    "PAGER_BASE_LINK" => $APPLICATION->GetCurDir(),
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],


                    "OFFERS_SORT_FIELD" => "PROPERTY_IS_AVAILABLE",
                    "OFFERS_SORT_FIELD2" => "CATALOG_PRICE_1",
                    "OFFERS_SORT_ORDER" => "DESC",
                    "OFFERS_SORT_ORDER2" => "ASC",

                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                    "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                    "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                    "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                    "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                    "ADD_SECTIONS_CHAIN" => "N",
                    'ADD_TO_BASKET_ACTION' => $basketAction,
                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                    'COMPARE_PATH' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
                    'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                    'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
                    'SECTION_USER_FIELDS' => array(
                        'UF_FILTER'
                    ),
                ),
                $component
            ); ?>
            <?
            if (CModule::IncludeModule("sotbit.seometa")) {
                $APPLICATION->IncludeComponent(
                    "sotbit:seo.meta",
                    ".default",
                    Array(
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "SECTION_ID" => $arCurSection["ID"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                    )
                );
            }
            ?>
            <?
            // Подгрузка контента  при скролинге
            if (array_key_exists("is_ajax", $_REQUEST) && $_REQUEST["is_ajax"] == "y") {
                die();
            }
            ?>
            <? if (!(intval($request["PAGEN_1"]) > 1)) { ?>
                <div class="catalog__text">
                    <div class="text-content">
                        <div class="sobit_desc">
                            <? $APPLICATION->ShowViewContent('sotbit_seometa_bottom_desc'); ?>
                        </div>
                        <div class="sobit_add_desc">
                            <? $APPLICATION->ShowViewContent('sotbit_seometa_add_desc'); ?>
                        </div>
                        <?
                        global $issetCondition;
                        if (!$issetCondition):?>
                            <div class="sec_desc"><?= $arCurSection["DESCRIPTION"] ?></div><? endif; ?>
                    </div>
                </div>
            <? } ?>
            <?
            $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
            unset($basketAction);

            if (ModuleManager::isModuleInstalled("sale")) {
                if (!empty($arRecomData)) {
                    if (!isset($arParams['USE_SALE_BESTSELLERS']) || $arParams['USE_SALE_BESTSELLERS'] != 'N') {
                        ?>

                        <?
                        $APPLICATION->IncludeComponent("bitrix:sale.bestsellers", "", array(
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            "PAGE_ELEMENT_COUNT" => "5",
                            "SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
                            "PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
                            "SHOW_NAME" => "Y",
                            "SHOW_IMAGE" => "Y",
                            "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                            "MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
                            "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                            "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                            "LINE_ELEMENT_COUNT" => 5,
                            "TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "BY" => array(
                                0 => "AMOUNT",
                            ),
                            "PERIOD" => array(
                                0 => "15",
                            ),
                            "FILTER" => array(
                                0 => "CANCELED",
                                1 => "ALLOW_DELIVERY",
                                2 => "PAYED",
                                3 => "DEDUCTED",
                                4 => "N",
                                5 => "P",
                                6 => "F",
                            ),
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "ORDER_FILTER_NAME" => "arOrderFilter",
                            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                            "SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action") . "_slb",
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                            "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
                            "OFFER_TREE_PROPS_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                            "ADDITIONAL_PICT_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'],
                            "ADDITIONAL_PICT_PROP_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP']
                        ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?>

                        <?
                    }
                    if (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N') {
                        ?>

                        <?
                        $APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "", array(
                            "LINE_ELEMENT_COUNT" => 5,
                            "TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action") . "_cbdp",
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                            "SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
                            "SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                            "PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                            "SHOW_NAME" => "Y",
                            "SHOW_IMAGE" => "Y",
                            "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                            "MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
                            "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                            "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                            "PAGE_ELEMENT_COUNT" => 5,
                            "SHOW_FROM_SECTION" => "Y",
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "DEPTH" => "2",
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
                            "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                            "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "SECTION_ID" => $intSectionID,
                            "SECTION_CODE" => "",
                            "SECTION_ELEMENT_ID" => "",
                            "SECTION_ELEMENT_CODE" => "",
                            "LABEL_PROP_" . $arParams["IBLOCK_ID"] => $arParams['LABEL_PROP'],
                            "PROPERTY_CODE_" . $arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
                            "PROPERTY_CODE_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                            "CART_PROPERTIES_" . $arParams["IBLOCK_ID"] => $arParams["PRODUCT_PROPERTIES"],
                            "CART_PROPERTIES_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFERS_CART_PROPERTIES"],
                            "ADDITIONAL_PICT_PROP_" . $arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
                            "ADDITIONAL_PICT_PROP_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams['OFFER_ADD_PICT_PROP'],
                            "OFFER_TREE_PROPS_" . $arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
                            "RCM_TYPE" => (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : '')
                        ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?>

                        <?
                    }
                }
            }
            ?>


        </div>

    </div>

    </div>

    <div class="index-slider__bottom">
        <div class="index-slider-bottom__element">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/slider_icon_1.php"
                )
            );
            ?>
        </div>
        <div class="index-slider-bottom__element">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/slider_icon_2.php"
                )
            );
            ?>
        </div>
        <div class="index-slider-bottom__element">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/slider_icon_3.php"
                )
            );
            ?>
        </div>
        <div class="index-slider-bottom__element">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/slider_icon_4.php"
                )
            );
            ?>
        </div>
    </div>

<? //$APPLICATION->IncludeComponent(
//  "bitrix:catalog.products.viewed",
//  "catalog",
//  array(
//    "COMPONENT_TEMPLATE" => "catalog",
//    "IBLOCK_MODE" => "single",
//    "IBLOCK_TYPE" => "catalog",
//    "IBLOCK_ID" => "2",
//    "SHOW_FROM_SECTION" => "N",
//    "SECTION_ID" =>"",
//    "SECTION_CODE" => "",
//    "SECTION_ELEMENT_ID" => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
//    "SECTION_ELEMENT_CODE" => "",
//    "DEPTH" => "2",
//    "HIDE_NOT_AVAILABLE" => "N",
//    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
//    "PAGE_ELEMENT_COUNT" => "9",
//    "TEMPLATE_THEME" => "blue",
//    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
//    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
//    "ENLARGE_PRODUCT" => "STRICT",
//    "SHOW_SLIDER" => "Y",
//    "SLIDER_INTERVAL" => "3000",
//    "SLIDER_PROGRESS" => "N",
//    "TITLE_BLOCK" => "Вы недавно смотрели:",
//    "OFFERS_SORT_FIELD" => "property_SKLAD",
//    "OFFERS_SORT_FIELD2" => "catalog_PRICE_1",
//    "OFFERS_SORT_ORDER" => "desc",
//    "OFFERS_SORT_ORDER2" => "asc",
//    "LABEL_PROP_POSITION" => "top-left",
//    "PRODUCT_SUBSCRIPTION" => "Y",
//    "SHOW_DISCOUNT_PERCENT" => "N",
//    "SHOW_OLD_PRICE" => "N",
//    "SHOW_MAX_QUANTITY" => "N",
//    "SHOW_CLOSE_POPUP" => "N",
//    "MESS_BTN_BUY" => "Купить",
//    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
//    "MESS_BTN_SUBSCRIBE" => "Подписаться",
//    "MESS_BTN_DETAIL" => "Подробнее",
//    "MESS_NOT_AVAILABLE" => "Нет в наличии",
//    "CACHE_TYPE" => "A",
//    "CACHE_TIME" => "3600",
//    "CACHE_GROUPS" => "Y",
//    "ACTION_VARIABLE" => "action_cpv",
//    "PRODUCT_ID_VARIABLE" => "id",
//    "PRICE_CODE" => array(
//      0 => "BASE",
//    ),
//    "USE_PRICE_COUNT" => "N",
//    "SHOW_PRICE_COUNT" => "1",
//    "PRICE_VAT_INCLUDE" => "Y",
//    "CONVERT_CURRENCY" => "N",
//    "BASKET_URL" => "/personal/basket.php",
//    "USE_PRODUCT_QUANTITY" => "N",
//    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
//    "ADD_PROPERTIES_TO_BASKET" => "Y",
//    "PRODUCT_PROPS_VARIABLE" => "prop",
//    "PARTIAL_PRODUCT_PROPERTIES" => "N",
//    "ADD_TO_BASKET_ACTION" => "ADD",
//    "DISPLAY_COMPARE" => "N",
//    "PROPERTY_CODE_2" => array(
//      1 => "NEWPRODUCT",
//      2 => "SALELEADER",
//    ),
//    "PROPERTY_CODE_MOBILE_2" => array(
//    ),
//    "CART_PROPERTIES_2" => array(
//      0 => "",
//      1 => "",
//    ),
//    "ADDITIONAL_PICT_PROP_2" => "-",
//    "LABEL_PROP_2" => array(
//    ),
//    "PROPERTY_CODE_12" => array(
//      0 => "SIZE",
//      1 => "ARTICLE",
//      2 => "SIDE",
//      3 => "COLOR",
//      4 => "COLOR_IN",
//      5 => "COLOR_OUT",
//      6 => "GLASS_COLOR",
//      7 => "",
//    ),
//    "CART_PROPERTIES_12" => array(
//      0 => "",
//      1 => "",
//    ),
//    "ADDITIONAL_PICT_PROP_12" => "-",
//    "OFFER_TREE_PROPS_12" => array(
//
//    ),
//    "USE_ENHANCED_ECOMMERCE" => "N"
//  ),
//  false
//);?>


<?
global $sotbitSeoMetaBreadcrumbTitle;
if (!empty($sotbitSeoMetaBreadcrumbTitle)) {
    $APPLICATION->AddChainItem($sotbitSeoMetaBreadcrumbTitle);
} ?>