<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
    <div class="b2b-basket__total" data-entity="basket-checkout-aligner">
        <div class="b2b-basket__total-zag">Информация о заказе</div>
        {{#SHOW_STOCK}}
        <div class="b2b-basket__total-promo">
            <span><b>Укажите размер скидки в %</b></span>
            <div class="stock_button">
                <input type="text" data-entity="stock-value" placeholder="Введите размер скидки в %" value="{{STOCK_PERCENT}}">
                <button class="button" data-entity="stock-set" data-class="w820 b2b-wrapper">Применить</button>
            </div>
        </div>
        {{/SHOW_STOCK}}
        <ul class="b2b-basket__total-list">
            <li><span><b>Количество товаров</b></span><span>{{COUNT}} шт.</span></li>
            <li><span><b>К оплате</b></span><span>{{PRICE_WITHOUT_DISCOUNT_FORMATED}}</span></li>
            {{#SHOW_STOCK}}
            <li><span><b>Скидка</b></span><span>{{DISCOUNT}}</span></li>
            <li><span><b>К оплате со скидкой</b></span><span data-entity="basket-total-price">{{{PRICE_FORMATED}}}</span></li>
            {{/SHOW_STOCK}}
        </ul>
        <button class="button" data-entity="basket-total-kpcreate" data-class="w820 b2b-wrapper" data-href="/ajax/reAjax.php?action=modalBasketKp&new=Y">Сформировать КП</button>
    </div>
    <button class="button _grey" data-entity="basket-total-delete">
        <svg class="icon icon-close ">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#close"></use>
        </svg>
        <span>Очистить всю коризну</span>
    </button>

    <div class="basket-checkout-container" data-entity="basket-checkout-aligner">
        <?
        if ($arParams['HIDE_COUPON'] !== 'Y') {
            ?>
            <div class="basket-coupon-section">
                <div class="basket-coupon-block-field">
                    <div class="basket-coupon-block-field-description">
                        <?= Loc::getMessage('SBB_COUPON_ENTER') ?>:
                    </div>
                    <div class="form">
                        <div class="form-group" style="position: relative;">
                            <input type="text" class="form-control" id="" placeholder=""
                                   data-entity="basket-coupon-input">
                            <span class="basket-coupon-block-coupon-btn"></span>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
        ?>
</script>