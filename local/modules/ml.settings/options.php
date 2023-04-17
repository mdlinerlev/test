<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Medialine\Search\Main;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
global $APPLICATION;

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($module_id);


$arIblocks = [];
$iterator = \Bitrix\Iblock\IblockTable::getList([
   'select' => ['ID', 'NAME']
]);
while ($arItem = $iterator->fetch()){
    $arIblocks[$arItem['ID']] = '['.$arItem['ID'].'] '.$arItem['NAME'];
}

$arBlock = [
    [
        [
            "DIV" => "settings",
            "TAB" => "Настройки",
            "TITLE" => "Настройки wsdl от MediaLine",
            "OPTIONS" => [
                [
                    "IBLOCK_ID",
                    "Инфоблок каталога",
                    "",
                    ["selectbox", $arIblocks]
                ],
                [
                    "IBLOCK_OFFERS_ID",
                    "Инфоблок торговых предложений",
                    "",
                    ["selectbox", $arIblocks]
                ],
            ]
        ],
        [
            "DIV" => "access",
            "TAB" => "Доступ",
            "TITLE" => "Уровень доступа к модулю",
        ]
    ],
];
?>

<? if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($arBlock as $arTabs) {
        foreach ($arTabs as $aTab) {
            foreach ($aTab["OPTIONS"] as $arOption) {
                __AdmSettingsSaveOption($module_id, $arOption);
            }
        }
    }

    $REQUEST_METHOD = "POST";
    ob_start();
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/admin/group_rights.php');
    ob_end_clean();

    LocalRedirect($APPLICATION->GetCurPage() . '?lang=' . LANGUAGE_ID . '&mid=' . $module_id);
} ?>

<? foreach ($arBlock as $arTabs) {
    $tabControl = new CAdminTabControl(
        "tabControl",
        $arTabs
    );
    $tabControl->Begin(); ?>
    <form action="<?= $APPLICATION->GetCurPage(); ?>?mid=<?= $module_id; ?>&lang=<?= LANG; ?>" method="post">
        <? foreach ($arTabs as $aTab) {
            if ($aTab["OPTIONS"] && $aTab["DIV"] != "access") {
                $tabControl->BeginNextTab();
                __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
            }
            if ($aTab["DIV"] == "access") {
                $tabControl->BeginNextTab();
                require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
            }
        }
        $tabControl->Buttons([
            "back_url" => $_REQUEST["back_url"],
            "btnApply" => true,
            "btnSave" => true,
        ]); ?>
        <input type="hidden" name="Update" value="Y">
        <?= bitrix_sessid_post(); ?>
    </form>
    <?
    $tabControl->End();
} ?>
