<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();

$this->setFrameMode(true);
?>

<div class="modern-page-navigation">
    <span class="modern-page-title">Страницы:</span>

    <? if ($arResult["REVERSED_PAGES"] === true): ?>
        <? if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
            <? if (($arResult["CURRENT_PAGE"] + 1) == $arResult["PAGE_COUNT"]): ?>
                <li class="bx-pag-prev">
                    <a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>">
                        <span><? echo GetMessage("round_nav_back") ?></span>
                    </a>
                </li>
            <? else: ?>
                <li class="bx-pag-prev">
                    <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>">
                        <span><? echo GetMessage("round_nav_back") ?></span>
                    </a>
                </li>
            <? endif ?>
            <li class=""><a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"><span>1</span></a></li>
        <? else: ?>
            <li class="bx-pag-prev"><span><? echo GetMessage("round_nav_back") ?></span></li>
            <li class="bx-active"><span>1</span></li>
        <? endif ?>

        <?
        $page = $arResult["START_PAGE"] - 1;
        while ($page >= $arResult["END_PAGE"] + 1):
            ?>
            <? if ($page == $arResult["CURRENT_PAGE"]):?>
            <li class="bx-active"><span><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></span></li>
        <? else:?>
            <li class=""><a
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"><span><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></span></a>
            </li>
        <? endif ?>

            <? $page-- ?>
        <? endwhile ?>

        <? if ($arResult["CURRENT_PAGE"] > 1): ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <li class=""><a
                            href="<?= htmlspecialcharsbx($component->replaceUrlTemplate(1)) ?>"><span><?= $arResult["PAGE_COUNT"] ?></span></a>
                </li>
            <? endif ?>
            <li class="bx-pag-next"><a
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"><span><? echo GetMessage("round_nav_forward") ?></span></a>
            </li>
        <? else: ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <li class="bx-active"><span><?= $arResult["PAGE_COUNT"] ?></span></li>
            <? endif ?>
            <li class="bx-pag-next"><span><? echo GetMessage("round_nav_forward") ?></span></li>
        <? endif ?>

    <? else: ?>

        <? if ($arResult["CURRENT_PAGE"] > 1): ?>
            <span class="modern-page-first" onclick="location.href='<?= htmlspecialcharsbx($arResult["URL"]) ?>'" >1</span>
        <? else: ?>
            <span class="modern-page-first modern-page-current">1</span>
        <? endif ?>

        <?
        $page = $arResult["START_PAGE"] + 1;
        while ($page <= $arResult["END_PAGE"] - 1):?>
            <? if ($page == $arResult["CURRENT_PAGE"]):?>
                <span class="modern-page-first modern-page-current"><?= $page ?></span>
            <? else:?>
                <span class="modern-page-first" onclick="location.href='<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>'" ><?= $page ?></span>
            <? endif ?>
            <? $page++ ?>
        <? endwhile ?>

        <? if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <span class="modern-page-first" onclick="location.href='<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"])) ?>'" ><?= $arResult["PAGE_COUNT"] ?></span>
            <? endif ?>
        <? else: ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <span class="modern-page-first modern-page-current"><?= $arResult["PAGE_COUNT"] ?></span>
            <? endif ?>
        <? endif ?>
    <? endif ?>

    <? if ($arResult["CURRENT_PAGE"] < $arResult["END_PAGE"]): ?>
        <a class="modern-page-last"
           href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1)) ?>"><?= GetMessage("nav_next") ?></a>
        <a class="modern-page-last"
           href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["END_PAGE"])) ?>"><?= GetMessage("nav_end") ?></a>
    <? else: ?>
        <span class="modern-page-next"><?= GetMessage("nav_next") ?></span>
        <span class="modern-page-last"><?= GetMessage("nav_end") ?></span>
    <? endif ?>
</div>
