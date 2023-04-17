<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if (!empty($arResult['ITEMS'])) {
    $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
    $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
    $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>
    <section class="related">
        <div class="related__container">

            <?if(!empty($arParams['TITLE_BLOCK'])):?>
                <h4 class="related__title"><?=$arParams['TITLE_BLOCK']?></h4>
            <?endif;?>

            <div class="related__slider owl-carousel--arrow  owl-carousel--dots">

                <?
                foreach ($arResult['ITEMS'] as $key => $arItem) {
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                    $strMainID = $this->GetEditAreaId($arItem['ID']);
                    $productTitle = (
                    isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                        ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                        : $arItem['NAME']
                    );
                    $imgTitle = (
                    isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                        ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                        : $arItem['NAME']
                    );
                    ?>
                    <?$confDoor = 'Распашная двойная';?>
                        <?//pr($arItem);?>
                    <div class="related__slider-item <?=($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arItem['DISPLAY_PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1? 'double_image' : '');?>"  id="<?= $strMainID; ?>">
                        <a href="<?= $arResult['DETAIL_PAGE_URL'][$arItem['ID']]; ?>">
                            <?if($arItem['DISPLAY_PROPERTIES']['CONFIGURATION']['VALUE'] == $confDoor && $arItem['DISPLAY_PROPERTIES']['DOUBLE_IMAGE']['VALUE'] == 1) {?>
                                <img src="<?= $arItem['PICTURE'] ? $arItem['PICTURE']['src'] : SITE_TEMPLATE_PATH.'/preload.svg'?>" alt="<?= $imgTitle?>" class="related__slider-img" width="125" height="260"/>
                            <?}?>
                            <img src="<?= $arItem['PICTURE'] ? $arItem['PICTURE']['src'] : SITE_TEMPLATE_PATH.'/preload.svg'?>" alt="<?= $imgTitle?>" class="related__slider-img"/>
                            <div class="catalog-item__badge-container">
                                <?
                                foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp) {

                                    if (($arOneProp['CODE'] == 'SALELEADER') && ($arOneProp['VALUE'])) {
                                        ?>
                                        <div class="catalog-item__label catalog-item__label--dark"><?= GetMessage('CT_BCS_SALELEADER')?></div>
                                        <?
                                    } elseif (($arOneProp['CODE'] == 'NEWPRODUCT') && ($arOneProp['VALUE'])) {
                                        ?>
                                        <div class="catalog-item__label"><?= GetMessage('CT_BCS_NEWPRODUCT')?></div>
                                    <? }
                                }
                                ?>
                            </div>
                        </a>
                    </div>
                <?
                }
                ?>
            </div>
        </div>
    </section>
    <?
}
?>