<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Ml\Main\Map\MapTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class MedialineMainMap extends CBitrixComponent
{
    private $_request;
    private $data_table;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (!Loader::includeModule('iblock') || !Loader::includeModule('sale')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }
    /**
     * Проверка наличия модулей таблиц для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkTablets(){
        if (!MapTable::getEntity()->getConnection()->isTableExists(MapTable::getTableName())) {
            throw new \Exception('Нет таблицы ml_main_map');
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        // тут пишем логику обработки параметров, дополнение параметрами по умолчанию
        // и прочие нужные вещи
        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent()
    {
        $this->_checkModules();
        $this->_checkTablets();

        $this->_request = Application::getInstance()->getContext()->getRequest();
        $ob_map = MapTable::getList([
            'order'=>["DEPTH_LEVEL"=>"ASC"]
        ]);
        $this->data_table = [];
        while($fields = $ob_map->fetch()){

    /*        if(strpos($fields["URL"],"catalog")===false){
                continue;
            }*/

            $param_key = "URL".str_replace("/","_",$fields["URL"]);
            if($this->arParams[$param_key]){
                $fields["NAME"] = $this->arParams[$param_key];
            }
            $this->data_table[$fields["URL"]] = $fields;
        }

        $res = [];
        foreach ($this->data_table as $url=>$fields){
            if($fields["DEPTH_LEVEL"]<2){
                $res[$url] = $fields;
                continue;
            }

            $u = explode('/',$url);

            if ($fields["DEPTH_LEVEL"]==2){
                $res["/$u[1]/"]["CHILD"][$url] = $fields;
            }elseif ($fields["DEPTH_LEVEL"]==3){
                $res["/$u[1]/"]["CHILD"]["/$u[1]/$u[2]/"]["CHILD"][$url] = $fields;
            }elseif ($fields["DEPTH_LEVEL"]==4){
                $res["/$u[1]/"]["CHILD"]["/$u[1]/$u[2]/"]["CHILD"]["/$u[1]/$u[2]/$u[3]/"]["CHILD"][$url] = $fields;
            }elseif ($fields["DEPTH_LEVEL"]==5){
                $res["/$u[1]/"]["CHILD"]["/$u[1]/$u[2]/"]["CHILD"]["/$u[1]/$u[2]/$u[3]/"]["CHILD"]["/$u[1]/$u[2]/$u[3]/$u[4]/"]["CHILD"][$url] = $fields;
            }
        }
        $this->arResult['MAPS'] = $res;

        $this->includeComponentTemplate();
    }
}
