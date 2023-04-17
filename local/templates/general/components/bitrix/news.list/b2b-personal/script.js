$(document).ready(function () {
    $(document).on('change', '.js-add input[type="file"]', function () {
        var form = $(this).parents('form'),
            fd = new FormData(form.get(0)),
            size = $(this).attr('data-size');

        if (!this.files.length) {
            setImgError('Загрузите файл до ' + size + ' кб');
        } else {
            fd.append('action', 'setImageProfile');
            $.ajax({
                type: 'POST',
                url: '/ajax/reAjax.php',
                data: fd,
                processData: false,
                contentType: false,
                success: (result) => {
                    try {
                        var json = JSON.parse(result);
                        if (!json.success) {
                            setImgError(json.errorMsg);
                        } else {
                            setImgError('');
                        }
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        }
    });
    $(document).on('change', 'select.js-setPrice', function () {
        var price = $(this).val(),
            id = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: {
                action: 'setUserPrice',
                newPrice: price,
                itemId: id
            },
            dataType: 'json',
            success: (result) => {
                if(!result.success){

                }
            }
        })
    });
    var setImgError = (msg) => {
        $('#error_img_cont').html(msg);
    }
});