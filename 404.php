<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>
<?/*
	<div class="bx-404-container">
		<div class="bx-404-block"><img src="<?=SITE_DIR?>images/404.png" alt=""></div>
		<div class="bx-404-text-block">Неправильно набран адрес, <br>или такой страницы на сайте больше не существует.</div>
		<div class="">Вернитесь на <a href="<?=SITE_DIR?>">главную</a> или воспользуйтесь картой сайта.</div>
	</div>
	<div class="map-columns row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="bx-maps-title">Карта сайта:</div>
		</div>
	</div>

	<div class="col-sm-offset-2 col-sm-4">
		<div class="bx-map-title"><i class="fa fa-leanpub"></i> Каталог</div>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"tree",
			array(
				"COMPONENT_TEMPLATE" => "tree",
				"IBLOCK_TYPE" => "catalog",
				"IBLOCK_ID" => "2",
				"SECTION_ID" => $_REQUEST["SECTION_ID"],
				"SECTION_CODE" => "",
				"COUNT_ELEMENTS" => "Y",
				"TOP_DEPTH" => "2",
				"SECTION_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"SECTION_USER_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"SECTION_URL" => "",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_GROUPS" => "Y",
				"ADD_SECTIONS_CHAIN" => "Y"
			),
			false
		);
		?>
	</div>

	<div class="col-sm-offset-1 col-sm-4">
		<div class="bx-map-title"><i class="fa fa-info-circle"></i> О магазине</div>
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:main.map",
			".default",
			array(
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"SET_TITLE" => "N",
				"LEVEL" => "3",
				"COL_NUM" => "2",
				"SHOW_DESCRIPTION" => "Y",
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
		);?>
	</div>*/?>

<section class="errorpage">
          <div class="content-container">
            <div class="errorpage__block">
              <div class="errorpage__inner">
                <div class="errorpage__title">Ошибка 404
                </div>
                <div class="errorpage__text">К сожалению, страница не найдена. Возможно, вы искали это:
                </div>
              </div>
            </div>
          </div>
        </section>
<?$sale_filter=array("SECTION_ID" => 35, array(
    'LOGIC' => 'OR',
    array('>PROPERTY_OLD_PRICE' => 0),
    array('=ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
        'IBLOCK_ID' => IBLOCK_ID_OFFERS,
        'ACTIVE' => 'Y',
        '>PROPERTY_OLD_PRICE' => 0
    )))

)
);?>

<?$hit_filter=array("!PROPERTY_SALELEADER" => false);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:catalog.top",
    "hit_slider",
    array(
        "ACTION_VARIABLE" => "action",
        "ADD_PICT_PROP" => "MORE_PHOTO",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_TO_BASKET_ACTION" => "ADD",
        "BASKET_URL" => "/personal/basket.php",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "COMPONENT_TEMPLATE" => "hit_slider",
        "CONVERT_CURRENCY" => "N",
        "DETAIL_URL" => "#SITE_DIR#/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
        "DISPLAY_COMPARE" => "N",
        "ELEMENT_COUNT" => "18",
        "ELEMENT_SORT_FIELD" => "RAND",
        "ELEMENT_SORT_FIELD2" => "",
        "ELEMENT_SORT_ORDER" => "",
        "ELEMENT_SORT_ORDER2" => "",
        "FILTER_NAME" => "hit_filter",
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
        "OFFERS_CART_PROPERTIES" => array(
            0 => "OLD_PRICE",
        ),
        "OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "DETAIL_PICTURE",
            2 => "",
        ),
        "OFFERS_LIMIT" => "1",
        "OFFERS_PROPERTY_CODE" => array(
            0 => "SIZE",
            1 => "ARTICLE",
            2 => "INNER_PHOTO",
            3 => "TRANSOMS",
            4 => "BOX",
            5 => "JAMB",
            6 => "XML_NEW",
            7 => "BAR",
            8 => "OLD_PRICE",
            9 => "SIDE",
            10 => "TWO_LEAF_PHOTO",
            11 => "COLOR",
            12 => "COLOR_IN",
            13 => "COLOR_OUT",
            14 => "GLASS_COLOR",
            15 => "",
        ),
        "OFFERS_SORT_FIELD" => "property_OLD_PRICE",
        "OFFERS_SORT_FIELD2" => "",
        "OFFERS_SORT_ORDER" => "desc",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFER_ADD_PICT_PROP" => "-",
        "OFFER_TREE_PROPS" => array(
            0 => "SIZE",
            1 => "COLOR",
            2 => "GLASS_COLOR",
            3 => "SIDE",
            4 => "COLOR_OUT",
            5 => "COLOR_IN",
            6 => "BOX",
            7 => "JAMB",
            8 => "TRANSOMS",
            9 => "BAR",
        ),
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array(
        ),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PROPERTY_CODE" => array(
            0 => "NEWPRODUCT",
            1 => "SALELEADER",
            2 => "SPECIALOFFER",
            3 => "",
        ),
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
        "VIEW_MODE" => "SECTION"
    ),
    false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>