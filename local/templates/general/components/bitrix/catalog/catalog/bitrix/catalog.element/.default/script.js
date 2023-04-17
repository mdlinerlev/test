$(document).ready(function () {
    $("button.door-to-cart").on("click", function () {
        var id = $(".detail-product").attr("data-product-id");
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
            var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_basketadd_ru-"+ id +"&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
            ifr.setAttribute("src", source);
            ifr.setAttribute("width", "1");
            ifr.setAttribute("height", "1");
            ifr.setAttribute("scrolling", "no");
            ifr.setAttribute("frameBorder", "0");
            ifr.setAttribute("style", "display:none");
            ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
            body.appendChild(ifr);
        }());

    });

    $('.sku-value').on( "mouseup", function() {
        deleteDescText();
        setDetailPrices(true);
        $('.dinamic-hit').css("display", "none");
        var offer = $(this).data("propOffer");
        $('.product-top__badge-container').find('.catalog-item__label[data-dinamic-hit="'+ offer +'"]').css("display", "table");
    });
});



var twoLeaf = false,
    secondOfferPrice = 0,
    secondOffer = false,
    countClickColor = 0;

// открываем запрошенное тп первым
window.selectCurrentOffer = function () {
    var currentOfferProps = false,
        $materialSelect = $('#product-size'),
        jsCurrentOffer = location.hash.replace('#offer', ''),
        i, j, propId;
    //console.log(jsCurrentOffer);
    if (jsCurrentOffer && window.jsOffers) {

        $('.detail-product').addClass();

        for (i in jsOffers) {
            if (!jsOffers.hasOwnProperty(i)) {
                continue;
            }
            if (jsOffers[i].hasOwnProperty(jsCurrentOffer)) {
                currentOfferProps = jsOffers[i][jsCurrentOffer];
            }
        }
    }
    //console.log(currentOfferProps);
    if (currentOfferProps) {
        // меняем, если надо, размер
        /*for(j = 0; j < currentOfferProps.length; j++) {
            propId = currentOfferProps[j];
            if($materialSelect.val() != propId && $materialSelect.find('option[value="'+propId+'"]').length) {
                $materialSelect.val(propId).trigger('change').selectmenu('refresh');
                break;
            }
        }*/
        // ищем нужный цвет
        $('.detail-product').addClass('js_get_init');
        for (j = 0; j < currentOfferProps.length; j++) {
            propId = currentOfferProps[j];
            var $citem = $('.sku-value[data-id="' + propId + '"]').eq(0);
            if ($citem.length) {
                $citem.trigger('click');
            }
        }
        $('.detail-product').removeClass('js_get_init');
    }
};

// тп
(function () {
    // constructor
    var BitrixOffers = function ($item, options) {
        var defaults = {
                activeClass: 'current',
                hiddenClass: 'hidden',
                propWrapperSelector: '.select-container',
                propValueSelector: '.sku-container li'
            },
            that = this;
        this.$item = $item;
        this.$hover = $item.find('.catalog-item-hover');
        this.options = $.extend(defaults, options || {});

        $item.find(this.options.propValueSelector).on('click', function () {
            $(this).closest(that.options.propWrapperSelector).find(that.options.propValueSelector).removeClass(that.options.activeClass);

            $(this).addClass(that.options.activeClass);
            that.changeOfferPoints();
            var purl = window.location.href;
            var exp = /vkhodnye_dveri/;
            var regex = new RegExp(exp);
            if (purl.match(regex)) {
                if ($(this).hasClass("product-filter-color__link") && countClickColor > 1) {
                    if ($(this).parent().parent().parent().hasClass("product-filter__color--second") && !$(this).parent().parent().parent().hasClass("filter-glass-color")) {


                        if ($('.product-view-links__inner').children().eq(0).hasClass('active')) {
                            $('.product-view-links__inner').children().eq(1).trigger('click');
                        } else {
                            $('.product-view-links__inner').children().eq(3).trigger('click');
                        }

                    } else if ($(this).hasClass("product-filter-color__link") && !$(this).parent().parent().parent().hasClass("product-filter__color--second") && !$(this).parent().parent().parent().hasClass("filter-glass-color")) {


                        if ($('.product-view-links__inner').children().eq(1).hasClass('active')) {
                            $('.product-view-links__inner').children().eq(0).trigger('click');
                        } else {
                            $('.product-view-links__inner').children().eq(2).trigger('click');
                        }
                    }
                } else if ($(this).hasClass("product-filter-color__link")) {

                    countClickColor++;
                }
            }
        });

        this.changeOfferPoints(true);
        BX.addCustomEvent('onFrameDataReceived', function () {
            that.changeOfferPoints(true);
        });

        $('.second-base select').on('selectmenuchange', $.proxy(this.getSecondOfferPrice, this));
    };

    BitrixOffers.prototype = {
        checkOfferExists: function (props) {
            //console.log(props);
            var productId = this.$item.data('productId'),
                exists, offerId, i;
            for (offerId in jsOffers[productId]) {
                exists = true;
                for (i = 0; i < props.length; i++) {
                    if (jsOffers[productId][offerId].indexOf(props[i]) == -1) {
                        exists = false;
                        break;
                    }
                }
                if (exists) {
                    return offerId;
                }
            }
            return false;
        },
        changeOfferPoints: function (first) {
            var productId = this.$item.data('productId');
            var currentProps = [],
                propLevel = 0,
                that = this,
                $availItem;
            first = (typeof first !== 'undefined') && first;
            this.$item.add(this.$hover).find(this.options.propValueSelector).removeClass(this.options.hiddenClass);

            this.$item.add(this.$hover).find(this.options.propWrapperSelector).each(function () {
                var $points = $(this).find(that.options.propValueSelector),
                    $activePoint,
                    $visiblePoint,
                    $firstPoint;
                propLevel++;

                if (propLevel == 1) {
                    $activePoint = $points.filter('.' + that.options.activeClass).eq(0);
                    $firstPoint = $activePoint.length ? $activePoint : $points.eq(0);
                    $firstPoint.addClass(that.options.activeClass);
                    currentProps.push($firstPoint.data('id'));
                } else {
                    $points.each(function () {
                        currentProps.push($(this).data('id'));
                        if (!that.checkOfferExists(currentProps)) {
                            $(this).addClass(that.options.hiddenClass).removeClass(that.options.activeClass);
                        }
                        currentProps.pop();
                    });
                    $activePoint = $points.filter('.' + that.options.activeClass).eq(0);

                    $visiblePoint = $activePoint.length ? $activePoint : $points.not('.' + that.options.hiddenClass).eq(0);
                    $visiblePoint.addClass(that.options.activeClass);
                    currentProps.push($visiblePoint.data('id'));
                }
            });
            if (currentProps.length) {
                var offerId = this.checkOfferExists(currentProps),
                    $offerBlocks = this.$item.add(this.$hover).find('[data-offer-id]');
                $offerBlocks.hide();
                $offerBlocks.removeClass('offers-show');
                $offerBlocks.filter('[data-offer-id="' + offerId + '"]').css('visibility', 'visible').css('display', 'inline-block').addClass('offers-show');//.show();

                $('.product_order').find('.one-click-buy').data('product_id', offerId);


                $('.product-filter-price-tabs__tab-wrap').each(function () {
                    if ($(this).find('.product-filter-complect__inner').filter('[data-offer-id="' + offerId + '"]').data('show') == 1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });


                if (first/* && productId == 35419*/) {
                    $availItem = $offerBlocks.filter('[data-available="1"]').eq(0);
                    if ($availItem.length) {
                        var availOfferId = $availItem.data('offerId');
                        for (offerId in jsOffers[productId]) {
                            if (jsOffers[productId].hasOwnProperty(offerId) && offerId == availOfferId) {
                                var availProps = jsOffers[productId][offerId];
                                for (var i = 0; i < availProps.length; i++) {
                                    // RU 29092016 todo временно закомментированно
                                    //this.$item.find(this.options.propValueSelector).filter('[data-id="'+availProps[i]+'"]').trigger('click');
                                }
                                break;
                            }
                        }
                    }
                } else {
                    $(document).trigger('bitrix:offers:change', [+offerId]);
                }
            }

            // цена для второго полотна
            secondOfferPrice = 0;
            if (twoLeaf) {
                var $secondSelect = $('.second-base select');
                var secondBaseVal = $secondSelect.val();
                var options = $('.first-base select').html();
                $secondSelect.html(options);
                if (secondBaseVal && $secondSelect.find('option[value="' + secondBaseVal + '"]').length) {
                    $secondSelect.val(secondBaseVal);
                } else {
                    $secondSelect.val($secondSelect.find('option').eq(0).attr('value'));
                }

                if ($secondSelect.length && typeof selectmenu !== "undefined") {
                    $secondSelect.selectmenu('refresh');
                }

                this.getSecondOfferPrice();
            }
        },
        getSecondOfferPrice: function () {
            secondOfferPrice = 0;
            var propIds = [],
                colorId,
                $secondSelect = $('.second-base select');
            propIds.push(parseInt($secondSelect.val()));

            $('.product-filter-color__inner').each(function () {
                colorId = $(this).find('a.active').data('id');
                if (colorId) {
                    propIds.push(parseInt(colorId));
                }
            });
            var secondOfferId = this.checkOfferExists(propIds);
            if (secondOfferId) {
                secondOfferPrice = jsPrices[secondOfferId];
                secondOffer = secondOfferId;
            }
            //console.log(secondOfferPrice);
            setDetailPrices();
        }
    };

    // plugin
    $.fn.extend({
        bitrixOffers: function (options) {
            $(this).each(function () {
                var obj = new BitrixOffers($(this), options);
                $(this).data('bitrixOffers', obj);
            });
        }
    });
})();

var numberFormat = function (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
};

if (window.localStorage) {
    localStorage.removeItem('detailState');
}

var saveCurrentState = function () {
    var state = {};
    state.color = $('.filter-main-color .sku-value.active').data('id');
    state.size = $('.filter-size-canvas .sku-value.active').data('id');
    state.glassColor = $('.filter-glass-color .sku-value.active').data('id');
    state.qty = $('#total-quantity-input').val();

    if (window.localStorage) {
        localStorage.setItem('detailState', JSON.stringify(state));
    }
};

var setDetailState = function () {
    if (window.localStorage) {
        var state = localStorage.getItem('detailState');
        state = JSON.parse(state);
        if (!state) {
            return;
        }
        if (state.color) {
            $('.filter-main-color .sku-value[data-id="' + state.color + '"]').trigger('click');
        }
        if (state.size) {
            $('.filter-size-canvas .sku-value[data-id="' + state.size + '"]').trigger('click');
        }
        if (state.glassColor) {
            $('.filter-glass-color .sku-value[data-id="' + state.glassColor + '"]').trigger('click');
        }
        if (state.size) {
            $('#product-size').val(state.size).selectmenu('refresh');
        }
        if (state.qty) {
            $('#total-quantity-input').val(state.qty).trigger('change');
        }
    }
};

$(document).on('bitrix:offers:change', saveCurrentState);

var deleteDescText = function () {
    $('.product-filter-price-tabs__title.two').each(function(indx){
        $(this).remove();
    });
}

var setDescEmalComplect = function () {
    $('.product-filter-price-tabs__title:contains("Комплект компланар")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта компланар для полотна заказная эмаль</div>');
    $('.product-filter-price-tabs__title:contains("Комплект телескоп")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта телескоп для полотна заказная эмаль</div>');
}
var setDescEmalSizeComplect = function () {
    $('.product-filter-price-tabs__title:contains("Комплект компланар")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта компланар для полотна нестандартного размера + заказная эмаль</div>');
    $('.product-filter-price-tabs__title:contains("Комплект телескоп")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта телескоп для полотна нестандартного размера + заказная эмаль</div>');
}
var setDescSizeComplect = function () {
    $('.product-filter-price-tabs__title:contains("Комплект компланар")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта компланар для полотна нестандартного размера</div>');
    $('.product-filter-price-tabs__title:contains("Комплект телескоп")').after('<div class="product-filter-price-tabs__title two">Стоимость комплекта телескоп для полотна нестандартного размера</div>');
}

var setDetailPrices = function (isProductCounter, proc = 0) {
    var height = $('#heigth-door')[0];
    var color = $('#test-razmer')[0];

    deleteDescText();
    if(height) {
        if (Number(height.value) >= 2050 && Number(height.value) <= 2300) {
            proc = 30;
            deleteDescText();
            setDescSizeComplect();
        } else {
            if (Number(height.value) >= 2350 && Number(height.value) <= 2400 ) {
                proc = 100;
                deleteDescText();
                setDescSizeComplect();
            } else {
                proc = 0;
            }
        }
    }

    if(color) {
        if (Number(color.value) > 0) {
            proc = 30;
            deleteDescText();
            setDescEmalComplect();
        }
    }
    if(height && color) {
        if (Number(height.value) >= 1800 && Number(height.value) <= 2400 && Number(color.value) > 0) {
            proc = 50;
            deleteDescText();
            setDescEmalSizeComplect();
        }
    }

    setTimeout(function (){
        if($('.sku-value.product-filter-color__link.active .tooltiptext').text() == 'Эмаль заказной' && $('.sku-value.select-sku-value.size-value.active').text() != 'нестандарт') {
            deleteDescText();
            setDescEmalComplect();
        }
    }, 200);


    $('.detail-to-cart').each(function () {
        $(this).data('proc', proc);
    });

    var isProductCounter = typeof isProductCounter !== 'undefined';

    /*if (twoLeaf) {
        var totalCount = parseInt($('#total-quantity-input').val()) * 2,
            isComplect = $('.detail-price-complect').is('.active');
    } else {
        var totalCount = parseInt($('#total-quantity-input').val()),
            isComplect = $('.detail-price-complect').is('.active');
    }*/
    var totalCount = parseInt($('#total-quantity-input').val()),
        isComplect = $('.detail-price-complect').is('.active');

    //cart = [];

    $('.detail-to-cart').data('cart', false);
    //console.log('clear cart');

    /*if (twoLeaf) {
        $('.simple-quantity').text(totalCount);
    } else {
        $('.simple-quantity').text(totalCount);
    }*/
    $('.simple-quantity').text(totalCount);

    // блок за полотно
    $('.left-base-price').each(function () {
        var self = $(this);
        var self_offer_id = self.closest('[data-offer-id]').data('offer-id');
        var price = parseFloat(self.parent().data('basePrice').toString().replace(' ', ''));
        //price = price + price * proc / 100;
        if (secondOfferPrice) {
            price += secondOfferPrice;
        }

        if (!isNaN(price)) {
            /*if (twoLeaf) {
                $(this).text(numberFormat(price * 2, 2, '.', ' ') + ' ' + currency_text);
            } else {
                $(this).text(numberFormat(price, 2, '.', ' ') + ' ' + currency_text);
            }*/
            $(this).text(numberFormat(price, 2, '.', ' ') + ' ' + currency_text);
        }

        // установка цены за полотно в зависимости от выбранного количества
        var calc_formated_price = numberFormat((price * totalCount).toFixed(2), 2, '.', ' ') + ' ' + currency_text;
        self.text(calc_formated_price);
        if (self_offer_id){
            $('.product-filter-complect__cell--price [data-offer-id="' + self_offer_id + '"]').text(calc_formated_price);
        }
    });



    $('.left-kbase-price').each(function () {
        var price = parseFloat($(this).data('kbasePrice'));
        if (secondOfferPrice) {
            price += secondOfferPrice * 10000;
        }
        if (!isNaN(price)) {
            $(this).text('(' + numberFormat(price, 2, '.', ' ') + ' ' + currency_text + ')');
        }
    });

    $('.complect-count-input').each(function() {
        var ct_price = parseFloat($(this).data('price').toString().replace(' ', '')),
            count = parseInt($(this).val()),
            defval = $(this).data('defval'),
            id = parseInt($(this).data('id')),
            complect_item_calc_formated_price;
        ct_price = ct_price + ct_price * proc / 100;
        if (isProductCounter) {
            count = defval * totalCount;
            $(this).val(count);
        }

        complect_item_calc_formated_price = numberFormat((ct_price * count).toFixed(2), 2, '.', ' ') + ' ' + currency_text;

        var complectItemRow = $(this).closest('.product-filter-complect__row'),
            complectItemPriceCell = complectItemRow.find('.product-filter-complect__cell--price:first');

        complectItemPriceCell.text(complect_item_calc_formated_price);

        /*if(isComplect) {
            cart.push([id, count]);
        }*/
    });

    // расчет итоговой цены за комплект
    var activeComplectFullPrice = $('.active .complect-full-price')
    var complectFullPrice = activeComplectFullPrice.length ? activeComplectFullPrice : $('.complect-full-price')
    if (complectFullPrice.length > 0) {
        complectFullPrice.each(function () {
            var complectParent = $(this)
            var complectPrice = 0,
                cart = [],
                basePrice = complectParent.data('basePrice'),
                offerId = complectParent.closest('[data-offer-id]').data('offerId')
            //basePrice = basePrice + basePrice * proc / 100;
            var baseComplect = complectParent.closest('.product-filter-price-tabs__tab-wrap');
            // итоговая цена товара на панели
            var $totalPrice = $('[data-offer-id="' + offerId + '"] .total-price');
            if (!$totalPrice.length) {
                $totalPrice = $('.total-price');
            }

            // итоговая старая цена товара на панели
            var $totalPriceOld = $('[data-offer-id="' + offerId + '"] .total-price_old');
            if (!$totalPriceOld.length) {
                $totalPriceOld = $('.product-filter-submit__price--old');
            }
            var oldPrice = $totalPriceOld.data('old-price');

            // поля с количеством товаров входящих в комплект
            var inputsActiveComplect = $('.active-complect [data-offer-id="' + offerId + '"] .complect-count-input')
            var inputsComplect = baseComplect.find('[data-offer-id="' + offerId + '"] .complect-count-input')
            var finalInputs = inputsActiveComplect.length ? inputsActiveComplect : inputsComplect;

            finalInputs.each(function () {
                var inputProductComplect = $(this);
                var price = parseFloat(inputProductComplect.data('price')),
                    count = parseInt(inputProductComplect.val()),
                    id = parseInt(inputProductComplect.data('id'));
                price = price + price * proc / 100;
                complectPrice += price * count;

                if (isComplect) {
                    cart.push([id, count, price, 111]);
                }
            });


            // подсчет и установка итоговой суммы комплекта
            if (isComplect) {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' '));
            } else {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * totalCount + secondOfferPrice * totalCount, 2, '.', ' '));
            }

            // установка цены на комплекте
            complectParent.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' ') + ' ' + currency_text);
            $('.detail-to-cart[data-offer-id="' + offerId + '"]').data('cart', cart);
        });
    } else {
        $('.detail-price-base .offers-show .product-filter-price-tabs__discount').each(function () {
            var complectPrice = 0,
                cart = [],
                basePrice = $(this).data('basePrice'),
                offerId = $(this).closest('[data-offer-id]').data('offerId'),
                $totalPrice = $('[data-offer-id="' + offerId + '"] .total-price');
            basePrice = basePrice + basePrice * proc / 100;
            if (!$totalPrice.length) {
                $totalPrice = $('.total-price');
            }

            var $totalPriceOld = $('[data-offer-id="' + offerId + '"] .total-price_old');
            if (!$totalPriceOld.length) {
                $totalPriceOld = $('.product-filter-submit__price--old');
            }
            var oldPrice = $totalPriceOld.data('old-price');


            $('[data-offer-id="' + offerId + '"] .complect-count-input').each(function () {
                var price = parseFloat($(this).data('price')),
                    count = parseInt($(this).val()),
                    id = parseInt($(this).data('id'));
                complectPrice += price * count;

                if (isComplect) {
                    cart.push([id, count,$(this).data('basePrice'), basePrice, 222]);
                }
            });

            /*console.log(basePrice*totalCount);
            console.log(secondOfferPrice*totalCount);
            console.log(complectPrice);
            console.log('#################');*/


            if (isComplect) {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' '));
            } else {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * totalCount + secondOfferPrice * totalCount, 2, '.', ' '));
            }

            $('.detail-to-cart[data-offer-id="' + offerId + '"]').data('cart', cart);
        });


    }


    if ($('.complect-full-price_old').length > 0) {
        $('.complect-full-price_old').each(function () {
            var complectPrice = 0,
                cart = [],
                basePrice = $(this).data('basePrice'),
                offerId = $(this).closest('[data-offer-id]').data('offerId'),
                $totalPrice = $('[data-offer-id="' + offerId + '"] .total-price');
            basePrice = basePrice + basePrice * proc / 100;
            if (!$totalPrice.length) {
                $totalPrice = $('.product-filter-submit__price--new');
            }
            var $totalPriceOld = $('[data-offer-id="' + offerId + '"] .total-price_old');
            if (!$totalPriceOld.length) {
                $totalPriceOld = $('.product-filter-submit__price--old');
            }

            var oldPrice = $totalPriceOld.data('old-price');

            $('[data-offer-id="' + offerId + '"] .complect-count-input').each(function () {
                var price = parseFloat($(this).data('price')),
                    count = parseInt($(this).val()),
                    id = parseInt($(this).data('id'));
                complectPrice += price * count;

                if (isComplect) {
                    cart.push([id, count]);
                }
            });

            if (isComplect) {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount + complectPrice, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * 10000 * totalCount + secondOfferPrice * 10000 * totalCount + complectPrice * 10000, 0, '.', ' '));
                //console.log('1');
            } else {
                $totalPrice.text(numberFormat(basePrice * totalCount + secondOfferPrice * totalCount, 2, '.', ' '));
                if (oldPrice)
                    $totalPriceOld.text(numberFormat(oldPrice * 10000 * totalCount + secondOfferPrice * 10000 * totalCount, 0, '.', ' '));
                //console.log('2');
            }

            $(this).text('(' + (numberFormat(basePrice * 10000 * totalCount + complectPrice * 10000, 0, '.', ' ')) + ' б.р.)');
            $('.detail-to-cart[data-offer-id="' + offerId + '"]').data('cart', cart);
        });
    } else {

        // var complectPrice = 0,
        //     cart = [],
        //     offerId = $('.detail-price-base').find('.offers-show').data('offer-id'),
        //     basePrice = $('[data-offer-id="' + offerId + '"] .product-filter-price-tabs__discount').data('base-price'),
        //     $totalPrice = $('[data-offer-id="' + offerId + '"] .total-price');
        // if (!$totalPrice.length) {
        //     $totalPrice = $('.product-filter-submit__price--old');
        // }
        //
        // $totalPrice.text(numberFormat(basePrice * totalCount, 2, '.', ' '));
        //
        // $(this).text('(' + (numberFormat(basePrice * 10000 * totalCount, 0, '.', ' ')) + ' б.р.)');
        // $('.detail-to-cart[data-offer-id="' + offerId + '"]').data('cart', cart);
        //


    }

    // проставляем товары для корзины
    $('.detail-to-cart').each(function () {
        var baseId = parseInt($(this).data('id')),
            basePrice = $('.product-filter-price-tabs__tab.active .offers-show .product-filter-price-tabs__price').data('basePrice'),
            //thisCart = cart.slice(0),
            thisCart = $(this).data('cart') || [],
            localCart = [baseId, totalCount];
        //localCart = [baseId, totalCount, basePrice + basePrice * proc / 100, 9999999999999999];
        basePrice = basePrice + basePrice * proc / 100;
        /*if( $('.side-type').length ) {
            var side = $('.side-type.product-filter__type--right').is('.active') ? 'Правая' : 'Левая';
            localCart.push({
                NAME: 'Сторона открывания',
                CODE: 'SIDE',
                VALUE: side,
                SORT: 100
            });
        }*/
        thisCart.unshift(localCart);

        if (secondOffer && secondOfferPrice) {
            thisCart.push([secondOffer, totalCount]);
        }
        $(this).data('cart', thisCart);
    });

    //saveCurrentState();
};

var initDetailProduct = function () {
    var stop = false;

    $('.detail-product .product-filter__select--size').selectmenu();

    app.quantity.initScripts();
    app.product.initScripts();
    app.itemsSlider.slidersInit();

    /*$('.side-type').on('click', function() {
        setTimeout(setDetailPrices, 200);
    });*/

    $('.total-square').on('change', function () {
        var val = $(this).val(),
            defaultVal = parseFloat($(this).data('square')),
            boxes = 1,
            offerId = $(this).closest('[data-offer-id]').data('offerId');

        if (defaultVal > 0 && val > 0) {
            val = parseFloat(val.replace(/\s/g, '').replace(',', '.'));
            boxes = Math.ceil(val / defaultVal);
        }

        $('#total-quantity-input').val(boxes).trigger('change');
    });

    $('.product-filter-color__link').on('click', function () {
        var inner = $(this).data('inner'),
            $innerImage = $('.inner-image');

        $('#test-razmer').val('');
        $('.checkbox-color').removeClass('has-checked');
        $('.checkbox-group').removeClass('has-checked');
        if($(this).data('ral') == 1) {
            $('.color-ral').removeClass('hidden');
            $('#test-razmer').prop('required', true);
        } else {
            $('.color-ral').addClass('hidden');
            $('#test-razmer').prop('required', false);

        }

        if ($('.detail-product').hasClass('js_get_init')) {
            return;
        }


        if (inner && $innerImage.length) {
            $('.one-leaf-image, .two-leaf-image').addClass('hidden');
            $innerImage.removeClass('hidden');
        } else {
            /*if (twoLeaf) {
                $('.two-leaf-image').removeClass('hidden');
            } else {
                $('.one-leaf-image').removeClass('hidden');
            }*/
            $('.one-leaf-image').removeClass('hidden');
            $innerImage.addClass('hidden');
        }

        if (!$(".two-leaf-image").hasClass("hidden") && !$(".one-leaf-image").hasClass("hidden")) {
            if ($(".product-top__button--single").hasClass("active")) {
                $(".two-leaf-image").addClass("hidden");
            } else if ($(".product-top__button--double").hasClass("active")) {
                $(".one-leaf-image").addClass("hidden");
            }
        }
    });

    $('.product-top__compare').on('click', function () {
        if (!$(this).is('.active')) {
            var that = this,
                href = $(this).attr('href');
            $.post(href, {}, function () {
                $(that)
                    .attr('href', '/catalog/compare/')
                    .addClass('active');
                $(that).find('span')
                    .removeClass('product-top-compare__text--default')
                    .addClass('product-top-compare__text--active')
                    .text('В сравнении');
            });
            return false;
        }
    });
    $('.catalog-item-compare').on('click', function () {
        if ($(this).is('.active')) {
            var that = this,
                href = $(this).attr('href');
            $.post(href, {}, function () {
                $(that)
                    .attr('href', '/catalog/compare/')
                    .addClass('active');
                $(that).find('a')
                    .removeClass('catalog-item-compare__text--default')
                    .addClass('catalog-item-compare__text--active')
                    .text('В сравнении');
            });
            return false;
        }
    });

    $('.select-sku-value').on('click', function () {
        if (stop) {
            stop = false;
            return;
        }

        deleteDescText();
        console.log($(this).data('ral'));

        if($(this).data('size') == 1) {
            $('.select-size').removeClass('hidden');
            $('#heigth-door').prop('required', true);
            $('#width-door').prop('required', true);
            $('#width-door').val('');
            $('#heigth-door').val('');
        } else {
            $('.select-size').addClass('hidden');
            $('#heigth-door').prop('required', false);
            $('#width-door').prop('required', false);
            $('#width-door').val('');
            $('#heigth-door').val('');
        }
        setDetailPrices(true, 0);

        var $select = $(this).closest('.sku-wrapper').find('select'),
            $options = $select.find('option'),
            id = $(this).data('id'),
            that = this;

        setTimeout(function () {
            $(that).closest('.sku-wrapper').find('.select-sku-value').each(function () {
                if ($(this).is('.hidden')) {
                    $options.filter('[value="' + $(this).data('id') + '"]').attr('disabled', true);
                }
            });
            if ($select.length && typeof selectmenu !== "undefined") {
                $select.val(id).selectmenu('refresh');
            }
        }, 300);
    });

    $('.sku-wrapper select').on('selectmenuchange', function () {
        var ssize = $('#product-size').val();
        $('.select-sku-value').data('id', ssize);
        stop = true;
        $(this).closest('.sku-wrapper').find('.select-sku-value').filter('[data-id="' + $(this).val() + '"]').trigger('click');
    });

    setDetailState();

    $('.detail-product').bitrixOffers({
        activeClass: 'active',
        propWrapperSelector: '.sku-wrapper',
        propValueSelector: '.sku-value'
    });

	$('.dinamic-hit').css("display", "none");
	$('.sku-value.active').each(function( index ) {
		var offer = $(this).data("propOffer");
		$('.product-top__badge-container').find('.catalog-item__label[data-dinamic-hit="'+ offer +'"]').css("display", "table");
		return false;
	});

    /*$('.glass-type').not('.active').on('click', function () {
        var ajaxId = $(this).closest('[id^="comp_"]').attr('id'),
            href = $(this).attr('href');
        // BX.ajax.insertToNode(href + '?bxajaxid=' + ajaxId.replace('comp_', ''), ajaxId);

        $.get(href + '?bxajaxid=' + ajaxId.replace('comp_', ''))
            .done(function (response) {
                // обновление разметки во всем товаре (.content-container)
                $('#' + ajaxId).html(response)

                // инициализация события аякса на битриксе
                BX.onCustomEvent('onAjaxSuccess');

                // обработка новой разметки
                var ssize = $('#product-size').val();
                $('.select-sku-value').data('id', ssize);
                $('.detail-product').data('bitrixOffers').changeOfferPoints();

                // фикс бага (видимы две версии дверей) - скрытие двойной двери
                $('.product-preview__door-image.two-leaf-image').addClass('hidden')
                console.log("переключили");
                toggleFade();
            })
            .fail(function (response) {
                console.warn('glass-type: ajax load failed', response)
            })


        setTimeout(function () {
            // var ssize = $('#product-size').val();
            // $('.select-sku-value').data('id',ssize);
            // $('.detail-product').data('bitrixOffers').changeOfferPoints();
        }, 3000);

        return false;
    });*/

    /*$('.product-top__button--type').on('click', function () {
        if ($(this).is('.active')) {
            return;
        }
        $('.product-top__button--type').removeClass('active');
        $(this).addClass('active');
        twoLeaf = !$(this).is('.product-top__button--single');

        if (twoLeaf) {
            $('.two-leaf-image, .second-base, .two-leaf').removeClass('hidden');
            $('.one-leaf-image, .one-leaf').addClass('hidden');
            $('.leaf-switch-text').each(function () {
                $(this).text($(this).data('twoleafText'));
            });
        } else {
            $('.two-leaf-image, .second-base, .two-leaf').addClass('hidden');
            $('.one-leaf-image, .one-leaf').removeClass('hidden');
            $('.leaf-switch-text').each(function () {
                $(this).text($(this).data('oneleafText'));
            });
        }
        $('.detail-product').data('bitrixOffers').changeOfferPoints();
        $('#total-quantity-input').trigger('change');

        return false;
    });*/


    $('.complect-count-input').on('change', function () {
        setDetailPrices();
    });

    $('#total-quantity-input').on('change', function () {
        var val = parseInt($(this).val()),
            //map = twoLeaf ? window.complectMapTwoLeaf : window.complectMap;
            //map = twoLeaf ? window.getComplectMapTwoLeaf : window.getComplectMap;
            map = window.getComplectMap;

        if (!val || val === 0) { $(this).val(1) }
        saveCurrentState();

        if (map) {
            //map = map[val];
            map = map(val);
            for (var key in map) {
                if (map.hasOwnProperty(key)) {
                    $('input[data-code="' + key + '"]').val(map[key] === false ? 0 : map[key]);
                    if (map[key] === false) {
                        $('input[data-code="' + key + '"]').closest('.product-filter-complect__row').addClass('hidden');
                    } else {
                        $('input[data-code="' + key + '"]').closest('.product-filter-complect__row').removeClass('hidden');
                    }
                }
            }
        }


        //map = twoLeaf ? window.getComplectMapTwoLeaf2 : window.getComplectMap2;
        map = window.getComplectMap2;
        if (map) {
            //map = map[val];
            map = map(val);
            for (var key in map) {
                if (map.hasOwnProperty(key)) {
                    $('input[data-code="' + key + '"]').val(map[key] === false ? 0 : map[key]);
                    if (map[key] === false) {
                        $('input[data-code="' + key + '"]').closest('.product-filter-complect__row').addClass('hidden');
                    } else {
                        $('input[data-code="' + key + '"]').closest('.product-filter-complect__row').removeClass('hidden');
                    }
                }
            }
        }


        setDetailPrices(true);

        var $ts = $('.total-square');
        if ($ts.length) {
            $ts.val((val * parseFloat($ts.data('square'))).toFixed(3).replace('.', ','));
        }
    });
    setDetailPrices();

    $('html').append('<style>.notify._top-right{position:fixed; top: 30px; right: 30px; padding: 30px; border-radius: 5px; box-shadow: 0 0 10px 0 rgba(0,0,0,.3); font-size: 17px; background: #fff; font-family: Arial, helvetica, sans-serif}</style>');

    $('.detail-to-cart').on('click', function () {
        if (!$(this).is('.detail-to-cart')) {
            return true;
        }
        var cart = $(this).data('cart'),
            that = this;
        if (!cart.length) {
            return false;
        }

        var heigth = document.getElementById('heigth-door');
        var width = document.getElementById('width-door');

        var widthVal = 0;
        var heigthVal = 0;
        var colorVal = 0;

        var procent = $(this).data('proc');

        if(width && heigth) {
            if(heigth.required || width.required) {
                heigthVal = heigth.value;
                widthVal = width.value;
                if(!checkHeigth(heigth)) return false;
                if(!checkHeigth(width)) return false;
            } else {
                heigthVal = 0;
                widthVal = 0;
            }
        }


        var colorRal = document.getElementById('test-razmer');
        //console.log(colorRal.required);
        if(colorRal) {
            if(colorRal.required) {
                colorVal = colorRal.value;
                if(!checkColor(colorRal)) return false;
            } else {
                colorVal = 0
            }
        }


        $.post('/bitrix/templates/general/ajax/connector.php?act=cart', {
            cart: JSON.stringify(cart)
        }, function (res) {
            res.buy.forEach(function(currentValue) {
                if(cart[0][0] == currentValue['product_id']) {
                    var arrHL = [];

                    console.log(arrHL);
                    $.post('/bitrix/templates/general/ajax/setWidthHeightColor.php', {
                        ID: currentValue['id'],
                        PRODUCT_ID: currentValue['product_id'],
                        WIDTH: widthVal,
                        HEIGHT: heigthVal,
                        COLOR: colorVal,
                        PROCENT: 0
                    }, function (res2) { console.log(res2)}, 'json');
                } else {
                    $.post('/bitrix/templates/general/ajax/setWidthHeightColor.php', {
                        ID: currentValue['id'],
                        PRODUCT_ID: currentValue['product_id'],
                        PROCENT: procent
                    }, function (res2) { console.log(res2)}, 'json');
                }
            });

            if (res.success) {

                if ($(that).parent('div').find('.product-filter-submit__button').length > 0) {
                    $(that).addClass('hidden');
                    $(that).parent('div').find('a').removeClass('hidden')
                } else {
                    $(that).removeClass('detail-to-cart');
                    $(that).addClass('button--secondary');
                    $(that).text('В корзине');
                }

                $('.js-header-shop-links__badge').html(res.count);
                $('.js-header-shop-links__text').html(res.allsum_formatted_clean);


                if (res.count > 0) {
                    $('.js-header-shop-links__cart').addClass('active');
                } else {
                    $('.js-header-shop-links__cart').removeClass('active');
                    $('.js-header-shop-links__text').html('Корзина');
                }
                BX.onCustomEvent('OnBasketChange');
            }
        }, 'json');
// task1090
        popupMarketing('AddCart');
        return false;
    });

    $('#total-quantity-input').trigger('change');

    selectCurrentOffer();
};

function checkHeigth(self) { //this.value = checkHeigth(this.value) this.setCustomValidity('123')
    if ( document.readyState !== 'complete' ) return false;
    if(self.value) {
        console.log(self.min);
        console.log(self.max);
        console.log(self.value < self.min);
        console.log(self.value > self.max);
        if(Number(self.value) < Number(self.min) || Number(self.value) > Number(self.max))  {
            if(!$(self).next(".error").length)
                $(self).after("<div class='error'>Укажите правильные размеры дверного полотна</div>");
            return false;
        } else {
            if(Number(self.value)%50 != 0) {
                if(!$(self).next(".error").length)
                    $(self).after("<div class='error'>Укажите правильные размеры дверного полотна с шагом 50 мм</div>");
                return false;
            }

            if (Number(self.value) >= 2050 && Number(self.value) <= 2300) {
                setDetailPrices(true,30);
            } else {
                if (Number(self.value) >= 2350 && Number(self.value) <= 2400 ) {
                    setDetailPrices(true,100);
                } else {
                    setDetailPrices(true,0);
                }
            }
            $(self).next(".error").remove();
            console.log(Number(self.value));
            return true;
        }
    } else {
        if(!$(self).next(".error").length)
            $(self).after("<div class='error'>Укажите правильные размеры дверного полотна</div>");
        return false;
    }

}

function checkColor(self) { //this.value = checkHeigth(this.value) this.setCustomValidity('123')
    console.log(self);
    if ( document.readyState !== 'complete' ) return false;
    console.log(self.value);
    if(isEmpty(self.value))  {

        return false;
    } else {
        setDetailPrices(true,30);
        $('.color-ral .error').remove();
        return true;
    }
}

function isEmpty(str) {
    if (str.trim() == '')
        return true;

    return false;
}