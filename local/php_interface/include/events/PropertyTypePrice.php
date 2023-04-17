<?

use Bitrix\Main\Loader;

class PropertyTypePrice{
    public static function GetUserTypeDescription()
    {
        Loader::includeModule('iblock');
        return array(
            'USER_TYPE_ID' => 'pricetype',
            'USER_TYPE' => 'PRICETYPE',
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Тип цены',
            'PROPERTY_TYPE' => 'S',
            'BASE_TYPE' => 'string',
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
        );
    }

    public static function ConvertToDB($arProperty, $value)
    {
        return $value;
    }

    public static function ConvertFromDB($arProperty, $value, $format = '')
    {
        return $value;
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
    {
        $fieldName = htmlspecialcharsbx($arHtmlControl['VALUE']);
        $html = '<select name="'.$fieldName.'">';
        $iterator = \Bitrix\Catalog\GroupLangTable::getList([
            'select' => ['CATALOG_GROUP_ID', 'NAME'],
            'filter' => ['LANG' => 'ru', '!NAME' => false]
        ]);
        while ($arGroup = $iterator->fetch()) {
            $selected = ($arGroup['CATALOG_GROUP_ID'] == $value['VALUE']) ? 'selected' : '';
            $html .= '<option value="'.$arGroup['CATALOG_GROUP_ID'].'" '.$selected.' >'.$arGroup['NAME'].'</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
