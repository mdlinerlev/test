<?

/**
 * @author Dmitry Sharyk
 * email: d.sharyk@gmail.com
 */
CBitrixComponent::includeComponentClass("newsite:basket");

class CNewsiteBasketSmall extends CShBasket
{
    /**
     * Подготовка параметров - всегда ajax режим
     * @param type $initParams
     * @return string
     */
    function onPrepareComponentParams($initParams) {
        $initParams["AJAX_ID"] = 'basket_component';
        $initParams["AJAX_MODE"] = "Y";
        return $initParams;
    }
    
}
