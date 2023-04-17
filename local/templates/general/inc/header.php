<header class="header">
    <section class="header-fixed">
        <div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
        <div class="content-container">
            <a class="header-fixed__menu-button">Меню</a>
            <script data-b24-form="click/28/si2hx0" data-skip-moving="true">
                (function (w, d, u) {
                    var s = d.createElement('script');
                    s.async = true;
                    s.src = u + '?' + (Date.now() / 180000 | 0);
                    var h = d.getElementsByTagName('script')[0];
                    h.parentNode.insertBefore(s, h);
                })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_28_si2hx0.js');
            </script>
            <a data-popup="" class="contacts__button--master button-measuring button popup-link">Заказать замер</a>
            <script data-b24-form="click/8/bv4tdf" data-skip-moving="true"> (function (w, d, u) {
                    var s = d.createElement('script');
                    s.async = true;
                    s.src = u + '?' + (Date.now() / 180000 | 0);
                    var h = d.getElementsByTagName('script')[0];
                    h.parentNode.insertBefore(s, h);
                })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_8_bv4tdf.js'); </script>
            <a data-popup="" class="header-fixed__button__popup--callback button popup-link">Заказать звонок</a>
            <!--data-popup="popup--callback"-->

            <div class="btns">
                <a href="#" class="header-text-item__callback" id="btns-link">Консультация</a>
            </div>
            <div class="header-fixed__right">
                <div class="header-fixed__phones">
                    <div class="header-fixed-phones__number">
                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:main.include", "", Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/tel1.php"
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="header-fixed__shop-links">
                    <? require_once 'basket.php'; ?>
                </div>
            </div>
        </div>
    </section>
    <section class="header-top">
        <div class="content-container">
            <a class="header__menu-button">
                <svg class="icon icon-list">
                    <use xlink:href="#icon-list"></use>
                </svg>
                <span class="header__menu-span">Меню</span>
            </a>
            <div class="header__logo">
                <a class="header-logo__link" <? if ($APPLICATION->GetCurPage() != '/index.php') echo 'href="/"' ?>>
                    <img class="header-logo__image" alt="Логотип BELWOODDOORS"
                         src="<?= SITE_TEMPLATE_PATH ?>/assets/images/logo_white.png" width="170" height="30">
                </a>
            </div>
            <div class="main-menu">
                <a class="main-menu__link main-menu__link--back">Назад в меню
                </a>
                <nav class="main-menu__container main-menu__container--level1">
                    <?
                    // верхнее меню
                    require 'top_menu.php';
                    ?>
                </nav>
            </div>
            <div class="header__languages">
                <? /* if (SITE_ID == 's1') { ?>
                                <span class="header-languages__link header-languages__link--active">Рус
                                </span>/
                                <a href="javascript:void(0)" class="header-languages__link">Укр
                                </a>
<? } else { ?>
                                <a href="javascript:void(0)" class="header-languages__link">Рус
                                </a>/
                                <span class="header-languages__link header-languages__link--active">Укр</span>
<? } */ ?>
            </div>
        </div>
    </section>
    <section class="header-middle">
        <div class="fixed-header  js-fixed">
            <div class="fixed-header__wrap">
                <div class="content-container header-middle-container">
                    <div class="header__logo">
                        <? if ($curPage != SITE_DIR . "index.php"): ?>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include", "", Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/company_logo.php"
                                )
                            ); ?>
                        <? else: ?>
                            <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:main.include", "", Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/company_logo_index.php"
                                )
                            );
                            ?>
                        <? endif; ?>
                    </div>
                    <div class="header_search">
                        <? require 'search.php'; ?>
                    </div>
                    <div class="header-b2b">
                        <div class="header-b2b__phone header-b2b__btn">
                            <div class="header-b2b__img">
                                <svg class="icon icon-phone ">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#phone"></use>
                                </svg>
                            </div>
                            <div class="header-b2b__content">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include", "", Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/include/tel1.php"
                                    )
                                ); ?>
								<p style="margin-bottom: 0px; font-size: 14px; display: inline-block;">Пн — Вс: с 07:00 до 22:00</p>
                                <script data-b24-form="click/8/bv4tdf" data-skip-moving="true">
                                    (function (w, d, u) {
                                        var s = d.createElement('script');
                                        s.async = true;
                                        s.src = u + '?' + (Date.now() / 180000 | 0);
                                        var h = d.getElementsByTagName('script')[0];
                                        h.parentNode.insertBefore(s, h);
                                    })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_22_gbambh.js');
                                </script>
                                <a class="call-popup" data-class="w440">Заказать обратный звонок</a>
                            </div>
                        </div>
                        <?
                        $basketUrl = SITE_DIR.'personal/cart/';
                        if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) {
                            $basketUrl = SITE_DIR.'personal-b2b/cart/';
                        }
                        $APPLICATION->IncludeComponent(
                            "bitrix:sale.basket.basket.line",
                            "b2b",
                            array(
                                "HIDE_ON_BASKET_PAGES" => "N",
                                "PATH_TO_AUTHORIZE" => "",
                                "PATH_TO_BASKET" => $basketUrl,
                                "PATH_TO_ORDER" => SITE_DIR . "personal/order/make/",
                                "PATH_TO_PERSONAL" => SITE_DIR . "personal/",
                                "PATH_TO_PROFILE" => SITE_DIR . "personal/",
                                "PATH_TO_REGISTER" => SITE_DIR . "login/",
                                "POSITION_FIXED" => "N",
                                "SHOW_AUTHOR" => "N",
                                "SHOW_EMPTY_VALUES" => "Y",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_PRODUCTS" => "N",
                                "SHOW_REGISTRATION" => "N",
                                "SHOW_TOTAL_PRICE" => "N",
                                "COMPONENT_TEMPLATE" => "b2b"
                            ),
                            false
                        ); ?>

                        <div class="header-b2b__btn">
                            <? if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) { ?>
                                <?global  $arHlElements;
                                $arHlElements = HLHelpers::getInstance()->getElementList(8, ['UF_USER_ID' => \Bitrix\Main\Engine\CurrentUser::get()->getId(), 'UF_CHECK' => 'N']);
                                ?>
                                <a class="header-b2b__img <?=count($arHlElements)>0?'has_notification"':''?>"  href="/personal-b2b/" <?=count((array)$arHlElements)>0?'data-count="'.count((array)$arHlElements).'"':''?>>
                                    <svg class="icon icon-cabinet ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#cabinet"></use>
                                    </svg>
                                </a>
                            <? } else {?>
                                <a class="header-b2b__img ajax-form" data-href="/ajax/reAjax.php?action=modalAuth" data-class="w440">
                                    <svg class="icon icon-cabinet ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#cabinet"></use>
                                    </svg>
                                </a>
                            <? } ?>
                            <div class="header-b2b__content">
                                <? if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) { ?>
                                    <a class="name" href="/personal-b2b/">B2B кабинет</a>
                                    <a class="val" href="/?logout=yes">Выйти</a>
                                <? } else { ?>
                                    <div class="name">B2B кабинет</div>
                                    <a class="val ajax-form" href="#" data-href="/ajax/reAjax.php?action=modalAuth"
                                       data-class="w440">Войти</a>
                                <? } ?>

                                <? if($location->getRegionCode() == 'RU-KDA' && $isRedirectKrasndar !== 'Y'): ?>

                                    <a class="val ajax-form"
                                       href="#"
                                       data-href="/ajax/reAjax.php?action=modalAuth_redirect"
                                       data-class="w440" id="popup-redirect">
                                    </a>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                    <? if (false) { ?>
                        <div class="header__text-item--phone">
                            <div class="header-text-item__phone">
                                <svg class="icon-phone">
                                    <use xlink:href="#phone"></use>
                                </svg>
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include", "", Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/include/tel1.php"
                                    )
                                );
                                ?>
                            </div>
                            <script data-b24-form="click/8/bv4tdf" data-skip-moving="true">
                                (function (w, d, u) {
                                    var s = d.createElement('script');
                                    s.async = true;
                                    s.src = u + '?' + (Date.now() / 180000 | 0);
                                    var h = d.getElementsByTagName('script')[0];
                                    h.parentNode.insertBefore(s, h);
                                })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_22_gbambh.js');
                            </script>
                            <a data-popup="" class="underline nowrap popup-link">Заказать обратный звонок</a>
                        </div>
                        <div class="header__shoping" id="basket_head"></div>
                    <? } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="header-bottom">
        <div class="content-container">
            <a class="header__button header__button--catalog-button button"><span>Каталог товаров</span>
            </a>
            <div class="header__catalog-menu">
                <a class="header-catalog-menu__button">Каталог
                </a>
                <a class="header-catalog-menu__link header-catalog-menu__link--back"><span class="back-menu">Назад в меню</span><span
                            class="back-catalog">Назад в каталог</span>
                </a>
                <?
                // меню
                require 'main_menu.php';
                ?>
            </div>

        </div>
    </section>
    <section class="header-mobile-bottom-fixed">
        <div class="mobnav_bottom">
            <a href="/catalog/" class="mobnav_item">
                <div class="mobnav_icon">
                    <svg class="icon">
                        <use xlink:href="#icon-catalog"></use>
                    </svg>
                </div>
                <div class="mobnav_text">Каталог</div>
            </a>
            <a href="/addresses/" class="mobnav_item">
                <div class="mobnav_icon">
                    <svg class="icon">
                        <use xlink:href="#map_marker_line"></use>
                    </svg>
                </div>
                <div class="mobnav_text">Салоны</div>
            </a>
            <div href="#" class="mobnav_item tooltip-link tooltip-top">
                <div>
                    <div class="mobnav_icon">
                        <svg class="icon">
                            <use xlink:href="#exclamation_mark"></use>
                        </svg>
                    </div>
                    <div class="mobnav_text">Контакт</div>
                </div>
                <div class="tooltip mobnav_popup">
                    <div class="mobnav_popup_line">
                        <svg class="icon popup_icon">
                            <use xlink:href="#phone_line"></use>
                        </svg>
                        <script data-b24-form="click/8/bv4tdf" data-skip-moving="true"> (function (w, d, u) {
                                var s = d.createElement('script');
                                s.async = true;
                                s.src = u + '?' + (Date.now() / 180000 | 0);
                                var h = d.getElementsByTagName('script')[0];
                                h.parentNode.insertBefore(s, h);
                            })(window, document, 'https://bitrix.belwood.ru/upload/crm/form/loader_8_bv4tdf.js'); </script>
                        <span data-popup="" class="product__button popup-link"> <!-- data-popup="popup--callback" -->
                                Заказать звонок
                            </span>
                    </div>
                    <span class="mobnav_popup_line">
				            <svg class="icon popup_icon">
					            <use xlink:href="#phone_line"></use>
				            </svg>

                            <? if (CModule::IncludeModule("spectr.targetcontent")):
                                $APPLICATION->IncludeComponent("spectr:spectr.target-content", "template1", Array(
                                    "COMPONENT_TEMPLATE" => ".default"
                                ),
                                    false
                                );
                            else:?>
                                <a class="zphone" href="tel:+74993808919"><span></span></a>
                            <? endif; ?>

			            </span>

                </div>
            </div>
            <a href="/catalog/?q=" class="mobnav_item mobnav_item_search">
                <div class="mobnav_icon">
                    <svg class="icon">
                        <use xlink:href="#search"></use>
                    </svg>
                </div>
                <div class="mobnav_text">Поиск</div>
            </a>
        </div>
    </section>
    <div class="move_top">
        <svg class="move_top_arrow">
            <use xlink:href="#arrow-up"></use>
        </svg>
    </div>
</header>