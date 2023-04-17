<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="promo-banner" style="background-color: <?= $arParams['BACKGROUND_COLOR']?>;">
    <div class="promo-banner__wrapper  content-container">
        <a class="promo-banner__link" href="<?=$arParams["LINK_FOR_PROMOTIONAL"]?>"></a>
        <div class="promo-banner__timer">
            <div class="js-countdown-simple" style="background-color: <?= $arParams["BACK_COLOR"]?>;" data-date="<?=$arResult["DATE_BEFORE"]?>"></div>
        </div>
        <div class="promo-banner__content">
            <div class="promo-banner__title" style="color:<?=$arParams['TITLE_COLOR']?>;"><?=$arParams['MAIN_TEXT']?></div>
            <div class="promo-banner__text" style="color:<?=$arParams['DESCRIPTION_COLOR']?>;"><?=$arParams['ADDITIONAL_TEXT']?></div>
        </div>
    </div>
</div>

<style>
    .countdown-section:not(:last-child)::after {
        background-color: <?= $arParams["SLESH_COLOR"]?>;
    }
</style>