<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var string $strElementEdit */
/** @var string $strElementDelete */
/** @var array $arElementDeleteParams */
/** @var array $skuTemplate */
/** @var array $templateData */
global $APPLICATION;

if(empty($arResult['ITEMS']))
    return;

?>

<section class="items-slider items-slider--similar">
    <div class="content-container">
        <div class="items-slider__title-container">
            <div class="items-slider__title"><?= $arParams['TITLE_HEADER'] ?></div>
        </div>
        <div class="items-slider__slider">
        <?
        foreach ($arResult['ITEMS'] as $key => $arItem) {

            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($arItem['ID']);

            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID . '_pict',
                'SECOND_PICT' => $strMainID . '_secondpict',
                'MAIN_PROPS' => $strMainID . '_main_props',
                'QUANTITY' => $strMainID . '_quantity',
                'QUANTITY_DOWN' => $strMainID . '_quant_down',
                'QUANTITY_UP' => $strMainID . '_quant_up',
                'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                'BUY_LINK' => $strMainID . '_buy_link',
                'BASKET_ACTIONS' => $strMainID . '_basket_actions',
                'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
                'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
                'COMPARE_LINK' => $strMainID . '_compare_link',
                'PRICE' => $strMainID . '_price',
                'DSC_PERC' => $strMainID . '_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
                'PROP_DIV' => $strMainID . '_sku_tree',
                'PROP' => $strMainID . '_prop_',
                'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
            );

            $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
            $productTitle = (
                    isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '' ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']
                    );
            $imgTitle = (
                    isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != '' ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] : $arItem['NAME']
                    );

            $minPrice = false;
            if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
                $minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
            ?>

            <div class="catalog-item">
                <div class="catalog-item__inner" id="<? echo $strMainID; ?>">
                    <div class="catalog-item__image-container">
                        <div>
                            <?$file = CFile::ResizeImageGet($arItem['OFFERS'][0]['DETAIL_PICTURE'], array('width'=>135,'height' => 280), BX_RESIZE_IMAGE_PROPORTIONAL   , true);?>
                    <a id="<? echo $arItemIDs['PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL'].'#offer'.$arItem['OFFERS'][0]['ID']; ?>" class="catalog-item__image-link" title="<? echo $imgTitle; ?>">
                        <img  src="<?= SITE_TEMPLATE_PATH ?>/preload.svg"  data-src="<? echo $file['src']; ?>" class="catalog-item__image">
                        
                        <?
           /* if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                ?>
                            <div id="<? echo $arItemIDs['DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
        <?
    }
    /*if ($arItem['LABEL']) {
        ?>
                            <div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
                            <?
                        }*/
                        ?>
                    </a>
                            </div>
                        </div>
                    
                        <?
                        if ($arItem['SECOND_PICT']) {
                            ?>
                     <?$file_sec = CFile::ResizeImageGet($arItem['OFFERS'][0]['DETAIL_PICTURE'], array('width'=>135,'height' => 280), BX_RESIZE_IMAGE_PROPORTIONAL   , true);?>
                        <a id="<? echo $arItemIDs['SECOND_PICT']; ?>" href="<? echo $arItem['DETAIL_PAGE_URL'].'#offer'.$arItem['OFFERS'][0]['ID']; ?>" class="bx_catalog_item_images_double" style="background-image: url('<?
                            echo (
                            !empty($file_sec['src']) ? $file_sec['src'] : $file['src']
                            );
                            ?>')" title="<? echo $imgTitle; ?>">
                        <?/*
                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                            ?>
                                <div id="<? echo $arItemIDs['SECOND_DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<? echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
                            <?
                        }
                        if ($arItem['LABEL']) {
                            ?>
                                <div class="bx_stick average left top" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $arItem['LABEL_VALUE']; ?></div>
                               <?
                           }
                           */?>
                        </a>
                            <?
                        }
                        ?>
                    <div class="catalog-item__title-container"><a class="catalog-item__title" href="<? echo $arItem['DETAIL_PAGE_URL'].'#offer'.$arItem['OFFERS'][0]['ID']; ?>" title="<? echo $productTitle; ?>"><? echo $productTitle; ?></a></div>
                    <div class="catalog-item__price">
                        
                        <?
                        if (!empty($minPrice)) {
                            echo '<div class="catalog-item-price__discount">'.$minPrice['PRINT_DISCOUNT_VALUE'].'</div>';
                        }
                        unset($minPrice);
                        ?>
                        
                    </div>

                </div>
                 
            </div><?

                }
                ?>
    </div>
</section>
