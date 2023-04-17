<?php

$arFilter = ['IBLOCK_ID' => IBLOCK_ID_PROJECTS];
$arSelect = ['NAME', 'CODE','SECTION_PAGE_URL'];
$arSect = CIBlockSection::GetList( [], $arFilter,false,    $arSelect, false);
while($res = $arSect->GetNext())
{
    $arResult['sectionProjects'][$res['SECTION_PAGE_URL'].$res['CODE']] = $res['NAME'];
}
