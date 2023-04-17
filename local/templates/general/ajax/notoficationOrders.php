<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
$result = [
    'success' => false
];
if($request['id']) {
    $arFields = [
        'UF_CHECK' => true
    ];
    $isUpd = HLHelpers::getInstance()->updateElement(8, $request['id'], $arFields);
    if($isUpd)
        $result['success'] = true;
    $result['mess'] = $isUpd;
}

echo json_encode($result)
?>