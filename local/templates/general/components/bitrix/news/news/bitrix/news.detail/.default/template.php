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
<?
$APPLICATION->IncludeComponent(
    "coffeediz:schema.org.Article",
    "myArticle",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "SHOW" => "Y",
        "TYPE" => "NewsArticle",
        "LEARNING_RESOURCE_TYPE" => "",
        "NAME" => $arResult["NAME"],
        "ARTICLEBODY" => $arResult["DETAIL_TEXT"] ,
        "ABOUT" => "",
        "GENRE" => "",
        "ARTICLE_SECTION" => array(
        ),
        "KEYWORDS" => array(
        ),
        "IN_LANGUAGE" => "ru",
        "DATA_PUBLISHED" => "",
        "DATA_MODIFIED" => "",
        "AUTHOR_TYPE" => "Text",
        "IMAGEURL" => $arResult["DETAIL_PICTURE"]["SRC"],
        "MAINENTITYOFPAGE" => "",
        "AUTHOR_TEXT" => "",
        "PUBLISHER_ORGANIZATION_TYPE_2" => "",
        "PUBLISHER_ORGANIZATION_NAME" => "",
        "PUBLISHER_ORGANIZATION_DESCRIPTION" => "",
        "PUBLISHER_ORGANIZATION_SITE" => "",
        "PUBLISHER_ORGANIZATION_PHONE" => array(
        ),
        "PUBLISHER_ORGANIZATION_POST_CODE" => "",
        "PUBLISHER_ORGANIZATION_COUNTRY" => "",
        "PUBLISHER_ORGANIZATION_REGION" => "",
        "PUBLISHER_ORGANIZATION_LOCALITY" => "",
        "PUBLISHER_ORGANIZATION_ADDRESS" => "",
        "PUBLISHER_ORGANIZATION_LOGO" => "",
        "PARAM_RATING_SHOW" => "N"
    ),
    false
);
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
        global $new_img;
        $new_img = $arResult["DETAIL_PICTURE"]["SRC"];
        ?>
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

