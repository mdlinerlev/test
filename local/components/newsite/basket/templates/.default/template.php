<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

/**
 * @var $currentBasket \Newsite\Sale\Basket
 * @var $currentOrder \Newsite\Sale\Order
 * @var $component \CShBasket
 */
$currentProduct = $component::$items;
$currentBasket = $component::$basket;
$currentOrder = $component::$order;
?>

<div id="basket_form">
    <form id="basketSubmitAction" class="replaceFormBitixAjax hide"
          action="<?= $arParams["AJAX_MODE"] != "Y" ? $APPLICATION->GetCurPageParam("", $arResult["KILLARG"]) : "#" ?>"
          method="post">
        <? if ($arParams["AJAX_MODE"] == "Y"): ?>
            <input type="hidden" name="FORM_ACTION"
                   value="<?= $APPLICATION->GetCurPageParam("", $arResult["KILLARG"]) ?>"/>
            <input type="hidden" name="bxajaxidjQuery" value="<?= $arParams["AJAX_ID"]; ?>"/>
        <? endif; ?>

        <input type="hidden" class="setBasketValues" name="CLEAR_COUPON" value=""/>
        <input type="hidden" class="setBasketValues" name="ADD_COUPON" value=""/>
        <input type="hidden" class="setBasketValues" name="BASKET_ADD" value=""/>
        <input type="hidden" class="setBasketValues" name="COUNT" value=""/>
        <input type="hidden" class="setBasketValues" name="BASKET_DELETE" value=""/>
        <input type="hidden" class="setBasketValues" name="GROUPDELETE" value=""/>
        <input type="hidden" class="setBasketValues" name="GIFT_WRAP" value=""/>

        <a class="reloadLink hidden" href="<?= $APPLICATION->GetCurPageParam("", $arResult["KILLARG"]) ?>"></a>
    </form>
    <section class="cart__form">
        <form   class="replaceFormBitixAjax"
                method="post"
                action="<?= $arParams["AJAX_MODE"] != "Y" ? $APPLICATION->GetCurPageParam("", $arResult["KILLARG"]) : "#" ?>"
        >
        <div class="basket">
            <div class="basket__table">
                <div class="basket__head"></div>
                <div class="basket__body">

                    <? if ($arParams["AJAX_MODE"] == "Y"): ?>
                        <input type="hidden" name="FORM_ACTION"
                               value="<?= $APPLICATION->GetCurPageParam("", $arResult["KILLARG"]) ?>"/>
                        <input type="hidden" name="bxajaxidjQuery" value="<?= $arParams["AJAX_ID"]; ?>"/>
                    <? endif; ?>

                    <?
                    /** @var \Newsite\Sale\BasketItem $basketItem */
                    foreach ($currentBasket->getBasketItems() as $basketItem):
                        $productId = $basketItem->getProductId();
                        $itemProduct = $currentProduct[$productId];
                    ?>
                    <div class="basket__item js_cat_list_item js_increment_logic js_small_basket_list_item js_update_after_change"
                                                     data-rel="<?= $APPLICATION->GetCurPageParam("BASKET_ADD={$productId}", $arParams["KILLPARAMS"]) ?>"
                                                     data-productid="<?= $productId ?>">
                        <div class="basket__item-img">
                            <?if(!empty($basketItem->getField('IMAGE')) && !empty($arResult["IMAGES"][$basketItem->getField('IMAGE')])):?>
                            <a class="basket__item-img-link" href="<?=$basketItem->getField('DETAIL_PAGE_URL') ?>">
                                <?
                                $src = $component->imageResize(
                                    [
                                        "MODE" => "maxsize",
                                        "WIDTH" => 270,
                                        "HEIGHT" => 190,
                                        "FILTER_BEFORE" => [],
                                    ], $arResult["IMAGES"][$basketItem->getField("IMAGE")]
                                );
                                ?>
                                <img src="<?= $src?>" alt="<?=$basketItem->getField('NAME') ?>" class="cart-table__image"/>
                            </a>
                            <?endif;?>
                        </div>
                        <div class="basket__item-params">
                            <a class="basket__item-title" href="<?=$basketItem->getField('DETAIL_PAGE_URL') ?>">
                                <?=$basketItem->getField('NAME') ?>
                            </a>

                            <?if(!empty($basketItem->getField('ARTICLE'))):?>
                            <div class="basket__item-art">Арт. <?= $basketItem->getField('ARTICLE') ?></div>
                            <?endif;?>

                            <div class="basket__item-parameters">
                            <? foreach ( $itemProduct['PROPERTIES'] as $prop):?>
                                <? if (!empty($prop['VALUE']) && in_array($prop['CODE'], ["SIZE", "SIDE", "COLOR", "COLOR_IN", "COLOR_OUT", "GLASS_COLOR"])):?>
                                    <?$prop = CIBlockFormatProperties::GetDisplayValue($itemProduct, $prop, "catalog_out"); ?>
                                    <div class="basket__item-prm"><span><?= $prop['NAME'] ?></span><span class="basket__float-dots"></span><span class="basket__item-span"><?= $prop['DISPLAY_VALUE']?></span></div>
                                <? endif;?>
                            <? endforeach; ?>
                            </div>
                            <? if($itemProduct['CATALOG_QUANTITY'] > 0): ?>
                                <div class="product-top__availability product-top__availability--available">В наличии</div>
                            <? else:?>
	                            <div class="product-top__availability_not not_product-top__availability--available">Под заказ</div>
                            <? endif;?>
                        </div>


                        <div class="basket__item-price">
                            <div class="basket__item-base-price">
	                            <span><?= SaleFormatCurrency($basketItem->getPrice(), $arParams["CURRENCY"]) ?></span>
	                            <span>за шт.</span>
                            </div>

                            <div class="basket__item-quantity">
                                <label for="QUANTITY_INPUT_<?=$productId?>" class="sr-only">Количество:</label>
                                <div class="basket__item-quantity-inputs quantity js-add-one-box">
                                    <a href="javascript:void(0);" class="quantity__button js-btn-add-one-box js-btn-add-one-box-mn">-</a>
                                    <input class="quantity__input js-add-one-box-input" type="text" size="3" data-inc-val="1" data-min-val="1"  data-max-val="999" name="QUANTITY" style="max-width: 50px" value="<?= $basketItem->getQuantity(); ?>">
                                    <a href="javascript:void(0);" class="quantity__button js-btn-add-one-box js-btn-add-one-box-pl">+</a>
                                </div>
                            </div>
	                        
	                        <div class="basket__item-sum-price">
		                        <?= SaleFormatCurrency($basketItem->getFinalPrice(), $arParams["CURRENCY"]) ?>
	                        </div>
	                        <div class="basket__item-price--discount">
		                        <? if ($basketItem->getDiscountPrice() > 0): ?>
			                        <div class="basket__item-price--base"><?= SaleFormatCurrency($basketItem->getBasePrice(), $arParams["CURRENCY"])?></div>
		                        <? endif; ?>
	                        </div>
                            <div class="basket__item-remove">
                                <a class="basket__item-remove-link" href="<?= $APPLICATION->GetCurPageParam("action=remove&id={$basketItem->getProductId()}", $arResult["KILLARG"]) ?>">Удалить</a>
                            </div>
                        </div>
                    </div>

                    <? endforeach; ?>

                    <a href="<?= $APPLICATION->GetCurPageParam("action=clear", $arResult["KILLARG"]) ?>" class="cart-topbar__button cart-topbar__button--clear-button button">
                        <span>Удалить всё из корзины</span>
                    </a>


                    <h3>Оформление заказа</h3>
                    <div class="basket_order">
                        <?
                        foreach ($currentOrder->getOrderStepCollection() as $itemStep):
                            $showedProps = [];

                            foreach ($itemStep->getGroupIds() as $itemStepGroupId)
                                $showedProps = array_merge($showedProps, $currentOrder->getPropertyByGroup($itemStepGroupId));

                            switch ($itemStep->getCode()):
                                case "personal":
                                    include_once __DIR__ . '/steps/personal.php';
                                break;
                                case "delivery":
                                    include_once __DIR__ . '/steps/delivery.php';
                                break;
                                case "payment":
                                    include_once __DIR__ . '/steps/payment.php';
                                break;

                            endswitch;

                        endforeach;
                        ?>
                    </div>

                </div>
            </div>
            <div class="basket__float ">
                <div class="basket__float_box">
	                <div class="basket__float_box_wrap">
		                <div class="basket__float-summary">
			                <div class="basket__float-row">
				                <span>Товаров</span>
				                <span class="basket__float-dots"></span>
				                <span class="basket__item-span js-basket__item__badge"><?= count($currentBasket->getProductIds())?></span>
			                </div>
                            <?if($currentOrder->getDeliveryPrice()):?>
                                <div class="basket__float-row">
                                    <span>К оплате&nbsp;<small>(без доставки)</small></span>
                                    <span class="basket__float-dots"></span>
                                    <span class="basket__item-span js-basket__item__text"><?= SaleFormatCurrency($currentBasket->getBasePrice(), $arParams["CURRENCY"]) ?></span>
                                </div>
                                <div class="basket__float-row">
                                    <span>Доставка</span>
                                    <span class="basket__float-dots"></span>
                                    <span class="basket__item-span"><?=$currentOrder->getDeliveryPrice() ? SaleFormatCurrency($currentOrder->getDeliveryPrice(), $arParams["CURRENCY"]) : 'бесплатно'?></span>
                                </div>
                            <?endif;?>
                            <div class="basket__float-row">
                                <span>К оплате</span>
                                <span class="basket__float-dots"></span>
                                <span class="basket__item-span"><?= SaleFormatCurrency($currentBasket->getPrice() + $currentOrder->getDeliveryPrice(), $arParams["CURRENCY"]) ?></span>
                            </div>
		                </div>
<?/*
		                <div class="basket__float-promo js_cat_list_item">
			                <div class="toggled-elem">Промокод на скидку&nbsp;<svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg></div>
			                <div class="toggled-item">
				
				                <? if (!empty($arResult["COUPON_LIST"])): ?>
					                <? foreach ($arResult["COUPON_LIST"] as $itemCoupon): ?>
						                <? if ($itemCoupon["STATUS"] == \Bitrix\Sale\DiscountCouponsManager::STATUS_APPLYED): ?>
							                <div class="promocode promocode--active">
								                <div class="promocode__label">
									                <div class="promocode__success-note">
										                <div class="success-note">Купон применен</div>
									                </div>
									                <button type="button" name="CLEAR_COUPON" aria-label="Удалить" class="promocode__btn js_add_and_go_cart" data-rel="<?= $APPLICATION->GetCurPageParam("", $arParams["KILLPARAMS"], false) ?>">
										                <svg viewBox="0 0 10 10" width="20" height="20">
											                <path d="M1 1L9 9M9 1L1 9" stroke="currentColor"></path>
										                </svg>
									                </button>
								                </div>
							                </div>
						                <? else: ?>
							                <div class="promocode promocode--alert">
								                <div class="promocode__label">
									                <input type="text" maxlength="50" name="ADD_COUPON" class="js-rich-text-input__input promocode__input" placeholder="Неверный промокод">
									                <button type="button"  aria-label="Отправить" class="promocode__btn js_add_and_go_cart" data-rel="<?= $APPLICATION->GetCurPageParam("", $arParams["KILLPARAMS"], false) ?>">
										                <svg viewBox="0 0 20 14" width="24" height="20">
											                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1624 6.19558L12.5196 1.12929L13.5851 -5.60805e-07L20 7L13.5851 14L12.5196 12.8707L17.1624 7.80442L5.41635e-07 7.80442L6.82284e-07 6.19558L17.1624 6.19558Z" fill="currentColor"></path>
										                </svg>
									                </button>
								                </div>
							                </div>
						                <? endif; ?>
					                <? endforeach; ?>
				                <?else: ?>
					                <div class="promocode">
						                <div class="promocode__label">
							                <input type="text" maxlength="50" name="ADD_COUPON" class="js-rich-text-input__input promocode__input">
							                <button type="button" aria-label="Отправить" class="promocode__btn js_add_and_go_cart" data-rel="<?= $APPLICATION->GetCurPageParam("", $arParams["KILLPARAMS"], false) ?>">
								                <svg viewBox="0 0 20 14" width="24" height="20">
									                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1624 6.19558L12.5196 1.12929L13.5851 -5.60805e-07L20 7L13.5851 14L12.5196 12.8707L17.1624 7.80442L5.41635e-07 7.80442L6.82284e-07 6.19558L17.1624 6.19558Z" fill="currentColor"></path>
								                </svg>
							                </button>
						                </div>
					                </div>
				                <? endif ?>
			                </div>


		                </div>
         */?>
		                <div class="basket__float-addition">
			                <?
				                foreach ($currentOrder->getOrderStepCollection() as $itemStep):
					                if($itemStep->getCode() == 'other'):
						                $showedProps = [];
						
						                foreach ($itemStep->getGroupIds() as $itemStepGroupId)
							                $showedProps = array_merge($showedProps, $currentOrder->getPropertyByGroup($itemStepGroupId));
						
						                include 'prop.php';
					
					                endif;
				                endforeach;
			                ?>
		                </div>


		                <button class="btn btn-lg btn-submit" value="Y" name="submit" type="submit">Оформить заказ</button>
	                </div>

	                <!-- Купить в 1 клик попап -->
<!--	                <div class="one-click">-->
<!--		                <div data-product_id="--><?//= $productId?><!--" data-popup="popup-oneclickbuy" class="one-click-buy button button--secondary mt-10">Купить в 1 клик</div>-->
<!--	                </div>-->
                </div>
            </div>
        </div>
        </form>
    </section>
</div>
<script>
    $(document).on('change', '.bx-ui-sls-fake', function(){
        setTimeout(function() {
            $('.cart__form form').trigger('submit');
        }, 300);
    });
</script>
<?php
$sBasketItemsID = "";
foreach ($currentBasket->getBasketItems() as $basketItem) {
    if($idProduct = CIBlockElement::GetProperty(12, $basketItem->getProductId(), array("sort" => "asc"), Array("CODE"=>"CML2_LINK"))->Fetch()["VALUE"]) {
        $sBasketItemsID = $sBasketItemsID."ru-".$idProduct.",";
    } else {
        $sBasketItemsID = $sBasketItemsID."ru-".$basketItem->getProductId().",";
    }
}
$sBasketItemsID = substr($sBasketItemsID,0,-1);
global $globalBasketLines;
$globalBasketLines = $sBasketItemsID;
?>