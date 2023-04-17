<?php

CModule::IncludeModule('itconstruct.uncachedarea');

itc\CUncachedArea::registerCallback('productCartBlock', function($key, $data) {
	$output = array();

	foreach($data as $item) {
		$subkey = itc\CUncachedArea::getSubkey($item);
		$dataString = $item['dataString'] ?: '';

		$inCart = false;
		$myItems = $GLOBALS['myCartItems'];
		if(is_array($myItems)) {
			# ищем товар в отложенных
			foreach($myItems as $myItem) {
				if($myItem['PRODUCT_ID']==$item['id']) {
					$inCart = true;
					break;
				}
			}
		}
		$class = 'product-filter-submit__button product-filter-submit__button--submit button';
		$classSecond = 'product-filter-submit__button  button  hidden';
		
		if($item['notFilter']) {
			$class = 'product-filter-submit__button  button tooltip-link tooltip-top';
		}

		if($inCart){
			$output[$subkey] = '<a href="/personal/cart/" data-id="'.$item['id'].'" class="'.$class.'"'.$dataString.'>Перейти в корзину</a>';
		}
		else{
			if($item['quantity']) {
				$output[$subkey] = '<button type="button" data-id="'.$item['id'].'" class="'.$class.' detail-to-cart"'.$dataString.'>Купить</button>
                    <a rel="nofollow" href="/personal/cart/" data-id="'.$item['id'].'" class="'.$classSecond.'">Перейти в корзину</a>';
			}
			else {
				$tooltip = '<div class="tooltip  tooltip--right">Средний срок ожидания заказной позиции 21 дней. Пожалуйста, уточняйте срок у оператора</div>';
				$output[$subkey] = '<button type="button" data-id="'.$item['id'].'" class="'.$class.' detail-to-cart tooltip-link tooltip-top"'.$dataString.'>'.$tooltip.'Заказать</button>
                    <a rel="nofollow" href="/personal/cart/" data-id="'.$item['id'].'" class="'.$classSecond.'">Перейти в корзину</a>';
			}
		}
	}
	return $output;
});

