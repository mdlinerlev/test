<?
class CoptimizatorCorrectSphinxFilter {

    function CorectRowData(&$arData, $indexID) {

        if ($arData["INDEX_TYPE"] != "ELEMENT")
            return;

        $queryName =  mb_strtolower($arData["NAME"]);
        $queryDetailText =  mb_strtolower($arData["DETAIL_TEXT"]);
        $exceptions = $_SERVER['DOCUMENT_ROOT'] . '/../../sphinx/data/belwooddoors.ru/wordform.txt';
        if (file_exists($exceptions)) {
            $lines = file($exceptions);
            foreach ($lines as $value) {
                $q = array_map('trim', explode('>', $value));
                $q = array_map('mb_strtolower', $q);
                if(strpos($queryName, $q[1]) !== false) {
                    $arData["NAME"] .= ' ' . $q[0];
                }
                if(strpos($queryDetailText, $q[1]) !== false) {
                    $arData["DETAIL_TEXT"] .= ' ' . $q[0];
                }
            }
        }
    }



}
