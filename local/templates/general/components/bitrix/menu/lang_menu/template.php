<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>


<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
<span class="header-languages__link header-languages__link--active"><?=$arItem["TEXT"]?></span>/
	<?else:?>
		<a class="header-languages__link" <?=$arItem['PARAMS']['nofolow']?:''?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
	<?endif?>
	
<?endforeach?>


<?endif?>