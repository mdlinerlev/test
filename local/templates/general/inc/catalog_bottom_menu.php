 <?
                                $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"bottom_menu", 
	array(
		"ROOT_MENU_TYPE" => "bottom_catalog",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_TYPE" => "Y",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"COMPONENT_TEMPLATE" => "bottom_menu",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);
                                ?>

<?/*
                                $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog_bottom_menu", Array(
                                    "COMPONENT_TEMPLATE" => "tree",
                                    "IBLOCK_TYPE" => "catalog", // Тип инфоблока
                                    "IBLOCK_ID" => "2", // Инфоблок
                                    "SECTION_ID" => $_REQUEST["SECTION_ID"], // ID раздела
                                    "SECTION_CODE" => "", // Код раздела
                                    "COUNT_ELEMENTS" => "N", // Показывать количество элементов в разделе
                                    "TOP_DEPTH" => "1", // Максимальная отображаемая глубина разделов
                                    "SECTION_FIELDS" => array(// Поля разделов
                                        0 => "",
                                        1 => "",
                                    ),
                                    "SECTION_USER_FIELDS" => array(// Свойства разделов
                                        0 => "",
                                        1 => "",
                                    ),
                                    "SECTION_URL" => "", // URL, ведущий на страницу с содержимым раздела
                                    "CACHE_TYPE" => "A", // Тип кеширования
                                    "CACHE_TIME" => "36000000", // Время кеширования (сек.)
                                    "CACHE_GROUPS" => "Y", // Учитывать права доступа
                                    "ADD_SECTIONS_CHAIN" => "Y", // Включать раздел в цепочку навигации
                                        ), false
                                );
                                */?>