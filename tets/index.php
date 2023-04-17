<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Поиск");
?>

<?
$message = 'Проверка отправки';
\Bitrix\Main\Mail\Event::sendImmediate([
    "EVENT_NAME" => "IMPORT_RESULT",
    "LID" => 's1',
    "C_FIELDS" => ["MESSAGE" => $message]
]);

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>