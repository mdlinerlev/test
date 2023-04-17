<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?>
<a class="header-b2b__btn" href="<?= $arParams['PATH_TO_BASKET'] ?>">
    <div class="header-b2b__img">
        <svg class="icon icon-basket ">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#basket"></use>
        </svg>
    </div>
    <div class="header-b2b__content">
        <div class="name">Корзина</div>
        <?if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y')) {?>
            <?if($arResult['NUM_PRODUCTS'] == 1){
                $text = 'товар';
            }elseif($arResult['NUM_PRODUCTS'] < 4){
                $text = 'товара';
            } else {
                $text = 'товаров';
            }?>
            <div class="val"><?=$arResult['NUM_PRODUCTS'];?> <?=$text?></div>
        <?}?>
    </div>
</a>