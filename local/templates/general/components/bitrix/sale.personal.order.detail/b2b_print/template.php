<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

if (!empty($arResult['ERRORS']['FATAL'])) {
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
    }
    ?>
    <div class="container-fluid sale-order-detail">
        <div class="sale-order-detail-title-container">
            <h1 class="sale-order-detail-title-element">
                <?= Loc::getMessage('SPOD_LIST_MY_ORDER', array(
                    '#ACCOUNT_NUMBER#' => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
                    '#DATE_ORDER_CREATE#' => $arResult["DATE_INSERT_FORMATED"]
                )) ?>
            </h1>
        </div>
        <div class="sale-order-detail-general">
            <div class="row sale-order-detail-about-order">
                <div class="sale-order-detail-about-order-container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-title">
                            <h3 class="sale-order-detail-about-order-title-element">
                                <?= Loc::getMessage('SPOD_LIST_ORDER_INFO') ?>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="sale-order-detail-about-order-inner-container">
                            <div class="col-md-12 col-sm-12 col-xs-12 sale-order-detail-about-order-inner-container-details">
                                <ul class="sale-order-detail-about-order-inner-container-details-list"
                                    style="list-style: outside none none;">
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Номер кп:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['PROPERTIES']['KP_ID'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Номер заказа:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['PROPERTIES']['NUMBER_1C'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Дата заказа:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['DATE_INSERT_FORMATED'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Сумма с НДС :</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['PRODUCT_SUM_FORMATED'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Оплачено:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['PAYMENT'][$arResult['ID']]['PRICE_FORMATED'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Статус:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['STATUS']['NAME'] ?></div>
                                    </li>
                                    <li class="sale-order-detail-about-order-inner-container-list-item">
                                        <b>Комментарий:</b>
                                        <div class="sale-order-detail-about-order-inner-container-list-item-element"><?= $arResult['PROPERTIES']['COMMENT'] ?></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row sale-order-detail-payment-options-order-content">
                <table>
                    <tbody>
                    <tr>
                        <th></th>
                        <th>Наименование</th>
                        <th>Количество</th>
                        <th>Сумма c НДС</th>
                    </tr>
                    <? foreach ($arResult['BASKET'] as $arBasketItem) { ?>
                        <tr>
                            <td>
                                <img src="<?=$arBasketItem['PICTURE']['SRC']?>" style="opacity: 1;">
                            </td>
                            <td><?=$arBasketItem['NAME']?></td>
                            <td> <?=$arBasketItem['QUANTITY']?> <?=$arBasketItem['MEASURE_NAME']?></td>
                            <td>
                                <span class="price"><?=$arBasketItem['FORMATED_SUM']?></span>
                            </td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
            <h4>Итого: <?= $arResult['PRODUCT_SUM_FORMATED'] ?></h4>
        </div>
    </div>
<? } ?>

