<?

class OrderController extends \Ml\Soap\Module {
    public static function Update(array $request){
        $res = \Ml\Soap\Wsdl\Action\Order::Update($request);
        return [
            'success' => ($res['errorMsg'])? false : true,
            'errorMsg' => ($res['errorMsg']) ? $res['errorMsg'] : ''
        ];
    }
}
