<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */

$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);

$restoreColSpan = 2 + $usePriceInAdditionalColumn + $useSumColumn + $useActionColumn;

$positionClassMap = array(
	'left' => 'basket-item-label-left',
	'center' => 'basket-item-label-center',
	'right' => 'basket-item-label-right',
	'bottom' => 'basket-item-label-bottom',
	'middle' => 'basket-item-label-middle',
	'top' => 'basket-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>
<script id="basket-item-template" type="text/html">
    <tr id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">
        {{#SHOW_RESTORE}}
            <td class="basket-items-list-item-notification" colspan="7">
                <div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">
                    {{#SHOW_LOADING}}
                    <div class="basket-items-list-item-overlay"></div>
                    {{/SHOW_LOADING}}
                    <div class="basket-items-list-item-removed-container">
                        <div>
                            <?=Loc::getMessage('SBB_GOOD_CAP')?> <strong>{{NAME}}</strong> <?=Loc::getMessage('SBB_BASKET_ITEM_DELETED')?>.
                        </div>
                        <div class="basket-items-list-item-removed-block">
                            <a href="javascript:void(0)" data-entity="basket-item-restore-button">
                                <?=Loc::getMessage('SBB_BASKET_ITEM_RESTORE')?>
                            </a>
                            <span class="basket-items-list-item-clear-btn" data-entity="basket-item-close-restore-button"></span>
                        </div>
                    </div>
                </div>
            </td>
        {{/SHOW_RESTORE}}
        {{^SHOW_RESTORE}}
        <td><img src="{{IMAGE_URL}}" alt="{{NAME}}"></td>
        <td>
            <a href="{{DETAIL_PAGE_URL}}" class="name">{{NAME}}</a>
            <ul class="list">
                <?
                if (!empty($arParams['PRODUCT_BLOCKS_ORDER']))
                {
                    foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
                    {
                        switch (trim((string)$blockName))
                        {
                            case 'props':
                                if (in_array('PROPS', $arParams['COLUMNS_LIST']))
                                {
                                    ?>
                                    {{#PROPS}}
                                    <li><span><b>{{{NAME}}}:&nbsp;</b></span><span>{{{VALUE}}}</span></li>
                                    {{/PROPS}}
                                    <?
                                }

                                break;
                            case 'columns':
                                ?>
                                {{#COLUMN_LIST}}
                                {{#IS_IMAGE}}
                                <div class="basket-item-property-custom basket-item-property-custom-photo
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                     data-entity="basket-item-property">
                                    <div class="basket-item-property-custom-name">{{NAME}}</div>
                                    <div class="basket-item-property-custom-value">
                                        {{#VALUE}}
                                        <span>
                                            <img class="basket-item-custom-block-photo-item"
                                                 src="{{{IMAGE_SRC}}}" data-image-index="{{INDEX}}"
                                                 data-column-property-code="{{CODE}}">
                                        </span>
                                        {{/VALUE}}
                                    </div>
                                </div>
                                {{/IS_IMAGE}}

                                {{#IS_TEXT}}
                                <li><span><b>{{{NAME}}}:&nbsp;</b></span><span>{{{VALUE}}}</span></li>
                                {{/IS_TEXT}}

                                {{#IS_HTML}}
                                <div class="basket-item-property-custom basket-item-property-custom-text
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                     data-entity="basket-item-property">
                                    <div class="basket-item-property-custom-name">{{NAME}}</div>
                                    <div class="basket-item-property-custom-value"
                                         data-column-property-code="{{CODE}}"
                                         data-entity="basket-item-property-column-value">
                                        {{{VALUE}}}
                                    </div>
                                </div>
                                {{/IS_HTML}}

                                {{#IS_LINK}}
                                <div class="basket-item-property-custom basket-item-property-custom-text
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                     data-entity="basket-item-property">
                                    <div class="basket-item-property-custom-name">{{NAME}}</div>
                                    <div class="basket-item-property-custom-value"
                                         data-column-property-code="{{CODE}}"
                                         data-entity="basket-item-property-column-value">
                                        {{#VALUE}}
                                        {{{LINK}}}{{^IS_LAST}}<br>{{/IS_LAST}}
                                        {{/VALUE}}
                                    </div>
                                </div>
                                {{/IS_LINK}}
                                {{/COLUMN_LIST}}
                                <?
                                break;
                        }
                    }
                }
                ?>
            </ul>
        </td>
        <td><span class="zag sm-show">Статус</span>
            <div class="status">
                {{#AVAILABLE_QUANTITY}}
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/status.svg">
                    <span>В наличии</span>
                {{/AVAILABLE_QUANTITY}}
                {{^AVAILABLE_QUANTITY}}
                    <div class="product-top__availability_not"></div>
                    <span>Под заказ</span>
                {{/AVAILABLE_QUANTITY}}
            </div>
        </td>
        <td>
            <span class="zag sm-show">Цена</span>
            <span id="basket-item-price-{{ID}}">{{{PRICE_FORMATED}}}</span>
        </td>
        <td><span class="zag sm-show">Количество</span>
            <div class="counter" data-entity="basket-item-quantity-block">
                <button class="minus" data-entity="basket-item-quantity-minus">-</button>
                <input type="text" value="{{QUANTITY}}" data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
                       id="basket-item-quantity-{{ID}}" {{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}>
                <button class="plus" data-entity="basket-item-quantity-plus">+</button>
            </div>
        </td>
        <td>
            <span class="zag sm-show">Итого цена</span>
            <span id="basket-item-sum-price-{{ID}}">{{{SUM_PRICE_FORMATED}}}</span>
        </td>
        <td class="_empty">
            <button class="js-delete delete" data-entity="basket-item-delete">
                <svg class="icon icon-close ">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#close"></use>
                </svg>
            </button>
        </td>
        {{/SHOW_RESTORE}}
    </tr>
</script>