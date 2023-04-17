<?
if (empty($arResult["MENU"])) {
    return;
}
//UF_NOFOLOW
//UF_TYPE
//  LINE       = 1
//  IMG_COLUMN = 2
//  COLUMN_IMG = 3
//  TABLE = 4
$curUrl = $APPLICATION->GetCurDir();
?>

<nav class="header-catalog-menu__container header-catalog-menu__container--level1" id="cont_catalog_menu_XEVOpk">
    <ul class="header-catalog-menu__list header-catalog-menu__list--level1" id="ul_catalog_menu_XEVOpk">
        <? foreach ($arResult["MENU"][0] as $firstSectionID => $section): ?>
            
            <?
            if(!\Bitrix\Main\Engine\CurrentUser::get()->getId() && $section['UF_AUTH'] == 1){
                continue;
            }
            
            $isSecondSection = !empty($arResult["MENU"][$firstSectionID]) ? $arResult["MENU"][$firstSectionID] : false;
            $typeSectionId = $section['UF_TYPE'];
            $hasSelect = false;
            if(strpos($curUrl, $section['UF_LINK']) === 0){
                $hasSelect = true;
            } else {
                foreach ($isSecondSection as $secondSectionID => $secondSection) {
                    if (strpos($curUrl, $secondSection['UF_LINK']) === 0) {
                        $hasSelect = true;
                        break;
                    }
                }
            }
            ?>

            <li class="header-catalog-menu__item header-catalog-menu__item--level1 <?=($hasSelect?'header-catalog-menu__item--active':'')?> <?=!empty($isSecondSection) ? ' header-catalog-menu__item--has-items header-catalog-menu__item--has-items-mobile' : ''?> <?=($typeSectionId == 3 ? 'header-catalog-menu__item--doors-menu-table' : (in_array($typeSectionId, [2,5]) ? ' header-catalog-menu__item--has-items-mobile header-catalog-menu__item--has-items' : ' header-catalog-menu__item--doors-menu'))?>">
                <a <?=$section['UF_NOFOLOW']?'rel="nofollow"':'' ?>  <?= preg_match("/^http(s)?:\/\/.*/", $section['UF_LINK']) ? ' target="_blank" ' : "" ?> href="<?=$section['UF_LINK']?>" class="<?= !empty($isSecondSection)  ? 'header-catalog-menu__link--has-items-mobile ' : ''?>header-catalog-menu__link header-catalog-menu__link--level1<?=empty($isSecondSection) ? ($typeSectionId != 1 ? ' header-catalog-menu__link--line' : '') : (in_array($typeSectionId, [2,5]) ? ' header-catalog-menu__link--has-items-mobile header-catalog-menu__link--has-items' : ' header-catalog-menu__link--has-items')?>">
                <span class="header-catalog-menu__title header-catalog-menu__title--level1">
                    <?=$section['~NAME']?>
                </span>
                </a>
                <?if(!empty($isSecondSection)):?>
                    <div class="header-catalog-menu__container header-catalog-menu__container--level2">
                        <ul class="header-catalog-menu__list header-catalog-menu__list--level2">


                            <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--current">
                                <a <?=$section['UF_NOFOLOW']?'rel="nofollow"':'' ?> href="<?=$section['UF_LINK']?>" class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--current">
                                    <?=$section['~NAME']?>
                                </a>
                            </li>
                            <?
                            if ($section["CODE"] == "MEZHKOMNATNYE_DVERI") {
                                $countColumn = count($isSecondSection) -1;
                                $oneColumn = round (100 / $countColumn, 2);
                            }
                            ?>
                            <? $a = 0;?>


                            <? foreach ($isSecondSection as $secondSectionID => $secondSection): ?>

                                <?
                                $isSecondSubSection = !empty($arResult["MENU"][$secondSectionID]) ? $arResult["MENU"][$secondSectionID] : false;
                                $typeSecondSectionId = $secondSection['UF_TYPE'];
                                ?>
                                <? $styleBottom = ' style="bottom:  ' .(-20 + ( $a * 30 )). 'px;"'; $a++;?>

                                <li class="header-catalog-menu__item header-catalog-menu__item--level2<?= !in_array($typeSectionId, [2,5])? ' header-catalog-menu__item--doors-item' : ''?>
<?= $typeSecondSectionId == 1 ? ' header-catalog-menu__item--colors-item' : ''?><?= empty($typeSectionId) && empty($isSecondSubSection)  ? ' header-catalog-menu__item--splitable-doors' : ''?>"
                                    <?= empty($typeSectionId) && empty($isSecondSubSection) && !empty($styleBottom)  ? $styleBottom : ''?> <? if ($section["CODE"] == "MEZHKOMNATNYE_DVERI"): ?> style="min-width: calc(<?=$oneColumn?>% - 8px);" <?endif;?>>

                                    <?if(!empty($isSecondSubSection)):?>
                                        <?if($typeSecondSectionId != 1 && !empty($arResult["IMAGES"][$secondSection['PICTURE']])):?>
                                            <div data-src="<?=$arResult["IMAGES"][$secondSection['PICTURE']]?>" style="background-image: url(<?= SITE_TEMPLATE_PATH . '/preload.svg'?>);" class="lazy header-catalog-menu__image<?= $typeSectionId == 3 ? ' header-catalog-menu__image--left' : ' header-catalog-menu__image--top'?>"></div>
                                        <?endif;?>
                                        <span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item<?=$typeSecondSectionId == 1 ? ' header-catalog-menu__link--colors-item' : ''?>"><?=$secondSection['~NAME']?></span>

                                        <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                                            <ul class="header-catalog-menu__list header-catalog-menu__list--level3">

                                                <? foreach ($isSecondSubSection as $secondSubSectionID => $secondSubSection): ?>
                                                    <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                                                        <a <?=$secondSubSection['UF_NOFOLOW']?'rel="nofollow"':'' ?> <?= preg_match("/^http(s)?:\/\/.*/", $secondSubSection['UF_LINK']) ? ' target="_blank" ' : "" ?> href="<?= $secondSubSection['UF_LINK']?>" class="header-catalog-menu__link header-catalog-menu__link--level3">
                                                            <? if($typeSecondSectionId == 1 && !empty($arResult["IMAGES"][$secondSubSection['PICTURE']])):?>
                                                                <div data-src="<?=$arResult["IMAGES"][$secondSubSection['PICTURE']]?>" style="background-image: url(<?= SITE_TEMPLATE_PATH . '/preload.svg'?>);" class="lazy header-catalog-menu__color"></div>
                                                            <? endif;?>
                                                            <?= $secondSubSection['~NAME']?>
                                                        </a>
                                                    </li>
                                                <? endforeach;?>

                                            </ul>
                                        </div>
                                    <?else:?>

                                        <a <?=$secondSection['UF_NOFOLOW']?'rel="nofollow"':'' ?> class="header-catalog-menu__link header-catalog-menu__link--level2<?= empty($typeSectionId) ? ' header-catalog-menu__link--doors-item header-catalog-menu__link--splitable-doors' : ''?>" <?= preg_match("/^http(s)?:\/\/.*/", $secondSection['UF_LINK']) ? ' target="_blank" ' : "" ?> href="<?=$secondSection['UF_LINK']?>">

                                            <?if($typeSecondSectionId != 1 && !empty($arResult["IMAGES"][$secondSection['PICTURE']])):?>
                                                <div data-src="<?=$arResult["IMAGES"][$secondSection['PICTURE']]?>" style="background-image: url(<?= SITE_TEMPLATE_PATH . '/preload.svg'?>);" class="lazy header-catalog-menu__image<?= $typeSectionId == 3 ? ' header-catalog-menu__image--left' : ' header-catalog-menu__image--top'?>"></div>
                                            <?endif;?>

                                            <?if(empty($typeSectionId)):?>
                                                <?=$secondSection['~NAME']?>
                                            <?elseif($typeSectionId == 5):?>
                                                <div class="header-catalog-menu__title  parent-size"><?=$secondSection['~NAME']?></div>
                                            <?else:?>
                                                <div class="header-catalog-menu__title header-catalog-menu__title--furniture"><?=$secondSection['~NAME']?></div>
                                            <?endif;?>
                                        </a>

                                    <?endif;?>
                                </li>
                                <?
                                if(empty($typeSectionId) && empty($isSecondSubSection))
                                    $styleBottom = false;
                                ?>

                            <? endforeach;?>

                        </ul>
                    </div>
                <?endif;?>
            </li>

        <? endforeach;?>
    </ul>
</nav>
