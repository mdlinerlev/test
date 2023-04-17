<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
/** @var $currentBasket \Newsite\Sale\Basket */
$currentBasket = $component::$basket;

?>

<div class="bx-basket bx-opener">
    <a rel="nofollow" href="/personal/cart/" class="header-shop-links__link header-shop-links__link--cart js-header-shop-links__cart <?= $currentBasket->getProductIds() ? ' active' : ''?>">
        <svg class="icon icon-small-cart">
            <use xlink:href="#shopping_cart_line"></use>
        </svg>
        <div class="header-shop-links__badge js-header-shop-links__badge"><?= count($currentBasket->getQuantityList())?></div>
        <div class="header-shop-links__text js-header-shop-links__text"><?= $currentBasket->getProductIds() ? SaleFormatCurrency($currentBasket->getPrice(), \Bitrix\Currency\CurrencyManager::getBaseCurrency()) : 'Корзина'?></div>
    </a>
</div>

