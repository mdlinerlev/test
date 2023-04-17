<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (preg_match('#^/catalog/dlya-dverey/.*#Uui', CURPAGE)) {
    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404", "Y");
}

/*redirect users from Krasnodar*/

use Bitrix\Main\Application,
    Bitrix\Main\Context,
    Bitrix\Main\Request,
    Bitrix\Main\Server;

$isRedirectKrasndar = htmlspecialchars(Application::getInstance()->getContext()->getRequest()->get('REDIRECT_KRASNODAR'));
if ( $isRedirectKrasndar == 'Y')
{
    if( $_SESSION['REDIRECT_KRASNODAR'] != 'Y' ) $_SESSION['REDIRECT_KRASNODAR'] = 'Y';
    if( $_COOKIE['REDIRECT_KRASNODAR'] != 'Y' )
    {
        setcookie('REDIRECT_KRASNODAR', 'Y' , time() + 50000, '/');
        $_COOKIE['REDIRECT_KRASNODAR'] = 'Y';
    }
    header("Location:https://doorskrd.ru/");
}


use Bitrix\Main\Loader, Rover\GeoIp\Location;
Loader::includeModule('rover.geoip');
$location = Location::getInstance($_SERVER['REMOTE_ADDR']);

//test IP from Krasnodar 62.183.18.255
//$_SERVER['REMOTE_ADDR'] - user IP

/* end - redirect users from Krasnodar*/

CModule::IncludeModule("fileman");
$isMobileDevice = CLightHTMLEditor::IsMobileDevice();
//CJSCore::Init(array('jquery2'));
require_once 'inc/template-helper.php';
$_SESSION['DISABLE_RECCURING'] = false;
$curPage = $APPLICATION->GetCurPage(true);
$objCatalog = new CCatalogRU();

define('KOEF_OLD_PRICE', 10000);

$LastModified_unix = time(); // время последнего изменения страницы
$LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
$IfModifiedSince = false;
if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
    $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
    $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
    exit;
}
header('Last-Modified: '. $LastModified);
?><!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
<head itemscope itemtype="http://schema.org/WPHeader">
    <meta itemprop="name" content="Двери BELWOODDOORS">
    <meta itemprop="description" content="Межкомнатные двери от белорусского производителя">
    <!-- Yandex.Metrika counter -->
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/35453395" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
<!-- End Google Tag Manager -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width"/>
    <meta name="google-site-verification" content="SsPqYbONcpXhBweNbd_ocd6eGymmhS0FI3tnwcmlgzM"/>
    <meta name="facebook-domain-verification" content="77c5p18gjd1epaweu25kr111zw3ygt"/>
    <link rel="shortcut icon" type="image/x-icon" href="<?= SITE_DIR ?>favicon.ico"/>
    <? $APPLICATION->ShowHead(); ?>
    <title itemprop="headline">
        <script async id="zd_ct_phone_script" src="https://my.zadarma.com/js/ct_phone.min.js"></script>
        <? $APPLICATION->ShowTitle() ?></title>
    <?
    CJSCore::Init(['ajax']);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/jquery-ui.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/magnific-popup.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/owl.carousel.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/countdown.css');
    #$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/bootstrap-grid.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/styles.css');
    #$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/assets/css/styles.all.min.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/css/btn-zv.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/css/libs.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/css/b2b.css");

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/lib/aos.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/lib/slick.css");
    //$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/css/mp.css");

    if($curPage=="/opt/index.php"){
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/css/optom/critical.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/lib/aos.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/lib/swiper-bundle.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/css/optom/lp.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/css/optom/main.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/assets/css/optom/custom.css");
    }

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/assets/css/custom.css");
    // $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/fonts/roboto.css");
    //$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/fonts/opensans.css");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery-2.2.1.min.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/lib/aos.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/lib/slick.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/lib/jquery.cookie.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/imagesloaded.pkgd.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.easing.1.3.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.magnific-popup.min.js");
    if($curPage!="/opt/index.php") {
        $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.mask.min.js");
    }
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.multifile.pack.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.placeholder-enhanced.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.validate.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery-ui.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery-ui.touch-punch.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/owl.carousel.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/flexmenu.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.maskedinput.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/jquery.countdown.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/scripts.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/phone-cta.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/track-cookies.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/create-object.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/create-object.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/libs.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/b2b.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/js/main.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/assets/js/mp.js");

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/custom.js");


    include $_SERVER['DOCUMENT_ROOT'] . '/include/tags_head.php';
    ?>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH . "/fonts/roboto.css" ?>" as="style"/>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH . "/fonts/opensans.css" ?>" as="style"/>


    <script type="text/javascript">
        <?php foreach ($_SESSION['maxby_referer_params'] as $k=>$v):?>
        <?php echo "var $k='$v';\n"; ?>
        <?php endforeach; ?>
    </script>

    <script>
        window.mainUrl = '<?= UrlUtils::getCanonicalUrl().$APPLICATION->GetCurPage() ?>';
        $('meta[property="og:url"]').attr("content", $("link[rel=canonical]").attr("href"))
    </script>
</head>
<body class="index _show-msg-on-leave <? if (strpos(CURPAGE, 'personal-b2b') !== false) { ?>b2b-pages<? } ?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KP23GW7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?
//include $_SERVER['DOCUMENT_ROOT'] . '/include/tags_body.php';
?>
<? require($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/assets/images/icons.svg"); ?>
<div class="offcanvas">
    <aside class="offcanvas__menu">
        <div class="offcanvas-menu__inner">
            <section class="offcanvas-menu__search">
                <?
                $APPLICATION->IncludeComponent(
                    "newsite:search.title",
                    "search_main_menu_new",
                    array(
                        "NUM_CATEGORIES" => "1",
                        "TOP_COUNT" => "5",
                        "CHECK_DATES" => "Y",
                        "SHOW_OTHERS" => "N",
                        "PAGE" => SITE_DIR . "catalog/",
                        "CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
                        "CATEGORY_0" => array(
                            0 => "iblock_catalog",
                        ),
                        "CATEGORY_0_iblock_catalog" => array(
                            0 => "2",
                        ),
                        "CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
                        "SHOW_INPUT" => "Y",
                        "INPUT_ID" => "search-mobile-input",
                        "BUTTON_ID" => "search-mobile-btn",
                        "CONTAINER_ID" => "search-mobile",
                        "PLACEHOLDER" => "Поиск",
                        "PRICE_CODE" => array(
                            0 => "BASE",
                        ),
                        "SHOW_PREVIEW" => "Y",
                        "PREVIEW_WIDTH" => "75",
                        "PREVIEW_HEIGHT" => "75",
                        "CONVERT_CURRENCY" => "Y",
                        "COMPONENT_TEMPLATE" => "search_main_menu",
                        "ORDER" => "date",
                        "USE_LANGUAGE_GUESS" => "N",
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "CURRENCY_ID" => MAIN_CURRENCY,
                        "CATEGORY_0_iblock_offers" => array(
                            0 => "all",
                        ),
                    ),
                    false
                );
                ?>
            </section>
            <div class="offcanvas-menu__shop-links">
                <? // корзина
                require 'inc/basket_line.php';
                ?>

            </div>
        </div>
    </aside>
    <div class="offcanvas__shim">
    </div>
</div>

<? if (strpos(CURPAGE, 'personal-b2b') !== false){ ?>
<div class="page-wrapper" id="bx_eshop_wrap">
    <? require_once 'inc/header.php' ?>
    <? $APPLICATION->IncludeComponent(
        "newsite:promotionalHeadBanner",
        ".default",
        array(
            "LINK_FOR_PROMOTIONAL" => "https://belwooddoors.ru/about/news/34084/",
            "COMPONENT_TEMPLATE" => ".default",
            "ACTIVE" => "Y",
            "MY_DATA" => "31.01.2022 23:59:00",
            "BANNER" => "/images/counter-desktop-ru.jpg",
            "MOB_BANNER" => "/images/counter-mob-ru2.jpg",
            "BACK_COLOR" => "#ff652f",
            "SLESH_COLOR" => "#fafafa",
            "MAIN_TEXT" => "НОВОГОДНИЕ СКИДКИ!",
            "ADDITIONAL_TEXT" => "- 22% на фурнитуру",
            "BACKGROUND_COLOR" => "#ffae73",
            "TITLE_COLOR" => "#222227",
            "DESCRIPTION_COLOR" => "#40404B"
        ),
        false
    ); ?>
    <section class="main-content">
        <section class="breadcrumbs">
            <div class="content-container">
                <?
                $APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", array(
                    "START_FROM" => "0",
                    "PATH" => "",
                    "SITE_ID" => "-"
                ), false, Array('HIDE_ICONS' => 'Y')
                );
                ?>
            </div>
        </section>
        <section class="catalog catalog--main">
            <div class="content-container">
                <h1 class="catalog__title"><?= $APPLICATION->ShowTitle(false); ?></h1>
            </div>
        </section>
    </section>
</div>
<div class="content-container">
    <div class="b2b-wrapper">
        <? if ($APPLICATION->GetDirProperty('hide_b2bmenu') != 'Y') { ?>
            <div class="b2b-aside">
                <div class="b2b-menu">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "personal",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "left",
                            "USE_EXT" => "N"
                        )
                    ); ?>
                    <ul class="b2b-menu__ul">
                        <li>
                            <a href="?logout=yes">
                                <svg class="icon icon-exit ">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#exit"></use>
                                </svg>
                                <span>Выйти из личного кабинета</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <? } ?>
        <div class="<?= ($APPLICATION->GetDirProperty('b2b_container_class_custom')) ? $APPLICATION->GetDirProperty('b2b_container_class_custom') : 'b2b-content'; ?>">
            <? } else { ?>
            <div class="page-wrapper" id="bx_eshop_wrap">
                <? require_once 'inc/header.php' ?>
                <? $APPLICATION->IncludeComponent(
	"newsite:promotionalHeadBanner", 
	".default", 
	array(
		"LINK_FOR_PROMOTIONAL" => "https://belwooddoors.ru/about/news/45265/",
		"COMPONENT_TEMPLATE" => ".default",
		"ACTIVE" => "Y",
		"MY_DATA" => "30.04.2023 23:59:00",
		"BANNER" => "/images/counter-desktop-ru.jpg",
		"MOB_BANNER" => "/images/counter-mob-ru2.jpg",
		"BACK_COLOR" => "#ff652f",
		"SLESH_COLOR" => "#fafafa",
		"MAIN_TEXT" => "До конца апреля!",
		"ADDITIONAL_TEXT" => "Пятая дверь из заказа - в подарок!",
		"BACKGROUND_COLOR" => "#ffae73",
		"TITLE_COLOR" => "#222227",
		"DESCRIPTION_COLOR" => "#40404B"
	),
	false
); ?>
                <section class="main-content">
                    <? if ($curPage != SITE_DIR . "index.php"): ?>
                        <? if ($APPLICATION->GetDirProperty('hide_breadcrumbs') != 'Y') { ?>
                            <section class="breadcrumbs">
                                <div class="content-container">
                                    <?
                                    $APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", array(
                                        "START_FROM" => "0",
                                        "PATH" => "",
                                        "SITE_ID" => "-"
                                    ), false, Array('HIDE_ICONS' => 'Y')
                                    );
                                    ?>
                                </div>
                            </section>
                        <? } ?>
                        <? $isCatalogPage = preg_match("~^" . SITE_DIR . "catalog/~", $curPage); ?>
                        <? $isNewsPage = preg_match("~^" . SITE_DIR . "about/news/~", $curPage); ?>
                        <? $isArticlePage = preg_match("~^" . SITE_DIR . "articles/~", $curPage); ?>
                        <? // if (isDetailCatalogPage()): ?>
                        <? if ($isNewsPage || $isArticlePage): // Добавление canonical?>
                            <? if (CSite::InDir(SITE_DIR . "articles/index.php") || CSite::InDir(SITE_DIR . "about/news/index.php")): ?>
                                <? $APPLICATION->SetPageProperty('canonical', urlDomain() . $APPLICATION->GetCurPage(false) . 'all/'); //$APPLICATION->AddHeadString('<link rel="canonical" href="' . urlDomain() . $APPLICATION->GetCurPage(false) .'all/'. '" />'); ?>
                            <? else : ?>
                                <? $APPLICATION->SetPageProperty('canonical', urlDomain() . $APPLICATION->GetCurPage(false)); //$APPLICATION->AddHeadString('<link rel="canonical" href="' . urlDomain() . $APPLICATION->GetCurPage(false) .'" />'); ?>
                            <? endif; ?>
                            <? if ($isNewsPage || $isArticlePage) : ?>
                                <section class="page-title-section">
                                    <div class="content-container">
                                        <div class="page-title">
                                            <h1 class="page-title__title"
                                                id="pagetitle"><?= $APPLICATION->ShowTitle(false); ?></h1>
                                        </div>
                                    </div>
                                </section>
                            <? endif; ?>
                        <? elseif ($isCatalogPage): ?>
                            <? $APPLICATION->SetPageProperty('canonical', urlDomain() . $APPLICATION->GetCurPage(false));//$APPLICATION->AddHeadString('<link rel="canonical" href="' . urlDomain() . $APPLICATION->GetCurPage(false) . '" />'); ?>
                        <? elseif (!$isCatalogPage): ?>
                            <? $APPLICATION->SetPageProperty('canonical', urlDomain() . $APPLICATION->GetCurPage(false)); //$APPLICATION->AddHeadString('<link rel="canonical" href="' . urlDomain() . $APPLICATION->GetCurPage(false) . '" />'); ?>
                            <? if ($APPLICATION->GetDirProperty('hide_title') != 'Y') { ?>
                                <section class="page-title-section">
                                    <div class="content-container">
                                        <div class="page-title">
                                            <h1 class="page-title__title"
                                                id="pagetitle"><?= $APPLICATION->ShowTitle(false); ?></h1>
                                        </div>
                                    </div>
                                </section>
                            <? } ?>
                            <? $isAktsiiPage = preg_match("~^" . SITE_DIR . "aktsii/index~", $curPage); ?>
                            <? if ($isAktsiiPage): ?>
                                <section class="index-slider  index-slider--akcii">
                                    <div class="content-container">
                                        <?
                                        // слайдер
                                        require 'inc/slider_aktsii.php';
                                        ?>
                                    </div>
                                </section><br>
                            <? else: ?>
                            <? endif; ?>

                        <? else: ?>

                        <? endif; ?>
                    <? else: ?>
                        <? $APPLICATION->SetPageProperty('canonical', urlDomain());//$APPLICATION->AddHeadString('<link rel="canonical" href="' . urlDomain() . '" />'); ?>
                        <section class="index-slider">
                            <div class="content-container">

                                <? if (!$isMobileDevice) { ?>
                                    <div class="banner-w-slider">
                                        <div class="banner-w-slider__main">
                                            <? // слайдер
                                            require 'inc/slider.php';
                                            ?>
                                        </div>
                                        <? // слайдер дополнительные
                                        require 'inc/slider_grid.php';
                                        ?>
                                    </div>
                                <? } else { ?>
                                    <!--мобильный слайдер начало-->
                                    <section class="index-slider2" style="display: none;">
                                        <? require 'inc/mslider.php'; ?>
                                    </section><!--мобильный слайдер конец-->
                                <? } ?>

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
                            </div>
                        </section>
                    <? endif ?>

                    <? if (
                    ($curPage != SITE_DIR . "index.php") &&
                    (!$isCatalogPage) &&
                    !preg_match('#^/personal/cart/.*#Uui', CURPAGE) &&
                    !preg_match('#^/personal/order/make/.*#Uui', CURPAGE) &&
                    !preg_match('#^/catalog/compare/.*#Uui', CURPAGE)
                    ){ ?>
                    <section class="text text-content">
                        <div class="<?if ($APPLICATION->GetDirProperty('full_width') != 'Y'){?>content-container<?}?>">
                            <? require 'inc/left_menu.php'; ?>
                            <? $textContentClass = 'text__content';
                            if (defined('content_no_padding')) {
                                $textContentClass .= '  text__content--no-padding';
                            } ?>
                            <div class="<?= $textContentClass ?>">
                                <? } ?>
<? } ?>