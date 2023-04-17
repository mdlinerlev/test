<?
class CIBlockPriceToolsNewsite{

    public static $fileID = [];
    public static $staticProductInfo = [];
    protected static $highLoadInclude = null;
    protected static $needDiscountCache = null;
    public static function getTreePropertyValues(&$propList, &$propNeedValues)
    {
        $result = array();
        if (!empty($propList) && is_array($propList))
        {
            $useFilterValues = !empty($propNeedValues) && is_array($propNeedValues);
            foreach ($propList as $oneProperty)
            {
                $values = array();
                $valuesExist = false;
                $pictMode = ('PICT' == $oneProperty['SHOW_MODE']);
                $needValuesExist = !empty($propNeedValues[$oneProperty['ID']]) && is_array($propNeedValues[$oneProperty['ID']]);
                if ($useFilterValues && !$needValuesExist)
                    continue;
                switch($oneProperty['PROPERTY_TYPE'])
                {
                    case \Bitrix\Iblock\PropertyTable::TYPE_LIST:
                        if ($needValuesExist)
                        {
                            foreach (array_chunk($propNeedValues[$oneProperty['ID']], 500) as $pageIds)
                            {
                                $iterator = \Bitrix\Iblock\PropertyEnumerationTable::getList(array(
                                    'select' => array('ID', 'VALUE', 'SORT'),
                                    'filter' => array('=PROPERTY_ID' => $oneProperty['ID'], '@ID' => $pageIds),
                                    'order' => array('SORT' => 'ASC', 'VALUE' => 'ASC')
                                ));
                                while ($row = $iterator->fetch())
                                {
                                    $row['ID'] = (int)$row['ID'];
                                    $values[$row['ID']] = array(
                                        'ID' => $row['ID'],
                                        'NAME' => $row['VALUE'],
                                        'SORT' => (int)$row['SORT'],
                                        'PICT' => false
                                    );
                                    $valuesExist = true;
                                }
                                unset($row, $iterator);
                            }
                            unset($pageIds);
                        }
                        else
                        {
                            $iterator = \Bitrix\Iblock\PropertyEnumerationTable::getList(array(
                                'select' => array('ID', 'VALUE', 'SORT'),
                                'filter' => array('=PROPERTY_ID' => $oneProperty['ID']),
                                'order' => array('SORT' => 'ASC', 'VALUE' => 'ASC')
                            ));
                            while ($row = $iterator->fetch())
                            {
                                $row['ID'] = (int)$row['ID'];
                                $values[$row['ID']] = array(
                                    'ID' => $row['ID'],
                                    'NAME' => $row['VALUE'],
                                    'SORT' => (int)$row['SORT'],
                                    'PICT' => false
                                );
                                $valuesExist = true;
                            }
                            unset($row, $iterator);
                        }
                        $values[0] = array(
                            'ID' => 0,
                            'SORT' => PHP_INT_MAX,
                            'NA' => true,
                            'NAME' => $oneProperty['DEFAULT_VALUES']['NAME'],
                            'PICT' => $oneProperty['DEFAULT_VALUES']['PICT']
                        );
                        break;
                    case \Bitrix\Iblock\PropertyTable::TYPE_ELEMENT:
                        $selectFields = array('ID', 'NAME');
                        if ($pictMode)
                            $selectFields[] = 'PREVIEW_PICTURE';

                        if ($needValuesExist)
                        {
                            foreach (array_chunk($propNeedValues[$oneProperty['ID']], 500) as $pageIds)
                            {
                                $iterator =  CIBlockElement::GetList(
                                    array('SORT' => 'ASC', 'NAME' => 'ASC'),
                                    array('ID' => $pageIds, 'IBLOCK_ID' => $oneProperty['LINK_IBLOCK_ID'], 'ACTIVE' => 'Y'),
                                    false,
                                    false,
                                    $selectFields
                                );
                                while ($row = $iterator->Fetch())
                                {
                                    if ($pictMode)
                                    {
                                        $row['PICT'] = false;
                                        if (!empty($row['PREVIEW_PICTURE']))
                                        {
                                            self::$fileID[$row['PREVIEW_PICTURE']] = $row['PICT'] = $row['PREVIEW_PICTURE'];
                                        }
                                        if (empty($row['PICT']))
                                            $row['PICT'] = $oneProperty['DEFAULT_VALUES']['PICT'];
                                    }
                                    $row['ID'] = (int)$row['ID'];
                                    $values[$row['ID']] = array(
                                        'ID' => $row['ID'],
                                        'NAME' => $row['NAME'],
                                        'SORT' => (int)$row['SORT'],
                                        'PICT' => ($pictMode ? $row['PICT'] : false)
                                    );
                                    $valuesExist = true;
                                }
                                unset($row, $iterator);
                            }
                            unset($pageIds);
                        }
                        else
                        {
                            $iterator =  CIBlockElement::GetList(
                                array('SORT' => 'ASC', 'NAME' => 'ASC'),
                                array('IBLOCK_ID' => $oneProperty['LINK_IBLOCK_ID'], 'ACTIVE' => 'Y'),
                                false,
                                false,
                                $selectFields
                            );
                            while ($row = $iterator->Fetch())
                            {
                                if ($pictMode)
                                {
                                    $row['PICT'] = false;
                                    if (!empty($row['PREVIEW_PICTURE']))
                                    {
                                        self::$fileID[$row['PREVIEW_PICTURE']] = $row['PICT'] = $row['PREVIEW_PICTURE'];
                                    }
                                    if (empty($row['PICT']))
                                        $row['PICT'] = $oneProperty['DEFAULT_VALUES']['PICT'];
                                }
                                $row['ID'] = (int)$row['ID'];
                                $values[$row['ID']] = array(
                                    'ID' => $row['ID'],
                                    'NAME' => $row['NAME'],
                                    'SORT' => (int)$row['SORT'],
                                    'PICT' => ($pictMode ? $row['PICT'] : false)
                                );
                                $valuesExist = true;
                            }
                            unset($row, $iterator);
                        }
                        $values[0] = array(
                            'ID' => 0,
                            'SORT' => PHP_INT_MAX,
                            'NA' => true,
                            'NAME' => $oneProperty['DEFAULT_VALUES']['NAME'],
                            'PICT' => ($pictMode ? $oneProperty['DEFAULT_VALUES']['PICT'] : false)
                        );
                        break;
                    case \Bitrix\Iblock\PropertyTable::TYPE_STRING:
                        if (self::$highLoadInclude === null)
                            self::$highLoadInclude = \Bitrix\Main\Loader::includeModule('highloadblock');
                        if (!self::$highLoadInclude)
                            continue;
                        $xmlMap = array();
                        $sortExist = isset($oneProperty['USER_TYPE_SETTINGS']['FIELDS_MAP']['UF_SORT']);

                        $directorySelect = array('ID', 'UF_NAME', 'UF_XML_ID');
                        $directoryOrder = array();
                        if ($pictMode)
                            $directorySelect[] = 'UF_FILE';
                        if ($sortExist)
                        {
                            $directorySelect[] = 'UF_SORT';
                            $directoryOrder['UF_SORT'] = 'ASC';
                        }
                        $directoryOrder['UF_NAME'] = 'ASC';
                        $sortValue = 100;

                        /** @var Main\Entity\Base $entity */
                        $entity = $oneProperty['USER_TYPE_SETTINGS']['ENTITY'];
                        if (!($entity instanceof \Bitrix\Main\Entity\Base))
                            continue;
                        $entityDataClass = $entity->getDataClass();
                        $entityGetList = array(
                            'select' => $directorySelect,
                            'order' => $directoryOrder
                        );

                        if ($needValuesExist)
                        {
                            foreach (array_chunk($propNeedValues[$oneProperty['ID']], 500) as $pageIds)
                            {
                                $entityGetList['filter'] = array('=UF_XML_ID' => $pageIds);
                                $iterator = $entityDataClass::getList($entityGetList);
                                while ($row = $iterator->fetch())
                                {
                                    $row['ID'] = (int)$row['ID'];
                                    $row['UF_SORT'] = ($sortExist ? (int)$row['UF_SORT'] : $sortValue);
                                    $sortValue += 100;
                                    if ($pictMode)
                                    {
                                        if (!empty($row['UF_FILE']))
                                        {
                                            self::$fileID[$row['UF_FILE']] = $row['PICT'] = $row['UF_FILE'];
                                        }
                                        if (empty($row['PICT']))
                                            $row['PICT'] = $oneProperty['DEFAULT_VALUES']['PICT'];
                                    }
                                    $values[$row['ID']] = array(
                                        'ID' => $row['ID'],
                                        'NAME' => $row['UF_NAME'],
                                        'SORT' => (int)$row['UF_SORT'],
                                        'XML_ID' => $row['UF_XML_ID'],
                                        'PICT' => ($pictMode ? $row['PICT'] : false)
                                    );
                                    $valuesExist = true;
                                    $xmlMap[$row['UF_XML_ID']] = $row['ID'];
                                }
                                unset($row, $iterator);
                            }
                            unset($pageIds);
                        }
                        else
                        {
                            $iterator = $entityDataClass::getList($entityGetList);
                            while ($row = $iterator->fetch())
                            {
                                $row['ID'] = (int)$row['ID'];
                                $row['UF_SORT'] = ($sortExist ? (int)$row['UF_SORT'] : $sortValue);
                                $sortValue += 100;

                                if ($pictMode)
                                {
                                    if (!empty($row['UF_FILE']))
                                    {
                                        self::$fileID[$row['UF_FILE']] = $row['PICT'] = $row['UF_FILE'];
                                    }
                                    if (empty($row['PICT']))
                                        $row['PICT'] = $oneProperty['DEFAULT_VALUES']['PICT'];
                                }
                                $values[$row['ID']] = array(
                                    'ID' => $row['ID'],
                                    'NAME' => $row['UF_NAME'],
                                    'SORT' => (int)$row['UF_SORT'],
                                    'XML_ID' => $row['UF_XML_ID'],
                                    'PICT' => ($pictMode ? $row['PICT'] : false)
                                );
                                $valuesExist = true;
                                $xmlMap[$row['UF_XML_ID']] = $row['ID'];
                            }
                            unset($row, $iterator);
                        }
                        $values[0] = array(
                            'ID' => 0,
                            'SORT' => PHP_INT_MAX,
                            'NA' => true,
                            'NAME' => $oneProperty['DEFAULT_VALUES']['NAME'],
                            'XML_ID' => '',
                            'PICT' => ($pictMode ? $oneProperty['DEFAULT_VALUES']['PICT'] : false)
                        );
                        if ($valuesExist)
                            $oneProperty['XML_MAP'] = $xmlMap;
                        break;
                }
                if (!$valuesExist)
                    continue;
                $oneProperty['VALUES'] = $values;
                $oneProperty['VALUES_COUNT'] = count($values);

                $result[$oneProperty['CODE']] = $oneProperty;
            }
        }
        $propList = $result;

        self::GetImages();

        foreach ($propList as $keyProp => $oneProp)
        {
            $pictMode = ('PICT' == $oneProp['SHOW_MODE']);
            if($pictMode) {
                foreach ($oneProp['VALUES'] as $keyValues => $oneValues) {
                    //pr($oneValues['PICT']);
                    if(!empty($oneValues['PICT']['SRC'])) {
                        $propList[$keyProp]['VALUES'][$keyValues]['PICT'] = self::$fileID[$oneValues['PICT']['SRC']];
                    } else {
                        if(
                            !empty($oneValues['PICT']) &&
                            !empty(self::$fileID[$oneValues['PICT']])
                        ){
                            $propList[$keyProp]['VALUES'][$keyValues]['PICT'] = self::$fileID[$oneValues['PICT']];
                        } else {
                            $propList[$keyProp]['VALUES'][$keyValues]['PICT'] = $oneProp['DEFAULT_VALUES']['PICT'] ;
                        }
                    }
                }
            }
        }

        unset($arFilterProp);
    }


    public static function GetOffersArray($arFilter, $arElementID, $arOrder, $arSelectFields, $arSelectProperties, $limit, $arPrices, $vat_include, $arCurrencyParams = array(), $USER_ID = 0, $LID = SITE_ID)
    {
        global $USER;

        $arResult = array();

        $boolCheckPermissions = false;
        $boolHideNotAvailable = false;
        $showPriceCount = false;
        $customFilter = false;
        $IBLOCK_ID = 0;

        if (!empty($arFilter) && is_array($arFilter))
        {
            if (isset($arFilter['IBLOCK_ID']))
                $IBLOCK_ID = $arFilter['IBLOCK_ID'];
            if (isset($arFilter['HIDE_NOT_AVAILABLE']))
                $boolHideNotAvailable = ($arFilter['HIDE_NOT_AVAILABLE'] === 'Y');
            if (isset($arFilter['CHECK_PERMISSIONS']))
                $boolCheckPermissions = ($arFilter['CHECK_PERMISSIONS'] === 'Y');
            if (isset($arFilter['SHOW_PRICE_COUNT']))
            {
                $showPriceCount = (int)$arFilter['SHOW_PRICE_COUNT'];
                if ($showPriceCount <= 0)
                    $showPriceCount = false;
            }

            if (isset($arFilter['CUSTOM_FILTER']))
            {
                $customFilter = $arFilter['CUSTOM_FILTER'];
            }
        }
        else
        {
            $IBLOCK_ID = $arFilter;
        }

        if (self::$needDiscountCache === null)
        {
            if(\Bitrix\Main\Config\Option::get('sale', 'use_sale_discount_only') === 'Y')
            {
                self::$needDiscountCache = false;
            }
            else
            {
                $pricesAllow = CIBlockPriceTools::GetAllowCatalogPrices($arPrices);
                if (empty($pricesAllow))
                {
                    self::$needDiscountCache = false;
                }
                else
                {
                    $USER_ID = (int)$USER_ID;
                    $userGroups = array(2);
                    if ($USER_ID > 0)
                        $userGroups = CUser::GetUserGroup($USER_ID);
                    elseif (isset($USER) && $USER instanceof CUser)
                        $userGroups = $USER->GetUserGroupArray();
                    self::$needDiscountCache = CIBlockPriceTools::SetCatalogDiscountCache($pricesAllow, $userGroups);
                    unset($userGroups);
                }
                unset($pricesAllow);
            }
        }

        $arOffersIBlock = CIBlockPriceTools::GetOffersIBlock($IBLOCK_ID);
        if($arOffersIBlock)
        {
            $arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);

            $limit = (int)$limit;
            if (0 > $limit)
                $limit = 0;

            if (!isset($arOrder["ID"]))
                $arOrder["ID"] = "DESC";

            $intOfferIBlockID = $arOffersIBlock["OFFERS_IBLOCK_ID"];

            $productProperty = 'PROPERTY_'.$arOffersIBlock['OFFERS_PROPERTY_ID'];
            $productPropertyValue = $productProperty.'_VALUE';

            $propertyList = array();
            if (!empty($arSelectProperties))
            {
                $selectProperties = array_fill_keys($arSelectProperties, true);
                $propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
                    'select' => array('ID', 'CODE'),
                    'filter' => array('=IBLOCK_ID' => $intOfferIBlockID, '=ACTIVE' => 'Y'),
                    'order' => array('SORT' => 'ASC', 'ID' => 'ASC')
                ));
                while ($property = $propertyIterator->fetch())
                {
                    $code = (string)$property['CODE'];
                    if ($code == '')
                        $code = $property['ID'];
                    if (!isset($selectProperties[$code]))
                        continue;
                    $propertyList[] = $code;
                    unset($code);
                }
                unset($property, $propertyIterator);
                unset($selectProperties);
            }

            $arFilter = array(
                "IBLOCK_ID" => $intOfferIBlockID,
                $productProperty => $arElementID,
                "ACTIVE" => "Y",
                "ACTIVE_DATE" => "Y",
            );

            if (!empty($customFilter))
            {
                $arFilter[] = $customFilter;
            }

            if ($boolHideNotAvailable)
                $arFilter['CATALOG_AVAILABLE'] = 'Y';
            if ($boolCheckPermissions)
            {
                $arFilter['CHECK_PERMISSIONS'] = "Y";
                $arFilter['MIN_PERMISSION'] = "R";
            }

            $arSelect = array(
                "ID" => 1,
                "IBLOCK_ID" => 1,
                $productProperty => 1,
                "CATALOG_QUANTITY" => 1
            );
            //if(!$arParams["USE_PRICE_COUNT"])
            {
                foreach($arPrices as $value)
                {
                    if (!$value['CAN_VIEW'] && !$value['CAN_BUY'])
                        continue;
                    $arSelect[$value["SELECT"]] = 1;
                    if ($showPriceCount !== false)
                    {
                        $arFilter['CATALOG_SHOP_QUANTITY_'.$value['ID']] = $showPriceCount;
                    }
                }
            }

            if (!empty($arSelectFields))
            {
                foreach ($arSelectFields as $code)
                    $arSelect[$code] = 1; //mark to select
                unset($code);
            }
            $checkFields = array();
            foreach (array_keys($arOrder) as $code)
            {
                $code = strtoupper($code);
                $arSelect[$code] = 1;
                if ($code == 'ID' || $code == 'CATALOG_AVAILABLE')
                    continue;
                $checkFields[] = $code;
            }
            unset($code);

            if (!isset($arSelect['PREVIEW_PICTURE']))
                $arSelect['PREVIEW_PICTURE'] = 1;
            if (!isset($arSelect['DETAIL_PICTURE']))
                $arSelect['DETAIL_PICTURE'] = 1;

            $arOfferIDs = array();
            $arMeasureMap = array();
            $intKey = 0;
            $arOffersPerElement = array();
            $arOffersLink = array();
            $extPrices = array();

            if($_GET['avail'] == 1)
                $arFilter['>CATALOG_QUANTITY'] = 0;

            $rsOffers = CIBlockElement::GetList($arOrder, $arFilter, false, false, array_keys($arSelect));
            while($arOffer = $rsOffers->GetNext())
            {
                $arOffer['ID'] = (int)$arOffer['ID'];
                $element_id = (int)$arOffer[$productPropertyValue];
                //No more than limit offers per element
                if($limit > 0)
                {
                    $arOffersPerElement[$element_id]++;
                    if($arOffersPerElement[$element_id] > $limit)
                        continue;
                }

                if($element_id > 0)
                {
                    $arOffer['SORT_HASH'] = 'ID';
                    if (!empty($checkFields))
                    {
                        $checkValues = '';
                        foreach ($checkFields as $code)
                            $checkValues .= (isset($arOffer[$code]) ? $arOffer[$code] : '').'|';
                        unset($code);
                        if ($checkValues != '')
                            $arOffer['SORT_HASH'] = md5($checkValues);
                        unset($checkValues);
                    }
                    $arOffer["LINK_ELEMENT_ID"] = $element_id;
                    $arOffer["PROPERTIES"] = array();
                    $arOffer["DISPLAY_PROPERTIES"] = array();

                    if(!empty($arOffer['PREVIEW_PICTURE']))
                        self::$fileID[$arOffer['PREVIEW_PICTURE']]  = $arOffer['PREVIEW_PICTURE'];

                    if(!empty($arOffer['DETAIL_PICTURE']))
                        self::$fileID[$arOffer['DETAIL_PICTURE']]  = $arOffer['DETAIL_PICTURE'];


                    $arOffer['CHECK_QUANTITY'] = ('Y' == $arOffer['CATALOG_QUANTITY_TRACE'] && 'N' == $arOffer['CATALOG_CAN_BUY_ZERO']);
                    $arOffer['CATALOG_TYPE'] = CCatalogProduct::TYPE_OFFER;
                    $arOffer['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
                    $arOffer['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
                    $arOffer["CATALOG_MEASURE_RATIO"] = 1;
                    if (!isset($arOffer['CATALOG_MEASURE']))
                        $arOffer['CATALOG_MEASURE'] = 0;
                    $arOffer['CATALOG_MEASURE'] = (int)$arOffer['CATALOG_MEASURE'];
                    if (0 > $arOffer['CATALOG_MEASURE'])
                        $arOffer['CATALOG_MEASURE'] = 0;
                    if (0 < $arOffer['CATALOG_MEASURE'])
                    {
                        if (!isset($arMeasureMap[$arOffer['CATALOG_MEASURE']]))
                            $arMeasureMap[$arOffer['CATALOG_MEASURE']] = array();
                        $arMeasureMap[$arOffer['CATALOG_MEASURE']][] = $intKey;
                    }

                    $arOfferIDs[] = $arOffer['ID'];
                    $arResult[$intKey] = $arOffer;
                    if (!isset($arOffersLink[$arOffer['ID']]))
                    {
                        $arOffersLink[$arOffer['ID']] = &$arResult[$intKey];
                    }
                    else
                    {
                        if (!isset($extPrices[$arOffer['ID']]))
                        {
                            $extPrices[$arOffer['ID']] = array();
                        }
                        $extPrices[$arOffer['ID']][] = &$arResult[$intKey];
                    }
                    $intKey++;
                }
            }


            if(!empty( self::$fileID )) {

                self::GetImages();

                foreach ($arOfferIDs as $offerKeyId => &$offerValId) {

                    foreach (['PREVIEW_PICTURE', 'DETAIL_PICTURE'] as $codeName) {
                        $entity = (string)\Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT;
                        $ipropertyKey = 'IPROPERTY_VALUES';

                        $imageData = !empty(self::$fileID[$offerValId[$codeName]]) ? self::$fileID[$offerValId[$codeName]] : false;
                        if (is_array($imageData)) {

                            if (isset($imageData['SAFE_SRC'])) {
                                $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                                $imageData['SRC'] = $imageData['SAFE_SRC'];
                            } else {
                                if (!preg_match('/^(ftp|ftps|http|https):\/\//', $imageData['SRC'])) {
                                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                                    $imageData['SAFE_SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                                }
                            }
                            $imageData['ALT'] = '';
                            $imageData['TITLE'] = '';
                            if ($ipropertyKey != '' && isset($offerValId[$ipropertyKey]) && is_array($offerValId[$ipropertyKey])) {
                                $entityPrefix = $entity . '_' . $codeName;
                                if (isset($offerValId[$ipropertyKey][$entityPrefix . '_FILE_ALT']))
                                    $imageData['ALT'] = $offerValId[$ipropertyKey][$entityPrefix . '_FILE_ALT'];
                                if (isset($offerValId[$ipropertyKey][$entityPrefix . '_FILE_TITLE']))
                                    $imageData['TITLE'] = $offerValId[$ipropertyKey][$entityPrefix . '_FILE_TITLE'];
                                unset($entityPrefix);
                            }
                            if ($imageData['ALT'] == '' && isset($offerValId['NAME']))
                                $imageData['ALT'] = $offerValId['NAME'];
                            if ($imageData['TITLE'] == '' && isset($offerValId['NAME']))
                                $imageData['TITLE'] = $offerValId['NAME'];


                        }
                    }

                }
            }

            if (!empty($arOfferIDs))
            {
                $rsRatios = CCatalogMeasureRatio::getList(
                    array(),
                    array('@PRODUCT_ID' => $arOfferIDs),
                    false,
                    false,
                    array('PRODUCT_ID', 'RATIO')
                );
                while ($arRatio = $rsRatios->Fetch())
                {
                    $arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
                    if (isset($arOffersLink[$arRatio['PRODUCT_ID']]))
                    {
                        $intRatio = (int)$arRatio['RATIO'];
                        $dblRatio = (float)$arRatio['RATIO'];
                        $mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
                        if (CATALOG_VALUE_EPSILON > abs($mxRatio))
                            $mxRatio = 1;
                        elseif (0 > $mxRatio)
                            $mxRatio = 1;
                        $arOffersLink[$arRatio['PRODUCT_ID']]['CATALOG_MEASURE_RATIO'] = $mxRatio;
                    }
                }

                if (!empty($propertyList))
                {
                    CIBlockElement::GetPropertyValuesArray($arOffersLink, $intOfferIBlockID, $arFilter);
                    foreach ($arResult as &$arOffer)
                    {
                        if (self::$needDiscountCache)
                            CCatalogDiscount::SetProductPropertiesCache($arOffer['ID'], $arOffer["PROPERTIES"]);
                        if (\Bitrix\Main\Config\Option::get('sale', 'use_sale_discount_only') === 'Y')
                            \Bitrix\Catalog\Discount\DiscountManager::setProductPropertiesCache($arOffer['ID'], $arOffer["PROPERTIES"]);

                        foreach ($propertyList as $pid)
                        {
                            if (!isset($arOffer["PROPERTIES"][$pid]))
                                continue;
                            $prop = &$arOffer["PROPERTIES"][$pid];
                            $boolArr = is_array($prop["VALUE"]);
                            if(
                                ($boolArr && !empty($prop["VALUE"])) ||
                                (!$boolArr && (string)$prop["VALUE"] !== '')
                            )
                            {
                                $arOffer["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arOffer, $prop, "catalog_out");
                            }
                            unset($boolArr, $prop);
                        }
                        unset($pid);
                    }
                    unset($arOffer);
                }

                if (!empty($extPrices))
                {
                    foreach ($extPrices as $origID => $prices)
                    {
                        foreach ($prices as $oneRow)
                        {
                            $oneRow['PROPERTIES'] = $arOffersLink[$origID]['PROPERTIES'];
                            $oneRow['DISPLAY_PROPERTIES'] = $arOffersLink[$origID]['DISPLAY_PROPERTIES'];
                            $oneRow['CATALOG_MEASURE_RATIO'] = $arOffersLink[$origID]['CATALOG_MEASURE_RATIO'];
                        }
                    }
                }
                if (self::$needDiscountCache)
                {
                    CCatalogDiscount::SetProductSectionsCache($arOfferIDs);
                    CCatalogDiscount::SetDiscountProductCache($arOfferIDs, array('IBLOCK_ID' => $intOfferIBlockID, 'GET_BY_ID' => 'Y'));
                }
                if (\Bitrix\Main\Config\Option::get('sale', 'use_sale_discount_only') === 'Y')
                {
                    $pricesAllow = CIBlockPriceTools::GetAllowCatalogPrices($arPrices);
                    if (!empty($pricesAllow))
                    {
                        $USER_ID = (int)$USER_ID;
                        $userGroups = array(2);
                        if ($USER_ID > 0)
                            $userGroups = CUser::GetUserGroup($USER_ID);
                        elseif (isset($USER) && $USER instanceof CUser)
                            $userGroups = $USER->GetUserGroupArray();
                        \Bitrix\Catalog\Discount\DiscountManager::preloadPriceData($arOfferIDs, $pricesAllow);
                        \Bitrix\Catalog\Discount\DiscountManager::preloadProductDataToExtendOrder($arOfferIDs, $userGroups);
                        unset($userGroups);
                    }
                    unset($pricesAllow);
                }
                foreach ($arResult as &$arOffer)
                {
                    $arOffer['CATALOG_QUANTITY'] = (
                    0 < $arOffer['CATALOG_QUANTITY'] && is_float($arOffer['CATALOG_MEASURE_RATIO'])
                        ? (float)$arOffer['CATALOG_QUANTITY']
                        : (int)$arOffer['CATALOG_QUANTITY']
                    );
                    $arOffer['MIN_PRICE'] = false;
                    $arOffer["PRICES"] = CIBlockPriceTools::GetItemPrices($arOffersIBlock["OFFERS_IBLOCK_ID"], $arPrices, $arOffer, $vat_include, $arCurrencyParams, $USER_ID, $LID);
                    if (!empty($arOffer["PRICES"]))
                    {
                        foreach ($arOffer['PRICES'] as &$arOnePrice)
                        {
                            if ($arOnePrice['MIN_PRICE'] == 'Y')
                            {
                                $arOffer['MIN_PRICE'] = $arOnePrice;
                                break;
                            }
                        }
                        unset($arOnePrice);
                    }
                    $arOffer["CAN_BUY"] = CIBlockPriceTools::CanBuy($arOffersIBlock["OFFERS_IBLOCK_ID"], $arPrices, $arOffer);
                }
                if (isset($arOffer))
                    unset($arOffer);
            }
            if (!empty($arMeasureMap))
            {
                $rsMeasures = CCatalogMeasure::getList(
                    array(),
                    array('@ID' => array_keys($arMeasureMap)),
                    false,
                    false,
                    array('ID', 'SYMBOL_RUS')
                );
                while ($arMeasure = $rsMeasures->GetNext())
                {
                    $arMeasure['ID'] = (int)$arMeasure['ID'];
                    if (isset($arMeasureMap[$arMeasure['ID']]) && !empty($arMeasureMap[$arMeasure['ID']]))
                    {
                        foreach ($arMeasureMap[$arMeasure['ID']] as $intOneKey)
                        {
                            $arResult[$intOneKey]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
                            $arResult[$intOneKey]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
                        }
                        unset($intOneKey);
                    }
                }
            }
        }

        return $arResult;
    }


    public static function GetImages()
    {
        if (!empty(CIBlockPriceToolsNewsite::$fileID)) {
            $uploadDir = COption::GetOptionString("main", "upload_dir", "upload");
            $dbl = CFile::GetList([], ["@ID" => implode(",", array_filter(self::$fileID, function ($a) {
                return !is_array($a) && intval($a);
            }))]);
            while ($res = $dbl->fetch()) {
                $res['SRC'] = "/$uploadDir/" . $res["SUBDIR"] . "/" . $res["FILE_NAME"];
                self::$fileID[$res["ID"]] = $res;
            }

            self::$fileID = array_filter(self::$fileID, function ($a) {
                return is_array($a);
            });
        }

        return self::$fileID;
    }

    public static function getFieldImageData(array &$item, array $keys, $entity, $ipropertyKey = 'IPROPERTY_VALUES')
    {
        if (empty($item) || empty($keys))
            return;

        $entity = (string)$entity;
        $ipropertyKey = (string)$ipropertyKey;
        foreach ($keys as $fieldName)
        {
            if (!isset($item[$fieldName]) || is_array($item[$fieldName]))
                continue;
            $imageData = false;
            $imageId = (int)$item[$fieldName];
            if ($imageId > 0)
                $imageData = \CFile::getFileArray($imageId);
            unset($imageId);
            if (is_array($imageData))
            {
                if (isset($imageData['SAFE_SRC']))
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                }
                else
                {
                    if (!preg_match('/^(ftp|ftps|http|https):\/\//', $imageData['SRC']))
                    {
                        $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                        $imageData['SAFE_SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                        $imageData['SRC'] = $imageData['SAFE_SRC'];
                    }
                }
                $imageData['ALT'] = '';
                $imageData['TITLE'] = '';
                if ($ipropertyKey != '' && isset($item[$ipropertyKey]) && is_array($item[$ipropertyKey]))
                {
                    $entityPrefix = $entity.'_'.$fieldName;
                    if (isset($item[$ipropertyKey][$entityPrefix.'_FILE_ALT']))
                        $imageData['ALT'] = $item[$ipropertyKey][$entityPrefix.'_FILE_ALT'];
                    if (isset($item[$ipropertyKey][$entityPrefix.'_FILE_TITLE']))
                        $imageData['TITLE'] = $item[$ipropertyKey][$entityPrefix.'_FILE_TITLE'];
                    unset($entityPrefix);
                }
                if ($imageData['ALT'] == '' && isset($item['NAME']))
                    $imageData['ALT'] = $item['NAME'];
                if ($imageData['TITLE'] == '' && isset($item['NAME']))
                    $imageData['TITLE'] = $item['NAME'];
            }
            $item[$fieldName] = $imageData;
            unset($imageData);
        }
        unset($fieldName);
    }


    public static function getDoublePicturesForItem(&$item, $propertyCode, $encode = true)
    {
        $encode = ($encode === true);
        $result = array(
            'PICT' => false,
            'SECOND_PICT' => false
        );

        if (!empty($item) && is_array($item))
        {
            if (!empty($item['PREVIEW_PICTURE']))
            {
                if (!is_array($item['PREVIEW_PICTURE']))
                    $item['PREVIEW_PICTURE'] = CFile::GetFileArray($item['PREVIEW_PICTURE']);
                if (isset($item['PREVIEW_PICTURE']['ID']))
                {
                    $result['PICT'] = $item['PREVIEW_PICTURE'];
                }
            }
            if (!empty($item['DETAIL_PICTURE']))
            {
                $keyPict = (empty($result['PICT']) ? 'PICT' : 'SECOND_PICT');
                if (!is_array($item['DETAIL_PICTURE']))
                    $item['DETAIL_PICTURE'] = CFile::GetFileArray($item['DETAIL_PICTURE']);
                if (isset($item['DETAIL_PICTURE']['ID']))
                {
                    $result[$keyPict] = $item['DETAIL_PICTURE'];
                }
            }
            if (empty($result['SECOND_PICT']))
            {
                if (
                    '' != $propertyCode &&
                    isset($item['PROPERTIES'][$propertyCode]) &&
                    'F' == $item['PROPERTIES'][$propertyCode]['PROPERTY_TYPE']
                )
                {
                    if (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) &&
                        !empty($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE'])
                    )
                    {
                        $fileValues = (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']['ID']) ?
                            array(0 => $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) :
                            $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']
                        );
                        foreach ($fileValues as $oneFileValue)
                        {
                            $keyPict = (empty($result['PICT']) ? 'PICT' : 'SECOND_PICT');
                            $result[$keyPict] = array(
                                'ID' => (int)$oneFileValue['ID'],
                                'SRC' => \Bitrix\Iblock\Component\Tools::getImageSrc($oneFileValue, $encode),
                                'WIDTH' => (int)$oneFileValue['WIDTH'],
                                'HEIGHT' => (int)$oneFileValue['HEIGHT']
                            );
                            if ('SECOND_PICT' == $keyPict)
                                break;
                        }
                        if (isset($oneFileValue))
                            unset($oneFileValue);
                    }
                    else
                    {
                        $propValues = $item['PROPERTIES'][$propertyCode]['VALUE'];
                        if (!is_array($propValues))
                            $propValues = array($propValues);
                        foreach ($propValues as $oneValue)
                        {
                            $oneFileValue = CFile::GetFileArray($oneValue);
                            if (isset($oneFileValue['ID']))
                            {
                                $keyPict = (empty($result['PICT']) ? 'PICT' : 'SECOND_PICT');
                                $oneFileValue['SRC'] = \Bitrix\Iblock\Component\Tools::getImageSrc($oneFileValue, $encode);
                                $result[$keyPict] = $oneFileValue;

                                if ('SECOND_PICT' == $keyPict)
                                    break;
                            }
                        }
                        if (isset($oneValue))
                            unset($oneValue);
                    }
                }
            }
        }
        return $result;
    }


}