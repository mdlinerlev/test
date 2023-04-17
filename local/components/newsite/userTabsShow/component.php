<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/* @var $this CBitrixComponent */

$arResult = ["TABS" => []];
$first = true;


if (!is_array($arParams["TAB_NAME"])) {

    $arParams["TAB_NAME"] = json_decode($arParams["~TAB_NAME"], true);

    foreach ($arParams["TAB_NAME"] as $key => &$value) {

        if (empty($value["ELEMENT_ID"])) {
            unset($arParams["TAB_NAME"][ $key ]);
        }

        $value = ["NAME" => $value["ELEMENT_ID"], "ELEMENT_ID" => $value["ELEMENT_ID"]];
    }
} else {

    foreach ($arParams["TAB_NAME"] as $key => &$value) {
        if (!strlen(trim($value))) {
            unset($arParams["TAB_NAME"][ $key ]);
        }

        $value = ["NAME" => $value, "ELEMENT_ID" => 0];
    }
}

unset($value);

foreach ($arParams["TAB_NAME"] as $index => $tabName) {


    if (!strlen(trim($arParams["TAB_{$index}_INCLUDEFILE"])) || $arParams["TAB_{$index}_ACTIVE"] == "N") {
        continue;
    }

    $arParams["TAB_{$index}_GROUP"] = (array)$arParams["TAB_{$index}_GROUP"];

    if (
        ($USER->IsAuthorized() && $arParams["TAB_{$index}_AUTHONLY"] == "Y" &&
            (empty($arParams["TAB_{$index}_GROUP"]) || count(array_intersect($USER->GetUserGroupArray(),
                    $arParams["TAB_{$index}_GROUP"])))) ||
        (!$USER->IsAuthorized() && $arParams["TAB_{$index}_NOAUTHONLY"] == "Y") ||
        $USER->isAdmin()
    ) {


        $tabContent = "";
        $include = true;
//        Проверка что вкладка не пуста
        if (!$USER->isAdmin()) {
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $arParams["TAB_{$index}_INCLUDEFILE"])) {
                continue;
            }

            $showTab = true;
            foreach (GetModuleEvents("main", 'OnGetContentUserTabsShow', true) as $arEvent) {
                if (ExecuteModuleEventEx($arEvent,
                        ["INCLUDEFILE" => $arParams["TAB_{$index}_INCLUDEFILE"]]) === false
                ) {
                    $showTab = false;
                }
            }

            if (!$showTab) {
                continue;
            }
            $content = file_get_contents($_SERVER["DOCUMENT_ROOT"] . $arParams["TAB_{$index}_INCLUDEFILE"]);

            //не проверяю если есть компоненты с ajax баг с непонятно куда вставленным конткнтом
            if (!preg_match("/['\"]{1}AJAX_MODE['\"]{1}[ \t]*=>[ \t]*['\"]{1}Y['\"]{1}[ \t]*[,]{1}/", $content)) {
                $include = false;
                $this->getTemplate()->SetViewTarget("TAB_{$index}_INCLUDEFILE" . $this->getCacheID());
                include $_SERVER["DOCUMENT_ROOT"] . $arParams["TAB_{$index}_INCLUDEFILE"];
                $this->getTemplate()->EndViewTarget();
                $tabContent = trim($APPLICATION->GetViewContent("TAB_{$index}_INCLUDEFILE" . $this->getCacheID()));

                if (empty($tabContent)) {
                    continue;
                }
            }
        }


        $arResult["TABS"][ $index ] = [
            "NAME"         => $tabName["NAME"],
            "SORT"         => $tabName["SORT"] ?: 500,
            "ELEMENT_ID"   => $tabName["ELEMENT_ID"],
            "INCLUDEFILE"  => $arParams["TAB_{$index}_INCLUDEFILE"],
            "CURRENT"      => (count($arResult["TABS"]) == 0),
            "HASH"         => empty($arParams["TAB_{$index}_HASH"]) ? ("tab_" . md5($tabName["NAME"])) : $arParams["TAB_{$index}_HASH"],
            "CONTENT"      => $tabContent,
            "INCLUDE_FILE" => $include,
        ];
    }
}
uasort($arResult["TABS"], array($this, "sortBySortField"));


$arResult["TABS"] = array_values($arResult["TABS"]);

$this->IncludeComponentTemplate();
