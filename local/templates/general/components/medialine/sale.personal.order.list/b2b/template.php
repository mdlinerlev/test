<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

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
    if (!count((array)$arResult['ORDERS'])) {
        if ($request["filter_history"] == 'Y') {
            if ($request["show_canceled"] == 'Y') {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER') ?></h3>
                <?
            } else {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST') ?></h3>
                <?
            }
        } else {
            ?>
            <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST') ?></h3>
            <?
        }
    }
    ?>

    <div class="b2b-head">
        <form class="b2b-head__search">
            <input type="text" name="q" placeholder="Введите номер коммерческого предложения или заказа"
                   value="<?= $request['q'] ?>">
            <button type="submit">
                <svg class="icon icon-search ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#search"></use>
                </svg>
            </button>
        </form>
        <div class="b2b-head__person">
            <div class="b2b-head__person-ico">
                <svg class="icon icon-profile ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#profile"></use>
                </svg>
            </div>
            <? if ($arResult['MANAGER']) { ?>
                <div class="b2b-head__person-info">
                    <span class="name"><?= $arResult['MANAGER']['NAME'] ?></span>
                    <span class="phone"><?= $arResult['MANAGER']['PHONE'] ?></span>
                </div>
            <? } ?>
        </div>
    </div>
    <? if ($request["filter_history"] !== 'Y') { ?>
        <div class="b2b-content__wrp">
            <div class="b2b-orders">
                <div class="b2b-table__wrp scroll-X">
                    <? if ($arResult['ORDERS']) { ?>
                        <table class="b2b-table" style="width: 1170px">
                            <thead>
                            <tr>
                                <th style="width: 15%;">
                                    <div class="flex">
                                        <span>Номер КП</span>
                                        <div class="b2b-table__sort">
                                            <div class="b2b-table__sort-ico">
                                                <svg class="icon icon-sort ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                                </svg>
                                            </div>
                                            <ul class="b2b-table__sort-ul js-order-sort">
                                                <li>
                                                    <input type="radio"
                                                           name="sort"
                                                           data-url="?by=DATE&order=ASC"
                                                           <? if ($request['by'] == 'NUMBER' && $request['order'] == 'ASC'){ ?>checked<? } ?>><span>Сортировать по возрастанию</span>
                                                </li>
                                                <li>
                                                    <input type="radio" name="sort" data-url="?by=DATE&order=DESC"
                                                           <? if ($request['by'] == 'DATE' && $request['order'] == 'DESC'){ ?>checked<? } ?>><span>Сортировать по убыванию</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                                <th style="width: 15%;">
                                    <div class="flex">
                                        <span>Номер заказа</span>
                                        <div class="b2b-table__sort">
                                            <div class="b2b-table__sort-ico">
                                                <svg class="icon icon-sort ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                                </svg>
                                            </div>
                                            <ul class="b2b-table__sort-ul js-order-sort">
                                                <li>
                                                    <input type="radio"
                                                           name="sort"
                                                           data-url="?by=XML_ID&order=ASC"
                                                           <? if ($request['by'] == 'XML_ID' && $request['order'] == 'ASC'){ ?>checked<? } ?>><span>Сортировать по возрастанию</span>
                                                </li>
                                                <li>
                                                    <input type="radio" name="sort" data-url="?by=XML_ID&order=DESC"
                                                           <? if ($request['by'] == 'XML_ID' && $request['order'] == 'DESC'){ ?>checked<? } ?>><span>Сортировать по убыванию</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                                <th style="width: 13%;">
                                    <div class="flex">
                                        <span>Дата заказа</span>
                                        <div class="b2b-table__sort">
                                            <div class="b2b-table__sort-ico">
                                                <svg class="icon icon-sort ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                                </svg>
                                            </div>
                                            <ul class="b2b-table__sort-ul js-order-sort">
                                                <li>
                                                    <input type="radio"
                                                           name="sort"
                                                           data-url="?by=DATE&order=ASC"
                                                           <? if ($request['by'] == 'DATE_INSERT' && $request['order'] == 'ASC'){ ?>checked<? } ?>><span>Сортировать по возрастанию</span>
                                                </li>
                                                <li>
                                                    <input type="radio" name="sort" data-url="?by=DATE&order=DESC"
                                                           <? if ($request['by'] == 'DATE_INSERT' && $request['order'] == 'DESC'){ ?>checked<? } ?>><span>Сортировать по убыванию</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                                <th style="width: 15%;">
                                    <div class="flex">
                                        <span>Сумма с НДС</span>
                                        <div class="b2b-table__sort">
                                            <div class="b2b-table__sort-ico">
                                                <svg class="icon icon-sort ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                                </svg>
                                            </div>
                                            <ul class="b2b-table__sort-ul js-order-sort">
                                                <li>
                                                    <input type="radio"
                                                           name="sort"
                                                           data-url="?by=PRICE&order=ASC"
                                                           <? if ($request['by'] == 'PRICE' && $request['order'] == 'ASC'){ ?>checked<? } ?>><span>Сортировать по возрастанию</span>
                                                </li>
                                                <li>
                                                    <input type="radio" name="sort" data-url="?by=PRICE&order=DESC"
                                                           <? if ($request['by'] == 'PRICE' && $request['order'] == 'DESC'){ ?>checked<? } ?>><span>Сортировать по убыванию</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </th>
                                <th style="width: 15%;">Сумма КП с НДС</th>
                                <th style="width: 10%;">Оплачено</th>
                                <th style="width: 9%;">Статус</th>
                                <th style="width: 31%;">Комментарий</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $paymentChangeData = array();
                            $orderHeaderStatus = null;

                            foreach ($arResult['ORDERS'] as $key => $order) { ?>
                                <tr>
                                    <div style="display: none;">
                                        <?pr($order['ORDER']['ID'], true);?>
                                        <?pr($order['ORDER']['PROPERTIES'], true);?>
                                    </div>
                                    <td>
                                        <div class="flex">
                                            <? if (false) { ?>
                                                <a class="b2b-table__link"
                                                   href="?ID=<?= $order['ORDER']['ID'] ?>&print=Y"
                                                   title="Печать" target="_blank">
                                                    <svg class="icon icon-table-stamp ">
                                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#table-stamp"></use>
                                                    </svg>
                                                </a>
                                            <? } ?>
                                            <a class="b2b-table__link ajax-form" href="#"
                                               data-href="/ajax/reAjax.php?action=modalOrder" data-class="w440"
                                               title="Отправить сообщения">
                                                <svg class="icon icon-table-message ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#table-message"></use>
                                                </svg>
                                            </a>
                                            <span><a class="ajax-form" href="#"
                                                     data-href="/ajax/reAjax.php?action=modalComOfferDetail&ID=<?= $order['ORDER']['PROPERTIES']['KP_ID'] ?>"><?= $order['ORDER']['PROPERTIES']['KP_ID'] ?></a></span>
                                        </div>
                                    </td>
                                    <td><a class="b2b-table__order-num ajax-form"
                                           data-href="/ajax/reAjax.php?action=modalOrderDetail&ID=<?= $order['ORDER']['ID'] ?>"><?= $order['ORDER']['PROPERTIES']['NUMBER_1C'] ?></a>
                                    </td>
                                    <td><span><?= $order['ORDER']['DATE_INSERT_FORMATED'] ?></span></td>
                                    <td><span><?= $order['ORDER']['FORMATED_PRICE'] ?></span></td>
                                    <td><span><?= $order['ORDER']['PRICE_BASE_DISCOUNT'] ?></span></td>
                                    <td><span><?= $order['PAYMENT'][0]['FORMATED_SUM'] ?></span></td>
                                    <td>
                                        <span><?= $order['ORDER']['PROPERTIES']['STATUS'] ?></span>
                                    </td>
                                    <td><span><?= $order['ORDER']['PROPERTIES']['COMMENT'] ?></span></td>
                                </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    <? } else { ?>
                        <? if (!empty($_REQUEST['q'])) { ?>
                            <p>По вашему запросу ничего не найдено</p>
                        <? } else { ?>
                            <p>Нет заказов</p>
                        <? } ?>
                    <? } ?>
                </div>
            </div>
            <? if (!empty($arResult["NAV_STRING"])) { ?>
                <div class="pagination">
                    <div class="pagination-list">
                        <?= $arResult["NAV_STRING"]; ?>
                    </div>
                </div>
            <? } ?>
        </div>
    <? } ?>
<? } ?>
