<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$arResult["CATEGORIES"] = [];

//$PREVIEW_WIDTH = intval($arParams["PREVIEW_WIDTH"]);
//if ($PREVIEW_WIDTH <= 0)
//    $PREVIEW_WIDTH = 75;
//
//$PREVIEW_HEIGHT = intval($arParams["PREVIEW_HEIGHT"]);
//if ($PREVIEW_HEIGHT <= 0)
//    $PREVIEW_HEIGHT = 75;
//
//$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";
//
//$arCatalogs = false;

//$arResult["ELEMENTS"] = $arResult["CATEGORIES"][0];


//$arResult["ELEMENTS"] = array();
//$arResult["CATEGORIES"] = array();
//if ($arResult['query']) {
//    $quantity = 1;
//    global $USER;
//    $GetUserGroupArray = $USER->GetUserGroupArray();
//    if (CModule::IncludeModule("catalog") and CModule::IncludeModule("iblock")) {
//        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "*", "PROPERTY_MINIMUM_PRICE");
//        $arFilter = Array(
//            "IBLOCK_ID" => 2,
//            "ACTIVE_DATE" => "Y",
//            "SECTION_ACTIVE" => 'Y',
//            "SECTION_GLOBAL_ACTIVE" => "Y",
//            "ACTIVE" => "Y",
//            '!SECTION_ID' => array(45, 30, 34, 33, 32, 31, false),
//            "INCLUDE_SUBSECTIONS" => "Y",
//            "%NAME" => $arResult['query']
//        );
//        $res = CIBlockElement::GetList(Array('ID'=>'DESC'), $arFilter, false, Array("nPageSize" => 15), $arSelect);
//        while ($arFields = $res->GetNext()) {
//            $result = array();
//            $result['NAME'] = $arFields['NAME'];
//            $result['PREVIEW_PICTURE'] = CFile::ResizeImageGet($arFields['PREVIEW_PICTURE'], array('width' => $PREVIEW_WIDTH, 'height' => $PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//            $result['DETAIL_PAGE_URL'] = $arFields['DETAIL_PAGE_URL'];
//            $productID = $arFields['ID'];
//           /* $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $GetUserGroupArray);
//            if (!$arPrice || count($arPrice) <= 0) {
//                if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($productID, $quantity, $GetUserGroupArray)) {
//                    $quantity = $nearestQuantity;
//                    $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $GetUserGroupArray);
//                }
//            }
//            $result['PRICE'] = $arPrice['RESULT_PRICE'];*/
//            // устоновка на минимальную цены при торговых предложениях
//            if (!$result['PRICE']) {
//                $result['PRICE']['BASE_PRICE'] = $arFields['PROPERTY_MINIMUM_PRICE_VALUE'];
//            }
//            $arResult["ELEMENTS"][$arFields['ID']] = $result;
//        }
//    }
//}