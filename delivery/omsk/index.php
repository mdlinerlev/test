<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Межкомнатные белорусские двери - купить в Омске");
$APPLICATION->SetPageProperty("keywords", "межкомнатные двери, белоруссия, доставка, Омск");
$APPLICATION->SetPageProperty("description", "Межкомнатные двери Belwooddoors от производителя с доставкой в Омск через сторонних подрядчиков");
$APPLICATION->SetTitle("Межкомнатные двери Belwooddoors с доставкой в Омск");
?><section class="about">
<p>
	 Мы можем организовать доставку наших дверей в Омск с помощью услуг транспортных компаний на ваш выбор.
</p>
<p>
	 Стоимость и условия перевозки можно уточнить у наших менеджеров по ☎ тел. <a href="tel:+74993807058">8 (499) 380-70-58</a> при формировании заказа или посетив один из наших <a href="/addresses/">фирменных салонов</a>.
</p>
	<p><a style="margin-top: 0;" href="/catalog/mezhkomnatnye_dveri/" class="about__link">Каталог межкомнатных дверей </a></p>
	<br>
	<p style="padding-top:10px;"><a  style="margin-top: 0; padding-top: 1px;" href="/catalog/vkhodnye_dveri/" class="about__link">Каталог входных дверей </a></p>
 <br>
	<p>Для вашего удобства предлагаем контактные данные транспортных компаний.</p>
	<p>Для точного просчета стоимости перевозки вам необходимо связаться с нашим менеджером-консультантом и получить от него информацию о габаритах и тоннаже груза.</p>
<ul>
	<li>«CDEK — логистические решения», МТС: +7-910-440-44-34;</li>
	<li>«DPD», МТС: +7-910-440-44-34, Билайн: +7-906-013-44-34;</li>
	<li>«Желдорэкспедиция», МТС: +7-495-727-14-56;</li>
	<li>«Деловые Линии», Тел. 8-800-100–80-00</li>
</ul>
 <a href="/delivery/" class="about__link">Подробнее о доставке</a>
 </section>
<?$hit_filter=array("!PROPERTY_SALELEADER" => false);?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"hit_slider",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_FILTER" => "Y",
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
		"OFFERS_CART_PROPERTIES" => array(),
		"OFFERS_FIELD_CODE" => array(0=>"NAME",1=>"DETAIL_PICTURE",2=>"",),
		"OFFERS_LIMIT" => "1",
		"OFFERS_PROPERTY_CODE" => array(0=>"",1=>"",),
		"OFFERS_SORT_FIELD" => "",
		"OFFERS_SORT_FIELD2" => "",
		"OFFERS_SORT_ORDER" => "",
		"OFFERS_SORT_ORDER2" => "",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(0=>"BASE",),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_DISPLAY_MODE" => "N",
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
		"VIEW_MODE" => "SECTION"
	)
);?> <?$sale_filter=array("SECTION_ID" => 35, array(
				'LOGIC' => 'OR',
				array('>PROPERTY_OLD_PRICE' => 0),
				array('=ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
					'IBLOCK_ID' => IBLOCK_ID_OFFERS,
					'ACTIVE' => 'Y',
					'>PROPERTY_OLD_PRICE' => 0
				)))
			)
    );?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"sale_slider",
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
		"VIEW_MODE" => "SECTION"
	)
);?> <?$new_filter=array("!PROPERTY_NEWPRODUCT" => false);?> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"new_slider",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "new_slider",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "#SITE_DIR#/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "18",
		"ELEMENT_SORT_FIELD" => "RAND",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"FILTER_NAME" => "new_filter",
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
		"OFFERS_CART_PROPERTIES" => array(),
		"OFFERS_FIELD_CODE" => array(0=>"NAME",1=>"DETAIL_PICTURE",2=>"",),
		"OFFERS_LIMIT" => "1",
		"OFFERS_PROPERTY_CODE" => array(0=>"",1=>"",),
		"OFFERS_SORT_FIELD" => "RAND",
		"OFFERS_SORT_FIELD2" => "",
		"OFFERS_SORT_ORDER" => "",
		"OFFERS_SORT_ORDER2" => "",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(0=>"BASE",),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_DISPLAY_MODE" => "N",
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
		"VIEW_MODE" => "SECTION"
	)
);?> <section class="index-feedback">
<div class="content-container">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"feedback_slider",
	Array(
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "feedback_slider",
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>$arParams["LIST_FIELD_CODE"],2=>"",),
		"FILE_404" => $arParams["FILE_404"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MAX_VOTE" => $arParams["MAX_VOTE"],
		"MEDIA_PROPERTY" => "",
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"PROPERTY_CODE" => array(0=>"",1=>$arParams["LIST_PROPERTY_CODE"],2=>"",),
		"SEARCH_PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
		"SHARE_HIDE" => $arParams["SHARE_HIDE"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
		"SHOW_404" => "N",
		"SLIDER_PROPERTY" => "",
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
		"USE_RATING" => "N",
		"USE_SHARE" => "N",
		"VOTE_NAMES" => $arParams["VOTE_NAMES"]
	),
$component,
Array(
	'ACTIVE_COMPONENT' => 'N'
)
);?><br>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"clients",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
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
		"COMPONENT_TEMPLATE" => "clients",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"",1=>"",),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	),
false,
Array(
	'ACTIVE_COMPONENT' => 'N'
)
);?><br>
</div>
 </section> <section class="index-news">
<div class="content-container">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"news_slider",
	Array(
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"COMPONENT_TEMPLATE" => "news_slider",
		"DETAIL_URL" => "#SITE_DIR#/about/news/#ELEMENT_ID#/",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>$arParams["LIST_FIELD_CODE"],2=>"",),
		"FILE_404" => $arParams["FILE_404"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MAX_VOTE" => $arParams["MAX_VOTE"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"PROPERTY_CODE" => array(0=>"",1=>$arParams["LIST_PROPERTY_CODE"],2=>"",),
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"USE_RATING" => $arParams["USE_RATING"],
		"VOTE_NAMES" => $arParams["VOTE_NAMES"]
	),
$component,
Array(
	'ACTIVE_COMPONENT' => 'N'
)
);?>
</div>
 </section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>