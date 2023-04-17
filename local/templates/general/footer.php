<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if(!empty($_SESSION['REDIRECT_KRASNODAR']) && $_SESSION['REDIRECT_KRASNODAR'] != 'N' ):?>
    <script>
        $(document).ready(() => {
            var link = document.getElementById('popup-redirect');
            link.click();
        });
    </script>
<?php endif ?>

<? if (!$isCatalogPage): ?>

    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.include", "", Array(
        "AREA_FILE_SHOW" => "sect",
        "AREA_FILE_SUFFIX" => "sidebar",
        "AREA_FILE_RECURSIVE" => "Y",
        "EDIT_MODE" => "html",
    ), false, Array('HIDE_ICONS' => 'Y')
    );
    ?>
    <!--// sidebar -->
<? endif ?>

<?if (strpos(CURPAGE, 'personal-b2b') === false){ ?>
    <? if (
        ($curPage != SITE_DIR . "index.php") &&
        (!$isCatalogPage) &&
        !preg_match('#^/personal/cart/.*#Uui', CURPAGE) &&
        !preg_match('#^/personal/order/make/.*#Uui', CURPAGE) &&
        !preg_match('#^/catalog/compare/.*#Uui', CURPAGE)
    ){ ?>
        </div>
        </div>
        </section>
    <? } ?>
    </section>
<? } else { ?>
    </div>
    <?php
    if (strpos(CURPAGE, 'personal-b2b/') !== false && strpos(CURPAGE, 'personal-b2b/cart/') === false){
        if(count((array)$arHlElements) > 0) {?>
            <div class="b2b-aside b2b-aside-2">
                <div class="b2b-menu">
                    <div class="zag">Уведомления</div>
                    <ul class="b2b-menu__ul">
                        <?foreach ($arHlElements as $elem) {?>
                            <li class="active">
                                <div>Изменен статус товаров в заказе <a class="b2b-table__order-num ajax-form" data-href="/ajax/reAjax.php?action=modalOrderDetail&amp;ID=<?=$elem['UF_ORDER_ID']?>"><?=$elem['UF_NUMBER_1C']?></a></div>
                                <a href="javascript:void(0);" class="check" data-id="<?=$elem['ID']?>">Прочитано</a>
                            </li>
                        <?}?>
                    </ul>
                </div>
            </div>
        <?}
    }?>
    </div>
    </div>
<? } ?>
<footer class="footer" data-landingId="<?= LANDING_ID ?>" itemscope itemtype="http://schema.org/WPFooter">
    <meta itemprop="copyrightYear" content="2022">
    <meta itemprop="copyrightHolder" content="BELWOODDOORS">
    <section class="footer-section footer-section--orange">
        <div class="content-container content-container--orange">
            <div class="footer--orange">
                <div class="subscribe subscribe--white">
                    <form action="https://belwooddoors.us19.list-manage.com/subscribe?u=61556c8c94e531765bb2be71f&id=32c789c356"
                          method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                          class="validate" target="_blank" novalidate>
                        <div class="subscribe__title">
                            Рассылка для оптовых покупателей и партнеров
                        </div>
                        <div class="subscribe__form">
                            <div class="subscribe__group">
                                <input type="email" value="" name="EMAIL" class="required email subscribe_email"
                                       placeholder="Электронная почта" id="mce-EMAIL">
                                <div class="response" id="mce-error-response">Неверный email</div>
                                <input type="submit" value="Подписаться" name="subscribe" id="mc-embedded-subscribe"
                                       class="btn subscribe_button">
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input type="text" name="b_61556c8c94e531765bb2be71f_1d5b9dbea2" tabindex="-1" value="">
                            </div>
                        </div>


                    </form>
                </div>
                <!--                <div class="socials  socials--white">-->
                <!--                    <div class="socials__title">-->
                <!--	                    Следите за новинками и акциями! Наши страницы и чаты.-->
                <!--                    </div>-->
                <!--                    <div class="socials__links">-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="https://www.facebook.com/Belwooddoors-BY-664898020365911/"><svg class="socials_svg"><use xlink:href="#icon-facebook"></use></svg></a>-->
                <!--                        </span>-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="viber://chat?number=+375293150012"><svg class="socials_svg"><use xlink:href="#icon-viber"></use></svg></a>-->
                <!--                        </span>-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="https://api.whatsapp.com/send?phone=375293150012"><svg class="socials_svg"><use xlink:href="#icon-whatsapp"></use></svg></a>-->
                <!--                        </span>-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="https://www.instagram.com/belwooddoors_by"><svg class="socials_svg"><use xlink:href="#icon-instagram"></use></svg></a>-->
                <!--                        </span>-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="https://www.youtube.com/channel/UCP6NYMtPVfKT51F0q3a_jFA"><svg class="socials_svg"><use xlink:href="#icon-youtube"></use></svg></a>-->
                <!--                        </span>-->
                <!--                        <span class="socials__link">-->
                <!--                            <a targer="_blank" href="https://www.pinterest.com/belwooddoors_ru/"><svg class="socials_svg"><use xlink:href="#icon-pinterest"></use></svg></a>-->
                <!--                        </span>-->
                <!--                    </div>-->
                <!--                </div>-->

            </div>


            <!--End mc_embed_signup-->
        </div>
    </section>
    <section class="footer-section">
        <div class="content-container">
            <div class="footer-section__menu">
                <div class="footer-section-menu__item footer-section-menu__item--level1 footer-section-menu__item--has-items">
                    <span class="footer-section-menu__link footer-section-menu__link--level1 footer-section-menu__link--has-items">Каталог</span>
                    <div class="footer-section-menu__container footer-section-menu__container--level2">
                        <?
                        // меню каталога футер
                        require 'inc/catalog_bottom_menu.php';
                        ?>
                    </div>
                </div>
                <div class="footer-section-menu__item footer-section-menu__item--level1 footer-section-menu__item--has-items">
                    <span class="footer-section-menu__link footer-section-menu__link--level1 footer-section-menu__link--has-items">Информация</span>
                    <div class="footer-section-menu__container footer-section-menu__container--level2">
                        <?
                        // меню разделов футера
                        require 'inc/footer_static_menu.php';
                        ?>
                    </div>
                </div>
                <div class="footer-section__address-container">
                    <?
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include", "", Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/footer_adresses.php"
                        )
                    );
                    ?>
                    <a href="<?= SITE_DIR ?>addresses/" class="footer-section-address-container__link">Все салоны</a>
                </div>
                <div class="footer-section__contacts">
                    <?
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include", "", Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => "/include/footer_contacts.php"
                        )
                    );
                    ?>
                    <div>
                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:main.include", "", Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/social_links.php"
                            )
                        );
                        ?>
                    </div>
                    <img src="<?= SITE_TEMPLATE_PATH . '/preload.svg' ?>" data-src="/upload/visa.png" class="owl-lazy"
                         alt="способы оплаты">

                </div>
            </div>

            <div class="text_about_shop">
                <div class="tabs">
                    <div class="tab">
                        <input type="checkbox" id="chck_about">
                        <label itemprop="name" class="tab-label" for="chck_about">
                            О магазине&nbsp;
                            <svg class="tab-arrow">
                                <use xlink:href="#arrow-down"></use>
                            </svg>
                        </label>
                        <div itemprop="description" class="tab-content">
                            На сегодняшний день мы поставляем наши двери в 21 страну мира. География поставок
                            BELWOODDOORS постоянно расширяется. Качество наших дверей, а также выгодные условия
                            сотрудничества являются ключевыми элементами в развитии нашей сети.
                        </div>
                    </div>
                </div>

            </div>

            <div class="socials-block-adaptive  mobile_only">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/social_links.php",
                        "COMPONENT_TEMPLATE" => ".default"
                    ),
                    false
                );
                ?>
                <img class="lazy" src="<?= SITE_TEMPLATE_PATH . '/preload.svg' ?>" data-src="/upload/visa.png"
                     alt="способы оплаты">
            </div>

            <div class="footer-section-contacts__developer">
                <div class="footer-section-contacts__copyright">
                    © <?= date('Y') ?> BELWOODDOORS
                </div>


            </div>
        </div>
    </section>
    <? //микроразметка?>
    <? //$APPLICATION->IncludeComponent(
    //	"coffeediz:schema.org.OrganizationAndPlace",
    //	"myOrganizationAndPlace",
    //	array(
    //		"ADDRESS" => "Кутузовский проспект 36А, 3 этаж, салон BELWOODDOORS ",
    //		"BESTRATING" => "5",
    //		"COUNTRY" => "ru",
    //		"DESCRIPTION" => "",
    //		"EMAIL" => array(
    //			0 => "bwdru@belwooddoors.by",
    //		),
    //		"FAX" => "",
    //		"ITEMPROP" => "",
    //		"LATITUDE" => "",
    //		"LOCALITY" => "Москва",
    //		"LOGO" => "/bitrix/templates/general/assets/images/logo.png",
    //		"LOGO_CAPTION" => "",
    //		"LOGO_DESCRIPTION" => "",
    //		"LOGO_HEIGHT" => "",
    //		"LOGO_NAME" => "Belwooddoors logo",
    //		"LOGO_TRUMBNAIL_CONTENTURL" => "",
    //		"LOGO_WIDTH" => "",
    //		"LONGITUDE" => "",
    //		"NAME" => "OOO \"СтройДеталь\"",
    //		"OPENING_HOURS_HUMAN" => array(
    //			0 => "С Понедельника по Пятницу 9-20",
    //			1 => "Суббота, Воскресенье 10-19",
    //			2 => "",
    //		),
    //		"OPENING_HOURS_ROBOT" => array(
    //			0 => "Mo-Fr 9:00&#8722;20:00",
    //			1 => "St,Sn 10:00&#8722;19:00",
    //			2 => "",
    //		),
    //		"PARAM_RATING_SHOW" => "Y",
    //		"PHONE" => array(
    //			0 => "+7(495)981-6495",
    //			1 => "+7(964)628-5623",
    //			2 => "",
    //		),
    //		"PHOTO_CAPTION" => array(
    //		),
    //		"PHOTO_DESCRIPTION" => array(
    //		),
    //		"PHOTO_HEIGHT" => array(
    //		),
    //		"PHOTO_NAME" => array(
    //		),
    //		"PHOTO_SRC" => array(
    //		),
    //		"PHOTO_TRUMBNAIL_CONTENTURL" => array(
    //		),
    //		"PHOTO_WIDTH" => array(
    //		),
    //		"POST_CODE" => "121170",
    //		"RAITINGCOUNT" => "",
    //		"RATINGVALUE" => "",
    //		"RATING_SHOW" => "Y",
    //		"REGION" => "Московская область",
    //		"REVIEWCOUNT" => "",
    //		"SHOW" => "Y",
    //		"SITE" => "belwooddoors.ru",
    //		"TAXID" => "7728297840",
    //		"TYPE" => "Organization",
    //		"TYPE_2" => "LocalBusiness",
    //		"TYPE_3" => "Store",
    //		"TYPE_4" => "FurnitureStore",
    //		"WORSTRATING" => "1",
    //		"COMPONENT_TEMPLATE" => "myOrganizationAndPlace"
    //	),
    //	false,
    //	array(
    //		"HIDE_ICONS" => "N"
    //	)
    //);?>
    <? //Конец микроразметки?>
</footer>
<?if (strpos(CURPAGE, 'personal-b2b') === false){ ?>
    </div> <!-- //bx-wrapper -->
<?}?>
<div class="popup popup--callback">
    <? /*$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"popup", 
	array(
		"CACHE_TIME" => "3600",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "popup-2",
		"AJAX_OPTION_JUMP " => "N",
		"AJAX_OPTION_SHADOW" => "N",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "2",
		"COMPONENT_TEMPLATE" => "popup",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);*/ ?>
</div>

<div class="popup popup--splitable-door">
    <? $APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "popup",
        Array(
            "CACHE_TIME" => "3600",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "popup-4",
            "AJAX_OPTION_JUMP " => "N",
            "AJAX_OPTION_SHADOW" => "N",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "EDIT_URL" => "",
            "IGNORE_CUSTOM_TEMPLATE" => "Y",
            "LIST_URL" => "",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID"),
            "WEB_FORM_ID" => "4"
        )
    ); ?>
</div>

<div class="popup popup-oneclickbuy"></div>

<div class="popup popup--contactsok">
    <div id="msgSent">
        <p>Ваша заявка успешно отправлена!</br>Наш оператор свяжется с Вами.</p>
        <button class="msgSent">ОК</button>
    </div>
</div>

<div class="popup popup--measurement">
    <? /*$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"popup", 
	array(
		"CACHE_TIME" => "3600",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "popup-1",
		"AJAX_OPTION_JUMP " => "N",
		"AJAX_OPTION_SHADOW" => "N",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "Y",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "1",
		"COMPONENT_TEMPLATE" => "popup",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);*/ ?>
</div>

<?php if (isset($_SESSION['popup_email_send'])): unset($_SESSION['popup_email_send']); ?>
    <div id="msgSent">
        <p>Ваша заявка успешно отправлена!</br>Наш оператор свяжется с Вами.</p>
        <button class="msgSent" onclick="document.getElementById('msgSent').style.display='none'">ОК</button>
    </div>
<?php endif; ?>
<?
///$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/461dbb3a4b.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/lazyload.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/assets/js/service.model.js");
?>
<?php
if (strlen($_SERVER['REQUEST_URI']) > 2 && !strripos($_SERVER['REQUEST_URI'], "catalog") && !strripos($_SERVER['REQUEST_URI'], "cart")) { ?>
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
            var source = "https://creativecdn.com/tags?type=iframe&id=pr_B6MjHKf0gPbC1LVhT75b&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + encodeURIComponent(timestamp);
            ifr.setAttribute("src", source);
            ifr.setAttribute("width", "1");
            ifr.setAttribute("height", "1");
            ifr.setAttribute("scrolling", "no");
            ifr.setAttribute("frameBorder", "0");
            ifr.setAttribute("style", "display:none");
            ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
            body.appendChild(ifr);
        }());
    </script>
<? } ?>
<?php if (strripos($_SERVER['REQUEST_URI'], "cart")) {
    global $globalBasketLines;
    ?>
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
            var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_basketstatus_<?=$globalBasketLines?>&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
            ifr.setAttribute("src", source);
            ifr.setAttribute("width", "1");
            ifr.setAttribute("height", "1");
            ifr.setAttribute("scrolling", "no");
            ifr.setAttribute("frameBorder", "0");
            ifr.setAttribute("style", "display:none");
            ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
            body.appendChild(ifr);
        }());
    </script>
<?php } ?>
<script>
    var loadRoistat = async () => {
        (function (w, d, u) {
            var s = d.createElement('script');
            s.async = true;
            s.src = u + '?' + (Date.now() / 180000 | 0);
            var h = d.getElementsByTagName('script')[0];
            h.parentNode.insertBefore(s, h);
        })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_8_bv4tdf.js');

        (function (w, d, s, h, id) {
            w.roistatProjectId = id;
            w.roistatHost = h;
            var p = d.location.protocol == "https:" ? "https://" : "http://";
            var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init?referrer=" + encodeURIComponent(d.location.href);
            var js = d.createElement(s);
            js.charset = "UTF-8";
            js.async = 1;
            js.src = p + h + u;
            var js2 = d.getElementsByTagName(s)[0];
            js2.parentNode.insertBefore(js, js2);
        })(window, document, 'script', 'cloud.roistat.com', 'd6a509e5b5119ca1b1ac1f3a92d42bf7');

        $(document).on('mousedown', '.subscribe form', function () {
            var form = $(this).closest('form'),
                email = form.find('.subscribe_email').length ? form.find('.subscribe_email').val() : '',
                form = "Подписаться",
                phone = "",
                comment = "";

            if (email.length) {
                sendToRoistat(phone, email, comment, form);
            }
        });

        (function (w, d, s, h) {
            w.roistatLanguage = '';
            var p = d.location.protocol == "https:" ? "https://" : "http://";
            var u = "/static/marketplace/Bitrix24Widget/script.js";
            var js = d.createElement(s);
            js.async = 1;
            js.src = p + h + u;
            var js2 = d.getElementsByTagName(s)[0];
            js2.parentNode.insertBefore(js, js2);
        })(window, document, 'script', 'cloud.roistat.com');

    };

    function sendToRoistat(phone, email, comment, form) {
        roistatGoal.reach({
            leadName: "Заявка с формы '" + form + "'",
            phone: phone,
            email: email,
            text: comment
        });
    }

    setTimeout(loadRoistat, 1000);
</script>
<!-- END BITRIX24 WIDGET INTEGRATION WITH ROISTAT -->
<?php
/*
$title = $APPLICATION->GetTitle(false);

if ($title == "") {
    $title = $APPLICATION->GetProperty("title");
}

$APPLICATION->AddHeadString('<meta property="og:title" content="' . $title . '"/>', true);
$description = $APPLICATION->GetPageProperty("description");
if ($description == '') {
    $description = $APPLICATION->GetDirProperty("description");
}
if ($description != '') {
    $APPLICATION->AddHeadString('<meta property="og:description" content="' . $description . '"/>', true); //Мета описание, если оно не пусто.
}
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$APPLICATION->AddHeadString('<meta property="og:url" content="' . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" />', true);
global $item_img;
global $article_img;
global $item_img;
if (!empty($item_img)) {
    $APPLICATION->AddHeadString('<meta property="og:image" content="' . $item_img . '"/>', true);
    $APPLICATION->AddHeadString('<meta property="og:type" content="product"/>', true);
    $item_img = null;
} elseif (!empty($article_img)) {
    $APPLICATION->AddHeadString('<meta property="og:image" content="' . $article_img . '"/>', true);
    $APPLICATION->AddHeadString('<meta property="og:type" content="article"/>', true);
    $article_img = null;
} elseif (!empty($new_img)) {
    $APPLICATION->AddHeadString('<meta property="og:image" content="' . $new_img . '"/>', true);
    $APPLICATION->AddHeadString('<meta property="og:type" content="website"/>', true);
    $new_img = null;
} else {
    $APPLICATION->AddHeadString('<meta property="og:image" content="/og-logo.png"/>', true);
    $APPLICATION->AddHeadString('<meta property="og:type" content="website"/>', true);
}*/
?>
<? $APPLICATION->IncludeComponent(
    "coffeediz:schema.org.OrganizationAndPlace",
    "myOrganizationAndPlace",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "TYPE" => "Organization",
        "TYPE_2" => "",
        "NAME" => "BELWOODDORS",
        "POST_CODE" => "",
        "COUNTRY" => "Россия",
        "REGION" => "",
        "LOCALITY" => "",
        "ADDRESS" => "",
        "PHONE" => array(
            0 => "+7 499 380 89 19",
            1 => "",
        ),
        "FAX" => "",
        "SITE" => "belwooddoors.ru",
        "LOGO" => "https://belwooddoors.ru/bitrix/templates/general/assets/images/logo.png",
        "LOGO_URL" => "https://belwooddoors.ru/bitrix/templates/general/assets/images/logo.png",
        "DESCRIPTION" => $APPLICATION->GetDirProperty('description'),
        "PARAM_RATING_SHOW" => "N",
        "ITEMPROP" => "",
        "LOGO_NAME" => "",
        "LOGO_CAPTION" => "",
        "LOGO_DESCRIPTION" => "",
        "LOGO_HEIGHT" => "",
        "LOGO_WIDTH" => "",
        "LOGO_TRUMBNAIL_CONTENTURL" => "",
        "SHOW" => "Y",
        "EMAIL" => array(
        ),
        "TAXID" => "",
        "TYPE_3" => "HomeAndConstructionBusiness",
        "TYPE_4" => "",
        "PHOTO_SRC" => "",
        "PHOTO_NAME" => "",
        "PHOTO_CAPTION" => "",
        "PHOTO_DESCRIPTION" => "",
        "PHOTO_HEIGHT" => "",
        "PHOTO_WIDTH" => "",
        "PHOTO_TRUMBNAIL_CONTENTURL" => "",
        "OPENING_HOURS_ROBOT" => array(
            0 => "Mo-Su 10:00&#8722;19:00",
            1 => "",
            2 => "",
        ),
        "OPENING_HOURS_HUMAN" => array(
            0 => "С Понедельника по Воскресение 10-19",
            1 => "Без выходных",
            2 => "",
        ),
        "LATITUDE" => "",
        "LONGITUDE" => ""
    ),
    false
);
?>
<?
$APPLICATION->IncludeComponent("medialine:cookie.popup", "", [
    "TEXT"=>"Мы используем cookie-файлы. Продолжая пользование сайтом belwooddoors.ru Вы соглашаетесь на использование файлов cookie.",
    "LINK"=>"/cookie/",
    "TEXT_BUTTON"=>"Принять"
]);
?>

</body>
</html>
