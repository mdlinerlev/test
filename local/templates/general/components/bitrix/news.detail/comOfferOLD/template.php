<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>

<div class="popup-b2b__wrp">
    <div class="popup-b2b__head">
        <div class="popup-b2b__zag">Коммерческое предложение <?= $arResult['ID'] ?></div>
    </div>
    <?//pr($arResult['ITEMS'][43438]);?>
    <div class="popup-b2b__detail" data-id="<?= $arResult['ID'] ?>">
        <input type="hidden" id="ComOfferId" name="ID" value="<?= $arResult['ID'] ?>">
        <div class="popup-b2b__detail-table js-wrp">
            <?//pr();?>
            <div class="b2b-favorite__table" id="basket-items">
                <table>
                    <thead>
                    <tr>
                        <th style="width: 6%">Фото</th>
                        <th style="width: 30%">Название товара</th>
                        <th style="width: 10%" class="123">Цена закупки</th>
                        <th style="width: 10%">Цена</th>
                        <th style="width: 10%">Количество</th>
                        <th style="width: 10%">Сумма без НДС</th>
                        <th style="width: 10%">Сумма закупки</th>
                        <th style="width: 10%">%НДС</th>
                        <th style="width: 10%">Сумма c НДС</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arResult['ITEMS'] as $arItem) { ?>
                        <tr>
                            <td>
                                <div class="flex" style="padding-right: 15px;">
                                    <button class="close js-basketItem-del" data-id="<?= $arItem['id'] ?>">
                                        <svg class="icon icon-close ">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#close"></use>
                                        </svg>
                                    </button>
                                    <? if ($arItem['picture']) { ?>
                                        <img src="<?= CFile::GetPath($arItem['picture']) ?>"
                                             alt="<?= $arItem['name'] ?>"/>
                                    <? } ?>
                                </div>
                            </td>
                            <td><?= $arItem['name'] ?></td>
                            <td>
                                <span><?= ($arItem['prices'][$arResult['PRICE_TYPE']]) ? $arItem['prices'][$arResult['PRICE_TYPE']] : '-' ?></span>
                            </td>
                            <td>
                                <span><?= $arItem['prices'][PRICE_TYPE_DEFAULT_ID] ?></span>
                            </td>
                            <td>
                                <div class="counter">
                                    <button class="minus">-</button>
                                    <input type="text" class="count" name="BASKET_ITEM_<?= $arItem['id'] ?>"
                                           value="<?= $arItem['count'] ?>"/>
                                    <button class="plus">+</button>
                                </div>
                            </td>
                            <td>
                                <span><?= $arItem['price_sum'] ?></span>
                            </td>
                            <td>
                                <span><?= ($arItem['price_purchase']) ? $arItem['price_purchase'] : '-' ?></span>
                            </td>
                            <td>
                                <span>20%</span>
                            </td>
                            <td>
                                <span><?= $arItem['price_sum_wat'] ?></span>
                            </td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
            <div class="popup-b2b__detail-table__btn">
                <button class="button js-edit-table-popup" data-before="Редактировать" data-after="Сохранить"></button>
            </div>
            <?
            $text = '';
            foreach ($arResult['ITEMS'] as $item) {
                if($item['check'] == 1) {
                    $text.= $item['name'] . PHP_EOL;
                }
            }
            if (!empty($text)) {?>
                <div class="tab-panels">
                    <div class="basket_order__descr">
                        <div class="basket__float-comment">
                            <div class="toggled-elem"> Комментарий к заказу</div>
                            <?//pr($arResult['PROPERTIES']['COMMENT']);?>
                            <textarea class="toggled-item" name="COMMENT" cols="30" rows="3"><?=$arResult['PROPERTIES']['COMMENT']['VALUE']['TEXT']/*$text*/?></textarea>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
        <div class="popup-b2b__detail-form">
            <div class="popup-b2b__detail-form__zag">Информация о клиенте</div>
            <div class="popup-b2b__form-wrp">
                <? if ($arResult['PROPERTIES']['TYPE']['VALUE'] == 'Физическое лицо') { ?>
                    <div class="popup-b2b__form-item">
                        <label>Имя</label>
                        <input type="text" value="<?= $arResult['PROPERTIES']['CLIENT_NAME']['VALUE'] ?>" name="CLIENT_NAME"/>
                    </div>
                <? } else { ?>
                    <div class="popup-b2b__form-item">
                        <label>Наименование организации</label>
                        <input type="text" value="<?= $arResult['PROPERTIES']['CLIENT']['VALUE'] ?>" name="CLIENT"/>
                    </div>
                <? } ?>
                <div class="popup-b2b__form-item">
                    <label>Телефон</label>
                    <input type="tel" data-mask="+7 (999) 999-99-99" placeholder="+7 (___) ___-__-__"
                           value="<?= $arResult['PROPERTIES']['PHONE']['VALUE'] ?>" name="PHONE"/>
                </div>
                <div class="popup-b2b__form-item">
                    <label>Город</label>
                    <input type="text" value="<?= $arResult['PROPERTIES']['CITY']['VALUE'] ?>" name="CITY"/>
                </div>
                <div class="popup-b2b__form-item">
                    <label>Адрес</label>
                    <input type="text" value="<?= $arResult['PROPERTIES']['ADDRESS']['VALUE'] ?>" name="ADDRESS"/>
                </div>
                <div class="popup-b2b__form-item">
                    <label>Скидка</label>
                    <input type="text" value="<?= $arResult['PROPERTIES']['STOCK']['VALUE'] ?>" name="STOCK"/>
                </div>
                <div class="popup-b2b__form-item">
                    <label>Итого к оплате</label>
                    <input type="text" value="<?=$arResult['DISCOUNT_PRICE']?>" disabled/>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    imputMask();
</script>