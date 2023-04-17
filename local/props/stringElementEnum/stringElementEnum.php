<?

/**
 * @author Dmitry Sharyk
 * email: d.sharyk@gmail.com
 */
class CPropertyElementEnum extends IIblockPropertyInterface {

    public static function GetUserTypeDescription() {

        return array(
            "PROPERTY_TYPE"             => "S",
            "USER_TYPE"                 => "enumList",
            "DESCRIPTION"               => "NewsiteProperty - Строковый список",
            "GetPropertyFieldHtml"      => array("CPropertyElementEnum", "GetPropertyFieldHtml"),
            "GetPropertyFieldHtmlMulty" => array("CPropertyElementEnum", "GetPropertyFieldHtmlMulty"),
            "GetPublicEditHTML"         => array("CPropertyElementEnum", "GetPropertyFieldHtml"),
            "GetPublicEditHTMLMulty"    => array("CPropertyElementEnum", "GetPropertyFieldHtmlMulty"),
            "GetAdminFilterHTML"        => array("CPropertyElementEnum", "GetAdminFilterHTML"),
            "PrepareSettings"           => array("CPropertyElementEnum", "PrepareSettings"),
            "GetSettingsHTML"           => array("CPropertyElementEnum", "GetSettingsHTML")
        );
    }

    public static function GetElements($arProperty) {
        $settings = self::PrepareSettings($arProperty);
        $values = $settings["VALUES"]? : array();

        $values = array_values(array_filter($values, function($a)
        {
            $a["NAME"] = trim($a["NAME"]);
            $a["ID"] = trim($a["ID"]);
            return strlen($a["ID"]) && strlen($a["NAME"]);
        }));

        uasort($values, function ($a, $b)
        {
            if ($a["SORT"] == $b["SORT"]) {
                $strCmpResult = strcasecmp(trim($a["NAME"]), trim($b["NAME"]));
                if (!$strCmpResult)
                    return 0;

                return ($strCmpResult < 0) ? -1 : 1;
            }

            return ($a["SORT"] < $b["SORT"]) ? -1 : 1;
        });

        return $values;
    }

    function PrepareSettings($arProperty) {

        $settings = (!is_array($arProperty["USER_TYPE_SETTINGS"])) ? array() : $arProperty["USER_TYPE_SETTINGS"];


        $settings["VALUES"] = array_values(array_filter($settings["VALUES"], function($a)
        {
            $a["ID"] = trim($a["ID"]);
            $a["NAME"] = trim($a["NAME"]);
            return strlen($a["ID"]) && strlen($a["NAME"]);
        }));


        $settings["VALUES"] = array_map(function($a)
        {
            $a["SORT"] = intval($a["SORT"]);
            return $a;
        }, $settings["VALUES"]);


        uasort($settings["VALUES"], function ($a, $b)
        {
            if ($a["SORT"] == $b["SORT"]) {
                $strCmpResult = strcasecmp(trim($a["NAME"]), trim($b["NAME"]));
                if (!$strCmpResult)
                    return 0;

                return ($strCmpResult < 0) ? -1 : 1;
            }

            return ($a["SORT"] < $b["SORT"]) ? -1 : 1;
        }
        );

        return $settings;
    }

    function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields) {
        $settings = self::PrepareSettings($arProperty);

        $arPropertyFields = array(
            "SHOW" => array(""),
            "HIDE" => array(
                "LINK_IBLOCK_ID",
                "WITH_DESCRIPTION",
                "SEARCHABLE",
                "ROW_COUNT",
                "LINK_IBLOCK_ID",
                "COL_COUNT",
                "DEFAULT_VALUE",
                "FILTRABLE",
                "SMART_FILTER"
            ),
        );

        ob_start();
        require __DIR__ . '/enuminc.php';

        return ob_get_clean();
    }

}
