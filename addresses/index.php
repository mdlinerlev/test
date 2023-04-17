<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords_inner", "belwooddoors, салон");
$APPLICATION->SetPageProperty("description", "Адреса магазинов белорусских дверей Belwooddoors в Москве");
$APPLICATION->SetPageProperty("keywords", "Салон межкомнатных дверей, магазин межкомнатных дверей.");
$APPLICATION->SetPageProperty("title", "Адреса дверных салонов Belwooddoors в Москве и Мытищах");
$APPLICATION->SetTitle("Адреса магазинов белорусских дверей Belwooddoors в Москве");
?><div class="stores stores__content">
<?//микроразметка?>
<?$APPLICATION->IncludeComponent("coffeediz:schema.org.OrganizationAndPlace", "myOrganizationAndPlace", Array(
    "ADDRESS" => "Кутузовский проспект 36А, 3 этаж, салон BELWOODDOORS ",	// Адрес
    "BESTRATING" => "5",	// Максимальное значение рейтинга
    "COUNTRY" => "ru",	// Страна
    "DESCRIPTION" => "",	// Краткое описание
    "EMAIL" => array(	// Email
        0 => "",
    ),
    "FAX" => "",	// Факс
    "ITEMPROP" => "",	// Является свойством другого объекта Schema.org
    "LATITUDE" => "",	// Широта
    "LOCALITY" => "Москва",	// Город
    "LOGO" => "/bitrix/templates/general/assets/images/logo.png",	// Логотип (ссылка)
    "LOGO_CAPTION" => "",	// Подпись к картинке Логотипа
    "LOGO_DESCRIPTION" => "",	// Описание картинки Логотипа
    "LOGO_HEIGHT" => "",	// Высота изображения (px)
    "LOGO_NAME" => "Belwooddoors logo",	// Название картинки Логотипа
    "LOGO_TRUMBNAIL_CONTENTURL" => "",	// Ссылка на миниатюру
    "LOGO_WIDTH" => "",	// Ширина изображения (px)
    "LONGITUDE" => "",	// Долгота
    "NAME" => "OOO \"СтройДеталь\"",	// Название компании
    "OPENING_HOURS_HUMAN" => array(	// Время работы для организации в текстовом формате
        0 => "С Понедельника по Пятницу 9-20",
        1 => "Суббота, Воскресенье круглосуточно",
        2 => "",
    ),
    "OPENING_HOURS_ROBOT" => array(	// Время работы для организации в формате ISO 8601
        0 => "Mo-Fr 9:00&#8722;20:00",
        1 => "St,Sn",
        2 => "",
    ),
    "PARAM_RATING_SHOW" => "Y",	// Выводить рейтинг
    "PHONE" => array(	// Телефон
        0 => "+7(495)981-6495",
        1 => "+7(964)628-5623",
        2 => "",
    ),
    "PHOTO_CAPTION" => "",	// Подписи к картинкам фото
    "PHOTO_DESCRIPTION" => "",	// Описания картинок фото
    "PHOTO_HEIGHT" => "",	// Высота изображений (px)
    "PHOTO_NAME" => "",	// Названия картинок фото
    "PHOTO_SRC" => array(	// Ссылка на фото
        0 => "",
    ),
    "PHOTO_TRUMBNAIL_CONTENTURL" => "",	// Ссылки на миниатюры фото
    "PHOTO_WIDTH" => "",	// Ширина изображений (px)
    "POST_CODE" => "121170",	// Индекс
    "RAITINGCOUNT" => "",	// Количество голосов
    "RATINGVALUE" => "",	// Значение рейтинга
    "RATING_SHOW" => "Y",	// Не отображать на сайте
    "REGION" => "Московская область",	// Регион
    "REVIEWCOUNT" => "",	// Количество отзывов
    "SHOW" => "Y",	// Не отображать на сайте
    "SITE" => "belwooddoors.ru",	// Сайт
    "TAXID" => "7728297840",	// ИНН
    "TYPE" => "Organization",	// Тип компании
    "TYPE_2" => "LocalBusiness",	// Тип компании
    "TYPE_3" => "Store",	// Тип компании
    "TYPE_4" => "FurnitureStore",	// Тип компании
    "WORSTRATING" => "1",	// Минимальное значение рейтинга
),
    false,
    array(
        "HIDE_ICONS" => "N"
    )
);?>
<?//Конец микроразметки?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"cities", 
	array(
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CATEGORY_CODE" => "CATEGORY",
		"CATEGORY_IBLOCK" => array(
			0 => "11",
		),
		"CATEGORY_ITEMS_COUNT" => "0",
		"CATEGORY_THEME_11" => "list",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "cities",
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
			0 => "",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "11",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "ADDRESS",
			1 => "FEEDBACK",
			2 => "WORKING",
			3 => "HREF_FOR_MAP",
			4 => "PHONES",
			5 => "PHOTO_FASAD",
			6 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_FOLDER" => "/addresses/",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "",
		"USE_CATEGORIES" => "Y",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"STRICT_SECTION_CHECK" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"news" => "/addresses/",
			"section" => "#SECTION_CODE#/",
			"detail" => "#SECTION_CODE#/#ELEMENT_CODE#/",
		)
	),
	false
);?>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>