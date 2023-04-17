<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if ($arResult['MANAGER']) {
    $this->__template->SetViewTarget("MANAGER");
    $arManager = $arResult['MANAGER'];
    if ($arManager) { ?>
        <div class="b2b-profile__item">
            <div class="b2b-profile__item-head">
                <div class="img">
                    <svg class="icon icon-anchored ">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#anchored"></use>
                    </svg>
                </div>
                <span>Закрепленный специалист</span>
            </div>
            <div class="b2b-profile__item-content">
                <div class="b2b-profile__item-list">
                    <? if ($arManager['NAME']) { ?>
                        <ul>
                            <li><b>ФИО</b></li>
                            <li><span><?= $arManager['NAME'] ?></span>
                            </li>
                        </ul>
                    <? } ?>
                    <? if ($arManager['EMAIL']) { ?>
                        <ul>
                            <li><b>Email</b></li>
                            <li><a href="mailto::<?= $arManager['EMAIL'] ?>"><span><?= $arManager['EMAIL'] ?></span></a>
                            </li>
                        </ul>
                    <? } ?>
                    <? if ($arManager['PHONE']) { ?>
                        <ul>
                            <li><b>Телефон</b></li>
                            <li>
                                <a href="tel::+<?= NormalizePhone($arManager['PHONE']) ?>"><span><?= $arManager['PHONE'] ?></span></a>
                            </li>
                        </ul>
                    <? } ?>
                </div>
            </div>
        </div>
    <? } ?>
    <?
    $this->__template->EndViewTarget();
}
if ($arResult['DOCUMENT']) {
    $this->__template->SetViewTarget("DOCUMENT");
    $arDocument = $arResult['DOCUMENT'];
    if ($arDocument) { ?>
        <div class="b2b-profile__item">
            <div class="b2b-profile__item-head">
                <div class="img">
                    <svg class="icon icon-dogovor ">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#dogovor"></use>
                    </svg>
                </div>
                <span>Договор</span>
            </div>
            <div class="b2b-profile__item-content">
                <div class="b2b-profile__item-list">
                    <? if ($arDocument['DOCUMENT']) {
                        $file = CFile::GetFileArray($arDocument['DOCUMENT']);
                        ?>
                        <ul>
                            <li><b>Номер договора</b></li>
                            <li>
                                <? if ($file['CONTENT_TYPE'] == 'application/pdf') { ?>
                                    <a href="<?= $file['SRC'] ?>" target="_blank">
                                        <span class="flex">
                                            <svg class="icon icon-pdf ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#pdf"></use>
                                            </svg>
                                            <span><?= $file['ORIGINAL_NAME'] ?></span>
                                        </span>
                                    </a>
                                <? } else { ?>
                                    <a href="<?= $file['SRC'] ?>" download="<?= $file['ORIGINAL_NAME'] ?>">
                                        <span class="flex">
                                            <svg class="icon icon-pdf ">
                                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#pdf"></use>
                                            </svg>
                                            <span><?= $file['ORIGINAL_NAME'] ?></span>
                                        </span>
                                    </a>
                                <? } ?>
                            </li>
                        </ul>
                    <? } ?>
                    <? if ($arDocument['PERIOD']) { ?>
                        <ul>
                            <li><b>Период действия</b></li>
                            <li><span><?= $arDocument['PERIOD'] ?></span>
                            </li>
                        </ul>
                    <? } ?>
                </div>
            </div>
        </div>
    <? } ?>
    <?
    $this->__template->EndViewTarget();
}
if ($arResult['ADDRESS']) {
    $this->__template->SetViewTarget("ADDRESS"); ?>
    <? if (!empty($arResult['ADDRESS'])) { ?>
        <div class="b2b-req__item-step js-b2b-req__item-step">
            <div class="b2b-req__item-step__head">
                <div class="name">
                    <div class="img-type">
                        <svg class="icon icon-address ">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#address"></use>
                        </svg>
                    </div>
                    <span>Адрес</span>
                </div>
            </div>
            <div class="b2b-req__item-step__content" style="display: block">
                <ul>
                    <? if ($arResult['ADDRESS']['ACTUAL_ADDRESS']) { ?>
                        <li>
                            <ul>
                                <li>Фактический адрес</li>
                                <li>
                                                <textarea
                                                        placeholder="Введите данные"><?= $arResult['ADDRESS']['ACTUAL_ADDRESS'][array_key_first( $arResult['ADDRESS']['ACTUAL_ADDRESS'])]?></textarea>
                                    <button class="js-reset button">
                                        <svg class="icon icon-delete ">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </li>
                    <? } ?>
                    <? if ($arResult['ADDRESS']['LAW_ADDRESS']) { ?>
                        <li>
                            <ul>
                                <li>Юридический адрес</li>
                                <li>
                                            <textarea
                                                    placeholder="Введите данные"><?= $arResult['ADDRESS']['LAW_ADDRESS'] ?></textarea>
                                    <button class="js-reset button">
                                        <svg class="icon icon-delete ">
                                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </li>
                    <? } ?>
                    <? if ($arResult['ADDRESS']['POST_ADDRESS']) { ?>
                        <li>
                            <ul>
                                <li>Почтовый адрес</li>
                                <li>
                                            <textarea
                                                    placeholder="Введите данные"><?= $arResult['ADDRESS']['POST_ADDRESS'] ?></textarea>
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
    <? } ?>
    <? $this->__template->EndViewTarget();
}