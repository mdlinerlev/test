<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="main-menu__slogan">
    Официальный сайт Belwooddoors в России
</div>

<? if (!empty($arResult)): ?>

<ul class="main-menu__list main-menu__list--level1" itemscope itemtype="http://schema.org/SiteNavigationElement">
    <meta itemprop="name" content="Навигационное Меню">
    <?
    $previousLevel = 0;
    foreach ($arResult

    as $arItem):
    ?>
    <?if (empty($arItem["LINK"])) {
        continue;
    } ?>
    <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
        <?= str_repeat("</ul></div></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
    <? endif ?>

    <? if ($arItem["IS_PARENT"]): ?>

    <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
    <li class="main-menu__item main-menu__item--level1 main-menu__item--has-items" itemprop="name">
        <a itemprop="url"  class="main-menu__link main-menu__link--level1 main-menu__link--has-items" <?=$arItem['PARAMS']['nofollow']?: ''?> href="<?= $arItem["LINK"] ?>"  <?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?>>
            <?= $arItem["TEXT"] ?>
            <svg class="dropdown_arrow">
                <use xlink:href="#arrow-down"></use>
            </svg>
        </a>
        <div class="main-menu__container main-menu__container--level2">

            <ul class="main-menu__list--level2">
                <li itemprop="name" class="main-menu__item main-menu__item--level2 main-menu__item--current"><a itemprop="url"
                            class="main-menu__link main-menu__link--level2 main-menu__link--current"
                            href="<?= $arItem["LINK"] ?>" <?=$arItem['PARAMS']['nofollow']?: ''?> <?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?>><?= $arItem["TEXT"] ?></a></li>

                <? else: ?>
                <li itemprop="name" class="main-menu__item main-menu__item--level1"<? if ($arItem["SELECTED"]): ?><? endif ?>><a itemprop="url"
                            class="main-menu__link main-menu__link--level1"
                            href="<?= $arItem["LINK"] ?>" <?=$arItem['PARAMS']['nofollow']?: ''?> <?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?>><?= $arItem["TEXT"] ?></a>

                    <? endif ?>

                    <? else: ?>

                    <? if ($arItem["PERMISSION"] > "D"): ?>

                    <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <li itemprop="name" class="main-menu__item main-menu__item--level1">
                <? if ($arItem["TEXT"] == "Заказать замер"): ?> <script data-b24-form="click/9/5qnm9v" data-skip-moving="true"> (function(w,d,u){ var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0); var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h); })(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_9_5qnm9v.js');</script>  <? endif; ?>
                <a itemprop="url" href="<?= $arItem["LINK"] ?>"  class="main-menu__link main-menu__link--level1 <?= !empty($arItem['PARAMS']['CLASS']) ? $arItem['PARAMS']['CLASS'] : ''?>" <?=$arItem['PARAMS']['nofollow']?: ''?> <?=  (!empty($arItem['PARAMS']['OPTIONS']) && $arItem["TEXT"] != "Заказать замер") ? $arItem['PARAMS']['OPTIONS'] : ''?>>
                        <?= $arItem["TEXT"] ?>
                    </a>
                </li>
            <? else: ?>
                <li itemprop="name" class="main-menu__item main-menu__item--level2"><a class="main-menu__link main-menu__link--level2" itemprop="url"
                        <?=$arItem['PARAMS']['nofollow']?: ''?>  href="<?= $arItem["LINK"] ?>"  <?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?>><?= $arItem["TEXT"] ?></a>
                </li>

            <? endif ?>

                <? else: ?>

                <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <li itemprop="name" class="main-menu__item main-menu__item--level1 main-menu__item--has-items"><a
                            class="main-menu__link main-menu__link--level1 main-menu__link--has-items" itemprop="url"
                            href="<?= $arItem["LINK"] ?>"  <?=$arItem['PARAMS']['nofollow']?: ''?> <?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?>><?= $arItem["TEXT"] ?></a>
                    <div class="main-menu__container main-menu__container--level2">
                        <ul class="main-menu__list--level2">
                            <? else: ?>
                                <li itemprop="name" class="main-menu__item main-menu__item--level1"><a
                                            class="main-menu__link main-menu__link--level1" href=""
                                            title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><?= $arItem["TEXT"] ?></a>
                                </li>

                            <? endif ?>

                            <? endif ?>

                            <? endif ?>

                            <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

                            <? endforeach ?>


                            <? if ($previousLevel > 1)://close last item tags ?>

                                <?= str_repeat("</ul></div></li>", ($previousLevel - 1)); ?>
                            <? endif ?>
                            <?
                            $APPLICATION->IncludeComponent(
                                "bitrix:main.include", "", Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "inc",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/filials_dropdown.php"
                                )
                            );
                            ?>

                        </ul>

                        <? endif ?>
