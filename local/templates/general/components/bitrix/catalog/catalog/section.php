<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
	$arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isSidebar = ($arParams["SIDEBAR_SECTION_SHOW"] == "Y" && isset($arParams["SIDEBAR_PATH"]) && !empty($arParams["SIDEBAR_PATH"]));
$isFilter = ($arParams['USE_FILTER'] == 'Y');

if ($isFilter)
{
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	);
	if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
		$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
	elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
		$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID", "NAME", "CODE", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID", "UF_TITLE_H1", 'UF_SLIDERS', "UF_FILTER", "UF_DESCR", "UF_DESCR_HTML", "DESCRIPTION", "UF_BANNER", "UF_BANNER_LINK", "UF_BANNER_2", "UF_BANNER_2_LINK"));

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->GetNext())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->GetNext())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}
?>

<? global $newfilterparams;
   $filterprop = explode( '_' ,$arCurSection['UF_FILTER'], 2);//выносим первое ниж.подчеркивание, чтобы не повредить в свойстве
   $filterprop_next_step = explode( '=' ,$filterprop[1], 2);
 
   foreach($arParams["FILTER_OFFERS_PROPERTY_CODE"] as $keys => $props){
       if ($filterprop_next_step[0] == $props){
           $arParams["FILTER_OFFERS_PROPERTY_CODE"][$keys] = FALSE;
       }
       if ($filterprop_next_step[0] == 'COLOR'){
          // $arParams["OFFERS_SORT_FIELD"] = "PROPERTY_COLOR_VALUE";
       }
   }
   $newfilterparams = $arParams["FILTER_OFFERS_PROPERTY_CODE"];
   
   
   
   ?>
<style>#filter_<?=$filterprop_next_step[0]?>{
    display:none;
}
</style>



<?

if (!in_array($arCurSection["CODE"], array('napolnye_pokrytiya', 'furnitura'))){
   // define(checksection, 1);
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_vertical.php");
        
}else{
  //  define(checksection, 0);
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_horizontal.php");
        
}
?>


