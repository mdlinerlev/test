<?
$result = [
    'errorMsg' => ''
];
if (\Bitrix\Main\Engine\CurrentUser::get()->isAdmin()) {
    if ($request['itemId'] > 0) {
        $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
        $item = $entity::getByPrimary(intval($request['itemId']), [
            'select' => ['ID', 'PROPERTY_PRICE_' => 'PRICE_NAME', 'IBLOCK_ID']
        ]);
        if ($arItem = $item->fetch()) {
            $iterator = \Bitrix\Catalog\GroupLangTable::getList([
                'select' => ['ID', 'CATALOG_GROUP_ID'],
                'filter' => ['CATALOG_GROUP_ID' => $request['newPrice']]
            ]);
            if ($arGroup = $iterator->fetch()) {
                CIBlockElement::SetPropertyValuesEx($arItem['ID'], $arItem['IBLOCK_ID'], ['PRICE_NAME' => $arGroup['CATALOG_GROUP_ID']]);
            } else {
                $result['errorMsg'] = 'Не верный тип цен';
            }
        } else {
            $result['errorMsg'] = 'Элемент не найден';
        }
    } else {
        $result['errorMsg'] = 'Не верный id элемента';
    }
} else {
    $result['errorMsg'] = 'Нет доступа';
}

$result['success'] = (empty($result['errorMsg']) ? true : false);
echo \Bitrix\Main\Web\Json::encode($result);
