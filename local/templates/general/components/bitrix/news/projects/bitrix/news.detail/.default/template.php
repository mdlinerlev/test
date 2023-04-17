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

    <?if($arParams["DISPLAY_PICTURE"]!="N" && (is_array($arResult["DETAIL_PICTURE"])) && empty($arResult['PROPERTIES']['GALLERY']['VALUE'])):?>
        <img
                class="left"
                border="0"
                src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                width="563px"
                height="<?/*=$arResult["DETAIL_PICTURE"]["HEIGHT"]*/?>"
                alt="<?=(!empty($arResult["DETAIL_PICTURE"]["ALT"])) ? $arResult["DETAIL_PICTURE"]["ALT"] : $arResult["NAME"]?>"
                title="<?=(!empty($arResult["DETAIL_PICTURE"]["ALT"])) ? $arResult["DETAIL_PICTURE"]["ALT"] : $arResult["NAME"]?>"
        />
    <?else :?>
        <div id="projectCarousel" class="carousel">
            <?if($arResult["DETAIL_PICTURE"]["SRC"]):?>
                <div class="carousel__slide">
                    <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" />
                </div>
            <?else :?>
                <div class="carousel__slide">
                    <img src="<?=CFile::GetPath($arResult['PROPERTIES']['GALLERY']['VALUE'][0])?>" data-src="<?=CFile::GetPath($arResult['PROPERTIES']['GALLERY']['VALUE'][0])?>"  data-fancybox="biggallery" />
                </div>
            <?endif?>
        </div>
    <?endif?>
    <?if($arParams["DISPLAY_PICTURE"]!="N" && !empty($arResult['PROPERTIES']['GALLERY']['VALUE'])):?>
        <div id="navCarousel" class="carousel">
            <?if(is_array($arResult["DETAIL_PICTURE"])):?>
                <div class="carousel__slide">
                    <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" data-src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"  data-fancybox="gallery" />
                </div>
            <?endif?>
            <? foreach ($arResult['PROPERTIES']['GALLERY']['VALUE'] as $gallery) {?>
                <div class="carousel__slide">
                    <img src="<?=CFile::GetPath($gallery)?>" data-src="<?=CFile::GetPath($gallery)?>"  data-fancybox="gallery" />
                </div>
            <?}?>
        </div>
    <?endif?>
<script>
    $('#projectCarousel .carousel__slide img').click(function() {
        $('#navCarousel [data-index="0"] [data-fancybox="gallery"]').trigger('click');
    })

    const navCarousel = new Carousel(document.querySelector("#navCarousel"), {
        Dots: false,
        Navigation: true,

        //infinite: false,
        center: true,
        slidesPerPage: 1,
    });

    Fancybox.bind('#navCarousel [data-fancybox="gallery"]', {
        Carousel: {
            on: {
                change: (that) => {
                    navCarousel.slideTo(navCarousel.findPageForSlide(that.page), {
                        friction: 0,
                    });
                },
            },
        },
    });
</script>
    <?/*if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;*/?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
	<?/*if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;*/?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<p><?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?></p>


    <?if (!empty($arResult["PROPERTIES"]["USED_DOORS"]["VALUE"])){
        global $arrProjectsDoorsFilter;
        $arrProjectsDoorsFilter = ["IBLOCK_ID" => IBLOCK_ID_CATALOG, "=ID" => $arResult["PROPERTIES"]["USED_DOORS"]["VALUE"]];
        ?>
        <div class="card-projects__doors">
	        <div class="card-projects__doors-zag">Двери из проекта:</div>
            <div class="card-projects__doors-wrp">
                <?CJSCore::Init("currency");?>
                <script src="/bitrix/templates/general/assets/js/scripts.js"></script>
                <script src="/bitrix/templates/general/components/jorique/catalog.section/similar_products/script.js"></script>
                <?$APPLICATION->IncludeComponent("jorique:catalog.section",
                    "used_doors",
                    array(
                        "AVAIL" => $_GET['avail'] == 1,
                        "IBLOCK_TYPE" => "catalog",
                        "IBLOCK_ID" => IBLOCK_ID_CATALOG,
                        "ELEMENT_SORT_FIELD" => [],
                        "ELEMENT_SORT_ORDER" => [],
                        "ELEMENT_SORT_FIELD2" => [],
                        "ELEMENT_SORT_ORDER2" => [],
                        "PROPERTY_CODE" => array(
                            0 => "CML2_NO_SHOW_IN_SITE",
                            1 => "COUNTRY",
                            2 => "BRAND_L_EXCHANGE",
                            3 => "COLLECTION",
                            4 => "MANUFACTURER",
                            5 => "NUMBER_STRIP",
                            6 => "DURABILITY_CLASS",
                            7 => "WIDTH",
                            8 => "PRODUCT_TYPE",
                            9 => "NEWPRODUCT",
                            10 => "SALELEADER",
                            11 => "DOUBLE_BYPASS_L_EXCHANGE",
                            12 => "DOUBLE_POCKET_L_EXCHANGE",
                            13 => "DOUBLE_SWING_L_EXCHANGE",
                            14 => "FOUR_PANEL_BI_FOLD_L_EXCHANGE",
                            15 => "HEIGHT_L_EXCHANGE",
                            16 => "GLASS_REF",
                            17 => "BLOG_POST_ID",
                            18 => "IS_AVAILABLE",
                            19 => "MAGIC_L_EXCHANGE",
                            20 => "MODEL_L_EXCHANGE",
                            21 => "ROOT_IMPORT",
                            22 => "SINGLE_BARN_L_EXCHANGE",
                            23 => "SINGLE_BYPASS_L_EXCHANGE",
                            24 => "SINGLE_POCKET_L_EXCHANGE",
                            25 => "SINGLE_SWING_L_EXCHANGE",
                            26 => "STOCKASTANA_L_EXCHANGE",
                            27 => "STOCKMOSKVA_L_EXCHANGE",
                            28 => "TWO_PANEL_BI_FOLD_L_EXCHANGE",
                            29 => "WIDTH_L_EXCHANGE",
                            30 => "ACTIVE_SKLAD",
                            31 => "ARTICLE",
                            32 => "BAZOVAYA_MODEL_L_EXCHANGE",
                            33 => "FREE",
                            34 => "GUARANTEE",
                            35 => "DESIGN",
                            36 => "BOX_SQUARE",
                            37 => "BLOG_COMMENTS_CNT",
                            38 => "WHERE",
                            39 => "MATERIAL",
                            40 => "MODEL_VKHODNOY_DVERI_L_EXCHANGE",
                            41 => "NALICHIE_I_VID_RUCHKI_L_EXCHANGE",
                            42 => "XML_NEW",
                            43 => "Options",
                            44 => "GLASS",
                            45 => "DECORATION",
                            46 => "DISABLED_TYPE_INTERIOR",
                            47 => "PEREDAVAT_NA_SAYT_L_EXCHANGE",
                            48 => "PROIZVODSTVENNAYA_SERIYA_L_EXCHANGE",
                            49 => "FLOOR_SIZE",
                            50 => "FASKA_L_EXCHANGE",
                            51 => "SERIYNAYA_PRODUKTSIYA_L_EXCHANGE",
                            52 => "OLD_PRICE",
                            53 => "GLASS_TYPE",
                            54 => "STYLE",
                            55 => "STORONA_OTKRYVANIYA_L_EXCHANGE",
                            56 => "TYPE",
                            57 => "LAMINATE_TYPE",
                            58 => "WIDTH_OLD",
                            59 => "MONTAGE_HIDDEN",
                            60 => "FURNITURE",
                            61 => "TSVET_VNUTRI_L_EXCHANGE",
                            62 => "TSVET_SNARUZHI_L_EXCHANGE",
                            63 => "MINIMUM_PRICE",
                            64 => "MAXIMUM_PRICE",
                            65 => "VIDEO",
                            66 => "Construction",
                            67 => "FLOOR_COLOR",
                            68 => "GLASSING",
                            69 => "Filling",
                            70 => "",
                        ),
                        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                        "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                        "BASKET_URL" => "/personal/cart/",
                        "ACTION_VARIABLE" => "action",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "FILTER_NAME" => "arrProjectsDoorsFilter",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => 3600,
                        "CACHE_FILTER" => false,
                        "CACHE_GROUPS" => "Y",
                        "SET_TITLE" => 1,
                        "MESSAGE_404" => "",
                        "SET_STATUS_404" => "Y",
                        "SHOW_404" => "Y",
                        "FILE_404" => $arParams["FILE_404"],
                        "DISPLAY_COMPARE" => "",
                        "PAGE_ELEMENT_COUNT" => 8,
                        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                        "PRICE_CODE" => [0=>"BASE"],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => 1,

                        "PRICE_VAT_INCLUDE" => 1,
                        "USE_PRODUCT_QUANTITY" => 1,
                        "ADD_PROPERTIES_TO_BASKET" => "Y",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                        "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                        "PAGER_BASE_LINK" => $APPLICATION->GetCurDir(),
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

                        "OFFERS_CART_PROPERTIES" => Array
                        (
                            0 => "COLOR",
                            1 => "ARTICLE",
                            2 => "SIZE"
                        ),
                        "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                        "OFFERS_PROPERTY_CODE" => array(
                            0 => "NEWPRODUCT",
                            1 => "SALELEADER",
                            2 => "SPECIALOFFER",
                            3 => "FREE",
                            4 => "SKLAD",
                            5 => "IS_AVAILABLE",
                            6 => "ROOT_IMPORT",
                            7 => "ARTICLE",
                            8 => "INNER_PHOTO",
                            9 => "GROUP_COLOR",
                            10 => "DINAMIC_HITS",
                            11 => "TRANSOMS",
                            12 => "BOX",
                            13 => "JAMB",
                            14 => "XML_NEW",
                            15 => "BAR",
                            16 => "OLD_PRICE",
                            17 => "SIDE",
                            18 => "TWO_LEAF_PHOTO",
                            19 => "COLOR_IN",
                            20 => "COLOR_OUT",
                            21 => "GLASS_COLOR",
                            22 => "COLOR",
                            23 => "SIZE",
                            24 => "",
                        ),


                        "OFFERS_SORT_FIELD" => "PROPERTY_IS_AVAILABLE",
                        "OFFERS_SORT_FIELD2" => "CATALOG_PRICE_1",
                        "OFFERS_SORT_ORDER" => "DESC",
                        "OFFERS_SORT_ORDER2" => "ASC",


                        "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                        "SECTION_ID" => "",
                        "SECTION_CODE" => "",
                        "SECTION_URL" => "",
                        "DETAIL_URL" => "",
                        "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                        'CONVERT_CURRENCY' => "Y",
                        'CURRENCY_ID' => "BYR",
                        'HIDE_NOT_AVAILABLE' => "N",

                        'LABEL_PROP' => $arParams['LABEL_PROP'],
                        'ADD_PICT_PROP' => "MORE_PHOTO",
                        'PRODUCT_DISPLAY_MODE' => "Y",

                        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                        'OFFER_TREE_PROPS' => Array
                        (
                            0 => "COLOR",
                            1 => "COLOR_OUT",
                            2 => "COLOR_IN",
                            3 => "GLASS_COLOR",
                            4 => "SIDE",
                            5 => "SIZE",
                        ),
                        'PRODUCT_SUBSCRIPTION' => "N",
                        'SHOW_DISCOUNT_PERCENT' => "Y",
                        'SHOW_OLD_PRICE' => "Y",
                        'MESS_BTN_BUY' => "Купить",
                        'MESS_BTN_ADD_TO_BASKET' => "В корзину",
                        'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                        'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                        'MESS_NOT_AVAILABLE' => "Нет в наличии",

                        'TEMPLATE_THEME' => "",
                        "ADD_SECTIONS_CHAIN" => "N",
                        'ADD_TO_BASKET_ACTION' => "",
                        'SHOW_CLOSE_POPUP' => "",
                        'COMPARE_PATH' => "",
                        'BACKGROUND_IMAGE' => "",
                        'DISABLE_INIT_JS_IN_COMPONENT' => "N",
                        'SECTION_USER_FIELDS' => array(
                            'UF_FILTER'
                        ),
                    ),
                    false
                );?>
            </div>
        </div>
        <?
    }?>



	<div style="clear:both"></div>
	<br />
	<?/*foreach($arResult["FIELDS"] as $code=>$value):
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
	<?endforeach;*/
	foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
        <?if ($pid == "USED_DOORS") continue;?>

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

