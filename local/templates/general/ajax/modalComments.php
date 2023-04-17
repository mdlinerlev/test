<?
use Bitrix\Main\Context,
    Bitrix\Sale,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

if ($request['itemsId']) {
    $req = [
        'action' => $request['subaction'],
        'type' => $request['type'],
        'itemsId' => $request['itemsId'],
        'items' => $request['items'],
        'all' => $request['all']
    ];
    //pr($request);
    $userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
    if ($userId > 0) {
        $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BKP)->getEntityDataClass();
        $iterator = $entity::getList([
            'select' => [
                'ID', 'NAME',
                'PROPERTY_COMMENT_' => 'COMMENT',
                'PROPERTY_USER_' => 'USER',
            ],
            'filter' => ['=PROPERTY_USER_VALUE' => $userId, 'ID' => $request['itemsId']]
        ]);?>
        <div class="popup-b2b">
            <div class="popup-b2b__wrp">
                <div class="popup-b2b__head">
                    <div class="popup-b2b__zag">Комментарий к заказу</div>
                </div>
                <div class="popup-b2b__detail" data-json='<?=json_encode($req);?>'>
                    <?/*<input type="hidden" id="ComOfferId" name="ID" value="<?= $arResult['ID'] ?>">*/?>
                    <div class="popup-b2b__detail-table full-width js-wrp">
                        <div class="tab-panels">
                            <?while ($arItem = $iterator->fetch()) {
                                $arItem['PROPERTY_COMMENT_VALUE'] = unserialize($arItem['PROPERTY_COMMENT_VALUE']);?>
                                <div class="basket_order__descr">
                                    <div class="basket__float-comment">
                                        <textarea class="toggled-item" name="<?=$arItem['ID']?>" cols="30" rows="3"><?=$arItem['PROPERTY_COMMENT_VALUE']['TEXT']?$arItem['PROPERTY_COMMENT_VALUE']['TEXT'].PHP_EOL:''?><?if($req['type'] == 'reserve_order') {?>Зарезервировать товары в заказе<?}?></textarea>
                                    </div>
                                </div>
                            <?}?>
                        </div>
                        <div class="popup-b2b__detail-table__btn">
                            <button class="button js-edit-table-popup-comment" data-before="Применить" data-after="Применить"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?}
}?>
