<?php

/**
 * Class ResizeImgUP
 */
class ResizeImgUP
{
    static function OnBeforeIBlockElementAddHandler(&$arFields)
    {

        $arrPropsId = array(
            array(
                'IBLOCK' => 1,
                'PROP_ID' => 12,
            ),
        );

        foreach ($arrPropsId as $prop) {
            if ($arFields['IBLOCK_ID'] == $prop['IBLOCK']) {

                if (eRU($arFields['PROPERTY_VALUES'][$prop['PROP_ID']])) {
                    foreach ($arFields['PROPERTY_VALUES'][$prop['PROP_ID']] as &$file) {

                        CAllFile::ResizeImage(
                            $file['VALUE'],
                            array("width" => "1500", "height" => "1500"),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );
                    }
                }
            }
        }
    }
}

class MyHtmlRedactorType extends \Bitrix\Main\UserField\Types\StringType
{
    public static function GetUserTypeDescription():array
    {
        return array(
            "USER_TYPE_ID" => "c_string",
            "CLASS_NAME" => "MyHtmlRedactorType",
            "DESCRIPTION" => "Строка в html редакторе",
            "BASE_TYPE" => "string",
        );
    }

    public static function GetEditFormHTML(array $arUserField, ?array $arHtmlControl): string
    {
        //if($arUserField["ENTITY_VALUE_ID"]<1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"] ;) >0)
        //$arHtmlControl["VALUE"] = htmlspecialchars($arUserField["SETTINGS"]["DEFAULT_VALUE"] ;) ;
        ob_start();
        CFileMan::AddHTMLEditorFrame($arHtmlControl["NAME"],
            $arHtmlControl["VALUE"],
            "html",
            "html",
            440,
            "N",
            0,
            "",
            "",
            CATALOG_IBLOCK_ID);
        $b = ob_get_clean();
        return $b;
    }
}


/**
 * Устанавливат минимальную/максимальную цену в свойства товара
 * из торговых предложений для сортировки.
 *
 * Пример:
 * $setter = new MinMaxPriceSetter($iblockId, $offersId, 'CML2_LINK');
 * $setter->init();
 *
 * Предварительно нужно создать св-ва MINIMUM_PRICE и MAXIMUM_PRICE
 * Для нескольких типов цен св-ва должны иметь вид MINIMUM_PRICE_%id цены% и MAXIMUM_PRICE_%id цены%
 * Class MinMaxPriceSetter
 */
class MinMaxPriceSetter
{

    /**
     * @var int id инфоблока каталога
     */
    private $_iblockId;

    /**
     * @var int id инфоблока торговых предложений
     */
    private $_offersId;

    /**
     * @var string св-во тп для связи с товарами
     */
    private $_offersPropId;

    /**
     * @var bool один тип цен | несколько типов (true | false)
     */
    private $_onePrice = true;

    public function __construct($iblockId, $offersId, $offersPropId, $onePrice = null)
    {
        $this->_iblockId = $iblockId;
        $this->_offersId = $offersId;
        $this->_offersPropId = $offersPropId;
        if ($onePrice !== null) {
            $this->_onePrice = (bool)$onePrice;
        }

        $this->_minPriceProp = 'MINIMUM_PRICE';
        $this->_maxPriceProp = 'MAXIMUM_PRICE';
    }

    public function init()
    {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('catalog');
        CModule::IncludeModule('currency');

        AddEventHandler("catalog", "OnPriceAdd", array($this, 'onPriceUpdateHandler'));
        AddEventHandler("catalog", "OnPriceUpdate", array($this, 'onPriceUpdateHandler'));
        AddEventHandler("catalog", "OnSuccessCatalogImport1C", array($this, 'onImportHandler'));
        AddEventHandler("iblock", "OnBeforeIBlockElementDelete", array($this, 'onDeleteHandler'));
        AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array($this, 'onUpdateHandler'));
    }

    private function setMinMaxPrice($id, $excludeId = false)
    {
        file_put_contents(__DIR__.'/testMinMaxPrice.txt', print_r($id.PHP_EOL, 1), 8);
        $ieObject = new CIBlockElement;
        $priceObject = new CPrice;
        $minPrice = array();
        $maxPrice = array();
        $fields = array();

        # дефолтная валюта
        $strDefaultCurrency = CCurrency::GetBaseCurrency();
        //file_put_contents(__DIR__.'/testMinMaxPrice.txt', print_r($strDefaultCurrency.PHP_EOL, 1), 8);
        # список id тп
        $offers = array();
        $res = $ieObject->GetList(
            array(
                'PROPERTY_SORT_FOR_SKU' => 'ASC',
                'ID' => 'ASC',
                'CATALOG_PRICE_1' => 'ASC',
            ),
            array(
                'IBLOCK_ID' => $this->_offersId,
                'ACTIVE' => 'Y',
                'PROPERTY_' . $this->_offersPropId => $id
            ),
            false,
            false,
            array(
                'ID', 'IBLOCK_ID', 'SORT', 'PROPERTY_SORT_FOR_SKU'
            )
        );
        /*while ($offer = $res->Fetch()) {
            if ($excludeId && $offer['ID'] == $excludeId) continue;
            $offers[] = $offer['ID'];
        }*/
        /*if ($offer = $res->Fetch()) {
            //if ($excludeId && $offer['ID'] == $excludeId) continue;
            $offers[] = $offer['ID'];
        }*/
        $i = 0;
        while ($offer = $res->Fetch()) {
            if(!empty($offer['PROPERTY_SORT_FOR_SKU_VALUE'])) {
                $offers = [];
                $offers[] = $offer['ID'];
                break;
            }
            if($i == 0) {
                $offers[] = $offer['ID'];
            }
            $i++;
        }


        //file_put_contents(__DIR__.'/testMinMaxPrice.txt', print_r($offers, 1), 8);
        if (!$offers) {
            # нет тп - ищем цены у самого товара
            $offers = $id;
        }

        $prices = $priceObject->GetList(array(), array(
            'PRODUCT_ID' => $offers,
            "CATALOG_GROUP_ID" => 1
        ));

        while ($price = $prices->Fetch()) {
            //file_put_contents(__DIR__.'/testMinMaxPrice.txt', print_r($price, 1), 8);
            if ($strDefaultCurrency != $price['CURRENCY']) {
                $price["PRICE"] = CCurrencyRates::ConvertCurrency($price["PRICE"], $price["CURRENCY"], $strDefaultCurrency);
            }
            if (!$minPrice[$price['CATALOG_GROUP_ID']] || $minPrice[$price['CATALOG_GROUP_ID']] > $price['PRICE']) {
                $minPrice[$price['CATALOG_GROUP_ID']] = $price['PRICE'];
            }

            if (!$maxPrice[$price['CATALOG_GROUP_ID']] || $maxPrice[$price['CATALOG_GROUP_ID']] < $price['PRICE']) {
                $maxPrice[$price['CATALOG_GROUP_ID']] = $price['PRICE'];
            }
        }

        foreach ($minPrice as $priceId => $minPriceItem) {
            if ($this->_onePrice) {
                # для одного типа цен (малый бизнес)
                $fields[$this->_minPriceProp] = $minPriceItem;
                $fields[$this->_maxPriceProp] = $maxPrice[$priceId];
            } else {
                # несколько типов цен (бизнес)
                $fields[$this->_minPriceProp . '_' . $priceId] = $minPriceItem;
                $fields[$this->_maxPriceProp . '_' . $priceId] = $maxPrice[$priceId];
            }
        }
        file_put_contents(__DIR__.'/testMinMaxPrice.txt', print_r($fields, 1), 8);
        $ieObject->SetPropertyValuesEx($id, $this->_iblockId, $fields);
    }

    public function onImportHandler()
    {
        $ieObject = new CIBlockElement;
        $res = $ieObject->GetList(false, array(
            'IBLOCK_ID' => $this->_iblockId
        ), false, false, array(
            'ID', 'IBLOCK_ID'
        ));
        while ($el = $res->Fetch()) {
            $this->setMinMaxPrice($el['ID']);
        }

        $cacheManager = \Bitrix\Main\Application::getInstance()->getManagedCache()->cleanAll();
    }

    public function onPriceUpdateHandler($arg1, $arg2 = false)
    {
        $ieObject = new CIBlockElement;

        if ($arg2["PRODUCT_ID"]) {
            $offer = $ieObject->GetList(array(), array(
                //'IBLOCK_ID' => $this->_offersId,
                'ID' => $arg2['PRODUCT_ID']
            ), false, false, array(
                'ID', 'IBLOCK_ID', 'PROPERTY_' . $this->_offersPropId
            ))->Fetch();

            if ($offer) {
                # обычный товар
                if ($offer['IBLOCK_ID'] == $this->_iblockId) {
                    $this->setMinMaxPrice($offer['ID']);
                } # тп
                elseif ($offer['IBLOCK_ID'] == $this->_offersId) {
                    $this->setMinMaxPrice($offer['PROPERTY_' . $this->_offersPropId . '_VALUE']);
                }
            }
        }
    }

    /**
     * Удаление тп, на обычный товар не реагируем
     * @param $id
     */
    public function onDeleteHandler($id)
    {
        $ieObject = new CIBlockElement;

        $offer = $ieObject->GetList(array(), array(
            'IBLOCK_ID' => $this->_offersId,
            'ID' => $id
        ), false, false, array(
            'ID', 'PROPERTY_' . $this->_offersPropId
        ))->Fetch();

        if ($offer && $offer['PROPERTY_' . $this->_offersPropId . '_VALUE']) {
            $this->setMinMaxPrice($offer['PROPERTY_' . $this->_offersPropId . '_VALUE'], $id);
        }
    }

    public function onUpdateHandler($fields)
    {
        if (!in_array($fields['IBLOCK_ID'], array($this->_offersId, $this->_iblockId))) return;
        if ($fields['IBLOCK_ID'] == $this->_iblockId) {
            # обычный товар
            $this->setMinMaxPrice($fields['ID']);
        } else {
            # тп
            $ieObject = new CIBlockElement;

            $offer = $ieObject->GetList(array(), array(
                'IBLOCK_ID' => $this->_offersId,
                'ID' => $fields['ID']
            ), false, false, array(
                'ID', 'PROPERTY_' . $this->_offersPropId
            ))->Fetch();

            if ($offer && $offer['PROPERTY_' . $this->_offersPropId . '_VALUE']) {
                $this->setMinMaxPrice($offer['PROPERTY_' . $this->_offersPropId . '_VALUE']);
            }
        }
    }
}

$setter = new MinMaxPriceSetter(IBLOCK_ID_CATALOG, IBLOCK_ID_OFFERS, 'CML2_LINK');
$setter->init();


/**
 * Хелпер для запоминания и получения просмотренных элементов
 */
class RecentHelper
{

    /**
     * @var int максимальное кол-во запоминаемых элементов
     */
    private $_max = false;
    private $_lifetime = false;

    /**
     * @var string название куки
     */
    private $_cookieName;

    /**
     * @param $max int максимальное кол-во запоминаемых элементов
     * @param $cookieName string название куки
     */
    public function __construct($cookieName, $max = false, $lifetime = false)
    {
        $this->_cookieName = $cookieName;
        $this->_max = $max;
        $this->_lifetime = $lifetime;
    }

    /**
     * Возвращает массив id просмотренных элементов из куки
     * @return array
     */
    public function get()
    {
        global $APPLICATION;
        $viewed = $APPLICATION->get_cookie($this->_cookieName);
        return explode('|', $viewed);
    }

    /**
     * Возвращает время удаление куки в timestamp или, false, если не задано
     * @return bool|int
     */
    private function getUntil()
    {
        if ($this->_lifetime) {
            return time() + $this->_lifetime;
        }
        return false;
    }

    /**
     * Добавляет id просмотренного элемента в куку
     * @param $id
     */
    public function add($id)
    {
        global $APPLICATION;
        $viewed = $this->get();
        if (!in_array($id, $viewed)) {
            array_unshift($viewed, $id);
            if ($this->_max) {
                $viewed = array_slice($viewed, 0, $this->_max);
            }
        }
        $APPLICATION->set_cookie($this->_cookieName, implode('|', $viewed), $this->getUntil());
    }
}

CModule::addAutoloadClasses(
    '',
    array(
        'CCatalogRU' => '/bitrix/php_interface/init/classes/CCatalogRU.php',
        'CBasketRU' => '/bitrix/php_interface/init/classes/CBasketRU.php',
        'CIBlockPriceToolsNewsite' => '/bitrix/php_interface/init/classes/CIBlockPriceToolsNewsite.php',
        'UrlUtils' => '/bitrix/php_interface/init/classes/UrlUtils.php',
        'HLHelpers' => '/bitrix/php_interface/init/classes/HLHelpers.php',
    )
);