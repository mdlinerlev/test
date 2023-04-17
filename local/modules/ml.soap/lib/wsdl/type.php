<?

namespace Ml\Soap\Wsdl;

class Type extends Normalizer
{
    public static function GetComplexType()
    {
        $data = [];
        /*user*/
        $data[] = self::FormatComlexType(
            'userAddAction',
            'complexType',
            'struct',
            'all',
            '',
            [
                'guid' => ['name' => 'guid', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'name' => ['name' => 'name', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'phone' => ['name' => 'phone', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'email' => ['name' => 'email', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'unp' => ['name' => 'unp', 'type' => 'xsd:number', 'minOccurs' => 1, 'maxOccurs' => 1],
                'kpp' => ['name' => 'kpp', 'type' => 'xsd:number', 'minOccurs' => 1, 'maxOccurs' => 1],
                'city_register' => ['name' => 'city_register', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'partner' => ['name' => 'partner', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'add_info' => ['name' => 'add_info', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],

                //'price_name' => ['name' => 'price_name', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],

                'document_number' => ['name' => 'document_number', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'document_period' => ['name' => 'document_period', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],

                'manager_fio' => ['name' => 'manager_fio', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'manager_email' => ['name' => 'manager_email', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'manager_phone' => ['name' => 'manager_phone', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],

                'address_actual' => ['name' => 'address_actual', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'address_law' => ['name' => 'address_law', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'address_post' => ['name' => 'address_post', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
            ]
        );
        /*order*/
        $data[] = self::FormatComlexType(
            'orderUpdateAction',
            'complexType',
            'struct',
            'all',
            '',
            [
                'site_id' => ['name' => 'site_id', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'client_guid' => ['name' => 'guid', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'number_1c' => ['name' => 'number_1c', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'order_date' => ['name' => 'order_date', 'type' => 'xsd:date', 'minOccurs' => 1, 'maxOccurs' => 1],
                'summ' => ['name' => 'summ', 'type' => 'xsd:number', 'minOccurs' => 1, 'maxOccurs' => 1],
                'pay' => ['name' => 'pay', 'type' => 'xsd:number', 'minOccurs' => 1, 'maxOccurs' => 1],
                'status' => ['name' => 'status', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'comment' => ['name' => 'comment', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'items_list' => ['name' => 'items_list', 'type' => 'xsd:ArrayOfUnits', 'minOccurs' => 1, 'maxOccurs' => 1],
            ]
        );
        $data[] = self::FormatComlexType(
          'ArrayOfUnits',
            'complexType',
            'struct',
            'all',
            '',
            [
                'unit' => ['name' => 'unit', 'type' => 'xsd:Unit', 'minOccurs' => 1, 'maxOccurs' => 'unbound'],
            ]
        );
        $data[] = self::FormatComlexType(
            'Unit',
            'complexType',
            'struct',
            'all',
            '',
            [
                'guid' => ['name' => 'guid', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'count' => ['name' => 'count', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'price_wat' => ['name' => 'price_wat', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'summ_wat' => ['name' => 'summ_wat', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'status' => ['name' => 'status', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
            ] 
        );
        /*products*/
        $data[] = self::FormatComlexType(
            'productUpdateAction',
            'complexType',
            'struct',
            'all',
            '',
            [
                'products_list' => ['name' => 'products_list', 'type' => 'xsd:ArrayOfProducts', 'minOccurs' => 1, 'maxOccurs' => 1],
            ]
        );
        $data[] = self::FormatComlexType(
            'ArrayOfProducts',
            'complexType',
            'struct',
            'all',
            '',
            [
                'product' => ['name' => 'product', 'type' => 'xsd:Product', 'minOccurs' => 1, 'maxOccurs' => 'unbound'],
            ]
        );
        $data[] = self::FormatComlexType(
            'Product',
            'complexType',
            'struct',
            'all',
            '',
            [
                'guid' => ['name' => 'guid', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
                'count' => ['name' => 'count', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1]
            ]
        );

        /*response*/
        $data[] = self::FormatComlexType(
            'ActionResponse',
            'complexType',
            'struct',
            'all',
            '',
            [
                'success' => ['name' => 'success', 'type' => 'xsd:bool', 'minOccurs' => 1, 'maxOccurs' => 1],
                'errorMsg' => ['name' => 'errorMsg', 'type' => 'xsd:string', 'minOccurs' => 1, 'maxOccurs' => 1],
            ]
        );
        return $data;
    }

    public static function GetRegisterType()
    {
        $data = [];
        /*user*/
        $data[] = self::FormatRegisterType(
            'UserControllerAdd',
            ['query' => 'tns:userAddAction'],
            ['return' => 'tns:ActionResponse'],
            'urn:webservice',
            'urn:webservice#Add'
        );
        /*order*/
        $data[] = self::FormatRegisterType(
            'OrderControllerUpdate',
            ['query' => 'tns:orderUpdateAction'],
            ['return' => 'tns:ActionResponse'],
            'urn:webservice',
            'urn:webservice#Add'
        );
        $data[] = self::FormatRegisterType(
            'ProductsControllerUpdate',
            ['query' => 'tns:productUpdateAction'],
            ['return' => 'tns:ActionResponse'],
            'urn:webservice',
            'urn:webservice#Add'
        );
        return $data;
    }
}
