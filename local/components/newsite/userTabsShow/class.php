<?
CBitrixComponent::includeComponentClass("newsite:baseComponent");

/**
 * Class CTabsShow
 */
class CTabsShow extends CShBaseComponent
{

    function onPrepareComponentParams($arParams)
    {
        $this->initComponentTemplate("", $this->getSiteTemplateId());

        return parent::onPrepareComponentParams($arParams);
    }

    function executeComponent()
    {
        return $this->__includeComponent();
    }

    function includeEditHtmlFile($path, $name, $reset = true) {
        /* @var $APPLICATION CMain */
        global $APPLICATION;
        global $USER;
        if (!$USER->isAdmin()) {
            if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path) && filesize($_SERVER["DOCUMENT_ROOT"] . $path)) {
                include $_SERVER["DOCUMENT_ROOT"] . $path;
            }
            return;
        }


        static $counter = 0;
        $counter++;


        if ($reset) {
            $areaIndex = $APPLICATION->editArea->includeAreaIndex;
            $orig = $APPLICATION->editArea->includeLevel;
            $APPLICATION->editArea->includeAreaIndex = array(1000 + $counter);
            $APPLICATION->editArea->includeLevel = -1;
        }

        if ($_SESSION["SESS_INCLUDE_AREAS"]) {
            echo "<span class='htmlblockedit' ondblclick='return false' title=\"" . htmlspecialchars($name) . "\" onclick=\"(new BX.CDialog({'content_url':'/bitrix/admin/public_file_edit.php?path=" . urlencode($path) . "&back_url=" . urlencode($APPLICATION->GetCurPageParam("", array(), false)) . "&lang=" . LANGUAGE_ID . "&template=EMPTY','width':'770','height':'470'})).Show(); return false;\">" . (($name) ? "{$name}" : "Редактировать") . "</span>";
        }

        //$APPLICATION->IncludeFile($path, array(), array("MODE" => "html", "NAME" => $name, "TEMPLATE" => 'EMPTY'));

        if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path) && filesize($_SERVER["DOCUMENT_ROOT"] . $path)) {
            include $_SERVER["DOCUMENT_ROOT"] . $path;
        }


        if ($reset) {
            $APPLICATION->editArea->includeAreaIndex = $areaIndex;
            $APPLICATION->editArea->includeLevel = $orig;
        }
    }

    /**
     * Сортировка массива по полею сорт
     * вызывается из функции uasort
     */
    function sortBySortField($a, $b) {
        return ($a["SORT"] == $b["SORT"]) ? 0 : (($a["SORT"] < $b["SORT"]) ? -1 : 1);
    }
}
