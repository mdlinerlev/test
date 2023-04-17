<?
$result = ['success' => false, 'errorMsg' => ''];
if (\Bitrix\Main\Engine\CurrentUser::get()->getId() && $request['id'] && $_FILES['PREVIEW_PICTURE']) {
    $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
    $element = $entity::getByPrimary(intval($request['id']), [
        'select' => ['ID']
    ]);
    if($arItem = $element->fetch()){

        move_uploaded_file($_FILES['PREVIEW_PICTURE']["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $_FILES['PREVIEW_PICTURE']["name"]);
        $arFile = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $_FILES['PREVIEW_PICTURE']["name"]);

        $el = new CIBlockElement();
        if($el->Update($arItem['ID'], ['PREVIEW_PICTURE' => $arFile])){
            $result['success'] = true;
        }else{
            $result['errorMsg'] = $el->LAST_ERROR;
        }
    }else{
        $result['errorMsg'] = 'Элемент не найден';
    }
}else{
    $result['errorMsg'] = 'Нет доступа';
}

echo \Bitrix\Main\Web\Json::encode($result);