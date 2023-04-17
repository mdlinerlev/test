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
$this->setFrameMode(true);

if(empty($arResult["ITEMS"]))
    return;
?>


<section class="index-brands">
    <?if(!empty($arParams['TITLE_NAME'])):?>
        <div class="container-gray__header">
            <div class="container-gray__title">
                <?= $arParams['TITLE_NAME']?>
                <?if(!empty( $arParams['TITLE_SUB_NAME'])):?>
                    <div class="container-gray__subtitle"><?= $arParams['TITLE_SUB_NAME']?></div>
                <?endif;?>
            </div>
            <a href="<?= $arParams['ALL_LINK']?>" class="container-gray__all"><?=$arParams['ALL_TEXT']?></a>
        </div>

    <?endif;?>
    <div class="projects">
        <div class="project__box project__box--real project__box-slider">
            <?
            foreach ($arResult["ITEMS"] as $arItem):
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));


                $image = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], ['width'=>426, 'height'=>290], BX_RESIZE_IMAGE_PROPORTIONAL, true);
                ?>
                <a href="<?= $arItem["DETAIL_PAGE_URL"]?>" class="our_project">
                    <div class="our_project__img">
                        <img class="our_project__image owl-lazy"
                             src="<?= SITE_TEMPLATE_PATH . '/preload.svg'?>"
                             width="<?=$image['width']?>"
                             height="<?=$image['height']?>"
                             data-src="<?= $image['src'] ?>"
                             alt="<?=htmlspecialcharsEx($arItem['NAME'])?>"/></div>
                    <div class="our_project__title"><?= $arItem["NAME"]?></div>
                </a>
            <? endforeach; ?>
        </div>
    </div>
</section>

