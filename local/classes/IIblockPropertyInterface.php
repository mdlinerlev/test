<?
/**
 * @author Dmitry Sharyk
 * email: d.sharyk@gmail.com
 */
abstract class IIblockPropertyInterface
{

    function GetItemName($template, $itemData)
    {

        $template = strval($template);

        if (!is_array($itemData) || empty($itemData)) {
            return $template;
        }

        if (strlen(trim($template)) === 0) {
            return self::GetItemName("[#ID#] #NAME#", $itemData);
        }


        $search = array_map(function($v) {
            return "#{$v}#";
        }, array_keys($itemData));

        return str_replace($search, $itemData, $template) ?: self::GetItemName("#NAME#", $itemData);
    }

    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $settings = self::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';


        $options = self::GetOptionsHtml($arProperty, array($value["VALUE"]));

        $html = '<select class="js_select" style="max-width:90%" name="' . $strHTMLControlName["VALUE"] . '"' . $size . $width . '>';
        if ($arProperty["IS_REQUIRED"] != "Y")
            $html .= '<option value="">' . "--//--" . '</option>';
        $html .= $options;
        $html .= '</select>';
        return $html;
    }

    function GetPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName)
    {
        $max_n = 0;
        $values = array();
        if (is_array($value)) {
            foreach ($value as $property_value_id => $arValue) {
                $values[$property_value_id] = $arValue["VALUE"] ?: $arValue;
                if (preg_match("/^n(\\d+)$/", $property_value_id, $match)) {
                    if ($match[1] > $max_n)
                        $max_n = intval($match[1]);
                }
            }
        }

        $settings = self::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';

        if ($settings["multiple"] == "Y") {
            $options = self::GetOptionsHtml($arProperty, $values);

            $html = '<input type="hidden" name="' . $strHTMLControlName["VALUE"] . '[]" value="">';
            $html .= '<select  class="js_select" multiple name="' . $strHTMLControlName["VALUE"] . '[]"' . $size . $width . '>';
            if ($arProperty["IS_REQUIRED"] != "Y")
                $html .= '<option value="">' . "--//--" . '</option>';
            $html .= $options;
            $html .= '</select>';
        } else {
            if (end($values) != "" || substr(key($values), 0, 1) != "n")
                $values["n" . ($max_n + 1)] = "";

            $name = $strHTMLControlName["VALUE"] . "VALUE";

            $html = '<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%" id="tb' . md5($name) . '">';
            foreach ($values as $property_value_id => $value) {
                $html .= '<tr><td>';


                $options = self::GetOptionsHtml($arProperty, array($value));

                $html .= '<select  class="js_select" name="' . $strHTMLControlName["VALUE"] . '[' . $property_value_id . '][VALUE]"' . $size . $width . '>';
                $html .= '<option value="">' . "--//--" . '</option>';
                $html .= $options;
                $html .= '</select>';

                $html .= '</td></tr>';
            }
            $html .= '</table>';

            $html .= '<input type="button" value="..." onClick="if(window.addNewRow){addNewRow(\'tb' . md5($name) . '\', -1)}else{addNewTableRow(\'tb' . md5($name) . '\', 1, /\[(n)([0-9]*)\]/g, 2)}">';
        }
        return $html;
    }

    function GetUIFilterProperty($arProperty, $strHTMLControlName, &$filter)
    {
        $filter["type"] = "list";
        $filter["value"] = 0;
        $elements = self::GetElements($arProperty, $values, true);


        $filter["items"] = [];

        foreach ($elements as $elemID => $propData) {
            $filter["items"][$elemID] = $propData["NAME"];
        }

        return;
    }

    function GetAdminFilterHTML($arProperty, $strHTMLControlName)
    {
        if (isset($_REQUEST[$strHTMLControlName["VALUE"]]) && is_array($_REQUEST[$strHTMLControlName["VALUE"]]))
            $values = $_REQUEST[$strHTMLControlName["VALUE"]];
        else
            $values = array();

        $settings = self::PrepareSettings($arProperty);
        if ($settings["size"] > 1)
            $size = ' size="' . $settings["size"] . '"';
        else
            $size = '';

        if ($settings["width"] > 0)
            $width = ' style="width:' . $settings["width"] . 'px"';
        else
            $width = '';


        $options = self::GetOptionsHtml($arProperty, $values);

        $html = '<select class="js_select"  multiple name="' . $strHTMLControlName["VALUE"] . '[]"' . $size . $width . '>';
        $html .= '<option value="">' . GetMessage("IBLOCK_PROP_ELEMENT_LIST_ANY_VALUE") . '</option>';
        $html .= $options;
        $html .= '</select>';
        return $html;
    }

    function PrepareSettings($arProperty)
    {
        $setting = (!is_array($arProperty["USER_TYPE_SETTINGS"])) ? array() : $arProperty["USER_TYPE_SETTINGS"];

        return array(
            "size" => intval($setting["size"]) > 0 ? intval($setting["size"]) : 0,
            "width" => intval($setting["width"]) > 0 ? intval($setting["width"]) : 0,
            "multiple" => ( $setting["multiple"] === "Y") ? "Y" : "N",
        );
    }

    function GetOptionsHtml($arProperty, $values)
    {
        $settings = self::PrepareSettings($arProperty);

        $elements = static::GetElements($arProperty);
        $options = self::BuildOption($elements, $values);

        return $options;
    }

    function BuildOption($elements, $values)
    {
        $values = (array) $values;
        $options = "";
        foreach ($elements as $arItem) {
            $options .= '<option value="' . $arItem["ID"] . '"';
            if (in_array($arItem["ID"], $values)) {
                $options .= ' selected';
            }
            $options .= '>' . $arItem["NAME"] . '</option>';
        }
        return $options;
    }

    abstract public static function GetElements($arProperty);

    abstract public static function GetUserTypeDescription();
}
