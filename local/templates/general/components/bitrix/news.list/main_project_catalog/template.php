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

<?if(!empty($arResult["ITEMS"])):?>
    <section class="index-types<?if($arParams['WITH_ICON'] == "Y") {?> container-gray<?}?>">
        <div class="types content-container">
            <?if(!empty($arParams['TITLE_NAME']) && $arParams['WITH_ICON'] == "Y"):?>
                <div class="container-gray__header">
                    <h2 class="container-gray__title">
                        <?= $arParams['TITLE_NAME']?>
                    </h2>
                </div>
            <?endif;?>
            <?if($arParams['WITH_ICON'] !== "Y") {?>
                <div class="project__box project__box-slider">
                    <?
                    foreach ($arResult["ITEMS"] as $arItem):
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <?if(!empty($arItem["PREVIEW_PICTURE"]["SRC"])) {?>
                        <a href="<?= $arItem['PROPERTIES']["LINK"]["VALUE"] ?>" class="project__item lazy" data-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" style="background-image: url(<?= SITE_TEMPLATE_PATH . '/preload.svg'?>);">
                            <div class="project__text">
                                <?= $arItem["NAME"]?>
                                <!--                    <div class="project_arrow"><svg class="icon icon-arrow-circle"><use xlink:href="#icon-arrow-circle"></use></svg></div>-->
                            </div>
                        </a>
                    <?}?>
                    <? endforeach;  ?>
                </div>
            <? } else { ?>
                <div class="popular-categories-main_icons">
                    <?
                    foreach ($arResult["ITEMS"] as $arItem):
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <?if(!empty($arItem['PROPERTIES']["ICON"]["VALUE"])) {?>
                        <a href="<?= $arItem['PROPERTIES']["LINK"]["VALUE"] ?>" class="popular-categories-main_icons__item">
                            <img src="<?= CFile::GetPath($arItem['PROPERTIES']["ICON"]["VALUE"]) ?>" alt="<?= $arItem["NAME"]?>" class="popular-categories-main_icons__item--image">
                            <p class="popular-categories-main_icons__item--text">
                                <?= $arItem["NAME"]?>
                            </p>
                        </a>
                    <?}?>
                    <? endforeach;  ?>
                </div>
            <?}?>
        </div>
    </section>
<?endif;?>
