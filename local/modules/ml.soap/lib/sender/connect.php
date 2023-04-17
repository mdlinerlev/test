<?

namespace Ml\Soap\Sender;

use Ml\Soap\Module;
use stdClass;

class Connect extends Module
{
    private static $connection = false;
    private static \SoapClient $client;

    public static function Connect()
    {
        if(!self::$connection){
            $options = self::GetOptions();

            $auth = base64_encode($options['LOGIN'].":".$options['PASSWORD']);
            $opts = [
                'http' => [
                    'user_agent' => 'PHPSoapClient',
                    "header" => "Authorization: Basic $auth"
                ],
            ];
            $context = stream_context_create($opts);
            $connect = new \SoapClient(
                $options['URL'],
                [
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_BOTH,
                    'trace' => true,
                    'exceptions' => 1,
                    'connection_timeout' => 10,
                    'features' => SOAP_USE_XSI_ARRAY_TYPE,
                    'uri' => $options['URL']
                ]
            );

            self::$connection = (count($connect->__getFunctions()) > 0) ? true : false;
            self::$client = $connect;
        }
    }

    public static function Exec(string $method, array $data)
    {
        self::Connect();
        try {
            if(self::$connection){
                $result = (array)self::$client->__soapCall($method, [$method => $data]);
                file_put_contents(__DIR__.'/testSoap2.txt', print_r($result, 1), FILE_APPEND);
                return $result;
            }
        } catch (\SoapFault $e){
            file_put_contents(__DIR__.'/testSoap3.txt', print_r($e, 1), FILE_APPEND);
            self::addLog('soap_errors', $e);
        }
        return false;
    }
}