<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |    Attention!
 * |    The following comments are for system use
 * |    and are required for the component to work correctly in ajax mode:
 * |    <!-- items-container -->
 * |    <!-- pagination-container -->
 * |    <!-- component-end -->
 */

$this->setFrameMode(true);

if (!empty($arResult['NAV_RESULT'])) {
    $navParams = array(
        'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
        'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
        'NavNum' => $arResult['NAV_RESULT']->NavNum
    );
} else {
    $navParams = array(
        'NavPageCount' => 1,
        'NavPageNomer' => 1,
        'NavNum' => $this->randString()
    );
}

global $USER;
$reserve = false;
$arFilter = array("ID" => $USER->GetID());
$arParams["SELECT"] = array("UF_CHECK_RESERVE");
$arRes = CUser::GetList($by,$desc,$arFilter,$arParams);
if ($res = $arRes->Fetch()) {
    $reserve = $res['UF_CHECK_RESERVE'];
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1) {
    $showTopPager = $arParams['DISPLAY_TOP_PAGER'];
    $showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
    $showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}

$templateLibrary = array('popup', 'ajax', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCS_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCS_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCS_CATALOG_RELATIVE_QUANTITY_FEW');

$arParams['MESS_BTN_LAZY_LOAD'] = $arParams['MESS_BTN_LAZY_LOAD'] ?: Loc::getMessage('CT_BCS_CATALOG_MESS_BTN_LAZY_LOAD');

$generalParams = array(
    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
    'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
    'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
    'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
    'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
    'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
    'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
    'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
    'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
    'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
    'COMPARE_PATH' => $arParams['COMPARE_PATH'],
    'COMPARE_NAME' => $arParams['COMPARE_NAME'],
    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
    'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
    'LABEL_POSITION_CLASS' => $labelPositionClass,
    'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
    'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
    'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
    '~BASKET_URL' => $arParams['~BASKET_URL'],
    '~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
    '~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
    '~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
    'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
    'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
    'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
    'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
    'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
    'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
    'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE']
);

$obName = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-' . $navParams['NavNum'];

if ($showTopPager) {
    ?>
    <div data-pagination-num="<?= $navParams['NavNum'] ?>">
        <!-- pagination-container -->
        <?= $arResult['NAV_STRING'] ?>
        <!-- pagination-container -->
    </div>
    <?
} ?>
<div class="b2b-content__wrp">
    <form type="get" action="<?= $APPLICATION->GetCurDir() ?>" class="b2b-head" id="com-offers-search">
        <div class="b2b-head__search w60">
            <input type="text" name="NUMBER" placeholder="Введите номер коммерческого предложения или заказа"
                   value="<?= $request['NUMBER'] ?>">
            <button type="submit" form="com-offers-search">
                <svg class="icon icon-search ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#search"></use>
                </svg>
            </button>
        </div>
        <div class="b2b-head__search w38">
            <input type="text" name="PHONE" placeholder="Введите номер телефона клиента"
                   value="<?= $request['PHONE'] ?>">
            <button type="submit" form="com-offers-search">
                <svg class="icon icon-search ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#search"></use>
                </svg>
            </button>
        </div>
    </form>
    <!-- items-container -->
    <div class="b2b-orders">
        <div class="b2b-table__wrp scroll-X js-editor__coord js-control-checked">
            <table class="b2b-table _editor js-b2b-table js-edit-wrp" style="width: 2000px"
                   data-entity="<?= $containerName ?>">
                <thead>
                <tr>
                    <th style="width: 8%;">
                        <div class="flex">
                            <span>Номер</span>
                            <div class="b2b-table__sort">
                                <div class="b2b-table__sort-ico">
                                    <svg class="icon icon-sort ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                    </svg>
                                </div>
                                <ul class="b2b-table__sort-ul no-right">
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_NUMBER&order=asc" <?if($request['sort'] == 'PROPERTY_NUMBER' && $request['order'] == 'asc'){?>checked<?}?>>
                                        <span>Сортировать по возрастанию</span>
                                    </li>
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_NUMBER&order=desc" <?if($request['sort'] == 'PROPERTY_NUMBER' && $request['order'] == 'desc'){?>checked<?}?>>
                                        <span>Сортировать по убыванию</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </th>
                    <th style="width: 7%;">
                        <div class="flex">
                            <span>Дата</span>
                            <div class="b2b-table__sort">
                                <div class="b2b-table__sort-ico">
                                    <svg class="icon icon-sort ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                    </svg>
                                </div>
                                <ul class="b2b-table__sort-ul">
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_DATE&order=asc" <?if($request['sort'] == 'PROPERTY_DATE' && $request['order'] == 'asc'){?>checked<?}?>>
                                        <span>Сортировать по возрастанию</span>
                                    </li>
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_DATE&order=desc" <?if($request['sort'] == 'PROPERTY_DATE' && $request['order'] == 'desc'){?>checked<?}?>>
                                        <span>Сортировать по убыванию</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </th>
                    <th style="width: 12%;">Клиент</th>
                    <th style="width: 9%;">Телефон</th>
                    <th style="width: 9%;">Город доставки</th>
                    <th style="width: 9%;">Адрес доставки</th>
                    <th style="width: 10%;">
                        <div class="flex">
                            <span>Сумма без скидки</span>
                            <div class="b2b-table__sort">
                                <div class="b2b-table__sort-ico">
                                    <svg class="icon icon-sort ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                    </svg>
                                </div>
                                <ul class="b2b-table__sort-ul">
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_SUM_W_STOCK&order=asc" <?if($request['sort'] == 'PROPERTY_SUM_W_STOCK' && $request['order'] == 'asc'){?>checked<?}?>>
                                        <span>Сортировать по возрастанию</span>
                                    </li>
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?sort=PROPERTY_SUM_W_STOCK&order=desc" <?if($request['sort'] == 'PROPERTY_SUM_W_STOCK' && $request['order'] == 'desc'){?>checked<?}?>>
                                        <span>Сортировать по убыванию</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </th>
                    <th style="width: 9%;">Скидка</th>
                    <th style="width: 11%;">Сумма со скидкой и НДС</th>
                    <th style="width: 11%;">Сумма закупки</th>
                    <th style="width: 9%;">
                        <div class="flex">
                            <span>Статус</span>
                            <div class="b2b-table__sort">
                                <div class="b2b-table__sort-ico">
                                    <svg class="icon icon-sort ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#sort"></use>
                                    </svg>
                                </div>
                                <ul class="b2b-table__sort-ul">
                                    <li>
                                        <input type="radio" class="js-sort" name="sort"
                                               data-href="?clear_filter=Y">
                                        <span>По умолчанию</span>
                                    </li>
                                    <? foreach ($arResult['PROPERTY_LIST_VAL']['STATUS'] as $arStatus) { ?>
                                        <li>
                                            <input type="radio" class="js-sort" name="sort"
                                                   data-href="?filter=PROPERTY_STATUS&value=<?= $arStatus['ID'] ?>" <?if($request['value'] == $arStatus['ID']){?>checked<?}?> >
                                            <span><?= $arStatus['VALUE'] ?></span>
                                        </li>
                                    <? } ?>
                                </ul>
                            </div>
                        </div>
                    </th>
                    <th style="width: 9%;">Тип оплаты</th>
                </tr>
                </thead>
                <? if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS'])) { ?>
                    <?
                    $areaIds = array();
                    foreach ($arResult['ITEMS'] as $item) {
                        $uniqueId = $item['ID'] . '_' . md5($this->randString() . $component->getAction());
                        $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
                        $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
                        $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
                    }
                    ?>
                    <tbody data-entity="items-row">
                    <? foreach ($arResult['ITEM_ROWS'] as $rowData) {
                        $rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']);
                        foreach ($rowItems as $item) {
                            $APPLICATION->IncludeComponent(
                                'bitrix:catalog.item',
                                'com-offers',
                                array(
                                    'RESULT' => array(
                                        'ITEM' => $item,
                                        'AREA_ID' => $areaIds[$item['ID']],
                                        'TYPE' => $rowData['TYPE'],
                                        'BIG_LABEL' => 'N',
                                        'BIG_DISCOUNT_PERCENT' => 'N',
                                        'BIG_BUTTONS' => 'N',
                                        'SCALABLE' => 'N',
                                        'PROPERTY_LIST_VAL' => $arResult['PROPERTY_LIST_VAL'],
                                    ),
                                    'PARAMS' => $generalParams
                                        + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                                ),
                                $component,
                                array('HIDE_ICONS' => 'Y')
                            );
                        }
                    }
                    unset($generalParams, $rowItems);
                    ?>
                    </tbody>
                <? } else {
                    if (!empty($request['NUMBER']) || !empty($request['PHONE']) || !empty($request['filter'])) {?>
                        <tbody>
                            <tr><td colspan="11" style="text-align: left;">По вашему запросу ничего не найдено</td></tr>
                        </tbody>
                    <? } else { ?>
                        <tbody>
                            <tr><td colspan="11" style="text-align: left;">Нет коммерческих предложений</td></tr>
                        </tbody>
                    <? }
                    // load css for bigData/deferred load
                    $APPLICATION->IncludeComponent(
                        'bitrix:catalog.item',
                        '',
                        array(),
                        $component,
                        array('HIDE_ICONS' => 'Y')
                    );
                } ?>
            </table>
        </div>
        <div class="b2b-editor__wrp js-edit__editor-wrp">
            <form class="b2b-editor js-edit__editor" id="edit">
                <select class="styler js-select" name="type">
                    <option value="">Выберите действие</option>
                    <option value="create_order">Создать заказ</option>
                    <?if($reserve) {?>
                        <option value="reserve_order">Создать заказ с резервом</option>
                    <?}?>
                    <option value="edit">Редактировать</option>
                    <option value="print">Печать</option>
                    <option value="del">Удалить</option>
                    <option value="copy">Копировать</option>
                    <option value="fix">Закрепить</option>
                </select>
                <button class="button" type="submit" form="edit">Применить</button>
                <div class="checkbox js-checkbox">
                    <input type="checkbox" id="all">
                    <label>Для всех</label>
                </div>
            </form>
        </div>
    </div>
    <!-- items-container -->
    <div class="pagination">
        <? if ($showLazyLoad) { ?>
            <a class="pagination-more" data-use="show-more-<?= $navParams['NavNum'] ?>"
               href="javascript:void(0)"> <?= $arParams['MESS_BTN_LAZY_LOAD'] ?></a>
        <? } ?>
        <? if ($showBottomPager) { ?>
            <div data-pagination-num="<?= $navParams['NavNum'] ?>" class="pagination-list">
                <!-- pagination-container -->
                <?= $arResult['NAV_STRING'] ?>
                <!-- pagination-container -->
            </div>
        <? } ?>
    </div>
</div>
<?
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'catalog.section');
$signedParams = $signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');
?>
<script>
    BX.message({
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BASKET_URL: '<?=$arParams['BASKET_URL']?>',
        ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
        BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
    });
    var <?=$obName?> =
    new JCCatalogSectionComponent({
        siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
        componentPath: '<?=CUtil::JSEscape($componentPath)?>',
        navParams: <?=CUtil::PhpToJSObject($navParams)?>,
        deferredLoad: false, // enable it for deferred load
        initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
        bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
        lazyLoad: !!'<?=$showLazyLoad?>',
        loadOnScroll: !!'<?=($arParams['LOAD_ON_SCROLL'] === 'Y')?>',
        template: '<?=CUtil::JSEscape($signedTemplate)?>',
        ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'])?>',
        parameters: '<?=CUtil::JSEscape($signedParams)?>',
        container: '<?=$containerName?>'
    });
</script>
<!-- component-end -->