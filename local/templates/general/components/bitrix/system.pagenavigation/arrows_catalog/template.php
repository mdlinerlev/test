<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$ClientID = 'navigation_'.$arResult['NavNum'];

$this->setFrameMode(true);

if(!empty($_SERVER['REDIRECT_SCRIPT_URL'])){
    $arResult['sUrlPath'] = $_SERVER['REDIRECT_SCRIPT_URL'];
}

if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
?>

<div class="pagination_mobile">
<?if($arResult["bDescPageNumbering"] === true):?>
    <?if ($arResult["NavPageNomer"] > 1):?>
        <a class="feedback__button feedback__button--more button" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" id="infinity-next-page"><span>Показать еще</span></a>
    <?endif?>
<?else:?>
    <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
        <a class="feedback__button feedback__button--more button" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" id="infinity-next-page"><span>Показать еще</span></a>
    <?endif?>
<?endif?>
</div>

<div class="pagination">
	<div class="pagination__total">
		Показано <?= $arResult['NavLastRecordShow']?> товаров из <?= $arResult['NavRecordCount']?>
	</div>
    <nav class="pagination__nav">

    <?
    $strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
    $strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
    if($arResult["bDescPageNumbering"] === true)
    {
    // to show always first and last pages
    $arResult["nStartPage"] = $arResult["NavPageCount"];
    $arResult["nEndPage"] = 1;

    $sPrevHref = '';
    if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
    {
        $bPrevDisabled = false;
        if ($arResult["bSavePage"])
        {
            $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
        }
        else
        {
            if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1))
            {
                $sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
            }
            else
            {
                $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
            }
        }
    }
    else
    {
        $bPrevDisabled = true;
    }

    $sNextHref = '';
    if ($arResult["NavPageNomer"] > 1)
    {
        $bNextDisabled = false;
        $sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
    }
    else
    {
        $bNextDisabled = true;
    }
    ?>

        <?if ($bPrevDisabled):?>
            <button class="pagination__item pagination__arrow 1" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></button>
        <?else:?>
            <a href="<?= $sPrevHref?>" class="pagination__item pagination__arrow 2"><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></a>
        <?endif;?>

        <?
        $bFirst = true;
        $bPoints = false;
        do
        {
            $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
            if ($arResult["nStartPage"] <= 2 || $arResult["NavPageCount"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2)
            {

                if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                    ?>
                    <span class="pagination__item pagination__item--current"><?=$NavRecordGroupPrint?></span>
                <?
                elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):
                    ?>
                    <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?><?$_GET['sort'] == 'property_MINIMUM_PRICE'?'&sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>" class="pagination__item 4"><?=$NavRecordGroupPrint?></a>
                <?
                else:
                    ?>
                    <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?><?$_GET['sort'] == 'property_MINIMUM_PRICE'?'&sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>" class="pagination__item 1"><?=$NavRecordGroupPrint?></a>
                <?
                endif;
                $bFirst = false;
                $bPoints = true;
            }
            else
            {
                if ($bPoints)
                {
                    ?><span class="pagination__item pagination__item--nohover">...</span><?
                    $bPoints = false;
                }
            }
            $arResult["nStartPage"]--;
        } while($arResult["nStartPage"] >= $arResult["nEndPage"]);
        ?>

        <?if ($bNextDisabled):?>
            <button class="pagination__item pagination__arrow 3" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></button>
        <?else:?>
            <a href="<?= $sNextHref?>" class="pagination__item pagination__arrow 4"><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></a>
        <?endif;?>

        <?
        }
        else
        {
        // to show always first and last pages
        $arResult["nStartPage"] = 1;
        $arResult["nEndPage"] = $arResult["NavPageCount"];

        $sPrevHref = '';
        if ($arResult["NavPageNomer"] > 1)
        {
            $bPrevDisabled = false;

            if ($arResult["bSavePage"] || $arResult["NavPageNomer"] > 2)
            {
                $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
            }
            else
            {
                $sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
            }
        }
        else
        {
            $bPrevDisabled = true;
        }

        $sNextHref = '';
        if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
        {
            $bNextDisabled = false;
            $sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
        }
        else
        {
            $bNextDisabled = true;
        }
        ?>


        <?if ($bPrevDisabled):?>
            <button class="pagination__item pagination__arrow 5" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></button>
        <?else:?>
            <a href="<?= $sPrevHref?><?echo $_GET['sort'] == 'property_MINIMUM_PRICE'?'&sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>" class="pagination__item pagination__arrow 6"><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></a>
        <?endif;?>




            <?
            $bFirst = true;
            $bPoints = false;
            do
            {
                if ($arResult["nStartPage"] <= 2 || $arResult["nEndPage"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2)
                {

                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
                        ?>
                        <span class="pagination__item pagination__item--current"><?=$arResult["nStartPage"]?></span>
                    <?
                    elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
                        ?>
                        <a class="pagination__item 2" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?><? echo$_GET['sort'] == 'property_MINIMUM_PRICE'?'?sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>"><?=$arResult["nStartPage"]?></a>
                    <?
                    else:
                        ?>
                        <a class="pagination__item 3" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?><?echo $_GET['sort'] == 'property_MINIMUM_PRICE'?'&sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>"><?=$arResult["nStartPage"]?></a>
                    <?
                    endif;
                    $bFirst = false;
                    $bPoints = true;
                }
                else
                {
                    if ($bPoints)
                    {
                        ?><span class="pagination__item pagination__item--nohover">...</span><?
                        $bPoints = false;
                    }
                }
                $arResult["nStartPage"]++;
            } while($arResult["nStartPage"] <= $arResult["nEndPage"]);
            }
            ?>

        <?if ($bNextDisabled):?>
            <button class="pagination__item pagination__arrow 7 " disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></button>
        <?else:?>
            <a href="<?= $sNextHref?><?echo $_GET['sort'] == 'property_MINIMUM_PRICE'?'&sort=property_MINIMUM_PRICE&method='.$_GET['method']:''?>" class="pagination__item pagination__arrow 8"><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></a>
        <?endif;?>

    </nav>
</div>