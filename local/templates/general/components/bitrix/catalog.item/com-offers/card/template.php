<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

?>
<td>
    <div class="flex">
        <div class="checkbox js-checkbox _empty">
            <input type="checkbox" name="ID" data-id="<?= $item['ID'] ?>">
            <label></label>
        </div>
        <a class="b2b-table__order-num ajax-form js-offer-item"
           data-href="/ajax/reAjax.php?action=modalComOfferDetail&ID=<?= $item['ID'] ?>"
           data-id="<?= $item['ID'] ?>"
        ><?= $item['ID'] ?></a>
    </div>
</td>
<td>
    <input type="text" name="DATE" value="<?= $item['PROPERTIES']['DATE']['VALUE'] ?>">
</td>
<?if ($item['PROPERTIES']['TYPE']['VALUE'] == 'Физическое лицо') { ?>
    <td>
        <input type="text" name="CLIENT_NAME" value="<?= $item['PROPERTIES']['CLIENT_NAME']['VALUE'] ?>">
    </td>
<? } else { ?>
    <td>
        <input type="text" name="CLIENT" value="<?= $item['PROPERTIES']['CLIENT']['VALUE'] ?>">
    </td>
<? } ?>
<td>
    <input type="text" name="PHONE" value="<?= $item['PROPERTIES']['PHONE']['VALUE'] ?>">
</td>
<td>
    <input type="text" name="CITY" value="<?= $item['PROPERTIES']['CITY']['VALUE'] ?>">
</td>
<td>
    <input type="text" name="ADDRESS" value="<?= $item['PROPERTIES']['ADDRESS']['VALUE'] ?>">
</td>
<td>
    <span><?= $item['TOTAL_PRICE'] ?></span>
</td>
<td>
    <span><?= $item['STOCK'] ?></span>
</td>
<td>
    <span><?= $item['DISCOUNT_PRICE'] ?></span>
</td>
<td>
    <span><?= CurrencyFormat($item['PROPERTIES']['SUM_PURCHASE']['VALUE'], 'RUB')?></span>
</td>
<td>
    <select class="styler" name="STATUS">
        <? foreach ($arResult['PROPERTY_LIST_VAL'][$item['PROPERTIES']['STATUS']['CODE']] as $arStatusVal) { ?>
            <option value="<?= $arStatusVal['ID'] ?>"
                    <? if ($arStatusVal['ID'] == $item['PROPERTIES']['STATUS']['VALUE_ENUM_ID']){ ?>selected<? } ?>><?= $arStatusVal['VALUE'] ?></option>
        <? } ?>
    </select>
</td>
<td>
    <select class="styler" name="TYPE">
        <? foreach ($arResult['PROPERTY_LIST_VAL'][$item['PROPERTIES']['PAYMENT_TYPE']['CODE']] as $arStatusVal) { ?>
            <option value="<?= $arStatusVal['ID'] ?>"
                    <? if ($arStatusVal['ID'] == $item['PROPERTIES']['PAYMENT_TYPE']['VALUE_ENUM_ID']){ ?>selected<? } ?>><?= $arStatusVal['VALUE'] ?></option>
        <? } ?>
    </select>
</td>