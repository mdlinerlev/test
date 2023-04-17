<?

namespace Ml\Soap\Wsdl;

use Ml\Soap\Wsdl\Action\User;

class Server{

    private \soap_server $server;

    public function __construct()
    {
        $server = new \soap_server();
        $server->decode_utf8 = false;
        $server->soap_defencoding = 'UTF-8';
        $server->configureWSDL('webservice', 'urn:webservice');
        $server->wsdl->schemaTargetNamespace = 'urn:webservice';

        $this->server = $server;
    }

    private function SetShema(){
        $complexType = Type::GetComplexType();
        foreach ($complexType as $arType) {
            $this->server->wsdl->addComplexType(
                $arType['name'],
                $arType['typeClass'],
                $arType['phpType'],
                $arType['compositor'],
                $arType['restrictionBase'],
                $arType['actions']
            );
        }

        $registerType =  Type::GetRegisterType();
        foreach ($registerType as $arRegtype) {
            $this->server->register(
                $arRegtype['name'],
                $arRegtype['query'],
                $arRegtype['return'],
                $arRegtype['namespace'],
                $arRegtype['soapaction'],
                $arRegtype['style'],
                $arRegtype['use'],
                $arRegtype['documentation']
            );
        }
    }

    public function run(){
        self::SetShema();
        $this->server->service(file_get_contents("php://input"));
    }
}
