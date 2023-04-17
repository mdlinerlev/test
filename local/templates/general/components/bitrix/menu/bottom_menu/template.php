<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

if (empty($arResult))
	return;
?>

	<ul class="footer-section-menu__list footer-section-menu__list--level2">
		<?foreach($arResult as $itemIdex => $arItem):?>
			<?if ($arItem["DEPTH_LEVEL"] == "1"):?>
                <?if ($arItem["TEXT"] == "Заказать замер"):?><script data-b24-form="click/9/5qnm9v" data-skip-moving="true">
                    (function(w,d,u){
                        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/180000|0);
                        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
                    })(window,document,'https://bitrix.belwood.ru/upload/crm/form/loader_9_5qnm9v.js');
                </script><?endif;?>
		<li class="footer-section-menu__item footer-section-menu__item--level2"><a <?=$arItem['PARAMS']['nofollow']?:''?> href="<?=$arItem["LINK"]?>" class="footer-section-menu__link footer-section-menu__link--level2 <?= !empty($arItem['PARAMS']['CLASS']) ? $arItem['PARAMS']['CLASS'] : ''?>" <?if ($arItem["TEXT"] != "Заказать замер"):?><?=  !empty($arItem['PARAMS']['OPTIONS']) ? $arItem['PARAMS']['OPTIONS'] : ''?><?endif;?>><?=htmlspecialcharsbx($arItem["TEXT"])?></a></li>
			<?endif?>
		<?endforeach;?>
	</ul>
