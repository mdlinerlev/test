<?

class ProductsController{

    public static function Update($request){
        $res = \Ml\Soap\Wsdl\Action\Product::Update($request);
        return [
            'success' => ($res['errorMsg'])? false : true,
            'errorMsg' => ($res['errorMsg']) ? $res['errorMsg'] : ''
        ];
    }
}
