<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if ((mb_stripos($_SERVER['REQUEST_URI'], "/index.php") > -1) || mb_stripos($_SERVER['REQUEST_URI'], "//") > -1) LocalRedirect("/", false, "301 Moved permanently");

$APPLICATION->SetPageProperty("description", "✅ Официальный сайт белорусского производителя дверей Belwooddoors™ в Москве 💰 Выгодные цены ⚡ Доставка, установка 📞 +7 (499) 380-70-58");
$APPLICATION->SetPageProperty("keywords", "Белорусские двери");
$APPLICATION->SetPageProperty("title", "Белорусские двери Belwooddoors в Москве - официальный сайт");
$APPLICATION->SetTitle("");
?><?
global $new_filter;
$new_filter = [
    "!PROPERTY_SALELEADER" => false,
    array("ID" => \CIBlockElement::SubQuery("PROPERTY_CML2_LINK", array("IBLOCK_ID" => 12, "ACTIVE" => "Y"))),
];
$APPLICATION->IncludeComponent(
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
        "OFFERS_FIELD_CODE" => array(0 => "NAME", 1 => "DETAIL_PICTURE", 2 => "",),
        "OFFERS_LIMIT" => "1",
        "OFFERS_PROPERTY_CODE" => array(0 => "", 1 => "",),
        "OFFERS_SORT_FIELD" => "PROPERTY_IS_AVAILABLE",
        "OFFERS_SORT_FIELD2" => "CATALOG_PRICE_1",
        "OFFERS_SORT_ORDER" => "DESC",
        "OFFERS_SORT_ORDER2" => "ASC",
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(0 => "BASE",),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_DISPLAY_MODE" => "N",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array(),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PROPERTY_CODE" => array(0 => "NEWPRODUCT", 1 => "SALELEADER", 2 => "SPECIALOFFER", 3 => "",),
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
);
?> <?
    global $new_filter;
	$new_filter=array(
        "!PROPERTY_NEWPRODUCT" => false,
        //array("ID" => \CIBlockElement::SubQuery("PROPERTY_CML2_LINK", array("IBLOCK_ID" => 12, "ACTIVE" => "Y", ">CATALOG_QUANTITY" => 0)))
    );
	$APPLICATION->IncludeComponent(
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
            "OFFERS_SORT_FIELD" => "PROPERTY_IS_AVAILABLE",
            "OFFERS_SORT_FIELD2" => "CATALOG_PRICE_1",
            "OFFERS_SORT_ORDER" => "DESC",
            "OFFERS_SORT_ORDER2" => "ASC",
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
	);?>
<div class="js_main_container">
	 <?include 'include/main_container.php';?>
</div>
 <section class="about" id="content-video">
<div class="content-container">
	<div class="about__text-content">
		<div class="about__text">
			 <!--img src="<?= SITE_TEMPLATE_PATH . '/preload.svg'?>" class="image_for_index_page lazy" alt="Производство" data-src="/upload/medialibrary/f43/f435ebdbcd29714152119970b3f399a4.jpg" title="Производство"--> <iframe class="image_for_index_page lazy" src="https://www.youtube.com/embed/i5vBfbfAYQA?autoplay=1&mute=1" title="YouTube video player" frameborder="0"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			<div class="about__inner">
				<h1 class="about__title">Belwooddoors</h1>
				<p>
					 Как найти дверь, которая будет идеально соответствовать соотношению «цена — качество»? Предлагаем обратить внимание на широко известную в Белоруссии и за ее пределами компанию по производству <a href="https://belwooddoors.ru/catalog/mezhkomnatnye_dveri/" target="_blank" rel="noopener">межкомнатных дверей</a> — Belwooddoors, впервые выпустившую свою продукцию в конце 1999 года.
				</p>
				<p>
					 Продукция фабрики Belwooddoors сертифицирована в России, Казахстане, Украине и Беларуси.
				</p>
				<p>
					 Доступность цены и высокое качество наших <a href="https://belwooddoors.ru/catalog/vkhodnye_dveri/" target="_blank" rel="noopener">входных дверей</a> достигается за счет регулярного совершенствования производственного цикла, внедрения инноваций, строгого соблюдения технологии производства, а также сотрудничества с ведущими производителями и поставщиками необходимых материалов и <a href="https://belwooddoors.ru/catalog/furnitura/" target="_blank" rel="noopener">фурнитуры</a>.
				</p>
 <a href="/about/" class="button button--secondary button--md">Подробнее</a>
			</div>
		</div>
	</div>
</div>
 </section>
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
            var source = "https://creativecdn.com/tags?type=iframe&id=pr_B6MjHKf0gPbC1LVhT75b_home&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + encodeURIComponent(timestamp);
            ifr.setAttribute("src", source);
            ifr.setAttribute("width", "1");
            ifr.setAttribute("height", "1");
            ifr.setAttribute("scrolling", "no");
            ifr.setAttribute("frameBorder", "0");
            ifr.setAttribute("style", "display:none");
            ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
            body.appendChild(ifr);
        }());
    </script><br><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>