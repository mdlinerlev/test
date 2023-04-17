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
//$this->addExternalCss("/bitrix/css/main/font-awesome.css");

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
    $INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "title-search";

$BUTTON_ID = trim($arParams["~BUTTON_ID"]);
if(strlen($BUTTON_ID) <= 0)
    $BUTTON_ID = "title-search-button";

$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>

    <form action="<?echo $arResult["FORM_ACTION"]?>" class="header_search__form  js-search-form">
        <div class="header_search__block" id="<?=$CONTAINER_ID?>">
            <label for="header_search__label" class="sr-only">Поиск</label>
            <input id="<?echo $INPUT_ID?>" type="text" name="q" value="<?=htmlspecialcharsbx($_REQUEST["q"])?>" autocomplete="off" class="header_search__input" placeholder="<?=$arParams["~PLACEHOLDER"]?>"/>
        </div>
        <button id="<?echo $BUTTON_ID?>" aria-label="search" class="header_search__button header_search__button--submit button <?=(empty($_REQUEST['q'])?'disabled':'');?>" type="submit">
            <svg class="header_search__icon"><use xlink:href="#search"></use></svg>
        </button>
    </form>

<?endif?>
<script>
    BX.ready(function(){
        new JCTitleSearch({
            'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
            'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
            'INPUT_ID': '<?echo $INPUT_ID?>',
            'BUTTON_ID': '<?echo $BUTTON_ID?>',
            'MIN_QUERY_LEN': 3
        });
    });
</script>

