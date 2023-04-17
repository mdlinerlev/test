<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


$arComponentParameters = [
    "GROUPS"     => [],
    "PARAMETERS" => [
        "TAB_NAME" => [
            "PARENT"   => "BASE",
            "NAME"     => "Название вкладки",
            "TYPE"     => "STRING",
            "DEFAULT"  => "",
            "MULTIPLE" => "Y",
            "REFRESH"  => "Y",
        ],
    ],
];

$groupList = [];

$dbl = \Bitrix\Main\GroupTable::query()
    ->setFilter(["!=ID" => [1, 2]])
    ->setSelect(["ID", "NAME"])
    ->exec();

while ($res = $dbl->fetch()) {
    $groupList[ $res["ID"] ] = $res["NAME"];
}


if (!empty($arCurrentValues["TAB_NAME"])) {

    if (!is_array($arCurrentValues["TAB_NAME"])) {
        $arCurrentValues["TAB_NAME"] = json_decode($arCurrentValues["TAB_NAME"], true);
    }

    foreach ($arCurrentValues["TAB_NAME"] as $index => $tabName) {

        if ((!strlen(trim($tabName)) && !is_array($tabName)) || (is_array($tabName) && empty($tabName["ELEMENT_ID"]))) {
            continue;
        }

        if (is_array($tabName)) {
            $res = CIBlockElement::GetList(false,
                ["ID" => $tabName["ELEMENT_ID"], "IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"]], false, false,
                ["NAME"])->Fetch();

            if (!$res) {
                continue;
            }

            $tabName = $res["NAME"];
        }


        $arComponentParameters["GROUPS"]["TAB_{$index}"] = [
            "NAME" => "Настройки вкладки \"{$tabName}\"",
            "SORT" => 1000 + $index,
        ];


        $arComponentParameters["PARAMETERS"]["TAB_{$index}_ACTIVE"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Вкладка активна",
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "Y",
        ];

        $arComponentParameters["PARAMETERS"]["TAB_{$index}_AUTHONLY"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Доступна авторизованным",
            "TYPE"    => "CHECKBOX",
            "REFRESH" => "Y",
            "DEFAULT" => "Y",
        ];

        if ($arCurrentValues["TAB_{$index}_AUTHONLY"] == "Y") {

            $arComponentParameters["PARAMETERS"]["TAB_{$index}_GROUP"] = [
                "PARENT"   => "TAB_{$index}",
                "NAME"     => "Группа пользотвателей для авторизованных",
                "TYPE"     => "LIST",
                "MULTIPLE" => "Y",
                "VALUES"   => $groupList,
            ];
        }


        $arComponentParameters["PARAMETERS"]["TAB_{$index}_NOAUTHONLY"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Доступна не авторизованным",
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "Y",
        ];


        $arComponentParameters["PARAMETERS"]["TAB_{$index}_SORT"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Сортировка",
            "TYPE"    => "FILE",
            "DEFAULT" => "500",
        ];


        $arComponentParameters["PARAMETERS"]["TAB_{$index}_INCLUDEFILE"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Включаемый файл текста вкладки",
            "TYPE"    => "FILE",
            "DEFAULT" => "",
        ];

        $arComponentParameters["PARAMETERS"]["TAB_{$index}_HASH"] = [
            "PARENT"  => "TAB_{$index}",
            "NAME"    => "Хэш для ссылки",
            "TYPE"    => "TEXT",
            "DEFAULT" => "",
        ];
    }
}