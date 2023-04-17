<?
if ($new == 'Y' && !empty($_SESSION['KP_ITEM'])) {
    unset($_SESSION['KP_ITEM']);
}

$requisits = $addresses = [];
$mainAddress = '';
$iblock = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE);
$entity = $iblock->getEntityDataClass();
$iterator = $entity::getList([
    'select' => [
        'ID',
        'NAME',
        'USER_' => 'USER',
        'ACTUAL_ADDRESS_' => 'ACTUAL_ADDRESS',
        'IS_MAIN_' => 'IS_MAIN'
    ],
    'filter' => ['=USER_VALUE' => \Bitrix\Main\Engine\CurrentUser::get()->getId()]
]);
while ($arItem = $iterator->fetch()) {
    $requisits[$arItem['ID']] = $arItem;

    if ($arItem['IS_MAIN_VALUE'] == 1) {
        $mainAddress = $arItem['ACTUAL_ADDRESS_VALUE'];
    } else {
        $addresses[] = $arItem['ACTUAL_ADDRESS_VALUE'];
    }
}
$property_type = getPropertyListVariant('TYPE', IBLOCK_ID_B2BKP, ['ID', 'VALUE', 'XML_ID'], [], 'XML_ID', ['SORT' => 'ASC']);
$property_payment = getPropertyListVariant('PAYMENT_TYPE', IBLOCK_ID_B2BKP, ['ID', 'VALUE'], [], '', ['SORT' => 'ASC']);

$fizId = ($property_type['fiz']['ID']) ?: 0;
$yurId = ($property_type['fiz']['ID']) ?: 0;

$dataValues = ($_COOKIE['basketValues'])? \Bitrix\Main\Web\Json::decode($_COOKIE['basketValues']) : [];
?>
<div class="popup-b2b">
    <div class="popup-b2b__wrp">
        <div class="popup-b2b__head">
            <div class="popup-b2b__zag">Формирование коммерческого предложения</div>
        </div>
        <form type="post" class="popup-b2b__form js-check-kp" id="kp-create">
            <div class="popup-b2b__form-wrp">
                <p style="color: red;" id="error"></p>
                <div class="popup-b2b__form-zag">Основная информация</div>
                <div class="popup-b2b__form-item w50">
                    <label>Тип лица</label>
                    <select class="styler js-change-property" name="PROPERTIES[TYPE]">
                        <? foreach ($property_type as $type) { ?>
                            <option value="<?= $type['ID'] ?>" <?if($dataValues['PROPERTIES[TYPE]'] == $type['ID']){?>selected<?}?>><?= $type['VALUE'] ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="popup-b2b__form-item w50" data-property="PROPERTIES[TYPE]" data-hide="<?= $fizId ?>">
                    <label>Наименование организации</label>
                    <input type="text" name="PROPERTIES[CLIENT]" value="<?=$dataValues['PROPERTIES[CLIENT]']?>" placeholder="Введите название организации"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Имя лица, оформляющего заказ</label>
                    <input type="text" name="PROPERTIES[CLIENT_NAME]" value="<?=$dataValues['PROPERTIES[CLIENT_NAME]']?>" placeholder="Введите Имя"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Телефон</label>
                    <input type="tel" data-mask="+7 (999) 999-99-99" value="<?=$dataValues['PROPERTIES[PHONE]']?>" placeholder="+7 (___) ___-__-__"
                           name="PROPERTIES[PHONE]"/>
                </div>
                <div class="popup-b2b__form-zag">Доставка</div>
                <div class="popup-b2b__form-item w50">
                    <label>Город доставки</label>
                    <input type="text" name="PROPERTIES[CITY]" value="<?=$dataValues['PROPERTIES[CITY]']?>" placeholder="Введите город доставки"/>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Адрес доставки</label>
                    <input type="text" id="address_delivery" name="PROPERTIES[ADDRESS]"
                           placeholder="Введите адрес доставки" value="<?=$dataValues['PROPERTIES[ADDRESS]']?>"/>
                </div>
                <div class="popup-b2b__form-zag">Оплата и Реквизиты</div>
                <div class="popup-b2b__form-item w50">
                    <label>Тип оплаты</label>
                    <select class="styler" name="PROPERTIES[PAYMENT_TYPE]">
                        <? foreach ($property_payment as $payment) { ?>
                            <option value="<?= $payment['ID'] ?>" <?if($dataValues['PROPERTIES[PAYMENT_TYPE]'] == $payment['ID']){?>selected<?}?>><?= $payment['VALUE'] ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="popup-b2b__form-item w50">
                    <label>Выберите реквизиты</label>
                    <select class="styler" name="PROPERTIES[REQUISITE]">
                        <? foreach ($requisits as $arRequisit) { ?>
                            <option value="<?= $arRequisit['ID'] ?>" <?if($dataValues['PROPERTIES[REQUISITE]'] == $arRequisit['ID']){?>selected<?}?>><?= $arRequisit['NAME'] ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="popup-b2b__form-item" style="position: relative">
                    <label>Адрес салона</label>
                    <select name="PROPERTIES[SALON_ADDRESS]" class="styler">
                        <option value="<?=$mainAddress?>" <?if($dataValues['PROPERTIES[SALON_ADDRESS]'] == $mainAddress){?>selected<?}?>><?=$mainAddress?></option>
                        <? foreach ($addresses as $arAddress) { ?>
                            <option value="<?= $arAddress ?>" <?if($dataValues['PROPERTIES[SALON_ADDRESS]'] == $arAddress){?>selected<?}?>><?= $arAddress ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="popup-b2b__form-item _center">
                    <div class="popup-b2b__form-item w50">
                        <button class="button" type="submit" form="kp-create" data-class="w1710"
                                data-href="/ajax/reAjax.php?action=modalBasketKpFinal">Далее
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    try {
        new ymaps.SuggestView('address', {
            provider: {
                suggest: (function (request, options) {
                    return ymaps.suggest('Россия' + ", " + request);
                })
            }
        });
        new ymaps.SuggestView('address_delivery', {
            provider: {
                suggest: (function (request, options) {
                    return ymaps.suggest('Россия' + ", " + request);
                })
            },
            zIndex: 99999999
        });
    } catch (e) {
        console.log(e);
    }
    $('select.js-change-property').each(function () {
        toggleType($(this).attr('name'), $(this).val());
    });
    imputMask();
</script>
