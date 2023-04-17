<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$iterator = \Bitrix\Main\UserTable::getList([
    'select' => ['ID'],
    'filter' => ['!=UF_FAVORITES' => false]
]);
while ($user = $iterator->fetch()) {
    $el = new CUser();
    $el->Update($user['ID'], ['UF_FAVORITES' => serialize([])]);
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>