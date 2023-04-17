<?php

//foreach($arResult['ITEMS'] as &$item) {
//	# смотрим торговые предложения
//	$offer = CIBlockElement::GetList(array('RAND' => 'ASC'), array(
//		'IBLOCK_ID' => IBLOCK_ID_OFFERS,
//		'ACTIVE' => 'Y',
//		'=PROPERTY_CML2_LINK' => $item['ID']
//	))->Fetch();
//	if($offer) {
//		$item['OFFERS'][0] = $offer;
//	}
//}
//unset($item);