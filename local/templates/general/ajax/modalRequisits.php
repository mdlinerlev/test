<? ?>
<div class="popup-b2b">
    <div class="popup-b2b__wrp">
        <div class="popup-b2b__head">
            <div class="popup-b2b__zag">Добавить компанию</div>
        </div>
        <form type="post" id="requisits_add" class="popup-b2b__form js-add-requisits">
            <div class="popup-b2b__form-wrp">
                <div class="popup-b2b__form-item w50">
                    <label>Название организации</label>
                    <input type="text" name="NAME" placeholder="Введите название организации"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Телефон</label>
                    <input type="tel"
                           data-mask="+7 (999) 999-99-99"
                           placeholder="+7 (___) ___-__-__"
                           name="PROPERTIES[PHONE]"
                    />
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Email</label>
                    <input type="email" name="PROPERTIES[EMAIL]" placeholder="Введите Email"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Менеджер</label>
                    <input type="text" name="PROPERTIES[MANAGER]" placeholder="Введите ФИО менеджера"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>УНП/ИНН</label>
                    <input type="text" name="PROPERTIES[UNP]" placeholder="Введите УНП/ИНН"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Расчетный счет</label>
                    <input type="text" name="PROPERTIES[PAYMENT_ACCOUNT]" placeholder="Введите расчетный счет"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Название банка</label>
                    <input type="text" name="PROPERTIES[BANK_NAME]" placeholder="Введите название банка"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Адрес банка</label>
                    <input type="text" name="PROPERTIES[BANK_ADDRESS]" placeholder="Введите адрес банка"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Кассовый счет</label>
                    <input type="text" name="PROPERTIES[CASH_ACCOUNT]" placeholder="Введите кассовый счет"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>БИК</label>
                    <input type="text" name="PROPERTIES[BIC]" placeholder="Введите БИК"/>
                </div>

                <div class="popup-b2b__form-item w100">
                    <label>Фактический адрес</label>
                    <div data-row-container="address" class="row_container">
                        <div class="row">
                            <input type="text" name="PROPERTIES[ACTUAL_ADDRESS][]" placeholder="Введите адрес салона"/>
                            <div class="close js-rowDel button">
                                <svg class="icon icon-close ">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#close"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="button js-addRow" data-row="address">Добавить адрес</div>
                </div>
                <div class="popup-b2b__form-item w50">
                    <button class="button" type="submit" form="requisits_add">Добавить компанию</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var templates = {
        address: '<div class="row">\n' +
            '                            <input type="text" name="PROPERTIES[ACTUAL_ADDRESS][]" placeholder="Введите адрес салона"/>\n' +
            '                            <div class="close js-rowDel button">\n' +
            '                                <svg class="icon icon-close ">\n' +
            '                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#close"></use>\n' +
            '                                </svg>\n' +
            '                            </div>\n' +
            '                        </div>'
    };
    $(document).on('click', '.js-addRow', function (e) {
        e.preventDefault();
        var rowName = $(this).attr('data-row'),
            row = $('[data-row-container="' + rowName + '"]');
        if (row.length) {
            row.append(templates[rowName])
        }
    });
    $(document).on('click', '.js-rowDel', function (e) {
        e.preventDefault();
        $(this).parent().remove();
    });
    imputMask();
</script>
