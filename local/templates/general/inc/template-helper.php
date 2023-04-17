<?php

# получаем id товаров в корзине
Cmodule::IncludeModule('sale');
$rsCartItems = CSaleBasket::GetList(array(), array(
	'FUSER_ID' => CSaleBasket::GetBasketUserID(),
	'LID' => SITE_ID,
	'ORDER_ID' => 'NULL'
), false, false, array(
	'ID', 'PRODUCT_ID', 'DELAY', 'SUBSCRIBE'
));
$cartItems = array();
while($cartItem = $rsCartItems->Fetch()) {
	$cartItems[] = $cartItem;
}
$GLOBALS['myCartItems'] = $cartItems;



# просмотренные товары
$GLOBALS['recentHelper'] = new RecentHelper('viewed_products', 18, 3600*24*7);