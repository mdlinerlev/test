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

if (empty($arResult["ALL_ITEMS"]))
    return;

CUtil::InitJSCore();

if (file_exists($_SERVER["DOCUMENT_ROOT"] . $this->GetFolder() . '/themes/' . $arParams["MENU_THEME"] . '/colors.css'))
    $APPLICATION->SetAdditionalCSS($this->GetFolder() . '/themes/' . $arParams["MENU_THEME"] . '/colors.css');

$menuBlockId = "catalog_menu_" . $this->randString();
?>

<nav class="header-catalog-menu__container header-catalog-menu__container--level1" id="cont_<?= $menuBlockId ?>">
    <ul class="header-catalog-menu__list header-catalog-menu__list--level1" id="ul_<?= $menuBlockId ?>">
        <li class="header-catalog-menu__item header-catalog-menu__item--level1">
            <a href="<?= $SITE_DIR ?>/aktsii/" class="header-catalog-menu__link header-catalog-menu__link--level1">Акции
                и скидки
            </a>
        </li>
        <? foreach ($arResult["MENU_STRUCTURE"] as $itemID => $arColumns): ?><? /*first level*/ ?>
            <? $existPictureDescColomn = ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]) ? true : false; ?>
            <? /* Для физических разделов(Акции, Распродажа)?>
           <? foreach ($arResult["MENU_STRUCTURE"] as $itemID => $arColumns): ?>     <!-- first level-->
    <? $existPictureDescColomn = ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]) ? true : false; ?>
            <?if (!$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["FROM_IBLOCK"]){ // Для физических разделов(Акции, Распродажа)?>
        <li class="header-catalog-menu__item header-catalog-menu__item--level1">
                    <a href="<?= $arResult["ALL_ITEMS"][$itemID]["LINK"] ?>" class="header-catalog-menu__link header-catalog-menu__link--level1"><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"] ?>
                    </a>
                  </li>*/ ?>


            <? if ($arResult["ALL_ITEMS"][$itemID]["TEXT"] == 'Межкомнатные двери') { ?>
                <li class="header-catalog-menu__item header-catalog-menu__item--level1 header-catalog-menu__item--has-items header-catalog-menu__item--doors-menu">
                <a class="header-catalog-menu__link header-catalog-menu__link--level1 header-catalog-menu__link--has-items header-catalog-menu__link--has-items"

                   href="<?= $arResult["ALL_ITEMS"][$itemID]["LINK"] ?>"><span
                            class="header-catalog-menu__title header-catalog-menu__title--level1"><?= $arResult["ALL_ITEMS"][$itemID]["TEXT"] ?></span>
                </a>
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.include", "", Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/inc/html_menu_mezhkomnatnie_dvery.php"
                    )
                );
                ?>

            <? } elseif ($arResult["ALL_ITEMS"][$itemID]["TEXT"] == 'Входные двери') { ?>
                <li class="header-catalog-menu__item header-catalog-menu__item--level1 header-catalog-menu__item--has-items header-catalog-menu__item--doors-menu-table">
                <a class="header-catalog-menu__link header-catalog-menu__link--level1 "

                   href="<?= $arResult["ALL_ITEMS"][$itemID]["LINK"] ?>"><span
                            class="header-catalog-menu__title header-catalog-menu__title--level1"><?= $arResult["ALL_ITEMS"][$itemID]["TEXT"] ?></span>
                </a>
                <? /* не выводим подкатегории
                                
                            $APPLICATION->IncludeComponent(
                                    "bitrix:main.include", "", Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => SITE_TEMPLATE_PATH."/inc/html_menu_vhodnie_dvery.php"
                                    )
);*/

            } else { ?>

                <? if ($arResult["ALL_ITEMS"][$itemID]["TEXT"] == 'Для дверей') { ?>


                    <? if (is_array($arColumns) && count($arColumns) > 0): ?><? endif ?>


                    <? if (is_array($arColumns) && count($arColumns) > 0): ?>
                        <?/*for mobile*/ ?>


                    <?

                    endif;
                } else { ?>
                    </li>

                    <?// Для разделов с категориями, таких как Напольные покррытия, Фурнитура?>
                    <li class="header-catalog-menu__item header-catalog-menu__item--level1 header-catalog-menu__item--has-items-mobile header-catalog-menu__item--has-items">

                        <a class="header-catalog-menu__link header-catalog-menu__link--level1 header-catalog-menu__link--has-items-mobile header-catalog-menu__link--has-items"

                           href="<?= $arResult["ALL_ITEMS"][$itemID]["LINK"] ?>"><span
                                    class="header-catalog-menu__title header-catalog-menu__title--level1"><?= $arResult["ALL_ITEMS"][$itemID]["TEXT"] ?></span>


                            <? if (is_array($arColumns) && count($arColumns) > 0): ?><? endif ?>

                        </a>
                        <? if (is_array($arColumns) && count($arColumns) > 0): ?>
                            <? /*for mobile*/ ?>
                            <div class="header-catalog-menu__container header-catalog-menu__container--level2">
                                <? foreach ($arColumns as $key => $arRow): ?>
                                    <ul class="header-catalog-menu__list header-catalog-menu__list--level2">
                                        <li class="header-catalog-menu__item header-catalog-menu__item--level2 header-catalog-menu__item--current">
                                            <a href="javascript:void(0)"
                                               class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--current"><?= $arResult["ALL_ITEMS"][$itemID]["TEXT"] ?>
                                            </a>
                                        </li>

                                        <? foreach ($arRow as $itemIdLevel_2 => $arLevel_3): ?><? /*second level*/ ?>
                                            <li class="header-catalog-menu__item header-catalog-menu__item--level2">

                                                <? /*<pre><? print_r($arResult);?></pre>
                                        <?/*<span class="header-catalog-menu__link header-catalog-menu__link--level2 header-catalog-menu__link--doors-item"><?= $arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"] ?>
                                        </span>*/ ?>

                                                <a class="header-catalog-menu__link header-catalog-menu__link--level2"
                                                   href="<?= $arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"] ?>"

                                                   data-picture="<?= $arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["DETAIL_PICTURE"] ?>"
                                                   <? if ($arResult["ALL_ITEMS"][$itemIdLevel_2]["SELECTED"]): ?>class="bx-active"<? endif ?>
                                                >
                                                    <div style="background-image:url(<?= CFile::GetPath($arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["DETAIL_PICTURE"]) ?>)"
                                                         class="header-catalog-menu__image">
                                                    </div>
                                                    <div class="header-catalog-menu__title <? if (stristr($arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"], 'furnitura') != FALSE) { ?>header-catalog-menu__title--furniture<? } ?>"><?= $arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"] ?></div>
                                                </a>
                                                <? if (is_array($arLevel_3) && count($arLevel_3) > 0): ?>
                                                    <div class="header-catalog-menu__container header-catalog-menu__container--level3">
                                                        <ul class="header-catalog-menu__list header-catalog-menu__list--level3">
                                                            <? foreach ($arLevel_3 as $itemIdLevel_3): ?><? /*third level*/ ?>
                                                                <li class="header-catalog-menu__item header-catalog-menu__item--level3">
                                                                    <a class="header-catalog-menu__item header-catalog-menu__item--level3"
                                                                       href="<?= $arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"] ?>"
                                                                        <? if ($existPictureDescColomn): ?>
                                                                            onmouseover="obj_<?= $menuBlockId ?>.changeSectionPicure(this, '<?= $itemIdLevel_3 ?>');return false;"
                                                                        <? endif ?>
                                                                       data-picture="<?= CFile::GetPath($arResult["ALL_ITEMS"][$itemIdLevel_3]["PARAMS"]["DETAIL_PICTURE"]) ?>"
                                                                       <? if ($arResult["ALL_ITEMS"][$itemIdLevel_3]["SELECTED"]): ?>class="bx-active"<? endif ?>
                                                                    >
                                                                        <?= $arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"] ?>
                                                                    </a>
                                                                </li>
                                                            <? endforeach; ?>
                                                        </ul>
                                                    </div>
                                                <? endif ?>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                <? endforeach; ?>
                                <? /* if ($existPictureDescColomn):?>
                          <div class="bx-nav-list-2-lvl bx-nav-catinfo dbg" data-role="desc-img-block">
                          <a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
                          <img src="<?=$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"]?>" alt="">
                          </a>
                          <p><?=$arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]?></p>
                          </div>
                          <div class="bx-nav-catinfo-back"></div>
                          <?endif */ ?>
                            </div>
                        <? endif ?>
                    </li>
                <?
                }
            } ?>
        <? endforeach; ?>


<?// Добавим вкладку "Распродажа"?>

           <li class="header-catalog-menu__item header-catalog-menu__item--level1">
            <a href="https://sale.belwooddoors.by/" target="_blank"
            class="header-catalog-menu__link header-catalog-menu__link--level1">Распродажа

            </a>
        </li>




        <?php if (false): ?>
            <li class="header-catalog-menu__item header-catalog-menu__item--level1">
                <a href="<?= $SITE_DIR ?>/derevyannye-velosipedy/rama-iz-massiva-berezi/"
                   class="header-catalog-menu__link header-catalog-menu__link--level1">Велосипеды из дерева
                </a>
            </li>
        <?php endif; ?>
        <script>
            BX.ready(function () {
                window.obj_<?= $menuBlockId ?> = new BX.Main.Menu.CatalogHorizontal('<?= CUtil::JSEscape($menuBlockId) ?>', <?= CUtil::PhpToJSObject($arResult["ITEMS_IMG_DESC"]) ?>);
            });
        </script>