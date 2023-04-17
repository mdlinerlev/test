<?
class CCatalogRU {

    public $priceRetail;
    public $priceWholesale;

    public function __construct(){

        global $APPLICATION;
        CModule::IncludeModule('iblock');

        $this->priceRetail = 'catalog_PRICE_20';
        $this->priceWholesale = 'catalog_PRICE_19';
    }

    /**
     * Возвращает список инфоблоков
     * @return array
     */
    public function getCatalogIblock(){

        $arr = array();

        $cache = new CPHPCache;
        $cacheTime = 3600*24*30;

        // формируем идентификатор кеша в зависимости от всех параметров
        // которые могут повлиять на результирующий HTML
        $cacheID = 'info';
        $cachePath = '/iblock_id';

        if( $cache->InitCache($cacheTime, $cacheID, $cachePath) ){
            $vars = $cache->GetVars();
            $arr = $vars['ARR'];
        }
        else {
            if( CModule::IncludeModule('iblock') ){
                $res = CIBlock::GetList(
                    Array('SORT' => 'DESC'),
                    Array(
                        'TYPE'=>'catalog',
                        'SITE_ID'=>SITE_ID,
                        'ACTIVE'=>'Y',
                        "CNT_ACTIVE"=>"Y",
                    ), true
                );
                while($ar_res = $res->Fetch()){
                    $arr[$ar_res['CODE']] = $ar_res;
                }
            }

            $cache->StartDataCache($cacheTime, $cacheID, $cachePath);
            $cache->EndDataCache(array('ARR' => $arr));
        }

        return $arr;
    }

    /**
     * Возвращает список ID ИБ типа "catalog"
     * @return array
     */
    public function getCatalogIblockId(){

        $iblockArr = $this->getCatalogIblock();

        foreach( $iblockArr as $iblock ){
            $idArr[] = $iblock['ID'];
        }

        return $idArr;
    }

    /**
     * Возвращает параметры ИБ для включения в хлебную кроху
     * @return array
     */
    public function getIblockParams2Breadcrumbs($iblockId){
        $objCatalog = new CCatalogRU();
        $iblocks = $objCatalog->getCatalogIblock();

        foreach($iblocks as $iblock){

            if($iblock['ID'] == $iblockId){

                $params = array(
                    'NAME'=> $iblock['NAME'],
                    'CODE'=> $iblock['CODE'],
                    'SECTION_PAGE_URL'=> '/catalog/'.$iblock['CODE'].'/',
                    '~SECTION_PAGE_URL'=> '/catalog/'.$iblock['CODE'].'/',
                    'ID'=> $iblock['ID'],
                );
            }
        }

        return $params;
    }

    /**
     * Возвращает список маркеров товара
     * @param $item
     * @return string
     */
    public function getMarker($item, $place = 'list'){

        $content = "";
        $markers = array(
            'hit' => array(
                'MARKER' => 'hit',
                'PROP' => 'KHIT',
                'SIGN' => 'хит',
            ),
            'new' => array(
                'MARKER' => 'new',
                'PROP' => 'NOVINKA',
                'SIGN' => 'новинка',
            ),
            'discount' => array(
                'MARKER' => 'discount',
                'PROP' => 'RASPRODAZHA',
                'SIGN' => '%',
            ),
        );

        # список товаров
        if( $place == 'list' ){
            foreach( $markers as $marker => $params ){
                if( $item['PROPERTIES'][$params['PROP']]['VALUE'] == 'Да' ){
                    $content .= '<div class="catalog-item__badge catalog-item__badge--'.$params['MARKER'].'">'.$params['SIGN'].'</div>';
                }
            }
        }
        # карточка товара
        elseif( $place == 'detail' ){
            foreach( $markers as $marker => $params ){
                if( $item['PROPERTIES'][$params['PROP']]['VALUE'] == 'Да' ){
                    $content .= '<div class="product-badge-container__badge product-badge-container__badge--'.$params['MARKER'].'">'.$params['SIGN'].'</div>';
                }
            }
        }

        return $content;
    }

    /**
     * Вовзращает структуру
     * @return array
     */
    /*public function getSections(){

        $sections = array();

        # кешируем результат
        $phpCache = new CPHPCache;
        $cacheTime = 3600*30*24; # время кеширования - 1 месяц
        $cacheID = 'catalog_sections'; # формируем уникальный id кеша
        $cachePath = '/cached_sections'; # папка с кешем

        if($phpCache->InitCache($cacheTime, $cacheID, $cachePath)) {
            $sections = $phpCache->GetVars();
        }
        else {
            # помечаем кеш тегом, связанным с инфоблоком
            $tagCache = $GLOBALS['CACHE_MANAGER'];
            $tagCache->StartTagCache($cachePath);
            $tagCache->RegisterTag('iblock_id_'.IBLOCK_ID_CATALOG);
            $tagCache->EndTagCache();

            if( CModule::IncludeModule('iblock') ){

                $arFilter = Array('IBLOCK_ID'=>IBLOCK_ID_CATALOG, 'GLOBAL_ACTIVE'=>'Y',);
                $db_list = CIBlockSection::GetList(Array('name'=>'asc'), $arFilter, true, array('ID', 'NAME', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'UF_*', 'LEFT_MARGIN', 'RIGHT_MARGIN'));
                while($ar_result = $db_list->GetNext()){
                    $sections[$ar_result['ID']] = $ar_result;
                }
            }

            $phpCache->StartDataCache();
            $phpCache->EndDataCache($sections);
        }

        return $sections;
    }*/

    /**
     * Возвращает ссылки на варианты сортировки в каталоге
     * @return string
     */
    public function getSortingVariants(){

        global $APPLICATION;

        # розница
        $idPrice = 20;
        # опт
        if( USER_TYPE == 'wholesale' ){
            $idPrice = 19;
        }

        $arrSort = array(
            'PRICE' => array(
                'NAME' => 'По цене',
                'FIELD' => "catalog_PRICE_".$idPrice,
                'DIRECTION' => 'asc',
            ),
            'NAME' => array(
                'NAME' => 'По названию',
                'FIELD' => "name",
                'DIRECTION' => 'asc',
            )
        );

        $field = $_REQUEST['field']? htmlspecialchars($_REQUEST['field']) : $arrSort['PRICE']['FIELD'];
        $order = $_REQUEST['order']? htmlspecialchars($_REQUEST['order']) : 'asc';
        ?>

        <?ob_start();?>

        <?foreach($arrSort as $param){?>
            <?
            $class = "";
            if( $_REQUEST['field'] && $field == $param['FIELD'] ){

                if( $order == 'asc' ){
                    $newOrder = 'desc';
                }
                else{
                    $newOrder = 'asc';
                }
                switch($newOrder){
                    case 'asc' : $direction = 'down';
                        break;
                    case 'desc' : $direction = 'up';
                        break;
                }
                $class = ' catalog-topbar__sort-link--'.$direction;
            }
            else{
                $newOrder = $param['DIRECTION'];
            }

            $link = $APPLICATION->GetCurPageParam('field='.$param['FIELD'].'&order='.$newOrder, array('field', 'order',));?>
            <a href="<?=$link?>" class="catalog-topbar__sort-link<?=$class?>"><?=$param['NAME']?></a>
        <?}?>

        <?
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

    /**
     * Возвращает тип отображения списка товаров
     * @return string
     */
    public function getCatalogSectionView (){

        global $APPLICATION;

        if( $_REQUEST['view'] ){
            $time = time()+60*60*24*30*12*2;
            $view = htmlspecialchars($_REQUEST['view']);
            $APPLICATION->set_cookie("SECTION_VIEW", $view, $time);
        }
        elseif( $APPLICATION->get_cookie("SECTION_VIEW") ){
            $view = $APPLICATION->get_cookie("SECTION_VIEW");
        }
        else{
            $view = 'table';
        }

        return $view;
    }

    /**
     * Возвращает варианты отображения каталога
     * @return mixed
     */
    public function getViewVariants(){
        global $APPLICATION;

        $view = $this->getCatalogSectionView();
        $viewVariants = array('table', 'list',);
        ob_start();
        ?>
        <div class="catalog-topbar__type-buttons">
            <?foreach( $viewVariants as $viewVariant ){?>
                <a href="<?=$APPLICATION->GetCurPageParam("view=".$viewVariant, array('view'))?>" class="catalog-topbar__type-button catalog-topbar__type-button--<?=$viewVariant?><?=$view == $viewVariant ? " active" : ""?>"></a>
            <?}?>
        </div>
        <?
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

    /**
     * Возвращает текуший шаблон списка товаров
     * @return string
     */
//    public function getCatalogTemplate(){
//        $template = $this->getCatalogSectionView();
//        return $template;
//    }

    /**
     * Возвращает текущий инфоблок
     * @return bool
     */
    public function currentCatalogInfo(){

        global $DB;
        global $APPLICATION;

        $iblockArr = $this->getCatalogIblockId();

        # определение детальной карточки товара
//        $urlParts = explode('/', trim($APPLICATION->GetCurDir(), '/'));
        $urlParts = explode('filter', trim(CURPAGE, '/'));
        $urlParts = array_diff(explode('/', $urlParts[0]), array(''));


        array_shift($urlParts); # удаляем /catalog/

        if( sizeof($urlParts) >= 1 ){
            # подозрение на элемент - ибо он не может быть в корне
            $suspectPart = $urlParts[sizeof($urlParts)-1];

            # ищем элемент с таким символьным кодом
            $res = $DB->Query("
                        SELECT ID, IBLOCK_ID FROM b_iblock_element WHERE
                        IBLOCK_ID IN (".implode(',', $iblockArr).") AND
                        ACTIVE = 'Y' AND
                        CODE = '".$DB->ForSQL($suspectPart)."' LIMIT 1
                    ");
            if( $res->SelectedRowsCount() ){
                if( $row = $res->Fetch() ){
                    $result['PLACE'] = 'DETAIL';
                    $result['IBLOCK_ID'] = $row['IBLOCK_ID'];
                }
            }
            else{
                $res = $DB->Query("
                    SELECT ID, IBLOCK_ID FROM b_iblock_section WHERE
                    IBLOCK_ID IN (".implode(',', $iblockArr).") AND
                    ACTIVE = 'Y' AND
                    CODE = '".$DB->ForSQL($suspectPart)."' LIMIT 1
                ");

                if( $res->SelectedRowsCount() ){
                    if( $row = $res->Fetch() ){
                        $result['PLACE'] = 'SECTION';
                        $result['IBLOCK_ID'] = $row['IBLOCK_ID'];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Возвращает артикул товара
     * @param $rekviziti
     * @param $id
     * @return string
     */
    public function getArticul($id = "", $rekviziti){

        $articul = "";

        if( !eRU($rekviziti) ){

            if( CModule::IncludeModule('iblock') ){

                $res = CIBlockElement::GetList(Array(), array("IBLOCK_TYPE" => "catalog", "ACTIVE"=>"Y", "ID" => $id), false, false, array("ID", "IBLOCK_ID"));
                if( $ob = $res->GetNextElement() ){

                    $arFields = $ob->GetFields();
                    $rekviziti = array();

                    $res = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], "sort", "asc", array("CODE" => "CML2_TRAITS"));
                    while( $ob = $res->GetNext() ){
                        $rekviziti['VALUE'][] = $ob['VALUE'];
                        $rekviziti['DESCRIPTION'][] = $ob['DESCRIPTION'];
                    }
                }
            }
        }

        foreach($rekviziti['DESCRIPTION'] as $k => $rekvizit){
            if( $rekvizit == 'Код' ){
                $articul = $rekviziti['VALUE'][$k];
                break;
            }
        }

        return $articul;
    }

    /**
     * Возвращает иконки к инфоблокам
     * @return array
     */
    public function getIblockIcons(){

        $icons = array();

        # кешируем результат
        $phpCache = new CPHPCache;
        $cacheTime = 3600*30*24; # время кеширования - 1 месяц
        $cacheID = 'iblock_icons'; # формируем уникальный id кеша
        $cachePath = '/iblock_icons'; # папка с кешем

        if($phpCache->InitCache($cacheTime, $cacheID, $cachePath)) {
            $icons = $phpCache->GetVars();
        }
        else {
            # помечаем кеш тегом, связанным с инфоблоком
            $tagCache = $GLOBALS['CACHE_MANAGER'];
            $tagCache->StartTagCache($cachePath);
            $tagCache->RegisterTag('iblock_id_'.IBLOCK_ID_IBLOCK_ICONS);
            $tagCache->EndTagCache();

            if( CModule::IncludeModule('iblock') ){

                $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => IBLOCK_ID_IBLOCK_ICONS, "ACTIVE"=>"Y",), false, false,
                    array(
                        "ID",
                        "XML_ID",
                        "PREVIEW_PICTURE",
                        "DETAIL_PICTURE"
                    )
                );
                while( $ob = $res->GetNextElement() ){

                    $arFields = $ob->GetFields();
                    $icons[$arFields['XML_ID']] = array(
                        "INACTIVE" => CFile::GetPath($arFields['PREVIEW_PICTURE']),
                        "ACTIVE" => CFile::GetPath($arFields['DETAIL_PICTURE']),
                    );
                }
            }

            $phpCache->StartDataCache();
            $phpCache->EndDataCache($icons);
        }

        return $icons;
    }

    /**
     * Возвращает SEO-тексты к инфоблокам каталога
     * @return array
     */
    public function getIblockSeo(){

        $seo = array();

        # кешируем результат
        $phpCache = new CPHPCache;
        $cacheTime = 3600*30*24; # время кеширования - 1 месяц
        $cacheID = 'iblock_seo_texts'; # формируем уникальный id кеша
        $cachePath = '/iblock_seo'; # папка с кешем

        if($phpCache->InitCache($cacheTime, $cacheID, $cachePath)) {
            $seo = $phpCache->GetVars();
        }
        else {
            # помечаем кеш тегом, связанным с инфоблоком
            $tagCache = $GLOBALS['CACHE_MANAGER'];
            $tagCache->StartTagCache($cachePath);
            $tagCache->RegisterTag('iblock_id_'.IBLOCK_ID_IBLOCK_ICONS);
            $tagCache->EndTagCache();

            if( CModule::IncludeModule('iblock') ){

                $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => IBLOCK_ID_IBLOCK_ICONS, "ACTIVE"=>"Y",), false, false,
                    array(
                        "ID",
                        "XML_ID",
                        "DETAIL_TEXT",
                    )
                );
                while( $ob = $res->GetNextElement() ){

                    $arFields = $ob->GetFields();
                    $seo[$arFields['XML_ID']] = array(
                        "DETAIL_TEXT" => $arFields['DETAIL_TEXT'],
                    );
                }
            }

            $phpCache->StartDataCache();
            $phpCache->EndDataCache($seo);
        }

        return $seo;
    }

    /**
     * Возвращает кол-во товаров в сравнение
     * @return int
     */
    public function getInCompareItemsCount(){
        $count = 0;
        foreach($_SESSION['CATALOG_COMPARE_LIST'] as $iblock){
            $count += count($iblock['ITEMS']);
        }
        return $count;
    }

    /**
     * Вовзращает html сравнения для шапки
     * @return string
     */
    public function getCompareTopHtml($type = "simple"){

        $count = $this->getInCompareItemsCount();
        ob_start();
        if( $type == "simple" ){
            if( $count ){?>
                <a href="<?=$count ? "/catalog/compare/" : "javascript:void(0)"?>" class="shop-links__link shop-links__link--comparsion">
                    <div class="shop-links__badge"><?=$count;?></div>
                    <div class="shop-links__text shop-links__text--mobile-only">Сравнение</div>
                </a>
            <?}
            else{?>
                <a href="<?=$count ? "/catalog/compare/" : "javascript:void(0)"?>" class="shop-links__link shop-links__link--comparsion">
                    <div class="shop-links__text shop-links__text--mobile-only">Сравнение</div>
                </a>
            <?}
        }
        elseif( $type == "mobile" ){
            if( $count ){?>
                <a href="/catalog/compare/" class="header-mobile-shop-links__link header-mobile-shop-links__link--comparsion">
                    <div class="header-mobile-shop-links__badge"><?=$count;?></div>
                </a>
            <?}
            else{?>
                <a href="javascript:void(0)" class="header-mobile-shop-links__link header-mobile-shop-links__link--comparsion"></a>
            <?}
        }
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    /**
     * получаем список корневых разделов с разбивкой по товарам
     * @param $items
     * @return array
     */
    static function compareProps( $items ){
        $sections = array();
        foreach($items as $item){
            if( !in_array($item['IBLOCK_SECTION_ID'], $sections) ){
                $sections[] = $item['IBLOCK_SECTION_ID'];
            }
        }
        return $sections;
    }

    /**
     * возвращает названия разделов
     * @param $arr
     * @return array
     */
    static function compareSectionName($arr){
        if( CModule::IncludeModule('iblock') ){
            $sections = array();

            $arFilter = Array('IBLOCK_TYPE' => 'catalog', 'ID' => $arr);
            $db_list = CIBlockSection::GetList(Array(), $arFilter, true, array('ID','NAME'));
            while($ar_result = $db_list->GetNext()){
                $sections[$ar_result['ID']] = $ar_result['NAME'];
            }
        }

        return $sections;
    }

    /**
     * Возвращает связь между id и кодом св-ва в инфоблоке
     * @param $idIblock
     * @return array
     */
    public function getPropsName($idIblock){

        $props = array();

        $cache = new CPHPCache;
        $cacheTime = 3600*24*1;

        // формируем идентификатор кеша в зависимости от всех параметров
        // которые могут повлиять на результирующий HTML
        $cacheID = 'names_codes_'.$idIblock;
        $cachePath = '/iblock_properties';

        if( $cache->InitCache($cacheTime, $cacheID, $cachePath) ){
            $vars = $cache->GetVars();
            $props = $vars['PROPERTIES'];
        }
        else {
            if( CModule::IncludeModule('iblock') ){
                $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$idIblock));
                while ($prop_fields = $properties->GetNext()){
                    $props[$prop_fields['ID']] = $prop_fields['CODE'] ? $prop_fields['CODE'] : $prop_fields['ID'];
                }
            }

            $cache->StartDataCache($cacheTime, $cacheID, $cachePath);
            $cache->EndDataCache(array('PROPERTIES' => $props));
        }

        return $props;
    }

    /**
     * Возвращает свойства, исключенные из сравнения
     * @return array
     */
    public function getCompareExcludeProps(){

        $props = array(
            'MORE_PHOTO',
            'FILES',
            'CML2_TAXES',
            'CML2_BASE_UNIT',
            'CML2_TRAITS',
            'CML2_ATTRIBUTES',
            'CML2_ARTICLE',
            'CML2_BAR_CODE',
            'POKAZYVAT_NA_MARKETE',
            'NOVINKA',
            'KHIT',
            'SPETSPREDLOZHENIE',
        );

        return $props;
    }
}