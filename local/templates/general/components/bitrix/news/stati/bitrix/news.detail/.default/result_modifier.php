<?php
\Bitrix\Main\Loader::includeModule('dev2fun.opengraph');
\Dev2fun\Module\OpenGraph::Show($arResult['ID'],'element');

$arFilter = ['IBLOCK_ID' => IB_ID_STATI];
$arSelect = ['NAME', 'CODE'];
$arSect = CIBlockSection::GetList( [], $arFilter,false,    $arSelect, false);
while($res = $arSect->GetNext())
{
    $arResult['sectionStati'][$res['CODE']] = $res['NAME'];
}