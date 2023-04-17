$(document).ready(() => {
    setHeadBasket = async () => {
        $.ajax({
            url: '/bitrix/templates/general/inc/basket_line2.php',
            success: (data) => {
                $('#basket_head').html(data);
            }
        });
    };
    setHeadBasket();
    imputMask();
    $(document).on('click', '.js-favorite-add', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '/ajax/reAjax.php',
            type: 'POST',
            data: {
                action: 'favoriteHandler',
                type: 'favorite',
                itemId: id
            },
            dataType: 'json',
            success: (data) => {
                window.favorites = data.favorites;

                var message = '';
                if(data.success){
                    $('.js-favorite-add[data-id='+id+']').toggleClass('_active');
                    message = data.successMsg;
                }else{
                    message = data.errorMsg;
                }

                var messageText =
                    '<div class="popup-b2b">' +
                    '<div class="popup-b2b__wrp">' +
                    '<div class="popup-b2b__form">' + message + '</div>' +
                    '</div>' +
                    '</div>';
                $.magnificPopup.open({
                    type: 'inline',
                    items: {
                        src: messageText
                    },
                    overflowY: 'scroll',
                    mainClass: 'w440',
                    closeOnBgClick: true,
                    enableEscapeKey: true,
                });
            }
        })
    });
});

$(document).ready(function () {
    function openLinkList(linkList) {
        for (var i = 0; i < linkList.length; i++) {
            var a = document.createElement("a");
            a.href = linkList[i];
            a.target = "_blank"
            a.click();
        }
    };

    var isWork = false;

    $(document).on('submit', 'form.js-edit__editor', function (e) {
        e.preventDefault();
        console.log(isWork);
        if (isWork === false) {
            isWork = true;

            var data = {
                action: 'comOffersHandler',
                type: '',
                itemsId: [],
                items: {},
                all: false
            };

            $('.js-b2b-table .js-checkbox input:checked').each(function () {
                var itemContainer = $(this).parents('tr'),
                    item = {},
                    itemId = $(this).attr('data-id');

                data.itemsId.push(itemId);
                itemContainer.find('input,select').each(function () {
                    var code = $(this).attr('name'),
                        val = $(this).val();
                    item[code] = val;
                });
                item['ID'] = itemId;
                data.items[itemId] = item;
            });
            data.all = $(this).find('#all').get(0).checked;
            data.type = $(this).find('select.js-select').val();


            if(data.type == 'create_order' || data.type == 'reserve_order') {
                data.action = 'modalComments';
                data.subaction = 'comOffersHandler';
                $.ajax({
                    type: 'POST',
                    url: '/ajax/reAjax.php',
                    data: data,
                    dataType: 'html',
                    success: (result) => {
                        console.log(result);
                        if (result.returnUrl !== undefined && result.returnUrl.length) {
                            openLinkList(result.returnUrl);
                        } else {
                            $.magnificPopup.open({
                                type: 'inline',
                                items: {
                                    src: result
                                },
                                overflowY: 'scroll',
                                mainClass: 'w1170',
                                closeOnBgClick: false,
                                enableEscapeKey: false,
                            });
                        }
                        console.log(isWork);
                        //isWork = false
                        console.log(isWork);
                        //BX.closeWait('com-offers-search');
                        isWork = false;
                    }
                });
            } else {
                if (data.type.length) {
                    BX.showWait('com-offers-search');
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/reAjax.php',
                        data: data,
                        dataType: 'json',
                        success: (result) => {
                            if (result.returnUrl !== undefined && result.returnUrl.length) {
                                openLinkList(result.returnUrl);
                            }
                            var title = result.title,
                                message = result.success_message;
                            if (result.errorMsg) {
                                message = result.errorMsg;
                            }

                            var messageText =
                                '<div class="popup-b2b">' +
                                '<div class="popup-b2b__wrp">' +
                                '<div class="popup-b2b__head">' +
                                '<div class="popup-b2b__zag">' + title + '</div>' +
                                '</div>' +
                                '<div class="popup-b2b__form">' + message + '</div>' +
                                '</div>' +
                                '</div>';
                            $.magnificPopup.open({
                                type: 'inline',
                                items: {
                                    src: messageText
                                },
                                overflowY: 'scroll',
                                mainClass: 'w440',
                                closeOnBgClick: false,
                                enableEscapeKey: false,
                                callbacks: {
                                    close: function () {
                                        if (result.needReload) {
                                            location.reload();
                                        }
                                    }
                                }
                            });
                            /*}*/

                            BX.closeWait('com-offers-search');
                            isWork = false;
                        }
                    });
                } else {
                    isWork = false;
                }
            }
        }
    });
    $(document).on('click', '.js-edit-table-popup._active', function (e) {
        console.log(2);
        var formCont = $('.popup-b2b__detail'),
            id = formCont.attr('data-id'),
            items = {}, item = {};

        formCont.find('input').each(function () {
            item[$(this).attr('name')] = $(this).val();
        });
        formCont.find('textarea').each(function () {
            item[$(this).attr('name')] = $(this).val();
        });
        items[id] = item;

        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: {
                action: 'comOffersHandler',
                items: items,
                itemsId: [id],
                type: 'edit',
            },
            dataType: 'json',
            success: (result) => {
                if (!result.success) {

                    $('.mfp-close').click();

                    var title = result.title,
                        message = result.success_message;
                    if (result.errorMsg) {
                        message = result.errorMsg;
                    }

                    var messageText =
                        '<div class="popup-b2b">' +
                        '<div class="popup-b2b__wrp">' +
                        '<div class="popup-b2b__head">' +
                        '<div class="popup-b2b__zag">' + title + '</div>' +
                        '</div>' +
                        '<div class="popup-b2b__form">' + message + '</div>' +
                        '</div>' +
                        '</div>';
                    $.magnificPopup.open({
                        type: 'inline',
                        items: {
                            src: messageText
                        },
                        overflowY: 'scroll',
                        mainClass: 'w440',
                        closeOnBgClick: false,
                        enableEscapeKey: false,
                        callbacks: {
                            close: function () {
                                if (result.needReload) {
                                    location.reload();
                                }
                            }
                        }
                    });
                } else {
                    $('.js-offer-item[data-id="' + id + '"]').click();
                }
            }
        });
    });
    $(document).on('click', '.js-edit-table-popup-comment', function (e) {
        e.preventDefault();
        if (isWork === false) {
            isWork = true;
            console.log(2);
            var formCont = $('.popup-b2b__detail'),
                jsonData = formCont.attr('data-json'),
                items = {}, item = {}, itemId = {};
            var data;
            console.log(jsonData);
            data = JSON.parse(jsonData);

            console.log(data);
            formCont.find('textarea').each(function () {
                item[$(this).attr('name')] = $(this).val();
                //itemsId.push($(this).attr('name'));
            });
            data.comment = item;
            //items = item;
            console.log(data);
            if (data.type.length) {
                BX.showWait('com-offers-search');
                $.ajax({
                    type: 'POST',
                    url: '/ajax/reAjax.php',
                    data: data,
                    dataType: 'json',
                    success: (result) => {
                        if (result.returnUrl !== undefined && result.returnUrl.length) {
                            openLinkList(result.returnUrl);
                        } else {
                            var title = result.title,
                                message = result.success_message;
                            if (result.errorMsg) {
                                message = result.errorMsg;
                            }

                            var messageText =
                                '<div class="popup-b2b">' +
                                '<div class="popup-b2b__wrp">' +
                                '<div class="popup-b2b__head">' +
                                '<div class="popup-b2b__zag">' + title + '</div>' +
                                '</div>' +
                                '<div class="popup-b2b__form">' + message + '</div>' +
                                '</div>' +
                                '</div>';
                            $.magnificPopup.close()
                            $.magnificPopup.open({
                                type: 'inline',
                                items: {
                                    src: messageText
                                },
                                overflowY: 'scroll',
                                mainClass: 'w440',
                                closeOnBgClick: false,
                                enableEscapeKey: false,
                                callbacks: {
                                    close: function () {
                                        if (result.needReload) {
                                            location.reload();
                                        }
                                    }
                                }
                            });
                        }

                        BX.closeWait('com-offers-search');
                        isWork = false;
                    }
                });
            } else {
                isWork = false;
            }
        }
    });
    $(document).on('click', '.js-basketItem-del', function () {
        console.log(3);
        var delId = $(this).attr('data-id'),
            id = $('#ComOfferId').val();
        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: {
                action: 'comOffersHandler',
                itemsId: id,
                delId: delId,
                type: 'del_basket-item',
            },
            success: (result) => {
                $('.js-offer-item[data-id="' + id + '"]').click();
            }
        });
    });
    $(document).on('change', '.js-sort', function () {
        if ($(this).attr('data-href').length) {
            location.href = $(this).attr('data-href');
        }
    });


    $(document).on('click', '.b2b-aside-2 a', function () {
        var input = $(this).attr('data-id');
        self = this;
        if(input > 0) {
            $.ajax({
                url: '/ajax/reAjax.php?action=notoficationOrders',
                type: 'POST',
                data: {
                    id: input
                },
                dataType: 'json',
                success: (data) => {
                    var message = '';
                    if(data.success){
                        $(self).closest('li').remove();
                        var len = $('.b2b-aside-2 .b2b-menu__ul li').length;
                        $('.has_notification').attr('data-count', len);
                        $('.cab .header-b2b__img').attr('data-count', len);
                        if($('.b2b-aside-2 .b2b-menu__ul li').length == 0) {
                            $('.b2b-aside-2').remove();
                            $('.has_notification').removeClass('has_notification');
                            $('.cab').removeClass('cab');
                        }
                    }else{

                    }
                }
            })
        }
    });
});
var checkFavorites = () => {
    if(window.favorites !== undefined){
        $('.js-favorite-add').removeClass('_active');
        for (const [key, value] of Object.entries(window.favorites)) {
            $('.js-favorite-add[data-id='+value+']').addClass('_active');
        }
    }
};
var addresAutocomplete = (selector) => {
    if (window.cacheAddress === undefined) {
        window.cacheAddress = [];
    }
    $(selector).autocomplete({
        appendTo: ".response",
        minLength: 2,
        source: function (request, response) {
            var term = request.term;
            if (term in window.cacheAddress) {
                response(window.cacheAddress[term]);
                return;
            }

            $.getJSON('/ajax/reAjax.php?action=addressValidation', request, function (data, status, xhr) {
                window.cacheAddress[term] = data;
                response(data);
            });
        }
    });
};
var imputMask = () => {
    $('input[type=tel]').each(function () {
        var mask = $(this).attr('data-mask');
        $(this).mask(mask);
    });
};

$(document).on('click', '.article-cat__btn', function (e) {
    $('.article-cat__li').removeClass('_hidden');
    $(this).html('Скрыть').addClass('_active');
});
$(document).on('click', '.article-cat__btn._active', function (e) {
    $('.article-cat__li:nth-child(n+11)').addClass('_hidden');
    $(this).html('Показать еще').removeClass('_active');
});
