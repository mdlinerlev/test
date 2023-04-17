<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if ((!empty($arResult)) && (!preg_match('#^/about/news/.*#Uui', CURPAGE))):?>
<aside class="sidebar <?=preg_match('#^/addresses/.*#Uui', CURPAGE) ? 'sidebar--stores' : 'sidebar--text'?>">
    <?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
    <?if($arItem["SELECTED"]):?>
            <a class="sidebar__button sidebar__button--menu button"><span><?=$arItem["TEXT"]?></span>
              </a>
    <?  endif;?>
    <?endforeach;?>
            <div class="sidebar__menu">
<ul class="sidebar-menu__list">

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<li class="sidebar-menu__item"><span class="sidebar-menu__title"><?=$arItem["TEXT"]?></span></li>
	<?else:?>
		<li class="sidebar-menu__item"><a class="sidebar-menu__link" <?=$arItem['PARAMS']['nofollow']?:''?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	
<?endforeach?>

</ul>
            </div>
</aside>
<?endif?>