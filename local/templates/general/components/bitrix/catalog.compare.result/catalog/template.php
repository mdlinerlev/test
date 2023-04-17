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
$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");
?>
<?if($_GET["action"]=="DELETE_FROM_COMPARE_RESULT" && $_GET["all"]=="Y")
    {
    unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]);
    header('location: http://belwooddoors.by/catalog/compare/');
    }elseif($_REQUEST["action"]=="DELETE_FROM_COMPARE_LIST" && $id == 0) {
        unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]);
    
    } 
 
    ?>
<?
CModule::IncludeModule('iblock');
$objCatalog = new CCatalogRU();
$counter = $arParams['COUNTER'];
$sections = $objCatalog->compareProps($arResult['ITEMS']);
?>
<section class="page-title-section">
          <div class="content-container">
            <div class="page-title">
              <h1 class="page-title__title">Сравнение
              </h1>
            </div>
          </div>
        </section>
<section class="compare">
    <div class="content-container">
           
       <div class="compare__categories">
              <div class="compare-categories__inner">
                <a class="compare-categories__button compare-categories__button--toggler button"><span>Межкомнатные двери</span>
                </a>
                <div class="compare-categories__menu">
                  <div class="compare-categories__list">
          <?  foreach ($sections as $compare_section){
              $compare_section_name = GetIBlockSection($compare_section, 'catalog');
              ?>
                        <a class="compare-categories__button compare-categories__button--category button"><span><?=$compare_section_name["NAME"]?></span>
                    </a>
          <?}?>
                   </div>
                </div>
              </div>
       </div>
                 <div class="compare__tabs">
                    <div class="compare-tabs__menu">
                        <? if ($arResult["DIFFERENT"]): ?>

                            <a class="compare-tabs-menu__item" href="<?= htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N", array("DIFFERENT"))) ?>" rel="nofollow" class="cs_links"><span><?= GetMessage("CATALOG_ALL_CHARACTERISTICS") ?></span></a>

    <? else: ?>

                            <a class="compare-tabs-menu__item active" href="<?= htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N", array("DIFFERENT"))) ?>" rel="nofollow" class="cs_links active"><span><?= GetMessage("CATALOG_ALL_CHARACTERISTICS") ?></span></a>

    <? endif ?>

                        <? if (!$arResult["DIFFERENT"]): ?>

                            <a class="compare-tabs-menu__item" href="<?= htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y", array("DIFFERENT"))) ?>" rel="nofollow" class="cs_links"><span><?= GetMessage("CATALOG_ONLY_DIFFERENT") ?></span></a>

                        <? else: ?>

                            <a class="compare-tabs-menu__item active" href="<?= htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y", array("DIFFERENT"))) ?>" rel="nofollow" class="cs_links active"><span><?= GetMessage("CATALOG_ONLY_DIFFERENT") ?></span></a>

    <? endif ?>
                    </div>
                  
                    
             <a href="<?//= CURPAGE ?>?action=DELETE_FROM_COMPARE_RESULT&all=Y&IBLOCK_ID=<?=$arParams['IBLOCK_ID']?>&ajax_mode=Y" class="compare-tabs__clear-link">
    <?= GetMessage("COMPARE_DELETE_ITEMS") ?>
                    </a>
        
        
        <?
//получаем список корневых разделов с разбивкой товаров
        
# получаем связь между названием свойства и его кодом
        $allProps = $objCatalog->getPropsName($arParams['IBLOCK_ID']);
        ?>
                                  
        <?
        foreach ($sections as $idSection) {
            $sectionItems[$idSection]['SECTION']['ID'] = $idSection;
            foreach ($arResult['ITEMS'] as $arItem) {
                if ($arItem['IBLOCK_SECTION_ID'] == $idSection) {
                    $sectionItems[$idSection]['ITEMS'][] = $arItem;
                  
                }
            }
        }
        ?>
   
        <?
        foreach ($sections as $idSection) {
            foreach (CIBlockSectionPropertyLink::GetArray($arParams['IBLOCK_ID'], $idSection) as $PID => $prop) {
                #$code = $code ? $code : $PID;
                $code = $allProps[$prop['PROPERTY_ID']];
                if ($code) {
                    //if( !in_array($PID, $excludeProps) ){
                    $props[$idSection][$code] = $prop;
                    
                    //}
                }
            }
        }
        ?>


        <?
        $i = 1;
        foreach ($sectionItems as $idSection => $section) {
            ?>

            <?
            $elCount = count($section['ITEMS']);
            ?>

    <?
    $delUrlID = "";
    foreach ($section['ITEMS'] as $arItem) {
        $delUrlID .= "&ID[]=" . $arItem['ID'];
    }
    ?> 
        

    
           

                   
                    <div class="compare__content">
                            <div class="compare-content__tab <?if ($i == 1){?>active<?}?>">
                                <div class="compare-content__controls">


                                    <div class="compare-content-controls__items-count">По данной категории в сравнении <?= $elCount ?> <?= plural($elCount, 'товар', 'товара', 'товаров') ?></div>
                                    <div class="compare-content-controls__slider-scroll">
                                        <div class="compare-content-controls__scroller-container">
                                            <div class="compare-content-controls__scroller"></div>
                                        </div>
                                    </div>
                                    <div class="compare-tabs-controls__slider-arrows"></div>
                                </div>

                    <div class="compare-content__slider">
                        <div class="compare-content-slider__parameters-titles-container">
                        <div class="compare-content-slider__parameters compare-content-slider__parameters--titles">
                            <?
                            $j = 1;
                            //св-ва для текуей группы товаров
                            $propList = $props[$idSection];
//				$arResult["SHOW_PROPERTIES"] = $props[$idSection];
                            foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) {
                                if (!eRU($propList[$code])) {
                                    continue;
                                }

                                $arCompare = Array();
                                foreach ($section['ITEMS'] as $arElement) {
                                    $arPropertyValue = $arElement["PROPERTIES"][$code]["VALUE"];
                                    if (is_array($arPropertyValue)) {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    if (trim($arPropertyValue)) {
                                        $arCompare[] = $arPropertyValue;
                                    }
                                }

                                $diff = false;
                                $diff = (count(array_unique($arCompare)) > 1 ? true : false);

                                $arProperty['NAME'] = array_diff(explode('#', $arProperty['NAME']), array(''));
                                $arProperty['NAME'] = $arProperty['NAME'][0];

                                if (
                                        (!$arResult['DIFFERENT'] || $diff || count($arCompare) < $elCount) &&
                                        eRU($arCompare)
                                ) {
                                    ?>
                                    <div  data-param="<?= $j ?>" class="compare-content-slider__parameter compare-content-slider__parameter--title"><?= $arProperty['NAME'] ?></div>
                                <? $j++;
                            }
                            ?>
                        <? } ?>
                        </div>
                    </div>
                    
                    <div class="compare-content-slider__inner">

                        <? #pRU($section['ITEMS'], 'all')?>

                        <? foreach ($section['ITEMS'] as $arItem) { ?>

                            <?
                            $price = $arItem['MIN_PRICE'];
                            $priceBase = $price['VALUE'];
                            $priceDiscount = $price['DISCOUNT_VALUE'];

                            if ($arItem["PREVIEW_PICTURE"]["ID"]) {
                                $img = i($arItem["PREVIEW_PICTURE"]["ID"], 174, 174);
                            } elseif ($arItem["DETAIL_PICTURE"]["ID"]) {
                                $img = i($arItem["DETAIL_PICTURE"]["ID"], 174, 174);
                            } else {
                                $img = "";
                            }
                            ?>


                            <div class="compare-content-slider__item">
                                <div class="compare-content-slider__top">
                                    <div class="compare-content-slider__image-container">
                                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="compare-content-slider-image-container__image-link">
                                            <img src="<?= CFile::GetPath($arItem["OFFER_FIELDS"]["DETAIL_PICTURE"]); ?>" class="compare-content-slider-image-container__image" />
                                        </a>
                                    </div>
                                    <div class="compare-content-slider__title">
                                        <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" class="compare-content-slider-title__link"><?= $arItem['NAME']; ?></a>
                                    </div>
                                        <?/* if ($arItem['ARTICUL']) { ?>
                                        <div class="catalog-item__number">Артикул: <span><?= $arItem['ARTICUL'] ?></span></div>
                                        <? } */?>
                                    <div class="compare-content-slider__price">
                                        <? if ($priceDiscount) { ?>
            <? if ($priceDiscount < $priceBase) { ?>
                                                <div class="compare-content-slider-price__discount"><?= SaleFormatCurrency($priceDiscount, MAIN_CURRENCY, true) ?> <span class="rouble">Р</span></div>
                                                <div class="compare-content-slider-price__base"><span class="compare-content-slider-price__number"><?= SaleFormatCurrency($priceBase, MAIN_CURRENCY, true) ?>&nbsp;</span> <span class="rouble">Р</span></div>
                                        <? } else {
                                            ?>
                                                <div class="compare-content-slider-price__discount"><?= SaleFormatCurrency($priceDiscount, MAIN_CURRENCY, true) ?> <span class="rouble">Р</span></div>
                                        <? } ?>
                                    <? } ?>
                                    </div>
                                    <a href="<?= $APPLICATION->GetCurPage() ?>?action=DELETE_FROM_COMPARE_RESULT&IBLOCK_ID=<?= $arParams['IBLOCK_ID'] ?>&ID[]=<?= $arItem['ID'] ?>" title="Удалить из сравнения" class="compare-content-slider__remove-link"></a>
                                </div>
                                <div class="compare-content-slider__parameters">
                                    <?
                                    $j = 1;
                                    $propList = $props[$idSection];
                                    foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) {

                                        if (!eRU($propList[$code])) {
                                            continue;
                                        }
                                        $arPropertyValue_ = $arItem["PROPERTIES"][$code]["VALUE"];
                                        if (is_array($arPropertyValue_)) {
                                            sort($arPropertyValue_);
                                            $arPropertyValue_ = implode(" / ", $arPropertyValue_);
                                        }

                                        if ($arPropertyValue_ == 'Да' || $arPropertyValue_ == 'Yes') {
                                            $arPropertyValue_ = '<span class="dot">&bull;</span>';
                                        }

                                        $arCompare = array();
                                        foreach ($section['ITEMS'] as $arElement_) {
                                            $arPropertyValue = $arElement_["PROPERTIES"][$code]["VALUE"];
                                            if (is_array($arPropertyValue)) {
                                                sort($arPropertyValue);
                                                $arPropertyValue = implode(" / ", $arPropertyValue);
                                            }
                                            if (trim($arPropertyValue)) {
                                                $arCompare[] = $arPropertyValue;
                                            }
                                        }

                                        $diff = false;
                                        $diff = (count(array_unique($arCompare)) > 1 ? true : false);
                                        if (
                                                ($diff || !$arResult["DIFFERENT"]) &&
                                                eRU($arCompare)
                                        ) {
                                            ?>

                                            <div data-code="<?= $code ?>" data-param="<?= $j ?>" class="compare-content-slider__parameter">
                                            <?= $arPropertyValue_ ? $arPropertyValue_ : '-'; ?>
                                            </div>

                                            <? $j++; ?>

                
            <? }elseif ((!$arResult['DIFFERENT'] || $diff || count($arCompare) < $elCount) &&
                                        eRU($arCompare)){ ?>
                                    <div data-code="<?= $code ?>" data-param="<?= $j ?>" class="compare-content-slider__parameter">
                                            <?= $arPropertyValue_ ? $arPropertyValue_ : '-'; ?>
                                            </div>
                <?}?>
        <? } ?>
                                </div>
                            </div>
    <? } ?>
                    </div>
                </div>
            </div>
         
    </div>
    <?
    $i++;
}?> 
    </div>
       
    </div>
    </section>
