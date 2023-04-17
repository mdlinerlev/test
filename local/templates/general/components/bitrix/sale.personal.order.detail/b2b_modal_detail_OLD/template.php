<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

?>
<div class="popup-b2b">
    <div class="popup-b2b__wrp">

        <? if (!empty($arResult['ERRORS']['FATAL'])) {
            foreach ($arResult['ERRORS']['FATAL'] as $error) {
                ShowError($error);
            }

            $component = $this->__component;

            if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
                $APPLICATION->AuthForm('', false, false, 'N', false);
            }
        } else {
            if (!empty($arResult['ERRORS']['NONFATAL'])) {
                foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
                    ShowError($error);
                }
            } ?>
            <div class="popup-b2b__head">
                <div class="popup-b2b__zag">
                    <span>Заказ <?= $arResult['PROPERTIES']['NUMBER_1C'] ?></span>
                </div>
            </div>
            <div class="popup-b2b__kp-detail">
                <div class="b2b-favorite__table">
                    <?
                    $arStatuses = [];
                    foreach ($arResult['ORDER_PROPS'] as $prop) {
                        if($prop['CODE'] == 'STATUS_PRODUCTS') {
                            $arStatuses = unserialize(trim($prop['VALUE'], "'"));
                        }
                    }
                    //pr($arStatuses);?>
                    <table style="min-width: 1000px">
                        <thead>
                        <tr>
                            <th style="width: 2%">Номер</th>
                            <th style="width: 30%">Название товара</th>
                            <th style="width: 10%">Цена закупки</th>
                            <th style="width: 10%">Количество</th>
                            <?if(count($arStatuses) > 0) :?>
                                <th style="width: 10%">Статус товаров</th>
                            <?endif;?>
                            <th style="width: 10%">Ед. изм.</th>
                            <th style="width: 10%">Сумма</th>
                            <th style="width: 10%">%НДС</th>
                            <th style="width: 10%">Сумма с НДС</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($arResult['BASKET'] as $key => $arBasketItem) {
                            $user_price = 0;
                            if (isset($arResult['PRICES'][$arBasketItem['PRODUCT_ID']][$arResult['PRICE_TYPE']])) {
                                $user_price = $arResult['PRICES'][$arBasketItem['PRODUCT_ID']][$arResult['PRICE_TYPE']];
                            }
                            //pr($arBasketItem['PRODUCT_ID']);
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= $key ?></td>
                                <td><?= $arBasketItem['NAME'] ?></td>
                                <td><?= CurrencyFormat($user_price, $arResult['CURRENCY']); ?></td>
                                <td><?= $arBasketItem['QUANTITY'] ?></td>
                                <?if(count($arStatuses) > 0) :?>
                                    <td>
                                        <?if(isset($arStatuses[$arBasketItem['PRODUCT_ID']])){
                                            echo $arStatuses[$arBasketItem['PRODUCT_ID']];
                                        }?>
                                    </td>
                                <?endif;?>
                                <td>шт</td>

                                <td><?= CurrencyFormat(getWatPrice($user_price * $arBasketItem['QUANTITY'])['~PRICE'], $arResult['CURRENCY']); ?></td>
                                <td>20%</td>
                                <td><?= CurrencyFormat($user_price * $arBasketItem['QUANTITY'], $arResult['CURRENCY']); ?></td>
                            </tr>
                        <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <? } ?>
    </div>
</div>

