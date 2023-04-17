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

CJSCore::Init('ajax');

$isAjax = isset($_REQUEST['bxajaxid']) ? 'Y' : 'N';

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? array($arParams['COMMON_ADD_TO_BASKET_ACTION']) : array());
}
else
{
	$basketAction = (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION'] : array());
}

$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	array(
        "USER" => $USER->isAuthorized(),
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "STRICT_SECTION_CHECK" => $arParams["DETAIL_STRICT_SECTION_CHECK"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
		'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],

		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
		'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
		'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
		'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
		'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
		'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
		'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
		'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
		'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
		'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
		'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
		'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
		'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
		"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
		"DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
		"DETAIL_PICTURE_MODE" => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
		'BACKGROUND_IMAGE' => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : ''),
		'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
		'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),

		"USE_GIFTS_DETAIL" => $arParams['USE_GIFTS_DETAIL']?: 'Y',
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST']?: 'Y',
		"GIFTS_SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		"GIFTS_SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
		"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
		"GIFTS_SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
		"GIFTS_SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
		"GIFTS_MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

		'AJAX_MODE' => 'Y',
		'AJAX_OPTION_JUMP' => 'N',
		'AJAX_OPTION_STYLE' => 'N',
		'AJAX_OPTION_HISTORY' => 'Y',

		'IS_AJAX' => $isAjax
	),
	$component
);?>

<?

$addProductsParamss = array(
	"ADD_SECTIONS_CHAIN" => "N",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_ADDITIONAL" => "",
	"AJAX_OPTION_HISTORY" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"CACHE_SALT" => "",
	"CACHE_TIME" => "3600",
	"CACHE_TYPE" => "A",
	"COMPONENT_TEMPLATE" => ".default",
	"DETAIL_URL" => "",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"DISPLAY_TOP_PAGER" => "N",
	"ELEMENT_CONTROLS" => "Y",
	"ELEMENT_FIELDS" => array(0 => "NAME", 1 => "DETAIL_PICTURE", 2 => "DETAIL_PAGE_URL"),
	"ELEMENT_PROPERTIES" => array(0 => "", 1 => "",),
	"FILTER_NAME" => "similarFilter",
	"IBLOCK_ID" => $arParams['IBLOCK_ID'],
	"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
	"INCLUDE_SUBSECTIONS" => "Y",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_TEMPLATE" => ".default",
	"PAGER_TITLE" => "",
	"PAGE_ELEMENT_COUNT" => "18",
	"SECTION_CODE" => "",
	"SECTION_FIELDS" => array(0 => "", 1 => "",),
	"SECTION_ID" => array(0 => "30", 1 => "35", 2 => "36", 3 => "22",),
	"SECTION_URL" => "",
	"SECTION_USER_FIELDS" => array(0 => "", 1 => "",),
	"SORT_FIELD" => "RAND",
	"SORT_FIELD2" => "id",
	"SORT_ORDER" => "asc",
	"SORT_ORDER2" => "desc",
	"URL_404" => "/404.php",
	'HEADER' => 'Сопутствующие товары'
);

$ids = array();

# сопут. товары раздела напольные покрытия
if (in_array($arResult['SECTION']['CODE'], array('laminat', 'parket', 'plintus', 'podlozhka'))) {
	$addProductsParamss['SECTION_ID'] = [$arResult['SECTION']['ID']];
} else {
	# сопут. товары
	$addProductsParamss['SECTION_ID'] =  array(30, 36, 35, 23, 24, 25, 26, 27, 28, 29, 22);
}
$arSimilar = [];
$res = CIBlockElement::GetProperty(2, $ElementID, "sort", "asc", array("CODE" => "LINK_PROD"));

while ($ob = $res->GetNext())
{
    if(!empty($ob['VALUE'])) {
        $arSimilar[] = $ob['VALUE'];
    }
}
if(!in_array($arResult['SECTION']['PATH'][0]['CODE'], ['mezhkomnatnye_dveri', 'vkhodnye_dveri'])) {

	/*$GLOBALS['similarFilter'] = array(
		'!=ID' => $arResult['ID'],
		'!PROPERTY_CML2_LINK' => true
	);*/

    if(!empty($arSimilar)) {
        $GLOBALS['similarFilter'] = array(
            '=ID' => $arSimilar,
            'SECTION_ID' => ''
        );

        $APPLICATION->IncludeComponent(
            "jorique:iblock.element.list",
            "similar",
            array_merge($addProductsParamss, array(
                'HEADER' => 'Сопутствующие товары'
            )),
            $component,
            array(
                'HIDE_ICONS' => 'Y'
            )
        );
    }



}

?>


<?

$collectionTile = "";
if(!empty($arResult['PROPERTIES']['COLLECTION']['VALUE']))
    $collectionTile = '"'.(implode(', ', array_map('trim', $arResult['PROPERTIES']['COLLECTION']['VALUE']))).'"';


if(!empty($arResult['SECTION']["ID"])):
    global $sale_filter;
    $sale_filter = ["SECTION_ID" => $arResult['SECTION']["ID"]];
    $sale_filter['PROPERTY_COLLECTION_VALUE'] = $arResult['PROPERTIES']['COLLECTION']['VALUE'];
    $sale_filter['!ID'] = $arResult['ID'];

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.top",
        "sale_slider_cart",
        Array(
            "ACTION_VARIABLE" => "action",
            "ADD_PICT_PROP" => "MORE_PHOTO",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "BASKET_URL" => "/personal/basket.php",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "COMPONENT_TEMPLATE" => "sale_slider",
            "CONVERT_CURRENCY" => "N",
            "DETAIL_URL" => "#SITE_DIR#/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
            "DISPLAY_COMPARE" => "N",
            "ELEMENT_COUNT" => "18",
            "ELEMENT_SORT_FIELD" => "RAND",
            "ELEMENT_SORT_FIELD2" => "",
            "ELEMENT_SORT_ORDER" => "",
            "ELEMENT_SORT_ORDER2" => "",
            "FILTER_NAME" => "sale_filter",
            "HIDE_NOT_AVAILABLE" => "N",
            "IBLOCK_ID" => "2",
            "IBLOCK_TYPE" => "catalog",
            "LABEL_PROP" => "-",
            "LINE_ELEMENT_COUNT" => "0",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_COMPARE" => "Сравнить",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "OFFERS_CART_PROPERTIES" => array(0=>"OLD_PRICE",),
            "OFFERS_FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"DETAIL_PICTURE",3=>"",),
            "OFFERS_LIMIT" => "1",
            "OFFERS_PROPERTY_CODE" => array(0=>"SIZE",1=>"ARTICLE",2=>"INNER_PHOTO",3=>"TRANSOMS",4=>"BOX",5=>"JAMB",6=>"XML_NEW",7=>"BAR",8=>"OLD_PRICE",9=>"SIDE",10=>"TWO_LEAF_PHOTO",11=>"COLOR",12=>"COLOR_IN",13=>"COLOR_OUT",14=>"GLASS_COLOR",15=>"",),
            "OFFERS_SORT_FIELD" => "CATALOG_PRICE_1",
            "OFFERS_SORT_FIELD2" => "property_OLD_PRICE",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFER_ADD_PICT_PROP" => "-",
            "OFFER_TREE_PROPS" => array(0=>"SIZE",1=>"COLOR",2=>"GLASS_COLOR",3=>"SIDE",4=>"COLOR_OUT",5=>"COLOR_IN",6=>"BOX",7=>"JAMB",8=>"TRANSOMS",9=>"BAR",),
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => array(0=>"BASE",),
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_DISPLAY_MODE" => "Y",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array(),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "",
            "PROPERTY_CODE" => array(0=>"NEWPRODUCT",1=>"SALELEADER",2=>"SPECIALOFFER",3=>"",),
            "ROTATE_TIMER" => "30",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE_PATH#/",
            "SEF_MODE" => "N",
            "SEF_RULE" => "",
            "SHOW_CLOSE_POPUP" => "N",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
            "SHOW_PAGINATION" => "Y",
            "SHOW_PRICE_COUNT" => "1",
            "TEMPLATE_THEME" => "blue",
            "USE_PRICE_COUNT" => "N",
            "USE_PRODUCT_QUANTITY" => "N",
            "VIEW_MODE" => "SECTION",
            "TITLE_HEADER" => "Другие двери из этой коллекции {$collectionTile}"
        ),false, array('HIDE_ICONS' => 'Y')
    );
endif;
?>
<div class="content-container">
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


    <?$APPLICATION->IncludeComponent(
        "bitrix:catalog.products.viewed",
        "catalog",
        array(
            "COMPONENT_TEMPLATE" => "catalog",
            "IBLOCK_MODE" => "single",
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "2",
            "SHOW_FROM_SECTION" => "N",
            "SECTION_ID" =>"",
            "SECTION_CODE" => "",
            "SECTION_ELEMENT_ID" => $GLOBALS["CATALOG_CURRENT_ELEMENT_ID"],
            "SECTION_ELEMENT_CODE" => "",
            "DEPTH" => "2",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "PAGE_ELEMENT_COUNT" => "9",
            "TEMPLATE_THEME" => "blue",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "ENLARGE_PRODUCT" => "STRICT",
            "SHOW_SLIDER" => "Y",
            "SLIDER_INTERVAL" => "3000",
            "SLIDER_PROGRESS" => "N",
            "TITLE_BLOCK" => "Вы недавно смотрели:",
            "OFFERS_SORT_FIELD" => "property_SKLAD",
            "OFFERS_SORT_FIELD2" => "catalog_PRICE_1",
            "OFFERS_SORT_ORDER" => "desc",
            "OFFERS_SORT_ORDER2" => "asc",
            "LABEL_PROP_POSITION" => "top-left",
            "PRODUCT_SUBSCRIPTION" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "SHOW_MAX_QUANTITY" => "N",
            "SHOW_CLOSE_POPUP" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CACHE_GROUPS" => "Y",
            "ACTION_VARIABLE" => "action_cpv",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRICE_CODE" => array(
                0 => "BASE",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "BASKET_URL" => "/personal/basket.php",
            "USE_PRODUCT_QUANTITY" => "N",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "ADD_TO_BASKET_ACTION" => "ADD",
            "DISPLAY_COMPARE" => "N",
            "PROPERTY_CODE_2" => array(
                1 => "NEWPRODUCT",
                2 => "SALELEADER",
                3 => "CONFIGURATION",
                4 => "DOUBLE_IMAGE",
            ),
            "PROPERTY_CODE_MOBILE_2" => array(
            ),
            "CART_PROPERTIES_2" => array(
                0 => "",
                1 => "",
            ),
            "ADDITIONAL_PICT_PROP_2" => "-",
            "LABEL_PROP_2" => array(
            ),
            "PROPERTY_CODE_12" => array(
                0 => "SIZE",
                1 => "ARTICLE",
                2 => "SIDE",
                3 => "COLOR",
                4 => "COLOR_IN",
                5 => "COLOR_OUT",
                6 => "GLASS_COLOR",
                7 => "CONFIGURATION",
            ),
            "CART_PROPERTIES_12" => array(
                0 => "",
                1 => "",
            ),
            "ADDITIONAL_PICT_PROP_12" => "-",
            "OFFER_TREE_PROPS_12" => array(

            ),
            "USE_ENHANCED_ECOMMERCE" => "N"
        ),
        false
    );?>

</div>
