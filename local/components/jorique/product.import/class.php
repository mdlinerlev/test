<?php

namespace Jorique\Components;

use Bitrix\Catalog\Model\Price;
use Exception;

class ProductImport extends \CBitrixComponent
{

    private $cache = array();
    private $iblockId;
    private $offersIblockId;
    private $colorsHlBlock;
    private $colorsGroupBlock;

    private $goodTypeMap = array(
        1 => TYPE_INTERIOR_DOORS, # межк.
        2 => TYPE_EXTERIOR_DOORS, # входные
        3 => TYPE_FINDINGS, # фурнитура
        4 => TYPE_FLOOR, # нап. покр.
        6 => TYPE_DEKOR, # декор.
    );

    private $planedTypeMap = array(
        1 => 'JAMB',
        2 => 'BOX',
        3 => 'TRANSOMS',
        4 => 'BAR'
    );

    private $currency = 'RUB';

    # сообщения об успешных действиях
    private $successes = array();

    # ошибки
    private $errors = array();

    # предупреждения
    private $warnings = array();

    #цены
    private $prices = array();

    private $IblockElements = array();
    private $IblockOffers = array();
    private $IdXmlId = array();

    public function __construct($component)
    {
        @ini_set('max_execution_time', 600);
        @set_time_limit(600);
        ignore_user_abort(true);

        $this->cache['bitrixObjects'] = array();
        $this->iblockId = IBLOCK_ID_CATALOG;
        $this->offersIblockId = IBLOCK_ID_OFFERS;
        $this->colorsHlBlock = HLBLOCK_ID_COLORS;
        $this->colorsGroupBlock = HLBLOCK_ID_COLOR_GROUPS;

        \CModule::IncludeModule('iblock');
        \CModule::IncludeModule('catalog');
        $this->initColorsHl();
        $this->initColorsGroup();

        parent::__construct($component);
    }

    private function initColorsHl()
    {
        \CModule::IncludeModule('highloadblock');
        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->colorsHlBlock)->fetch();
        \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    }

    private function initColorsGroup()
    {
        \CModule::IncludeModule('highloadblock');
        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->colorsGroupBlock)->fetch();
        \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    }


    private function getBitrixObject($className)
    {
        $className = '\\' . $className;
        if (isset($this->cache['bitrixObjects'][$className])) {
            return $this->cache['bitrixObjects'][$className];
        } else {
            $object = new $className;
            $this->cache['bitrixObjects'][$className] = $object;
            return $object;
        }
    }

    public function prepareParams()
    {
        $this->arParams['IS_CLI'] = $this->arParams['IS_CLI'] == 'Y';
        $this->arParams['XML_PATH'] = $_SERVER['DOCUMENT_ROOT'] . trim($this->arParams['XML_PATH']);
    }

    public function checkParams()
    {
        if ($this->arParams['IS_CLI']) {
            if (php_sapi_name() !== 'cli') {
                throw new Exception('Запуск импорта только из шелла');
            }
            if (!$this->arParams['XML_PATH']) {
                throw new Exception('Не указан путь до XML');
            }
            $this->checkXml($this->arParams['XML_PATH']);
        }
    }

    public function checkXml($path) {
        //$xmlPath = $_FILES[$name]['tmp_name'];
        # проверяем существования XML
        if (!file_exists($path)) {
            throw new Exception('Не загружен XML');
        }

        # проверяем xml на валидность
        $testvalid = new \DOMDocument();
        if (false === @$testvalid->load($path)) {
            throw new Exception('Некорректный XML');
        }
        $this->successes[] = 'XML файл корректный';
    }

    public function loadXml($path) {
        $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/import/';
        $uploadfile = $uploaddir . 'import.xml';
        if (move_uploaded_file($path, $uploadfile)) {
            $this->successes[] = 'XML файл загружен';
            return true;
        } else {
            $this->errors[] = 'не удалось загрузить XML файл';
            return false;
        }
    }

    public function startExec() {
        exec('php -f ' . $_SERVER["DOCUMENT_ROOT"] . '/import/import-cli.php >/dev/null 2>&1 &');
        $this->successes[] = 'Выгрузка запущена';
    }


    protected $iblockProps = array();

    protected function GetExitsPropsList($IBLOCK_ID)
    {
        if (isset($this->iblockProps[$IBLOCK_ID])) {
            return $this->iblockProps[$IBLOCK_ID];
        }

        $this->iblockProps[$IBLOCK_ID] = array();

        $obProperty = new \CIBlockProperty();
        $dbl = $obProperty->GetList(array(), array("IBLOCK_ID" => $IBLOCK_ID));
        while ($res = $dbl->Fetch()) {
            $code = !empty($res["XML_ID"]) ? $res["XML_ID"] : $res["CODE"];

            $this->iblockProps[$IBLOCK_ID][$code] = $res;
        }

        return $this->iblockProps[$IBLOCK_ID];
    }


    /**
     * Подготовка свойтва для добавления как и для свойтва так и для фильтра
     * @param type $arProperty
     * @return string
     */
    private function PreparePropertyFields($arProperty)
    {

        if (!array_key_exists("PROPERTY_TYPE", $arProperty))
            $arProperty["PROPERTY_TYPE"] = "S";

        if (!array_key_exists("CODE", $arProperty) || empty($arProperty["CODE"])) {
            $arProperty["CODE"] = \CUtil::translit(trim($arProperty["NAME"]), LANGUAGE_ID, array(
                "max_len" => 50,
                "change_case" => "U", // "L" - toLower, "U" - toUpper, false - do not change
                "replace_space" => "_",
                "replace_other" => "_",
                "delete_repeat_replace" => true,
            ));

            if (preg_match("/^[0-9]/", $arProperty["CODE"]))
                $arProperty["CODE"] = "_" . $arProperty["CODE"];

            $arProperty["CODE"] .= "_" . $arProperty["PROPERTY_TYPE"];

            if (!empty($arProperty["USER_TYPE"])) {
                $arProperty["CODE"] .= "_{$arProperty["USER_TYPE"]}";
            }

            $arProperty["CODE"] .= "_EXCHANGE";
        }

        return $arProperty;
    }

    /**
     * Проверяет свойтва на необходимость его создания
     */
    private function CheckIblockProperties($arProperty, $iblockId)
    {

        $this->GetExitsPropsList($iblockId);

        $obProperty = new \CIBlockProperty();

        $code = $arProperty["XML_ID"];
        if (!empty($this->iblockProps[$iblockId][$code])) {
            if ($this->iblockProps[$iblockId][$code]['USER_TYPE'] != 'directory' && $arProperty['PROPERTY_TYPE'] != $this->iblockProps[$iblockId][$code]['PROPERTY_TYPE']) {
                $obProperty->Update($this->iblockProps[$iblockId][$code]["ID"], ['PROPERTY_TYPE' => $arProperty['PROPERTY_TYPE']]);
                $this->iblockProps[$iblockId][$code]['PROPERTY_TYPE'] = $arProperty['PROPERTY_TYPE'];
            }
        } else {
            $this->iblockProps[$iblockId][$code] = $this->PreparePropertyFields($arProperty);
            $this->iblockProps[$iblockId][$code]['ID'] = $obProperty->Add($this->iblockProps[$iblockId][$code]);
        }

        return $this->iblockProps[$iblockId][$code];
    }

    private function getAllElements () {
        $elements = \Bitrix\Iblock\Elements\ElementItemsTable::getList([
            'select' => ['ID', 'NAME', 'DETAIL_PICTURE', 'XML_NEW', 'CODE', 'XML_ID', 'ACTIVE', 'ROOT_IMPORT'],
            'filter' => [
                'IBLOCK_ID' => $this->iblockId,
            ],
        ])->fetchCollection();

        foreach ($elements as $element) {
            $this->IblockElements[$element->getXmlId()] = [
                'ID' => $element->getId(),
                'NAME' => $element->getName(),
                'CODE' => $element->getCode(),
                'XML_ID' => $element->getXmlId(),
                'XML_NEW' => $element->getXmlNew()->getValue(),
                'ACTIVE' => $element->getActive(),
                'ROOT_IMPORT' => $element->getRootImport()->getValue(),
                'IBLOCK_ID' => $this->iblockId
            ];
            //$this->IdXmlId[$element->getId()] = $element->getXmlId();
        }
    }

    private function getAllOffers() {
        $elements = \Bitrix\Iblock\Elements\ElementOffersTable::getList([
            'select' => ['ID', 'NAME', 'DETAIL_PICTURE', 'XML_NEW', 'CODE', 'XML_ID', 'ACTIVE', 'ROOT_IMPORT'],
            'filter' => [
                'IBLOCK_ID' => $this->offersIblockId,
            ],
        ])->fetchCollection();

        foreach ($elements as $element) {
            $this->IblockOffers[$element->getXmlId()] = [
                'ID' => $element->getId(),
                'NAME' => $element->getName(),
                'CODE' => $element->getCode(),
                'XML_ID' => $element->getXmlId(),
                'XML_NEW' => $element->getXmlNew()->getValue(),
                'ACTIVE' => $element->getActive(),
                'ROOT_IMPORT' => $element->getRootImport()->getValue(),
                'IBLOCK_ID' => $this->offersIblockId
            ];
            //$this->IdXmlId[$element->getId()] = $element->getXmlId();
        }
        //return $resOffers;
    }

    private function getPrices()
    {
        $db_res = \CPrice::GetList([], []);
        while ($ar_res = $db_res->Fetch()) {
            $this->IdXmlId[$ar_res['PRODUCT_ID']][$ar_res['CATALOG_GROUP_ID']] = [
                'PRICE' => $ar_res['PRICE'],
                'ID' => $ar_res['ID'],
            ];
        }
    }

    private function getEnumVariantsId()
    {
        $arrProId = [66,SIZE_DOOR,WIDTH_PANEL,86];
        $arVariants = array();
        foreach ($arrProId as $propId) {
            $rsVariants = \CIBlockProperty::GetPropertyEnum($propId);
            if ($rsVariants->SelectedRowsCount()) {
                while ($arVariant = $rsVariants->Fetch()) {
                    $arVariants[$arVariant['VALUE']] = $arVariant['ID'];
                }
            }
            $this->cache['prop-' . $propId] = $arVariants;
        }
    }

    public function importPrices($prices)
    {
        //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/import.txt', print_r($prices, 1));

        $arDbPrices = [];
        $iterator = \Bitrix\Catalog\GroupLangTable::getList([
            'select' => ['*'],
            'filter' => ['LANG' => 'ru', '!NAME' => false]
        ]);
        while ($arPrice = $iterator->fetch()) {
            $arDbPrices[$arPrice['NAME']] = $arPrice['CATALOG_GROUP_ID'];
        }

        foreach ($prices as $price) {
            $name = (string)$price->name;
            if (!isset($arDbPrices[$name])) {
                $arFields = [
                    "NAME" => $name,
                    "SORT" => 100,
                    "USER_GROUP" => [B2B_GROUP],
                    "USER_GROUP_BUY" => [B2B_GROUP],
                    "USER_LANG" => [
                        "ru" => $name,
                        "en" => \CUtil::translit($name, 'ru')
                    ]
                ];
                $id = \CCatalogGroup::Add($arFields);
                $arDbPrices[$name] = $id;
            }
        }

        $this->prices = $arDbPrices;
    }

    public function import($path)
    {
        $xml = simplexml_load_file($path);
        $root = (array)$xml;
        $base = $root['@attributes']['base'];

        $prices = $xml->xpath('//prices/prices');
        self::importPrices($prices);

        $goods = $xml->xpath('//products/product');
        if (!sizeof($goods)) {
            throw new Exception('В файле выгрузки не найдены товары');
        }

        $this->getAllElements();
        $this->getAllOffers();
        $this->getPrices();
        $this->getEnumVariantsId();

        /** @var \CIBlockElement $ieObject */
        $ieObject = $this->getBitrixObject('CIBlockElement');

        /*
         * Уникальный ид товара - XML_ID. Проверяем на сайте - если товар с текущим XML_ID есть,
         * обновляем его свойства. Иначе добавляем товар. Всем товарам изначально проставляем 0 в свойство XML_NEW,
         * затем каждому обновлённому или добавленному товару проставляем 1. В конце выгрузки все товары, у которых XML_NEW=0
         * деактивируем (или удаляем) - этих товаров нет в файле выгрузки.
         */

        $added = $updated = $deleted = 0;


        $offersXml = [];

        # проставляем FROM_XML_TMP=0 у всех товаров и тп
        /*$rsElems = $ieObject->GetList(array(), array(
            'IBLOCK_ID' => $this->iblockId,
            'ACTIVE' => 'Y',
            'PROPERTY_XML_NEW' => 1,
            '=PROPERTY_ROOT_IMPORT' => $base,
        ), false, false, array(
            'ID', 'IBLOCK_ID', 'XML_ID'
        ));
        if ($rsElems->SelectedRowsCount()) {
            while ($arElem = $rsElems->Fetch()) {
                $offersXml[$arElem['XML_ID']] = $arElem['ID'];
                $ieObject->SetPropertyValuesEx(
                    $arElem['ID'],
                    $arElem['IBLOCK_ID'],
                    array(
                        'XML_NEW' => 0,
                    )
                );
            }
        }*/

        foreach ($this->IblockElements as $key => $elem) {
            if($elem['ACTIVE'] == 1 && $elem['XML_NEW'] && $elem['ROOT_IMPORT'] == $base) {
                $offersXml[$elem['XML_ID']] = $elem['ID'];
                $ieObject->SetPropertyValuesEx(
                    $elem['ID'],
                    $elem['IBLOCK_ID'],
                    array(
                        'XML_NEW' => 0,
                    )
                );
                $this->IblockElements[$key]['XML_NEW'] = 0;
            }
        }

        foreach ($this->IblockOffers as $key => $elem) {
            if($elem['ACTIVE'] == 1 && $elem['XML_NEW'] && $elem['ROOT_IMPORT'] == $base) {
                $offersXml[$elem['XML_ID']] = $elem['ID'];
                $ieObject->SetPropertyValuesEx(
                    $elem['ID'],
                    $elem['IBLOCK_ID'],
                    array(
                        'XML_NEW' => 0,
                    )
                );
                $this->IblockOffers[$key]['XML_NEW'] = 0;
            }
        }

        /*$rsElems = $ieObject->GetList(array(), array(
            'IBLOCK_ID' => $this->offersIblockId,
            'ACTIVE' => 'Y',
            'PROPERTY_XML_NEW' => 1,
            '=PROPERTY_ROOT_IMPORT' => $base,
        ), false, false, array(
            'ID', 'IBLOCK_ID', 'XML_ID'
        ));
        if ($rsElems->SelectedRowsCount()) {
            while ($arElem = $rsElems->Fetch()) {
                $offersXml[$arElem['XML_ID']] = $arElem['ID'];
                $ieObject->SetPropertyValuesEx(
                    $arElem['ID'],
                    $arElem['IBLOCK_ID'],
                    array(
                        'XML_NEW' => 0,
                    )
                );
            }
        }*/



        $arPropertyListCache = array();

        $sectionExists = \CIBlockSection::GetList(false, array(
            'IBLOCK_ID' => $this->iblockId,
        ), false, array(
            'ID', 'IBLOCK_ID'
        ));

        while ($arSect = $sectionExists->GetNext()) {
            $arrSections[] = $arSect['ID'];
        }

        foreach ($goods as $good) {
            //file_put_contents(__DIR__.'/import'.date('d.m.Y_H.i.s').'.log', print_r($good->id.PHP_OEL), FILE_APPEND);
            $pri = floatval($good->price);
            $ol = floatval($good->{'old-price'});
            if ($pri > $ol) {
                $ol = 0;
            } elseif ($pri <= 0) {
                $ol = 0;
            }

            $offers = $good->offers->offer;
            /*echo sizeof($offers).' ';
            continue;*/
            $props = array(
                'name' => $good->name,
                'preview_text' => $good->priview_text,
                'ref_id' => $good->reference_id,
                'configuration' => $good->configuration,
                'is_glass' => $good->glass,
                'type' => $good['type'],
                'xml_id' => $good->id,
                'section_id' => $good->section_id,
                'qty' => $good->quantity,
                'price' => (array)$good->prices,
                //'old-price' => $ol,
                'article' => $good->article,
                'square' => $good->square
            );
            if ($props['square']) {
                $props['square'] = (float)str_replace(',', '.', $props['square']);
                //$props['price'] *= $props['square'];
            }
            $props = array_map(array($this, 'prepareXmlProp'), $props);

            if ($props['section_id']) {
                /*$sectionExists = \CIBlockSection::GetList(false, array(
                    'IBLOCK_ID' => $this->iblockId,
                    'ID' => $props['section_id']
                ), false, array(
                    'ID', 'IBLOCK_ID'
                ))->SelectedRowsCount();*/

                if (/*!$sectionExists*/!in_array($props['section_id'], $arrSections)) {
                    $this->errors[] = 'Не найден раздел с id ' . $props['section_id'];
                    continue;
                }
            }

            if (!$props['xml_id']) {
                $this->errors[] = 'У товара нет XML_ID';
                continue;
            }

            $fields = array(
                'XML_ID' => $props['xml_id'],
                'NAME' => $props['name'],
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
                'IBLOCK_SECTION_ID' => $props['section_id'] ?: false,
            );
            if (!empty($props['preview_text'])) {
                $fields['PREVIEW_TEXT'] = $props['preview_text'];
            }
            $properties = array(
                'XML_NEW' => 1,
                'ROOT_IMPORT' => $base,
                'PRODUCT_TYPE' => $this->getGoodType($props['type']),
                'GLASS' => $props['is_glass'] ? 1 : false,
                'CONFIGURATION' => $props['configuration'],
                'GLASS_REF' => $props['ref_id'],
                'OLD_PRICE' => $props['old-price'],
                'ARTICLE' => $props['article'],
                'BOX_SQUARE' => str_replace(',', '.', $props['square'])
            );


            if (!empty($good->properties)) {

                $propertiesXml = simplexml_load_string($good->properties->asXML());
                $propertiesXml = $propertiesXml->xpath('//properties/property');

                foreach ($propertiesXml as $field) {
                    $field = (array)$field;
                    $propsAttr = $field['@attributes'];

                    $arPropertyValue = trim($field[0]);

                    if (empty($propsAttr['guid']) || empty($arPropertyValue))
                        continue;

                    $arProperty = [
                        "IBLOCK_ID" => $this->iblockId,
                        "ACTIVE" => "Y",
                        "XML_ID" => $propsAttr['guid'],
                        "NAME" => $propsAttr['name'],
                        "PROPERTY_TYPE" => "S"
                    ];


                    if ($propsAttr['type'] == 'Справочник') {
                        $arProperty["PROPERTY_TYPE"] = "L";
                    } elseif ($propsAttr['type'] == 'Число') {
                        $arProperty["PROPERTY_TYPE"] = "N";
                    } elseif ($propsAttr['type'] == 'Булево') {
                        $arProperty["PROPERTY_TYPE"] = "L";
                        $arProperty["LIST_TYPE"] = "C";
                    }


                    $arProperty = $this->CheckIblockProperties($arProperty, $this->iblockId);
                    $idProp = $arProperty['ID'];
                    if (!empty($idProp)) {
                        switch ($arProperty["PROPERTY_TYPE"]) {
                            case "L":
                                $propValueHash = md5('belwood' . mb_strtolower($arPropertyValue));
                                if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash])) {
                                    $arPropertyListCache[$arProperty['ID']] = [];
                                    $propEnumRes = \CIBlockPropertyEnum::GetList([], ['IBLOCK_ID' => $this->iblockId, 'PROPERTY_ID' => $arProperty['ID']]);
                                    while ($propEnumValue = $propEnumRes->Fetch())
                                        $arPropertyListCache[$arProperty['ID']][$propEnumValue['XML_ID'] ?: md5('belwood' . mb_strtolower($propEnumValue['VALUE']))] = $propEnumValue['ID'];

                                }

                                if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash]))
                                    $arPropertyListCache[$arProperty['ID']][$propValueHash] = \CIBlockPropertyEnum::Add(["PROPERTY_ID" => $arProperty['ID'], "XML_ID" => $propValueHash, "VALUE" => $arPropertyValue]);


                                if (isset($arPropertyListCache[$arProperty['ID']][$propValueHash]))
                                    $properties[$idProp] = $arPropertyListCache[$arProperty['ID']][$propValueHash];
                                else
                                    $properties[$idProp] = '';

                                break;
                            case "N":
                                $properties[$idProp] = str_replace(',', '.', $arPropertyValue);
                                break;
                            case "S":
                                if ($arProperty['USER_TYPE'] == 'directory') {

                                    if (\CModule::IncludeModule('highloadblock')) {

                                        $propValueHash = md5('belwood' . mb_strtolower($arPropertyValue));
                                        if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash])) {
                                            $arPropertyListCache[$arProperty['ID']] = [];

                                            $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['TABLE_NAME' => $arProperty['USER_TYPE_SETTINGS']['TABLE_NAME']]])->fetch();
                                            $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                                            $hlDataClass = $entity->getDataClass();
                                            $res = $hlDataClass::getList(['filter' => [], 'select' => ["XML_ID" => "UF_XML_ID", "ID", "VALUE" => "UF_NAME"]]);
                                            while ($propEnumValue = $res->fetch()) {
                                                $xmlID = $propEnumValue['XML_ID'];
                                                if (strlen($xmlID) < 15) {
                                                    $xmlID = false;
                                                }
                                                $arPropertyListCache[$arProperty['ID']][$xmlID ?: md5('belwood' . mb_strtolower($propEnumValue['VALUE']))] = $propEnumValue['XML_ID'];
                                            }

                                            if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash])) {
                                                if ($hlDataClass::add(["UF_XML_ID" => $propValueHash, "UF_NAME" => $arPropertyValue])) {
                                                    $arPropertyListCache[$arProperty['ID']][$propValueHash] = $propValueHash;
                                                }
                                            }
                                        }

                                        if (isset($arPropertyListCache[$arProperty['ID']][$propValueHash]))
                                            $properties[$idProp] = $arPropertyListCache[$arProperty['ID']][$propValueHash];
                                        else
                                            $properties[$idProp] = '';

                                    }

                                    break;
                                }
                            default:

                                $properties[$idProp] = $arPropertyValue;

                        }
                    }
                }
            }

            # проверяем, есть ли товар с таким XML_ID
            //$el = $ieObject->GetList(array(), array('IBLOCK_ID' => $this->iblockId, '=XML_ID' => $props['xml_id']));
            if (/*$el->SelectedRowsCount()*/isset($this->IblockElements[$props['xml_id']])) {
                # апдейтим
                //$el = $el->Fetch();
                $el = $this->IblockElements[$props['xml_id']];

                $prId = $el['ID'];
                $fields['CODE'] = $this->getCode($props['name'], $prId);
                unset($fields['IBLOCK_SECTION_ID']);
                if ($ieObject->Update($el['ID'], $fields)) {
                    \CIBlockElement::SetPropertyValuesEx($el['ID'], $this->iblockId, $properties);
                    $updated++;
                    $this->IblockElements[$props['xml_id']]['XML_NEW'] = 1;
                    $this->IblockElements[$props['xml_id']]['CODE'] = $fields['CODE'];
                } else {
                    $this->errors[] = 'Ошибка при обновлении товара, XML_ID ' . $props['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                }
            } else {
                # добавляем
                $fields['CODE'] = $this->getCode($props['name']);
                if ($prId = $ieObject->Add(array_merge($fields, array('PROPERTY_VALUES' => $properties)))) {
                    $added++;
                    $this->IblockElements[$props['xml_id']] = [
                        'ID' => $prId,
                        'NAME' => $fields['NAME'],
                        'CODE' => $fields['CODE'],
                        'XML_ID' => $fields['XML_ID'],
                        'XML_NEW' => 1,
                        'ACTIVE' => 1,
                        'ROOT_IMPORT' => $base,
                        'IBLOCK_ID' => $this->iblockId,
                    ];
                } else {
                    $this->errors[] = 'Ошибка при добавлении товара, XML_ID ' . $props['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                }
            }

            if ($prId) {
                $offersXml[$fields['XML_ID']] = $prId;
            }
            # цена и количество
            if ($prId && !sizeof((array)$offers)) {
                //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/local/logs/items.txt', print_r($props,1), FILE_APPEND);
                $this->setQuantity($prId, $props['qty']);
                $this->setPrice($prId, $good->prices);
            }
            if ($prId && sizeof((array)$offers)) {
                foreach ($offers as $offer) {
                    if($good->id == 'c7e2de6b-77ae-11ed-92c3-000c295257b20' || $good->id == '527d435d-2448-11ed-8f71-005056bb745a0') {
                        $specArr[] = $offer->id;
                    }
                    $pri = floatval($offer->price);
                    $ol = floatval($offer->{'old-price'});
                    if ($pri > $ol) {
                        $ol = 0;
                    } elseif ($pri <= 0) {
                        $ol = 0;
                    }

                    $offerProps = array(
                        'name' => $offer->name,
                        'xml_id' => $offer->id,
                        'price' => (array)$offer->prices,
                        'old-price' => $ol,
                        'qty' => $offer->quantity,
                        'color' => $offer->color,
                        'hardware_color' => $offer->entrance_door_hardware_color,
                        'size' => $offer->size,
                        'size-door' => $offer->{'size-door'},
                        'width-panel' => $offer->{'width-panel'},
                        'article' => $offer->article,
                        'glass-color' => $offer->{'glass-color'},
                        'open-side' => $offer->{'open-side'},
                        'color-inner' => $offer->{'inner-color'},
                        'color-outer' => $offer->{'outer-color'},
                    );

                    $offerProps = array_map(array($this, 'prepareXmlProp'), $offerProps);
                    if (!$offerProps['xml_id']) {
                        $this->errors[] = 'У торгового предложения нет XML_ID';
                        continue;
                    }

                    $fields = array(
                        'XML_ID' => $offerProps['xml_id'],
                        'NAME' => $offerProps['name'],
                        'IBLOCK_ID' => $this->offersIblockId,
                        'ACTIVE' => 'Y'
                    );
                    $properties = array(
                        'XML_NEW' => 1,
                        'CML2_LINK' => $prId,
                        'OLD_PRICE' => $offerProps['old-price'],
                        'ARTICLE' => $offerProps['article'],
                        'ROOT_IMPORT' => $base,
                        'slab_thickness' => $offer->{'slab_thickness'},
                        'GLASS_COLOR' => $offer->{'GLASS_COLOR'},
                        'razmer_v_ypalovke' => $offer->{'razmer_v_ypalovke'},
                        'ves_btutto' => $offer->{'ves_btutto'}

                    );
                    if ($offerProps['size']) {
                        $properties['SIZE'] = $this->getEnumVariantId(66, $offerProps['size']);
                    }
                    if($offerProps['size-door']) {
                        $properties['SIZE_DOOR'] = $this->getEnumVariantId(SIZE_DOOR, $offerProps['size-door']);
                    }
                    if($offerProps['width-panel']) {
                        $properties['WIDTH_PANEL'] = $this->getEnumVariantId(WIDTH_PANEL, $offerProps['width-panel']);
                    }
                    if ($offerProps['color']) {
                        $properties['COLOR'] = $this->getColorIdByName($offerProps['color']);
                        $properties['GROUP_COLOR'] = $this->getColorGroupIdByName($properties['COLOR']);
                    }
                    if($offerProps['hardware_color']) {
                        $properties['HARDWARE_COLOR'] = $this->getColorIdByName($offerProps['hardware_color']);
                    }
                    if ($offerProps['glass-color']) {
                        $properties['GLASS_COLOR'] = $this->getColorIdByName($offerProps['glass-color']);
                    }
                    if ($offerProps['color-inner']) {
                        $properties['COLOR_IN'] = $this->getColorIdByName($offerProps['color-inner']);
                    }
                    if ($offerProps['color-outer']) {
                        $properties['COLOR_OUT'] = $this->getColorIdByName($offerProps['color-outer']);
                    }
                    if ($offerProps['open-side']) {
                        $properties['SIDE'] = $this->getEnumVariantId(86, $offerProps['open-side'] == 'LHO/RHI' ? 'Левая' : 'Правая');
                    }

                    # проверяем, есть ли оффер с таким XML_ID
                    //$el = $ieObject->GetList(array(), array('IBLOCK_ID' => $this->offersIblockId, '=XML_ID' => $offerProps['xml_id']));


                    if (/*$el->SelectedRowsCount()*/isset($this->IblockOffers[$offerProps['xml_id']])) {
                        # апдейтим
                        //$el = $el->Fetch();
                        $el = $this->IblockOffers[$offerProps['xml_id']];
                        $ofId = $el['ID'];
                        if ($ieObject->Update($el['ID'], $fields)) {
                            \CIBlockElement::SetPropertyValuesEx($el['ID'], $this->offersIblockId, $properties);
                            $updated++;
                            $this->IblockOffers[$offerProps['xml_id']]['XML_NEW'] = 1;
                            $this->IblockOffers[$offerProps['xml_id']]['CODE'] = $fields['CODE'];
                            $this->IblockOffers[$offerProps['xml_id']]['ROOT_IMPORT'] = $properties['ROOT_IMPORT'];
                        } else {
                            $this->errors[] = 'Ошибка при обновлении оффера, XML_ID ' . $offerProps['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                        }
                    } else {
                        # добавляем
                        if ($ofId = $ieObject->Add(array_merge($fields, array('PROPERTY_VALUES' => $properties)))) {
                            $added++;
                            $this->IblockOffers[$props['xml_id']] = [
                                'ID' => $ofId,
                                'NAME' => $fields['NAME'],
                                'CODE' => $fields['CODE'],
                                'XML_ID' => $offerProps['xml_id'],
                                'XML_NEW' => 1,
                                'ACTIVE' => 1,
                                'ROOT_IMPORT' => $base,
                                'IBLOCK_ID' => $this->offersIblockId,
                            ];
                        } else {
                            $this->errors[] = 'Ошибка при добавлении оффера, XML_ID ' . $offerProps['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                        }
                    }
                    # цена и количество
                    if ($ofId) {
                        $offersXml[$fields['XML_ID']] = $ofId;

                        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/local/logs/offers.txt', print_r($offer,1), FILE_APPEND);
                        $this->setQuantity($ofId, $offerProps['qty']);
                        $this->setPrice($ofId, $offer->prices);
                    }
                }
            }
        }

        # теперь привяжем погонаж к дверям
        foreach ($goods as $good) {


            $planed['ID'] = $offersXml[trim((string)$good->id)];


            $offers = $good->references->offers;


            if (empty($planed['ID'])) {
                $this->errors[] = 'По каким-то причинам в каталоге не найден погонаж ' . trim((string)$good->id);
                continue;
            } elseif (!sizeof((array)$offers)) {
                if (\Bitrix\Main\Loader::includeModule("sh.dblayer")) {
                    $instance = \Sh\DBLayer\CTables::getOrmTableInstance('frame_type');
                    $instance->deleteFromGetList(["=ELEMENT_ID" => $planed['ID']]);
                }
                continue;
            }


            $type = trim((string)$good['type']);
            $planedTypeSpec = trim((string)$good['planed-type']);
            if ($type != 5) {
                continue;
            }


            $frameType = [];
            foreach ($offers as $item) {

                $xmlId = (string)$item->offer_id;

                if (!$xmlId) {
                    $this->errors[] = 'Пустой offer_id у погонажа';
                    continue;
                }

                if (empty($frameType[$xmlId])) {
                    $frameType[$xmlId] = [];
                }
                foreach ($item->{'frame-type'} as $frame) {
                    foreach ($frame as $type => $count) {
                        $type = (string)$type;
                        $type = str_replace('frame-type-', '', $type);

                        $count = (int)$count;
                        if(in_array($xmlId, $specArr)) {
                            if($planedTypeSpec == 5)
                                $frameType[$xmlId]['Space1'] = (int)$count;
                            $type = 'Space2';
                        }
                        //if ($count <= 0) continue;

                        //if (!empty($frameType[$xmlId][$type]) && intval($frameType[$xmlId][$type]) <= $count)
                        //    continue;

                        $frameType[$xmlId][$type] = (int)$count;
                    }
                }
            }

            $offersIds = array_keys($frameType);
            file_put_contents(__DIR__.'/frameType.txt', print_r($frameType, 1), 8);
            file_put_contents(__DIR__.'/offersIds.txt', print_r($offersIds, 1), 8);
            foreach ($offersIds as $xml) {

                if (!empty($offersXml[$xml])) {

                    if (!empty($frameType)) {
                        foreach ($frameType[$xml] as $type => $count) {
                            $tmp = [
                                'ELEMENT_ID' => $planed['ID'],
                                'LINK_TYPE' => $type,
                                'COUNT' => $count,
                                'ELEMENT_ID_LINK' => $offersXml[$xml],
                            ];
                            $hash = md5($tmp['ELEMENT_ID'] . $tmp['LINK_TYPE'] . $tmp['COUNT'] . $tmp['ELEMENT_ID_LINK']);
                            $dataFrame[$hash] = $tmp;
                        }
                    }

                    unset($frameType[$xml]);

                }

            }

            if (\Bitrix\Main\Loader::includeModule("sh.dblayer")) {

                $res = \Sh\DBLayer\CTables::getOrmQuery("frame_type")
                    ->setSelect(["*"])
                    ->setFilter(["=ELEMENT_ID" => $planed['ID']])
                    ->exec();

                $deleteIds = [];
                while ($tmp = $res->fetch()) {
                    $hash = md5($tmp['ELEMENT_ID'] . $tmp['LINK_TYPE'] . $tmp['COUNT'] . $tmp['ELEMENT_ID_LINK']);
                    if (!empty($dataFrame[$hash])) {
                        unset($dataFrame[$hash]);
                    } else {
                        $deleteIds[] = $tmp['ID'];
                    }
                }

                if (!empty($deleteIds)) {
                    $instance = \Sh\DBLayer\CTables::getOrmTableInstance('frame_type');
                    $instance->deleteFromGetList(["=ID" => $deleteIds]);
                }
                if (!empty($dataFrame)) {
                    $instance = \Sh\DBLayer\CTables::getOrmTableInstance('frame_type');
                    foreach ($dataFrame as $item) {
                        $instance->add($item);
                    }
                }
            }

        }

        # деактивируем товары, у которых XML_NEW=0
        $behavior = \COption::GetOptionString("grain.customsettings", "missing_behavior");
        if ($behavior == 'deact' || $behavior == 'remove') {
            /*$rsElems = $ieObject->GetList(array(), array(
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
                '!PROPERTY_XML_NEW' => 1,
                '=PROPERTY_ROOT_IMPORT' => $base,
            ));*/
            if (/*$rsElems->SelectedRowsCount()*/count($this->IblockElements) > 0) {
                /*while ($arElem = $rsElems->Fetch()) {
                    if ($behavior == 'remove') {
                        $delRes = $ieObject->Delete($arElem['ID']);
                    } else {
                        $delRes = $ieObject->Update($arElem['ID'], array('ACTIVE' => 'N'));
                    }
                    if ($delRes) {
                        $deleted++;
                    } else {
                        $this->errors[] = 'Ошибка при деактивации товара, id ' . $arElem['ID'] . ': ' . $ieObject->LAST_ERROR;
                    }
                }*/
                foreach ($this->IblockElements as $elemDelOrUp) {
                    if($elemDelOrUp['XML_NEW'] != 1 && $elemDelOrUp['ACTIVE'] == 1) {
                        if ($behavior == 'remove') {
                            $delRes = $ieObject->Delete($elemDelOrUp['ID']);
                        } else {
                            $delRes = $ieObject->Update($elemDelOrUp['ID'], array('ACTIVE' => 'N'));
                        }
                        if ($delRes) {
                            $deleted++;
                        } else {
                            $this->errors[] = 'Ошибка при деактивации товара, id ' . $elemDelOrUp['ID'] . ': ' . $ieObject->LAST_ERROR;
                        }
                    }

                }
            }

            # деактивируем офферы, у которых XML_NEW=0
            /*$rsElems = $ieObject->GetList(array(), array(
                'IBLOCK_ID' => $this->offersIblockId,
                'ACTIVE' => 'Y',
                '!PROPERTY_XML_NEW' => 1,
                '=PROPERTY_ROOT_IMPORT' => $base,
            ));*/
            if (/*$rsElems->SelectedRowsCount()*/count($this->IblockOffers) > 0) {
                /*while ($arElem = $rsElems->Fetch()) {
                    if ($behavior == 'remove') {
                        $delRes = $ieObject->Delete($arElem['ID']);
                    } else {
                        $delRes = $ieObject->Update($arElem['ID'], array('ACTIVE' => 'N'));
                    }
                    if ($delRes) {
                        $deleted++;
                    } else {
                        $this->errors[] = 'Ошибка при деактивации оффера, id ' . $arElem['ID'] . ': ' . $ieObject->LAST_ERROR;
                    }
                }*/
                foreach ($this->IblockOffers as $key => $elemDelOrUp) {
                    if($elemDelOrUp['XML_NEW'] != 1 && ($elemDelOrUp['ACTIVE'] == 1 || $elemDelOrUp['ACTIVE'] == 'Y') && $elemDelOrUp['ROOT_IMPORT'] == $base) {
                        if ($behavior == 'remove') {
                            $delRes = $ieObject->Delete($elemDelOrUp['ID']);
                        } else {
                            $delRes = $ieObject->Update($elemDelOrUp['ID'], array('ACTIVE' => 'N'));
                        }
                        if ($delRes) {
                            $deleted++;
                        } else {
                            $this->errors[] = 'Ошибка при деактивации товара, id ' . $elemDelOrUp['ID'] . ': ' . $ieObject->LAST_ERROR;
                        }
                    }
                }
            }
        }

        foreach (GetModuleEvents("catalog", "OnSuccessCatalogImport1C", true) as $arEvent) {
            ExecuteModuleEventEx($arEvent, array(array(), ''));
        }

        $this->successes[] = 'Товары: добавлено - ' . $added . ', обновлено - ' . $updated . ', деактивировано - ' . $deleted;
    }

    private function getColorIdByName($colorName)
    {
        /** @var \Bitrix\Main\DB\Result $color */
        $color = \ColorReferenceTable::getList(array(
            'filter' => array(
                '=UF_NAME' => $colorName
            )
        ));
        if ($color->getSelectedRowsCount()) {
            $color = $color->fetch();
            return $color['UF_XML_ID'];
        } else {
            $xmlId = randString();
            /** @var \Bitrix\Main\Entity\AddResult $addRes */
            $addRes = \ColorReferenceTable::add(array(
                'UF_XML_ID' => $xmlId,
                'UF_NAME' => $colorName
            ));
            if ($addRes->isSuccess()) {
                return $xmlId;
            }
        }
        return false;
    }

    private function getColorGroupIdByName($xmlId)
    {
        $color = \ColorReferenceTable::getList(array(
            'filter' => array(
                '=UF_XML_ID' => $xmlId
            )
        ));
        if ($color->getSelectedRowsCount()) {
            $color = $color->fetch();
            if (!empty($color['UF_GROUP'])) {
                $color = \ColorGroupTable::getList(array(
                    'filter' => array(
                        '=ID' => $color['UF_GROUP']
                    )
                ));
                $color = $color->fetch();
                return $color['UF_XML_ID'];
            }
        }
        return false;
    }


    /**
     * Возвращает ид варианта свойства инфоблока по его value
     * @param $propId
     * @param $value
     * @return mixed
     */
    private function getEnumVariantId($propId, $value)
    {
        if (isset($this->cache['prop-' . $propId])) {
            $arVariants = $this->cache['prop-' . $propId];
        }/* else {
            $arVariants = array();
            $rsVariants = \CIBlockProperty::GetPropertyEnum($propId);
            if ($rsVariants->SelectedRowsCount()) {
                while ($arVariant = $rsVariants->Fetch()) {
                    $arVariants[$arVariant['VALUE']] = $arVariant['ID'];
                }
            }
            $this->cache['prop-' . $propId] = $arVariants;
        }*/
        if (array_key_exists($value, $arVariants)) {
            return $arVariants[$value];
        } else {
            unset($this->cache['prop-' . $propId]);
            \CIBlockPropertyEnum::Add(array(
                'PROPERTY_ID' => $propId,
                'VALUE' => $value
            ));
            return $this->getEnumVariantId($propId, $value);
        }
    }

    private function getCode($name, $id = false)
    {
        $inc = '';
        $translit = \CUtil::translit($name, 'ru', array(
            'replace_space' => '-',
            'replace_other' => '-'
        ));
        $filter = array(
            'IBLOCK_ID' => $this->iblockId
        );
        if ($id) {
            $filter['!=ID'] = $id;
        }
        while (true) {
            $code = $translit . $inc;
            //$filter['=CODE'] = $code;
            # ищем такой элемент

            //$res = \CIBlockElement::GetList(false, $filter, false, array('nTopCount' => 1), array('ID', 'IBLOCK_ID'));
            $key = false;
            foreach ($this->IblockElements as $value) {
                if($value['CODE'] == $code) {
                    $key = true;
                }
            }
            foreach ($this->IblockOffers as $value) {
                if($value['CODE'] == $code) {
                    $key = true;
                }
            }
            //$key = array_search($code, array_column($this->IblockElements, 'CODE'));
            if (/*!$res->SelectedRowsCount()*/!$key) {
                return $code;
            }
            $inc++;
        }
        return 'ololo'; # impossibru
    }



    private function setPrice($id, $prices)
    {
        //file_put_contents($_SERVER['DOCUMENT_ROOT'].'/local/logs/prices.txt', print_r($prices,1), FILE_APPEND);
        if ($prices) {
            //\CPrice::DeleteByProduct($id);
            /*$db_res = \CPrice::GetList([], ["PRODUCT_ID" => $id]);
            while ($ar_res = $db_res->Fetch()) {
                $arPrice[$ar_res['CATALOG_GROUP_ID']] = [
                    'PRICE' => $ar_res['PRICE'],
                    'ID' => $ar_res['ID'],
                ];
            }*/
            foreach ($prices->price as $price){
                $priceVal = (string)$price->value;
                $guid = (string)$price->guid;
                $priceId = $this->prices[$guid];
                //file_put_contents(__DIR__.'/testPrice.txt', print_r($this->IdXmlId[$id][$priceId], 1), 8);
                if(isset($this->IdXmlId[$id][$priceId])) {
                    if(floatval($priceVal) != $this->IdXmlId[$id][$priceId]['PRICE']) {
                        Price::update(
                            $this->IdXmlId[$id][$priceId]['ID'],
                            [
                                "PRODUCT_ID" => $id,
                                "CATALOG_GROUP_ID" => $priceId,
                                "PRICE" => floatval($priceVal),
                                "CURRENCY" => $this->currency,
                            ]
                        );
                        $this->IdXmlId[$id][$priceId]['PRICE'] = floatval($priceVal);
                    }
                } else {
                    Price::add([
                        "PRODUCT_ID" => $id,
                        "CATALOG_GROUP_ID" => $priceId,
                        "PRICE" => floatval($priceVal),
                        "CURRENCY" => $this->currency,
                    ]);
                    $this->IdXmlId[$id][$priceId]['PRICE'] = floatval($priceVal);
                }
                /*if(isset($this->prices[$guid])){

                    Price::add([
                        "PRODUCT_ID" => $id,
                        "CATALOG_GROUP_ID" => $priceId,
                        "PRICE" => floatval($priceVal),
                        "CURRENCY" => $this->currency,
                    ]);
                }*/
            }
            /** @var \CPrice $priceObject */
            /*$priceObject = $this->getBitrixObject('CPrice');
            $priceObject->SetBasePrice($id, $price, $this->currency);*/
        }
    }

    private function setQuantity($id, $quantity)
    {
        $quantity = (int)$quantity;
        if ($prodInfo = \CCatalogProduct::GetByID($id)) {
            if($prodInfo['QUANTITY'] != $quantity)
                \CCatalogProduct::Update($id, array('QUANTITY' => $quantity));
        } else {
            \CCatalogProduct::Add(array('ID' => $id, 'QUANTITY' => $quantity));
        }

    }

    private function getGoodType($xmlTypeId)
    {
        if (isset($this->goodTypeMap[$xmlTypeId])) {
            return $this->goodTypeMap[$xmlTypeId];
        }
        return false;
    }

    private function getPlanedTypeProp($planedTypeId)
    {
        if (isset($this->planedTypeMap[$planedTypeId])) {
            return $this->planedTypeMap[$planedTypeId];
        }
        return false;
    }

    private function prepareXmlProp($prop)
    {
        return trim((string)$prop);
    }

    public function getMessages()
    {
        if($this->arParams['IS_CLI']) {
            $message = '';
            if($this->successes) {
                echo implode(PHP_EOL, $this->successes).PHP_EOL.PHP_EOL;
                $message .= implode(PHP_EOL, $this->successes).PHP_EOL.PHP_EOL;
            }
            echo implode(PHP_EOL, $this->errors);
            $message .= implode(PHP_EOL, $this->errors);
            \Bitrix\Main\Mail\Event::sendImmediate([
                "EVENT_NAME" => "IMPORT_RESULT",
                "LID" => 's1',
                "C_FIELDS" => ["MESSAGE" => $message]
            ]);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/importMail.txt', print_r(date('d.m.Y H:i:s').PHP_EOL, 1), 8);

        } else {
            echo '<div class="importSuccess">' . implode('<br>', $this->successes) . '</div>';
            echo '<div class="importErrors">' . implode('<br>', $this->errors) . '</div>';
        }
    }
}