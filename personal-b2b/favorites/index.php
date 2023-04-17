<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Main\Application;
$APPLICATION->SetTitle("Избранное");

global $arrFilter;

$request = Application::getInstance()->getContext()->getRequest();

if($request['q']){
	$arrFilter['%NAME'] = $request['q'];
}
if($request['categories'] && $request['categories'] > 0){
	$arrFilter['IBLOCK_SECTION_ID'] = $request['categories'];
}

?>
<?$APPLICATION->IncludeComponent(
	"medialine:favorites.list",
	"",
	Array(
		'IBLOCK_ID' => IBLOCK_ID_CATALOG,
		'IBLOCK_OFFERS_ID' => IBLOCK_ID_OFFERS,
		'FILTER_NAME' => 'arrFilter',
		'PAGEN_NUM' => 10,
		'PAGEN_TEMPLATE' => 'personal'
	)
);?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>