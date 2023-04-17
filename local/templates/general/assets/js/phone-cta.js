$(document).ready(function () {
    (function () {
    'use strict';

    var ZV = (function () {
        var $document = $(document),
            $window = $(window),
            $html = $document.find('html'),
            $CTA = $html.find('.btn-zv'),
            $timeField = $html.find('.f-default__field._time');
        /*@cc_on!@*/false || !!document.documentMode && document.querySelector('html').classList.add('_ie');
        navigator.userAgent.indexOf('MSIE 9.') > -1 && $html.addClass('_ie9');
        (navigator.appVersion.indexOf('Mac') !== -1 && navigator.appVersion.indexOf('Version/10') !== -1) && $html.addClass('_safari');

        function updateCTAPosition() {
        $CTA.css('top', $window.scrollTop() + window.innerHeight - 200);
        //   console.log($CTA.innerHTML);
        }

        function bindEvents() {
        $window
            .on('scroll.updateCTAPosition', function () {
                updateCTAPosition();
            });
        $document
            .on('click.openCTAModal', '.btn-zv', function () {
                $html.addClass('_open-cta-modal');
            })
            .on('click.closeCTAModalByCloseButton', '.modal-cta__close', function () {
                $html.removeClass('_open-cta-modal');                                 
            })
            .on('click.closeCTAModal', '.modal-cta', function (e) {
                if (e.target === this) {
                $html.removeClass('_open-cta-modal');
                }
            })
            .on('click.changeForm', '.modal-cta__change-form', function (e) {
                e.preventDefault();
                $('.modal-cta__block._active').removeClass('_active').siblings('.modal-cta__block').addClass('_active');
            });
        }

        function initMask() {
        $("._country").mask("+999 (99) 999-9999");
        $timeField.mask('99:99');
        $document
            .on('blur.validateTime', '.f-default__field._time', function () {
                var currentValue = $(this).val().split('');
                currentValue[0] = +currentValue[0];
                currentValue[1] = +currentValue[1];
                currentValue[3] = +currentValue[3];
                if (currentValue[0] > 2) {
                currentValue[0] = 2;
                }
                if (currentValue[0] === 2) {
                if (currentValue[1] > 3) {
                    currentValue[1] = 3;
                }
                }
                if (currentValue[3] > 5) {
                currentValue[3] = 5;
                }
                $(this).val(currentValue.join(''));
            })
        }

        return {
        init: function () {
            initMask();
            bindEvents();
            updateCTAPosition();
        }
        };
    }());

    $(document).ready(function () {

        ZV.init();
    });

    }());
    
    (function () {
    'use strict';

    var ZV2 = (function () {
        var $document = $(document),
            $window = $(window),
            $html = $document.find('html'),
            $CTA = $html.find('.header-text-item__callback'),
            $timeField = $html.find('.f-default__field._time');
        /*@cc_on!@*/false || !!document.documentMode && document.querySelector('html').classList.add('_ie');
        navigator.userAgent.indexOf('MSIE 9.') > -1 && $html.addClass('_ie9');
        (navigator.appVersion.indexOf('Mac') !== -1 && navigator.appVersion.indexOf('Version/10') !== -1) && $html.addClass('_safari');

        function updateCTAPosition() {
        //$CTA.css('top', $window.scrollTop() + window.innerHeight - 200);
        //   console.log($CTA.innerHTML);
        }

        function bindEvents() { 
            $window
            .on('scroll.updateCTAPosition', function () {
                updateCTAPosition();
            });
        $document
            // .on('click.openCTAModal', '.header-text-item__callback', function () {
            //     $html.addClass('_open-cta-modal');
            //     Basket.close();
            //     Callback.close();
            //     Consult.close();
            // })
            .on('click.closeCTAModalByCloseButton', '.modal-cta2__close', function () {
                $html.removeClass('_open-cta-modal');
            })
            .on('click.closeCTAModal', '.modal-cta2', function (e) {
                if (e.target === this) {
                $html.removeClass('_open-cta-modal');
                }
            })
            .on('click.changeForm', '.modal-cta2__change-form', function (e) {
                e.preventDefault();
                $('.modal-cta2__block._active').removeClass('_active').siblings('.modal-cta2__block').addClass('_active');
            });
        }

        function initMask() {
        $("._country").mask("+999 (99) 999-9999");
        $timeField.mask('99:99');
        $document
            .on('blur.validateTime', '.f-default__field._time', function () {
                var currentValue = $(this).val().split('');
                currentValue[0] = +currentValue[0];
                currentValue[1] = +currentValue[1];
                currentValue[3] = +currentValue[3];
                if (currentValue[0] > 2) {
                currentValue[0] = 2;
                }
                if (currentValue[0] === 2) {
                if (currentValue[1] > 3) {
                    currentValue[1] = 3;
                }
                }
                if (currentValue[3] > 5) {
                currentValue[3] = 5;
                }
                $(this).val(currentValue.join(''));
            })
        }

        return {
        init: function () {
            initMask();
            bindEvents();
            updateCTAPosition();
        }
        };
    }());

    $(document).ready(function () {

        ZV2.init();
    });

    }());

    // Добавление параметров к формам
    // Контакты
    //var form3 = $('form[name=SIMPLE_FORM_3');
    //form3.attr("onsubmit", "yaCounter18399610.reachGoal('contacts'); return true;" );
    // Обратный звонок
    //var formCallOne = $('.f-later.f-default');
    //formCallOne.attr("onsubmit", "_gaq.push(['_trackEvent', 'form','call_me']); yaCounter18399610.reachGoal('call_me'); return true;" );
    //var formCallTwo = $('.f-now.f-default');
    //formCallTwo.attr("onsubmit", "_gaq.push(['_trackEvent', 'form','call_me']); yaCounter18399610.reachGoal('call_me'); return true;" );
    // Заказать замер
    //var form1 = $('form[name=SIMPLE_FORM_1');
    //form1.attr("onsubmit", "yaCounter18399610.reachGoal('measurement'); return true;" );
    // Оставить отзыв
    //var form2 = $('form[name=SIMPLE_FORM_2');
    //form2.attr("onsubmit", "yaCounter18399610.reachGoal('consultation'); return true;" );

});
