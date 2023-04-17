<?php
/**
 * User: sasha
 * Date: 03.08.18
 * Time: 16:44
 */
namespace Newsite\Sale;

use
    Bitrix\Sale\Result,
    Bitrix\Sale\ResultError,
    Bitrix\Sale\ResultWarning,
    Bitrix\Sale\Internals\Input,
    Bitrix\Main\Localization\Loc;


class PropertyValue extends \Bitrix\Sale\PropertyValue
{

    public function getPropertyByCode($code)
    {
        return $this->getProperty()[$code] ?: "";
    }

    public function checkErrors()
    {
        $result = new Result();
        $resultItemProp = $this->checkValue( $this->getField("CODE"), $this->getValue() );
        if (!$resultItemProp->isSuccess()) {
            $result->addErrors( $resultItemProp->getErrors() );
        }
        $resultItemProp = $this->checkRequiredValue( $this->getField("CODE"), $this->getValue() );
        if (!$resultItemProp->isSuccess()) {
            $result->addErrors( $resultItemProp->getErrors() );
        }

        return $result;
    }


    public function isCheckUser($field, $value) {
        if( empty( $field ) && empty( $value ) ) {
            return false;
        }
        return \Bitrix\Main\UserTable::getList(array(
            'filter'    => array($field => $value),
            'select'    => array('ID'),
            'limit'     => 1
        ))->fetch();
    }

    public function checkValue($key, $value)
    {
        $errorsList = [];
        $result = new Result();
        $property = $this->getProperty();

        if ($property['TYPE'] == "STRING" && ((int)$property['MAXLENGTH'] <= 0)) {
            $property['MAXLENGTH'] = 500;
        }
        $error = Input\Manager::getError($property, $value);

        if (!is_array($error)) {
            $error = [$error];
        }

        foreach ($error as &$message) {
            $message = Loc::getMessage(
                "SALE_PROPERTY_ERROR",
                ["#PROPERTY_NAME#" => $property['NAME'], "#ERROR_MESSAGE#" => $message]
            );
        }

        if (!is_array($value) && strval(trim($value)) != "" && $property['CODE'] == 'EMAIL' && !check_email($value))
        {
            $error[$property['CODE']] = 'E-mail введён некорректно';
        } elseif($property['CODE'] == 'EMAIL' && !empty($this->arResult["ERRORS"]["REGISTERED"])) {
            $error[$property['CODE']] = $this->arResult["ERRORS"]["REGISTERED"];
        }


        foreach ($error as $e) {
            if (!empty($e) && is_array($e)) {
                foreach ($e as $errorMsg) {
                    if (isset($errorsList[ $property['ID'] ]) && in_array($errorMsg, $errorsList[ $property['ID'] ])) {
                        continue;
                    }

                    $result->addError(new ResultError($errorMsg, "PROPERTIES[$key]"));
                    $result->addError(new ResultWarning($errorMsg, "PROPERTIES[$key]"));

                    $errorsList[ $property['ID'] ][] = $errorMsg;
                }
            } else {
                if (isset($errorsList[ $property['ID'] ]) && in_array($e, $errorsList[ $property['ID'] ])) {
                    continue;
                }

                $result->addError(new ResultError($e, "PROPERTIES[$key]"));
                $result->addError(new ResultWarning($e, "PROPERTIES[$key]"));

                $errorsList[ $property['ID'] ][] = $e;
            }
        }

        return $result;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return Result
     * @throws \Bitrix\Main\SystemException
     */
    public function checkRequiredValue($key, $value)
    {
        $errorsList = [];
        $result = new Result();
        $property = $this->getProperty();


        $error = Input\Manager::getRequiredError($property, $value);

        foreach ($error as $e) {
            if (!empty($e) && is_array($e)) {
                foreach ($e as $errorMsg) {
                    if (isset($errorsList[ $property['ID'] ]) && in_array($errorMsg, $errorsList[ $property['ID'] ])) {
                        continue;
                    }

                    $result->addError(new ResultError(Loc::getMessage('LABEL') . $errorMsg, "PROPERTIES[" . $key . "]"));

                    $errorsList[ $property['ID'] ][] = $errorMsg;
                }
            } else {
                if (isset($errorsList[ $property['ID'] ]) && in_array($e, $errorsList[ $property['ID'] ])) {
                    continue;
                }

                $result->addError(new ResultError(Loc::getMessage('LABEL') . $e, "PROPERTIES[$key]"));

                $errorsList[ $property['ID'] ][] = $e;
            }
        }

        return $result;
    }
}