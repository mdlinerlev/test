$(document.getElementsByClassName('popup-form__button--submit button')).each(function(i,v) {
    $(v).click(function() {
        if (typeof (yaCounter18399610) != "undefined") {
            yaCounter18399610.reachGoal('callback_form');
        }
    })
});

var baseLandingId = $('.footer').data('landingid');
var baseServiceUrl = "https://web.belwood.ru/0/ServiceModel/GeneratedObjectWebFormService.svc/SaveWebFormObjectData";
var baseRedirectUrl = window.location.href;//"<?php echo $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>";
var config = {
    fields: {
        "Name": "#phoneInput",
        "MobilePhone": "#phoneInput", // Телефон посетителя
        "UsrCommentary": "#date_time" // Желаемые дата и время звонка
    },
    landingId: baseLandingId,
    serviceUrl: baseServiceUrl,
    redirectUrl: baseRedirectUrl
};

function createObject() {
    if ($('.modal-cta__choose').hasClass("_active")) {
        $('#date_time').val('CallBackBWDBY '+jQuery('._week_day option:selected').text() + '-' + jQuery('._time').val());
    } else {
        $('#date_time').val('CallBackBWDBY');
    }
    landing.createObjectFromLanding(config);
    var data = {
        phone:jQuery(config.fields.MobilePhone).val(),
        week_day:jQuery('._week_day option:selected').text(),
        time:jQuery('._time').val(),
    };
    jQuery.post( "/include/sendEmail.php", data);
    jQuery('#modalCta').hide();
    jQuery('#msgSent').show();
}

function initLanding() {
    landing.initLanding(config)
}
jQuery(document).ready(initLanding)
////////////////////////////////
var config2 = {
    fields: {
        "Name": "#phoneInput2",
        "MobilePhone": "#phoneInput2", // Телефон посетителя
        "UsrCommentary": "#date_time2" // Желаемые дата и время звонка
    },
    landingId: baseLandingId,
    serviceUrl: baseServiceUrl,
    redirectUrl: baseRedirectUrl
};

function createObject2() {
    if ($('.modal-cta__choose').hasClass("_active")) {
        $('#date_time2').val('CallBackBWDBY '+jQuery('._week_day option:selected').text() + '-' + jQuery('._time').val());
    } else {
        $('#date_time2').val('CallBackBWDBY');
    }
    landing.createObjectFromLanding(config2);
    var data = {
        phone:jQuery(config2.fields.MobilePhone).val(),
        week_day:jQuery('._week_day option:selected').text(),
        time:jQuery('._time').val(),
    };

    $.post( "/include/sendEmail.php", data);
    jQuery('#modalCta').hide();
    jQuery('#msgSent').show();
}

function initLanding2() {
    landing.initLanding(config2)
}
jQuery(document).ready(initLanding2);
////////////////////////////////////////////////////////////////////////////////
var config3 = {
    fields: {
        "Name": "#phoneInput3",
        "MobilePhone": "#phoneInput3", // Телефон посетителя
        "UsrCommentary": "#date_time3" // Желаемые дата и время звонка
    },
    landingId: baseLandingId,
    serviceUrl: baseServiceUrl,
    redirectUrl: baseRedirectUrl
};

function createObject3() {
    if ($('.modal-cta__choose').hasClass("_active")) {
        $('#date_time3').val('CallBackBWDBY '+jQuery('._week_day option:selected').text() + '-' + jQuery('._time').val());
    } else {
        $('#date_time3').val('CallBackBWDBY');
    }
    landing.createObjectFromLanding(config3);
    var data = {
        phone:jQuery(config3.fields.MobilePhone).val(),
        week_day:jQuery('._week_day3 option:selected').text(),
        time:jQuery('._time3').val(),
    };
    jQuery.post( "/include/sendEmail.php", data);
    jQuery('#modalCta2').hide();
    jQuery('#msgSent').show();
}

function initLanding3() {
    landing.initLanding(config3)
}
jQuery(document).ready(initLanding3)
////////////////////////////////
var config4 = {
    fields: {
        "Name": "#phoneInput4",
        "MobilePhone": "#phoneInput4", // Телефон посетителя
        "UsrCommentary": "#date_time4" // Желаемые дата и время звонка
    },
    landingId: baseLandingId,
    serviceUrl: baseServiceUrl,
    redirectUrl: baseRedirectUrl
};

function createObject4() {
    if ($('.modal-cta__choose').hasClass("_active")) {
        $('#date_time4').val('CallBackBWDBY '+jQuery('._week_day option:selected').text() + '-' + jQuery('._time').val());
    } else {
        $('#date_time4').val('CallBackBWDBY');
    }
    landing.createObjectFromLanding(config4);
    var data = {
        phone:jQuery(config4.fields.MobilePhone).val(),
        week_day:jQuery('._week_day4 option:selected').text(),
        time:jQuery('._time4').val(),
    };
    $.post( "/include/sendEmail.php", data);
    jQuery('#modalCta2').hide();
    jQuery('#msgSent').show();
}

function initLanding4() {
    landing.initLanding(config4)
}
jQuery(document).ready(initLanding4);


// Contact form
if($('form[name=SIMPLE_FORM_3]')) {
    $('form[name=SIMPLE_FORM_3]').removeAttr('action');
    $('input[name=form_text_1]').addClass('contacts-form_name');
    $('input[name=form_text_2]').addClass('contacts-form_phone');
    // $('input[name=form_campaign]').addClass('contacts-form_campaign').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
    // $('input[name=form_source]').addClass('contacts-form_source').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
    // $('input[name=form_medium]').addClass('contacts-form_medium').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
    // $('input[name=form_ip]').addClass('contacts-form_ip').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
    // $('input[name=form_ref]').addClass('contacts-form_ref').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
    $('form[name=SIMPLE_FORM_3]').on('submit',function() {
        var data = {
            content: {
                "Имя":$('.contacts-form_name').val(),
                "Телефон":$('.contacts-form_phone').val(),
                "Комментарий":$('.contacts-form__textarea').val(),
            }
        };

        jQuery.post( "/include/sendEmail.php", data);
        createObjectContact(); return true;
    });


    var configContact = {
        fields: {
            "Name": ".contacts-form_name", // Имя посетителя, заполнившего форму
            "MobilePhone": ".contacts-form_phone", // Телефон посетителя
            "UsrCommentary": ".contacts-form__textarea", // Сюда поместить желаемое время и день звонка, а так же комментарий, если есть
            "BpmRef": ".contacts-form_ref", // Текстовое поле (url referral)
            "Usr9PageSendForm": ".contacts-form_url", // Страница с которой отправлена форма
            "Usr9UtmCampaign": ".contacts-form_campaign", // utm_campaign (cmp)
            "Usr9UtmSource": ".contacts-form_source", // utm_source (src)
            "UsrUtmMedium": ".contacts-form_medium", // utm_medium
            "Usr9GeoIp": ".contacts-form_ip" // Usr9GeoIp
        },
        landingId: baseLandingId,
        serviceUrl: baseServiceUrl,
        redirectUrl: baseRedirectUrl
    };

    function createObjectContact() {
        landing.createObjectFromLanding(configContact);
    }
    /**
     * Функция ниже инициализирует лендинг из параметров URL.
     */
    function initLandingContact() {
        landing.initLanding(configContact);
    }
    $(document).ready(initLandingContact);
}
// // Zamer form
// if($('form[name=SIMPLE_FORM_1]')) {
//     $('form[name=SIMPLE_FORM_1]').removeAttr('action');
//     $('input[name=form_text_8]').addClass('zamer-form_name');
//     $('input[name=form_text_9]').addClass('zamer-form_phone');
//
//     // $('input[name=form_campaign]').addClass('zamer-form_campaign').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_source]').addClass('zamer-form_source').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_medium]').addClass('zamer-form_medium').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_ip]').addClass('zamer-form_ip').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_ref]').addClass('zamer-form_ref').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//
//
//     $('form[name=SIMPLE_FORM_1]').on('submit',function() {
//         var comboData = 'Форма Замер. Комментарий: '+$('textarea[name=form_textarea_11]').val()+'; Адрес:'+$('input[name=form_text_10]').val();
//         $('form[name=SIMPLE_FORM_1]').append('<input type=hidden class=zamer-form_comment value="'+comboData+'" />');
//         var data = {
//             content: {
//                 "Имя":$('.zamer-form_name').val(),
//                 "Телефон":$('.zamer-form_phone').val(),
//                 "Комментарий":$('.zamer-form_comment').val(),
//             }
//         };
//
//         jQuery.post( "/include/sendEmail.php", data);
//         createObjectZamer(); return false;
//     });
//
//
//     var configZamer = {
//         fields: {
//             "Name": ".zamer-form_name", // Имя посетителя, заполнившего форму
//             "MobilePhone": ".zamer-form_phone", // Телефон посетителя
//             "UsrCommentary": ".zamer-form_comment", // Сюда поместить желаемое время и день звонка, а так же комментарий, если есть
//             "BpmRef": ".zamer-form_ref", // Текстовое поле (url referral)
//             "Usr9PageSendForm": ".zamer-form_url", // Страница с которой отправлена форма
//             "Usr9UtmCampaign": ".zamer-form_campaign", // utm_campaign (cmp)
//             "Usr9UtmSource": ".zamer-form_source", // utm_source (src)
//             "UsrUtmMedium": ".zamer-form_medium", // utm_medium
//             "Usr9GeoIp": ".zamer-form_ip" // Usr9GeoIp
//         },
//         landingId: baseLandingId,
//         serviceUrl: baseServiceUrl,
//         redirectUrl: baseRedirectUrl
//     };
//
//     function createObjectZamer() {
//         landing.createObjectFromLanding(configZamer);
//     }
//     /**
//      * Функция ниже инициализирует лендинг из параметров URL.
//      */
//     function initLandingZamer() {
//         landing.initLanding(configZamer);
//     }
//     $(document).ready(initLandingZamer);
// }
// // Dveri form
// if($('form[name=SIMPLE_FORM_2]')) {
//     $('form[name=SIMPLE_FORM_2]').removeAttr('action');
//     $('input[name=form_text_6]').addClass('razddveri-form_name');
//     $('input[name=form_text_7]').addClass('razddveri-form_phone');
//     // $('input[name=form_campaign]').addClass('razddveri-form_campaign').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_source]').addClass('razddveri-form_source').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_medium]').addClass('razddveri-form_medium').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_ip]').addClass('razddveri-form_ip').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     // $('input[name=form_ref]').addClass('razddveri-form_ref').val('<?= $_SESSION['maxby_referer_params']['campaign']?>');
//     $('form[name=SIMPLE_FORM_2]').on('submit',function() {
//         var data = {
//             content: {
//                 "Имя":$('.razddveri-form_name').val(),
//                 "Телефон":$('.razddveri-form_phone').val(),
//                 "Комментарий":$('.razddveri-form_comment').val(),
//             }
//         };
//
//         jQuery.post( "/include/sendEmail.php", data);
//         createObjectDveri(); return false;
//     });
//
//
//     var configDveri = {
//         fields: {
//             "Name": ".razddveri-form_name", // Имя посетителя, заполнившего форму
//             "MobilePhone": ".razddveri-form_phone", // Телефон посетителя
//             "UsrCommentary": ".razddveri-form_comment", // Сюда поместить желаемое время и день звонка, а так же комментарий, если есть
//             "BpmRef": ".razddveri-form_ref", // Текстовое поле (url referral)
//             "Usr9PageSendForm": ".razddveri-form_url", // Страница с которой отправлена форма
//             "Usr9UtmCampaign": ".razddveri-form_campaign", // utm_campaign (cmp)
//             "Usr9UtmSource": ".razddveri-form_source", // utm_source (src)
//             "UsrUtmMedium": ".razddveri-form_medium", // utm_medium
//             "Usr9GeoIp": ".razddveri-form_ip" // Usr9GeoIp
//         },
//         landingId: baseLandingId,
//         serviceUrl: baseServiceUrl,
//         redirectUrl: baseRedirectUrl
//     };
//
//     function createObjectDveri() {
//         landing.createObjectFromLanding(configDveri);
//     }
//     /**
//      * Функция ниже инициализирует лендинг из параметров URL.
//      */
//     function initLandingDveri() {
//         landing.initLanding(configDveri);
//     }
//     $(document).ready(initLandingDveri);
// }



function hidePopupMarketing() {
    $(".popup-window-with-titlebar").hide();
    $(".popup-window-overlay").css('display', 'none');
}

/*var AddCart = new BX.PopupWindow("addCart", null, {
    content: '<p>Товар успешно добавлен в корзину</p>' +
        '<div>' +
        '<a href="/personal/order/make/" class="greenbutton"><span>Oформить заказ</span></a>' +
        '<a onclick="hidePopupMarketing()" class="greenbutton"><span>Продолжить покупки</span></a>' +
        '</div>',
    closeIcon: true,
    titleBar: {content: BX.create("span", {html: '', 'props': {'className': 'access-title-bar'}})},
    zIndex: 0,
    offsetLeft: 0,
    offsetTop: 0,
    draggable: {restrict: true},
    overlay: {backgroundColor: 'black', opacity: '80'},
    closeByEsc: true
});
var Basket = new BX.PopupWindow("Basket", null, {
    content: '',
    closeIcon: true,
    titleBar: {content: BX.create("span", {html: '', 'props': {'className': 'access-title-bar'}})},
    zIndex: 0,
    offsetLeft: 0,
    offsetTop: 0,
    draggable: {restrict: false},
    overlay: {backgroundColor: 'black', opacity: '80'},
    closeByEsc: true
});
var Consult = new BX.PopupWindow("Consult", null, {
    content: '<img src="/bitrix/templates/general/assets/images/operator.png" />' +
        '<p>Возможно, вам требуется дополнительная консультация?</p>' +
        '<div>' +
        '<a class="header-text-item__callback greenbutton" id="callback_p"><span class="header-text-item__callback-inner">заказать звонок</span></a>' +
        '</div>',
    closeIcon: true,
    titleBar: {content: BX.create("span", {html: '', 'props': {'className': 'access-title-bar'}})},
    zIndex: 0,
    offsetLeft: 0,
    offsetTop: 0,
    draggable: {restrict: false},
    overlay: {backgroundColor: 'black', opacity: '80'},
    closeByEsc: true
});
var Callback = new BX.PopupWindow("Callback", null, {
    content: '',
    closeIcon: true,
    titleBar: {content: BX.create("span", {html: '', 'props': {'className': 'access-title-bar'}})},
    zIndex: 0,
    offsetLeft: 0,
    offsetTop: 0,
    draggable: {restrict: false},
    overlay: {backgroundColor: 'black', opacity: '80'},
    closeByEsc: true
});*/
var flag = false;

function popupMarketing(popupWindow, urlWindow, contentDiv) {
    return;
    var currentLocation = window.location.pathname;
    $('#popup-window-overlay-addCart').on('click', function () {
        AddCart.close()
    });
    $('#popup-window-overlay-Basket').on('click', function () {
        Basket.close()
    });
    $('#popup-window-overlay-Consult').on('click', function () {
        Consult.close()
    });
    $('#popup-window-overlay-Callback').on('click', function () {
        Callback.close()
    });
    $('.popup-window > a').attr('class', 'fa fa-times');
    $('.popup-window > a').attr('aria-hidden', 'true');
    if (popupWindow == 'AddCart') {
        AddCart.show();
        return false;
    } else {
        $.ajax({
            type: "POST",
            async: false,
            data: ({curpath: currentLocation}),
            url: urlWindow,
            dataType: "html",
            success: function (data) {
                data = $.trim(data);
                if (data.length > 0) {
                    if (data == 'Consult') {
                        Consult.show();
                    }
                    else {
                        if (typeof contentDiv !== 'undefined') {
                            $('#' + contentDiv).empty();
                            $('#' + contentDiv).append(data);
                            if (contentDiv == 'popup-window-content-Basket') {
                                Basket.show();
                            } else {
                                Callback.show();
                                flag = true;
                            }
                        }
                    }
                }
            }
        });
    }
    return flag;
}
//
//
// BX.ready(function () {
//     //if($(window).width()>640)
//     var timerId = setInterval(function () {
//         var result = popupMarketing('popup_closetab_content', '/bitrix/templates/general/components/custom/session_check.php', 'popup-window-content-Callback');
//         if (result) clearInterval(timerId);
//     }, 300000);
//
//     $(document).mouseleave(function (e) {
//         if (e.pageY - $(window).scrollTop() <= 1)
//             popupMarketing('popup_closetab_content', '/bitrix/templates/general/components/custom/basket_check.php', 'popup-window-content-Basket');
//     });
//
// });
