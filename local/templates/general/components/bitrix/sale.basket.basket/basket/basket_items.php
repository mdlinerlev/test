<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @var array $arParams */
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
    ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn = false;
$bPriceType = false;

if ($normalCount > 0):
    ?>
<?

foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item){$total_count += $item['QUANTITY'];



}?>

    <!--Форма для физического лица-->
    <section class="cart__form">
        <form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
            <h2>Новая верстка для физического лица</h2>
            <div class="basket">
                <div class="basket__table">
                    <div class="basket__head">

                    </div>
                    <div class="basket__body">

                        <div class="basket__item">
                            <div class="basket__item-img">
                                <a class="basket__item-img-link" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    <img src="/upload/resize_cache/iblock/0a3/160_160_1/0a38073c4884740d8649ca0bf33b0df3.jpg" class="cart-table__image">
                                </a>
                            </div>
                            <div class="basket__item-params">
                                <a class="basket__item-title" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    Дверное Полотно Пвд "Классика Люкс" Дуб 2,0-0.8
                                </a>
                                <div class="basket__item-art">Арт. 00002430</div>
                                <div class="basket__item-parameters">
                                    <div class="basket__item-prm"><span>Размер полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">2000х800</span></div>
                                    <div class="basket__item-prm"><span>Цвет полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">Белый дуб</span></div>
                                    <div class="basket__item-prm"><span>Стекло</span><span class="basket__float-dots"></span><span class="basket__item-span">Люкс витраж белый</span></div>
                                    <div class="basket__item-prm"><span>Формат двери</span><span class="basket__float-dots"></span><span class="basket__item-span">Двухстворчатая</span></div>

                                </div>

                                <div class="basket__item-instore">
                                    <svg class="instore"><use xlink:href="#tick"></use></svg>
                                    В наличии
                                </div>
                                <div class="basket__item-instore basket__item-instore--gray">
                                    <svg class="instore instore--gray"><use xlink:href="#tick"></use></svg>
                                    Под заказ
                                </div>
                            </div>

                            <div class="basket__item-price">
                                <div class="basket__item-sum-price" id="sum_5973">
                                    1 408.80 руб.
                                </div>
                                <div class="basket__item-price--discount" id="current_price_5973">
                                    <div class="basket__item-price--base" id="old_price_5973">874.20 руб.</div>
                                    704.40 руб. <span class="basket__item-price--span">/ за шт.</span>

                                </div>

                                <div class="basket__item-quantity">
                                    <label for="QUANTITY_INPUT_5973" class="sr-only">Количество:</label>
                                    <div class="basket__item-quantity-inputs">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--minus" onclick="setQuantity(5973, 0, 'down', false);">-</a>
                                        <input class="quantity__input" type="text" size="3" id="QUANTITY_INPUT_5973" name="QUANTITY_INPUT_5973" maxlength="18" min="0" max="0" step="1" style="max-width: 50px" value="2" onchange="updateQuantity('QUANTITY_INPUT_5973', '5973', 0.5, false)">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--plus" onclick="setQuantity(5973, 0.5, 'up', false);">+</a>
                                        <input type="hidden" id="QUANTITY_5973" name="QUANTITY_5973" value="2">
                                    </div>
                                </div>
                                <!-- Купить в 1 клик попап -->
                                <div class="one-click">
                                    <div class="one-click-buy button button--secondary magnific-popup mt-10">Купить в 1 клик</div>

                                    <script>
                                        $('.one-click-buy').on('click', function () {
                                            var item = $(this).closest(".basket__item");
                                            var selectParams = item.find('.basket__item-prm');
                                            var formContent = '';
                                            selectParams.each(function () {
                                                var inputVal = $(this).find(".basket__item-span").text();
                                                var title = $(this).find("span:first-child").text();
                                                formContent += '<div class="popup_info__row"><span class="popup_info_first">'+title+'</span><span class="popup_info_dots"></span><span class="popup_info_second">'+inputVal+'</span><input type="hidden" value="'+title +' '+inputVal+'"></div>'
                                            })
                                            $.magnificPopup.open({
                                                    items: {
                                                        src: '<div class="white-popup"><form class="popup__form" method="post">' +
                                                            '<div class="popup__title">' + item.find(".basket__item-title").text() + '</div>' +
                                                            '<div class="popup_info">' + formContent + '</div>' +
                                                            '<div class="popup-form__row"><div class="popup-form__form-group"><label for="one-click-phone" class="popup-form__label">Телефон <span class="required" aria-required="true">*</span></label><input type="tel" id="one-click-phone" class="phone_input" name="form_phone" value="" size="0" required placeholder="+375 (29) 123-45-67"></div></div>' +
                                                            '<div class="popup-form__row"><input class="button popup__submit" type="submit" name="web_form_submit" value="Отправить"></div>' +
                                                            '</form></div>',
                                                    },
                                                    type: 'inline',
                                                    callbacks: {
                                                        open: function() {
                                                            $(".phone_input").mask("+7 (999) 999-99-99");
                                                        },
                                                    }
                                                }
                                            );
                                        });

                                    </script>
                                </div>
                                <div class="basket__item-remove">
                                    <a class="basket__item-remove-link" href="/personal/cart/index.php?basketAction=delete&amp;id=5973">Удалить</a>
                                </div>
                            </div>
                        </div>

                        <a href="?basketAction=deleteall" class="cart-topbar__button cart-topbar__button--clear-button button"><span>Удалить всё из корзины</span>
                        </a>



                        <h3>Оформление заказа</h3>
                        <div class="basket_order">
                            <div class="basket_order__step">
                                <div class="basket_order__title">Данные</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <input type="radio" name="tabset" id="tab1" aria-controls="content1" class="tabset_inp" checked>
                                        <label for="tab1" class="tabset_checkbox">Физическое лицо</label>
                                        <a href="#" class="link_checkbox">Юридическое лицо</a>
                                        <div class="tab-panels">
                                                <div class="basket_order__descr">
                                                    <label class="basket_order__subtitle">Ваше имя
                                                        <input type="text" name="name" placeholder="Ф.И.О" class="validate">
                                                    </label>
                                                    <label class="basket_order__subtitle">E-mail
                                                        <input type="email" name="email" placeholder="E-Mail" class="validate"/>
                                                        <span class="form__error">Это поле должно содержать E-Mail в формате name@site.com</span>
                                                    </label>
                                                    <label class="basket_order__subtitle">Телефон
                                                        <input type="tel" name="name" placeholder="Ваш номер телефона" pattern="^\D*(?:\d\D*){9,}$" minlength="9" maxlength="19" required class="validate"/>
                                                        <span class="form__error">Это поле должно содержать телефон с кодом оператора 00 1234567</span>
                                                    </label>

                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="basket_order__step">
                                <div class="basket_order__title">Доставка</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <input type="radio" name="delivery_type" id="deliv" class="tabset_inp" aria-controls="deliv1" checked>
                                        <label for="deliv" class="tabset_checkbox">Доставка</label>
                                        <input type="radio" name="delivery_type" id="self" class="tabset_inp" aria-controls="deliv2">
                                        <label for="self" class="tabset_checkbox">Самовывоз</label>
                                        <div class="tab-panels">
                                            <section id="deliv1" class="tab-panel">
                                                <div class="basket_order__descr">
                                                    <label class="basket_order__subtitle">Адрес доставки
                                                        <input type="text" name="adress" placeholder="Адрес доставки" required class="validate">
                                                        <span class="form__error">Введите адрес доставки</span>
                                                    </label>
                                                    Телефон для справок:
                                                    +375 17 388 15 58
                                                </div>
                                            </section>
                                            <section id="deliv2" class="tab-panel">
                                                <div class="basket_order__descr">
                                                    <div class="basket_order__descr_title">
                                                        Пункт самовывоза:
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        Минск, ул. Промышленная, дом 10
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        Пн-Пт с 9:00 до 22:00
                                                    </div>
                                                    <div class="basket_order__descr_title">
                                                        Телефон для справок:
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        +375 17 388 15 58
                                                    </div>
                                                    <div id="yandex-map" class="order__map-container">
                                                        <div id="map" style="width:100%; height:100%"></div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="basket_order__step">
                                <div class="basket_order__title">Оплата</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <input type="radio" name="payment_type" id="nal" class="tabset_inp" aria-controls="payment_type1" checked>
                                        <label for="nal" class="tabset_checkbox">Наличными</label>
                                        <input type="radio" name="payment_type" id="bez" class="tabset_inp" aria-controls="payment_type2">
                                        <label for="bez" class="tabset_checkbox">Безналичный расчет</label>
                                        <input type="radio" name="payment_type" id="card" class="tabset_inp" aria-controls="payment_type3">
                                        <label for="card" class="tabset_checkbox">Оплата картой при получении</label>
                                        <div class="tab-panels">
                                            <section id="payment_type1" class="tab-panel">
                                                <p><b>
                                                        Для оплаты покупки наличными деньгами, при доставке с курьером, введите в поле комментарий если вам нужна будет сдача и с какой суммы. Если вы осуществляете самовывоз, оплату необходимо будет совершить в кассе пункта выдачи товара.
                                                    </b></p>
                                                <div class="basket_order__subtitle">
                                                    Комментарий к оплате
                                                </div>
                                                <textarea name="" id="" cols="30" rows="3" placeholder="Например, подготовить сдачу со 100 руб."></textarea>


                                            </section>
                                            <section id="payment_type2" class="tab-panel">
                                                <p><b>На указанный e-mail будет выслан счет (его отправка займет не более 2 часов).</b></p>
                                                <p>Получив счет на e-mail, оплатите его в течение 3 рабочих дней (по истечении этого срока он станет недействителен). После зачисления денег с вами свяжется наш специалист для уточнения условий доставки. В назначенное время и место курьер привезет товар вместе с документами на подпись.</p>
                                            </section>
                                            <section id="payment_type3" class="tab-panel">
                                                <p><b>К оплате принимаются карты Visa, Visa Electron, MasterCard, БЕЛКАРТ всех классов и банков, Maestro с CVC-кодом и Белкарт. </b></p>

                                                <p>При оформлении заказа на сайте выберите способ «Оплатить сейчас онлайн», далее выберите подходящий вариант. Введите данные для доставки и комментарий, нажмите кнопку «Подтвердить заказ». Вы попадете на страницу подтверждения заказа, где будет указан его номер и сумма платежа. На этой странице нажмите кнопку «Оплатить счет». После вы будете перенаправлены на сервер системы, обеспечивающей безопасность платежей, где вам потребуется ввести реквизиты своей карты и персональные данные. Операция оплаты банковской картой онлайн полностью конфиденциальна и безопасна.</p>

                                                <p>Для большинства карт используется перенаправление в систему Webpay. Доступ к данным карты осуществляется по протоколу безопасной передачи данных TLS</p>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="basket__float">
                    <div class="basket__float_box">
                        <div class="basket__float-promo">
                            <div class="toggled-elem">Промокод на скидку&nbsp;<svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg>
                            </div>
                            <div class="toggled-item">
                                <input type="text" name="promocode"/>
                                <div class="basket__float-promo-ok">
                                    Промокод применен
                                </div>
                                <div class="basket__float-promo-no">
                                    Промокод не найден
                                </div>
                            </div>
                        </div>
                        <div class="basket__float-summary">
                            <div class="basket__float-row"><span>Товаров</span><span class="basket__float-dots"></span><span class="basket__item-span">2</span></div>
                            <div class="basket__float-row"><span>К&nbsp;оплате&nbsp;<small>(без доставки)</small></span><span class="basket__float-dots"></span><span class="basket__item-span">444 руб </span></div>
                            <div class="basket__float-row"><span>Доставка</span><span class="basket__float-dots"></span><span class="basket__item-span">222 руб.</span></div>

                            <div class="basket__float-row"><span>К оплате</span><span class="basket__float-dots"></span><span class="basket__item-span">666 руб</span></div>
                        </div>
                        <div class="basket__float-comment">
                            <div class="toggled-elem">Комментарий к заказу&nbsp;<svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg>
                            </div>
                            <textarea class="toggled-item" name="comment" id="" cols="30" rows="3"></textarea>
                        </div>


                        <div class="basket__float-addition">
                            <div class="checkbox filters__checkbox">
                                <input type="checkbox" value="Y" name="arrFilter_75_3260818684" id="arrFilter_75_3260818684">
                                <label for="arrFilter_75_3260818684">
                                    <span class="checked_filter_params"><svg class="icon-tick"><use xlink:href="#tick"></use></svg></span>
                                    <span class="bx-filter-param-text" title="Требуется вызов замерщика">Требуется вызов замерщика</span>
                                </label>
                            </div>
                            <div class="checkbox filters__checkbox">
                                <input type="checkbox" value="Y" name="arrFilter_75_3260818685" id="arrFilter_75_3260818685">
                                <label for="arrFilter_75_3260818685">
                                    <span class="checked_filter_params"><svg class="icon-tick"><use xlink:href="#tick"></use></svg></span>
                                    <span class="bx-filter-param-text" title="Требуется вызов замерщика">Требуется монтаж</span>
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-lg btn-submit" type="submit">Оформить заказ</button>
                    </div>
                </div>
            </div>
        </form>
    </section>


    <!--Форма для юридического лица-->
    <section class="cart__form">
        <form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
            <h2>Новая верстка для юрлица</h2>
            <div class="basket">
                <div class="basket__table">
                    <div class="basket__head">

                    </div>
                    <div class="basket__body">

                        <div class="basket__item">
                            <div class="basket__item-img">
                                <a class="basket__item-img-link" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    <img src="/upload/resize_cache/iblock/0a3/160_160_1/0a38073c4884740d8649ca0bf33b0df3.jpg" class="cart-table__image">
                                </a>
                            </div>
                            <div class="basket__item-params">
                                <a class="basket__item-title" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    Дверное Полотно Пвд "Классика Люкс" Дуб 2,0-0.8
                                </a>
                                <div class="basket__item-art">Арт. 00002430</div>
                                <div class="basket__item-parameters">
                                    <div class="basket__item-prm"><span>Размер полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">2000х800</span></div>
                                    <div class="basket__item-prm"><span>Цвет полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">Белый дуб</span></div>
                                    <div class="basket__item-prm"><span>Стекло</span><span class="basket__float-dots"></span><span class="basket__item-span">Люкс витраж белый</span></div>
                                    <div class="basket__item-prm"><span>Формат двери</span><span class="basket__float-dots"></span><span class="basket__item-span">Двухстворчатая</span></div>

                                </div>

                                <div class="basket__item-instore">
                                    <svg class="instore"><use xlink:href="#tick"></use></svg>
                                    В наличии
                                </div>
                                <div class="basket__item-instore basket__item-instore--gray">
                                    <svg class="instore instore--gray"><use xlink:href="#tick"></use></svg>
                                    Под заказ
                                </div>
                            </div>

                            <div class="basket__item-price">
                                <div class="basket__item-sum-price" id="sum_5973">
                                    1 408.80 руб.
                                </div>
                                <div class="basket__item-price--discount" id="current_price_5973">
                                    <div class="basket__item-price--base" id="old_price_5973">874.20 руб.</div>
                                    704.40 руб. <span class="basket__item-price--span">/ за шт.</span>

                                </div>

                                <div class="basket__item-quantity">
                                    <label for="QUANTITY_INPUT_5973" class="sr-only">Количество:</label>
                                    <div class="basket__item-quantity-inputs">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--minus" onclick="setQuantity(5973, 0, 'down', false);">-</a>
                                        <input class="quantity__input" type="text" size="3" id="QUANTITY_INPUT_5973" name="QUANTITY_INPUT_5973" maxlength="18" min="0" max="0" step="1" style="max-width: 50px" value="2" onchange="updateQuantity('QUANTITY_INPUT_5973', '5973', 0.5, false)">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--plus" onclick="setQuantity(5973, 0.5, 'up', false);">+</a>
                                        <input type="hidden" id="QUANTITY_5973" name="QUANTITY_5973" value="2">
                                    </div>
                                </div>
                                <!-- Купить в 1 клик попап -->
                                <div class="one-click">
                                    <div class="one-click-buy button button--secondary magnific-popup mt-10">Купить в 1 клик</div>

                                    <script>
                                        $('.one-click-buy').on('click', function () {
                                            var item = $(this).closest(".basket__item");
                                            var selectParams = item.find('.basket__item-prm');
                                            var formContent = '';
                                            selectParams.each(function () {
                                                var inputVal = $(this).find(".basket__item-span").text();
                                                var title = $(this).find("span:first-child").text();
                                                formContent += '<div class="popup_info__row"><span class="popup_info_first">'+title+'</span><span class="popup_info_dots"></span><span class="popup_info_second">'+inputVal+'</span><input type="hidden" value="'+title +' '+inputVal+'"></div>'
                                            })
                                            $.magnificPopup.open({
                                                    items: {
                                                        src: '<div class="white-popup"><form class="popup__form" method="post">' +
                                                            '<div class="popup__title">' + item.find(".basket__item-title").text() + '</div>' +
                                                            '<div class="popup_info">' + formContent + '</div>' +
                                                            '<div class="popup-form__row"><div class="popup-form__form-group"><label for="one-click-phone" class="popup-form__label">Телефон <span class="required" aria-required="true">*</span></label><input type="tel" id="one-click-phone" class="phone_input" name="form_phone" value="" size="0" required placeholder="+375 (29) 123-45-67"></div></div>' +
                                                            '<div class="popup-form__row"><input class="button popup__submit" type="submit" name="web_form_submit" value="Отправить"></div>' +
                                                            '</form></div>',
                                                    },
                                                    type: 'inline',
                                                    callbacks: {
                                                        open: function() {
                                                            $(".phone_input").mask("+7 (999) 999-99-99");
                                                        },
                                                    }
                                                }
                                            );
                                        });

                                    </script>
                                </div>
                                <div class="basket__item-remove">
                                    <a class="basket__item-remove-link" href="/personal/cart/index.php?basketAction=delete&amp;id=5973">Удалить</a>
                                </div>
                            </div>
                        </div>
                        <div class="basket__item">
                            <div class="basket__item-img">
                                <a class="basket__item-img-link" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    <img src="/upload/resize_cache/iblock/0a3/160_160_1/0a38073c4884740d8649ca0bf33b0df3.jpg" class="cart-table__image">
                                </a>
                            </div>
                            <div class="basket__item-params">
                                <a class="basket__item-title" href="/catalog/mezhkomnatnye_dveri/aurum-3-osteklennoe/">
                                    Дверное Металлическое Полотно "Люкс"
                                </a>
                                <div class="basket__item-art">Арт. 00002430</div>
                                <div class="basket__item-parameters">
                                    <div class="basket__item-prm"><span>Размер полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">3300х800</span></div>
                                    <div class="basket__item-prm"><span>Цвет полотна</span><span class="basket__float-dots"></span><span class="basket__item-span">Белый дуб</span></div>
                                    <div class="basket__item-prm"><span>Стекло</span><span class="basket__float-dots"></span><span class="basket__item-span">Люкс белый</span></div>
                                    <div class="basket__item-prm"><span>Формат двери</span><span class="basket__float-dots"></span><span class="basket__item-span">Глухая</span></div>

                                </div>

                                <div class="basket__item-instore">
                                    <svg class="instore"><use xlink:href="#tick"></use></svg>
                                    В наличии
                                </div>
                                <div class="basket__item-instore basket__item-instore--gray">
                                    <svg class="instore instore--gray"><use xlink:href="#tick"></use></svg>
                                    Под заказ
                                </div>
                            </div>

                            <div class="basket__item-price">
                                <div class="basket__item-sum-price" id="sum_5973">
                                    1 408.80 руб.
                                </div>
                                <div class="basket__item-price--discount" id="current_price_5973">
                                    <div class="basket__item-price--base" id="old_price_5973">874.20 руб.</div>
                                    704.40 руб. <span class="basket__item-price--span">/ за шт.</span>

                                </div>

                                <div class="basket__item-quantity">
                                    <label for="QUANTITY_INPUT_5973" class="sr-only">Количество:</label>
                                    <div class="basket__item-quantity-inputs">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--minus" onclick="setQuantity(5973, 0, 'down', false);">-</a>
                                        <input class="quantity__input" type="text" size="3" id="QUANTITY_INPUT_5973" name="QUANTITY_INPUT_5973" maxlength="18" min="0" max="0" step="1" style="max-width: 50px" value="2" onchange="updateQuantity('QUANTITY_INPUT_5973', '5973', 0.5, false)">
                                        <a href="javascript:void(0);" class="quantity__button quantity__button--plus" onclick="setQuantity(5973, 0.5, 'up', false);">+</a>
                                        <input type="hidden" id="QUANTITY_5973" name="QUANTITY_5973" value="2">
                                    </div>
                                </div>
                                <!-- Купить в 1 клик попап -->
                                <div class="one-click">
                                    <div class="one-click-buy button button--secondary magnific-popup mt-10">Купить в 1 клик</div>

                                    <script>
                                        $('.one-click-buy').on('click', function () {
                                            var item = $(this).closest(".basket__item");
                                            var selectParams = item.find('.basket__item-prm');
                                            var formContent = '';
                                            selectParams.each(function () {
                                                var inputVal = $(this).find(".basket__item-span").text();
                                                var title = $(this).find("span:first-child").text();
                                                formContent += '<div class="popup_info__row"><span class="popup_info_first">'+title+'</span><span class="popup_info_dots"></span><span class="popup_info_second">'+inputVal+'</span><input type="hidden" value="'+title +' '+inputVal+'"></div>'
                                            })
                                            $.magnificPopup.open({
                                                    items: {
                                                        src: '<div class="white-popup"><form class="popup__form" method="post">' +
                                                            '<div class="popup__title">' + item.find(".basket__item-title").text() + '</div>' +
                                                            '<div class="popup_info">' + formContent + '</div>' +
                                                            '<div class="popup-form__row"><div class="popup-form__form-group"><label for="one-click-phone" class="popup-form__label">Телефон <span class="required" aria-required="true">*</span></label><input type="tel" id="one-click-phone" class="phone_input" name="form_phone" value="" size="0" required placeholder="+375 (29) 123-45-67"></div></div>' +
                                                            '<div class="popup-form__row"><input class="button popup__submit" type="submit" name="web_form_submit" value="Отправить"></div>' +
                                                            '</form></div>',
                                                    },
                                                    type: 'inline',
                                                    callbacks: {
                                                        open: function() {
                                                            $(".phone_input").mask("+7 (999) 999-99-99");
                                                        },
                                                    }
                                                }
                                            );
                                        });

                                    </script>
                                </div>
                                <div class="basket__item-remove">
                                    <a class="basket__item-remove-link" href="/personal/cart/index.php?basketAction=delete&amp;id=5973">Удалить</a>
                                </div>
                            </div>
                        </div>


                        <h3>Оформить заказ</h3>
                        <div class="basket_order">
                            <div class="basket_order__step">
                                <div class="basket_order__title">Данные</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <a href="#" class="link_checkbox">Физическое лицо</a>
                                        <input type="radio" name="tabset" id="tab2" aria-controls="content2" class="tabset_inp" checked>
                                        <label for="tab2" class="tabset_checkbox">Юридическое лицо</label>
                                        <div class="tab-panels">
                                            <div class="order__form-group order__form-group--files">
                                                    <div class="order__files-title">Укажите реквизиты
                                                    </div>
                                                    <div class="order__files-or">или
                                                    </div>
                                                    <input type="file" size="0" value="Array" name="ORDER_PROP_21[0]" id="ORDER_PROP_21[0]" class="order__file-input"><a class="order__button order__button--file button"><span>Добавьте файл с реквизитами</span>
                                                    </a>
                                            </div>
                                                <div class="basket_order__descr">
                                                    <label class="basket_order__subtitle">Номер расчетного счета
                                                        <input type="text" name="name" placeholder="Счет" pattern="\d{20}" required class="validate">
                                                        <span class="form__error">Введите номер расчетного счета</span>
                                                    </label>
                                                    <label class="basket_order__subtitle">ИНН
                                                        <input type="text" name="name" placeholder="ИНН" pattern="\d{10}" required class="validate">
                                                        <span class="form__error">Введите номер ИНН</span>
                                                    </label>
                                                    <label class="basket_order__subtitle">ОКПО
                                                        <input type="text" name="name" placeholder="ОКПО" pattern="\d{8,}" required class="validate">
                                                        <span class="form__error">Введите номер ОКПО</span>
                                                    </label>
                                                    <label class="basket_order__subtitle">Юридический адрес
                                                        <input type="text" name="name" placeholder="Юридический адрес">
                                                    </label>
                                                    <label class="basket_order__subtitle">Полное название организации *
                                                        <input type="text" name="name" placeholder="Необходимо для избежания ошибки" required class="validate">
                                                    </label>
                                                    <label class="basket_order__subtitle">Контактный номер *
                                                        <input type="tel" name="name" placeholder="Ваш номер телефона" pattern="^\D*(?:\d\D*){9,}$" minlength="9" maxlength="19" required class="validate"/>
                                                        <span class="form__error">Это поле должно содержать телефон с кодом оператора 00 1234567</span>
                                                    </label>
                                                    <label class="basket_order__subtitle">Имя лица, оформляющего заказ *
                                                        <input type="text" name="name" placeholder="Название юрлица">
                                                    </label>
                                                    <label class="basket_order__subtitle">E-Mail *
                                                        <input type="email" name="email" placeholder="E-Mail" required class="validate"/>
                                                        <span class="form__error">Это поле должно содержать E-Mail в формате name@site.com</span>
                                                    </label>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="basket_order__step">
                                <div class="basket_order__title">Доставка</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <input type="radio" name="delivery_type" id="deliv" class="tabset_inp" aria-controls="deliv1" checked>
                                        <label for="deliv" class="tabset_checkbox">Доставка</label>
                                        <input type="radio" name="delivery_type" id="self" class="tabset_inp" aria-controls="deliv2">
                                        <label for="self" class="tabset_checkbox">Самовывоз</label>
                                        <div class="tab-panels">
                                            <section id="deliv1" class="tab-panel">
                                                <div class="basket_order__descr">
                                                    <label class="basket_order__subtitle">Адрес доставки
                                                        <input type="text" name="adress" placeholder="Адрес доставки" class="validate" required>
                                                        <span class="form__error">Введите адрес доставки</span>
                                                    </label>
                                                    Телефон для справок:
                                                    +375 17 388 15 58
                                                </div>
                                            </section>
                                            <section id="deliv2" class="tab-panel">
                                                <div class="basket_order__descr">
                                                    <div class="basket_order__descr_title">
                                                        Пункт самовывоза:
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        Минск, ул. Промышленная, дом 10
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        Пн-Пт с 9:00 до 22:00
                                                    </div>
                                                    <div class="basket_order__descr_title">
                                                        Телефон для справок:
                                                    </div>
                                                    <div class="basket_order__descr_text">
                                                        +375 17 388 15 58
                                                    </div>
                                                    <div id="yandex-map" class="order__map-container">
                                                        <div id="map" style="width:100%; height:100%"></div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="basket_order__step">
                                <div class="basket_order__title">Оплата</div>
                                <div class="basket_order__content">
                                    <div class="tabset">
                                        <input type="radio" name="payment_type" id="nal" class="tabset_inp" aria-controls="payment_type1" checked>
                                        <label for="nal" class="tabset_checkbox">Наличными</label>
                                        <input type="radio" name="payment_type" id="bez" class="tabset_inp" aria-controls="payment_type2">
                                        <label for="bez" class="tabset_checkbox">Безналичный расчет</label>
                                        <input type="radio" name="payment_type" id="card" class="tabset_inp" aria-controls="payment_type3">
                                        <label for="card" class="tabset_checkbox">Оплата картой при получении</label>
                                        <div class="tab-panels">
                                            <section id="payment_type1" class="tab-panel">
                                                <p><b>
                                                        Для оплаты покупки наличными деньгами, при доставке с курьером, введите в поле комментарий если вам нужна будет сдача и с какой суммы. Если вы осуществляете самовывоз, оплату необходимо будет совершить в кассе пункта выдачи товара.
                                                    </b></p>
                                                <div class="basket_order__subtitle">
                                                    Комментарий к оплате
                                                </div>
                                                <textarea name="" id="" cols="30" rows="3" placeholder="Например, подготовить сдачу со 100 руб."></textarea>


                                            </section>
                                            <section id="payment_type2" class="tab-panel">
                                                <p><b>На указанный e-mail будет выслан счет (его отправка займет не более 2 часов).</b></p>
                                                <p>Получив счет на e-mail, оплатите его в течение 3 рабочих дней (по истечении этого срока он станет недействителен). После зачисления денег с вами свяжется наш специалист для уточнения условий доставки. В назначенное время и место курьер привезет товар вместе с документами на подпись.</p>
                                            </section>
                                            <section id="payment_type3" class="tab-panel">
                                                <p><b>К оплате принимаются карты Visa, Visa Electron, MasterCard, БЕЛКАРТ всех классов и банков, Maestro с CVC-кодом и Белкарт. </b></p>

                                                <p>При оформлении заказа на сайте выберите способ «Оплатить сейчас онлайн», далее выберите подходящий вариант. Введите данные для доставки и комментарий, нажмите кнопку «Подтвердить заказ». Вы попадете на страницу подтверждения заказа, где будет указан его номер и сумма платежа. На этой странице нажмите кнопку «Оплатить счет». После вы будете перенаправлены на сервер системы, обеспечивающей безопасность платежей, где вам потребуется ввести реквизиты своей карты и персональные данные. Операция оплаты банковской картой онлайн полностью конфиденциальна и безопасна.</p>

                                                <p>Для большинства карт используется перенаправление в систему Webpay. Доступ к данным карты осуществляется по протоколу безопасной передачи данных TLS</p>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="basket__float">
                    <div class="basket__float_box">
                        <div class="basket__float-promo">
                            <div class="toggled-elem">Промокод на скидку&nbsp;<svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg>
                            </div>
                            <div class="toggled-item">
                                <input type="text" name="promocode"/>
                                <div class="basket__float-promo-ok">
                                    Промокод применен
                                </div>
                                <div class="basket__float-promo-no">
                                    Промокод не найден
                                </div>
                            </div>
                            
                        </div>
                        <div class="basket__float-summary">
                            <div class="basket__float-row"><span>Товаров</span><span class="basket__float-dots"></span><span class="basket__item-span">2</span></div>
                            <div class="basket__float-row"><span>К&nbsp;оплате&nbsp;<small>(без доставки)</small></span><span class="basket__float-dots"></span><span class="basket__item-span">444 руб </span></div>
                            <div class="basket__float-row"><span>Доставка</span><span class="basket__float-dots"></span><span class="basket__item-span">222 руб.</span></div>

                            <div class="basket__float-row"><span>К оплате</span><span class="basket__float-dots"></span><span class="basket__item-span">666 руб</span></div>
                        </div>
                        <div class="basket__float-comment">
                            <div class="toggled-elem">Комментарий к заказу&nbsp;<svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg>
                            </div>
                            <textarea class="toggled-item" name="comment" id="" cols="30" rows="3"></textarea>
                        </div>


                        <div class="basket__float-addition">
                            <div class="checkbox filters__checkbox">
                                <input type="checkbox" value="Y" name="arrFilter_75_3260818684" id="arrFilter_75_3260818684">
                                <label for="arrFilter_75_3260818684">
                                    <span class="checked_filter_params"><svg class="icon-tick"><use xlink:href="#tick"></use></svg></span>
                                    <span class="bx-filter-param-text" title="Требуется вызов замерщика">Требуется вызов замерщика</span>
                                </label>
                            </div>
                            <div class="checkbox filters__checkbox">
                                <input type="checkbox" value="Y" name="arrFilter_75_3260818685" id="arrFilter_75_3260818685">
                                <label for="arrFilter_75_3260818685">
                                    <span class="checked_filter_params"><svg class="icon-tick"><use xlink:href="#tick"></use></svg></span>
                                    <span class="bx-filter-param-text" title="Требуется вызов замерщика">Требуется монтаж</span>
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-lg btn-submit" type="submit">Оформить заказ</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
                        <?
                    else:
                        ?>
    <section class="cart">
        <table>
            <tbody>
                <tr>
                    <td style="text-align:center">
                        <div class=""><?= GetMessage("SALE_NO_ITEMS"); ?></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

<?
endif;
?>


<script>


    ymaps.ready(function () {
        var myMap = new ymaps.Map('map', {
                center: [53.846692, 27.684541],
                zoom: 12
            }, {
                searchControlProvider: 'yandex#search'
            }),
            myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                hintContent: 'Собственный значок метки',
                balloonContent: 'точка самовывоза'
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#image',
                iconColor: '#fff'
                // Своё изображение иконки метки.
                //iconImageHref: 'images/myIcon.gif',
                // Размеры метки.
                //iconImageSize: [20, 22],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                //iconImageOffset: [-3, -42]
            });

        myMap.geoObjects.add(myPlacemark);
    });

    BX.addCustomEvent('onAjaxSuccess', function () {
            ymaps.ready(function () {
                var myMap = new ymaps.Map('map', {
                    <? if (intval($loc['ID']) == 3326) { ?>
                        center: [53.846129, 27.683193],
                    <? } ?>
                        center: [53.846692, 27.684541],
                        zoom: 12
                    }, {
                        searchControlProvider: 'yandex#search'
                    }),
                    myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                        hintContent: 'Собственный значок метки',
                        balloonContent: 'точка самовывоза'
                    }, {
                        // Опции.
                        // Необходимо указать данный тип макета.
                        preset: "twirl#greenStretchyIcon"
                        // Своё изображение иконки метки.
                        //iconImageHref: 'images/myIcon.gif',
                        // Размеры метки.
                        //iconImageSize: [20, 22],
                        // Смещение левого верхнего угла иконки относительно
                        // её "ножки" (точки привязки).
                        //iconImageOffset: [-3, -42]
                    });
                myMap.geoObjects.add(myPlacemark);
            });
        }
    );
    

</script>
