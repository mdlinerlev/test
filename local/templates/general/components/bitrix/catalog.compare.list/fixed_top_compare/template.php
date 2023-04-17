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

$itemCount = count($arResult);
$isAjax = (isset($_REQUEST["ajax_action"]) && $_REQUEST["ajax_action"] == "Y" || $_POST["ajax_action"] == "Y");
$idCompareCount = 'compareList'.$this->randString();
$obCompare = 'ob'.$idCompareCount;
$idCompareTable = $idCompareCount.'_tbl'; 
$idCompareRow = $idCompareCount.'_row_';
$idCompareAll = $idCompareCount.'_count';
$mainClass = 'header-shop-links__link';
if ($arParams['POSITION_FIXED'] == 'Y')
{
	//$mainClass .= ' fix '.($arParams['POSITION'][0] == 'bottom' ? 'bottom' : 'top').' '.($arParams['POSITION'][1] == 'right' ? 'right' : 'left');
}
//$style = ($itemCount == 0 ? ' style="display: none;"' : '');
?><?
unset($style, $mainClass);
if ($isAjax)
{
	$APPLICATION->RestartBuffer();
}

?>
<? 
foreach($arResult as $arElement){
$GLOBALS["compare_lists"][] = $arElement["ID"];
}
?>
<!--noindex-->
<!--<pre><?print_r($GLOBALS["compare_lists"])?></pre> -->
<?$frame = $this->createFrame($idCompareCount)->begin('');?>
    <?
if ($itemCount > 0)
{
    ?><a rel="nofollow" id="<? echo $idCompareCount; ?>" class="header-fixed-shop-links__link header-fixed-shop-links__link--comparsion active" href="<? echo $arParams["COMPARE_URL"]; ?>">
        
      <div class="header-fixed-shop-links__badge" id="<? echo $idCompareAll; ?>"><? echo $itemCount; ?></div>
      <div class="header-fixed-shop-links__text header-fixed-shop-links__text--comparsion">Сравнение</div>  
    </a><?
}else{?>
    <a rel="nofollow" href="<? echo $arParams["COMPARE_URL"]; ?>" class="header-fixed-shop-links__link header-fixed-shop-links__link--comparsion">
                  <div class="header-fixed-shop-links__text header-fixed-shop-links__text--comparsion">
                  </div>
    </a>
<?}
?><?
$frame->end();
if (!empty($arResult))
{
/*?><div class="bx_catalog_compare_form">
<table id="<? echo $idCompareTable; ?>" class="compare-items">
<thead><tr><td align="center" colspan="2"><?=GetMessage("CATALOG_COMPARE_ELEMENTS")?></td></tr></thead>
<tbody><?
	foreach($arResult as $arElement)
	{
		?><tr id="<? echo $idCompareRow.$arElement['PARENT_ID']; ?>">
			<td><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></td>
			<td><noindex><a href="javascript:void(0);"  data-id="<? echo $arElement['PARENT_ID']; ?>" rel="nofollow"><?=GetMessage("CATALOG_DELETE")?></a></noindex></td>
		</tr><?
	}
?>
</tbody>
</table>
</div><?*/
}

if ($isAjax)
{
	die();
}
$currentPath = CHTTP::urlDeleteParams(
	$APPLICATION->GetCurPageParam(),
	array(
		$arParams['PRODUCT_ID_VARIABLE'],
		$arParams['ACTION_VARIABLE'],
		'ajax_action'
	),
	array("delete_system_params" => true)
);

$jsParams = array(
	'VISUAL' => array(
		'ID' => $idCompareCount,
	),
	'AJAX' => array(
		'url' => $currentPath,
		'params' => array(
			'ajax_action' => 'Y'
		),
		'templates' => array(
			'delete' => (strpos($currentPath, '?') === false ? '?' : '&').$arParams['ACTION_VARIABLE'].'=DELETE_FROM_COMPARE_LIST&'.$arParams['PRODUCT_ID_VARIABLE'].'='
		)
	),
	'POSITION' => array(
		'fixed' => $arParams['POSITION_FIXED'] == 'Y',
		'align' => array(
			'vertical' => $arParams['POSITION'][0],
			'horizontal' => $arParams['POSITION'][1]
		)
	)
);
?>
<!--/noindex-->
<script type="text/javascript">
var <? echo $obCompare; ?> = new JCCatalogCompareList(<? echo CUtil::PhpToJSObject($jsParams, false, true); ?>)
</script>
