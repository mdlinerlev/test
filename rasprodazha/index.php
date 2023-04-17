<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Распродажа межкомнатных дверей в Москве - BELWOODDOORS");
$APPLICATION->SetTitle("Распродажа");
?><div class="sale catalog catalog--search">
<?$GLOBALS['sale_filter']=array("SECTION_ID" => 35, array(
				'LOGIC' => 'OR',
				array('>PROPERTY_OLD_PRICE' => 0),
				array('=ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
					'IBLOCK_ID' => IBLOCK_ID_OFFERS,
					'ACTIVE' => 'Y',
					'>PROPERTY_OLD_PRICE' => 0
				)))
			)
    );?>

   <?$intSectionID = $APPLICATION->IncludeComponent(
	"jorique:catalog.section", 
	"cat_1", 
	array(
		"COMPONENT_TEMPLATE" => "cat_1",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "2",
		"SECTION_ID" => "35",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "sale_filter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"HIDE_NOT_AVAILABLE" => "N",
		"PAGE_ELEMENT_COUNT" => "10000",
		"LINE_ELEMENT_COUNT" => "6",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
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
		"OFFERS_SORT_FIELD" => "CATALOG_PRICE_1",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_LIMIT" => "1",
		"BACKGROUND_IMAGE" => "-",
		"TEMPLATE_THEME" => "site",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"ADD_PICT_PROP" => "-",
		"LABEL_PROP" => "-",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_CLOSE_POPUP" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "N",
		"SET_TITLE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"BROWSER_TITLE" => "-",
		"SET_META_KEYWORDS" => "Y",
		"META_KEYWORDS" => "-",
		"SET_META_DESCRIPTION" => "Y",
		"META_DESCRIPTION" => "-",
		"SET_LAST_MODIFIED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/personal/basket.php",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
			0 => "SIZE",
			1 => "OLD_PRICE",
			2 => "COLOR_IN",
			3 => "COLOR_OUT",
			4 => "GLASS_COLOR",
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"PAGER_TEMPLATE" => "infinity_catalog",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
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
		)
	),
	$component
);?>
<div class="catalog__text">

    <div class="catalog-text__columns text-content">
	<div>
	 Современные технологии характеризуются возможностью воплощения любой творческой задумки, касающейся ремонта и дизайна помещений. Магазины розничной торговли активно предлагают оформление заказов на межкомнатные двери. Компания BELWOODDOORS учитывает все потребности покупателей, поэтому предоставляет не только впечатляющий ассортимент полотен, но и возможность купить межкомнатные двери в наличии самых различных модификаций: <br>
 <br>
	<ul>
		<li>Классические и ультрасовременные;</li>
		<li>Глухие и с разнообразными витражами;</li>
		<li>Всех возможных цветовых исполнений.</li>
	</ul>
 <br>
 <b><span style="color: #007236;">Не нужно ждать – можно сразу покупать</span></b><br>
	 Дверные блоки из списка «В наличии» нисколько не уступают остальным моделям в качестве и разнообразии. Большой складской запас таких полотен лучше всего подтверждает, что они востребованы и по достоинству оценены покупателями. Все больше клиентов осознают, что приобретать двери, находящиеся в наличии, – это быстро, удобно и эффективно. А еще:<br>
 <br>
	<ul>
		<li>Кратчайшие сроки между покупкой и монтажом означают значительное сокращение продолжительности ремонтных работ;</li>
		<li>Готовые межкомнатные полотна означают не только быстроту покупки и возможность вживую оценить все ее характеристики, но и регулярные акции, снижающие финансовые затраты;</li>
		<li>Процесс реализации дизайнерских решений будет зависеть только от Вас – все необходимое уже будет под рукой;</li>
		<li>Шум, пыль, невозможность полноценного использования жилого помещения закончатся гораздо быстрее, чем полностью скажутся на Вашем эмоциональном и состоянии и бюджете.</li>
	</ul>
 <br>
 <b><span style="color: #007236;">На любой вкус и цвет</span></b><br>
	 Для производства дверей используются различные материалы (массив, шпон, экошпон, стекло), что обеспечивает по истине богатый модельный ряд, удовлетворяющий запросам любого интерьера. <br>
 <br>
	 Межкомнатные двери в наличии в Москве от компании Белвуддорс – это впечатляющее количество разновидностей дверных блоков. Специалисты, трудящиеся над разработкой и выпуском каждого дверного полотна, работают над решением каждого потребительского запроса. Поэтому цветовая гамма предусматривает и спокойные, проверенные временем тона (орех, дуб, красное дерево), и соответствующие смелым творческим идеям (венге, слоновая кость, палисандр). <br>
 <br>
	 Функционал моделей продуман до мелочей. Для малогабаритных помещений предусмотрены двери с раздвижными механизмами, позволяющие наиболее рационально использовать доступное пространство. В классических интерьерах будут гармонично смотреться двустворчатые распашные полотна. А любителям стиля техно придутся по вкусу двери из закаленного стекла.<br>
 <br>
 <b><span style="color: #007236;">И быстро, и выгодно</span></b><br>
	 Купить межкомнатные двери в наличии на складе можно не только быстро, но и выгодно. Если цена отдельно взятого полотна не совсем укладывается в финансовые возможности, то единовременная оптовая покупка за рамки выделенных материальных средств не выйдет. Приобретая двери в квартиру или дом оптом, Вы получаете приятный бонус и гарантию того, что все полотна будут из одной партии. <br>
 <br>
	 Белорусские двери от производителя также представлены на сайте, где можно посмотреть фото и заказать подходящие полотна не выходя из дома.
</div>
 <br><p></p>
    </div>
</div>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>