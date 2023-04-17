<?

namespace Ml\Soap\Wsdl\Action;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Security\Random;
use Bitrix\Main\UserTable;

class User
{
    private static $error = '';

    public static function Add(array $request)
    {
        $arReq = [
            'guid', 'email', 'name'
        ];
        if (self::CheckRequired($arReq, $request)) {
            $userId = self::CreateUser($request['guid'], $request['email']);
            if ($userId > 0) {
                self::CreateRequisits($request, $userId);
            }
        }

        return [
            'errorMsg' => self::$error
        ];
    }

    private static function CheckRequired(array $fields, array $request)
    {
        $isSuccess = true;
        foreach ($fields as $arField) {
            if (empty($request[$arField])) {
                self::$error .= 'Не заполнено обязательное поле ' . $arField;
                $isSuccess = false;
            }
        }
        return $isSuccess;
    }

    private static function CreateUser(string $guid, string $email)
    {
        $iterator = UserTable::getList([
            'select' => ['ID'],
            'filter' => ['=XML_ID' => $guid]
        ]);

        $userId = 0;
        if ($arUser = $iterator->fetch()) {
            $userId = $arUser['ID'];

            $user = new \CUser();
            $user->Update($arUser['ID'], ['EMAIL' => $email]);

        } else {
            $user = new \CUser();

            $password = Random::getString(10);
            $arLoad = [
                'NAME' => $email,
                'EMAIL' => $email,
                "ACTIVE" => 'N',
                "GROUP_ID" => [B2B_GROUP],
                'LOGIN' => $email,
                "PASSWORD" => $password,
                "CONFIRM_PASSWORD" => $password,
                'XML_ID' => $guid,
            ];
            if ($userCreateId = $user->Add($arLoad)) {
                $userId = $userCreateId;
            } else {
                self::$error = $user->LAST_ERROR;
            }
        }

        return $userId;
    }

    private static function CreateRequisits(array $request, int $userId)
    {
        $arLoad = [
            'NAME' => $request['name'],
            'IBLOCK_ID' => IBLOCK_ID_B2BPROFILE,
            'ACTIVE' => 'Y',
            'XML_ID' => $request['guid']
        ];

        $arProps = [
            'IS_MAIN' => 1,
            'USER' => $userId,
            'PHONE' => $request['phone'],
            'EMAIL' => $request['email'],
            'UNP' => $request['unp'],
            'KPP' => $request['kpp'],
            'CITY_REGISTER' => $request['city_register'],
            'PARTNER' => $request['partner'],
            'ADDITIONAL' => $request['add_info'],

            'DOCUMENT_NUMBER' => $request['document_number'],
            'PERIOD' => $request['document_period'],

            'MANAGER' => $request['manager_fio'],
            'MANAGER_EMAIL' => $request['manager_email'],
            'MANAGER_PHONE' => $request['manager_phone'],

            'ACTUAL_ADDRESS' => [$request['address_actual']],
            'LAW_ADDRESS' => $request['address_law'],
            'POST_ADDRESS' => $request['address_post'],

            //'PRICE_NAME' => $request['price_name'],
        ];

        $iterator = ElementTable::getList([
            'select' => ['ID'],
            'filter' => ['IBLOCK_ID' => IBLOCK_ID_B2BPROFILE, 'XML_ID' => $request['guid']]
        ]);
        $el = new \CIBlockElement();
        if ($arItem = $iterator->fetch()) {
            if($id = $el->Update($arItem['ID'], $arLoad)){
                \CIBlockElement::SetPropertyValuesEx($arItem['ID'], IBLOCK_ID_B2BPROFILE, $arProps);
            }else{
                self::$error = $el->LAST_ERROR;
            }
        } else {
            if ($elId = $el->Add($arLoad)){
                \CIBlockElement::SetPropertyValuesEx($elId, IBLOCK_ID_B2BPROFILE, $arProps);
            } else {
                self::$error = $el->LAST_ERROR;
            }
        }
    }
}