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

//pr($arResult['PROPERTIES']);
?>
<?php //pr($arResult['ITEMS']);?>
<table style="border: 0;">
    <tbody>
    <tr>
        <td style="width: 80%;border: 0;">
            <p>
                <?= $arResult['REQUISITS']['NAME'] ?><br>
                <?= ($arResult['REQUISITS']['LAW_ADDRESS']) ? 'Юр.адреc: ' . $arResult['REQUISITS']['LAW_ADDRESS'] : '' ?>
                <br>
                <?= ($arResult['REQUISITS']['INN']) ? 'ИНН ' . $arResult['REQUISITS']['INN'] : '' ?><?= ($arResult['REQUISITS']['KPP']) ? ' КПП ' . $arResult['REQUISITS']['KPP'] : '' ?>
                <br>
                <?= ($arResult['REQUISITS']['PC']) ? 'р/с ' . $arResult['REQUISITS']['PC'] : '' ?><?= ($arResult['REQUISITS']['BANK_NAME']) ? ' в ' . $arResult['REQUISITS']['BANK_NAME'] : '' ?>
                <br>
                <?= ($arResult['REQUISITS']['CA']) ? ' к/с ' . $arResult['REQUISITS']['CA'] : '' ?> <?= ($arResult['REQUISITS']['BIC']) ? ' БИК ' . $arResult['REQUISITS']['BIC'] : '' ?>
                <br>
            </p>
        </td>
        <td style="width: 20%;text-align: right;border: 0;">
            <img src="<?=getBase64($_SERVER['DOCUMENT_ROOT'] . $arResult['REQUISITS']['IMAGE'])?>"/>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px 0;font-size: 20px;text-align: center;border: 0;" colspan="2">
            <b>Коммерческое предложения <?= $arResult['ID'] ?> от <?= $arResult['PROPERTIES']['DATE']['VALUE'] ?>г</b>
        </td>
    </tr>
    </tbody>
</table>
<table style="border: 0; margin-bottom: 10px">
    <tr>
        <td style="border: 0;font-size: 14px;padding-bottom: 0;"
            data-cont-val="text-before"><?= str_replace("\n", '<br>', $arResult['PROPERTIES']['TABLE_TEXT_1']['~VALUE']['TEXT']) ?></td>
    </tr>
</table>
<table style="border: 0; border-collapse: collapse; margin-bottom: 10px">
    <thead>
    <tr>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">№</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Наименование</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Кол-во</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Ед.</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Цена</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Сумма</th>
        <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Общая сумма</th>
    </tr>
    </thead>
    <tbody>
    <?
    $key = 0;
    foreach ($arResult['ITEMS'] as $arItem) {
        ?>
        <tr>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= ++$key ?></td>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= $arItem['name'] ?></td>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= $arItem['count'] ?></td>
            <td style="border: 0;font-size: 14px;padding:3px;border:1px solid #000"> шт.</td>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= $arItem['price'] ?></td>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= getWatPrice($arItem['price_sum_val'])['PRICE'] ?></td>
            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= $arItem['price_sum'] ?></td>
        </tr>
    <? } ?>
    <tr>
        <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right">Итого</td>
        <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= $arResult['PRICE_FORMATED'] ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right"><b>Скидка (%)</b></td>
        <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= intval($arResult['PROPERTIES']['STOCK']['VALUE']) ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right"><b>Итого со скидкой</b></td>
        <td style="border: 0;font-size: 14px;padding: 5px;border:1px solid #000"><?= $arResult['PRICE_DISCOUNT'] ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border: 0;font-size: 14px;padding: 5px;text-align: right">НДС включен в сумму</td>
        <td style="border: 0;font-size: 14px;padding: 5px;border:1px solid #000"><?= getWatPrice($arResult['WAT_VALUE'])['PRICE'] ?></td>
    </tr>
    </tbody>
</table>
<? if (!empty($arResult['PICTURES'])) { ?>
    <table style="border: 0; text-align: center">
        <? foreach ($arResult['PICTURES'] as $arRow) { ?>
            <tr>
                <? foreach ($arRow as $key => $picture) { ?>
                    <td style="border: 0;">
                        <img src="<?=getBase64($_SERVER['DOCUMENT_ROOT'] . $picture)?>"
                             width="80"/>
                        <p><?= $arResult['ITEMS'][$key]['name'] ?></p>
                    </td>
                <? } ?>
            </tr>
        <? } ?>
    </table>
<? } ?>
<table style="border: 0; margin-bottom: 10px">
    <tr>
        <td style="border: 0;font-size: 14px;padding-bottom: 0;"
            data-cont-val="text-after"><?= str_replace("\n", '<br>', $arResult['PROPERTIES']['TABLE_TEXT_2']['~VALUE']['TEXT']) ?></td>
    </tr>
</table>
<table style="border: 0;">
    <tr>
        <td style="border: 0;font-size: 14px;padding-bottom: 0;"
            data-cont-val="suggest"><?= str_replace("\n", '<br>', $arResult['PROPERTIES']['SIGNATURE']['~VALUE']) ?></td>
    </tr>
</table>
