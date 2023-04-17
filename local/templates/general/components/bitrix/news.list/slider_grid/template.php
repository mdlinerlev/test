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
if(empty($arResult["ITEMS"])) return;
?>
<div class="banner-w-slider__aside">
    <div class="banner-w-slider__grid">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="banner-w-slider__cell">
                <?if($arItem["PROPERTIES"]["LINK"]["~VALUE"]):?>
                <a class="banner-w-slider__item" href="<?= $arItem["PROPERTIES"]["LINK"]["~VALUE"] ?>">
                    <?else:?>
                    <span class="banner-w-slider__item">
                    <?endif;?>
                        <div class="banner-w-slider__img">
                            <img src="<?= SITE_TEMPLATE_PATH . '/preload.svg'?>" data-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" class="owl-lazy" alt="">
                        </div>
                        <div class="banner-w-slider__name"><?= $arItem['NAME']?></div>
                    <?if(!$arItem["PROPERTIES"]["LINK"]["~VALUE"]):?>
                    </span>
                    <?else:?>
                </a>
            <?endif;?>
            </div>
        <? endforeach; ?>
    </div>
</div>
