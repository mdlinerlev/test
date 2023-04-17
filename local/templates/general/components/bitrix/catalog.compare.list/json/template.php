<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$objBasket = new CBasketRU();
$countBefore = $arParams['COUNT_BEFORE_ADD'];
$idItemCompare = $arParams['ID_ITEM_COMPARE'];

foreach($_SESSION['CATALOG_COMPARE_LIST'] as $iblock){
    $countAfter += count($iblock['ITEMS']);
}

if( $countAfter != $countBefore ){
    $compare['RESULT'] = 'success';
    if( $countAfter > $countBefore ){

        $compare['ACTION'] = 'add';

        $actionInformerHtml = $objBasket->getActionInformerHtml($idItemCompare, 'compare');
    }
    else{
        $compare['ACTION'] = 'delete';
    }
}
else{
    $compare['RESULT'] = 'fail';
}



# обновляем корзину в шапке
ob_start();
$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "top", Array(
    "COMPONENT_TEMPLATE" => ".default",
    "PATH_TO_BASKET" => "/personal/cart/",	// Страница корзины
    "PATH_TO_ORDER" => "/personal/order/make/",	// Страница оформления заказа
    "SHOW_DELAY" => "Y",	// Показывать отложенные товары
    "SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
    "SHOW_SUBSCRIBE" => "N",	// Показывать товары, на которые подписан покупатель
    "ACTION_INFORMER" => $actionInformerHtml,
),
    false
);
$topBasket = trim(ob_get_contents());
ob_end_clean();

$compare['topBasketHtml'] = $topBasket;


/*
#разбиваем по разделам
foreach($arResult as $arItem){

    $idIblock = $arItem['IBLOCK_SECTION_ID'];

    $section = array();
    $section['COUNT'] = $sections[$idIblock]['COUNT'] + 1;
    $section['COUNT_FORMATTED'] = $section['COUNT'].' '.plural($section['COUNT'], GetMessage("ITEM_ONE"), GetMessage("ITEM_TWO"), GetMessage("ITEM_MANY"));

    $sections[$idIblock] = $section;
}

$compare = $sections;
*/

echo json_encode($compare);