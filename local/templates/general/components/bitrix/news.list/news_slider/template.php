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
?>

<div class="index-news">
    <div class="container-gray__header">
        <div class="container-gray__title">Новости компании</div>
        <a class="container-gray__all" href="<?= SITE_DIR ?>about/news/">все новости</a>
    </div>
    <div class="index-news__slider">
        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <article class="index-news__item">
                <? $file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ['width' => 426, 'height' => 290], BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="index-news__image-link">
                    <img alt="<?=htmlspecialcharsEx($arItem['NAME'])?>"
                         src="<?= SITE_TEMPLATE_PATH . '/preload.svg'?>"
                         width="<?=$file['width']?>"
                         height="<?=$file['height']?>"
                         data-src="<?= $file["src"] ?>"
                         class="owl-lazy  index-news__image"
                    >
                </a>
                <div class="index-news__date">
                    <?= $arItem["DISPLAY_ACTIVE_FROM"]; ?>
                </div>
                <div class="index-news__title">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="index-news__title-link"><? echo $arItem["NAME"] ?></a>
                </div>

                <!--			<div class="index-news__text">-->
                <? //= trim(TruncateText($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"])) ?><!--</div>-->
                <!--			<a href="--><? //= $arItem["DETAIL_PAGE_URL"] ?><!--" class="index-news__link">Подробнее</a>-->
            </article>

        <? endforeach; ?>

    </div>
</div>