$(document).ready(function () {
    $(document).on('submit', 'form.js-edit__editor', function (e) {
        e.preventDefault();
        var data = {
            action: 'favoriteHandler',
            type: '',
            itemsId: [],
            items: {},
            all: false
        };

        $('.b2b-favorite__table .js-checkbox input:checked').each(function () {
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

        if (data.type.length) {
            $.ajax({
                type: 'POST',
                url: '/ajax/reAjax.php',
                data: data,
                dataType: 'json',
                success: (result) => {
                    if (result.needReload) {
                        location.reload();
                    }

                    if (result.returnUrl.length) {
                        result.returnUrl.forEach((item) => {
                            window.open(item);
                        });
                    }
                }
            });
        }
    });
    $(document).on('change', 'select.js-set-categories', function () {
        $('#favorites_filter').submit();
    });
});