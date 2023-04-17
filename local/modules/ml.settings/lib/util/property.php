<?

namespace Ml\Settings\Util;

use Ml\Settings\Model\PropertyTable;

class Property
{
    // private array $arResult;
    private $arResult; // ( исправление ) --- карточка товара ругалась на неправильное объявление свойства


    public function __construct(array &$arResult)
    {
        $this->arResult = $arResult;
    }

    private function getSections($arSectionPath)
    {
        $arSections = [];
        foreach ($arSectionPath as $arSection) {
            $arSections[] = $arSection['ID'];
        }
        return $arSections;
    }

    private function CheckProperty(array &$arProp, array $arItem)
    {
        if ($arProp['MULTIPLE'] == 'Y') {
            foreach ($arProp['VALUE'] as &$arValue) {
                self::CheckValue($arProp['PROPERTY_TYPE'], $arProp['USER_TYPE_SETTINGS'], $arValue, $arItem['PROPERTY_VALUE'], $arItem['LINK'], $arItem['NEW_WINDOW']);
            }
        } else {
            self::CheckValue($arProp['PROPERTY_TYPE'], $arProp['USER_TYPE_SETTINGS'], $arProp['VALUE'], $arItem['PROPERTY_VALUE'], $arItem['LINK'], $arItem['NEW_WINDOW']);
        }
    }

    private function CheckValue(string $propType, $userType, &$value, string $valueCheck, string $linkUrl, string $newWindow)
    {
        if($newWindow == 'Y'){
            $newWindow = 'target="_blank"';
        }else{
            $newWindow = '';
        }

        switch ($propType) {
            case 'L':
                if ($value == $valueCheck) {
                    $value = '<a href="' . $linkUrl . '" style="text-decoration:underline" '.$newWindow.'>' . $value . '</a>';
                }
                break;
            case 'S':
                if (!empty($userType)) {
                    if (
                        isset($this->arResult['HIGHLOAD_VALUES'][$userType['TABLE_NAME']][$value]) &&
                        $this->arResult['HIGHLOAD_VALUES'][$userType['TABLE_NAME']][$value]['UF_NAME'] == $valueCheck
                    ) {
                        $this->arResult['HIGHLOAD_VALUES'][$userType['TABLE_NAME']][$value]['UF_NAME'] = '<a href="' . $linkUrl . '" style="text-decoration:underline" '.$newWindow.'>' . $valueCheck . '</a>';
                    }
                } else {
                    if ($value == $valueCheck) {
                        $value = '<a href="' . $linkUrl . '" style="text-decoration:underline" '.$newWindow.'>' . $value . '</a>';
                    }
                }
                break;
        }
    }

    private function CheckItem($arProperties)
    {
        foreach ($arProperties as $arItem) {
            if (isset($this->arResult['DISPLAY_PROPERTIES'][$arItem['PROPERTY']])) {
                self::CheckProperty($this->arResult['DISPLAY_PROPERTIES'][$arItem['PROPERTY']], $arItem);
            }
        }
    }

    private function CheckOffers($arProperties)
    {
        foreach ($this->arResult['OFFERS'] as &$arOffer) {
            foreach ($arProperties as $arItem) {
                if (isset($arOffer['PROPERTIES'][$arItem['PROPERTY']])) {
                    self::CheckProperty($arOffer['PROPERTIES'][$arItem['PROPERTY']], $arItem);
                }
            }
        }
    }

    public function SetValues()
    {
        $arSections = self::getSections($this->arResult['SECTION']['PATH']);
        if ($arSections) {

            $arProperties = [];
            $iterator = PropertyTable::getList([
                'select' => ['*'],
                'filter' => ['SECTION' => $arSections],
            ]);
            while ($arItem = $iterator->fetch()) {
                $arProperties[] = $arItem;
            }

            self::CheckItem($arProperties);
            self::CheckOffers($arProperties);

            return $this->arResult;
        }
    }
}
