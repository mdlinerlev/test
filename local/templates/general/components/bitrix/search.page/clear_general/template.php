<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
	$APPLICATION->SetPageProperty('search-query', $arResult["REQUEST"]["QUERY"]);
?>
<section class="catalog catalog--search <?$APPLICATION->ShowProperty('search-found')?>">
    <div class="content-container">
        <h1 class="catalog__search-header">Результаты поиска
        </h1>
<!--        <div class="catalog__search-value">По запросу «--><?//= $arResult["REQUEST"]["~QUERY"] ?><!--»</div>-->
        <? if ($arParams["SHOW_TAGS_CLOUD"] == "Y") {
            $arCloudParams = Array(
                "SEARCH" => $arResult["REQUEST"]["~QUERY"],
                "TAGS" => $arResult["REQUEST"]["~TAGS"],
                "CHECK_DATES" => $arParams["CHECK_DATES"],
                "arrFILTER" => $arParams["arrFILTER"],
                "SORT" => $arParams["TAGS_SORT"],
                "PAGE_ELEMENTS" => $arParams["TAGS_PAGE_ELEMENTS"],
                "PERIOD" => $arParams["TAGS_PERIOD"],
                "URL_SEARCH" => $arParams["TAGS_URL_SEARCH"],
                "TAGS_INHERIT" => $arParams["TAGS_INHERIT"],
                "FONT_MAX" => $arParams["FONT_MAX"],
                "FONT_MIN" => $arParams["FONT_MIN"],
                "COLOR_NEW" => $arParams["COLOR_NEW"],
                "COLOR_OLD" => $arParams["COLOR_OLD"],
                "PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
                "SHOW_CHAIN" => "N",
                "COLOR_TYPE" => $arParams["COLOR_TYPE"],
                "WIDTH" => $arParams["WIDTH"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "RESTART" => $arParams["RESTART"],
            );

            if (is_array($arCloudParams["arrFILTER"])) {
                foreach ($arCloudParams["arrFILTER"] as $strFILTER) {
                    if ($strFILTER == "main") {
                        $arCloudParams["arrFILTER_main"] = $arParams["arrFILTER_main"];
                    } elseif ($strFILTER == "forum" && IsModuleInstalled("forum")) {
                        $arCloudParams["arrFILTER_forum"] = $arParams["arrFILTER_forum"];
                    } elseif (strpos($strFILTER, "iblock_") === 0) {
                        foreach ($arParams["arrFILTER_" . $strFILTER] as $strIBlock)
                            $arCloudParams["arrFILTER_" . $strFILTER] = $arParams["arrFILTER_" . $strFILTER];
                    } elseif ($strFILTER == "blog") {
                        $arCloudParams["arrFILTER_blog"] = $arParams["arrFILTER_blog"];
                    } elseif ($strFILTER == "socialnetwork") {
                        $arCloudParams["arrFILTER_socialnetwork"] = $arParams["arrFILTER_socialnetwork"];
                    }
                }
            }
            $APPLICATION->IncludeComponent("bitrix:search.tags.cloud", ".default", $arCloudParams, $component, array("HIDE_ICONS" => "Y"));
        }
        ?>
        <div class="catalog__content">
            <div class="catalog__search">
                <form action="" method="get" class="js-search-form">
                    <input type="hidden" name="tags" value="<? echo $arResult["REQUEST"]["TAGS"] ?>"/>
                    <input type="hidden" name="how" value="<? echo $arResult["REQUEST"]["HOW"] == "d" ? "d" : "r" ?>"/>

                    <? if ($arParams["USE_SUGGEST"] === "Y"):
                        if (strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"])) {
                            $arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
                            $obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
                            $obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
                        }
                        ?>
                        <? $APPLICATION->IncludeComponent(
                        "bitrix:search.suggest.input",
                        "",
                        array(
                            "NAME" => "q",
                            "VALUE" => $arResult["REQUEST"]["~QUERY"],
                            "INPUT_SIZE" => -1,
                            "DROPDOWN_SIZE" => 10,
                            "FILTER_MD5" => $arResult["FILTER_MD5"],
                        ),
                        $component, array("HIDE_ICONS" => "Y")
                    ); ?>
                    <? else: ?>
                        <label for="q" class="sr-only">Поиск</label>
                        <input id="search-input" class="catalog-search__input" type="text" name="q"
                               value="<?= $arResult["REQUEST"]["QUERY"] ?>"/>
                    <? endif; ?>

                    <button type="submit" class="catalog-search__button catalog-search__button--submit button"
                            type="submit" value="<? echo GetMessage("CT_BSP_GO") ?>"/>
                    <span>Найти</span></button>
                </form>
            </div>
            <? if (isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
                ?>
                <div class="search-language-guess">
                    <? echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#" => '<a href="' . $arResult["ORIGINAL_QUERY_URL"] . '">' . $arResult["REQUEST"]["ORIGINAL_QUERY"] . '</a>')) ?>
                </div><br/><?
            endif; ?>

<!--            <div class="catalog__list">-->
<!--                --><?// if ($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false): ?>
<!--                --><?// elseif ($arResult["ERROR_CODE"] != 0): ?>
<!--                    <p>--><?//= GetMessage("CT_BSP_ERROR") ?><!--</p>-->
<!--                    --><?// ShowError($arResult["ERROR_TEXT"]); ?>
<!--                    <p>--><?//= GetMessage("CT_BSP_CORRECT_AND_CONTINUE") ?><!--</p>-->
<!--                    <br/><br/>-->
<!--                    <p>--><?//= GetMessage("CT_BSP_SINTAX") ?><!--<br/><b>--><?//= GetMessage("CT_BSP_LOGIC") ?><!--</b></p>-->
<!--                    <table border="0" cellpadding="5">-->
<!--                        <tr>-->
<!--                            <td align="center" valign="top">--><?//= GetMessage("CT_BSP_OPERATOR") ?><!--</td>-->
<!--                            <td valign="top">--><?//= GetMessage("CT_BSP_SYNONIM") ?><!--</td>-->
<!--                            <td>--><?//= GetMessage("CT_BSP_DESCRIPTION") ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td align="center" valign="top">--><?//= GetMessage("CT_BSP_AND") ?><!--</td>-->
<!--                            <td valign="top">and, &amp;, +</td>-->
<!--                            <td>--><?//= GetMessage("CT_BSP_AND_ALT") ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td align="center" valign="top">--><?//= GetMessage("CT_BSP_OR") ?><!--</td>-->
<!--                            <td valign="top">or, |</td>-->
<!--                            <td>--><?//= GetMessage("CT_BSP_OR_ALT") ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td align="center" valign="top">--><?//= GetMessage("CT_BSP_NOT") ?><!--</td>-->
<!--                            <td valign="top">not, ~</td>-->
<!--                            <td>--><?//= GetMessage("CT_BSP_NOT_ALT") ?><!--</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td align="center" valign="top">( )</td>-->
<!--                            <td valign="top">&nbsp;</td>-->
<!--                            <td>--><?//= GetMessage("CT_BSP_BRACKETS_ALT") ?><!--</td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                --><?// endif; ?>
<!--            </div>-->
        </div>
</section>