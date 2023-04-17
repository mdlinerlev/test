<?php
foreach ($arResult["ITEMS"] as &$arItem){
    if($arItem["PREVIEW_PICTURE"]["SRC"]){
        $arItem["PREVIEW_PICTURE"]["SRC"]=CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"] ,array('width'=>570, 'height'=>540), BX_RESIZE_IMAGE_EXACT, true)['src'];
    }
}