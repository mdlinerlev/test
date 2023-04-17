<? foreach ($showedProps as $itemProp):

    $property = $itemProp->getPropertyObject();
    $itemPropErrors = new \Bitrix\Sale\Result();

    $requestState =\Bitrix\Main\Context::getCurrent()->getRequest()->get('submit');
    if (\Bitrix\Main\Context::getCurrent()->getRequest()->isPost() && $requestState === "Y")
        $itemPropErrors = $itemProp->checkErrors();



    if(in_array($property->getField("CODE"), ['COMMENT', 'UR_COMMENT'])):
        ?>
        <div class="basket__float-comment">
            <div class="toggled-elem">
                <?= $property->getName()?><?=$property->isRequired() ? ' *' : ''?>
                <svg class="dropdown_arrow"><use xlink:href="#arrow-up"></use></svg>
            </div>
            <?
            $text = '';
            foreach ($currentBasket->getBasketItems() as $basketItem) {
                $arHlElements = HLHelpers::getInstance()->getElementList(6, ['UF_ID' => $basketItem->getID(), 'UF_PRODUCT_ID' => $basketItem->getProductId()]);
                if(($arHlElements[0]['UF_HEIGHT'] && $arHlElements[0]['UF_WIDTH']) || $arHlElements[0]['UF_COLOR']) $text .= $basketItem->getField('NAME');
                if($arHlElements[0]['UF_HEIGHT'] && $arHlElements[0]['UF_WIDTH'])
                    $text .= ' ( Высота: '.$arHlElements[0]['UF_HEIGHT']. ' Ширина: '.$arHlElements[0]['UF_WIDTH'].' )';
                if($arHlElements[0]['UF_COLOR'])
                    $text .= ' ( Цвет RAL: '.$arHlElements[0]['UF_COLOR'].' )';
                $text .= PHP_EOL;
            }
            ?>
            <textarea class="toggled-item" name="PROP[<?= $property->getField("CODE")?>]" cols="30" rows="3" placeholder="<?=$property->getField("DESCRIPTION")?>" <?=$property->isRequired() ? ' required class="validate"' : ''?>><?=$text?><?=$itemProp->getValue()?></textarea>
        </div>
    <?
    else:
    ?>

        <? if($property->getField("TYPE") == 'Y/N'): ?>
            <div class="checkbox filters__checkbox">
                <input id="PROP_<?= $property->getId()?>" type="checkbox" value="Y" <?=$itemProp->getValue() == 'Y' ? ' checked ' : ''?>  name="PROP[<?= $property->getField("CODE")?>]"/>
                <label for="PROP_<?= $property->getId()?>">
                    <span class="checked_filter_params"><svg class="icon-tick"><use xlink:href="#tick"></use></svg></span>
                    <span class="bx-filter-param-text" title="Требуется вызов замерщика"><?= $property->getName()?></span>
                </label>
            </div>
        <? elseif($property->getField("TYPE") == 'LOCATION'):?>

            <div class="basket_order__subtitle" <?= !empty($storeInfo) ? ' style="display: none"' : ''?>>
                <?= $property->getName()?><?=$property->isRequired() ? ' *' : ''?>

                <?$APPLICATION->IncludeComponent(
                    "bitrix:sale.location.selector.search",
                    "",
                    Array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "ID" => "",
                        "CODE" => $itemProp->getValue(),
                        "INPUT_NAME" => "PROP[".$property->getField("CODE")."]",
                        "PROVIDE_LINK_BY" => "code",
                        "ONCITYCHANGE" => "submitForm",
                        "JS_CONTROL_DEFERRED_INIT" =>"",
                        "JS_CONTROL_GLOBAL_ID" => "",
                        "JS_CALLBACK" => "",
                        "FILTER_BY_SITE" => "Y",
                        "SHOW_DEFAULT_LOCATIONS" => "Y",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "FILTER_SITE_ID" => \Bitrix\Main\Context::getCurrent()->getSite(),
                        "INITIALIZE_BY_GLOBAL_EVENT" => "",
                        "SUPPRESS_ERRORS" => "N"
                    )
                );?>

                <?if(!empty($itemPropErrors->getErrorMessages())):?>
                    <span class="form__error error" style="display: block"><?=implode('<br>', $itemPropErrors->getErrorMessages())?></span>
                <?endif;?>
            </div>


        <? else:?>
            <label class="basket_order__subtitle">
                <?= $property->getName()?><?=$property->isRequired() ? ' *' : ''?>
                <input type="text" value="<?=$itemProp->getValue()?>" name="PROP[<?= $property->getField("CODE")?>]" placeholder="<?=$property->getField("DESCRIPTION")?>" <?=$property->isRequired() ? ' required class="validate"' : ''?>/>
                <?if(!empty($itemPropErrors->getErrorMessages())):?>
                    <span class="form__error error" style="display: block"><?=implode('<br>', $itemPropErrors->getErrorMessages())?></span>
                <?endif;?>
            </label>
        <? endif;?>
    <?endif;?>
<? endforeach;?>