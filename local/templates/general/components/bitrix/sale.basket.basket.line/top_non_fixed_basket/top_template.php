<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?><? if ($_REQUEST["basketAction"] == 'deleteall' and CModule::IncludeModule("sale")) {
    CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
} ?>
<? if ($arResult['NUM_PRODUCTS'] > 0) { ?>

    <a rel="nofollow" href="<?= $arParams['PATH_TO_BASKET'] ?>"
       class="header-shop-links__link header-shop-links__link--cart active">
        <svg class="icon icon-small-cart">
            <use xlink:href="#shopping_cart"></use>
        </svg>
        <div class="header-shop-links__badge"><?= $arResult['NUM_PRODUCTS'] ?></div>
        <div class="header-shop-links__text"><?= $arResult['TOTAL_PRICE'] ?> </div>
    </a>
<? } else { ?>

    <a rel="nofollow" href="<?= $arParams['PATH_TO_BASKET'] ?>"
       class="header-shop-links__link header-shop-links__link--cart">
        <svg class="icon icon-small-cart">
            <use xlink:href="#shopping_cart_line"></use>
        </svg>
        <div class="header-shop-links__text">Корзина
        </div>
    </a>

<? } ?>

<!--<a href="/compare/" class="header-shop-links__link header-shop-links__link--comparsion">
                <div class="header-shop-links__text header-shop-links__text--comparsion">Сравнение
                </div>
              </a>
              <a class="header-shop-links__link header-shop-links__link--cart">
                <div class="header-shop-links__text">
<? if (!$compositeStub && $arParams['SHOW_AUTHOR'] == 'Y'): ?>
	<div class="bx-basket-block">
	
		<? if ($USER->IsAuthorized()):
    $name = trim($USER->GetFullName());
    if (!$name)
        $name = trim($USER->GetLogin());
    if (strlen($name) > 15)
        $name = substr($name, 0, 12) . '...';
    ?>
			<a href="<?= $arParams['PATH_TO_PROFILE'] ?>"><?= htmlspecialcharsbx($name) ?></a>
			&nbsp;
			<a href="?logout=yes"><?= GetMessage('TSB1_LOGOUT') ?></a>
		<? else: ?>
			<a href="<?= $arParams['PATH_TO_REGISTER'] ?>?login=yes"><?= GetMessage('TSB1_LOGIN') ?></a>
			&nbsp;
			<a href="<?= $arParams['PATH_TO_REGISTER'] ?>?register=yes"><?= GetMessage('TSB1_REGISTER') ?></a>
		<? endif ?>
	</div>
<? endif ?>
	<div class="bx-basket-block"><?
if (!$arResult["DISABLE_USE_BASKET"]) {
    ?>
			<a rel="nofollow" href="<?= $arParams['PATH_TO_BASKET'] ?>"><?= GetMessage('TSB1_CART') ?></a><?
}
if (!$compositeStub) {
    if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y')) {
        echo $arResult['NUM_PRODUCTS'] . ' ' . $arResult['PRODUCT(S)'];
    }
    if ($arParams['SHOW_TOTAL_PRICE'] == 'Y'):?>
			<br <? if ($arParams['POSITION_FIXED'] == 'Y'): ?>class="hidden-xs"<? endif ?>/>
			<span>
				<?= GetMessage('TSB1_TOTAL_PRICE') ?>
				<? if ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'): ?>
					<strong><?= $arResult['TOTAL_PRICE'] ?></strong>
				<? endif ?>
			</span>
			<?endif; ?>
			<?
}
if ($arParams['SHOW_PERSONAL_LINK'] == 'Y'):?>
			<div style="padding-top: 4px;">
			<span class="icon_info"></span>
			<a rel="nofollow" href="<?= $arParams['PATH_TO_PERSONAL'] ?>"><?= GetMessage('TSB1_PERSONAL') ?></a>
			</div>
		<? endif ?>
	</div>
</div>-->
