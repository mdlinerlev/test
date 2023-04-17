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
<? foreach ($arResult['ITEMS'] as $arItem){ ?>
<div class="b2b-profile__col _lg">
    <div class="b2b-profile__item">
        <div class="b2b-profile__item-head">
            <div class="img">
                <svg class="icon icon-info ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#info"></use>
                </svg>
            </div>
            <span>Данные юридического лица</span>
        </div>
        <div class="b2b-profile__item-content">
            <div class="b2b-profile__item-list">
                <ul>
                    <li><b>Название организации</b></li>
                    <li><span><?= $arItem['NAME'] ?></span>
                    </li>
                </ul>
                <? if ($arItem['PROPERTIES']['PHONE']['VALUE']) { ?>
                    <ul>
                        <li><b>Телефон</b></li>
                        <li>
                            <a href="tel::+<?= NormalizePhone($arItem['PROPERTIES']['PHONE']['VALUE']); ?>">
                                <span><?= $arItem['PROPERTIES']['PHONE']['VALUE'] ?></span>
                            </a>
                        </li>
                    </ul>
                <? } ?>
                <? if ($arItem['PROPERTIES']['EMAIL']['VALUE']) { ?>
                    <ul>
                        <li><b>Email</b></li>
                        <li>
                            <a href="mailto::<?= $arItem['PROPERTIES']['EMAIL']['VALUE'] ?>">
                                <span><?= $arItem['PROPERTIES']['EMAIL']['VALUE'] ?></span>
                            </a>
                        </li>
                    </ul>
                <? } ?>
            </div>
            <?
            $prewImg = SITE_TEMPLATE_PATH.'/img/no-img-200x200.svg';
            if($arItem['PREVIEW_PICTURE']['SRC']){
                $prewImg = $arItem['PREVIEW_PICTURE']['SRC'];
            }
            ?>
            <form class="b2b-profile__item-form _border">
                <input type="hidden" name="id" value="<?=$arItem['ID']?>">
                <div class="b2b-profile__item-form__elem">
                    <label>Логотип</label>
                    <p id="error_img_cont" class="error"></p>
                    <div class="b2b-profile__item-form__file js-input-photo">
                        <div class="file js-add">
                            <input type="file" name="PREVIEW_PICTURE" data-size="200" accept="image/*">
                            <div class="file-content">
                                <svg class="icon icon-upload ">
                                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#upload"></use>
                                </svg>
                                <span>
                                    <b>Нажмите, чтобы загрузить логотип</b>
                                </span>
                                <span>Файл должен быть до 200 кб</span>
                            </div>
                        </div>
                        <div class="preview js-image" style="background: url('<?=$prewImg?>')"></div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="b2b-profile__item">
        <div class="b2b-profile__item-head">
            <div class="img">
                <svg class="icon icon-reauisites ">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#reauisites"></use>
                </svg>
            </div>
            <span>Реквизиты юридического лица</span>
        </div>
        <div class="b2b-profile__item-content">
            <div class="b2b-profile__item-list">
                <? if ($arItem['PROPERTIES']['UNP']['VALUE']) { ?>
                    <ul>
                        <li><b>УНП/ИНН</b></li>
                        <li>
                            <span><?= $arItem['PROPERTIES']['UNP']['VALUE'] ?></span>
                        </li>
                    </ul>
                <? } ?>
                <? if ($arItem['PROPERTIES']['KPP']['VALUE']) { ?>
                    <ul>
                        <li><b>КПП</b></li>
                        <li><span><?= $arItem['PROPERTIES']['KPP']['VALUE'] ?></span>
                        </li>
                    </ul>
                <? } ?>
                <? if ($arItem['PROPERTIES']['CITY_REGISTER']['VALUE']) { ?>
                    <ul>
                        <li><b>Страна регистрации</b></li>
                        <li><span><?= $arItem['PROPERTIES']['CITY_REGISTER']['VALUE'] ?></span>
                        </li>
                    </ul>
                <? } ?>
                <? if ($arItem['PROPERTIES']['PARTNER']['VALUE']){ ?>
                <ul>
                    <li><b>Партнер</b></li>
                    <li><span><?= $arItem['PROPERTIES']['PARTNER']['VALUE'] ?></span>
                    </li>
                </ul>
                <? } ?>
                <? if ($arItem['PROPERTIES']['ADDITIONAL']['VALUE']) { ?>
                    <ul>
                        <li><b>Дополнительно</b></li>
                        <li><span><?= $arItem['PROPERTIES']['ADDITIONAL']['VALUE'] ?></span>
                        </li>
                    </ul>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<? } ?>

