$(document).ready(function () {
    $(document).on('submit', '.js-check-kp', function (e) {
        e.preventDefault();
        var fd = new FormData($(this).get(0)),
            url = $(this).find('button[type=submit]').attr('data-href'),
            mainClass = $(this).find('button[type=submit]').attr('data-class'),
            comment = $('textarea[class="toggled-item"]').val();
        fd.append('action', 'comOffersHandler');
        fd.append('type', 'validate');
        fd.append('comment', comment);

        BX.showWait('kp-create');
        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: fd,
            processData: false,
            contentType: false,
            success: (request) => {
                try {
                    var json = JSON.parse(request);
                    if (json.success === false) {
                        $('#error').html(json.errorMsg);
                    } else {
                        $.magnificPopup.close();
                        $.magnificPopup.open({
                            type: 'ajax',
                            items: {
                                src: url
                            },
                            overflowY: 'scroll',
                            mainClass: mainClass,
                            closeOnBgClick: false,
                            enableEscapeKey: false,
                            callbacks: {
                                ajaxContentAdded: function ajaxContentAdded() {
                                    if ($('.styler').length) $('.styler').styler();
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.log(e);
                }
                BX.closeWait();
            }
        });
        return false;
    });
    $(document).on('submit', '.js-kp-create', function (e) {
        e.preventDefault();
        var fd = new FormData($(this).get(0));
        var comment = $('textarea[class="toggled-item"]').val();
        fd.append('action', 'comOffersHandler');
        fd.append('type', 'create');
        fd.append('comment', comment);

        BX.showWait('kp-create');
        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: fd,
            processData: false,
            contentType: false,
            success: (request) => {
                try {
                    var json = JSON.parse(request);
                    if (json.success === false) {
                        $('#error').html(json.errorMsg);
                    } else {
                        if (json.returnUrl.length) {
                            json.returnUrl.forEach((item) => {
                                window.open(item);
                            });
                            location.href = '/personal-b2b/com-offers/';
                        } else {
                            location.reload();
                        }
                    }
                } catch (e) {
                    console.log(e);
                }
                BX.closeWait();
            }
        });
        return false;
    });
    $(document).on('input', '.js-copy-value', function (e) {
        var data = $(this).attr('data-cont'),
            value = $(this).val().replaceAll("\n", '<br>');
        $('[data-cont-val=' + data + ']').html(value);
    });
    $(document).on('change', '.js-change-property', function (e) {
        toggleType($(this).attr('name'), $(this).val());
    });

    $(document).on('change', 'form.js-check-kp input,select', function () {
        var cookies = $.cookie('basketValues'),
            data = {};
        if (cookies !== undefined && cookies.length) {
            data = JSON.parse(cookies);
        }

        data[$(this).attr('name')] = $(this).val();
        $.cookie('basketValues', JSON.stringify(data), {expires: 1, path: '/'});
    });
});
var toggleType = (prop_name, value) => {
    var items = $('[data-property="' + prop_name + '"]');
    items.show();
    items.each(function () {
        if ($(this).attr('data-hide') === value) {
            $(this).hide()
        }
    });
};