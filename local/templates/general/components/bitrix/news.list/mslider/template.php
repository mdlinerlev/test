<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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
$this->setFrameMode(true);
?>


<div class="index-slider__slider">
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?><br/>
    <? endif; ?>
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

    $file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], ['width' => 330, 'height' => 325], BX_RESIZE_IMAGE_PROPORTIONAL, true);
    ?>
    <? if ($arItem["PROPERTIES"]["LINK"]["~VALUE"]){ ?>
    <a class="index-slider__item" href="<?= $arItem["PROPERTIES"]["LINK"]["~VALUE"] ?>">
        <? }else{ ?>
        <div class="index-slider__item">
            <? } ?>
            <img src="<?= SITE_TEMPLATE_PATH . '/preload.svg' ?>"
                 width="<?=$file['width']?>"
                 height="<?=$file['height']?>"
                 data-src="<?= $file['src'] ?>"
                 class="owl-lazy index-slider__image"
            />
            <? if ($arItem["PROPERTIES"]["LINK"]["~VALUE"]){ ?>
    </a>
    <? }else{ ?>
</div>
<? } ?>
<? endforeach; ?>
<? /*if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
                <br /><?= $arResult["NAV_STRING"] ?>
<? endif; */ ?>

</div>

