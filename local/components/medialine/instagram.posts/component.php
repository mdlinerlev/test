<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/init/classes/CInstagram.php");

if(!isset($arParams['CACHE_TIME'])){
    $arParams['CACHE_TIME'] = 86400;
}

$arResult['ITEMS_COUNT'] = ($arParams['ITEMS_COUNT'] && intval($arParams['ITEMS_COUNT']) > 0) ? intval($arParams['ITEMS_COUNT']) : 10;
$arResult['ITEMS_VISIBLE'] = ($arParams['ITEMS_VISIBLE'] && intval($arParams['ITEMS_VISIBLE']) > 0) ? intval($arParams['ITEMS_VISIBLE']) : 10;
$arResult['TOKEN'] = $arParams['TOKEN'] ? $arParams['TOKEN'] : 'IGQVJWQUNFN2owN0ViTGZAHSzBZAY0E4RVZACa21SOUFtdFVBUkF4UVVEUjRrU3JtR01uMm1RdzNzYVNFVmJsWVd2T1BPVl9kNnNXLWkzdkZA5LTkxMXVvM0U3WGtwWndKanZAXVFJQdkVVdFRseTNzWjZArYwZDZD';
$arResult['TITLE'] = $arParams['TITLE'] ? $arParams['TITLE'] : GetMessage('INSTAGRAM_TITLE');
$arResult['ALL_TITLE'] = $arParams['ALL_TITLE'] ? $arParams['ALL_TITLE'] : GetMessage('INSTAGRAM_ALL_ITEMS');
$arResult['TEXT_LENGTH'] = ($arParams['TEXT_LENGTH'] && intval($arParams['TEXT_LENGTH']) > 0) ? intval($arParams['TEXT_LENGTH']) : 400;

if(!is_object($GLOBALS['USER'])){
    $GLOBALS['USER'] = new CUser();
}

if(
    $this->startResultCache(
        $arParams['CACHE_TIME'],
        array(
            ($arParams['CACHE_GROUPS'] === 'N' ? false : $GLOBALS['USER']->GetGroups()),
            $arResult
        )
    )
){
    $obInstagram = new CInstagram($arResult['TOKEN'], $arParams['ITEMS_COUNT']);
//    pr($arResult['TOKEN'] );
    $arData = $obInstagram->getInstagramPosts();
    $arUser = $obInstagram->getInstagramUser();

    if($arData){
        if($arData['error']['message']){
            $arResult['ERROR'] = $arData['error']['message'];
        }
        elseif($arData['data']){
            $arResult['ITEMS'] = array_slice($arData['data'], 0, $arParams['ITEMS_COUNT']);
            $arResult['USER']['username'] = $arUser['username'];
        }
    }

    if($arResult['ERROR']){
        $this->AbortResultCache();
        ?>
        <?if($GLOBALS['USER']->IsAdmin()):?>
            <br>
            <div class="alert alert-danger">
                <strong>Error: </strong><?=$arResult['ERROR']?>
            </div>
        <?endif;?>
        <?
    }

    $this->IncludeComponentTemplate();
}

?>