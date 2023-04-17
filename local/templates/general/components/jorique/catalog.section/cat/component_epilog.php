<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $APPLICATION;
if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
	?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
<?
	}
}

$userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
if ($userId > 0) {
    $user = \Bitrix\Main\UserTable::getByPrimary($userId, [
        'select' => ['ID', 'UF_FAVORITES']
    ]);
    if ($arUser = $user->fetch()) {
        $arFavorites = ($arUser['UF_FAVORITES']) ? unserialize($arUser['UF_FAVORITES']) : ['ITEMS' => [], 'OFFERS' => []];
        ?>
        <script>
            window.favorites = <?=CUtil::PhpToJSObject($arFavorites);?>;
            checkFavorites();
        </script>
    <? }
}
?>