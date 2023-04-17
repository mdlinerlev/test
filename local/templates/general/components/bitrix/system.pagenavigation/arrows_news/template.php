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


<div class="pagination">
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
            <button class="pagination__item pagination__arrow" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></button>
        <?else:?>
            <a href="<?= $sPrevHref?>" class="pagination__item pagination__arrow"><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></a>
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
                        <a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="pagination__item"><?=$NavRecordGroupPrint?></a>
                    <?
                    else:
                        ?>
                        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" class="pagination__item"><?=$NavRecordGroupPrint?></a>
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
            <button class="pagination__item pagination__arrow" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></button>
        <?else:?>
            <a href="<?= $sNextHref?>" class="pagination__item pagination__arrow"><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></a>
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
            <button class="pagination__item pagination__arrow" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></button>
        <?else:?>
            <a href="<?= $sPrevHref?>" class="pagination__item pagination__arrow"><svg class="dropdown_arrow"><use xlink:href="#arrow-left"></use></svg></a>
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
                        <a class="pagination__item" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
                    <?
                    else:
                        ?>
                        <a class="pagination__item" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
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
            <button class="pagination__item pagination__arrow" disabled><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></button>
        <?else:?>
            <a href="<?= $sNextHref?>" class="pagination__item pagination__arrow"><svg class="dropdown_arrow"><use xlink:href="#arrow-right"></use></svg></a>
        <?endif;?>

    </nav>
</div>