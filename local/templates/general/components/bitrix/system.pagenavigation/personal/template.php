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
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<div class="modern-page-navigation">
    <span class="modern-page-title">Страницы:</span>

    <?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

        <?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
            <span class="modern-page-first modern-page-current"><?=$arResult["nStartPage"]?></span>
        <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
            <span class="modern-page-first" onclick="location.href='<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>'" ><?=$arResult["nStartPage"]?></span>
        <?else:?>
            <span class="modern-page-first" onclick="location.href='<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>'"><?=$arResult["nStartPage"]?></span>
        <?endif?>
        <?$arResult["nStartPage"]++?>
    <?endwhile?>

    <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
        <a class="modern-page-last" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_next")?></a>
        <a class="modern-page-last" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_end")?></a>
    <?else:?>
        <span class="modern-page-next"><?=GetMessage("nav_next")?></span>
        <span class="modern-page-last"><?=GetMessage("nav_end")?></span>
    <?endif?>
</div>