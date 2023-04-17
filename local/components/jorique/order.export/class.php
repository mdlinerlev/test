<?php

namespace Jorique\Components;

use Bitrix\Sale\Location\LocationTable;

class OrderExport extends \CBitrixComponent {

	/** @var \DOMDocument */
	public $xml;

	public function __construct($component) {
		$this->xml = new \DOMDocument;
		$this->xml->formatOutput = true;

		parent::__construct($component);
	}

	public function addTag(\DOMElement $node, $tag, $content = null) {
		$el = $this->xml->createElement($tag, $content);
		$node->appendChild($el);

		return $el;
	}

	public function getPropCode($arProp) {
		if($arProp['ORDER_PROPS_ID'] == 20) {
			$code = 'comment';
		}
		elseif($arProp['CODE']) {
			$code = ToLower(str_replace('-', '_', $arProp['CODE']));
		}
		else {
			$code = 'prop_'.$arProp['ORDER_PROPS_ID'];
		}
		return $code;
	}

	public function getPropValue($arProp) {
		$value = $arProp['VALUE'];
		if($arProp['CODE'] == 'LOCATION' && $arProp['VALUE']) {
			$value = $this->getLocationName($arProp['VALUE'], array('Центр'));
		}
		return $value;
	}

	public function getLocationName($id, $except = array()) {
		$location = LocationTable::getById($id)->fetch();
		if(!$location) {
			return $id;
		}
		$locationString = array();
		$rsLocations = LocationTable::getList(array(
			'select' => array('*', 'NAME_RU' => 'NAME.NAME'),
			'order' => array('LEFT_MARGIN' => 'ASC'),
			'filter' => array(
				'<=LEFT_MARGIN' => $location['LEFT_MARGIN'],
				'>=RIGHT_MARGIN' => $location['RIGHT_MARGIN'],
				'=NAME.LANGUAGE_ID' => LANGUAGE_ID
			)
		));
		while($location = $rsLocations->fetch()) {
			if(!in_array($location['NAME_RU'], $except)) {
				$locationString[] = $location['NAME_RU'];
			}
		}
		return implode(', ', $locationString);
	}
}