<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var \CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var array $templateData */
/** @var \CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();
?>
<form name="post" class="b2b-head" id="favorites_filter">
    <div class="b2b-head__search">
        <input type="text" name="q" placeholder="Введите название товара" value="<?= $request['q'] ?>">
        <button type="submit" form="favorites_filter">
            <svg class="icon icon-search ">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#search"></use>
            </svg>
        </button>
    </div>
    <div class="b2b-head__sort">
        <select class="styler js-set-categories" name="categories">
            <option value="0" <? if (empty($request['categories'])){ ?>class="disabled"<? } ?>>Категория товара</option>
            <? foreach ($arResult['SECTIONS'] as $arSection) { ?>
                <option value="<?= $arSection['ID'] ?>"
                        <? if ($request['categories'] == $arSection['ID']){ ?>selected<? } ?>><?= $arSection['NAME'] ?></option>
            <? } ?>
        </select>
    </div>
</form>
<div class="b2b-content__wrp">
    <? if (!empty($arResult['ITEMS'])) { ?>
        <!-- items-container -->
        <div class="b2b-favorite">
            <div class="b2b-favorite__table js-edit-wrp js-editor__coord">
                <table>
                    <thead>
                    <tr>
                        <th style="width: 6%">Фото товара</th>
                        <th style="width: 20%">Название товара</th>
                        <th style="width: 10%">Розничная цена</th>
                        <th style="width: 10%">Цена закупки</th>
                        <th style="width: 10%">Остатки</th>
                    </tr>
                    </thead>
                    <tbody data-entity="items-row">
                    <? foreach ($arResult['ITEMS'] as $item) { ?>
                        <tr id="<?= $item['ID'] ?>" data-entity="item">
                            <td>
                                <div class="checkbox js-checkbox">
                                    <input type="checkbox" name="ID" data-id="<?= $item['ID'] ?>">
                                    <label><img src="<?= $item['PREVIEW_PICTURE'] ?>"
                                                alt="<?= $item['NAME'] ?>"></label>
                                </div>
                            </td>
                            <td><a href="<?= $item['DETAIL_PAGE_URL']; ?>" style="color:black;"><?= $item['NAME'] ?></a>
                            </td>
                            <td><b><?= ($item['PRICE'][$arResult['PRICES']['DEFAULT']]) ?: '-' ?></b></td>
                            <td><?= ($item['PRICE'][$arResult['PRICES']['OPT']]) ?: '-' ?></td>
                            <td><b><?= ($item['QUANTITY'] == 0) ? '-' : $item['QUANTITY']; ?></b></td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
            <div class="b2b-editor__wrp js-edit__editor-wrp">
                <form class="b2b-editor js-edit__editor">
                    <select class="styler js-select" name="type">
                        <option>Выберите действие</option>
                        <option value="basket">Добавить в корзину</option>
                        <option value="delFavorite">Удалить</option>
                    </select>
                    <button class="button" type="submit">Применить</button>
                    <div class="checkbox js-checkbox">
                        <input type="checkbox" name="all" id="all">
                        <label>Для всех</label>
                    </div>
                </form>
            </div>
        </div>
        <!-- items-container -->
        <? if (!empty($arResult['NAV_STRING'])) { ?>
            <div class="pagination">
                <div data-pagination-num="<?= $navParams['NavNum'] ?>" class="pagination-list">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            </div>
        <? } ?>
    <? } else { ?>
        <? if (!empty($request['q'])) { ?>
            <p>По вашему запросу ничего не найдено</p>
        <? } else { ?>
            <p>Нет избранных</p>
        <? } ?>
    <? } ?>
</div>

