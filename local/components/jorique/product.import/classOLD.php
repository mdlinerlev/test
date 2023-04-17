<?php

namespace Jorique\Components;

use Exception;

class ProductImport extends \CBitrixComponent {

	private $cache = array();
	private $iblockId;
	private $offersIblockId;
	private $colorsHlBlock;
	private $colorsGroupBlock;

	private $goodTypeMap = array(
		1 => 23, # межк.
		2 => 24, # входные
		3 => 25, # фурнитура
		4 => 26, # нап. покр.
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

	public function __construct($component) {
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

	private function initColorsHl() {
		\CModule::IncludeModule('highloadblock');
		$hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->colorsHlBlock)->fetch();
		\Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
	}

    private function initColorsGroup() {
        \CModule::IncludeModule('highloadblock');
        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->colorsGroupBlock)->fetch();
        \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    }


	private function getBitrixObject($className) {
		$className = '\\'.$className;
		if (isset($this->cache['bitrixObjects'][$className])) {
			return $this->cache['bitrixObjects'][$className];
		} else {
			$object = new $className;
			$this->cache['bitrixObjects'][$className] = $object;
			return $object;
		}
	}

	public function prepareParams() {
		$this->arParams['IS_CLI'] = $this->arParams['IS_CLI'] == 'Y';
		$this->arParams['XML_PATH'] = $_SERVER['DOCUMENT_ROOT'].trim($this->arParams['XML_PATH']);
	}

	public function checkParams() {
		if($this->arParams['IS_CLI']) {
			if(php_sapi_name() !== 'cli') {
				throw new Exception('Запуск импорта только из шелла');
			}
			if(!$this->arParams['XML_PATH']) {
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

    protected function GetExitsPropsList($IBLOCK_ID) {
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
    private function PreparePropertyFields($arProperty) {

        if (!array_key_exists("PROPERTY_TYPE", $arProperty))
            $arProperty["PROPERTY_TYPE"] = "S";

        if (!array_key_exists("CODE", $arProperty) || empty($arProperty["CODE"])) {
            $arProperty["CODE"] = \CUtil::translit(trim($arProperty["NAME"]), LANGUAGE_ID, array(
                "max_len"				 => 50,
                "change_case"			 => "U", // "L" - toLower, "U" - toUpper, false - do not change
                "replace_space"			 => "_",
                "replace_other"			 => "_",
                "delete_repeat_replace"	 => true,
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
    private function CheckIblockProperties($arProperty, $iblockId) {

        $this->GetExitsPropsList($iblockId);

        $obProperty = new \CIBlockProperty();

        $code = $arProperty["XML_ID"];
        if (!empty($this->iblockProps[$iblockId][$code])) {
            if($this->iblockProps[$iblockId][$code]['USER_TYPE'] != 'directory' &&  $arProperty['PROPERTY_TYPE'] != $this->iblockProps[$iblockId][$code]['PROPERTY_TYPE'] ) {
                $obProperty->Update($this->iblockProps[$iblockId][$code]["ID"], ['PROPERTY_TYPE' => $arProperty['PROPERTY_TYPE']]);
                $this->iblockProps[$iblockId][$code]['PROPERTY_TYPE'] = $arProperty['PROPERTY_TYPE'];
            }
        }
        else {
            $this->iblockProps[$iblockId][$code] = $this->PreparePropertyFields($arProperty);
            $this->iblockProps[$iblockId][$code]['ID'] = $obProperty->Add($this->iblockProps[$iblockId][$code]);
        }

        return $this->iblockProps[$iblockId][$code];
    }


    public function import($path) {
        $xml = simplexml_load_file($path);
        $root = (array) $xml;
        $base = $root['@attributes']['base'];

        $goods = $xml->xpath('//products/product');
        if(!sizeof($goods)) {
            throw new Exception('В файле выгрузки не найдены товары');
        }

        /** @var \CIBlockElement $ieObject */
        $ieObject = $this->getBitrixObject('CIBlockElement');

		/*
         * Уникальный ид товара - XML_ID. Проверяем на сайте - если товар с текущим XML_ID есть,
         * обновляем его свойства. Иначе добавляем товар. Всем товарам изначально проставляем 0 в свойство XML_NEW,
         * затем каждому обновлённому или добавленному товару проставляем 1. В конце выгрузки все товары, у которых XML_NEW=0
         * деактивируем (или удаляем) - этих товаров нет в файле выгрузки.
         */

		$added = $updated = $deleted = 0;

        # проставляем FROM_XML_TMP=0 у всех товаров и тп
        $rsElems = $ieObject->GetList(array(), array(
            'IBLOCK_ID' => array($this->iblockId, $this->offersIblockId),
            'ACTIVE' => 'Y',
            'PROPERTY_XML_NEW' => 1,
            '=PROPERTY_ROOT_IMPORT' => $base,
        ), false, false, array(
            'ID', 'IBLOCK_ID'
        ));
        if ($rsElems->SelectedRowsCount()) {
            while ($arElem = $rsElems->Fetch()) {
                $ieObject->SetPropertyValuesEx(
                    $arElem['ID'],
                    $arElem['IBLOCK_ID'],
                    array(
                        'XML_NEW' => 0,
                    )
                );
            }
        }

        $arPropertyListCache = array();

		foreach($goods as $good) {

            $pri = floatval($good->price);
            $ol = floatval($good->{'old-price'});
            if($pri > $ol) {
                $ol = 0;
            }  elseif($pri <= 0 ){
                $ol = 0;
            }

		    $offers = $good->offers->offer;
			/*echo sizeof($offers).' ';
			continue;*/
			$props = array(
				'name' => $good->name,
				'ref_id' => $good->reference_id,
				'is_glass' => $good->glass,
				'type' => $good['type'],
				'xml_id' => $good->id,
				'section_id' => $good->section_id,
				'qty' => $good->quantity,
				'price' => $pri,
				'old-price' => $ol,
				'article' => $good->article,
				'square' => $good->square
			);
			if ($props['square']) {
                $props['square'] = (float)str_replace(',', '.', $props['square']);
                $props['price'] *= $props['square'];
            }
			$props = array_map(array($this, 'prepareXmlProp'), $props);

			if($props['section_id']) {
				$sectionExists = \CIBlockSection::GetList(false, array(
					'IBLOCK_ID' => $this->iblockId,
					'ID' => $props['section_id']
				), false, array(
					'ID', 'IBLOCK_ID'
				))->SelectedRowsCount();

				if(!$sectionExists) {
					$this->errors[] = 'Не найден раздел с id '.$props['section_id'];
					continue;
				}
			}

			if(!$props['xml_id']) {
				$this->errors[] = 'У товара нет XML_ID';
				continue;
			}

            $fields = array(
                'XML_ID' => $props['xml_id'],
                'NAME' => $props['name'],
                'IBLOCK_ID' => $this->iblockId,
                //'ACTIVE' => 'Y',
                'IBLOCK_SECTION_ID' => $props['section_id'] ?: false,
            );
            $properties = array(
                'XML_NEW' => 1,
                'ROOT_IMPORT' => $base,
                'PRODUCT_TYPE' => $this->getGoodType($props['type']),
                'GLASS' => $props['is_glass'] ? 1 : false,
                'GLASS_REF' => $props['ref_id'],
                'OLD_PRICE' => $props['old-price'],
                'ARTICLE' => $props['article'],
                'BOX_SQUARE' => str_replace(',', '.', $props['square'])
            );


            if(!empty($good->properties)){

                $propertiesXml = simplexml_load_string($good->properties->asXML());
                $propertiesXml = $propertiesXml->xpath('//properties/property');

                foreach($propertiesXml as $field) {
                    $field = (array) $field;
                    $propsAttr = $field['@attributes'];

                    $arPropertyValue = trim($field[0]);

                    if(empty($propsAttr['guid']) || empty($arPropertyValue))
                        continue;

                    $arProperty = [
                        "IBLOCK_ID"	 => $this->iblockId,
                        "ACTIVE"	 => "Y",
                        "XML_ID"     => $propsAttr['guid'],
                        "NAME"       => $propsAttr['name'],
                        "PROPERTY_TYPE" => "S"
                    ];


                    if($propsAttr['type'] == 'Справочник') {
                        $arProperty["PROPERTY_TYPE"] = "L";
                    } elseif($propsAttr['type'] == 'Число')  {
                        $arProperty["PROPERTY_TYPE"] = "N";
                    } elseif($propsAttr['type'] == 'Булево') {
                        $arProperty["PROPERTY_TYPE"] = "L";
                        $arProperty["LIST_TYPE"] = "C";
                    }


                    $arProperty = $this->CheckIblockProperties($arProperty, $this->iblockId);
                    $idProp = $arProperty['ID'];
                    if(!empty($idProp)) {
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
                                    $arPropertyListCache[$arProperty['ID']][$propValueHash] = \CIBlockPropertyEnum::Add(["PROPERTY_ID" => $arProperty['ID'],"XML_ID" => $propValueHash, "VALUE" => $arPropertyValue]);


                                if (isset($arPropertyListCache[$arProperty['ID']][$propValueHash]))
                                    $properties[$idProp] = $arPropertyListCache[$arProperty['ID']][$propValueHash];
                                else
                                    $properties[$idProp] = '';

                                break;
                            case "N":
                                $properties[$idProp] = str_replace(',', '.', $arPropertyValue);
                                break;
                            case "S":
                                if($arProperty['USER_TYPE'] == 'directory'){

                                    if(\CModule::IncludeModule('highloadblock')){

                                        $propValueHash = md5('belwood' . mb_strtolower($arPropertyValue));
                                        if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash])) {
                                            $arPropertyListCache[$arProperty['ID']] = [];

                                            $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(['filter' => ['TABLE_NAME' => $arProperty['USER_TYPE_SETTINGS']['TABLE_NAME']]])->fetch();
                                            $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                                            $hlDataClass = $entity->getDataClass();
                                            $res = $hlDataClass::getList(['filter' => [], 'select' => ["XML_ID" => "UF_XML_ID", "ID", "VALUE" => "UF_NAME"]]);
                                            while ($propEnumValue = $res->fetch()) {
                                                $xmlID = $propEnumValue['XML_ID'];
                                                if(strlen($xmlID)<15){
                                                    $xmlID = false;
                                                }
                                                $arPropertyListCache[$arProperty['ID']][$xmlID ?: md5('belwood' . mb_strtolower($propEnumValue['VALUE']))] = $propEnumValue['XML_ID'];
                                            }

                                            if (!isset($arPropertyListCache[$arProperty['ID']][$propValueHash])) {
                                                if($hlDataClass::add(["UF_XML_ID" => $propValueHash, "UF_NAME" => $arPropertyValue])) {
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
            $el = $ieObject->GetList(array(), array('IBLOCK_ID' => $this->iblockId, '=XML_ID' => $props['xml_id']));
            if($el->SelectedRowsCount()) {
                # апдейтим
                $el = $el->Fetch();
                $prId = $el['ID'];
                $fields['CODE'] = $this->getCode($props['name'], $prId);
                if($ieObject->Update($el['ID'], $fields)) {
                    \CIBlockElement::SetPropertyValuesEx($el['ID'], $this->iblockId, $properties);
                    $updated++;
                }
                else {
                    $this->errors[] = 'Ошибка при обновлении товара, XML_ID ' . $props['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                }
            }
            else {
                # добавляем
                $fields['CODE'] = $this->getCode($props['name']);
                if($prId = $ieObject->Add(array_merge($fields, array('PROPERTY_VALUES' => $properties)))) {
                    $added++;
                }
                else {
                    $this->errors[] = 'Ошибка при добавлении товара, XML_ID ' . $props['xml_id'] . ': ' . $ieObject->LAST_ERROR;
                }
            }
            # цена и количество
            if($prId && !sizeof($offers)) {
                $this->setQuantity($prId, $props['qty']);
                $this->setPrice($prId, $props['price']);
            }
            if($prId && sizeof($offers)) {
                foreach($offers as $offer) {

                    $pri = floatval($offer->price);
                    $ol = floatval($offer->{'old-price'});
                    if($pri > $ol) {
                        $ol = 0;
                    } elseif($pri <= 0){
                        $ol = 0;
                    }


                    $offerProps = array(
                        'name' => $offer->name,
                        'xml_id' => $offer->id,
                        'price' => $pri,
                        'old-price' => $ol,
                        'qty' => $offer->quantity,
                        'color' => $offer->color,
                        'size' => $offer->size,
                        'article' => $offer->article,
                        'glass-color' => $offer->{'glass-color'},
                        'open-side' => $offer->{'open-side'},
                        'color-inner' => $offer->{'inner-color'},
                        'color-outer' => $offer->{'outer-color'},
                    );
                    $offerProps = array_map(array($this, 'prepareXmlProp'), $offerProps);
                    if(!$offerProps['xml_id']) {
                        $this->errors[] = 'У торгового предложения нет XML_ID';
                        continue;
                    }

                    $fields = array(
                        'XML_ID' => $offerProps['xml_id'],
                        'NAME' => $offerProps['name'],
                        'IBLOCK_ID' => $this->offersIblockId,
                        //'ACTIVE' => 'Y'
                    );
                    $properties = array(
                        'XML_NEW' => 1,
'ROOT_IMPORT' => $base,
                        'CML2_LINK' => $prId,
                        'OLD_PRICE' => $offerProps['old-price'],
                        'ARTICLE' => $offerProps['article']
                    );
                    if($offerProps['size']) {
                        $properties['SIZE'] = $this->getEnumVariantId(66, $offerProps['size']);
                    }
                    if($offerProps['color']) {
                        $properties['COLOR'] = $this->getColorIdByName($offerProps['color']);
                        $properties['GROUP_COLOR'] = $this->getColorGroupIdByName($properties['COLOR']);
                    }
                    if($offerProps['glass-color']) {
                        $properties['GLASS_COLOR'] = $this->getColorIdByName($offerProps['glass-color']);
                    }
                    if($offerProps['color-inner']) {
                        $properties['COLOR_IN'] = $this->getColorIdByName($offerProps['color-inner']);
                    }
                    if($offerProps['color-outer']) {
                        $properties['COLOR_OUT'] = $this->getColorIdByName($offerProps['color-outer']);
                    }
                    if($offerProps['open-side']) {
                        $properties['SIDE'] = $this->getEnumVariantId(86, $offerProps['open-side']=='LHO/RHI' ? 'Левая' : 'Правая');
                    }

					# проверяем, есть ли оффер с таким XML_ID
					$el = $ieObject->GetList(array(), array('IBLOCK_ID' => $this->offersIblockId, '=XML_ID' => $offerProps['xml_id']));
					if($el->SelectedRowsCount()) {
						# апдейтим
						$el = $el->Fetch();
						$ofId = $el['ID'];
						if($ieObject->Update($el['ID'], $fields)) {
							\CIBlockElement::SetPropertyValuesEx($el['ID'], $this->offersIblockId, $properties);
							$updated++;
						}
						else {
							$this->errors[] = 'Ошибка при обновлении оффера, XML_ID ' . $offerProps['xml_id'] . ': ' . $ieObject->LAST_ERROR;
						}
					}
					else {
						# добавляем
						if($ofId = $ieObject->Add(array_merge($fields, array('PROPERTY_VALUES' => $properties)))) {
							$added++;
						}
						else {
							$this->errors[] = 'Ошибка при добавлении оффера, XML_ID ' . $offerProps['xml_id'] . ': ' . $ieObject->LAST_ERROR;
						}
					}
					# цена и количество
					if($ofId) {
						$this->setQuantity($ofId, $offerProps['qty']);
						$this->setPrice($ofId, $offerProps['price']);
					}
				}
			}
		}

		# теперь привяжем погонаж к дверям
		foreach($goods as $good) {
			$type = trim((string)$good['type']);
			if($type != 5) {
				continue;
			}

			$offers = $good->references->offers;
			if(!sizeof($offers)) {
				continue;
			}

			# ищем сам товар погонажа в каталоге
			$planed = \CIBlockElement::GetList(false, array(
				'IBLOCK_ID' => $this->iblockId,
				'=XML_ID' => trim((string)$good->id)
			), false, array('nTopCount' => 1), array(
				'ID', 'IBLOCK_ID'
			))->Fetch();
			if(!$planed) {
				$this->errors[] = 'По каким-то причинам в каталоге не найден погонаж '.trim((string)$good->id);
				continue;
			}

            $frameType = [];
			foreach($offers as $item) {

                $xmlId = (string)$item->offer_id;

                if (!$xmlId) {
                    $this->errors[] = 'Пустой offer_id у погонажа';
                    continue;
                }

                if(empty($frameType[$xmlId])) {
                    $frameType[$xmlId] = [];
                }
                foreach ($item->{'frame-type'} as $frame) {
                    foreach ($frame as $type => $count) {
                        $type = (string)$type;
                        $type = str_replace('frame-type-', '', $type);

                        $count = (int)$count;

                        if($count <= 0) continue;

                        if(!empty($frameType[$xmlId][$type]) && intval($frameType[$xmlId][$type]) <= $count)
                            continue;


                        $frameType[$xmlId][$type] = (int)$count;
                    }
                }
            }

			$offersIds = array_keys($frameType);
            $dbItems = \Bitrix\Iblock\ElementTable::getList([
                'select' => ['ID', 'XML_ID'],
                'filter' => ['IBLOCK_ID' => $this->offersIblockId, '=XML_ID' => $offersIds],
                'limit' => count($offersIds)
            ]);
            $dataFrame = [];
            while ($arItem = $dbItems->fetch()) {
                if(!empty($frameType)) {
                    foreach ($frameType[$arItem['XML_ID']] as $type => $count) {
                        $tmp = [
                            'ELEMENT_ID' => $planed['ID'],
                            'LINK_TYPE' => $type,
                            'COUNT' => $count,
                            'ELEMENT_ID_LINK' => $arItem['ID'],
                        ];
                        $hash = md5($tmp['ELEMENT_ID'].$tmp['LINK_TYPE'].$tmp['COUNT'].$tmp['ELEMENT_ID_LINK']);
                        $dataFrame[$hash] = $tmp;
                    }
                }

                unset($frameType[$arItem['XML_ID']]);
            }

            if($frameType) {
                $this->errors[] = 'offer_id ' . trim((string)$good->id) . ' нет товаров для связии ' . implode(', ', array_keys($frameType));
            }

            if (\Bitrix\Main\Loader::includeModule("sh.dblayer")) {

                $res = \Sh\DBLayer\CTables::getOrmQuery("frame_type")
                    ->setSelect(["*"])
                    ->setFilter(["=ELEMENT_ID" => $planed['ID']])
                    ->exec();

                $deleteIds = [];
                while ($tmp = $res->fetch()) {
                    $hash = md5($tmp['ELEMENT_ID'].$tmp['LINK_TYPE'].$tmp['COUNT'].$tmp['ELEMENT_ID_LINK']);
                    if(!empty($dataFrame[$hash])){
                        unset($dataFrame[$hash]);
                    } else {
                        $deleteIds[] = $tmp['ID'];
                    }
                }

                $instance = \Sh\DBLayer\CTables::getOrmTableInstance('frame_type');
                $instance->deleteFromGetList(["=ID" => $deleteIds]);

                if(!empty($dataFrame)) {
                    foreach ($dataFrame as $item) {
                        $instance->add($item);
                    }
                }
            }

		}

		# деактивируем товары, у которых XML_NEW=0
        $behavior = \COption::GetOptionString("grain.customsettings", "missing_behavior");
        if($behavior == 'deact' || $behavior == 'remove') {
            $rsElems = $ieObject->GetList(array(), array(
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
                'PROPERTY_XML_NEW' => 0,
                '=PROPERTY_ROOT_IMPORT' => $base,
            ));
            if ($rsElems->SelectedRowsCount()) {
                while ($arElem = $rsElems->Fetch()) {
                    if($behavior == 'remove') {
                        $delRes = $ieObject->Delete($arElem['ID']);
                    }
                    else {
                        $delRes = $ieObject->Update($arElem['ID'], array('ACTIVE' => 'N'));
                    }
                    if ($delRes) {
                        $deleted++;
                    } else {
                        $this->errors[] = 'Ошибка при деактивации товара, id '.$arElem['ID'].': '.$ieObject->LAST_ERROR;
                    }
                }
            }

            # деактивируем офферы, у которых XML_NEW=0
            $rsElems = $ieObject->GetList(array(), array(
                'IBLOCK_ID' => $this->offersIblockId,
                'ACTIVE' => 'Y',
                'PROPERTY_XML_NEW' => 0,
                '=PROPERTY_ROOT_IMPORT' => $base,
            ));
            if ($rsElems->SelectedRowsCount()) {
                while ($arElem = $rsElems->Fetch()) {
                    if($behavior == 'remove') {
                        $delRes = $ieObject->Delete($arElem['ID']);
                    }
                    else {
                        $delRes = $ieObject->Update($arElem['ID'], array('ACTIVE' => 'N'));
                    }
                    if ($delRes) {
                        $deleted++;
                    } else {
                        $this->errors[] = 'Ошибка при деактивации оффера, id '.$arElem['ID'].': '.$ieObject->LAST_ERROR;
                    }
                }
            }
        }

		foreach(GetModuleEvents("catalog", "OnSuccessCatalogImport1C", true) as $arEvent) {
			ExecuteModuleEventEx($arEvent, array(array(), ''));
		}

		$this->successes[] = 'Товары: добавлено - ' . $added . ', обновлено - ' . $updated . ', деактивировано - ' . $deleted;
	}

	private function getColorIdByName($colorName) {
		/** @var \Bitrix\Main\DB\Result $color */
		$color = \ColorReferenceTable::getList(array(
			'filter' => array(
				'=UF_NAME' => $colorName
			)
		));
		if($color->getSelectedRowsCount()) {
			$color = $color->fetch();
			return $color['UF_XML_ID'];
		}
		else {
			$xmlId = randString();
			/** @var \Bitrix\Main\Entity\AddResult $addRes */
			$addRes = \ColorReferenceTable::add(array(
				'UF_XML_ID' => $xmlId,
				'UF_NAME' => $colorName
			));
			if($addRes->isSuccess()) {
				return $xmlId;
			}
		}
		return false;
	}

    private function getColorGroupIdByName($xmlId) {
        $color = \ColorReferenceTable::getList(array(
            'filter' => array(
                '=UF_XML_ID' => $xmlId
            )
        ));
        if($color->getSelectedRowsCount()) {
            $color = $color->fetch();
            if(!empty($color['UF_GROUP'])){
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
	private function getEnumVariantId($propId, $value) {
		if (isset($this->cache['prop-' . $propId])) {
			$arVariants = $this->cache['prop-' . $propId];
		} else {
			$arVariants = array();
			$rsVariants = \CIBlockProperty::GetPropertyEnum($propId);
			if ($rsVariants->SelectedRowsCount()) {
				while ($arVariant = $rsVariants->Fetch()) {
					$arVariants[$arVariant['VALUE']] = $arVariant['ID'];
				}
			}
			$this->cache['prop-' . $propId] = $arVariants;
		}
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

	private function getCode($name, $id = false) {
		$inc = '';
		$translit = \CUtil::translit($name, 'ru', array(
			'replace_space' => '-',
			'replace_other' => '-'
		));
		$filter = array(
			'IBLOCK_ID' => $this->iblockId
		);
		if($id) {
			$filter['!=ID'] = $id;
		}
		while(true) {
			$code = $translit.$inc;
			$filter['=CODE'] = $code;
			# ищем такой элемент

			$res = \CIBlockElement::GetList(false, $filter, false, array('nTopCount' => 1), array('ID', 'IBLOCK_ID'));
			if(!$res->SelectedRowsCount()) {
				return $code;
			}
			$inc++;
		}
		return 'ololo'; # impossibru
	}

	private function setPrice($id, $price) {
		if($price) {
			/** @var \CPrice $priceObject */
			$priceObject = $this->getBitrixObject('CPrice');
			$priceObject->SetBasePrice($id, $price, $this->currency);
		}
	}

	private function setQuantity($id, $quantity) {
		$quantity = (int)$quantity;
		if(\CCatalogProduct::GetByID($id)) {
			\CCatalogProduct::Update($id, array('QUANTITY' => $quantity));
		}
		else {
			\CCatalogProduct::Add(array('ID' => $id, 'QUANTITY' => $quantity));
		}

	}

	private function getGoodType($xmlTypeId) {
		if(isset($this->goodTypeMap[$xmlTypeId])) {
			return $this->goodTypeMap[$xmlTypeId];
		}
		return false;
	}

	private function getPlanedTypeProp($planedTypeId) {
		if(isset($this->planedTypeMap[$planedTypeId])) {
			return $this->planedTypeMap[$planedTypeId];
		}
		return false;
	}

	private function prepareXmlProp($prop) {
		return trim((string)$prop);
	}

	public function getMessages() {
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
                "LID" => SITE_ID,
                "C_FIELDS" => ["MESSAGE" => $message]
            ]);
        }
		else {
			echo '<div class="importSuccess">'.implode('<br>', $this->successes) . '</div>';
			echo '<div class="importErrors">'.implode('<br>', $this->errors) . '</div>';
		}
	}
}