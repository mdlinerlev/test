<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
\Bitrix\Main\Page\Asset::getInstance()->addJs('/personal-b2b/test/dist/index_bundle.js');
?>
    <div id="root">
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>