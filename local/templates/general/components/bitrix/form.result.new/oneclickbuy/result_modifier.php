<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


$arResult['PRODUCT_PROP'] = [];

if(CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog") && !empty($arParams['PRODUCT_ID'])){

    $elementID = $elementSkuID = $arParams['PRODUCT_ID'];
    $arResult['PRODUCT'] = '#id'.$elementSkuID;
    $iblockID = 2;
    $mxResult = \CCatalogSku::GetProductInfo($elementSkuID);
    if (is_array($mxResult)) {
        $iblockID = 12;
        $elementID = $mxResult['ID'];
    }


    if($elementID != $elementSkuID)
        $arResult['PRODUCT_NAME'] = CIBlockElement::GetByID($elementID)->GetNext()['NAME'];

    $arSelect = ["ID", "IBLOCK_ID", "NAME","PROPERTY_*"];
    $arFilter = ["IBLOCK_ID"=> $iblockID, "=ID"=> $elementSkuID];
    $res = CIBlockElement::GetList([], $arFilter, false, ["nTopCount"=>1], $arSelect);
    if($ob = $res->GetNextElement()){
        $arData = $ob->GetFields();
        $arData['PROPERTIES'] = $ob->GetProperties();

        if(!isset( $arResult['PRODUCT_NAME'] ))
            $arResult['PRODUCT_NAME']= $arData['NAME'];

        $arResult['PRODUCT'] = $arData['NAME'];
        foreach ($arData['PROPERTIES'] as $prop){
            if(!empty($prop['VALUE']) && in_array($prop['CODE'], ["SIZE", "SIDE", "COLOR", "COLOR_IN", "COLOR_OUT", "GLASS_COLOR"])) {
                $prop = CIBlockFormatProperties::GetDisplayValue($arData, $prop, "catalog_out");
                $arResult['PRODUCT'] .= "\n".$prop['NAME'].': '.$prop['DISPLAY_VALUE'];
                $arResult['PROP'][] = $prop;
            }
        }
    }
}


?>