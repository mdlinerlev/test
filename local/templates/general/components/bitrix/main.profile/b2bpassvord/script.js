$(document).ready(function () {
    $(document).on('click', '.js-change-state-passvord', function (e) {
        e.preventDefault();
        var input = $(this).closest('.b2b-profile__item-form__elem').find('.js-passvord-input');

        if ($(this).hasClass('_active')) {
            $(this).parents('form').find('input[type="submit"]').get(0).click();
        } else {
            $(this).addClass('_active');
            input.attr('disabled', false).val('');
        }
    });
    $(document).on('input', 'input[name="NEW_PASSWORD"]', function () {
       $('input[name="NEW_PASSWORD_CONFIRM"]').val($(this).val());
        $('input[name="NEW_PASSWORD_CONFIRM"]').attr('value', $(this).val());
    });
});