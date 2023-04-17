<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!\Bitrix\Main\Loader::IncludeModule("highloadblock"))
    return false;

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
			$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
			$theme = COption::GetOptionString("main", "wizard_".$templateId."_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

$arProps = \Bitrix\Iblock\PropertyTable::getList(array(
    'select' => array('ID', 'SORT'),
    'filter' => array()
));
while ($prop = $arProps->fetch()) {
    $propTable[$prop['ID']] = $prop;
}

foreach ($arResult["ITEMS"] as $key => &$arItem) {
    $arItem['SORT'] = $propTable[$arItem['ID']]['SORT'];
    if($arItem["DISPLAY_TYPE"] == 'G' && $arItem['CODE'] == 'COLOR' && !empty($arItem['VALUES'])){


        $arItem['COLOR_GROUP'] = array();

        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_ID_COLORS)->fetch();
        $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
        $hlDataClass = $hldata['NAME'].'Table';
        $rsData = $hlDataClass::getList(array(
            'filter' => array(
                '=UF_XML_ID' => array_keys($arItem['VALUES']),
                '!UF_GROUP' => false,
            ),
            'order' => ['UF_SORT' => "ASC", 'UF_NAME' => "ASC"]
        ));
        $arColor = [];
        while ($arItemColor = $rsData->Fetch()) {
            if(empty($arColor[$arItemColor['UF_GROUP']]))
                $arColor[$arItemColor['UF_GROUP']] = [];

            $arColor[$arItemColor['UF_GROUP']][$arItemColor['UF_XML_ID']] = $arItem['VALUES'][$arItemColor['UF_XML_ID']];
        };

        if(!empty($arColor)) {
            $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getById(HLBLOCK_ID_COLOR_GROUPS)->fetch();
            $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'].'Table';
            $rsData = $hlDataClass::getList(array(
                'filter' => array(
                    '=ID' => array_keys($arColor),
                )
            ));
            while ($arItemColorGroups = $rsData->Fetch()) {
                $value = $arColor[$arItemColorGroups['ID']];
                $arItem['COLOR_GROUP'][$arItemColorGroups['ID']] = $arItemColorGroups;
                $arItem['COLOR_GROUP'][$arItemColorGroups['ID']]['ACTIVE'] = !empty(array_filter($value, function ($a){
                    return $a['CHECKED'];
                }));
                $arItem['COLOR_GROUP'][$arItemColorGroups['ID']]['VALUES'] = $value;
            }
        }

        usort($arItem['COLOR_GROUP'], function($a, $b) {
            return ((int)$a['UF_SORT'] < (int)$b['UF_SORT']) ? -1 : 1;
        });

    }
}




// Теги (установленные свойства)
foreach($arResult['ITEMS'] as &$arItem){
    unset($min);
    unset($max);

    $arItem['TAGS'] = array();
    if($arItem['PRICE']){
        $min = $max = null;
        if(!empty($arItem['VALUES']['MIN']['HTML_VALUE']) && floatval($arItem['VALUES']['MIN']['HTML_VALUE']) != floatval($arItem['VALUES']['MIN']['VALUE'])){
            $min = $arItem['VALUES']['MIN']['HTML_VALUE'];
        }
        if(!empty($arItem['VALUES']['MAX']['HTML_VALUE']) &&  floatval($arItem['VALUES']['MAX']['HTML_VALUE']) != floatval($arItem['VALUES']['MAX']['VALUE'])){
            $max = $arItem['VALUES']['MAX']['HTML_VALUE'];
        }
        $unit = null;
        $arReplace = array("#MIN#" => $min, "#MAX#" => $max, "#UNIT#" => $unit);
        if(isset($min) && isset($max)){
            $arItem['TAGS'][] = array(
                "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_BOTH', $arReplace)
            );
        }else{
            if(isset($min)){
                $arItem['TAGS'][] = array(
                    "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_FROM', $arReplace)
                );
            }else{
                if(isset($max)){
                    $arItem['TAGS'][] = array(
                        "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_TO', $arReplace)
                    );
                }
            }
        }
    }else{
        switch ($arItem['PROPERTY_TYPE']){
            case 'L':
            case 'S':
            case 'E':
                foreach($arItem['VALUES'] as $valueKey => $arValue){
                    if($arValue['CHECKED']){
                        $arItem['TAGS'][] = array(
                            "TITLE" => $arValue['VALUE'],
                            "VALUE" => $valueKey,
                            "VALUE_NAME" => $arValue['CONTROL_NAME']
                        );
                    }
                }
                break;
            case 'N':

                $unit = null;
                $min = $max = null;
                if(floatval($arItem['VALUES']['MIN']['HTML_VALUE']) != floatval($arItem['VALUES']['MIN']['VALUE'])){
                    $min = $arItem['VALUES']['MIN']['HTML_VALUE'];
                }
                if(floatval($arItem['VALUES']['MAX']['HTML_VALUE']) != floatval($arItem['VALUES']['MAX']['VALUE'])){
                    $max = $arItem['VALUES']['MAX']['HTML_VALUE'];
                }
                $arReplace = array("#MIN#" => $min, "#MAX#" => $max, "#UNIT#" => $unit);
                if(isset($min) && isset($max)){
                    $arItem['TAGS'][] = array(
                        "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_BOTH', $arReplace)
                    );
                }else{
                    if(isset($min)){
                        $arItem['TAGS'][] = array(
                            "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_FROM', $arReplace)
                        );
                    }else{
                        if(isset($max)){
                            $arItem['TAGS'][] = array(
                                "TITLE" => GetMessage('CSF_RESMOD_NUMBER_RANGE_TO', $arReplace)
                            );
                        }
                    }
                }
                break;
        }
    }
    if($arItem['TAGS']){
        $arResult['ITEMS_SET'][] = $arItem;
    }
}
unset($arItem);


$arResult['GROUP_COLOR'] = [];
foreach ($arResult["ITEMS"] as $key => $arItem) {
    if($arItem['CODE'] == 'GROUP_COLOR'){
        foreach ($arItem['VALUES'] as $xmlValue => $value) {
            $arItem['VALUES'] = [$xmlValue => $value];
            $arResult['GROUP_COLOR'][$xmlValue] = $arItem;
        }
        unset($arResult["ITEMS"][$key]);
    }
}

usort($arResult['ITEMS'], function($a,$b){
    return ($a['SORT']-$b['SORT']);
});

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
