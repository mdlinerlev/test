<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?><?$APPLICATION->IncludeComponent(
	"medialine:main.map",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"URL_" => "Главная",
		"URL_about_" => "О магазине",
		"URL_about_garantiya_" => "Гарантия и сертификаты",
		"URL_about_kak-zakazat_" => "Как заказать",
		"URL_about_news_" => "Новости",
		"URL_about_realizovannye-proekty_" => "Реализованные проекты",
		"URL_about_sertifikaty_" => "Сертификаты",
		"URL_addresses_" => "Адреса салонов Belwooddoors",
		"URL_aktsii_" => "Акции",
		"URL_articles_" => "Статьи",
		"URL_baffle_" => "Баффле",
		"URL_catalog_" => "Каталог",
		"URL_chetyrekhstvorchatye-razdvizhnye-dveri_" => "Система Rota",
		"URL_contacts_" => "Контакты",
		"URL_delivery_" => "Информация о доставке",
		"URL_dvustvorchatye-razdvizhnye-dveri_" => "Двустворчатые раздвижные двери",
		"URL_faq_" => "faq",
		"URL_installation_" => "Установка",
		"URL_invisible_" => "СКРЫТЫЕ ДВЕРИ ДЛЯ СТИЛЬНЫХ ИНТЕРЬЕРОВ",
		"URL_kredit-i-rassrochka_" => "Кредит и рассрочка",
		"URL_oplata_" => "Оплата",
		"URL_planed_" => "Погонаж",
		"URL_razdvizhnye-dveri_" => "Раздвижные двери",
		"URL_razdvizhnye-dveri_penalnaya-sistema_" => "Пенальная система",
		"URL_trekhstvorchatye-razdvizhnye-dveri_" => "Система Magic",
		"URL_zamer_" => "Замер"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>