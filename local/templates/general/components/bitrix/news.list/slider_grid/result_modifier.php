<?php
foreach ($arResult["ITEMS"] as &$arItem){
    if($arItem["PREVIEW_PICTURE"]["SRC"]){
        $arItem["PREVIEW_PICTURE"]["SRC"]=CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"] ,array('width'=>480, 'height'=>235), BX_RESIZE_IMAGE_PROPORTIONAL, true)['src'];
    }
}