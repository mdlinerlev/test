<div class="basket_order__step">
    <div class="basket_order__title">Данные</div>
    <div class="basket_order__content">
        <div class="tabset">

            <?
            $currentPersonTypeId = $currentOrder->getPersonTypeId();
            foreach ($arResult['PERSON_TYPE'] as $person):?>
            <input id="PERSON_TYPE_<?=$person["ID"]?>" type="radio" value="<?=$person['ID']?>" name="PERSON_TYPE" class="tabset_inp js_select_change_submit"<?=$currentPersonTypeId == $person['ID'] ? ' checked' : ''?>>
            <label for="PERSON_TYPE_<?=$person["ID"]?>" class="tabset_checkbox">
                <?=$person['NAME']?>
            </label>
            <?endforeach;?>

            <div class="tab-panels">
                <div class="basket_order__descr">
                    <? include_once __DIR__ . '/../prop.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
