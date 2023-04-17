<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Отзывы клиентов про двери и сервис компании Белвуддорс. Оставьте свой!");
$APPLICATION->SetPageProperty("keywords", "BelWoodDoors, отзывы");
$APPLICATION->SetPageProperty("title", "Отзывы о дверях производства Belwooddoors");
$APPLICATION->SetTitle("Отзывы о Belwooddoors");
?><style>
	.coment-block {
		display:flex;
	flex-wrap: wrap;
   }
	#yandex-otzivi {
		width:436px;
		overflow:hidden;
		position:relative;
        margin-bottom: 25px;
		margin-right: 30px;
}
	#yandex-otzivi:nth-child(3){
		margin-right:0
}
	#yandex-otzivi:nth-child(6){
		margin-right:0
}
	@media screen and (max-width:875px) {
		.coment-block {
		margin-bottom: 0;
		margin-right: 0;
			display:block;}
		#yandex-otzivi {
			width:100%;
margin-bottom: 20px;
			margin-right: 0;
}
}
</style>
<!--div class="coment-block">
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box; margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/1299355428?comments"></iframe><a href="https://yandex.by/maps/org/belwooddoors/1299355428/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Belwooddoors на карте Москвы — Яндекс.Карты</a></div>
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box;margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/223310453467?comments"></iframe><a href="https://yandex.by/maps/org/belwooddoors_dveri_ot_proizvoditelya/223310453467/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Belwooddoors двери от производителя на карте Мытищ — Яндекс.Карты</a></div>
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box;margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/47703849268?comments"></iframe><a href="https://yandex.by/maps/org/belwooddoors_dveri_ot_proizvoditelya/47703849268/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">BelWoodDoors двери от производителя на карте Москвы — Яндекс.Карты</a></div>
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box;margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/203266604204?comments"></iframe><a href="https://yandex.ru/maps/org/belwooddoors/203266604204/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Belwooddoors на карте Москвы — Яндекс.Карты</a></div>
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box;margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/41428871514?comments"></iframe><a href="https://yandex.by/maps/org/belwooddoors_dveri_ot_proizvoditelya/41428871514/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Belwooddoors двери от производителя на карте Москвы — Яндекс.Карты</a></div>
<div id="yandex-otzivi"><iframe style="width:100%;height:475px;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box;margin-bottom:0;" src="https://yandex.ru/maps-reviews-widget/240181933527?comments"></iframe><a href="https://yandex.ru/maps/org/belwooddoors/240181933527/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0">Belwooddoors на карте Москвы — Яндекс.Карты</a></div>

</div-->
<section class="feedback">
    <?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"feedback", 
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "TITLE_HAND",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "feedback",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "RATING",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "RATING",
			1 => "",
		),
		"MEDIA_PROPERTY" => "",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "9",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "infinity",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SLIDER_PROPERTY" => "",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"TEMPLATE_THEME" => "blue",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"SEF_FOLDER" => "/about/comments/",
		"STRICT_SECTION_CHECK" => "N",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?> 
<section class="feedback__form">
 <?$APPLICATION->IncludeComponent(
	"bitrix:iblock.element.add.form", 
	"comments", 
	array(
		"AJAX_MODE" => "Y",
		"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
		"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
		"CUSTOM_TITLE_DETAIL_PICTURE" => "",
		"CUSTOM_TITLE_DETAIL_TEXT" => "Комментарий",
		"CUSTOM_TITLE_IBLOCK_SECTION" => "",
		"CUSTOM_TITLE_NAME" => "Автор",
		"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
		"CUSTOM_TITLE_PREVIEW_TEXT" => "",
		"CUSTOM_TITLE_TAGS" => "",
		"DEFAULT_INPUT_SIZE" => "30",
		"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
		"ELEMENT_ASSOC" => "CREATED_BY",
		"GROUPS" => array(
			0 => "2",
		),
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "content",
		"LEVEL_LAST" => "Y",
		"LIST_URL" => "",
		"MAX_FILE_SIZE" => "0",
		"MAX_LEVELS" => "100000",
		"MAX_USER_ENTRIES" => "100000",
		"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
		"PROPERTY_CODES" => array(
			0 => "55",
			1 => "NAME",
			2 => "DETAIL_TEXT",
			3 => "133",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "55",
			1 => "NAME",
			2 => "DETAIL_TEXT",
		),
		"RESIZE_IMAGES" => "N",
		"SEF_MODE" => "N",
		"STATUS" => "ANY",
		"STATUS_NEW" => "NEW",
		"USER_MESSAGE_ADD" => "Спасибо за отзыв",
		"USER_MESSAGE_EDIT" => "Спасибо за отзыв",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "comments"
	),
	false
);?></section>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>