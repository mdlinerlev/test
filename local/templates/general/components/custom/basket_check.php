<?php
//task1090
define("LANGUAGE_ID", "ru");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("sale");
CModule::IncludeModule("iblock");

$curPath = $_POST["curpath"];
//При попытке закрыть вкладку на определенных страницах(список страниц можно задавать/менять) должно появляться окно(popup):
//Если страница определена, то показываем окно для страницы, дальше смотрим по корзине.
$show = false;

if(!empty($_SESSION['popup_closetab_curpath'])){
    if(in_array($curPath,explode(',',$_SESSION['popup_closetab_curpath'])))
        if(empty($_SESSION['popup_closetab_shown'])){
            $show = true;
        }else{
            if(time()-$_SESSION['popup_closetab_shown'] > 100)
                $show = true;
        }

}else{
    $pages = [];
    $arSelect = Array("ID", "PROPERTY_CURRENTURL");
    $arFilter = Array("IBLOCK_ID" => 20, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(Array("ORDER" => "ASC"), $arFilter, false, false, $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $pages[] = $arFields['PROPERTY_CURRENTURL_VALUE'];
    }
	//var_dump($pages);print_r('ffff');die;
    $_SESSION['popup_closetab_curpath'] = implode(",",$pages);    
    unset($pages);

    if(in_array($curPath,explode(',',$_SESSION['popup_closetab_curpath'])))
        $show = true;
}

if($show){
    $_SESSION['popup_closetab_shown'] = time();
    echo 'Consult';
} else {


//Проверяем количество товаров в корзине и записываем в сессию, если количество при следующей проверке не сходится и
// оно больше чем 0, то выводим корзину
    $productQuantity = 0;
    $countBasketItems = CSaleBasket::GetList(
        array(),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
        ),
        array()
    );

    $dbBasketItems = CSaleBasket::GetList(
        array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
        ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE",
            "PRODUCT_ID", "QUANTITY", "DELAY",
            "CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "DETAIL_PICTURE")
    );
    $arProd = [];
    while ($arItem = $dbBasketItems->Fetch()) {
        $productQuantity += $arItem['QUANTITY'];
        $arProd[] = $arItem;
    }

    if (!empty($_SESSION['popup_closetab']) and $_SESSION['popup_closetab'] == $countBasketItems && $_SESSION['popup_closetab_quantity'] == $productQuantity) {
        echo '';
        return;
    }
    $productCart = '';
    if ($countBasketItems > 0) {
        $_SESSION['popup_closetab'] = $countBasketItems;
        $_SESSION['popup_closetab_quantity'] = $productQuantity;
        $productCart .= '
        <p>В вашей корзине есть товары:</p>
            <div id="basket_items">
            <table class="cart__table">';
        foreach ($arProd as $arItems) {
            $res = CIBlockElement::GetByID($arItems["PRODUCT_ID"]);
            if ($imgprod = $res->GetNext()) {
                $img = $imgprod;
            }
            $file = CFile::ResizeImageGet($img['DETAIL_PICTURE'], array('width' => 52, 'height' => 110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $productCart .= '
           <tr class="cart-table__row" id="1265">
                <td class="cart-table__cell cart-table__cell--image">
                    <a class="cart-table__link" href="#">
                        <img src="' . $file['src'] . '" class="cart-table__image">
                    </a>
                </td>
                <td class="cart-table__cell cart-table__cell--title">
                    <a class="cart-table__title" href="#">
                        ' . $arItems["NAME"] . '
                    </a>
                </td>
                <td class="cart-table__cell cart-table__cell--price">
                    <div class="cart-table__price">
                        <div class="cart-table-price__discount" id="current_price_1265">
                            ' . CCurrencyLang::CurrencyFormat($arItems["PRICE"], $arItems["CURRENCY"]) . '
                        </div>
                        <div class="cart-table-price__base">
                            <span class="cart-table-price__number" id="old_price_1265"></span>
                        </div>
                    </div>
                </td>
           </tr>
        ';

        }
        $productCart .= '</table></div>
        <p>Возможно, Вам требуется дополнительная консультация?</p>
        <div style="text-align:center">
            <a class="header-text-item__callback greenbutton" id="callback_pb">
                <span class="header-text-item__callback-inner">Заказать звонок</span>
            </a>
        </div>';
    }
    echo $productCart;
}