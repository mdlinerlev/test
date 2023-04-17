<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="b2b-content__wrp">
    <div class="b2b-req">
        <div class="b2b-req__list js-editor__coord js-edit-wrp">
            <? foreach ($arResult['ITEMS'] as $arItem) { ?>
                <div class="b2b-req__item js-b2b-req__item">
                    <form type="post" id="item-<?= $arItem['ID'] ?>">
                        <input type="hidden" name="ID" value="<?= $arItem['ID'] ?>">
                        <div class="b2b-req__item-head">
                            <div class="name">
                                <div class="checkbox js-checkbox">
                                    <input type="checkbox" data-id="<?= $arItem['ID'] ?>">
                                    <label><?= $arItem['NAME'] ?></label>
                                </div>
                            </div>
                            <div class="btns">
                                <button class="button js-edit">
                                    <svg class="icon icon-edit ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#edit"></use>
                                    </svg>
                                    <span>Редактировать</span>
                                </button>
                                <button class="button js-save _hide" type="submit" name="save"
                                        form="item-<?= $arItem['ID'] ?>">
                                    <svg class="icon icon-save ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#save"></use>
                                    </svg>
                                    <span>Сохранить</span>
                                </button>
                                <div class="js-open arrow">
                                    <svg class="icon icon-down-arrow ">
                                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#down-arrow"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="b2b-req__item-content">
                            <div class="b2b-req__item-step _open">
                                <div class="b2b-req__item-step__head">
                                    <div class="name">
                                        <div class="img-type">
                                            <svg class="icon icon-info ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#info"></use>
                                            </svg>
                                        </div>
                                        <span>Данные юридического лица</span>
                                    </div>
                                </div>
                                <div class="b2b-req__item-step__content" style="display: block;">
                                    <ul>
                                        <li>
                                            <ul>
                                                <li>Название организации</li>
                                                <li class="js-editable">
                                                    <input type="text" name="NAME" value="<?= $arItem['NAME'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Телефон</li>
                                                <li class="js-editable">
                                                    <input type="tel" name="PROPERTIES[PHONE]"
                                                           data-mask="+7 (999) 999-99-99"
                                                           value="<?= $arItem['PROPERTIES']['PHONE']['VALUE'] ?>"
                                                           placeholder="+7 (___) ___-__-__">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Email</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[EMAIL]"
                                                           value="<?= $arItem['PROPERTIES']['EMAIL']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Менеджер</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[MANAGER]"
                                                           value="<?= $arItem['PROPERTIES']['MANAGER']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Текст до таблицы в кп</li>
                                                <li class="js-editable">
                                                    <textarea name="PROPERTIES[TEXT_TABLE_BEFORE]" rows="10"><?= $arItem['PROPERTIES']['TEXT_TABLE_BEFORE']['~VALUE']['TEXT'] ?></textarea>
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Текст после таблицы в кп</li>
                                                <li class="js-editable">
                                                    <textarea name="PROPERTIES[TEXT_TABLE_AFTER]" rows="10"><?= $arItem['PROPERTIES']['TEXT_TABLE_AFTER']['~VALUE']['TEXT'] ?></textarea>
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="b2b-req__item-step js-b2b-req__item-step">
                                <div class="b2b-req__item-step__head">
                                    <div class="name">
                                        <div class="img-type">
                                            <svg class="icon icon-reauisites ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#reauisites"></use>
                                            </svg>
                                        </div>
                                        <span>Реквизиты юридического лица</span>
                                    </div>
                                    <div class="js-arrow arrow">
                                        <svg class="icon icon-down-arrow ">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#down-arrow"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="b2b-req__item-step__content">
                                    <ul>
                                        <li>
                                            <ul>
                                                <li>УНП/ИНН</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[UNP]"
                                                           value="<?= $arItem['PROPERTIES']['UNP']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>КПП</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[KPP]"
                                                           value="<?= $arItem['PROPERTIES']['KPP']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Расчетный счет</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[PAYMENT_ACCOUNT]"
                                                           value="<?= $arItem['PROPERTIES']['PAYMENT_ACCOUNT']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Название банка</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[BANK_NAME]"
                                                           value="<?= $arItem['PROPERTIES']['BANK_NAME']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Адрес банка</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[BANK_ADDRESS]"
                                                           value="<?= $arItem['PROPERTIES']['BANK_ADDRESS']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>Кассовый счет</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[CASH_ACCOUNT]"
                                                           value="<?= $arItem['PROPERTIES']['CASH_ACCOUNT']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                                <li>БИК</li>
                                                <li class="js-editable">
                                                    <input type="text" name="PROPERTIES[BIC]"
                                                           value="<?= $arItem['PROPERTIES']['BIC']['VALUE'] ?>"
                                                           placeholder="Введите данные">
                                                    <button class="js-reset button">
                                                        <svg class="icon icon-delete ">
                                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                        </svg>
                                                    </button>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="b2b-req__item-step js-b2b-req__item-step">
                                <div class="b2b-req__item-step__head">
                                    <div class="name">
                                        <div class="img-type">
                                            <svg class="icon icon-address ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#address"></use>
                                            </svg>
                                        </div>
                                        <span>Адреса</span>
                                    </div>
                                    <div class="js-editable">
                                        <div class="btns">
                                            <button class="button js-add-entity" data-entity="address" data-container="<?=$arItem['ID']?>">
                                                <svg class="icon icon-edit ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#edit"></use>
                                                </svg>
                                                <span>Добавить адрес</span>
                                            </button>
                                        </div>
                                        <div class="js-arrow arrow">
                                            <svg class="icon icon-down-arrow ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#down-arrow"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="b2b-req__item-step__content">
                                    <ul data-entity-container="<?=$arItem['ID']?>">
                                        <? foreach ($arItem['PROPERTIES']['ACTUAL_ADDRESS']['VALUE'] as $arVal) { ?>
                                            <li>
                                                <ul>
                                                    <li>Фактический адрес</li>
                                                    <li class="js-editable">
                                                            <textarea placeholder="Введите данные"
                                                                      name="PROPERTIES[ACTUAL_ADDRESS][]"><?= $arVal ?></textarea>
                                                        <button class="js-reset button">
                                                            <svg class="icon icon-delete ">
                                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                            </svg>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <? } ?>
            <a class="b2b-req__add button ajax-form" data-href="/ajax/reAjax.php?action=modalRequisits"
               data-class="w820">Добавить компанию</a>
        </div>

        <div class="b2b-editor__wrp js-edit__editor-wrp">
            <form class="b2b-editor js-edit__editor" id="edit">
                <select class="styler" name="type" id="type">
                    <option value="">Выберите действие</option>
                    <option value="del">Удалить</option>
                </select>
                <button class="button" type="submit" form="edit">Применить</button>
                <div class="checkbox js-checkbox">
                    <input type="checkbox" name="all" id="all">
                    <label>Для всех</label>
                </div>
            </form>
        </div>
    </div>
</div>