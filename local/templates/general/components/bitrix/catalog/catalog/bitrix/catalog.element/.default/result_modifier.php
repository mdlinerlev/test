<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Sotbit\Seometa\SeometaUrlTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

//$arResult["TEMPLATE_DIR"] = "belwood";
\Bitrix\Main\Loader::includeModule('dev2fun.opengraph');
\Dev2fun\Module\OpenGraph::Show($arResult['ID'],'element');
$arResult["TEMPLATE_DIR"] = explode($_SERVER["SERVER_NAME"], __DIR__)[1];

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
$displayPreviewTextMode = array(
    'H' => true,
    'E' => true,
    'S' => true
);
$detailPictMode = array(
    'IMG' => true,
    'POPUP' => true,
    'MAGNIFIER' => true,
    'GALLERY' => true
);

$arDefaultParams = array(
    'TEMPLATE_THEME' => 'blue',
    'ADD_PICT_PROP' => '-',
    'LABEL_PROP' => '-',
    'OFFER_ADD_PICT_PROP' => '-',
    'OFFER_TREE_PROPS' => array('-'),
    'DISPLAY_NAME' => 'Y',
    'DETAIL_PICTURE_MODE' => 'IMG',
    'ADD_DETAIL_TO_SLIDER' => 'N',
    'DISPLAY_PREVIEW_TEXT_MODE' => 'E',
    'PRODUCT_SUBSCRIPTION' => 'N',
    'SHOW_DISCOUNT_PERCENT' => 'N',
    'SHOW_OLD_PRICE' => 'N',
    'SHOW_MAX_QUANTITY' => 'N',
    'SHOW_BASIS_PRICE' => 'N',
    'ADD_TO_BASKET_ACTION' => array('BUY'),
    'SHOW_CLOSE_POPUP' => 'N',
    'MESS_BTN_BUY' => '',
    'MESS_BTN_ADD_TO_BASKET' => '',
    'MESS_BTN_SUBSCRIBE' => '',
    'MESS_BTN_COMPARE' => '',
    'MESS_NOT_AVAILABLE' => '',
    'USE_VOTE_RATING' => 'N',
    'VOTE_DISPLAY_AS_RATING' => 'rating',
    'USE_COMMENTS' => 'N',
    'BLOG_USE' => 'N',
    'BLOG_URL' => 'catalog_comments',
    'BLOG_EMAIL_NOTIFY' => 'N',
    'VK_USE' => 'N',
    'VK_API_ID' => '',
    'FB_USE' => 'N',
    'FB_APP_ID' => '',
    'BRAND_USE' => 'N',
    'BRAND_PROP_CODE' => ''
);
$arParams = array_merge($arDefaultParams, $arParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
    $arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
    if ('site' == $arParams['TEMPLATE_THEME'])
    {
        $templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
        $templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
        $arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
    }
    if ('' != $arParams['TEMPLATE_THEME'])
    {
        if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
            $arParams['TEMPLATE_THEME'] = '';
    }
}
if ('' == $arParams['TEMPLATE_THEME'])
    $arParams['TEMPLATE_THEME'] = 'blue';

$arParams['ADD_PICT_PROP'] = trim($arParams['ADD_PICT_PROP']);
if ('-' == $arParams['ADD_PICT_PROP'])
    $arParams['ADD_PICT_PROP'] = '';
$arParams['LABEL_PROP'] = trim($arParams['LABEL_PROP']);
if ('-' == $arParams['LABEL_PROP'])
    $arParams['LABEL_PROP'] = '';
$arParams['OFFER_ADD_PICT_PROP'] = trim($arParams['OFFER_ADD_PICT_PROP']);
if ('-' == $arParams['OFFER_ADD_PICT_PROP'])
    $arParams['OFFER_ADD_PICT_PROP'] = '';
if (!is_array($arParams['OFFER_TREE_PROPS']))
    $arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
{
    $value = (string)$value;
    if ('' == $value || '-' == $value)
        unset($arParams['OFFER_TREE_PROPS'][$key]);
}
if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES']))
{
    $arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
    foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
    {
        $value = (string)$value;
        if ('' == $value || '-' == $value)
            unset($arParams['OFFER_TREE_PROPS'][$key]);
    }
}
if ('N' != $arParams['DISPLAY_NAME'])
    $arParams['DISPLAY_NAME'] = 'Y';
if (!isset($detailPictMode[$arParams['DETAIL_PICTURE_MODE']]))
    $arParams['DETAIL_PICTURE_MODE'] = 'IMG';
if ('Y' != $arParams['ADD_DETAIL_TO_SLIDER'])
    $arParams['ADD_DETAIL_TO_SLIDER'] = 'N';
if (!isset($displayPreviewTextMode[$arParams['DISPLAY_PREVIEW_TEXT_MODE']]))
    $arParams['DISPLAY_PREVIEW_TEXT_MODE'] = 'E';
if ('Y' != $arParams['PRODUCT_SUBSCRIPTION'])
    $arParams['PRODUCT_SUBSCRIPTION'] = 'N';
if ('Y' != $arParams['SHOW_DISCOUNT_PERCENT'])
    $arParams['SHOW_DISCOUNT_PERCENT'] = 'N';
if ('Y' != $arParams['SHOW_OLD_PRICE'])
    $arParams['SHOW_OLD_PRICE'] = 'N';
if ('Y' != $arParams['SHOW_MAX_QUANTITY'])
    $arParams['SHOW_MAX_QUANTITY'] = 'N';
if ($arParams['SHOW_BASIS_PRICE'] != 'Y')
    $arParams['SHOW_BASIS_PRICE'] = 'N';
if (!is_array($arParams['ADD_TO_BASKET_ACTION']))
    $arParams['ADD_TO_BASKET_ACTION'] = array($arParams['ADD_TO_BASKET_ACTION']);
$arParams['ADD_TO_BASKET_ACTION'] = array_filter($arParams['ADD_TO_BASKET_ACTION'], 'CIBlockParameters::checkParamValues');
if (empty($arParams['ADD_TO_BASKET_ACTION']) || (!in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']) && !in_array('BUY', $arParams['ADD_TO_BASKET_ACTION'])))
    $arParams['ADD_TO_BASKET_ACTION'] = array('BUY');
if ($arParams['SHOW_CLOSE_POPUP'] != 'Y')
    $arParams['SHOW_CLOSE_POPUP'] = 'N';

$arParams['MESS_BTN_BUY'] = trim($arParams['MESS_BTN_BUY']);
$arParams['MESS_BTN_ADD_TO_BASKET'] = trim($arParams['MESS_BTN_ADD_TO_BASKET']);
$arParams['MESS_BTN_SUBSCRIBE'] = trim($arParams['MESS_BTN_SUBSCRIBE']);
$arParams['MESS_BTN_COMPARE'] = trim($arParams['MESS_BTN_COMPARE']);
$arParams['MESS_NOT_AVAILABLE'] = trim($arParams['MESS_NOT_AVAILABLE']);
if ('Y' != $arParams['USE_VOTE_RATING'])
    $arParams['USE_VOTE_RATING'] = 'N';
if ('vote_avg' != $arParams['VOTE_DISPLAY_AS_RATING'])
    $arParams['VOTE_DISPLAY_AS_RATING'] = 'rating';
if ('Y' != $arParams['USE_COMMENTS'])
    $arParams['USE_COMMENTS'] = 'N';
if ('Y' != $arParams['BLOG_USE'])
    $arParams['BLOG_USE'] = 'N';
if ('Y' != $arParams['VK_USE'])
    $arParams['VK_USE'] = 'N';
if ('Y' != $arParams['FB_USE'])
    $arParams['FB_USE'] = 'N';
if ('Y' == $arParams['USE_COMMENTS'])
{
    if ('N' == $arParams['BLOG_USE'] && 'N' == $arParams['VK_USE'] && 'N' == $arParams['FB_USE'])
        $arParams['USE_COMMENTS'] = 'N';
}
if ('Y' != $arParams['BRAND_USE'])
    $arParams['BRAND_USE'] = 'N';
if ($arParams['BRAND_PROP_CODE'] == '')
    $arParams['BRAND_PROP_CODE'] = array();
if (!is_array($arParams['BRAND_PROP_CODE']))
    $arParams['BRAND_PROP_CODE'] = array($arParams['BRAND_PROP_CODE']);

$arEmptyPreview = false;
$strEmptyPreview = $this->GetFolder().'/images/no_photo.png';
if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
{
    $arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
    if (!empty($arSizes))
    {
        $arEmptyPreview = array(
            'SRC' => $strEmptyPreview,
            'WIDTH' => (int)$arSizes[0],
            'HEIGHT' => (int)$arSizes[1]
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

if ($arResult['MODULES']['catalog'])
{
    if (!$boolConvert)
        $strBaseCurrency = CCurrency::GetBaseCurrency();

    $arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
    $boolSKU = !empty($arSKU) && is_array($arSKU);

    if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']))
    {
        $arSKUPropList = CIBlockPriceTools::getTreeProperties(
            $arSKU,
            $arParams['OFFER_TREE_PROPS'],
            array(
                'PICT' => $arEmptyPreview,
                'NAME' => '-'
            )
        );
        $arSKUPropIDs = array_keys($arSKUPropList);
    }
}

$arResult['CHECK_QUANTITY'] = false;
if (!isset($arResult['CATALOG_MEASURE_RATIO']))
    $arResult['CATALOG_MEASURE_RATIO'] = 1;
if (!isset($arResult['CATALOG_QUANTITY']))
    $arResult['CATALOG_QUANTITY'] = 0;
$arResult['CATALOG_QUANTITY'] = (
	0 < $arResult['CATALOG_QUANTITY'] && is_float($arResult['CATALOG_MEASURE_RATIO'])
	? (float)$arResult['CATALOG_QUANTITY']
	: (int)$arResult['CATALOG_QUANTITY']
);
$arResult['CATALOG'] = false;
if (!isset($arResult['CATALOG_SUBSCRIPTION']) || 'Y' != $arResult['CATALOG_SUBSCRIPTION'])
    $arResult['CATALOG_SUBSCRIPTION'] = 'N';

CIBlockPriceTools::getLabel($arResult, $arParams['LABEL_PROP']);

$productSlider = CIBlockPriceTools::getSliderForItem($arResult, $arParams['ADD_PICT_PROP'], 'Y' == $arParams['ADD_DETAIL_TO_SLIDER']);
if (empty($productSlider))
{
    $productSlider = array(
        0 => $arEmptyPreview
    );
}
$productSliderCount = count($productSlider);
$arResult['SHOW_SLIDER'] = true;
$arResult['MORE_PHOTO'] = $productSlider;
$arResult['MORE_PHOTO_COUNT'] = count($productSlider);

if ($arResult['MODULES']['catalog'])
{
    $arResult['CATALOG'] = true;
    if (!isset($arResult['CATALOG_TYPE']))
        $arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
    if (
        (CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arResult['CATALOG_TYPE'])
        && !empty($arResult['OFFERS'])
    )
    {
        $arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
    }
    switch ($arResult['CATALOG_TYPE'])
    {
        case CCatalogProduct::TYPE_SET:
            $arResult['OFFERS'] = array();
            $arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
            break;
        case CCatalogProduct::TYPE_SKU:
            break;
        case CCatalogProduct::TYPE_PRODUCT:
        default:
            $arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
            break;
    }
}
else
{
    $arResult['CATALOG_TYPE'] = 0;
    $arResult['OFFERS'] = array();
}

if ($arResult['CATALOG'] && isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
    $boolSKUDisplayProps = false;

    $arResultSKUPropIDs = array();
    $arFilterProp = array();
    $arNeedValues = array();
    foreach ($arResult['OFFERS'] as &$arOffer)
    {
        foreach ($arSKUPropIDs as &$strOneCode)
        {
            if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
            {
                $arResultSKUPropIDs[$strOneCode] = true;
                if (!isset($arNeedValues[$arSKUPropList[$strOneCode]['ID']]))
                    $arNeedValues[$arSKUPropList[$strOneCode]['ID']] = array();
                $valueId = (
                $arSKUPropList[$strOneCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST
                    ? $arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']
                    : $arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']
                );
                $arNeedValues[$arSKUPropList[$strOneCode]['ID']][$valueId] = $valueId;
                unset($valueId);
                if (!isset($arFilterProp[$strOneCode]))
                    $arFilterProp[$strOneCode] = $arSKUPropList[$strOneCode];
            }
        }
        unset($strOneCode);
    }
    unset($arOffer);

    CIBlockPriceToolsNewsite::getTreePropertyValues($arSKUPropList, $arNeedValues);
    $arSKUPropIDs = array_keys($arSKUPropList);
    $arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);


    $arMatrixFields = $arSKUPropKeys;
    $arMatrix = array();

    //__($arMatrixFields);

    $arNewOffers = array();

    $arIDS = array($arResult['ID']);
    $arOfferSet = array();
    $arResult['OFFER_GROUP'] = false;
    $arResult['OFFERS_PROP'] = false;

    $arDouble = array();
    foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
    {
        $arOffer['ID'] = (int)$arOffer['ID'];
        if (isset($arDouble[$arOffer['ID']]))
            continue;
        $arIDS[] = $arOffer['ID'];
        $boolSKUDisplayProperties = false;
        $arOffer['OFFER_GROUP'] = false;
        $arRow = array();

        foreach ($arSKUPropIDs as $propkey => $strOneCode)
        {
            $arCell = array(
                'VALUE' => 0,
                'SORT' => PHP_INT_MAX,
                'NA' => true
            );
            //__($strOneCode);
            //__($arOffer['DISPLAY_PROPERTIES']);
            if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
            {
                $arMatrixFields[$strOneCode] = true;
                $arCell['NA'] = false;
                if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
                {
                    $intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
                    $arCell['VALUE'] = $intValue;
                }
                elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
                {
                    $arCell['VALUE'] = (int)$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID'];
                }
                elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
                {
                    $arCell['VALUE'] = (int)$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE'];
                }
                $arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
            }
            $arRow[$strOneCode] = $arCell;
        }
        $arMatrix[$keyOffer] = $arRow;

        CIBlockPriceTools::setRatioMinPrice($arOffer, false);

        $arOffer['MORE_PHOTO'] = array();
        $arOffer['MORE_PHOTO_COUNT'] = 0;
        $offerSlider = CIBlockPriceTools::getSliderForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP'], $arParams['ADD_DETAIL_TO_SLIDER'] == 'Y');
        if (empty($offerSlider))
        {
            $offerSlider = $productSlider;
        }
        $arOffer['MORE_PHOTO'] = $offerSlider;
        $arOffer['MORE_PHOTO_COUNT'] = count($offerSlider);

        if (CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']))
        {
            $boolSKUDisplayProps = true;
        }

        $arDouble[$arOffer['ID']] = true;
        $arNewOffers[$keyOffer] = $arOffer;
    }
    $arResult['OFFERS'] = $arNewOffers;
    $arResult['SHOW_OFFERS_PROPS'] = $boolSKUDisplayProps;

    $arUsedFields = array();
    $arSortFields = array();

    foreach ($arSKUPropIDs as $propkey => $strOneCode)
    {
        $boolExist = $arMatrixFields[$strOneCode];
        foreach ($arMatrix as $keyOffer => $arRow)
        {
            if ($boolExist)
            {
                if (!isset($arResult['OFFERS'][$keyOffer]['TREE']))
                    $arResult['OFFERS'][$keyOffer]['TREE'] = array();
                $arResult['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
                $arResult['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
                $arUsedFields[$strOneCode] = true;
                $arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
            }
            else
            {
                unset($arMatrix[$keyOffer][$strOneCode]);
            }
        }
    }
    $arResult['OFFERS_PROP'] = $arUsedFields;
    $arResult['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

    Collection::sortByColumn($arResult['OFFERS'], $arSortFields);

    $offerSet = array();
    if (!empty($arIDS) && CBXFeatures::IsFeatureEnabled('CatCompleteSet'))
    {
        $offerSet = array_fill_keys($arIDS, false);
        $rsSets = CCatalogProductSet::getList(
            array(),
            array(
                '@OWNER_ID' => $arIDS,
                '=SET_ID' => 0,
                '=TYPE' => CCatalogProductSet::TYPE_GROUP
            ),
            false,
            false,
            array('ID', 'OWNER_ID')
        );
        while ($arSet = $rsSets->Fetch())
        {
            $arSet['OWNER_ID'] = (int)$arSet['OWNER_ID'];
            $offerSet[$arSet['OWNER_ID']] = true;
            $arResult['OFFER_GROUP'] = true;
        }
        if ($offerSet[$arResult['ID']])
        {
            foreach ($offerSet as &$setOfferValue)
            {
                if ($setOfferValue === false)
                {
                    $setOfferValue = true;
                }
            }
            unset($setOfferValue);
            unset($offerSet[$arResult['ID']]);
        }
        if ($arResult['OFFER_GROUP'])
        {
            $offerSet = array_filter($offerSet);
            $arResult['OFFER_GROUP_VALUES'] = array_keys($offerSet);
        }
    }

    $arMatrix = array();
    $intSelected = -1;
    $arResult['MIN_PRICE'] = false;
    $arResult['MIN_BASIS_PRICE'] = false;
    foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
    {
        if (empty($arResult['MIN_PRICE']))
        {
            if ($arResult['OFFER_ID_SELECTED'] > 0)
                $foundOffer = ($arResult['OFFER_ID_SELECTED'] == $arOffer['ID']);
            else
                $foundOffer = $arOffer['CAN_BUY'];
            if ($foundOffer)
            {
                $intSelected = $keyOffer;
                $arResult['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
                $arResult['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
            }
            unset($foundOffer);
        }

        $arSKUProps = false;
        if (!empty($arOffer['DISPLAY_PROPERTIES']))
        {
            $boolSKUDisplayProps = true;
            $arSKUProps = array();
            foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp)
            {
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
        if (isset($arOfferSet[$arOffer['ID']]))
        {
            $arOffer['OFFER_GROUP'] = true;
            $arResult['OFFERS'][$keyOffer]['OFFER_GROUP'] = true;
        }
        reset($arOffer['MORE_PHOTO']);
        $firstPhoto = current($arOffer['MORE_PHOTO']);
        $arOneRow = array(
            'ID' => $arOffer['ID'],
            'NAME' => $arOffer['~NAME'],
            'SORT' => empty($arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE']) ? 500 : $arOffer['PROPERTIES']['SORT_FOR_SKU']['VALUE'],

            'TREE' => $arOffer['TREE'],
            'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
            'BASIS_PRICE' => $arOffer['MIN_PRICE'],
            'DISPLAY_PROPERTIES' => $arSKUProps,
            'PREVIEW_PICTURE' => $firstPhoto,
            'DETAIL_PICTURE' => $firstPhoto,
            'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
            'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
            'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
            'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
            'OFFER_GROUP' => (isset($offerSet[$arOffer['ID']]) && $offerSet[$arOffer['ID']]),
            'CAN_BUY' => $arOffer['CAN_BUY'],
            'SLIDER' => $arOffer['MORE_PHOTO'],
            'SLIDER_COUNT' => $arOffer['MORE_PHOTO_COUNT'],
            'SKY_PROP' => [
                $arOffer['PROPERTIES']['SIZE']['VALUE'] => $arOffer['PROPERTIES']['SIZE']['VALUE'],
                $arOffer['PROPERTIES']['COLOR']['VALUE'] =>  $arOffer['PROPERTIES']['COLOR']['VALUE'],
                $arOffer['PROPERTIES']['GLASS_COLOR']['VALUE'] =>  $arOffer['PROPERTIES']['GLASS_COLOR']['VALUE'],


            ],
        );
        $arMatrix[$keyOffer] = $arOneRow;
    }
    if (-1 == $intSelected)
    {
        $intSelected = 0;
        $arResult['MIN_PRICE'] = (isset($arResult['OFFERS'][0]['RATIO_PRICE']) ? $arResult['OFFERS'][0]['RATIO_PRICE'] : $arResult['OFFERS'][0]['MIN_PRICE']);
        $arResult['MIN_BASIS_PRICE'] = $arResult['OFFERS'][0]['MIN_PRICE'];
    }
    $arResult['JS_OFFERS'] = $arMatrix;
    $arResult['OFFERS_SELECTED'] = $intSelected;
    if ($arMatrix[$intSelected]['SLIDER_COUNT'] > 0)
    {
        $arResult['MORE_PHOTO'] = $arMatrix[$intSelected]['SLIDER'];
        $arResult['MORE_PHOTO_COUNT'] = $arMatrix[$intSelected]['SLIDER_COUNT'];
    }

    $arResult['OFFERS_IBLOCK'] = $arSKU['IBLOCK_ID'];
}

if ($arResult['MODULES']['catalog'] && $arResult['CATALOG'])
{
    if ($arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
    {
        CIBlockPriceTools::setRatioMinPrice($arResult, false);
        $arResult['MIN_BASIS_PRICE'] = $arResult['MIN_PRICE'];
    }
    if (
        CBXFeatures::IsFeatureEnabled('CatCompleteSet')
        && (
            $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
            || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET
        )
    )
    {
        $rsSets = CCatalogProductSet::getList(
            array(),
            array(
                '@OWNER_ID' => $arResult['ID'],
                '=SET_ID' => 0,
                '=TYPE' => CCatalogProductSet::TYPE_GROUP
            ),
            false,
            false,
            array('ID', 'OWNER_ID')
        );
        if ($arSet = $rsSets->Fetch())
        {
            $arResult['OFFER_GROUP'] = true;
        }
    }
}

if (!empty($arResult['DISPLAY_PROPERTIES']))
{
    foreach ($arResult['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
    {
        if ('F' == $arDispProp['PROPERTY_TYPE'])
            unset($arResult['DISPLAY_PROPERTIES'][$propKey]);
    }
}

$arResult['SKU_PROPS'] = $arSKUPropList;
$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

$arResult['CURRENCIES'] = array();
if ($arResult['MODULES']['currency'])
{
    if ($boolConvert)
    {
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
    }
    else
    {
        $phonesData = CurrencyTable::getList(array(
            'select' => array('CURRENCY')
        ));
        while ($currency = $phonesData->fetch())
        {
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
        unset($currencyFormat, $currency, $phonesData);
    }
}

# конфигурации двери
$arResult['ALL_CONFIGS'] = ['GLAZED' => [], 'NO_GLAZED' => []];
if($arResult['PROPERTIES']['GLASS_REF']['VALUE']) {
    $curItemConfig = $arResult['PROPERTIES']['CONFIGURATION']['VALUE'];
    if ($arResult['PROPERTIES']['GLASS']['VALUE']){
        $arResult['ALL_CONFIGS']['GLAZED'][$curItemConfig] = $curItemConfig;
    }else{
        $arResult['ALL_CONFIGS']['NO_GLAZED'][$curItemConfig] = $curItemConfig;
    }
    $modelDoorFilter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        '!ID' => $arResult['ID'],
        'ACTIVE' => 'Y',
        'PROPERTY_GLASS_REF' => $arResult['PROPERTIES']['GLASS_REF']['VALUE'],
    ];
    $modelDoorSelect = ['ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_CONFIGURATION', 'PROPERTY_GLASS'];
    $model_data = CIBlockElement::GetList(false, $modelDoorFilter, false, false, $modelDoorSelect);
    while($configDoor = $model_data->GetNext()){
        if($curItemConfig == $configDoor['PROPERTY_CONFIGURATION_VALUE']){
            # остеклённая дверь с текущей конфигурацией
            $arResult['GLASS_REF'] = $configDoor;
        }else{
            # дргуие конфигурации
            if ($configDoor['PROPERTY_GLASS_VALUE'] == 1) {
                $arResult['ALL_CONFIGS']['GLAZED'][$configDoor["PROPERTY_CONFIGURATION_VALUE"]] = $configDoor;
            } else {
                $arResult['ALL_CONFIGS']['NO_GLAZED'][$configDoor["PROPERTY_CONFIGURATION_VALUE"]] = $configDoor;
            }
        }
    }
}

# остеклённая дверь
//if($arResult['PROPERTIES']['GLASS_REF']['VALUE'] && $arResult['PROPERTIES']['CONFIGURATION']) {
//    $typeDoorFilter = [
//        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
//        'ACTIVE' => 'Y',
//        'PROPERTY_GLASS_REF' => $arResult['PROPERTIES']['GLASS_REF']['VALUE'],
//        'PROPERTY_CONFIGURATION' => $arResult['PROPERTIES']['CONFIGURATION']['VALUE'],
//    ];
//    if ($arResult['PROPERTIES']['GLASS']['VALUE']) {
//        $typeDoorFilter['!PROPERTY_GLASS'] = 1;
//    } else {
//        $typeDoorFilter['PROPERTY_GLASS'] = 1;
//    }
//    $glassRef = CIBlockElement::GetList(false, $typeDoorFilter)->GetNext();
//    if($glassRef) {
//        $arResult['GLASS_REF'] = $glassRef;
//        dump($glassRef);
//    }

//}

# фоны интерьеров
$interiorSectionId = false;
switch($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID']) {
    case TYPE_INTERIOR_DOORS:
        $interiorSectionId = INTERIOR_SECTION_INTERIOR;
        break;
    case TYPE_EXTERIOR_DOORS:
        $interiorSectionId = INTERIOR_SECTION_EXTERIOR;
        break;
}

if($interiorSectionId) {
    # забираем фоны
    $rsBgs = CIBlockElement::GetList(array(
        'SORT' => 'ASC',
        'ID' => 'ASC'
    ), array(
        'IBLOCK_ID' => IBLOCK_ID_INTERIORS,
        'ACTIVE' => 'Y',
        'SECTION_ID' => $interiorSectionId
    ), false, false, array(
        'ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE',
        'DETAIL_PICTURE', 'PROPERTY_COLOR', 'PROPERTY_INNER'
    ));
    $bgs = array();
    while($bg = $rsBgs->Fetch()) {

        if(!empty($bg['PREVIEW_PICTURE']))
            CIBlockPriceToolsNewsite::$fileID[$bg['PREVIEW_PICTURE']] = $bg['PREVIEW_PICTURE'];


        if(!empty($bg['DETAIL_PICTURE']))
            CIBlockPriceToolsNewsite::$fileID[$bg['DETAIL_PICTURE']] = $bg['DETAIL_PICTURE'];

        $bgs[] = $bg;
    }
    $arResult['BGS'] = $bgs;

}

if(!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])){
    foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img){
        CIBlockPriceToolsNewsite::$fileID[$img] = $img;
    }
}

if(!empty($arResult['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'])){
    $arResult['TWO_LEAF_PHOTO'] = $arResult['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'];
    CIBlockPriceToolsNewsite::$fileID[$arResult['TWO_LEAF_PHOTO']] = $arResult['TWO_LEAF_PHOTO'];
}

if(!empty($arResult['PROPERTIES']['INNER_PHOTO']['VALUE'])){
    $arResult['INNER_PHOTO'] = $arResult['PROPERTIES']['INNER_PHOTO']['VALUE'];
    CIBlockPriceToolsNewsite::$fileID[$arResult['INNER_PHOTO']] = $arResult['INNER_PHOTO'];
}

foreach ($arResult['OFFERS'] as &$offers){

    if(!empty($offers['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'])){
        $offers['TWO_LEAF_PHOTO'] = $offers['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'];
        CIBlockPriceToolsNewsite::$fileID[$offers['TWO_LEAF_PHOTO']] = $offers['TWO_LEAF_PHOTO'];
    }
    if(!empty($offers['PROPERTIES']['INNER_PHOTO']['VALUE'])){
        $offers['INNER_PHOTO'] = $offers['PROPERTIES']['INNER_PHOTO']['VALUE'];
        CIBlockPriceToolsNewsite::$fileID[$offers['INNER_PHOTO']] = $offers['INNER_PHOTO'];
    }

}
unset($offers);

CIBlockPriceToolsNewsite::GetImages();

$arWaterMark = [];
if(in_array($arResult['SECTION']['CODE'], ['mezhkomnatnye_dveri', 'vkhodnye_dveri'])){
    $arWaterMark = PICTURE_WATER_MARK;
}


if(!empty($arResult['BGS'])){
    foreach ($arResult['BGS'] as &$bg){

        if(!empty($bg['DETAIL_PICTURE'])) {
            $detail = CIBlockPriceToolsNewsite::$fileID[$bg['DETAIL_PICTURE']];
            if (!empty($detail)) {

                $bg['PICTURE_SMALL_DETAIL'] = CFile::ResizeImageGet($detail, array(
                    'width' => 244,
                    'height' => 244
                ), BX_RESIZE_IMAGE_EXACT,
                    true,
                    $arWaterMark
                );

                $bg['PICTURE_DETAIL'] = CFile::ResizeImageGet($detail, array(
                    'width' => 1600,
                    'height' => 635
                ), BX_RESIZE_IMAGE_EXACT,
                    true,
                    $arWaterMark
                );
            }
        }

        if(!empty($bg['PREVIEW_PICTURE'])) {
            $preview = CIBlockPriceToolsNewsite::$fileID[$bg['PREVIEW_PICTURE']];
            if (!empty($preview)) {
                $bg['PICTURE_SMALL'] = CFile::ResizeImageGet($preview, array(
                    'width' => 100,
                    'height' => 70
                ), BX_RESIZE_IMAGE_EXACT);
            }
        }

    }
    unset($bg);
}

if(!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])){
    foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as &$photoId){

        $preview = CIBlockPriceToolsNewsite::$fileID[$photoId];

        $photoId = [];

        if (!empty($preview)) {
            $photoId['PICTURE_SMALL'] = CFile::ResizeImageGet($preview, array(
                'width' => 70,
                'height' => 70
            ), BX_RESIZE_IMAGE_EXACT);

            $photoId['PICTURE'] = CFile::ResizeImageGet($preview, array(
                'width' => 1000,
                'height' => 800
            ), BX_RESIZE_IMAGE_PROPORTIONAL,
                true,
                $arWaterMark);

            $photoId['GALLERY_PICTURE'] = CFile::resizeImageGet($preview, array(
                'width' => 355,
                'height' => 355
            ), BX_RESIZE_IMAGE_PROPORTIONAL,
                true,
                $arWaterMark);

            $photoId['GALLERY_PICTURE_SMALL'] = CFile::resizeImageGet($preview, array(
                'width' => 66,
                'height' => 66
            ), BX_RESIZE_IMAGE_PROPORTIONAL,
                true,
                $arWaterMark);

        }

        if(empty($photoId))
            unset($photoId);

    }

}

if(!empty($arResult['DETAIL_PICTURE'])) {
    $arWaterMark = PICTURE_WATER_MARK;
    $arResult['BIG_IMAGE'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
        'width' => 485,
        'height' => 485
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

    $arResult['SMALL_IMAGE'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
        'width' => 244,
        'height' => 244
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);


    $arResult['GALLERY_DETAIL'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array(
        'width' => 355,
        'height' => 355
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

    $arResult['GALLERY_DETAIL_SMALL'] = CFile::resizeImageGet($arResult['DETAIL_PICTURE'], array(
        'width' => 66,
        'height' => 66
    ), BX_RESIZE_IMAGE_PROPORTIONAL);


}

if(!empty($arResult['TWO_LEAF_PHOTO'])){
    $arResult['TWO_LEAF_PHOTO'] = CIBlockPriceToolsNewsite::$fileID[$arResult['TWO_LEAF_PHOTO']];
    $arResult['BIG_TWO_LEAF_PHOTO'] = CFile::ResizeImageGet($arResult['TWO_LEAF_PHOTO'], array(
        'width' => 398,
        'height' => 485
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

    $arResult['SMALL_TWO_LEAF_PHOTO'] = CFile::ResizeImageGet($arResult['TWO_LEAF_PHOTO'], array(
        'width' => 199,
        'height' => 244
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

}

if(!empty($arResult['INNER_PHOTO'])){
    $arResult['INNER_PHOTO'] = CIBlockPriceToolsNewsite::$fileID[$arResult['INNER_PHOTO']];
    $arResult['BIG_INNER_PHOTO'] = CFile::ResizeImageGet($arResult['INNER_PHOTO'], array(
        'width' => 485,
        'height' => 485
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

    $arResult['SMALL_INNER_PHOTO'] = CFile::ResizeImageGet($arResult['INNER_PHOTO'], array(
        'width' => 244,
        'height' => 244
    ), BX_RESIZE_IMAGE_PROPORTIONAL,
        true,
        $arWaterMark);

}


foreach ($arResult['OFFERS'] as &$offers){

    if(!empty($offers['DETAIL_PICTURE'])) {

        $offers['BIG_IMAGE'] = CFile::ResizeImageGet($offers['DETAIL_PICTURE'], array(
            'width' => 485,
            'height' => 485
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

        $offers['SMALL_IMAGE'] = CFile::ResizeImageGet($offers['DETAIL_PICTURE'], array(
            'width' => 244,
            'height' => 244
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

    }

    if(!empty($offers['TWO_LEAF_PHOTO'])){
        $offers['TWO_LEAF_PHOTO'] = CIBlockPriceToolsNewsite::$fileID[$offers['TWO_LEAF_PHOTO']];
        $offers['BIG_TWO_LEAF_PHOTO'] = CFile::ResizeImageGet($offers['TWO_LEAF_PHOTO'], array(
            'width' => 398,
            'height' => 485
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

        $offers['SMALL_TWO_LEAF_PHOTO'] = CFile::ResizeImageGet($offers['TWO_LEAF_PHOTO'], array(
            'width' => 199,
            'height' => 244
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

    }

    if(!empty($offers['INNER_PHOTO'])){
        $offers['INNER_PHOTO'] = CIBlockPriceToolsNewsite::$fileID[$offers['INNER_PHOTO']];
        $offers['BIG_INNER_PHOTO'] = CFile::ResizeImageGet($offers['INNER_PHOTO'], array(
            'width' => 225,
            'height' => 485
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

        $offers['SMALL_INNER_PHOTO'] = CFile::ResizeImageGet($offers['INNER_PHOTO'], array(
            'width' => 244,
            'height' => 244
        ), BX_RESIZE_IMAGE_PROPORTIONAL,
            true,
            $arWaterMark);

    }

}
unset($offer);

foreach ($arResult['SKU_PROPS'] as &$prop) {
    if (!empty($prop['VALUES'])) {
        foreach ($prop['VALUES'] as &$pic){
            if(!empty($pic['PICT'])) {
                $pic['PICT_SMALL'] = CFile::ResizeImageGet($pic['PICT'], array(
                    'width' => 23,
                    'height' => 23
                ), BX_RESIZE_IMAGE_EXACT);
            }
        }
        unset($pic);
    }
}


$arIdELm = array();
$arIdELmValue = array();

$offersIds = [];
foreach ($arResult['OFFERS'] as $key => $offer)
    $offersIds[$offer['ID']] = $key;



$arResult['COMPLECT'] = [];
$arIdELm = [];
if (\Bitrix\Main\Loader::includeModule("sh.dblayer")) {

    $res = \Sh\DBLayer\CTables::getOrmQuery("frame_type")
        ->setSelect(["*"])
        ->setFilter(["=ELEMENT_ID_LINK" => array_keys($offersIds)])
        ->exec();

    $deleteIds = [];
    while ($tmp = $res->fetch()) {

        $key = $offersIds[$tmp['ELEMENT_ID_LINK']];

        if(empty($arResult['OFFERS'][$key]['COMPLECT']))
            $arResult['OFFERS'][$key]['COMPLECT'] = [];

        if(empty($arResult['OFFERS'][$key]['COMPLECT']))
            $arResult['OFFERS'][$key]['COMPLECT'][$tmp['LINK_TYPE']] = [];


        $arResult['COMPLECT'][$tmp['LINK_TYPE']] = [];

        //if(!empty($tmp['COUNT'])) {
            $arResult['OFFERS'][$key]['COMPLECT'][$tmp['LINK_TYPE']]['ITEMS'][$tmp['ELEMENT_ID']] = $tmp['COUNT'];
        //}

        if($tmp['COUNT'] == 0) {
            $arResult['OFFERS'][$key]['COMPLECT'][$tmp['LINK_TYPE']]['COUNT'] ++;
        }

        $arIdELm[$tmp['ELEMENT_ID']] = $tmp['ELEMENT_ID'];
    }

    $arIdELmValue = [];

    if (!empty($arIdELm)) {
        if (CModule::IncludeModule("iblock")) {
            $arSelect = Array("ID", "IBLOCK_ID", "NAME");
            $arFilter = Array(
                'ID' => array_keys($arIdELm),
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

            while ($arFields = $res->GetNext()) {
                $arFields['PRICE'] = CCatalogProduct::GetOptimalPrice($arFields['ID'], 1, $USER->GetUserGroupArray());
                $arIdELmValue[$arFields['ID']] = $arFields;
            }
        }
    }

    if(!empty($arResult['COMPLECT'])){
        $complect = [];
        $res = Sh\DBLayer\CFields::GetInstance()->Getlist(false, array('CODE' => 'LINK_TYPE'))->fetch();

        $complect = array_column($res['USER_TYPE_SETTINGS']['VALUES'], null, 'ID');
        foreach ($arResult['COMPLECT'] as $key => $item){
            $arResult['COMPLECT'][$key] = $complect[$key];
        }
    }

}

foreach ($arResult['OFFERS'] as $keyOffer => $offer){
    foreach ($offer['COMPLECT'] as $keyComplect => $complectIds) {
        foreach ($complectIds['ITEMS'] as $id => $qty){
            if(!empty($arIdELmValue[$id])){
                $arIdELmValue[$id]['COUNT'] = $qty;
                $arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['ITEMS'][$id] = $arIdELmValue[$id];
            }
        }
        foreach ($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['ITEMS'] as $id => $item){
            if(!is_array($item))
                unset($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['ITEMS'][$id]);
        }

        if($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['COUNT'] == count($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['ITEMS'])) {
            unset($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]);
        }

        if(empty($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]['ITEMS'])){
            unset($arResult['OFFERS'][$keyOffer]['COMPLECT'][$keyComplect]);
        }

        if(isset($arResult['OFFERS'][$keyOffer]['COMPLECT']['Invisible-Silver']) || isset($arResult['OFFERS'][$keyOffer]['COMPLECT']['Invisible-Black'])){
            unset($arResult['OFFERS'][$keyOffer]['COMPLECT']['Standart']);
        }

    }
}

if($arResult['PROPERTIES']['FLOOR_COLOR']['VALUE']){
    $arResult['PROPERTIES']['FLOOR_COLOR']['VALUE']=GetColorHB($arResult['PROPERTIES']['FLOOR_COLOR']['VALUE']);
    $arResult['DISPLAY_PROPERTIES']['FLOOR_COLOR']['VALUE']=$arResult['PROPERTIES']['FLOOR_COLOR']['VALUE'];
    //PR($arResult);
}

/**
 * Вытаскивает цвет из справочника
 **/
function GetColorHB($code){
    $hlblock = HL\HighloadBlockTable::getById(1)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entityClass = $entity->getDataClass();
    $res = $entityClass::getList(array(
            'select' => array('*'),
        )
    );
    $DATA = $res->fetchAll();
    $DATA = array_column($DATA, NULL, 'UF_XML_ID');
    return $DATA[$code]['UF_NAME'];
}

/*
$arResult['MIN_PRICE']['VALUE_NOVAT'] = $arResult['MIN_PRICE']['VALUE_NOVAT']*1.25;
foreach($arResult['PRICES'] as $p => $price){
    $arResult['PRICES'][$p]['VALUE_NOVAT'] = $price['VALUE_NOVAT']*1.25;
}

foreach($arResult['OFFERS'] as $i => $offer){
    $arResult['OFFERS'][$i]['MIN_PRICE']['VALUE_NOVAT'] = $offer['MIN_PRICE']['VALUE_NOVAT']*1.25;
    foreach($offer['PRICES'] as $p => $price){
        $arResult['OFFERS'][$i]['PRICES'][$p]['VALUE_NOVAT'] = $price['VALUE_NOVAT']*1.25;
    }
}
*/

if(!empty($arResult['PROPERTIES']['FILE_DOWNLOAD']['VALUE'])) {
    $res = CFile::GetList([], array("@ID" => $arResult['PROPERTIES']['FILE_DOWNLOAD']['VALUE']));
    $arResult['FILE_DOWNLOAD'] = [];
    while ($arr = $res->GetNext()) {
        $data = [];
        $data['LINK'] = "/".\COption::GetOptionString("main", "upload_dir", "upload") . "/" . $arr["SUBDIR"] . "/" . $arr["FILE_NAME"];
        //$data['TITLE'] = $arr["DESCRIPTION"] ? :  $arr["ORIGINAL_NAME"];
        $data['TITLE'] = 'Скачать 3D';
        $arResult['FILE_DOWNLOAD'][] = $data;
    }
}

$categoryId = [];
if (isset($arResult['SECTION']['ID']))
    $categoryId[$arResult['SECTION']['ID']] = $arResult['SECTION']['ID'];


if (isset($arResult['SECTION']['PATH']))
    foreach ($arResult['SECTION']['PATH'] as $cat)
        $categoryId[$cat['ID']] = $cat['ID'];


$arSelect = Array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_LINK', 'PREVIEW_PICTURE');
$arFilter = Array(
    'ACTIVE' => 'Y',
    'ACTIVE_DATE' => 'Y',
    'IBLOCK_ID' => IBLOCK_ID_BANNER_ITEMS,
    'INCLUDE_SUBSECTIONS' => 'Y',
    [
        'LOGIC' => 'OR',
        ['=PROPERTY_SECTIONS' => $categoryId],
        ['=PROPERTY_ITEMS' => $arResult['ID']],
        ['=PROPERTY_SECTIONS' => false, '=PROPERTY_ITEMS' => false],
    ],
);
$arResult['BANNER'] = [];
$res = \CIBlockElement::GetList(['SORT' => 'DESC'], $arFilter, false, ['nTopCount' => 1], $arSelect);
if ($row = $res->GetNext()) {
    $row["PREVIEW_PICTURE"] = \CFile::GetPath($row["PREVIEW_PICTURE"]);
    $arResult['BANNER'][] = $row;
}


$cp = $this->__component;
if (is_object($cp))
{
    $cp->SetResultCacheKeys(array("MIN_PRICE", "PROPERTIES", "DISPLAY_PROPERTIES", "DETAIL_TEXT", "PREVIEW_TEXT", "BANNER")); //cache keys in $arResult array
}


$arResult['COMPLETED_PROJECTS'] = [];
if(CModule::IncludeModule("iblock")){
    $arFilterElem = array("IBLOCK_ID" => IBLOCK_ID_PROJECTS,  "PROPERTY_".PROP_CODE_USED_DOORS => array($arResult["ID"]));
    $arSelectElem = array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_".PROP_CODE_USED_DOORS);
    $elemRes = CIBlockElement::GetList(
        array(),
        $arFilterElem,
        false,
        false,
        $arSelectElem
    );
    while ($ar_elem = $elemRes->GetNext()) {
        $ar_elem['PHOTO'] = CFile::ResizeImageGet($ar_elem['PREVIEW_PICTURE'], array(
            'width' => 330,
            'height' => 170
        ), BX_RESIZE_IMAGE_PROPORTIONAL);
        $arResult['COMPLETED_PROJECTS'][$ar_elem['ID']] = $ar_elem;
    }
}

$arResult['ITEMS'][$key]['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arResult['PREVIEW_PICTURE']['SRC']);
$arResult['ITEMS'][$key]['PREVIEW_PICTURE']['SRC'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $arResult['DETAIL_PICTURE']['SRC']);


foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $key => $arItem) {
    foreach ($arItem as $key2 => $value) {
        $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'][$key][$key2]['src'] = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $value['src']);
    }
    
}


$arPropValues = [];
foreach ($arResult['OFFERS'] as $keys => $arOffer) {
    foreach ($arOffer['PROPERTIES'] as $arProp) {
        if ($arProp['USER_TYPE'] == 'directory') {
            $table = $arProp['USER_TYPE_SETTINGS']['TABLE_NAME'];
            if ($arProp['MULTIPLE'] == 'Y') {
                foreach ($arProp['VALUE'] as $arValue) {
                    if (!empty($arValue)) {
                        $arPropValues[$table][$arValue] = $arValue;
                    }
                }
            } else {
                if (!empty($arProp['VALUE'])) {
                    $arPropValues[$table][$arProp['VALUE']] = $arProp['VALUE'];
                }
            }
        }
    }
}

foreach ($arPropValues as $table => $arValues) {
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
        'filter' => ['TABLE_NAME' => $table]
    ])->fetch();
    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $iterator = $entity_data_class::getList([
        'filter' => ['UF_XML_ID' => $arValues]
    ]);
    while ($arValue = $iterator->fetch()){
        $arResult['HIGHLOAD_VALUES'][$table][$arValue['UF_XML_ID']] = $arValue;
    }
}

if (\Bitrix\Main\Loader::includeModule('ml.settings')) {
    $properties = new \Ml\Settings\Util\Property($arResult);
    $arResult = $properties->SetValues();
}

$arResult["PROPERTIES"]["SALOONS_LIST"]["ADDRESSES"] = [];
if (count((array)$arResult["PROPERTIES"]["SALOONS_LIST"]["VALUE"]) > 0) {
/*    pr($arResult["PROPERTIES"]["SALOONS_LIST"]);*/

    $res = CIBlockElement::GetList(
        ['SORT' => 'ASC'],
        [
            "ID"=>$arResult["PROPERTIES"]["SALOONS_LIST"]["VALUE"],
            "IBLOCK_ID"=>$arResult["PROPERTIES"]["SALOONS_LIST"]["LINK_IBLOCK_ID"],
            "ACTIVE"=>"Y"
        ],
        false,
        ["nPageSize"=>50],
        [
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DATE_ACTIVE_FROM",
            "PROPERTY_ADDRESS",
            "PROPERTY_REAL_MAP",
            "PROPERTY_HREF_FOR_MAP",
            "PROPERTY_PHONES",
            "PROPERTY_WORKING",
        ]
    );

    while($ob = $res->GetNext())
    {
        $arResult["PROPERTIES"]["SALOONS_LIST"]["ADDRESSES"][] = [
            "ADDRESS"=>$ob["NAME"],
            "WORKING"=>strip_tags(html_entity_decode($ob["PROPERTY_WORKING_VALUE"]["TEXT"])), //clear all HTML tags
            "COORDS"=>$ob["PROPERTY_REAL_MAP_VALUE"],
        ];
    }
}

if (!empty($arResult['PROPERTIES']['ML_INCLUDE_ICON']['VALUE'])){

    $res_urls = SeometaUrlTable::getList(array(
        'select' => array('REAL_URL', 'NEW_URL'),
        'filter' => array('REAL_URL' => "/catalog/mezhkomnatnye_dveri/filter/ml_include_icon%"),
        'order' => array('ID')
    ));
    $urls = [ ];
    while($url = $res_urls->fetch()) {
        $urls[$url['NEW_URL']] = $url['REAL_URL'];
    }


    $arSelect = ["ID", "NAME", "PROPERTY_ICON_SLIDER_CARD", "PROPERTY_PATH_ICON", "CODE"];
    $arFilter = ["IBLOCK_ID" => IBLOCK_ID_ICON, "ID" => $arResult['PROPERTIES']['ML_INCLUDE_ICON']['VALUE']];
    $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext()) {
        $img = CFile::GetPath($ob['PROPERTY_ICON_SLIDER_CARD_VALUE']) . $ob['PROPERTY_PATH_ICON_VALUE'];
        $resultElemIcon[] = [
            "ID" => $ob["ID"],
            "NAME" => $ob['NAME'],
            "ICON_PATH" => $img,
            "PROPERTY_ICON_SLIDER_CARD_VALUE" => $ob["PROPERTY_ICON_SLIDER_CARD_VALUE"],
            "CODE" => $ob["CODE"],
            "LINK" => '#'
        ];
        $arr_key_last = array_key_last ($resultElemIcon);
        $serch_res = array_search('/catalog/mezhkomnatnye_dveri/filter/ml_include_icon-is-'.$resultElemIcon[$arr_key_last]['CODE'].'/apply/', $urls);
        if ($serch_res !== false) $resultElemIcon[$arr_key_last]['LINK'] = $serch_res;
    }
    
    $arResult += ["ICON_PROPERTIES" => $resultElemIcon];
}

//сокращение названия в комплектах
foreach($arResult['OFFERS'] as &$offer) {
    foreach($offer['COMPLECT'] as &$complect) {
        foreach($complect['ITEMS'] as &$item) {
            $item['TITLE_NAME'] = $item['NAME'];
            $item['NAME'] = mb_substr($item['NAME'],0,14, 'UTF-8').'...'; //14 это кол. знаков
        }
    }
}
