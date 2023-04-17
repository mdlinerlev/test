<?
class CCatalogProductMenu extends CBitrixComponent
{

    static $resultStaic = [];

    function onPrepareComponentParams($initParams)
    {

        $initParams["AJAX_ID"] = "menu_" . CAjax::GetComponentID($this->componentName, $this->componentTemplate,
                $initParams["AJAX_OPTION_ADDITIONAL"]);
        $initParams["AJAX_MODE"] = "Y";

        return parent::onPrepareComponentParams($initParams);
    }

    function initParams()
    {

        if(!CModule::IncludeModule("iblock"))
            return false;


        //инициализирую если раньше это не делалось
        if (!$this->getName()) {
            $arParams = $this->arParams;
            $this->initComponent("newsite:catalog.menu");
            $this->arParams = $arParams;
        }


        $this->arParams["CACHE_TYPE"] = empty($this->arParams["CACHE_TYPE"]) ? "A" : $this->arParams["CACHE_TYPE"];
        $this->arParams["CACHE_TIME"] = (!intval($this->arParams["CACHE_TIME"])) ? 86000 : $this->arParams["CACHE_TIME"];
        if ($this->arParams["CACHE_TYPE"] == "N" || ($this->arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main",
                    "component_cache_on", "Y") != "Y")
        ) {
            $this->arParams["CACHE_TIME"] = 0;
        }

        $this->arParams["IBLOCK_ID"] = empty($this->arParams["IBLOCK_ID"]) ? CATALOG_MENU_IBLOCK_ID : $this->arParams["IBLOCK_ID"];
        $this->arParams["INDEX_ID"] = empty($this->arParams["INDEX_ID"]) ? CATALOG_MENU_IBLOCK_ID : $this->arParams["IBLOCK_ID"];

        $this->arParams["GET_LEVELS"] = empty($this->arParams["GET_LEVELS"]) ? false : $this->arParams["GET_LEVELS"];

        $this->arResult["IMAGES"] = [];

        $this->arParams["SAVE_CODE"] = 0;

        return $this;
    }

    function GetMenu()
    {

        $this->arResult["MENU"] = [];

        $arFilter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "ACTIVE" => "Y",
        ];

        $arSelect = [
            "ID",
            "NAME",
            "CODE",
            "SORT",
            "PICTURE",
            "IBLOCK_SECTION_ID",
            "UF_LINK",
            "UF_TYPE",
            "UF_AUTH",
            "UF_NOFOLOW"
        ];

        $dbl = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter, false, $arSelect);

        while ($res = $dbl->GetNext()) {

            $res["SECTION_ID"] = intval($res["IBLOCK_SECTION_ID"]);

            if (!empty($res["PICTURE"])) {
                $this->arResult["IMAGES"] = array_merge($this->arResult["IMAGES"], (array)$res["PICTURE"]);
            }

            $this->arResult["MENU"][ $res["SECTION_ID"] ][ $res["ID"] ] = $res;
            $this->arResult["MENU_PARENT"][ $res["ID"] ] = $res["SECTION_ID"];

        }

        return $this;
    }

    function GetSelected($index = 0)
    {

        if (empty($this->arResult["MENU"][ $index ]) || (empty($this->arParams["SECTION_CODE"]) && empty($this->arParams["SECTION_ID"]))) {
            return false;
        }


        foreach ($this->arResult["MENU"][ $index ] as $key => $value) {

            if ($this->arParams["SECTION_ID"] == $value["ID"] || (empty($this->arParams["SECTION_ID"]) && $value["CODE"] && $this->arParams["SECTION_CODE"] == $value["CODE"]) || $this->GetSelected($value["ID"])) {
                $this->arResult["MENU"][ $index ][ $key ]["SELECTED"] = 1;
                $this->arResult["CHAIN"][ $index ] = $value["ID"];
                $this->arResult["CHAIN_CODE"][$index] = $value["CODE"];
                if ($index == 0) {
                    $this->arResult["CHAIN"] = array_reverse($this->arResult["CHAIN"], true);
                    $this->arResult["CHAIN_CODE"] = array_reverse($this->arResult["CHAIN_CODE"], true);
                }

                return true;
            }
        }

        return false;
    }


    /**
     * Поиск текущего
     * @return \CCatalogProductMenu
     */
    function FindSelected()
    {
        $this->arParams["SECTION_CODE"] = !empty($arVariables["SECTION_CODE"]) ? $arVariables["SECTION_CODE"] :  "";
        $this->arParams["SECTION_ID"] = !empty($arVariables["SECTION_ID"]) ? intval($arVariables["SECTION_ID"]) : 0;

        $this->arResult["CHAIN"] = $this->arResult["CHAIN_CODE"] = [];

        $this->GetSelected();

        if (!empty($this->arParams["SECTION_CODE"]) && empty($this->arResult["CHAIN"])) {


            $this->arParams["SECTION_CODE"] = explode("/", $this->arParams["SECTION_CODE"]);
            array_pop($this->arParams["SECTION_CODE"]);


            $this->arParams["SECTION_CODE"] = implode("/", $this->arParams["SECTION_CODE"]);
            $this->GetSelected();
        }

        return $this;
    }


    /**
     * Устновка результата данных меню
     */
    function SetResult()
    {
        CCatalogProductMenu::$resultStaic[ $this->arParams["IBLOCK_ID"] ][ $this->arParams["SAVE_CODE"] ] = $this->arResult;
    }

    function getCacheID($additionalCacheID = false)
    {
        return md5("index_id" . $this->arParams["INDEX_ID"] . "sect_code_" . $this->arParams["SAVE_CODE"] . "iblock_" . $this->arParams["IBLOCK_ID"] . serialize($additionalCacheID));
    }

    /**
     * Получение данных результата данных меню
     */
    function GetResult()
    {

        if (empty(CCatalogProductMenu::$resultStaic[ $this->arParams["IBLOCK_ID"] ][ $this->arParams["SAVE_CODE"] ])) {
            $this->arResult = ["IMAGES" => []];
            $cachePath = "/" . SITE_ID . "/newsite/catalog.menu/";

            if ($this->startResultCache(false, false, $cachePath)) {


                $this->GetMenu()->GetImages();


                $this->EndResultCache();
            }

            $this->FindSelected()->SetResult();
        }

        $this->arResult = CCatalogProductMenu::$resultStaic[ $this->arParams["IBLOCK_ID"] ][ $this->arParams["SAVE_CODE"] ];

        return $this->arResult;
    }



    /**
     * Получение картинок
     * @return CShBaseComponent
     */
    function GetImages()
    {
        if (!empty($this->arResult["IMAGES"])) {
            $dbl = CFile::GetList([], ["@ID" => implode(",", array_filter($this->arResult["IMAGES"], "intval"))]);

            $uploadDir = COption::GetOptionString("main", "upload_dir", "upload");
            while ($res = $dbl->fetch()) {

                $this->arResult["IMAGES"][ $res["ID"] ] = "/$uploadDir/" . $res["SUBDIR"] . "/" . $res["FILE_NAME"];

                if (!empty($res["DESCRIPTION"])) {
                    $this->arResult["IMAGES"][ $res["ID"] . "_DESCR" ] = $res["DESCRIPTION"];
                }
            }

            $this->arResult["IMAGES"] = array_filter($this->arResult["IMAGES"], function ($a) {
                return !is_numeric($a);
            });
        }


        $this->arResult["IMAGES"][0] = NO_IMAGE_SRC;

        return $this;
    }
}
