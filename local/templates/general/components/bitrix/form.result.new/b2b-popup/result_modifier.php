<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if(!empty($arParams['VALUES'])){

    foreach ($arResult["QUESTIONS"] as $FIELD_SID => &$arQuestion) {
        if(isset($arParams['VALUES'][$FIELD_SID])){
            $arQuestion["HTML_CODE"] = str_replace('<input ', '<input value="'.$arParams['VALUES'][$FIELD_SID].'"', $arQuestion["HTML_CODE"]);
        }
    }

}