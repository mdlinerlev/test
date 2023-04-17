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
?>

<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
    <img
            class="left"
            border="0"
            src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
            width="563px"
            height="<?/*=$arResult["DETAIL_PICTURE"]["HEIGHT"]*/?>"
            alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
            title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
    />
<?endif?>
<?if (!empty($arResult["DETAIL_PICTURE"]["SRC"])) :?>
    <?php
    global $article_img;
    $article_img = $arResult["DETAIL_PICTURE"]["SRC"];
    ?>
    <?$APPLICATION->IncludeComponent(
        "coffeediz:schema.org.ImageObject",
        "",
        Array(
            "CAPTION" => $arResult['NAME'],
            "CONTENTURL" => (!empty($arResult["DETAIL_PICTURE"]["SRC"]))? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"],
            "DESCRIPTION" => strip_tags($arResult['DETAIL_TEXT']),
            "ITEMPROP" => "",
            "NAME" => $arResult['NAME'],
            "PARAM_EXTRA_SHOW" => "N",
            "PARAM_RATING_SHOW" => "N",
            "REPRESENTATIVEOFPAGE" => "True",
            "SHOW" => "Y",
            "TRUMBNAIL_CONTENTURL" => (!empty($arResult["DETAIL_PICTURE"]["SRC"]))? $arResult["DETAIL_PICTURE"]["SRC"] :$arResult["PREVIEW_PICTURE"]["SRC"],
            "URL" => '',
            "WIDTH" => "563",
            "HEIGHT" => "",
        ), false, Array('HIDE_ICONS' => 'N')
    );?>
<?endif;?>
<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["TIMESTAMP_X"]):?>
    <?php $datePublication = explode (' ', $arResult['TIMESTAMP_X']); ?>
    <div class="text__date"><?=$datePublication[0]?></div>
<?endif;?>
<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
    <h3><?=$arResult["NAME"]?></h3>
<?endif;?>

<?if($arResult["NAV_RESULT"]):?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
<?echo $arResult["NAV_TEXT"];?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
<p><?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
        <?echo $arResult["DETAIL_TEXT"];?>
    <?else:?>
        <?echo $arResult["PREVIEW_TEXT"];?>
    <?endif?></p>
<div style="clear:both"></div>
<br />
<?foreach($arResult["FIELDS"] as $code=>$value):
    if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
    {
        ?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?
        if (!empty($value) && is_array($value))
        {
            ?><img border="0" src="<?=$value["SRC"]?>" width="<?=$value["WIDTH"]?>" height="<?=$value["HEIGHT"]?>"><?
        }
    }
    else
    {
        ?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?><?
    }
    ?><br />
<?endforeach;
foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
    <?php if($arProperty["NAME"] == 'Теги' ){
        continue;
    }?>
    <?=$arProperty["NAME"]?>:&nbsp;
    <?if(is_array($arProperty["DISPLAY_VALUE"])):?>
        <?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
    <?else:?>
        <?=$arProperty["DISPLAY_VALUE"];?>
    <?endif?>
    <br />
<?endforeach;
if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
{
    ?>
    <div class="news-detail-share">
        <noindex>
            <?
            $APPLICATION->IncludeComponent("bitrix:main.share", "", array(
                "HANDLERS" => $arParams["SHARE_HANDLERS"],
                "PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
                "PAGE_TITLE" => $arResult["~NAME"],
                "SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                "SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
                "HIDE" => $arParams["SHARE_HIDE"],
            ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>
        </noindex>
    </div>
    <?
}
?>


<?$keywordsMy = explode(',',$arResult["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS']);?>
<?  $dateTemp = $arResult["ACTIVE_FROM"];
$a = date_parse_from_format('d.m.Y G:i:s',$dateTemp);
$timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['day'], $a['year']);
$dateTemp1 = date('Y-m-d\TH:i:sO', $timestamp);
?>
<?$APPLICATION->IncludeComponent(
    "coffeediz:schema.org.Article",
    "myArticle",
    array(
        "ABOUT" => strip_tags($arResult["PREVIEW_TEXT"]),
        "ARTICLEBODY" => strip_tags($arResult["DETAIL_TEXT"]),
        "ARTICLE_SECTION" => array(
        ),
        "AUTHOR_TYPE" => "",
        "DATA_MODIFIED" => $dateTemp1,
        "DATA_PUBLISHED" => $dateTemp1,
        "GENRE" => "",
        "IMAGEURL" => '',//$arResult["DETAIL_TEXT"]["SRC"],
        "IN_LANGUAGE" => "by",
        "KEYWORDS" => $keywordsMy,
        "LEARNING_RESOURCE_TYPE" => "",
        "MAINENTITYOFPAGE" => $arResult['LIST_PAGE_URL'],
        "NAME" => $arResult["NAME"],
        "PARAM_RATING_SHOW" => "N",
        "PUBLISHER_ORGANIZATION_ADDRESS" => "ул. Промышленная, 10, комн. 20",
        "PUBLISHER_ORGANIZATION_COUNTRY" => "by",
        "PUBLISHER_ORGANIZATION_DESCRIPTION" => "Производитель и поставщик межкомнатных и входных дверей",
        "PUBLISHER_ORGANIZATION_LOCALITY" => "Минск",
        "PUBLISHER_ORGANIZATION_LOGO" => '',//"/bitrix/templates/general/assets/images/logo.png",
        "PUBLISHER_ORGANIZATION_NAME" => "ОДО «Беллесизделие»",
        "PUBLISHER_ORGANIZATION_PHONE" => array(
            0 => "+375(17)388-15-58",
            1 => "+375(17)346-22-48",
            2 => "+375(44)779-07-72",
            3 => "+375(44)712-12-48",
            4 => "",
        ),
        "PUBLISHER_ORGANIZATION_POST_CODE" => "220075",
        "PUBLISHER_ORGANIZATION_REGION" => "Минск",
        "PUBLISHER_ORGANIZATION_SITE" => "belwooddoors.by",
        "PUBLISHER_ORGANIZATION_TYPE_2" => "LocalBusiness",
        "SHOW" => "Y",
        "TYPE" => "",
        "COMPONENT_TEMPLATE" => ".default",
        "IMAGE_NAME" =>  strip_tags($arResult["NAME"]),
        "IMAGE_CAPTION" => strip_tags($arResult["NAME"]),
        "IMAGE_DESCRIPTION" => strip_tags($arResult["PREVIEW_TEXT"]),
        "IMAGE_HEIGHT" => "",
        "IMAGE_WIDTH" => "563",
        "IMAGE_TRUMBNAIL_CONTENTURL" => $arResult["DETAIL_PICTURE"]["SRC"],
        "PUBLISHER_ORGANIZATION_LOGO_NAME" => "",
        "PUBLISHER_ORGANIZATION_LOGO_CAPTION" => "",
        "PUBLISHER_ORGANIZATION_LOGO_DESCRIPTION" => "",
        "PUBLISHER_ORGANIZATION_LOGO_HEIGHT" => "",
        "PUBLISHER_ORGANIZATION_LOGO_WIDTH" => "",
        "PUBLISHER_ORGANIZATION_LOGO_TRUMBNAIL_CONTENTURL" => ""
    ),
    false
);?>