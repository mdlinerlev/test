<?php

namespace Ml\Settings\Form;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Data\DataManager;
use CLightHTMLEditor;
use Bitrix\Main\UserTable;

class AdminForm extends \CAdminForm
{
    var $optionTypes = [
        [
            'val' => 'string',
            'name' => 'Строка'
        ],
        [
            'val' => 'text',
            'name' => 'Текст'
        ],
        [
            'val' => 'number',
            'name' => 'Число (номер)'
        ],
        [
            'val' => 'email',
            'name' => 'Email'
        ],
        [
            'val' => 'phone',
            'name' => 'Телефон'
        ],
        [
            'val' => 'list',
            'name' => 'Список (последовательность значений)'
        ],
        [
            'val' => 'checkbox',
            'name' => 'Да/Нет (логические данные)'
        ],
        [
            'val' => 'file',
            'name' => 'Файл'
        ],
        [
            'val' => 'currency',
            'name' => 'Валюта'
        ],
        [
            'val' => 'location',
            'name' => 'Местоположение'
        ],
        [
            'val' => 'hidden',
            'name' => 'Скрытое'
        ],
    ];

    function AddDateTimeField($id, $label, $value, $readonly = false, $required = false)
    {

        if ($value) {
            if ($readonly) {
                $html = $value;
            } else {
                $html = CalendarDate($id, $value, $this->GetFormName(), 20);
            }

            $value = htmlspecialcharsbx($value);

            $this->tabs[$this->tabIndex]['FIELDS'][$id] = [
                'id' => $id,
                'required' => $required,
                'content' => $label,
                'html' => '<td>' . ($required ? '<span class="adm-required-field">' . $this->GetCustomLabelHTML($id,
                            $label) . '</span>' : $this->GetCustomLabelHTML($id, $label)) . '</td><td>' . $html . '</td>',
                'hidden' => '<input type="hidden" name="' . $id . '" value="' . $value . '">',
            ];
        }
    }

    function AddHiddenField($id, $label, $value)
    {
        $this->BeginCustomField($id, $label); ?>

        <input type="hidden" name="<?= $id ?>" value="<?= $value ?>">

        <?php
        $this->EndCustomField($id);
    }

    function AddHtmlEditorField($id, $label, $value, $required = false)
    {
        $this->BeginCustomField($id, $label, $required);
        ?>

        <tr class="heading">
            <td colspan="2"><?= $this->GetCustomLabelHTML($id, $label) ?></td>
        </tr>

        <tr>

            <td colspan="2" align="center">
                <?php
                $editor = new \CFileMan();
                $editor->AddHTMLEditorFrame(
                    $id,
                    $value,
                    $id . '_type',
                    'html',
                    array(
                        'width' => '100%',
                        'height' => '350',
                    )
                );

                ?>
            </td>
        </tr>
        <?
        $this->EndCustomField($id);
    }

    function AddHtmlEditorMessageField($id, $label, $value, $answers, $email_to)
    {
        $this->BeginCustomField($id, $label);
        ?>

        <?
        if (!$value) { ?>

            <tr class="heading">
                <td colspan="2">Типовой ответ</td>
            </tr>

            <tr>
                <td colspan="2">
                    <div style="width: 100%; display: flex; flex-direction: row; align-items: baseline; justify-content: center; margin-bottom: 20px;">

                        <select name="readyAnswers" id="readyAnswers"
                                style="margin-right: 10px; height: 29px; margin-bottom: 15px;">
                            <option value="">Выберите из готовых ответов</option>
                            <? foreach ($answers as $answer) { ?>
                                <option value="<?= $answer['ID'] ?>"><?= $answer['TITLE'] ?></option>
                            <?
                            } ?>
                        </select>

                        <input class="adm-btn-big answerBtn" id="sendReadyAnswer" type="button" value="Отправить">

                    </div>
                    <div class="answersMessage readyAnswersMessage"></div>
                </td>
            </tr>
        <?
        } ?>

        <tr class="heading">
            <td colspan="2"><?= $value ? 'Ответ был дан' : 'Свой ответ'; ?></td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <?php
                $editor = new \CFileMan();
                $editor->AddHTMLEditorFrame(
                    $id,
                    $value,
                    $id . '_type',
                    'html',
                    array(
                        'width' => '100%',
                        'height' => '350',
                    )
                ); ?>

                <div style="width: 100%; text-align: center; margin: 10px 0;">
                    <input class="adm-btn-big answerBtn" id="sendAnswer" type="button"
                           value="<?= $value ? 'Отправить повторно' : 'Отправить'; ?>">
                </div>
                <div class="answersMessage sendAnswerMessage"></div>
            </td>
        </tr>

        <script>
            window.addEventListener('DOMContentLoaded', () => {

                let sendReadyAnswers = document.getElementById('sendReadyAnswer');
                let readyAnswers = document.getElementById('readyAnswers');

                sendReadyAnswers.addEventListener('click', (e) => {
                    if (readyAnswers.value) {
                        const request = BX.ajax.runAction('ml:form.api.answers.sendReadyAnswer', {
                            data: {
                                id: document.querySelector('input[name="ID"]').value,
                                answerId: readyAnswers.value,
                                email: document.querySelector('input[name="EMAIL"]').value
                            }
                        })
                            .then(function (response) {
                                if (response.status === 'success') {
                                    //console.log(response.data);
                                    prepareResult(response.data);
                                }
                            });
                    }
                });

                let sendAnswerBnt = document.getElementById('sendAnswer');

                sendAnswerBnt.addEventListener('click', (e) => {

                    let message = document.querySelector('textarea[name="ANSWER"]').value;

                    if (message) {
                        const request = BX.ajax.runAction('ml:form.api.answers.sendAnswer', {
                            data: {
                                message: message,
                                id: document.querySelector('input[name="ID"]').value,
                                email: document.querySelector('input[name="EMAIL"]').value
                            }
                        })
                            .then(function (response) {
                                if (response.status === 'success') {
                                    //console.log(response.data);
                                    prepareResult(response.data);
                                }
                            });
                    }
                });

                function prepareResult(data) {
                    if (data.type === 'error') {
                        document.querySelector('.readyAnswersMessage')
                            .classList.add('error');
                    } else {
                        document.querySelectorAll('.answerBtn').forEach(btn => {
                            btn.remove();
                        })
                    }

                    document.querySelector('.readyAnswersMessage')
                        .textContent = data.message;
                }

            });
        </script>

        <?
        $this->EndCustomField($id);
    }

    function AddSlugField($id, $label, $value, $required = false)
    {
        $this->BeginCustomField($id, $label, $required);

        $noTranslit = false;

        if ($value) {
            if (strstr($value, 'COPY_')) {
                $noTranslit = false;
            } else {
                $noTranslit = true;
            }

        }
        ?>

        <tr>
            <td><?= $this->GetCustomLabelHTML($id, $label) ?></td>

            <td>

                <input id="<?= $id ?>" type="text" name="<?= $id ?>" <?
                if ($noTranslit) echo 'readonly="true"' ?> size="80" value="<?= $value ?>"/>

                <?
                if (!$noTranslit) { ?>

                    <img id="code_link" alt="Создать код из заголовка" class="linked"
                         src="/bitrix/themes/.default/icons/iblock/<?
                         if ($bLinked) echo 'link.gif'; else echo 'unlink.gif'; ?>" onclick="set_linked()"/>

                <?

                if ($id == "CODE")
                {
                \CUtil::InitJSCore(array('translit'));
                ?>

                    <script type="text/javascript">
                        var linked = false;

                        function set_linked() {
                            linked = !linked;

                            var name_link = document.getElementById('name_link');
                            if (name_link) {
                                if (linked)
                                    name_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                                else
                                    name_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
                            }
                            var code_link = document.getElementById('code_link');
                            if (code_link) {
                                if (linked)
                                    code_link.src = '/bitrix/themes/.default/icons/iblock/link.gif';
                                else
                                    code_link.src = '/bitrix/themes/.default/icons/iblock/unlink.gif';
                            }
                            var linked_state = document.getElementById('linked_state');
                            if (linked_state) {
                                if (linked)
                                    linked_state.value = 'Y';
                                else
                                    linked_state.value = 'N';
                            }
                        }

                        var oldValue = '';

                        function transliterate() {
                            if (linked) {
                                var from = document.getElementById('TITLE');
                                var to = document.getElementById('CODE');
                                if (from && to && oldValue != from.value) {
                                    BX.translit(from.value, {
                                        'max_len': 80,
                                        'change_case': 'U',
                                        'replace_space': '_',
                                        'replace_other': '_',
                                        'delete_repeat_replace': true,
                                        //'use_google' : 'false',
                                        'callback': function (result) {
                                            to.value = result;
                                            setTimeout('transliterate()', 250);
                                        }
                                    });
                                    oldValue = from.value;
                                } else {
                                    setTimeout('transliterate()', 250);
                                }
                            } else {
                                setTimeout('transliterate()', 250);
                            }
                        }

                        transliterate();
                    </script>
                    <?

                }
                    ?>

                <?
                } ?>


            </td>
        </tr>
        <?
        $this->EndCustomField($id);
    }

    function AddUserField($id, $label, $value, $readonly = false, $required = false)
    {

        if ($value) {
            $userId = $value;
            $html = '';

            if (!empty($userId) && $userId != 0) {

                $rsUser = UserTable::getById($userId);

                $user = $rsUser->fetch();

                $html = '[<a href="user_edit.php?lang=ru&ID=' . $user['ID'] . '">' . $user['ID'] . '</a>]';

                if ($user['EMAIL']) {
                    $html .= ' (' . $user['EMAIL'] . ')';
                }

                $html .= ' ' . $user['NAME']
                    . '&nbsp;' . $user['LAST_NAME'];
            }


            $value = htmlspecialcharsbx(htmlspecialcharsback($value));

            $this->tabs[$this->tabIndex]['FIELDS'][$id] = [
                'id' => $id,
                'required' => $required,
                'content' => $label,
                'html' => '<td>' . ($required ? '<span class="adm-required-field">' . $this->GetCustomLabelHTML($id,
                            $label) . '</span>' : $this->GetCustomLabelHTML($id, $label)) . '</td><td>' . $html . '</td>',
                'hidden' => '<input type="hidden" name="' . $id . '" value="' . $value . '">',
            ];
        }


    }

    function AddTimeIntervalField($id, $label, $value, $readonly = false, $required = false)
    {

        $html = '<input type="time" name="' . $id . '[from]" value="' . $value['from'] . '"><input type="time" name="' . $id . '[to]" value="' . $value['to'] . '">';

        $this->tabs[$this->tabIndex]['FIELDS'][$id] = [
            'id' => $id,
            'html' => '<td>' . ($required ? '<span class="adm-required-field">' . $this->GetCustomLabelHTML($id,
                        $label) . '</span>' : $this->GetCustomLabelHTML($id, $label)) . '</td><td>' . $html . '</td>',
        ];
    }

    function customAddEditField($id, $content, $required, $arParams = array(), $value = false)
    {
        if ($value === false)
            $value = htmlspecialcharsbx($this->arFieldValues[$id]);
        else
            $value = htmlspecialcharsbx(htmlspecialcharsback($value));


        if ($arParams['readonly']) {
            $html = $value;
        } else {
            $html = '<input type="text" name="' . $id . '" value="' . $value . '"';
            if (intval($arParams["size"]) > 0)
                $html .= ' size="' . intval($arParams["size"]) . '"';
            if (intval($arParams["maxlength"]) > 0)
                $html .= ' maxlength="' . intval($arParams["maxlength"]) . '"';
            if ($arParams["id"])
                $html .= ' id="' . htmlspecialcharsbx($arParams["id"]) . '"';
            $html .= '>';
        }


        $this->tabs[$this->tabIndex]["FIELDS"][$id] = array(
            "id" => $id,
            "required" => $required,
            "content" => $content,
            "html" => '<td width="40%">' . ($required ? '<span class="adm-required-field">' . $this->GetCustomLabelHTML($id, $content) . '</span>' : $this->GetCustomLabelHTML($id, $content)) . '</td><td>' . $html . '</td>',
            "hidden" => '<input type="hidden" name="' . $id . '" value="' . $value . '">',
        );
    }

    function referenceFormField($id, $content, $required, $value = false)
    {

        if ($value === false)
            $value = htmlspecialcharsbx('-');
        else
            $value = htmlspecialcharsbx($value);


        $editUrl = "/bitrix/admin/ml_form_edit.php?lang=" . LANGUAGE_ID . '&ENTITY_ID=Form&ID=' . $value;

        $html = "<a href='" . $editUrl . "'>" . (Model\FormTable::getByPrimary($value)->fetch())['TITLE'] . "</a>";
        $html .= '<input type="hidden" name="' . $id . '" value="' . $value . '">';


        $this->tabs[$this->tabIndex]["FIELDS"][$id] = array(
            "id" => $id,
            "required" => $required,
            "content" => $content,
            "html" => '<td width="40%">' . ($required ? '<span class="adm-required-field">' . $this->GetCustomLabelHTML($id, $content) . '</span>' : $this->GetCustomLabelHTML($id, $content)) . '</td><td>' . $html . '</td>',
            "hidden" => '<input type="hidden" name="' . $id . '" value="' . $value . '">',
        );
    }

    function AddCustomValuesField($id, $label, $value, $form_id)
    {
        $this->BeginCustomField($id, $label);

        /** значение этого поля редактировать нельзя
         * только вывод
         * поэтому нужно позаботиться чтобы ключи input-ов не совпали с id свойств
         */
        ?>
        <tr class="heading">
            <td colspan="2">
                <?= $this->GetCustomLabelHTML($id, $label) ?>
            </td>

        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%">
                    <tbody>
                    <? foreach ($value as $k => $v) { ?>
                        <tr>
                            <td class="adm-detail-content-cell-l" width="40%">
                                <?
                                if ($v['TYPE'] == 'group') { ?>
                                    <b><?= $v['NAME']; ?></b>
                                <?
                                } else { ?>
                                    <?= $v['NAME']; ?>:
                                <?
                                } ?>

                            </td>
                            <td class="adm-detail-content-cell-r">
                                <?
                                if ($v['TYPE'] == 'text') { ?>

                                    <?php
                                    $editor = new \CFileMan();
                                    $editor->AddHTMLEditorFrame(
                                        $id . '_nosave',
                                        $v['VALUE'],
                                        $id . '_type',
                                        'text',
                                        array(
                                            'width' => '100%',
                                            'height' => '350',
                                        )
                                    );

                                    ?>
                                <?
                                } elseif ($v['TYPE'] == 'file') { ?>

                                    <?= \Bitrix\Main\UI\FileInput::createInstance([
                                        "name" => "picture",
                                        "description" => false,
                                        "upload" => false,
                                        "allowUpload" => "I",
                                        "medialib" => false,
                                        "fileDialog" => false,
                                        "cloud" => false,
                                        "delete" => false,
                                        "maxCount" => 99
                                    ])->show($v['VALUE']); ?>

                                <?
                                } else { ?>
                                    <?= self::prepareToOutput($v['VALUE']); ?>
                                <?
                                } ?>
                            </td>
                        </tr>
                    <?
                    } ?>
                    </tbody>

                </table>
            </td>
        </tr>
        <?php/*
        <input type="hidden" name="<?=$id;?>" value="<?=$value;?>">
        */ ?>
        <?

        $this->EndCustomField($id);
    }

    function AddJsEditorField($id, $label, $value, $variants, $required = false)
    {
        $this->BeginCustomField($id, $label, $required);
        $OPTIONS = $value;
        $VARIANTS = $variants;
        ?>
        <tr class="heading">
            <td colspan="2">
                <?= $this->GetCustomLabelHTML($id, $label) ?>
                <br>
                <small>
                    Если вы хотите, чтобы в форму подставились поля из профиля пользователя без возможности изменения,
                    укажите им соответствующие коды: <br>
                    Фамилия = LAST_NAME <br>
                    Имя = NAME <br>
                    Отчество = SECOND_NAME <br>
                    Email = EMAIL (Обязательное поле для форм, на которые требуется ответ письмом)
                </small>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="optionsTable">
                    <div class="optionsTableHeader">
                        <div class="header_item option_item_cell__sort"></div>
                        <div class="header_item option_item_cell__input">Название</div>
                        <div class="header_item option_item_cell__input">Код</div>
                        <div class="header_item option_item_cell__input">Описание</div>
                        <div class="header_item option_item_cell__select">Тип</div>
                        <div class="header_item option_item_cell__input">Доп. атрибуты</div>
                        <div class="header_item option_item_cell__chechbox">Обязательное</div>
                        <div class="header_item option_item_cell__chechbox">Удалить</div>
                    </div>

                    <div class="optionsTableBody">
                        <?
                        self::renderJsEditorFieldHtml($id, $OPTIONS, $VARIANTS); ?>
                    </div>
                    <div style="width: 100%; text-align: center; margin: 10px 0;">
                        <input class="adm-btn-big" id="addRow" type="button" value="Добавить поле"
                               title="Добавить еще одно поле">
                    </div>

                    <div style="width: 100%; text-align: center; ">
                        <div style="display: flex; justify-content: center; margin-bottom: 15px;">
                            <input style="margin-right: 10px; height: 29px" type="text" placeholder="Название группы"
                                   id="nameGroupRow" size="50px">
                            <input style="display: inline-block; margin: 0;" class="adm-btn-big" id="addGroupRow"
                                   type="button" value="Добавить группу" title="Добавить группу">
                        </div>

                    </div>

                </div>
            </td>

            <script id="optionGroupRow" type="text/x-jquery-tmpl">
                <div class="option_item">
                    <div class="option_item_row" style="background-color: #d2d2d2;" data-group="${indexGroupRow}">
                        <div class="option_item_cell option_item_cell__sort">
                            <span class="sort_button"></span>
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="text" value="${nameGroup}" name="<?= $id ?>[GROUP_${indexGroupRow}][NAME]">
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="hidden" value="GROUP_${indexGroupRow}" name="<?= $id ?>[GROUP_${indexGroupRow}][CODE]">
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                        </div>
                        <div class="option_item_cell option_item_cell__select">
                            <input type="hidden" value="group" name="<?= $id ?>[GROUP_${indexGroupRow}][TYPE]">
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                        </div>
                        <div class="option_item_cell option_item_cell__chechbox">
                        </div>
                        <div class="option_item_cell option_item_cell__chechbox">
                        </div>
                    </div>
                </div>

            </script>

            <script id="optionRow" type="text/x-jquery-tmpl">
                <div class="option_item">
                    <div class="option_item_row">
                        <div class="option_item_cell option_item_cell__sort">
                            <span class="sort_button"></span>
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="text" value="" name="<?= $id ?>[${indexRow}][NAME]">
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="text" value="" name="<?= $id ?>[${indexRow}][CODE]">
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="text" value="" name="<?= $id ?>[${indexRow}][DESCRIPTION]">
                        </div>
                        <div class="option_item_cell option_item_cell__select">
                            <select name="<?= $id ?>[${indexRow}][TYPE]" id="<?= $id ?>[${indexRow}][TYPE]" style="width:150px">
                                <? foreach ($this->optionTypes as $optionType) { ?>
                                    <option value="<?= $optionType['val'] ?>"><?= $optionType['name'] ?></option>
                                <?
                } ?>
                           </select>
                        </div>
                        <div class="option_item_cell option_item_cell__input">
                            <input type="text" value="" name="<?= $id ?>[${indexRow}][ATTR]">
                        </div>
                        <div class="option_item_cell option_item_cell__chechbox">
                            <input type="hidden" name="<?= $id ?>[${indexRow}][REQUIRED]" id="<?= $id ?>[${indexRow}][REQUIRED_N]" value="N">
                            <input type="checkbox" name="<?= $id ?>[${indexRow}][REQUIRED]" id="<?= $id ?>[${indexRow}][REQUIRED]" value="Y" title="Акт." class="adm-designed-checkbox">
                            <label class="adm-designed-checkbox-label" for="<?= $id ?>[${indexRow}][REQUIRED]" title="Акт."></label>
                        </div>
                        <div class="option_item_cell option_item_cell__chechbox">
                        </div>
                    </div>
                </div>

            </script>

            <script id="listAnswerVariant" type="text/x-jquery-tmpl">
                <div class='listAnswerVariantWrap'>

                    <span class="adm-required-field">Варианты для списка:</span>

                    <span class="listAnswerVariantNode">
                        <div class="listAnswerVariants">
                            <input type="text" value="" name="VARIANTS[${id}][${index}]">
                        </div>

                        <div style="width: 100%; text-align: center; margin: 10px 0;">
                            <input class="addListAnswerVariant" type="button" value="+" title="Добавить еще вариант">
                        </div>
                    </span>

                </div>

            </script>

            <script id="listAnswerVariantInput" type="text/x-jquery-tmpl">
                <input type="text" value="" name="VARIANTS[${id}][${index}]">

            </script>

            <script>
                $(document).ready(function () {

                    var indexRow = 0;

                    var indexGroupRow = getNumGroup($('.option_item_row[data-group]'));
                    console.log('indexGroupRow: ', indexGroupRow);
                    var nameGroup = '';

                    $('body').on('click', '#addGroupRow', function (e) {
                        e.preventDefault();
                        indexGroupRow++;
                        nameGroup = $('#nameGroupRow').val();
                        var dataItems = {
                            'indexRow': indexRow,
                            'indexGroupRow': indexGroupRow,
                            'nameGroup': nameGroup,
                        };

                        $('#optionGroupRow').tmpl(dataItems).prependTo('.optionsTableBody');

                        indexRow++;
                        nameGroup = '';
                    });

                    $('body').on('click', '#addRow', function (e) {
                        e.preventDefault();

                        var dataItems = {
                            'indexRow': indexRow
                        };

                        $('#optionRow').tmpl(dataItems).appendTo('.optionsTableBody');

                        indexRow++;
                    });


                    $('body').on('change', '.option_item_cell__select select', function (e) {


                        var parent = $(this).closest('.option_item');
                        var indexSelect = $(parent).find('.listAnswerVariantWrap').data('count');
                        var dataItems = {
                            'id': $(parent).data('id'),
                            'index': indexSelect
                        };

                        if ($(parent).data('id')) {
                            if (this.value === 'list' || this.value === 'currency') {

                                if ($(parent).children('.listAnswerVariantWrap').length > 0) {
                                    $(parent).find('.listAnswerVariantWrap').show();
                                } else {
                                    /*
                                    $('#listAnswerVariant').tmpl(dataItems).appendTo(parent);
                                    indexSelect++;
                                    */
                                }

                            } else {
                                $(parent).find('.listAnswerVariantWrap').hide();
                            }
                        }

                        if (this.value === 'phone') {
                            $(this).closest('div').next()
                                .find('input')
                                .val('data-mask="+375 (99) 999-99-99" data-placeholder=""');
                        }
                    });

                    window.addEventListener('DOMContentLoaded', () => {
                        var addListAnswerVariantBtn = document.querySelectorAll('.addListAnswerVariant');

                        addListAnswerVariantBtn.forEach(function (btn) {
                            btn.addEventListener('click', function (e) {

                                var parentNode = e.target.closest('.listAnswerVariantNode')
                                    .querySelector('.listAnswerVariants');

                                var dataItems = {
                                    'id': e.target.closest('.option_item').dataset.id,
                                    'index': parentNode.dataset.count
                                };

                                $('#listAnswerVariantInput').tmpl(dataItems).appendTo(parentNode);

                                parentNode.dataset.count++;
                            })
                        })
                    });
                });

                function getNumGroup(groupList) {
                    var tmpValMax = 0;
                    groupList.each(function () {
                        var curValGroup = $(this).data('group');
                        console.log(tmpValMax);
                        tmpValMax = (curValGroup > tmpValMax) ? curValGroup : tmpValMax;
                    });

                    return tmpValMax;
                }
            </script>

        </tr>
        <?
        $this->EndCustomField($id);
    }

    public function renderJsEditorFieldHtml($FIELD, $options, $VARIANTS)
    {
        $HTML = '';

        foreach ($options as $arOption) {
            $HTML .= $this->renderJsEditorOptionItem(
                $FIELD,
                $arOption,
                $VARIANTS
            );
        }

        echo $HTML;
    }

    public function renderJsEditorOptionItem($FIELD, $VALUES, $VARIANTS = [])
    {
        ob_start();
        ?>
        <?
        if ($VALUES['TYPE'] == 'group') {
            ?>
            <?
            $numGroupArr = explode('_', $VALUES['CODE']);
            ?>
            <div class="option_item" data-id="<?= $VALUES['CODE']; ?>">
                <div class="option_item_row" style="background-color: #d2d2d2;" data-group="<?= $numGroupArr[1] ?>">
                    <div class="option_item_cell option_item_cell__sort">
                        <span class="sort_button"></span>
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="text" value="<?= self::prepareToOutput($VALUES['NAME']); ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][NAME]">
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="hidden" value="<?= self::prepareToOutput($VALUES['CODE']); ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][CODE]">
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                    </div>
                    <div class="option_item_cell option_item_cell__select">
                        <input type="hidden" value="group" name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][TYPE]">
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                    </div>
                    <div class="option_item_cell option_item_cell__chechbox">
                    </div>
                    <div class="option_item_cell option_item_cell__chechbox">
                        <input type="checkbox" name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]"
                               id="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]" value="Y" class="adm-designed-checkbox">
                        <label class="adm-designed-checkbox-label" for="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]"
                               title=""></label>
                    </div>
                </div>

            </div>
        <? } else { ?>
            <div class="option_item" data-id="<?= $VALUES['CODE']; ?>">
                <div class="option_item_row">
                    <div class="option_item_cell option_item_cell__sort">
                        <span class="sort_button"></span>
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="text" value="<?= self::prepareToOutput($VALUES['NAME']); ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][NAME]">
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="text" value="<?= self::prepareToOutput($VALUES['CODE']); ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][CODE]">
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="text" value="<?= self::prepareToOutput($VALUES['DESCRIPTION']) ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DESCRIPTION]">
                    </div>
                    <div class="option_item_cell option_item_cell__select">
                        <select name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][TYPE]"
                                id="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][TYPE]" style="width:150px">
                            <? foreach ($this->optionTypes as $optionType) { ?>
                                <option value="<?= $optionType['val'] ?>" <?
                                if ($VALUES['TYPE'] == $optionType['val']) echo 'selected' ?>><?= $optionType['name'] ?></option>
                            <?
                            } ?>
                        </select>
                    </div>
                    <div class="option_item_cell option_item_cell__input">
                        <input type="text" value="<?= self::prepareToOutput($VALUES['ATTR']); ?>"
                               name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][ATTR]">
                    </div>
                    <div class="option_item_cell option_item_cell__chechbox">
                        <input type="hidden" name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][REQUIRED]"
                               id="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][REQUIRED_N]" value="N">
                        <input type="checkbox" name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][REQUIRED]"
                               id="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][REQUIRED]" value="Y" <?
                               if ($VALUES['REQUIRED'] == 'Y'){ ?>checked=""<?
                        } ?> title="Акт." class="adm-designed-checkbox">
                        <label class="adm-designed-checkbox-label" for="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][REQUIRED]"
                               title="Акт."></label>
                    </div>
                    <div class="option_item_cell option_item_cell__chechbox">
                        <input type="checkbox" name="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]"
                               id="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]" value="Y" class="adm-designed-checkbox">
                        <label class="adm-designed-checkbox-label" for="<?= $FIELD ?>[<?= $VALUES['CODE'] ?>][DEL]"
                               title=""></label>
                    </div>
                </div>

                <?
                if ($VALUES['TYPE'] == 'list' || $VALUES['TYPE'] == 'currency') {
                    $count = 1;
                    if (!empty($VARIANTS[$VALUES['CODE']])) {
                        $count = count($VARIANTS[$VALUES['CODE']]);
                    }

                    $name = 'Варианты для списка';

                    if ($VALUES['TYPE'] == 'currency') {
                        $name = 'Коды доступных валют (BYN, USD, EUR, RUB)';
                    }
                    ?>

                    <div class='listAnswerVariantWrap'>

                        <span class="adm-required-field"><?= $name ?>:</span>

                        <span class="listAnswerVariantNode">
                            <div class="listAnswerVariants" data-count="<?= $count ?>">
                                
                                <?
                                foreach ($VARIANTS[$VALUES['CODE']] as $k => $v) { ?>
                                    <input type="text" value="<?= $v ?>"
                                           name="VARIANTS[<?= $VALUES['CODE'] ?>][<?= $k ?>]">
                                <?
                                } ?>

                            </div>

                            <div style="width: 100%; text-align: center; margin: 10px 0;">
                                <input class="addListAnswerVariant" type="button" value="+"
                                       title="Добавить еще вариант">
                            </div>
                        </span>

                    </div>
                <?
                } ?>

            </div>
        <?
        } ?>

        <?php
        $s = ob_get_contents();
        ob_end_clean();

        return $s;
    }

    public static function prepareToOutput($string, $hideTags = false)
    {
        if ($hideTags) {
            return preg_replace('/<.+>/mU', '', $string);
        } else {
            return htmlspecialchars($string, ENT_QUOTES, SITE_CHARSET);
        }
    }


    public function addPropertiesField($name, $title, $value)
    {
        $this->BeginCustomField($name, $title);
        $request = Application::getInstance()->getContext()->getRequest();
        \CJSCore::Init(["jquery"]);

        $arResult = [];
        if (!empty($value)) {
            $arJson = json_decode($value, JSON_OBJECT_AS_ARRAY);
            foreach ($arJson as $arProp) {
                $arResult[$arProp['id']] = $arProp;
            }
        }

        $arSectionProps = $arProperties = [];
        if ($request['ID']) {
            $iterator = \Ml\ImportOzon\Model\SectionsTable::getList([
                'filter' => ['=ID' => $request['ID'], '!SECTION_OZON_ID' => false]
            ]);
            if ($arItem = $iterator->fetch()) {
                $arSectionProps = Api::GetInstanse()->GetSectionProperty($arItem['SECTION_OZON_ID']);
            }

            $iterator = PropertyTable::getList([
                'select' => ['ID', 'CODE', 'NAME', 'PROPERTY_TYPE'],
                'filter' => ['IBLOCK_ID' => CATALOG],
            ]);
            while ($arProp = $iterator->fetch()) {
                $arProperties[] = [
                    'ID' => $arProp['ID'],
                    'NAME' => $arProp['NAME'] . ' (' . $arProp['CODE'] . ')',
                    'PROPERTY_TYPE' => $arProp['PROPERTY_TYPE']
                ];
            }
        }

        //pr($arSectionProps);
        ?>
        <tr id="tr_<?= $name ?>">
            <td width="40%" class="adm-detail-content-cell-l"><?= $title ?></td>
            <td class="adm-detail-content-cell-r" id="properties_list">
                <p>Поля выделенные красным обязательны к привязке!</p>
                <input type="hidden" name="<?= $name ?>" value="<?= $value ?>">
                <?
                foreach ($arSectionProps as $arProp) {
                    ?>
                    <?//pr($arProp);
                    ?>
                    <table>
                        <tr>
                            <td <?if ($arProp['is_required']){?>style="color: red" <?} ?>>
                                <p><?= $arProp['name'] ?> (<?= $arProp['type'] ?>)</p>
                                <p><?= $arProp['description'] ?></p>
                                <?
                                if (!empty($arProp['values'])) {
                                    ?>
                                    <p>
                                        Значения списка: <a href="javascript:void(0)" class="js-prop-values"
                                                            data-after="Показать значения" data-before="Скрыть значения"
                                                            data-id="<?= $arProp['id'] ?>">Показать значения</a><br>
                                        <a href="javascript:void(0)" class="js-prop-create" data-id="<?= $arProp['id'] ?>">
                                            Создать свойство
                                        </a>
                                        <br>
                                    <div class="prop_cont" data-id="<?= $arProp['id'] ?>">
                                        <? foreach ($arProp['values'] as $arValue) {?>
                                            <?= '- id = ' . $arValue['id'] . ' : value=' . $arValue['value'] . '<br>'; ?>
                                        <? } ?>
                                    </div>
                                    </p>
                                <?
                                } ?>
                                <select name="<?= $arProp['id'] ?>">
                                    <option value="0" <?if ($arResult[$arProp['id']] == 0){?>selected<?} ?>>Не выбрано</option>
                                    <?foreach ($arProperties as $arSiteProp) {?>
                                        <?if($arProp['is_collection'] && $arSiteProp['PROPERTY_TYPE'] != 'L'){
                                            continue;
                                        }?>
                                        <option value="<?= $arSiteProp['ID'] ?>" <?
                                        if ($arResult[$arProp['id']]['value'] == $arSiteProp['ID']){
                                        ?>selected<?
                                        } ?>><?= $arSiteProp['NAME'] ?></option>
                                    <?
                                    } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="text_<?= $arProp['id'] ?>" style="display: none"
                                       value="<?= $arResult[$arProp['id']]['text_value'] ?>">
                            </td>
                        </tr>
                    </table>
                <?
                } ?>
                <script>
                    $(document).ready(function () {
                        var setPropertyValues = () => {
                            var jsonData = [];

                            $('#properties_list select').each(function () {
                                var id = $(this).attr('name');
                                var text_field = $('input[name="text_' + id + '"]');

                                if ($(this).val() == '0') {
                                    text_field.show();
                                } else {
                                    text_field.hide();
                                }

                                var item = {
                                    'id': id,
                                    'value': $(this).val(),
                                    'text_value': text_field.val()
                                };
                                jsonData.push(item);
                            });
                            $('input[name="<?=$name?>"]').val(JSON.stringify(jsonData));
                        };
                        setPropertyValues();
                        $(document).on('change', '#properties_list select', function () {
                            setPropertyValues();
                        });
                        $(document).on('change', '#properties_list input[type=text]', function () {
                            setPropertyValues();
                        });
                        $(document).on('click', '.js-prop-values', function () {
                            var id = $(this).attr('data-id'),
                                cont = $('.prop_cont[data-id="' + id + '"]');
                            if (cont.length) {
                                cont.toggleClass('active');
                                if (cont.hasClass('active')) {
                                    $(this).html($(this).attr('data-before'));
                                } else {
                                    $(this).html($(this).attr('data-after'));
                                }
                            }
                        });
                        $(document).on('click', '.js-prop-create', function () {
                            var obpopup, params;

                            params = {
                                bxpublic: 'Y',
                                sessid: BX.bitrix_sessid(),
                                itemId: '<?=$request['ID']?>',
                                propId: $(this).attr('data-id'),
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
                                'title': 'Импорт',
                                'content_url': '/local/modules/ml.importozon/ajax/propertyCreate.php',
                                'content_post': params,
                                'draggable': false,
                                'resizable': false,
                                'buttons': [obBtn]
                            });
                            obpopup.Show();
                            return false;
                        });
                    });
                </script>
                <style>
                    .prop_cont {
                        display: none;
                    }

                    .prop_cont.active {
                        display: block;
                    }
                </style>
            </td>
        </tr>
        <?
        $this->EndCustomField($name);
    }
}
