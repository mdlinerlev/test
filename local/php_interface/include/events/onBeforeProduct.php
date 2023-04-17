<?
function OnBeforeIBlockElement($ID, $arFields = false)
{
    if(is_array($ID)) {
        $arFields = $ID;
        $IS_AVAILABLE = $arFields["QUANTITY"] > 0? 1: 0;
        $ELEMENT_ID = $arFields["ID"];
        CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array("IS_AVAILABLE" => $IS_AVAILABLE));
    } elseif(is_int($ID) && is_array($arFields) && isset($arFields["QUANTITY"])) {
        $IS_AVAILABLE = $arFields["QUANTITY"] > 0? 1: 0;
        $ELEMENT_ID = $ID;
        CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array("IS_AVAILABLE" => $IS_AVAILABLE));
    }
}
