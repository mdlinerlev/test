$(document).ready(function () {
    const ajaxUrl = '/ajax/reAjax.php';

    $(document).on('click', '.js-save', function () {
        var form = $(this).parents('form'),
            fd = new FormData(form.get(0));

        fd.append('action', 'requisitsHandler');
        fd.append('type', 'update');
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: fd,
            processData: false,
            contentType: false,
            success: (result) => {
                try {
                    var json = JSON.parse(result);
                    if (json.success) {
                        location.reload();
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        })
    });
    $(document).on('click', '.js-reset', function (e) {
        e.preventDefault();
    });
    $(document).on('submit', '.js-add-requisits', function (e) {
        e.preventDefault();
        var fd = new FormData(this);

        fd.append('action', 'requisitsHandler');
        fd.append('type', 'add');
        $.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: fd,
            processData: false,
            contentType: false,
            success: (result) => {
                try {
                    var json = JSON.parse(result);
                    location.reload();
                } catch (e) {
                    console.log(e);
                }
            }
        })
    });
    $(document).on('submit', '.js-edit__editor', function (e) {
        e.preventDefault();
        var type = $('#type').val();
        if (type.length) {
            var all = $('#all').is(':checked'),
                fd = new FormData();

            $('.b2b-req__item .js-checkbox input:checked').each(function () {
                fd.append('itemIds[]', $(this).attr('data-id'));
            });
            fd.append('type', 'del');
            fd.append('all', (all === true) ? 'Y' : 'N');
            fd.append('action', 'requisitsHandler');

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: fd,
                processData: false,
                contentType: false,
                success: (result) => {
                    try {
                        var json = JSON.parse(result);
                        location.reload();
                    } catch (e) {
                        console.log(e);
                    }
                }
            })
        }
    });
    $(document).on('click', '.js-add-entity', function (e) {
        e.preventDefault();
        var entity = $(this).attr('data-entity'),
            container = $(this).attr('data-container');
        if (templates[entity] !== undefined) {
            $('[data-entity-container=' + container + ']').append(templates[entity]);
        }
        return false;
    });

    var templates = {
        address: '<li>\n' +
            '                   <ul>\n' +
            '                     <li>Фактический адрес</li>\n' +
            '                     <li class="js-editable _edit">\n' +
            '                        <textarea placeholder="Введите данные"\n' +
            '                             name="PROPERTIES[ACTUAL_ADDRESS][]"></textarea>\n' +
            '                        <button class="js-reset button">\n' +
            '<svg class="icon icon-delete ">\n' +
            '<use xlink:href="/bitrix/templates/general/img/svg/symbol/sprite.svg#delete"></use>\n' +
            '                        </svg>\n' +
            '                        </button>\n' +
            '                      </li>\n' +
            '                    </ul>\n' +
            '                 </li>'
    };
});