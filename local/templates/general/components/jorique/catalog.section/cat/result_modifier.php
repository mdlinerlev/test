<?

use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
\Bitrix\Main\Loader::includeModule('dev2fun.opengraph');
\Dev2fun\Module\OpenGraph::Show($arResult['ID'],'section');
$arDefaultParams = array(
    'TEMPLATE_THEME' => 'blue',
    'PRODUCT_DISPLAY_MODE' => 'N',
    'ADD_PICT_PROP' => '-',
    'LABEL_PROP' => '-',
    'OFFER_ADD_PICT_PROP' => '-',
    'OFFER_TREE_PROPS' => array('-'),
    'PRODUCT_SUBSCRIPTION' => 'N',
    'SHOW_DISCOUNT_PERCENT' => 'N',
    'SHOW_OLD_PRICE' => 'N',
    'ADD_TO_BASKET_ACTION' => 'ADD',
    'SHOW_CLOSE_POPUP' => 'N',
    'MESS_BTN_BUY' => '',
    'MESS_BTN_ADD_TO_BASKET' => '',
    'MESS_BTN_SUBSCRIBE' => '',
    'MESS_BTN_DETAIL' => '',
    'MESS_NOT_AVAILABLE' => '',
    'MESS_BTN_COMPARE' => ''
);
$arParams = array_merge($arDefaultParams, $arParams);

if (!isset($arParams['LINE_ELEMENT_COUNT']))
    $arParams['LINE_ELEMENT_COUNT'] = 3;
$arParams['LINE_ELEMENT_COUNT'] = intval($arParams['LINE_ELEMENT_COUNT']);
if (2 > $arParams['LINE_ELEMENT_COUNT'] || 5 < $arParams['LINE_ELEMENT_COUNT'])
    $arParams['LINE_ELEMENT_COUNT'] = 3;

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME']) {
    $arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
    if ('site' == $arParams['TEMPLATE_THEME']) {
        $templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
        $templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
        $arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_' . $templateId . '_theme_id', 'blue', SITE_ID);
    }
    if ('' != $arParams['TEMPLATE_THEME']) {
        if (!is_file($_SERVER['DOCUMENT_ROOT'] . $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css'))
            $arParams['TEMPLATE_THEME'] = '';
    }
}
if ('' == $arParams['TEMPLATE_THEME'])
    $arParams['TEMPLATE_THEME'] = 'blue';
$arResult['NAV_PARAM']['TEMPLATE_THEME'] = $arParams['TEMPLATE_THEME'];

$arResult['NAV_STRING'] = $arResult['NAV_RESULT']->GetPageNavStringEx(
    $navComponentObject,
    $arParams['PAGER_TITLE'],
    $arParams['PAGER_TEMPLATE'],
    $arParams['PAGER_SHOW_ALWAYS'],
    $this->__component,
    $arResult['NAV_PARAM']
);

if ('Y' != $arParams['PRODUCT_DISPLAY_MODE'])
    $arParams['PRODUCT_DISPLAY_MODE'] = 'N';

$arParams['ADD_PICT_PROP'] = trim($arParams['ADD_PICT_PROP']);
if ('-' == $arParams['ADD_PICT_PROP'])
    $arParams['ADD_PICT_PROP'] = '';
$arParams['LABEL_PROP'] = trim($arParams['LABEL_PROP']);
if ('-' == $arParams['LABEL_PROP'])
    $arParams['LABEL_PROP'] = '';
$arParams['OFFER_ADD_PICT_PROP'] = trim($arParams['OFFER_ADD_PICT_PROP']);
if ('-' == $arParams['OFFER_ADD_PICT_PROP'])
    $arParams['OFFER_ADD_PICT_PROP'] = '';
if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']) {
    if (!is_array($arParams['OFFER_TREE_PROPS']))
        $arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
    foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value) {
        $value = (string)$value;
        if ('' == $value || '-' == $value)
            unset($arParams['OFFER_TREE_PROPS'][$key]);
    }
    if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES'])) {
        $arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
        foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value) {
            $value = (string)$value;
            if ('' == $value || '-' == $value)
                unset($arParams['OFFER_TREE_PROPS'][$key]);
        }
    }
} else {
    $arParams['OFFER_TREE_PROPS'] = array();
}
if ('Y' != $arParams['PRODUCT_SUBSCRIPTION'])
    $arParams['PRODUCT_SUBSCRIPTION'] = 'N';
if ('Y' != $arParams['SHOW_DISCOUNT_PERCENT'])
    $arParams['SHOW_DISCOUNT_PERCENT'] = 'N';
if ('Y' != $arParams['SHOW_OLD_PRICE'])
    $arParams['SHOW_OLD_PRICE'] = 'N';
if ($arParams['ADD_TO_BASKET_ACTION'] != 'BUY')
    $arParams['ADD_TO_BASKET_ACTION'] = 'ADD';
if ($arParams['SHOW_CLOSE_POPUP'] != 'Y')
    $arParams['SHOW_CLOSE_POPUP'] = 'N';
$arParams['MESS_BTN_BUY'] = trim($arParams['MESS_BTN_BUY']);
$arParams['MESS_BTN_ADD_TO_BASKET'] = trim($arParams['MESS_BTN_ADD_TO_BASKET']);
$arParams['MESS_BTN_SUBSCRIBE'] = trim($arParams['MESS_BTN_SUBSCRIBE']);
$arParams['MESS_BTN_DETAIL'] = trim($arParams['MESS_BTN_DETAIL']);
$arParams['MESS_NOT_AVAILABLE'] = trim($arParams['MESS_NOT_AVAILABLE']);
$arParams['MESS_BTN_COMPARE'] = trim($arParams['MESS_BTN_COMPARE']);

if (!empty($arResult['ITEMS'])) {
    $arEmptyPreview = false;
    $strEmptyPreview = $this->GetFolder() . '/images/no_photo.png';
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview)) {
        $arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview);
        if (!empty($arSizes)) {
            $arEmptyPreview = array(
                'SRC' => $strEmptyPreview,
                'WIDTH' => intval($arSizes[0]),
                'HEIGHT' => intval($arSizes[1])
            );
        }
        unset($arSizes);
    }
    unset($strEmptyPreview);

    $arSKUPropList = array();
    $arSKUPropIDs = array();
    $arSKUPropKeys = array();
    $boolSKU = false;
    $strBaseCurrency = '';
    $boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

    if ($arResult['MODULES']['catalog']) {
        if (!$boolConvert)
            $strBaseCurrency = CCurrency::GetBaseCurrency();

        $arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
        $boolSKU = !empty($arSKU) && is_array($arSKU);
        if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']) && 'Y' == $arParams['PRODUCT_DISPLAY_MODE']) {
            $arSKUPropList = CIBlockPriceTools::getTreeProperties(
                $arSKU,
                $arParams['OFFER_TREE_PROPS'],
                array(
                    'PICT' => $arEmptyPreview,
                    'NAME' => '-'
                )
            );

            $unicProp = 'GLASS_COLOR';
            $arEmptyPreviewGlass = [
                'SRC' => '/upload/no-glass.png',
                'WIDTH' => $arEmptyPreview['WIDTH'],
                'HEIGHT' => $arEmptyPreview['HEIGHT']
            ];
            $arSKUPropList2 = CIBlockPriceTools::getTreeProperties(
                $arSKU,
                [$unicProp],
                array(
                    'PICT' => $arEmptyPreviewGlass,
                    'NAME' => 'Без стекла'
                )
            );
            $arSKUPropList[$unicProp] = $arSKUPropList2[$unicProp];

            $arNeedValues = array();
            CIBlockPriceToolsNewsite::getTreePropertyValues($arSKUPropList, $arNeedValues);
            $arSKUPropIDs = array_keys($arSKUPropList);
            if (empty($arSKUPropIDs))
                $arParams['PRODUCT_DISPLAY_MODE'] = 'N';
            else
                $arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
        }
    }

    $arNewItemsList = $arResult['SECTION_ITEMS'] = array();

    foreach ($arResult['ITEMS'] as $key => $arItem) {
        foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
            if (!empty($arOffer['PREVIEW_PICTURE'])  && !is_array($arOffer['PREVIEW_PICTURE']))
                CIBlockPriceToolsNewsite::$fileID[$arOffer['PREVIEW_PICTURE']] = $arOffer['PREVIEW_PICTURE'];

            if (!empty($arOfferNew['DETAIL_PICTURE'])  && !is_array($arOffer['DETAIL_PICTURE']))
                CIBlockPriceToolsNewsite::$fileID[$arOffer['DETAIL_PICTURE']] = $arOffer['DETAIL_PICTURE'];
        }
    }

    CIBlockPriceToolsNewsite::GetImages();

    foreach ($arResult['ITEMS'] as $key => $arItem) {
        $arItem['CHECK_QUANTITY'] = false;
        if (!isset($arItem['CATALOG_MEASURE_RATIO']))
            $arItem['CATALOG_MEASURE_RATIO'] = 1;
        if (!isset($arItem['CATALOG_QUANTITY']))
            $arItem['CATALOG_QUANTITY'] = 0;
        $arItem['CATALOG_QUANTITY'] = (
        0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
            ? floatval($arItem['CATALOG_QUANTITY'])
            : intval($arItem['CATALOG_QUANTITY'])
        );
        $arItem['CATALOG'] = false;
        if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
            $arItem['CATALOG_SUBSCRIPTION'] = 'N';

        CIBlockPriceTools::getLabel($arItem, $arParams['LABEL_PROP']);

        $productPictures = CIBlockPriceToolsNewsite::getDoublePicturesForItem($arItem, $arParams['ADD_PICT_PROP']);
        if (empty($productPictures['PICT']))
            $productPictures['PICT'] = $arEmptyPreview;
        if (empty($productPictures['SECOND_PICT']))
            $productPictures['SECOND_PICT'] = $productPictures['PICT'];

        $arItem['PREVIEW_PICTURE'] = $productPictures['PICT'];
        $arItem['PREVIEW_PICTURE_SECOND'] = $productPictures['SECOND_PICT'];
        $arItem['SECOND_PICT'] = true;
        $arItem['PRODUCT_PREVIEW'] = $productPictures['PICT'];
        $arItem['PRODUCT_PREVIEW_SECOND'] = $productPictures['SECOND_PICT'];

        if ($arResult['MODULES']['catalog']) {
            $arItem['CATALOG'] = true;
            if (!isset($arItem['CATALOG_TYPE']))
                $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
            if (
                (CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
                && !empty($arItem['OFFERS'])
            ) {
                $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
            }
            switch ($arItem['CATALOG_TYPE']) {
                case CCatalogProduct::TYPE_SET:
                    $arItem['OFFERS'] = array();
                    $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                    break;
                case CCatalogProduct::TYPE_SKU:
                    break;
                case CCatalogProduct::TYPE_PRODUCT:
                default:
                    $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                    break;
            }
        } else {
            $arItem['CATALOG_TYPE'] = 0;
            $arItem['OFFERS'] = array();
        }

        if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
            if ('Y' == $arParams['PRODUCT_DISPLAY_MODE']) {
                $arMatrixFields = $arSKUPropKeys;
                $arMatrix = array();

                $arNewOffers = array();
                $boolSKUDisplayProperties = false;
                $arItem['OFFERS_PROP'] = false;
                $arItem['SKU_TREE_VALUES'] = array();

                $arDouble = array();
                foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {

                    if (!empty($arOffer['PREVIEW_PICTURE']) && CIBlockPriceToolsNewsite::$fileID[$arOffer['PREVIEW_PICTURE']])
                        $arOffer['PREVIEW_PICTURE'] = CIBlockPriceToolsNewsite::$fileID[$arOffer['PREVIEW_PICTURE']];

                    if (!empty($arOffer['DETAIL_PICTURE']) && CIBlockPriceToolsNewsite::$fileID[$arOffer['DETAIL_PICTURE']])
                        $arOffer['DETAIL_PICTURE'] = CIBlockPriceToolsNewsite::$fileID[$arOffer['DETAIL_PICTURE']];


                    $arOffer['ID'] = (int)$arOffer['ID'];
                    if (isset($arDouble[$arOffer['ID']]))
                        continue;
                    $arRow = array();
                    foreach ($arSKUPropIDs as $propkey => $strOneCode) {
                        $arCell = array(
                            'VALUE' => 0,
                            'SORT' => PHP_INT_MAX,
                            'NA' => true
                        );
                        if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode])) {
                            $arMatrixFields[$strOneCode] = true;
                            $arCell['NA'] = false;
                            if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE']) {
                                $intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
                                $arCell['VALUE'] = $intValue;
                            } elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
                                $arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
                            } elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE']) {
                                $arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
                            }
                            $arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
                        }
                        $arRow[$strOneCode] = $arCell;
                    }
                    $arMatrix[$keyOffer] = $arRow;
                    unset($arRow);

                    CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']);

                    CIBlockPriceTools::setRatioMinPrice($arOffer, false);

                    $offerPictures = CIBlockPriceToolsNewsite::getDoublePicturesForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP']);
                    $arOffer['OWNER_PICT'] = empty($offerPictures['PICT']);
                    $arOffer['PREVIEW_PICTURE'] = false;
                    $arOffer['PREVIEW_PICTURE_SECOND'] = false;
                    $arOffer['SECOND_PICT'] = true;
                    if (!$arOffer['OWNER_PICT']) {
                        if (empty($offerPictures['SECOND_PICT']))
                            $offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
                        $arOffer['PREVIEW_PICTURE'] = $offerPictures['PICT'];
                        $arOffer['PREVIEW_PICTURE_SECOND'] = $offerPictures['SECOND_PICT'];
                    }
                    if ('' != $arParams['OFFER_ADD_PICT_PROP'] && isset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]))
                        unset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]);

                    $arDouble[$arOffer['ID']] = true;
                    $arNewOffers[$keyOffer] = $arOffer;
                }
                unset($keyOffer, $arOffer);
                $arItem['OFFERS'] = $arNewOffers;

                $arUsedFields = array();
                $arSortFields = array();

                $matrixKeys = array_keys($arMatrix);
                foreach ($arSKUPropIDs as $propkey => $propCode) {
                    $boolExist = $arMatrixFields[$propCode];
                    foreach ($matrixKeys as $keyOffer) {
                        if ($boolExist) {
                            if (!isset($arItem['OFFERS'][$keyOffer]['TREE']))
                                $arItem['OFFERS'][$keyOffer]['TREE'] = array();
                            $propId = $arSKUPropList[$propCode]['ID'];
                            $value = $arMatrix[$keyOffer][$propCode]['VALUE'];
                            if (!isset($arItem['SKU_TREE_VALUES'][$propId]))
                                $arItem['SKU_TREE_VALUES'][$propId] = array();
                            $arItem['SKU_TREE_VALUES'][$propId][$value] = true;
                            $arItem['OFFERS'][$keyOffer]['TREE']['PROP_' . $propId] = $value;
                            $arItem['OFFERS'][$keyOffer]['SKU_SORT_' . $propCode] = $arMatrix[$keyOffer][$propCode]['SORT'];
                            $arUsedFields[$propCode] = true;
                            $arSortFields['SKU_SORT_' . $propCode] = SORT_NUMERIC;
                            unset($value, $propId);
                        } else {
                            unset($arMatrix[$keyOffer][$propCode]);
                        }
                    }
                    unset($keyOffer);
                }
                unset($propkey, $propCode);
                unset($matrixKeys);
                $arItem['OFFERS_PROP'] = $arUsedFields;
                $arItem['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

                Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

                $arMatrix = array();
                $intSelected = -1;
                $arItem['MIN_PRICE'] = false;
                $arItem['MIN_BASIS_PRICE'] = false;

                usort($arItem['OFFERS'], function($a, $b){
                    $a = empty($arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE']) ? 500 : $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'] ;
                    $b = empty($arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE']) ? 500 : $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'];
                    if ($a == $b) {
                        return 0;
                    }
                    return ($a < $b) ? -1 : 1;
                });
                $top = 10000000;
                $selec = 0;

                foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
                    if (empty($arItem['MIN_PRICE'])) {
                        if ($arItem['OFFER_ID_SELECTED'] > 0)
                            $foundOffer = ($arItem['OFFER_ID_SELECTED'] == $arOffer['ID']);
                        else
                            $foundOffer = $arOffer['CAN_BUY'];
                        if ($foundOffer) {
                            $intSelected = $keyOffer;
                            $arItem['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
                            $arItem['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
                        }
                        unset($foundOffer);
                    }

                    $arSKUProps = false;
                    if (!empty($arOffer['DISPLAY_PROPERTIES'])) {
                        $boolSKUDisplayProperties = true;
                        $arSKUProps = array();
                        foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp) {
                            if ('F' == $arOneProp['PROPERTY_TYPE'])
                                continue;
                            $arSKUProps[] = array(
                                'NAME' => $arOneProp['NAME'],
                                'CODE' => $arOneProp['CODE'],
                                'VALUE' => $arOneProp['DISPLAY_VALUE']
                            );
                        }
                        unset($arOneProp);
                    }

                    if($top > $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'] && $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE']) {
                        $top = $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'];
                        $selec = $arOffer['ID'];
                    }

                    $arOneRow = array(
                        'ID' => $arOffer['ID'],
                        'NAME' => $arOffer['NAME']?:'qwe',
                        'SORT' => empty($arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE']) ? 500 : $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'],
                        'TREE' => $arOffer['TREE'],
                        'DISPLAY_PROPERTIES' => $arSKUProps,
                        'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
                        'BASIS_PRICE' => $arOffer['MIN_PRICE'],
                        'SECOND_PICT' => $arOffer['SECOND_PICT'],
                        'OWNER_PICT' => $arOffer['OWNER_PICT'],
                        'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
                        'PREVIEW_PICTURE_SECOND' => $arOffer['PREVIEW_PICTURE_SECOND'],
                        'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
                        'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
                        'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
                        'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
                        'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
                        'CAN_BUY' => $arOffer['CAN_BUY'],
                        'SKY_PROP' => [
                            $arOffer['PROPERTIES']['SIZE']['VALUE'] => $arOffer['PROPERTIES']['SIZE']['VALUE'],
                            $arOffer['PROPERTIES']['COLOR']['VALUE'] =>  $arOffer['PROPERTIES']['COLOR']['VALUE'],
                            $arOffer['PROPERTIES']['GLASS_COLOR']['VALUE'] =>  $arOffer['PROPERTIES']['GLASS_COLOR']['VALUE']],
                    );
                    $arMatrix[$keyOffer] = $arOneRow;
                }
                $arrSelect[$arItem['ID']] = $selec;
                if (-1 == $intSelected) {
                    $intSelected = 0;
                    $arItem['MIN_PRICE'] = (isset($arItem['OFFERS'][0]['RATIO_PRICE']) ? $arItem['OFFERS'][0]['RATIO_PRICE'] : $arItem['OFFERS'][0]['MIN_PRICE']);
                    $arItem['MIN_BASIS_PRICE'] = $arItem['OFFERS'][0]['MIN_PRICE'];
                }
                if (!$arMatrix[$intSelected]['OWNER_PICT']) {
                    $arItem['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
                    $arItem['PREVIEW_PICTURE_SECOND'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SECOND'];
                }
                $arItem['JS_OFFERS'] = $arMatrix;
                $arItem['OFFERS_SELECTED'] = $intSelected;
                $arItem['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
            } else {
                $arItem['MIN_PRICE'] = CIBlockPriceTools::getMinPriceFromOffers(
                    $arItem['OFFERS'],
                    $boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
                );
            }
        }

        if (
            $arResult['MODULES']['catalog']
            && $arItem['CATALOG']
            &&
            ($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
                || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
        ) {
            CIBlockPriceTools::setRatioMinPrice($arItem, false);
            $arItem['MIN_BASIS_PRICE'] = $arItem['MIN_PRICE'];
        }

        if (!empty($arItem['DISPLAY_PROPERTIES'])) {
            foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp) {
                if ('F' == $arDispProp['PROPERTY_TYPE'])
                    unset($arItem['DISPLAY_PROPERTIES'][$propKey]);
            }
        }
        $arItem['LAST_ELEMENT'] = 'N';

        if (!empty($arItem["~IBLOCK_SECTION_ID"]))
            $arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]] = $arItem["~IBLOCK_SECTION_ID"];

        if (!empty($arItem["IBLOCK_SECTION_ID"]))
            $arResult['SECTION_ITEMS'][$arItem["IBLOCK_SECTION_ID"]] = $arItem["IBLOCK_SECTION_ID"];

        $arNewItemsList[$key] = $arItem;
    }

    if (!empty($arResult["IBLOCK_SECTION_ID"]))
        $arResult['SECTION_ITEMS'][$arResult["IBLOCK_SECTION_ID"]] = $arResult["IBLOCK_SECTION_ID"];

    if (!empty($arResult["~IBLOCK_SECTION_ID"]))
        $arResult['SECTION_ITEMS'][$arResult["~IBLOCK_SECTION_ID"]] = $arResult["~IBLOCK_SECTION_ID"];


    if (!empty($arResult['SECTION_ITEMS'])) {
        $res = CIBlockSection::GetList(Array(), ['=ID' => array_keys($arResult['SECTION_ITEMS'])], false, ['ID', 'CODE']);
        $arResult['SECTION_ITEMS'] = [];
        while ($arSection = $res->GetNext()) {
            $arResult['SECTION_ITEMS'][$arSection['ID']] = $arSection;
        }
    }

    $arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
    $arResult['ITEMS'] = $arNewItemsList;
    $arResult['SKU_PROPS'] = $arSKUPropList;
    $arResult['DEFAULT_PICTURE'] = $arEmptyPreview;


    //pr();
    $arResult['CURRENCIES'] = array();
    if ($arResult['MODULES']['currency']) {
        if ($boolConvert) {
            $currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
            $arResult['CURRENCIES'] = array(
                array(
                    'CURRENCY' => $arResult['CONVERT_CURRENCY']['CURRENCY_ID'],
                    'FORMAT' => array(
                        'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
                        'DEC_POINT' => $currencyFormat['DEC_POINT'],
                        'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
                        'DECIMALS' => $currencyFormat['DECIMALS'],
                        'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
                        'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
                    )
                )
            );
            unset($currencyFormat);
        } else {
            $currencyIterator = CurrencyTable::getList(array(
                'select' => array('CURRENCY')
            ));
            while ($currency = $currencyIterator->fetch()) {
                $currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
                $arResult['CURRENCIES'][] = array(
                    'CURRENCY' => $currency['CURRENCY'],
                    'FORMAT' => array(
                        'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
                        'DEC_POINT' => $currencyFormat['DEC_POINT'],
                        'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
                        'DECIMALS' => $currencyFormat['DECIMALS'],
                        'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
                        'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
                    )
                );
            }
            unset($currencyFormat, $currency, $currencyIterator);
        }
    }

    $height300 = true;
    foreach ($arResult['ITEMS'] as &$arItem) {
        if($arItem['PROPERTIES']['CONFIGURATION']['VALUE'] != 'Распашная') $height300 = false;
        if (empty($arItem['PREVIEW_PICTURE'])) {
            $arItem['PREVIEW_PICTURE']['SRC'] = SITE_TEMPLATE_PATH.'/images/no_photo1.png';
        }
        $src = false;
        $arWaterMark = PICTURE_WATER_MARK;
        $arItem['PREVIEW_PICTURE_ORIG'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 280,'height' => 280), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false);
        //pr($arWaterMark);
        $arItem['PREVIEW_PICTURE_ORIG']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem['PREVIEW_PICTURE_ORIG']['SRC']);
        $firstSectionItemCode = !empty($arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]]) ? $arResult['SECTION_ITEMS'][$arItem["~IBLOCK_SECTION_ID"]]['CODE'] : false;
        $arWaterMark = [];
        if (in_array($firstSectionItemCode, ['mezhkomnatnye_dveri', 'vkhodnye_dveri']))
            $arWaterMark = PICTURE_WATER_MARK;

        $arItem['PREVIEW_PICTURE_SECOND'] = false;
        if (!empty($arItem['PREVIEW_PICTURE'])) {

            $sectionItemsCode = $arResult['SECTION_ITEMS'][$arItem['IBLOCK_SECTION_ID']]['CODE'];
            if (in_array($sectionItemsCode, array('plintus', 'parket', 'laminat'))) {
                $src = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array(
                    'width' => 293,
                    'height' => 293
                ), BX_RESIZE_IMAGE_EXACT);
            } else if($arItem['PREVIEW_PICTURE']['ID']) {
                $arWaterMark = PICTURE_WATER_MARK;
                $src = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array(
                    'width' => 280,
                    'height' => 280
                ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark);
            }

            if($src){
                $arItem['PREVIEW_PICTURE']['SRC'] = $src['src'];
                $arItem['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem['PREVIEW_PICTURE']['SRC']);
            }
            $arItem['PRODUCT_PREVIEW'] = $arItem['PICTURE_SECOND'] = $arItem['PREVIEW_PICTURE'];
        } elseif (!empty($arItem['DETAIL_PICTURE'])) {

            $sectionItemsCode = $arResult['SECTION_ITEMS'][$arItem['IBLOCK_SECTION_ID']]['CODE'];
            if (in_array($sectionItemsCode, array('plintus', 'parket', 'laminat'))) {
                $src = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array(
                    'width' => 293,
                    'height' => 293
                ), BX_RESIZE_IMAGE_EXACT);
            } else {
                $arWaterMark = PICTURE_WATER_MARK;
                $src = CFile::ResizeImageGet($arItem['DETAIL_PICTURE'], array(
                    'width' => 280,
                    'height' => 280
                ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark);
            }
            $arItem['DETAIL_PICTURE']['SRC'] = $src['src'];
            $arItem['DETAIL_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem['DETAIL_PICTURE']['SRC']);
            $arItem['PRODUCT_PREVIEW'] = $arItem['PICTURE_SECOND'] = $arItem['DETAIL_PICTURE'];
        }

        if (!empty($arItem['OFFERS'])) {
            foreach (['OFFERS', 'JS_OFFERS'] as $code) {
                foreach ($arItem[$code] as $key => $offer) {
                    $arWaterMark = PICTURE_WATER_MARK;
                    $arItem[$code][$key]['PREVIEW_PICTURE_ORIG'] = CFile::ResizeImageGet($offer['PREVIEW_PICTURE'], array(
                        'width' => 280,
                        'height' => 280
                    ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, false);
                    //pr($arWaterMark);
                    $arItem[$code][$key]['PREVIEW_PICTURE_SECOND'] = false;

                    if (!empty($offer['PREVIEW_PICTURE'])) {
                        $arWaterMark = PICTURE_WATER_MARK;
                        $src = CFile::ResizeImageGet($offer['PREVIEW_PICTURE'], array(
                            'width' => 280,
                            'height' => 280
                        ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark);
                        $arItem[$code][$key]['PREVIEW_PICTURE']['SRC'] = $src['src'];
                        try {
                            $arItem[$code][$key]['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem[$code][$key]['PREVIEW_PICTURE']['SRC']);
                        } catch (Exception $c) {
                            //echo 'Выброшено исключение: ',  $c->getMessage(), "\n";
                        }
                        
                    } elseif (!empty($offer['DETAIL_PICTURE'])) {
                        $arWaterMark = PICTURE_WATER_MARK;
                        $src = CFile::ResizeImageGet($offer['DETAIL_PICTURE'], array(
                            'width' => 280,
                            'height' => 280
                        ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, $arWaterMark);
                        $arItem[$code][$key]['DETAIL_PICTURE']['SRC'] = $src['src'];
                        $arItem[$code][$key]['DETAIL_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem[$code][$key]['DETAIL_PICTURE']['SRC']);
                    }
                    //usleep(0.3);
                }
            }
        }

    }
    if($height300) $arResult['HEIGHT300'] = true;

    unset($arItem);


}

/*foreach ($arResult['ITEMS'] as $key => $arItem) {
	$arResult['ITEMS'][$key]['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arItem['PREVIEW_PICTURE']['SRC']);
}*/

// Подгрузка контента  при скролинге
if (array_key_exists("IS_AJAX", $_REQUEST) && $_REQUEST["IS_AJAX"] == "Y") {
    $APPLICATION->RestartBuffer();
}

foreach ($arResult['ITEMS'] as $key => $itemElement) {
    $arResult['ITEMS'][$key]['OFFER_SELECTED_MIN']['PRICE'] = 10000000000000;
    $arResult['ITEMS'][$key]['OFFER_SELECTED_MAX']['PRICE'] = 0;
    foreach ($itemElement['OFFERS'] as $key2 => $offer) {
       /* if($key2 == 0) {
            $arResult['ITEMS'][$key]['TEMP_PRICE'] = $offer['CATALOG_PRICE_1'];
            //$arResult['ITEMS'][$key]['TEMP_PRICE111'] = $key2;
        }
        if($arResult['ITEMS'][$key]['OFFER_SELECTED'] == $key2) {
            $arResult['ITEMS'][$key]['TEMP_PRICE'] = $offer['CATALOG_PRICE_1'];
        }
        if($arResult['ITEMS'][$key]['OFFER_ID_SELECTED'] == $offer['ID']) {
            $arResult['ITEMS'][$key]['TEMP_PRICE'] = $offer['CATALOG_PRICE_1'];
        }*/
        /*if($arResult['ITEMS'][$key]['OFFER_SELECTED_MIN']['PRICE'] > $offer['CATALOG_PRICE_1']) {
            $arResult['ITEMS'][$key]['OFFER_SELECTED_MIN']['PRICE'] = $offer['CATALOG_PRICE_1'];
            $arResult['ITEMS'][$key]['OFFER_SELECTED_MIN']['KEY'] = $key2;
        }
        if($arResult['ITEMS'][$key]['OFFER_SELECTED_MAX']['PRICE'] < $offer['CATALOG_PRICE_1']) {
            $arResult['ITEMS'][$key]['OFFER_SELECTED_MAX']['PRICE'] = $offer['CATALOG_PRICE_1'];
            $arResult['ITEMS'][$key]['OFFER_SELECTED_MAX']['KEY'] = $key2;
        }*/
        if($arrSelect[$itemElement['ID']] && $offer['ID'] == $arrSelect[$itemElement['ID']]) {
            $arResult['ITEMS'][$key]['PREVIEW_PICTURE'] = $arResult['ITEMS'][$key]['OFFERS'][$key2]['PREVIEW_PICTURE'];
            $arResult['ITEMS'][$key]['OFFER_ID_SELECTED'] = $arrSelect[$itemElement['ID']];
            $arResult['ITEMS'][$key]['OFFER_SELECTED2'] = $key2;
            //$arResult['ITEMS'][$key]['TEMP_PRICE'] = $offer['CATALOG_PRICE_1'];
            //$arResult['ITEMS'][$key]['TEMP_PRICE111'] = $key2;
        }


    }
    //pr($arResult['ITEMS'][$key]['TEMP_PRICE111']);
    if (!empty($itemElement['PROPERTIES']['ML_INCLUDE_ICON']['VALUE'])) {
        $arSelect = ["ID", "NAME", "PROPERTY_ICON_SLIDER_CARD", "PROPERTY_PATH_ICON"];
        $arFilter = ["IBLOCK_ID" => IBLOCK_ID_ICON, "ID" => $itemElement['PROPERTIES']['ML_INCLUDE_ICON']['VALUE']];
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNext()) {
            $img = CFile::GetPath($ob['PROPERTY_ICON_SLIDER_CARD_VALUE']) . $ob['PROPERTY_PATH_ICON_VALUE'];
            $resultElemIcon[] = [
                "NAME" => $ob['NAME'],
                "ICON_PATH" => $img,
            ];
        }
        $arResult['ITEMS'][$key] += ["ICON_PROPERTIES" => $resultElemIcon];
    }
}


function cmp_function($a, $b){
    return ($a['TEMP_PRICE'] > $b['TEMP_PRICE']);
}
function cmp_function_desc($a, $b){
    return ($a['TEMP_PRICE'] < $b['TEMP_PRICE']);
}

/*if($_GET['sort'] == 'property_MINIMUM_PRICE') {
    if($_GET['method'] == 'asc'){
        uasort($arResult['ITEMS'], 'cmp_function');
    } else {
        uasort($arResult['ITEMS'], 'cmp_function_desc');
    }
}
*/
$colorKey = 339;
$colorVal = $arResult['SKU_PROPS']['COLOR']['VALUES'][$colorKey];
//unset($arResult['SKU_PROPS']['COLOR']['VALUES'][339]);
//unset($arResult['SKU_PROPS']['GLASS_COLOR']['VALUES'][339]);
$arResult['SKU_PROPS']['COLOR']['VALUES'][$colorKey] = $colorVal;
$arResult['SKU_PROPS']['GLASS_COLOR']['VALUES'][$colorKey] = $colorVal;
//unset($arResult['SKU_PROPS']['COLOR_OUT']['VALUES'][339]);
//unset($arResult['SKU_PROPS']['COLOR_IN']['VALUES'][339]);
//unset($arResult['SKU_PROPS']['GROUP_COLOR']['VALUES'][339]);
//unset($arResult['SKU_PROPS']['HARDWARE_COLOR']['VALUES'][339]);
//pr($arResult['SKU_PROPS']['COLOR']['VALUES']);
//$arResult['SKU_PROPS']['COLOR']['VALUES'][339]['NAME'] = '123';
//pr($arResult['SKU_PROPS']['SIZE']['VALUES']);