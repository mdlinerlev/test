<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

foreach ($arResult['ITEMS'] as &$arItem){
    if(!empty($arItem['OFFERS']) && $arItem['OFFER_ID_SELECTED'] > 0){
        foreach ($arItem['OFFERS'] as $arOffer){
            if($arOffer['ID'] == $arItem['OFFER_ID_SELECTED']){
                $arItem['OFFER_SELECTED'] = $arOffer;
                break;
            }
        }
    }
}

$price = getUserPrice();
$rsGroup = \Bitrix\Catalog\GroupTable::getByPrimary($price, [
    'select' => ['NAME']
]);
if($arPrice = $rsGroup->fetch()){
   $price = $arPrice['NAME'];
}

$arResult['PRICE'] = $price;
if($arParams['FAVORITES']){

    if(!empty($arParams['FAVORITES']['ITEMS']) && empty($arParams['FAVORITES']['OFFERS'])){
        $arFilter['ID'] = $arParams['FAVORITES']['ITEMS'];
    }elseif(!empty($arParams['FAVORITES']['OFFERS']) && empty($arParams['FAVORITES']['ITEMS'])){
        $arFilter['ID'] = CIBlockElement::SubQuery('PROPERTY_CML2_LINK', ['ID' => $arParams['FAVORITES']['OFFERS']]);
    }elseif(!empty($arParams['FAVORITES']['ITEMS']) && !empty($arParams['FAVORITES']['OFFERS'])){
        $arFilter = [
            [
                'LOGIC' => 'OR',
                [
                    'ID' => $arParams['FAVORITES']['ITEMS'],
                ],
                [
                    'ID' => CIBlockElement::SubQuery('PROPERTY_CML2_LINK', ['ID' => $arParams['FAVORITES']['OFFERS']]),
                ]
            ],
        ];
    }else{
        $arFilter['ID'] = 0;
    }
    $arFilter['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
    $arFilter['!IBLOCK_SECTION_ID'] = false;
    $arFilter['ACTIVE_DATE'] = 'Y';
    $arFilter['ACTIVE'] = 'Y';

    $arSelect = ["ID", "IBLOCK_SECTION_ID"];
    $res = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
    $arSectionIDs = [];
    while ($item = $res->GetNext()){
        $arSectionIDs[] = $item['IBLOCK_SECTION_ID'];
    }


    if(!empty($arSectionIDs)){
        $rsSection = \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionIDs],
            'select' =>  ['ID', 'NAME'],
        ]);
        while($arSection = $rsSection->fetch()){
            $arResult['SECTIONS'][$arSection['ID']] = $arSection;
        }
    }
}