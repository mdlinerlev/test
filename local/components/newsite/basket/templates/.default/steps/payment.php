<div class="basket_order__step">
    <div class="basket_order__title">Оплата</div>
    <div class="basket_order__content">
        <div class="tabset">
            <?
            $descr = false;
            $currentPaymentId = reset(array_filter($currentOrder->getPaymentSystemId()));
            foreach ($arResult["AVAILABLE_PAYSYSTEMS"] as $itemPaysystem):
                if( $currentPaymentId == $itemPaysystem['ID'])
                    $descr = $itemPaysystem["DESCRIPTION"];
                ?>
                <input
                        class="js_select_change_submit tabset_inp"
                        type="radio"
                        value="<?= $itemPaysystem['ID'] ?>"
                        name="PAYMENT_ID"
                        id="PAYMENT_ID_<?= $itemPaysystem['ID']?>"
                    <?= $currentPaymentId == $itemPaysystem['ID'] ? "checked" : "" ?>
                >

                <label for="PAYMENT_ID_<?= $itemPaysystem['ID']?>" class="tabset_checkbox">
                    <?= $itemPaysystem["NAME"]?>
                </label>
            <?endforeach;?>
        </div>
        <div class="tab-panels">
            <div class="basket_order__descr">
                <? include __DIR__ . '/../prop.php'; ?>
                <?=$descr?>
            </div>
        </div>
    </div>
</div>