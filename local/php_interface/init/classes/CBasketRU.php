<?
class CBasketRU {

    public function __construct(){

    }

    /**
     * Вовзращает html информера добавления в корзину/отложенные/сравнение
     * @param $idItem
     * @param $action
     * @return string
     */
    public function getActionInformerHtml($idItem, $action){

        if( $idItem && $action ){
            if( CModule::IncludeModule('iblock') ){

                $res = CIBlockElement::GetByID($idItem);
                if( $ar_res = $res->GetNext() ){

                    switch( $action ){
                        case "cart" : $comment = "Добавлено в корзину";
                            break;
                        case "delay" : $comment = "Добавлено в отложенные";
                            break;
                        case "compare" : $comment = "Добавлено в сравнение";
                            break;
                        default:
                            break;
                    }

                    ob_start();?>

                    <div class="shop-links__informer <?=$action?>">
                        <div class="shop-links-informer__title"><?=$ar_res['NAME']?></div>
                        <div class="shop-links-informer__bottom">
                            <div class="shop-links-informer__comment"><?=$comment;?></div>
                        </div>
                    </div>

                    <?
                    $html = ob_get_contents();
                    ob_end_clean();
                }
            }
        }

        return $html;
    }

    /**
     * Возращает инфу по бесплатной доставке в корзине
     * @param $price
     * @param $weight
     * @return string
     */
    public function getBasketDeliveryInfo($price, $weight){

        $html = "";
        $minPrice = 3500;
        $maxWeight = 10000;

        $objLocation = new CLocationRU();
        $location = $objLocation->getLocationCityInfo();

        if(
            $price >= $minPrice &&
            $weight <= $maxWeight &&
            ( $location['CITY_NAME'] == 'Москва' || preg_match('#московская.*#ui', $location['REGION_NAME']) )
        ){
            $html = '<div class="cart-bottom__delivery"><div class="cart-bottom-delivery__text">Для Вас доставка<mark>бесплатно</mark></div></div>';
        }

        return $html;
    }

    /**
     * Сохраняет в сессии актуальный список товаров добавленных в корзину и отложенные
     */
    public function getActualizeBasketDelayItems(){

        $_SESSION['UP']['BASKET'] = array();

        if( CModule::IncludeModule('sale') ){
            $dbBasketItems = CSaleBasket::GetList(
                array("NAME" => "ASC", "ID" => "ASC"),
                array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
                false, false, array("ID", "CAN_BUY", "PRODUCT_ID", "DELAY", "SUBSCRIBE")
            );
            while( $arItems = $dbBasketItems->Fetch() ){

                if( $arItems['CAN_BUY'] == 'Y' ){
                    if( $arItems['DELAY'] == 'N' ){
                        $_SESSION['UP']['BASKET']['BASKET'][$arItems['PRODUCT_ID']] = $arItems['ID'];
                    }
                    elseif( $arItems['DELAY'] == 'Y' ){
                        $_SESSION['UP']['BASKET']['DELAY'][$arItems['PRODUCT_ID']] = $arItems['ID'];
                    }
                }
                else{
                    if( $arItems['SUBSCRIBE'] == 'Y' ){
                        $_SESSION['UP']['BASKET']['SUBSCRIBE'][$arItems['PRODUCT_ID']] = $arItems['ID'];
                    }
                }
            }
        }

        return $_SESSION['UP']['BASKET'];
    }
}
?>