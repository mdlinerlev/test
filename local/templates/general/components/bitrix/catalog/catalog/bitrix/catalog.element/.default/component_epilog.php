   <?
    include $_SERVER['DOCUMENT_ROOT']. '/include/product_view.php';
    ?>
<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<?
if(!empty($arResult['PROPERTIES']['CML2_NO_SHOW_IN_SITE']['VALUE'])):
	CHTTP::SetStatus("404 Not Found");
	@define("ERROR_404", "Y");
endif;

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
   <script>
       setTimeout(function () {
           $('.product-filter__color--second').find('a').removeClass('active');
           $('.product-filter__color--second').find('a').first().click();
           $('.product-filter-color__inner').find('a').removeClass('active');
           $('.product-filter-color__inner').find('a').first().click();
           $('.filter__select-box').find('a').removeClass('active');
           $('.filter__select-box').find('a').first().click();


       },10)  ;
   </script>
<? CIBlockPriceToolsNewsite::$staticProductInfo = $arResult;?>
   