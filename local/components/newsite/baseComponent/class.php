<?

/**
 * @author Dmitry Sharyk
 * email: d.sharyk@gmail.com
 */
class CShBaseComponent extends CBitrixComponent
{

    //метоод дожнен быть обьявлен если в компаенте используется постраничка
    function updateNavNum()
    {

    }

    protected static $wrappedParamsModel;
    protected $wrappedParams;

    public function onPrepareComponentParams($params)
    {

        $model = self::getWrappedParamsModel();
        $paramsWrapper = new \Newsite\Base\ParamsWrapper($model);
        $this->wrappedParams = $paramsWrapper->getWrappedParams($params);

        return $params;
    }


    public static function getWrappedParamsModel()
    {
        return static::$wrappedParamsModel;
    }

    /**
     * @param string|null $paramName
     * @return array|null
     */
    public function getWrappedParams($paramName = null) {

        if($paramName) {
            return $this->wrappedParams[$paramName];
        }

        return $this->wrappedParams;
    }

    protected static $iblockElemet;
    private $modulesDepending = [];

    function includeModules()
    {
        foreach ($this->modulesDepending as $module) {
            if (!Bitrix\Main\Loader::includeModule($module)) {
                throw new Bitrix\Main\SystemException("Module $module not install");
            }
        }

        return $this;
    }

    public function addModuleDepending($_module_name)
    {
        if (is_array($_module_name)) {
            $this->modulesDepending = array_unique(array_merge($_module_name));
        } else {
            if (!in_array($_module_name, $this->modulesDepending)) {
                $this->modulesDepending[] = $_module_name;
            }
        }

        return $this;
    }

    function GetResizedImages($imageID, $sizes = [])
    {

        if (!isset($this->arResult["IMAGES"][ $imageID ])) {
            $this->arResult["IMAGES"][ $imageID ] = CFile::GetPath($imageID);
            if (empty($this->arResult["IMAGES"][ $imageID ])) {
                unset($this->arResult["IMAGES"][ $imageID ]);
            }
        }

        $imageID = !isset($this->arResult["IMAGES"][ $imageID ]) ? 0 : $imageID;

        if (!$this->arResult["IMAGES"][ $imageID ]) {
            $this->arResult["IMAGES"][ $imageID ] = NO_IMAGE_SRC;
        }
        $result = [];
        if ($this->arResult["IMAGES"][ $imageID ]) {

            $result["ORIG"] = $this->arResult["IMAGES"][ $imageID ];
            $result["ORIG"] = file_exists($_SERVER["DOCUMENT_ROOT"] . $result["ORIG"]) ? $result["ORIG"] : NO_IMAGE_SRC;

            list($imgWidth, $imgHeight) = array_values(getimagesize($_SERVER["DOCUMENT_ROOT"] . $result["ORIG"]));

            foreach ($sizes as $code => $size) {

                $resizeParam = $size;

                if (!is_array($resizeParam)) {
                    $resizeParam = ["WIDTH" => $size, "HEIGHT" => 0];
                    if ($imgWidth < $imgHeight) {
                        $resizeParam = ["WIDTH" => 0, "HEIGHT" => $size];
                    }
                }

                $result[ $code ] = imageResize($resizeParam, $result["ORIG"]);
            }
        }

        return $result;
    }

    function GetElementList(
        $arSortElem = [],
        $arFilter = [],
        $group = false,
        $arNavParams = false,
        $arSelect = [],
        $return = "ITEMS"
    ) {

        $dbl = $this->GetIblockObj()->Getlist($arSortElem, $arFilter, $group, $arNavParams, $arSelect);

        while ($res = $dbl->GetNextElement()) {

            $this->getListElementFormater($res);

            $this->arResult[ $return ][ $res["ID"] ] = $res;
        }

        return $dbl;
    }

    function getListElementFormater(&$res)
    {
        if (!empty($res["IMAGES"])) {
            $this->arResult["IMAGES"] = array_merge((array)$this->arResult["IMAGES"], (array)$res["IMAGES"]);
        }

        if (!empty($res["IMAGE"])) {
            $this->arResult["IMAGES"] = array_merge((array)$this->arResult["IMAGES"], (array)$res["IMAGE"]);
        }
    }

    function GetSectionList($arSortElem = [], $arFilter = [], $arSelect = ["ID", "IBLOCK_ID"], $return = "SECTIONS")
    {
        $dbl = $this->GetIblockObj()->GetSectionList($arSortElem, $arFilter, $arSelect);

        while ($res = $dbl->GetNextSection()) {

            if (!empty($res["IMAGES"])) {
                $this->arResult["IMAGES"] = array_merge($this->arResult["IMAGES"], (array)$res["IMAGES"]);
            }
            $this->arResult[ $return ][ $res["ID"] ] = $res;
        }
    }

    /**
     *
     * @return \Sh\CIblock
     */
    function GetIblockObj()
    {

        CBitrixComponent::includeComponentClass("lib:iblock");

        if (!is_object(CShBaseComponent::$iblockElemet) || !(CShBaseComponent::$iblockElemet instanceof Sh\CIblock)) {
            CShBaseComponent::$iblockElemet = new \Sh\CIblock();
        }

        return CShBaseComponent::$iblockElemet;
    }

    function GetCollectedElement($arSelect, $saveKey = "LIBRARY")
    {
        $this->arResult[ $saveKey ] = $this->GetIblockObj()->GetCollectedElement($arSelect,
            $saveKey)->arResult[ $saveKey ];

        if (!empty($this->GetIblockObj()->arResult["IMAGES"])) {
            $this->arResult["IMAGES"] = array_merge((array)$this->arResult["IMAGES"],
                (array)$this->GetIblockObj()->arResult["IMAGES"]);
        }

        return $this;
    }

    function GetCollectedSection($arSelect, $saveKey = "LIBRARY_SECTION")
    {
        $this->arResult[ $saveKey ] = $this->GetIblockObj()->GetCollectedSection($arSelect,
            $saveKey)->arResult[ $saveKey ];

        if (!empty($this->GetIblockObj()->arResult["IMAGES"])) {
            $this->arResult["IMAGES"] = array_merge((array)$this->arResult["IMAGES"],
                (array)$this->GetIblockObj()->arResult["IMAGES"]);
        }

        return $this;
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

    protected function encodeParams()
    {
        return urlencode(base64_encode(gzcompress(json_encode($this->arParams))));
    }

    protected function decodeParams($encoded)
    {
        $this->arParams = json_decode(gzuncompress(base64_decode($encoded)), 1);
    }

    //флаг что выполнение началось внутри кэша нужна пергрузка компонента в дальне
    protected $cacheStart = false, $cacheIsRead = false, $readedCache = "";
    protected $cacheID = "";

    function getCacheID($additionalCacheID = false)
    {

        if (!empty($this->cacheID)) {
            return $this->cacheID;
        }
        $arParams = $this->arParams;
        sort($arParams);


        $this->cacheID = md5(serialize($arParams) . serialize($additionalCacheID));

        return $this->cacheID;
    }

    /**
     * Проверка что кэш включен
     * @return type
     */
    function smartCacheIsActive()
    {
        global $USER;

        return (!empty($this->arParams["SMARTCACHE"]) && $this->arParams["SMARTCACHE"] == "Y" && (!$USER->isAdmin() || (!empty($this->arParams["SMARTCACHE_ADMIN"]) && $this->arParams["SMARTCACHE_ADMIN"] == "Y")));
    }

    /**
     * Проверка внутри оператора кэша которая должна прервать выполнение заполнения кэша
     * @return boolean
     */
    public function checkSmartCacheStatus()
    {


        if ($this->smartCacheIsActive()) {

            $this->cacheStart = true;

            $this->cacheIsRead = $this->isFirstLoad() ? false : (!$this->isFirstLoad() ? true : $this->cacheIsRead);

            return $this->canLoadTemplate();
        }

        return true;
    }

    /**
     * Проверка перовй закгрузки  - компонент с такимм параметрами еще не разу не выполнялся либо истек кэш
     * @return type
     */
    function isFirstLoad()
    {
        //по файлу идет проверка выполнялся ли ранее компоенент
        //если не выполнялся  и разрешена первая загрузка - компопеннт отработает иначе отработает помере доскрола до компонента
        return (((!empty($this->arParams["FIRST_LOAD"]) && $this->arParams["FIRST_LOAD"] == "Y") || empty($this->arParams["AJAX_ID"])) || !empty($_REQUEST["bxajaxid"]) || !empty($_REQUEST["clear_cache"]));
    }

    //флаг, что кэш уже существует
    public function startSmartCache()
    {

        $this->cacheIsRead = $this->readedCache = false;

        if ($this->smartCacheIsActive() && file_exists($this->getCacheFilePath()) && empty($_REQUEST["clear_cache"])) {

            //чтение старого кэша
            if ((empty($this->arParams["SMARTCACHE_HIDE_RESULT"]) || $this->arParams["SMARTCACHE_HIDE_RESULT"] == "N")) {
                $this->readedCache = file_get_contents($this->getCacheFilePath());
            }

            $this->getSaveItemData();

            $this->cacheIsRead = true;
        }

        return true;
    }

    /**
     * Провверка нужно ли подключать шаблон
     * */
    function canLoadTemplate()
    {
        return !$this->cacheIsRead;
    }

    function getCacheFilePath()
    {
        return rtrim($_SERVER["DOCUMENT_ROOT"],
                "/") . "/bitrix/cache/html/{$this->getName()}/{$this->getTemplateName()}/" . substr($this->getCacheID(),
                0, 3) . "/" . substr($this->getCacheID(), 3, 3) . "/" . $this->getCacheID() . ".html";
    }

    protected function checkComponentRequest()
    {

        if (!empty($_REQUEST["bxajaxid"]) && ((!isset($this->arParams["SKIPAJAXCHECK"]) || $this->arParams["SKIPAJAXCHECK"] != "Y") && (empty($this->arParams["AJAX_ID"]) || $this->arParams["AJAX_ID"] != $_REQUEST["bxajaxid"]))) {

            global $NavNum;
            $NavNum += $this->updateNavNum();

            return false;
        }

        return true;
    }

    protected $saveTemplateData;

    private function saveItemData()
    {
        if (!empty($this->saveTemplateData)) {
            $cachePath = $this->getCacheFilePath() . ".data";
            file_put_contents($cachePath, serialize($this->saveTemplateData));
        }
    }

    private function getSaveItemData()
    {
        $cachePath = $this->getCacheFilePath() . ".data";
        if (file_exists($cachePath)) {
            $this->saveTemplateData = unserialize(file_get_contents($cachePath));
        }
    }

    public function setSaveDataField($fieldName, $value = "")
    {
        if (empty($fieldName)) {
            return false;
        }
        $this->saveTemplateData[ $fieldName ] = $value;

        return true;
    }

    public function setSaveData(array $fields)
    {
        $this->saveTemplateData = $fields;

        return true;
    }

    public function getSaveDataField($fieldName)
    {
        return $this->saveTemplateData[ $fieldName ];
    }

    public function getSaveData()
    {
        return $this->saveTemplateData;
    }

    public function afterPrintFromSmartCache()
    {

    }

    public function executeComponent()
    {
        /* @var $APPLICATION CMain */
        global $APPLICATION;

        if (!$this->checkComponentRequest()) {
            return false;
        }


        if ($this->smartCacheIsActive() && $_REQUEST["SMARTCAHEID"]) {
            $this->decodeParams($_REQUEST["SMARTCAHEID"]);
        }

        if ($this->smartCacheIsActive()) {

            ob_start();
            $result = $this->__includeComponent();
            $content = ob_get_clean();


            if (!empty($_REQUEST["bxajaxid"])) {
                $APPLICATION->RestartBuffer();
            }

            $cachePath = $this->getCacheFilePath();


            if ($this->cacheIsRead && $this->cacheStart && !empty($this->arParams["AJAX_ID"])) {

                $this->readedCache = $this->readedCache . "<a  class=\"js_smart_cache_load\" href=\"{$APPLICATION->GetCurPageParam("SMARTCAHEID={$this->encodeParams()}", ["SMARTCAHE"], false)}\"></a>";
                $this->afterPrintFromSmartCache();
            } elseif (!$this->cacheIsRead && $this->canLoadTemplate()) {


                $this->readedCache = $content;
                CheckDirPath($cachePath);

                $this->saveItemData();

                file_put_contents($cachePath, $this->readedCache);
            } else {
                $this->afterPrintFromSmartCache();
            }

            echo $this->readedCache;
        } else {
            $result = $this->__includeComponent();
        }

        //todo remove onLocalRedirect

        return true;
    }

    static public function addSmartCacheParam(&$arParam)
    {

        $smartParams = [
            "SKIPAJAXCHECK"          => [
                "PARENT" => "BASE",
                "NAME"   => "При аяксе выполнять компонент (по дефолту на компонентах нс выполняться не будет)",
                "TYPE"   => "CHECKBOX",
            ],
            "SMARTCACHE"             => [
                "PARENT" => "BASE",
                "NAME"   => "Постепенная подгрузка",
                "TYPE"   => "CHECKBOX",
            ],
            "FIRST_LOAD"             => [
                "PARENT" => "BASE",
                "NAME"   => "Если нет кэша отработать компонент сразу",
                "TYPE"   => "CHECKBOX",
            ],
            "SMARTCACHE_ADMIN"       => [
                "PARENT" => "BASE",
                "NAME"   => "Считать кэш для админа",
                "TYPE"   => "CHECKBOX",
            ],
            "SMARTCACHE_HIDE_RESULT" => [
                "PARENT" => "BASE",
                "NAME"   => "Не показывать предыдущий результат",
                "TYPE"   => "CHECKBOX",
            ],
            "CACHE_TIME"             => ["DEFAULT" => 3600],
            "AJAX_MODE"              => [],
        ];

        $arParam["PARAMETERS"] = array_merge((array)$arParam["PARAMETERS"], $smartParams);
    }

    /**
     * возвращает сгенерированные SEO для элемента на основе настроек инфоблока
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    function GetElementSeoPropsValues($defaultTitle, $iblockID)
    {
        if (empty($this->arResult["ELEMENT_ID"])) {
            return [];
        }
        \Bitrix\Main\Loader::includeModule('iblock');
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockID, $this->arResult["ELEMENT_ID"]);
        $vals = $ipropValues->getValues();
        $res = [
            'TITLE'         => empty($vals['ELEMENT_META_TITLE']) ? $defaultTitle : $vals['ELEMENT_META_TITLE'],
            'KEYWORDS'      => $vals['ELEMENT_META_KEYWORDS'],
            'DESCRIPTION'   => strip_tags(html_entity_decode(html_entity_decode($vals['ELEMENT_META_DESCRIPTION']))),
            'H1'            => empty($vals['ELEMENT_PAGE_TITLE']) ? $defaultTitle : $vals['ELEMENT_PAGE_TITLE'],
            'PREVIEW_ALT'   => $vals['ELEMENT_PREVIEW_PICTURE_FILE_ALT'],
            'PREVIEW_TITLE' => $vals['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'],
            'DETAIL_ALT'    => $vals['ELEMENT_DETAIL_PICTURE_FILE_ALT'],
            'DETAIL_TITLE'  => $vals['ELEMENT_DETAIL_PICTURE_FILE_TITLE'],
        ];

        $this->SetSeoMetaTags($res);

        return $res;
    }

    function GetSeoPFilter($vals)
    {
        $filter_selected_options = [];
        if (!empty($this->arResult["FILTER_SELECTED"])) {
            foreach ($this->arResult["FILTER"] as $prop) {
                if ($prop['PROPERTY_TYPE'] != 'N' && $selectedValues = $this->arResult["FILTER_SELECTED"][$prop['ID']]) {
                    $filter_selected_options[] = implode('/', array_map(function ($value) {
                            return mb_strtolower($value['VALUE']);
                        }, $selectedValues)) . ' ' . mb_strtolower($prop['NAME']);
                }
            }
        }

        return str_replace_json('[filter_selected_options]', implode(', ', $filter_selected_options), $vals);

    }
    /**
     * возвращает сгенерированные SEO для раздела на основе настроек инфоблока
     * @param $iblock_id
     * @param $element_id
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    function GetSectionSeoPropsValues($defaultTitle, $iblockID)
    {
        if (empty($this->arResult["CUR_SECTION"])) {
            return [];
        }
        \Bitrix\Main\Loader::includeModule('iblock');
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblockID,
            $this->arResult["CUR_SECTION"]["ID"]);
        $vals = $ipropValues->getValues();
        $res = [
            'TITLE'         => empty($vals['SECTION_META_TITLE']) ? $defaultTitle : $vals['SECTION_META_TITLE'],
            'KEYWORDS'      => $vals['SECTION_META_KEYWORDS'],
            'DESCRIPTION'   => strip_tags(html_entity_decode(html_entity_decode($vals['SECTION_META_DESCRIPTION']))),
            'H1'            => empty($vals['SECTION_PAGE_TITLE']) ? $defaultTitle : $vals['SECTION_PAGE_TITLE'],
            'PREVIEW_ALT'   => $vals['SECTION_PICTURE_FILE_ALT'],
            'PREVIEW_TITLE' => $vals['SECTION_PICTURE_FILE_TITLE'],
            'DETAIL_ALT'    => $vals['SECTION_DETAIL_PICTURE_FILE_ALT'],
            'DETAIL_TITLE'  => $vals['SECTION_DETAIL_PICTURE_FILE_TITLE'],
        ];

        $this->SetSeoMetaTags($this->GetSeoPFilter($res));

        return $res;
    }

    /**
     * устанавливает SEO метатеги
     * @param $values
     */
    function SetSeoMetaTags($values)
    {
        global $APPLICATION;

        if (!empty($values['TITLE'])) {
            $APPLICATION->SetPageProperty("title", $values['TITLE']);
        }

        if (!empty($values['KEYWORDS'])) {
            $APPLICATION->SetPageProperty("keywords", strip_tags($values['KEYWORDS']));
        }
        if (!empty($values['DESCRIPTION'])) {
            $APPLICATION->SetPageProperty("description", strip_tags($values['DESCRIPTION']));
        }
        if (!empty($values['H1'])) {
            $APPLICATION->SetPageProperty("title_custom", strip_tags($values['H1']));
        }
    }

    function trySetFilterCode($filterID, $filterCode, $unic = "")
    {

        if (is_array($filterID)) {
            $filterID = $this->HashEncode($filterID);
        }

        if (is_numeric($filterID)) {

            $filterCode = CUtil::translit(trim($filterCode), "ru", [
                "max_len"               => 240,
                "change_case"           => 'L', // 'L' - toLower, 'U' - toUpper, false - do not change
                "replace_space"         => '-',
                "replace_other"         => '-',
                "delete_repeat_replace" => true,
                "safe_chars"            => '',
            ]);


            $unic = CUtil::translit(trim($unic), "ru", [
                "max_len"               => 240,
                "change_case"           => 'L', // 'L' - toLower, 'U' - toUpper, false - do not change
                "replace_space"         => '-',
                "replace_other"         => '-',
                "delete_repeat_replace" => true,
                "safe_chars"            => '',
            ]);

            $opfilterHash = new CPFilterHash();

            if (!$opfilterHash->GetList(false, ["=CODE" => $filterCode])->SelectedRowsCount()) {
                $opfilterHash->Update($filterID, ["CODE" => $filterCode]);
                $filterID = $filterCode;
            } elseif ($unic && !$opfilterHash->GetList(false,
                    ["=CODE" => $filterCode . "_" . $unic])->SelectedRowsCount()
            ) {
                $opfilterHash->Update($filterID, ["CODE" => $filterCode . "_" . $unic]);
                $filterID = $filterCode . "_" . $unic;
            }
        }

        return $filterID;
    }

    static function getSinglePropsDescription($iblockID)
    {
        if (empty($iblockID)) {
            return [];
        }

        static $result = [];

        if (!empty($result[ $iblockID ])) {
            return $result[ $iblockID ];
        }


        $dbl = Bitrix\Iblock\PropertyTable::query()
            ->setSelect(["ID", "MULTIPLE", "CODE", "PROPERTY_TYPE", "IBLOCK_ID", "WITH_DESCRIPTION"])
            ->setFilter(["=IBLOCK_ID" => $iblockID, "!=MULTIPLE" => "Y"])
            ->exec();
        $singlePrams = ["IBLOCK_ELEMENT_ID" => ["TYPE" => "N", "PRIMARY" => true]];

        while ($res = $dbl->fetch()) {
            $param = [
                "PRIMARY" => false,
                "TYPE"    => ($res["PROPERTY_TYPE"] == "N" ? "NF" : $res["PROPERTY_TYPE"]),
                "ALIAS"   => $res["CODE"],
            ];
            $singlePrams["PROPERTY_{$res["ID"]}"] = $param;

            if ($res["WITH_DESCRIPTION"] == "Y") {
                $param = [
                    "PRIMARY" => false,
                    "TYPE"    => ($res["PROPERTY_TYPE"] == "N" ? "NF" : $res["PROPERTY_TYPE"]),
                    "ALIAS"   => $res["CODE"] . "_DESCRIPTION",
                ];
                $singlePrams["DESCRIPTION_{$res["ID"]}"] = $param;
            }
        }

        $result[ $iblockID ] = $singlePrams;

        return $result[ $iblockID ];
    }

    public function GetPageNavString(\Bitrix\Main\UI\PageNavigation $nav, $template = "")
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;

        ob_start();

        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation", $template, [
            "NAV_OBJECT" => $nav,
            "SEF_MODE"   => "N",
            "SHOW_COUNT" => "N",
            "BASE_LINK"  => $APPLICATION->GetCurPageParam('', $this->arResult["KILLARG"]),

        ], false
        );

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

}
