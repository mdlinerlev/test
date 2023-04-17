<?
if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/ml.settings/admin/list.php")) {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/ml.settings/admin/list.php");
} else {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/ml.settings/admin/list.php");
}
