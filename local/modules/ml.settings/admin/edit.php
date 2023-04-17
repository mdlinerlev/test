<?php


use Bitrix\Main\Context;

use Bitrix\Main\EventManager;

use Bitrix\Main\Loader;

use Bitrix\Main\Localization\Loc;

use Bitrix\Main\ORM\Data\DataManager;

use Bitrix\Main\ORM\Fields\BooleanField;

use Bitrix\Main\ORM\Fields\DateField;

use Bitrix\Main\ORM\Fields\DatetimeField;

use Bitrix\Main\ORM\Fields\EnumField;

use Bitrix\Main\ORM\Fields\ScalarField;

use Bitrix\Main\ORM\Fields\StringField;

use Bitrix\Main\ORM\Fields\TextField;

use Ml\Settings\Field;

use Ml\Settings\Fields\FileField;

use Ml\Settings\Form\AdminForm;

use Ml\Settings\Form\AdminFormConfig;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/prolog.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';


Loc::loadMessages(__FILE__);


$request = Context::getCurrent()->getRequest();


try {

    Loader::includeModule('fileman');
    Loader::includeModule('ml.settings');

    $entityId = $request->get('ENTITY_ID');


    if (!$entityId) {
        throw new EntityNotDefinedException(Loc::getMessage('ML_FORM_EDIT_ENTITY_ERROR'));
    }


    $entities_config = AdminFormConfig::getConfig();

    $fieldsConfig = $entities_config[$entityId];
    // echo '<pre>',print_r($fieldsConfig),'</pre>';

    $elementId = $request->get('ID');


    /** @var DataManager $class */

    $class = "\\Ml\\Settings\\Model\\" . $request->get('ENTITY_ID') . "Table";


    if (!(new $class instanceof DataManager)) {
        throw new EntityNotDefinedException(Loc::getMessage('ML_FORM_EDIT_ENTITY_WRONG'));
    }


    $entity = $class::getEntity();
    $fields = $entity->getFields();


    if ($elementId > 0) {

        $element = $class::getList([
            'filter' => ['ID' => $elementId]
        ])->fetch();

        //pr($element);

        //Копирование
        if ($request->get('action') == 'copy') {

            $element['TITLE'] = 'Копия ' . $element['TITLE'];
            $element['CODE'] = 'COPY_' . $element['CODE'];
            unset(
                $element['CREATED_AT'],
                $element['UPDATED_AT'],
                $element['CREATED_BY'],
                $element['MODIFIED_BY'],
                $element['ACTIVE_TO'],
                $element['ID']
            );

            $el = $class::add($element);

            if ($el->isSuccess()) {
                LocalRedirect('/bitrix/admin/ml_settings_edit.php?lang=' . LANG . '&ENTITY_ID=' . $entityId . '&FORM_ID=' . $request->get('FORM_ID') . '&ID=' . $el->getId());
            } else {
                $e = new CAdminException([['text' => implode('<br />', $el->getErrorMessages())]]);
                $message = new CAdminMessage(Loc::getMessage('ML_FORM_EDIT_SAVE_ERROR'), $e);
                echo $message->Show();
            }

        }


        if (!$element['ID']) {
            throw new ElementNotFoundException(Loc::getMessage('ML_FORM_EDIT_ELEMENT_NOT_FOUND'));
        }
    }


    $APPLICATION->SetTitle($elementId > 0 ? Loc::getMessage('ML_FORM_EDIT_ELEMENT_EDIT') : Loc::getMessage('ML_FORM_EDIT_ELEMENT_NEW'));

    $formId = 'form_' . mb_strtolower($entity->getCode()) . '_edit';

    $menu = [];

    $menu[] = [
        'TEXT' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_LIST'),
        'LINK' => '/bitrix/admin/ml_settings_list.php?lang=' . LANGUAGE_ID . '&ENTITY=' . $entityId . '&set_default=Y',
        'ICON' => 'btn_list',
        'TITLE' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_LIST'),
    ];

    $menu[] = ['SEPARATOR' => 'Y'];

    if ($entityId != 'FormResult') {
        $menu[] = [
            'TEXT' => "Добавить",
            'LINK' => '/bitrix/admin/ml_settings_edit.php?lang=' . LANGUAGE_ID . '&ENTITY_ID=' . $entityId . '&FORM_ID=' . $request->get('FORM_ID'),
            'ICON' => 'btn_new',
            'TITLE' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_ADD'),
        ];

        $menu[] = [
            'TEXT' => "Копировать",
            'LINK' => '/bitrix/admin/ml_settings_edit.php?lang=' . LANGUAGE_ID . '&ENTITY_ID=' . $entityId . '&ID=' . $elementId . '&action=copy',
            'ICON' => 'btn_copy',
            'TITLE' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_ADD'),
        ];
    }

    $menu[] = ['SEPARATOR' => 'Y'];

    $context = new CAdminContextMenu($menu);
    $context->Show();

    if (
        $request->isPost() &&
        ($request->getPost('save') !== '' || $request->getPost('apply') !== '' || $request->getPost('save_and_add') !== '') &&
        check_bitrix_sessid()
    ) {

        $ID = 0;
        $values = $request->getPostList()->toArray();

        if ($values['ID']) {
            $ID = $values['ID'];
            unset($values['ID']);
        }

        $allowedFields = array_keys($fields);

        foreach ($values as $k => &$value) {
            if (!in_array($k, $allowedFields, false)) {
                unset($values[$k]);
            } else {
                $field = new Field($class, $k);
                $value = $field->modify($value);
            }
        }
        unset($value);

        $errorMessages = [];

        if (empty($errorMessages)) {
            if ($ID > 0) {
                $el = $class::update($ID, $values);
            } else {
                $el = $class::add($values);
            }

            if ($el->isSuccess()) {

                $redirect_url_list = '/bitrix/admin/ml_settings_edit.php?lang=' . LANG . '&ENTITY_ID=' . $entityId . '&FORM_ID=' . $request->get('FORM_ID');
                $redirect_url_edit = '/bitrix/admin/ml_settings_edit.php?lang=' . LANG . '&ENTITY_ID=' . $entityId . '&FORM_ID=' . $request->get('FORM_ID');

                if ($request->getPost('save')) {
                    LocalRedirect($redirect_url_list);
                } elseif ($request->getPost('apply')) {
                    LocalRedirect($redirect_url_edit . '&ID=' . $el->getId());
                } elseif ($request->getPost('save_and_add')) {
                    LocalRedirect($redirect_url_edit . '&ID=0');
                }

            } else {
                $e = new CAdminException([['text' => implode('<br />', $el->getErrorMessages())]]);
                $message = new CAdminMessage(Loc::getMessage('ML_FORM_EDIT_SAVE_ERROR'), $e);
                echo $message->Show();
            }
        } else {
            $e = new CAdminException([['text' => implode('<br />', $errorMessages)]]);
            $message = new CAdminMessage(Loc::getMessage('ML_FORM_EDIT_SAVE_ERROR'), $e);
            echo $message->Show();
        }
    }


    $tabs = [];

    $tabs[] = [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_TAB'),
        'ICON' => 'main_user_edit',
        'TITLE' => Loc::getMessage('ML_FORM_EDIT_ELEMENT_TITLE') . ' ID - ' . $ID
    ];

    $tabControl = new AdminForm($formId, $tabs);

    $tabControl->BeginPrologContent();

    CAdminCalendar::ShowScript();

    $tabControl->EndPrologContent();
    $tabControl->BeginEpilogContent();

    echo bitrix_sessid_post();

    $tabControl->EndEpilogContent();


    $tabControl->Begin([
        'FORM_ACTION' => $APPLICATION->GetCurPage() . '?id=' . $elementId . '&lang=' . LANG
    ]);


    $tabControl->BeginNextFormTab(); ?>

    <?
    $tabControl->AddHiddenField('ENTITY_ID', 'ENTITY_ID', $entityId);

    if ($elementId > 0) {
        $tabControl->AddHiddenField('ID', 'ID', $elementId);
    }

    foreach ($fields as $field) {

        if ($field instanceof ScalarField && !$field->isAutocomplete()) {
            $fieldClass = get_class($field);

            $config = $fieldsConfig['fields'][$field->getName()];

            $value = $element[$field->getName()];
            //pr($element);

            if (isset($customFields[$fieldClass]) && is_callable($customFields[$fieldClass])) {

                call_user_func($customFields[$fieldClass], $tabControl, $field, $value);

            } else {
                switch ($config['widget']) {
                    case 'enum':
                        $tabControl->AddDropDownField($field->getName(), $field->getTitle(), $field->isRequired(),
                            $config['values'], $value);
                        break;
                    case 'boolean':
                        $tabControl->AddCheckBoxField($field->getName(), $field->getTitle(), $field->isRequired(),
                            ['Y', 'N'], $value == "Y");
                        break;
                    case 'date':
                        $tabControl->AddCalendarField($field->getName(), $field->getTitle(), $value,
                            $field->isRequired());
                        break;
                    case 'date_time':
                        $tabControl->AddDateTimeField($field->getName(), $field->getTitle(), $value, $config['widget']['readonly'],
                            $field->isRequired());
                        break;
                    case 'custom_fields':
                        $tabControl->AddJsEditorField($field->getName(), $field->getTitle(), $value, $element['VARIANTS']);
                        break;
                    case 'editor':
                        $tabControl->AddHtmlEditorField($field->getName(), $field->getTitle(), $value,
                            $field->isRequired());
                        break;
                    case 'slug':
                        $tabControl->AddSlugField($field->getName(), $field->getTitle(), $value,
                            $field->isRequired());
                        break;
                    case 'file':
                        $tabControl->AddFileField($field->getName(), $field->getTitle(), $value, [],
                            $field->isRequired());
                        break;
                    case 'user':
                        $tabControl->AddUserField($field->getName(), $field->getTitle(), $value);
                        break;
                    case 'time_interval':
                        $tabControl->AddTimeIntervalField($field->getName(), $field->getTitle(), $value);
                        break;
                    case 'custom_values':
                        $tabControl->AddCustomValuesField($field->getName(), $field->getTitle(), $value, $element['FORM_ID']);
                        break;
                    case 'properties':
                        $tabControl->addPropertiesField($field->getName(), $field->getTitle(), $value);
                        break;
                    case 'cEvent':
                        $arEvents = [];
                        $arFilter = ['TYPE_ID' => 'ML_FORM'];

                        $rsEvents = CEventMessage::GetList($by = 'site_id', $order = 'desc', $arFilter);
                        $arEvents[0] = '[0] Не отправлять';
                        while ($arr = $rsEvents->Fetch()) {
                            $arEvents[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['SUBJECT'];
                        }

                        $tabControl->AddDropDownField($field->getName(), $field->getTitle(), $field->isRequired(),
                            $arEvents,
                            $value ?? $field->getDefaultValue(),
                            $config['params']
                        );
                        break;
                    case 'hidden':
                        $tabControl->AddHiddenField($field->getName(), $field->getTitle(), $value ?? $field->getDefaultValue());
                        break;
                    case 'form_link':
                        $tabControl->referenceFormField($field->getName(), $field->getTitle(), $field->isRequired(),
                            $value ?? $field->getDefaultValue());
                        break;
                    case 'none':
                        break;
                    case 'number':
                    case 'string':
                    default:
                        if ($field->getName() == 'MAIL_TO') {
                            global $USER;
                            $value = $value ?? $USER->GetEmail();
                        }

                        $tabControl->customAddEditField($field->getName(), $field->getTitle(), $field->isRequired(),
                            ['size' => $config['size'], 'readonly' => $config['readonly'], 'id' => $field->getName()],
                            $value ?? $field->getDefaultValue()
                        );
                        break;
                }
            }
        }
    }


    $tabControl->Buttons([
        'disabled' => false,
        'btnSaveAndAdd' => true,
        'btnSave' => true,
        'btnApply' => true,
        'btnCancel' => true,
        'back_url' => '/bitrix/admin/ml_form_list.php?lang=' . LANGUAGE_ID . '&ENTITY_ID=' . $entityId . '&FORM_ID=' . $request->get('FORM_ID'),
    ]);


    $tabControl->Show(); ?>

    <script>
        $(document).on('click', 'a[data-event="import"]', function () {
            var id = $(this).attr('data-id');
            var obpopup, params;

            params = {
                bxpublic: 'Y',
                sessid: BX.bitrix_sessid(),
                itemId: id
            };

            var obBtn = {
                title: 'Закрыть окно',
                id: 'close',
                name: 'close',
                action: function () {
                    this.parentWindow.Close();
                }
            };

            obpopup = new BX.CDialog({
                'content_url': '/local/modules/ml.importozon/ajax/importPopup.php',
                'content_post': params,
                'draggable': true,
                'resizable': true,
                'buttons': [obBtn]
            });
            obpopup.Show();

            return false;
        });
    </script>
    <?
} catch (Exception $exception) {

    $e = new CAdminException([['text' => $exception->getMessage()]]);

    $message = new CAdminMessage(Loc::getMessage('ML_FORM_EDIT_ERROR'), $e);

    echo $message->Show();

}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';

