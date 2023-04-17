<?

class UserController extends \Ml\Soap\Module
{
    public static function Add($request)
    {
        $res = \Ml\Soap\Wsdl\Action\User::Add($request);
        return [
            'success' => ($res['errorMsg'])? false : true,
            'errorMsg' => ($res['errorMsg']) ? $res['errorMsg'] : ''
        ];
    }
}
