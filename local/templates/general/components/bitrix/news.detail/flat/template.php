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
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$this->addExternalCss($this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');
CUtil::InitJSCore(array('fx'));
?>

<div class="text__contents">
<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="text__date"> <?echo $arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif?>
	<?if($arParams["DISPLAY_PICTURE"]!="N"):?>
		<?if ($arResult["VIDEO"]):?>
			<div class="bx-newsdetail-youtube embed-responsive embed-responsive-16by9" style="display: block;">
				<iframe src="<?echo $arResult["VIDEO"]?>" frameborder="0" allowfullscreen=""></iframe>
			</div>
            <? // Микроразметка coffeediz:schema.org.Video?>
            <?  $dateTemp = ($arResult["DATE_CREATE"])?$arResult["DATE_CREATE"]:$arResult["DATE_ACTIVE_FROM"];
            $a = date_parse_from_format('d.m.Y G:i:s',$dateTemp);
            $timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['day'], $a['year']);
            $dateTemp1 = date('Y-m-d\TH:i:sO', $timestamp);
            ?>
            <?$APPLICATION->IncludeComponent("coffeediz:schema.org.Video", "myVideo", Array(
                "ALLOWCOUNTRIES" => "",	// Перечень стран, в которых доступно данное видео (В ОСТАЛЬНЫХ ЗАПРЕЩЕНО)
                "AUTHOR_PERSON_ADDITIONALNAME" => "",	// Отчество
                "AUTHOR_PERSON_EMAIL" => "",	// E-mail
                "AUTHOR_PERSON_FAMILYNAME" => "",	// Фамилия
                "AUTHOR_PERSON_IMAGEURL" => "",	// URL фото персоны
                "AUTHOR_PERSON_JOBTITLE" => "",	// Должность
                "AUTHOR_PERSON_NAME" => "",	// Имя
                "AUTHOR_PERSON_PHONE" => "",	// Телефон
                "AUTHOR_PERSON_URL" => "",	// URL страниц, связанных с персоной
                "AUTHOR_PERSON_URL_SAMEAS" => "",	// URL ОФИЦИАЛЬНЫХ страниц, связанных с персоной
                "CAPTION" => $arResult["NAME"],	// Подпись к видео
                "CONTENT_ID" => "",	// Идентификатор видео
                "CONTENT_URL" => $arResult["DETAIL_PAGE_URL"],	// Адрес, по которому доступен файл с видео-роликом
                "DESCRIPTION" => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]):strip_tags($arResult["PREVIEW_TEXT"]),	// Описание
                "DISALLOWCOUNTRIES" => "",	// Перечень стран, в которых НЕдоступно данное видео (В ОСТАЛЬНЫХ РАЗРЕШЕНО)
                "DUBBING" => "",	// Студия, дублировавшая видео
                "DURATION" => "100",	// Продолжительность видео (PTччHммMссS)
                "FEED_URL" => "",	// Адрес XML-фида для данной страницы
                "GENRE" => array(	// Жанр
                    0 => "",
                    1 => "",
                ),
                "IMAGEURL" => "",	// URL Оффициального Изображения (постера и т.п.)
                "IN_LANGUAGE" => "ru",	// Язык видео
                "IS_FAMILY_FRIENDLY" => "Y",	// Можно смотреть детям
                "IS_OFFICIAL" => "N",	// Официальное видео
                "KEYWORDS" => array(	// Ключевые слова, Теги
                    0 => "двери межкомнатные, двери входные, двери от производителя",
                ),
                "LICENSE" => "Common license",	// Тип лицензии, по которой распространяется видео
                "NAME" => $arResult["NAME"],	// Название
                "PARAM_RATING_SHOW" => "N",	// Выводить рейтинг
                "PRODUCTCOMPANY_TYPE" => "Organization",	// Тип описания Компании-Производитель видео
                "PRODUCTIONCOUNTRY" => "3166-2:BY",	// Страна-производитель (в формате ISO 3166-1)
                "SHOW" => "Y",	// Не отображать на сайте
                "STATUS" => "published",	// Статус
                "SUBTITLE_IN_LANGUAGE" => "",	// Язык субтитров
                "SUBTITLE_URL" => "",	// Адрес, по которому расположен файл с субтитрами
                "THUNBNAIL_IMAGEURL" => $videoSmallImage["src"],	// URL Изображения предпросмотра
                "UPLOAD_DATE" => $dateTemp1,	// дата загрузки видео-ролика на сайт в формате ISO 8601 (ГГГГ-ММ-ДД)
                "URL" => str_replace("watch?v=","embed/",$arResult["VIDEO"])."?enablejsapi=1",	// Ссылка на видео
                "COMPONENT_TEMPLATE" => ".default",
                "THUNBNAIL_IMAGE_NAME" => $arResult["NAME"],	// Название Изображения предпросмотра
                "THUNBNAIL_IMAGE_CAPTION" => $arResult["NAME"],	// Подпись к Изображению предпросмотра
                "THUNBNAIL_IMAGE_DESCRIPTION" => ($arResult["DETAIL_TEXT"])?$arResult["DETAIL_TEXT"]:$arResult["PREVIEW_TEXT"],	// Описание изображения предпросмотра
                "THUNBNAIL_IMAGE_HEIGHT" => "244",	// Высота изображения предпросмотра (px)
                "THUNBNAIL_IMAGE_WIDTH" => "113",	// Ширина изображения предпросмотра (px)
                "PRODUCTCOMPANY_ORGANIZATION_TYPE_2" => "LocalBusiness",	// Тип Организации
                "PRODUCTCOMPANY_ORGANIZATION_NAME" => "ОДО «Беллесизделие»",	// Название компании
                "PRODUCTCOMPANY_ORGANIZATION_DESCRIPTION" => "Белорусская компания-производитель межкомнатных и входных дверей. ",	// Краткое описание компании
                "PRODUCTCOMPANY_ORGANIZATION_SITE" => "belwooddoors.by",	// Сайт компании
                "PRODUCTCOMPANY_ORGANIZATION_PHONE" => array(	// Телефон компании
                    0 => "+375(17)388-15-58",
                    1 => "+375(17)346-22-48",
                    2 => "+375(44)779-07-72",
                    3 => "+375(44)712-12-48",
                    4 => "",
                ),
                "PRODUCTCOMPANY_ORGANIZATION_POST_CODE" => "220075",	// Почтовый индекс компании
                "PRODUCTCOMPANY_ORGANIZATION_COUNTRY" => "Беларусь",	// Страна компании
                "PRODUCTCOMPANY_ORGANIZATION_REGION" => "Минск и МО",	// Регион Компании
                "PRODUCTCOMPANY_ORGANIZATION_LOCALITY" => "",	// Город Компании
                "PRODUCTCOMPANY_ORGANIZATION_ADDRESS" => "ул. Промышленная, 10, комн. 20",	// Адрес компании
                "PRODUCTCOMPANY_ORGANIZATION_TYPE_3" => "Store",	// Тип Организации
                "PRODUCTCOMPANY_ORGANIZATION_TYPE_4" => "HomeGoodsStore",	// Тип Организации
            ),
                false,
                array(
                    "HIDE_ICONS" => "N"
                )
            );?>


		<?elseif ($arResult["SOUND_CLOUD"]):?>
			<div class="bx-newsdetail-audio">
				<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=<?echo urlencode($arResult["SOUND_CLOUD"])?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
			</div>
		<?elseif ($arResult["SLIDER"] && count($arResult["SLIDER"]) > 1):?>
			<div class="bx-newsdetail-slider">
				<div class="bx-newsdetail-slider-container" style="width: <?echo count($arResult["SLIDER"])*100?>%;left: 0%;">
					<?foreach ($arResult["SLIDER"] as $file):?>
					<div style="width: <?echo 100/count($arResult["SLIDER"])?>%;" class="bx-newsdetail-slider-slide">
						<img src="<?=$file["SRC"]?>" alt="<?=$file["DESCRIPTION"]?>">
					</div>
					<?endforeach?>
					<div style="clear: both;"></div>
				</div>
				<div class="bx-newsdetail-slider-arrow-container-left"><div class="bx-newsdetail-slider-arrow"><i class="fa fa-angle-left" ></i></div></div>
				<div class="bx-newsdetail-slider-arrow-container-right"><div class="bx-newsdetail-slider-arrow"><i class="fa fa-angle-right"></i></div></div>
				<ul class="bx-newsdetail-slider-control">
					<?foreach ($arResult["SLIDER"] as $i => $file):?>
						<li rel="<?=($i+1)?>" <?if (!$i) echo 'class="current"'?>><span></span></li>
					<?endforeach?>
				</ul>
			</div>
		<?elseif ($arResult["SLIDER"]):?>
			<div class="bx-newsdetail-img">
				<img
					src="<?=$arResult["SLIDER"][0]["SRC"]?>"
					width="<?=$arResult["SLIDER"][0]["WIDTH"]?>"
					height="<?=$arResult["SLIDER"][0]["HEIGHT"]?>"
					alt="<?=$arResult["SLIDER"][0]["ALT"]?>"
					title="<?=$arResult["SLIDER"][0]["TITLE"]?>"
					/>
			</div>
		<?elseif (is_array($arResult["DETAIL_PICTURE"])):?>
			<div class="bx-newsdetail-img">
				<img
					src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
					width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
					title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
					/>
			</div>
		<?endif;?>
	<?endif?>

	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3 class="bx-newsdetail-title"><?=$arResult["NAME"]?></h3>
	<?endif;?>
	
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		<?
		if(is_array($arProperty["DISPLAY_VALUE"]))
			$value = implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
		else
			$value = $arProperty["DISPLAY_VALUE"];
		?>
		<?if($arProperty["CODE"] == "FORUM_MESSAGE_CNT"):?>
			<div class="bx-newsdetail-comments"><i class="fa fa-comments"></i> <?=$arProperty["NAME"]?>:
				<?=$value;?>
			</div>
		<?elseif ($arProperty["CODE"] == "RATING"):?>
			<? $rating = strlen($arProperty["VALUE"]) > 0 ? str_replace(',', '.', $arProperty["VALUE"])
				: str_replace(',', '.', $arProperty["DEFAULT_VALUE"])?>
			<div class="feedback__stars">
				<?for ($stars = 5; $stars > 0; $stars--):?>
					<? if ($rating >= 1):?>
						<svg class="icon icon-star"><use xlink:href="#icon-star-1"></use></svg>
					<? elseif ($rating < 1 && $rating > 0):?>
						<svg class="icon icon-star"><use xlink:href="#icon-star-2"></use></svg>
					<? else:?>
						<svg class="icon icon-star"><use xlink:href="#icon-star-0"></use></svg>
					<? endif;?>
					<? $rating--; endfor;?>
			</div>
		<?elseif ($value != ""):?>
			<div class="bx-newsdetail-other"><i class="fa"></i> <?=$arProperty["NAME"]?>:
				<?=$value;?>
			</div>
		<?endif;?>
	<?endforeach;?>

	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	

	<?foreach($arResult["FIELDS"] as $code=>$value):?>
		<?if($code == "SHOW_COUNTER"):?>
			<div class="bx-newsdetail-view"><i class="fa fa-eye"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
				<?=intval($value);?>
			</div>
		<?elseif($code == "SHOW_COUNTER_START" && $value):?>
			<?
			$value = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($value, CSite::GetDateFormat()));
			?>
			<div class="bx-newsdetail-date"><i class="fa fa-calendar-o"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
				<?=$value;?>
			</div>
		<?elseif($code == "TAGS" && $value):?>
			<div class="bx-newsdetail-tags"><i class="fa fa-tag"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
				<?=$value;?>
			</div>
		<?elseif($code == "CREATED_USER_NAME"):?>
			<div class="bx-newsdetail-author"><i class="fa fa-user"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
				<?=$value;?>
			</div>
		<?elseif ($value != ""):?>
			<div class="bx-newsdetail-other"><i class="fa"></i> <?=GetMessage("IBLOCK_FIELD_".$code)?>:
				<?=$value;?>
			</div>
		<?endif;?>
	<?endforeach;?>

	
	<?if($arParams["USE_RATING"]=="Y"):?>
		<div class="bx-newsdetail-separator">|</div>
		<div class="bx-newsdetail-rating">
			<?$APPLICATION->IncludeComponent(
				"bitrix:iblock.vote",
				"flat",
				Array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"ELEMENT_ID" => $arResult["ID"],
					"MAX_VOTE" => $arParams["MAX_VOTE"],
					"VOTE_NAMES" => $arParams["VOTE_NAMES"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
					"SHOW_RATING" => "Y",
				),
				$component
			);?>
		</div>
	<?endif?>

	<div class="row">
		<div class="col-xs-5">
		</div>
	<?
	if ($arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="col-xs-7 text-right">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", $arParams["SHARE_TEMPLATE"], array(
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

</div>
    
<?//Отзыв?>
    <?  $dateTemp = ($arResult["DATE_CREATE"])?$arResult["DATE_CREATE"]:$arResult["ACTIVE_FROM"];
    $a = date_parse_from_format('d.m.Y G:i:s',$dateTemp);
    $timestamp = mktime($a['hour'], $a['minute'], $a['second'], $a['month'], $a['day'], $a['year']);
    $dateTemp1 = date('Y-m-d\TH:i:sO', $timestamp);
    ?>
<?$APPLICATION->IncludeComponent(
	"coffeediz:schema.org.Review", 
	"myReview", 
	array(
		"AUTHOR_PERSON_ADDITIONALNAME" => "",
		"AUTHOR_PERSON_EMAIL" => array(
			0 => $arResult["PROPERTIES"]["AUTHOR_MAIL"]["VALUE"],
			1 => "",
		),
		"AUTHOR_PERSON_FAMILYNAME" => "",
		"AUTHOR_PERSON_IMAGEURL" => "",
		"AUTHOR_PERSON_JOBTITLE" => "",
		"AUTHOR_PERSON_NAME" => $arResult["NAME"],
		"AUTHOR_PERSON_PHONE" => array(
		),
		"AUTHOR_PERSON_URL" => array(
		),
		"AUTHOR_PERSON_URL_SAMEAS" => array(
		),
		"BESTRATING" => "5",
		"CONTRA" => array(
		),
		"DATE_PUBLISHED" => $dateTemp1,
		"ITEMREVIEWED" => "OrganizationAndPlace",
		"MOBILE_URL" => $arResult["DETAIL_PAGE_URL"],
		"NAME" => $arResult["PROPERTIES"]["TITLE_HAND"]["VALUE"],
		"PRO" => array(
			0 => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]):"",
			1 => "",
		),
		"RATINGVALUE" => "5",
		"REVIEWBODY" => ($arResult["DETAIL_TEXT"])?strip_tags($arResult["DETAIL_TEXT"]):strip_tags($arResult["PREVIEW_TEXT"]),
		"REVIEWS_URL" => array(
			0 => $arResult["LIST_PAGE_URL"],
			1 => "",
		),
		"SHOW" => "Y",
		"URL" => $arResult["DETAIL_PAGE_URL"],
		"WORSTRATING" => "1",
		"COMPONENT_TEMPLATE" => "myReview",
		"ITEMREVIEWED_TYPE" => "Organization",
		"ITEMREVIEWED_NAME" => "ООО «СтройДеталь»",
		"ITEMREVIEWED_POST_CODE" => "121170",
		"ITEMREVIEWED_COUNTRY" => "ru",
		"ITEMREVIEWED_REGION" => "Московская область",
		"ITEMREVIEWED_LOCALITY" => "Москва",
		"ITEMREVIEWED_ADDRESS" => "ул. Кутузовский проспект 36А",
		"ITEMREVIEWED_PHONE" => array(
			0 => "+7(499)380-70-58",
			1 => "+7(495)981-6495",
			2 => "+7(964)628-5623",
			3 => "",
			4 => "",
		),
		"ITEMREVIEWED_FAX" => "",
		"ITEMREVIEWED_SITE" => "belwooddoors.ru",
		"ITEMREVIEWED_LOGO" => "/bitrix/templates/general/assets/images/logo.png",
		"DATE_VISITED" => "",
		"EMAIL" => array(
			0 => "bwdru@belwooddoors.by",
			1 => "",
		),
		"ITEMREVIEWED_TYPE_2" => "",
		"TAXID" => "7728297840",
		"CAR_BRAND_NAME" => "",
		"CAR_MODEL" => "",
		"CAR_URL" => "",
		"PRODYEAR" => "",
		"DATAPURCHASED" => "",
		"BODYTYPE" => "",
		"DISPLACEMENT" => "",
		"ENGINTYPE" => "",
		"GEARTYPE" => "",
		"TRANSMISSION" => "",
		"STEERINGWHEEL" => "",
		"HORSEPOWER" => "",
		"RUN" => "",
		"RUNMETRIC" => "",
		"CONFIGURATIONNAME" => ""
	),
	false
);?>



<script type="text/javascript">
	BX.ready(function() {
		var slider = new JCNewsSlider('<?=CUtil::JSEscape($this->GetEditAreaId($arResult['ID']));?>', {
			imagesContainerClassName: 'bx-newsdetail-slider-container',
			leftArrowClassName: 'bx-newsdetail-slider-arrow-container-left',
			rightArrowClassName: 'bx-newsdetail-slider-arrow-container-right',
			controlContainerClassName: 'bx-newsdetail-slider-control'
		});
	});
</script>
