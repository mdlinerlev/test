<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="bx_searche">
    <? foreach ($arResult["CATEGORIES"] as $i => $arItem) { ?>
        <div class="bx_item_block others_result">
            <div class="bx_img_element"></div>
            <div class="bx_item_element">
                <a href="<? echo $arItem["SECTION_PAGE_URL"] ?>"><b><? echo $arItem["NAME"] ?></b></a>
            </div>
            <div style="clear:both;"></div>
        </div>
    <? } ?>
    <? foreach ($arResult["ELEMENTS"] as $i => $arItem) { ?>
        <div class="bx_item_block">
            <? if (is_array($arItem["PREVIEW_PICTURE"])): ?>
                <div class="bx_img_element">
                    <div class="bx_image"
                         style="background-image: url('<? echo $arItem["PREVIEW_PICTURE"]["src"] ?>')"></div>
                </div>
            <? endif; ?>
            <div class="bx_item_element">
                <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>"><? echo $arItem["NAME"] ?></a>
                <?
                $arPrice = $arItem["PRICE"];
                if ($arPrice["DISCOUNT_PRICE"] < $arPrice["BASE_PRICE"]):?>
                    <div class="bx_price">
                        <?= $arPrice["DISCOUNT_PRICE"] ?>
                        <span class="bx_price"><?= $arPrice["BASE_PRICE"] ?></span>
                    </div>
                <? else: ?>
                    <div class="bx_price"><?= $arPrice["BASE_PRICE"] ?></div>
                <?endif;
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    <? } ?>
    <? if (empty($arResult["CATEGORIES"]) and empty($arResult["ELEMENTS"])) { ?>
        <div class="bx_item_block">
            <div class="bx_item_block others_result">
                <div class="bx_item_element">
                    <br> <b>Нет результатов</b>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    <? } ?>
</div>