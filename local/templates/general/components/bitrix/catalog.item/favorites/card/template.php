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
$url = $item['DETAIL_PAGE_URL'];
$id = $item['ID'];
if (!empty($item['OFFER_SELECTED'])) {
    $item = $item['OFFER_SELECTED'];
} elseif(!empty($item['OFFERS'])) {
    $item = $item['OFFERS'][array_key_first($item['OFFERS'])];
}
?>
<td>
    <div class="checkbox js-checkbox">
        <input type="checkbox" name="ID" data-id="<?= $id ?>">
        <label>
            <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>">
        </label>
    </div>
</td>
<td><a href="<?=$url;?>" style="color:black;"><?= $item['NAME'] ?></a></td>
<td><b><?= (!empty($item['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'])) ? $item['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'] : $item['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT'] ?></b></td>
<td><?= (isset($item['PRICES'][$arResult['PRICE']])) ? $item['PRICES'][$arResult['PRICE']]['PRINT_DISCOUNT_VALUE_VAT'] : '-' ?></td>
<td><b><?= ($item['PRODUCT']['QUANTITY'] == 0)  ? '-' : $item['PRODUCT']['QUANTITY']; ?></b></td>