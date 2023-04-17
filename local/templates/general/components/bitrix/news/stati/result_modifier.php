<?php

$arFilter = ['IBLOCK_ID' => IB_ID_STATI];
$arSelect = ['NAME', 'CODE'];
$arSect = CIBlockSection::GetList( [], $arFilter,false,    $arSelect, false);
while($res = $arSect->GetNext())
{
    $arResult['sectionStati'][$res['CODE']] = $res['NAME'];
}
