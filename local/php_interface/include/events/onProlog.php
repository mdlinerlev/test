<?
function SiteInitAction() {

    //refresh basket line
    unset($_SESSION['SALE_USER_BASKET_PRICE']);
    unset($_SESSION['SALE_USER_BASKET_QUANTITY']);

    if(\Bitrix\Main\Loader::IncludeModule("sale")) {
        $saleRegistry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
        $saleRegistry->set(\Bitrix\Sale\Registry::ENTITY_BASKET, '\Newsite\Sale\Basket');
        $saleRegistry->set(\Bitrix\Sale\Registry::ENTITY_BASKET_ITEM, '\Newsite\Sale\BasketItem');
        $saleRegistry->set(\Bitrix\Sale\Registry::ENTITY_ORDER, '\Newsite\Sale\Order');
        $saleRegistry->set(\Bitrix\Sale\Registry::ENTITY_PROPERTY_VALUE_COLLECTION, '\Newsite\Sale\PropertyValueCollection');
        $saleRegistry->set(\Bitrix\Sale\Registry::ENTITY_PROPERTY_VALUE, '\Newsite\Sale\PropertyValue');
    }

    if($_SERVER['REQUEST_URI'] == "/index.php") {
        header("Location: /",TRUE,301);
        exit();
    }
}
