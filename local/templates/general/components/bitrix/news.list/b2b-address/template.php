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
<? if ($arResult['ITEMS']) { ?>
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
            <div class="js-arrow arrow">
                <svg class="icon icon-down-arrow ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#down-arrow"></use>
                </svg>
            </div>
        </div>
        <div class="b2b-req__item-step__content _p0">
            <? foreach ($arResult['ITEMS'] as $arItem) { ?>
                <div class="b2b-req__item-step js-b2b-req__item-step">
                    <div class="b2b-req__item-step__head">
                        <span><?= $arItem['NAME'] ?></span>
                        <div class="js-arrow arrow">
                            <svg class="icon icon-down-arrow ">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#down-arrow"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="b2b-req__item-step__content">
                        <ul>
                            <? if ($arItem['PROPERTIES']['ACTUAL_ADDRESS']['VALUE']) { ?>
                                <li>
                                    <ul>
                                        <li>Фактический адрес</li>
                                        <li>
                                            <textarea
                                                    placeholder="Введите данные"><?= $arItem['PROPERTIES']['ACTUAL_ADDRESS']['VALUE'] ?></textarea>
                                            <button class="js-reset button">
                                                <svg class="icon icon-delete ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                            <? } ?>
                            <? if ($arItem['PROPERTIES']['LAW_ADDRESS']['VALUE']) { ?>
                                <li>
                                    <ul>
                                        <li>Юридический адрес</li>
                                        <li>
                                        <textarea
                                                placeholder="Введите данные"><?= $arItem['PROPERTIES']['LAW_ADDRESS']['VALUE'] ?></textarea>
                                            <button class="js-reset button">
                                                <svg class="icon icon-delete ">
                                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#delete"></use>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                            <? } ?>
                            <? if ($arItem['PROPERTIES']['POST_ADDRESS']['VALUE']) { ?>
                                <li>
                                    <ul>
                                        <li>Почтовый адрес</li>
                                        <li>
                                        <textarea
                                                placeholder="Введите данные"><?= $arItem['PROPERTIES']['POST_ADDRESS']['VALUE'] ?></textarea>
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
        </div>
    </div>
<? } ?>
