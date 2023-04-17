<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?/*
global $USER;

if( $USER->IsAdmin() ){
    pr($arResult);
}
*/
?>


<?if(count($arResult)>0):?>

<span>К сравнению</span> <span><strong><?=count($arResult);?></strong> <?=plural(count($arResult),'товар','товара','товаров')?></span>
<?else:?>
<span class="comp-count">Сравнение пусто</span>
<?endif;?>  
