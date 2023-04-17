<?php

use \Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options;
use \Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ScalarField;
use Ml\Settings\Form\AdminFormConfig;
use Bitrix\Main\Grid\Panel;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/prolog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

Loc::loadMessages(__FILE__);
$request = Context::getCurrent()->getRequest();

try {

    Loader::includeModule('ml.settings');

    $entityId = $request->get('ENTITY');
    $APPLICATION->SetTitle(Loc::getMessage('ML_LOGGING_LIST_ELEMENT_LIST'));

    $class = "\\Ml\\Settings\\Model\\" . $entityId . "Table";

    $list_id = $entityId . '_list';

    $entities_config = Ml\Settings\Form\AdminFormConfig::getConfig();
    $fieldsConfig = $entities_config[$entityId];

    /** Групповые действия START */
    $ids = $request->get('ID');
    $action = $request->get('action_button_' . $list_id);

    switch ($action) {
        case 'delete':
            if (!is_array($ids)) {
                $ids = array($ids);
            }

            foreach ($ids as $id) {
                $class::delete($id);
            }
            break;
        case 'edit':
            foreach ($request->get('FIELDS') as $k => $fields) {
                $class::update($k, $fields);
            }
            break;
    }
    /** Групповые действия END */


    $grid_options = new GridOptions($list_id);
    $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
    $nav_params = $grid_options->GetNavParams();


    $nav = new PageNavigation('request_list');
    $nav->allowAllRecords(true)
        ->setPageSize($nav_params['nPageSize'])
        ->initFromUri();

    $filterOption = new Bitrix\Main\UI\Filter\Options($list_id);
    $filterData = $filterOption->getFilter();
    $columns = $fieldsConfig['columns'];


    $filter = [];

    foreach ($filterData as $k => $v) {

        if ($filterData['FIND']) {
            $filter['TITLE'] = "%" . $filterData['FIND'] . "%";
        }

        if ($filterData['TITLE']) {
            $filter['TITLE'] = "%" . $filterData['TITLE'] . "%";
        }

        if ($filterData['NAME']) {
            $filter['NAME'] = "%" . $filterData['NAME'] . "%";
        }

        if ($filterData['VALUE']) {
            $filter['VALUE'] = "%" . $filterData['VALUE'] . "%";
        }

        if ($filterData['SITE_ID']) {
            $filter['SITE_ID'] = "%" . $filterData['SITE_ID'] . "%";
        }

        if ($filterData['TITLE']) {
            $filter['TITLE'] = "%" . $filterData['TITLE'] . "%";
        }

        if ($filterData['CODE']) {
            $filter['CODE'] = "%" . $filterData['CODE'] . "%";
        }

        if ($filterData['MODULE_ID']) {
            $filter['MODULE_ID'] = "%" . $filterData['MODULE_ID'] . "%";
        }

        if ($filterData['ACTION']) {
            $filter['ACTION'] = "%" . $filterData['ACTION'] . "%";
        }

        if ($filterData['SITE_ID']) {
            $filter['SITE_ID'] = "%" . $filterData['SITE_ID'] . "%";
        }

        if ($filterData['CREATED_BY']) {
            $filter['=CREATED_BY'] = $filterData['CREATED_BY'];
        }

        if ($filterData['STATUS']) {
            $filter['=STATUS'] = $filterData['STATUS'];
        }

        if ($filterData['ACTIVE']) {
            $filter['=ACTIVE'] = $filterData['ACTIVE'];
        }

        if ($filterData['RESULT']) {
            $filter['=RESULT'] = $filterData['RESULT'];
        }

        if ($filterData['MODULE_ID']) {
            $filter['=MODULE_ID'] = $filterData['MODULE_ID'];
        }

        if ($filterData['USER_ID_from']) {
            $filter['=USER_ID'] = $filterData['USER_ID_from'];
        }

        if ($filterData['USER_ID_to']) {
            $filter['=USER_ID'] = $filterData['USER_ID_to'];
        }

        if ($filterData['USER_IP']) {
            $filter['=USER_IP'] = $filterData['USER_IP'];
        }

        if (isset($filterData['CREATED_AT_from']) && $filterData['CREATED_AT_from']) {
            $filter['>=CREATED_AT'] = $filterData['CREATED_AT_from'];
        }

        if (isset($filterData['CREATED_AT_to']) && $filterData['CREATED_AT_to']) {
            $filter['<=CREATED_AT'] = $filterData['CREATED_AT_to'];
        }
    }


    $res = $class::getList([
        'filter' => $filter,
        'select' => array_merge(array_column($columns, 'id'), ['CNT']),
        'offset' => $nav->getOffset(),
        'limit' => $nav->getLimit(),
        'order' => $sort['sort'],
        'count_total' => true,
        'runtime' => array(
            new \Bitrix\Main\Entity\ExpressionField(
                'CNT',
                'COUNT(ID)'
            )
        ),
    ]);
    ?>

    <div class="pagetitle-inner-container">
        <?
        $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $list_id,
            'GRID_ID' => $list_id,
            'FILTER' => $fieldsConfig['filter'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true
        ]); ?>
        <div class="pagetitle-container pagetitle-align-right-container">
            <a id="FORM_BUTTON_ADD"
               href="/bitrix/admin/ml_settings_edit.php?lang=<?= LANGUAGE_ID . '&ENTITY_ID=' . $entityId ?>"
               class="ui-btn ui-btn-primary ui-btn-icon-add"
            >Добавить элемент</a>
        </div>
    </div>
    <style>
        .pagetitle-container {
            float: right;
            margin-top: 18px;
            margin-bottom: 18px;
        }
    </style>
    <?php

    $arSectionsIds = [];
    foreach ($res->fetchAll() as $row) {
        /** $defaultRow дефолтные значения */
        $defaultRow = $row;

        $editUrl = "/bitrix/admin/ml_settings_edit.php?lang=" . LANGUAGE_ID . '&ENTITY_ID=' . $entityId . '&ID=' . $row['ID'];

        $actions = [
            [
                'text' => 'Просмотр',
                'default' => true,
                'onclick' => 'document.location.href="' . $editUrl . '"',
            ]
        ];

        /** Модифицируем значения столбцов */
        if ($row['RESULT']) {

            if ($row['RESULT'] == 'ERROR') {
                $row['RESULT'] = '<span class="logStatus error">Ошибка</span>';
            } else {
                $row['RESULT'] = '<span class="logStatus">Успешно</span>';
            }
        }

        if (!empty($row['SECTION'])) {
            $arSectionsIds[$row['SECTION']] = $row['SECTION'];
        }

        if ($row['VALUE']) {
            $row['VALUE'] = htmlentities($row['VALUE']);
        }

        if ($row['DATA']) {
            $row['DATA'] = htmlentities($row['DATA']);
        }

        /** END Модифицируем значения столбцов */


        $list[] = [
            'data' => $defaultRow, // тут дефолтные значения для редактирования
            'columns' => $row, // тут измененные значения для представления
            'actions' => $actions // действия при нажатии на кнопку
        ];

    }

    if ($arSectionsIds) {
        $iterator = \Bitrix\Iblock\SectionTable::getList([
            'filter' => ['ID' => $arSectionsIds],
            'select' => ['ID', 'NAME'],
        ]);
        while ($arSection = $iterator->fetch()){
            $arSections[$arSection['ID']] = $arSection;
        }

        foreach ($list as &$arItem){
            if(isset($arSections[$arItem['columns']['SECTION']])){
                $arItem['columns']['SECTION'] = $arSections[$arItem['columns']['SECTION']]['NAME'];
            }
        }
    }

    $nav->setRecordCount($res->getCount());

    $snippet = new \Bitrix\Main\Grid\Panel\Snippet();

    ?>
    <div id="table">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.ui.grid",
            "",
            array(
                "GRID_ID" => $list_id,
                "COLUMNS" => $columns,
                "ROWS" => $list,
                "NAV_OBJECT" => $nav,
                "~NAV_PARAMS" => array('SHOW_ALWAYS' => false),
                'SHOW_ROW_CHECKBOXES' => true,
                'SHOW_GRID_SETTINGS_MENU' => true,
                'SHOW_PAGINATION' => true,
                'SHOW_SELECTED_COUNTER' => true,
                'SHOW_TOTAL_COUNTER' => true,
                'SHOW_PAGESIZE' => true,
                'ACTION_PANEL' => [
                    'GROUPS' => [
                        'TYPE' => [
                            'ITEMS' => [
                                $snippet->getRemoveButton(),
                            ],
                        ]
                    ],
                ],
                "TOTAL_ROWS_COUNT" => $res->getCount(),
                'ALLOW_COLUMNS_SORT' => true,
                'ALLOW_COLUMNS_RESIZE' => true,
                'PAGE_SIZES' => [
                    ['NAME' => '20', 'VALUE' => '20'],
                    ['NAME' => '50', 'VALUE' => '50'],
                    ['NAME' => '100', 'VALUE' => '100']
                ],
                "AJAX_MODE" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "Y"
            )
        ); ?>
    </div>


<? } catch (Exception $exception) {

    $e = new CAdminException([['text' => $exception->getMessage()]]);
    $message = new CAdminMessage(Loc::getMessage('ML_LOGGINGLIST_ERROR'), $e);
    echo $message->Show();

}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';

