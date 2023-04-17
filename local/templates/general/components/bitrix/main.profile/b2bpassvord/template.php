<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<div class="b2b-profile__item">
    <div class="b2b-profile__item-head">
        <div class="img">
            <svg class="icon icon-exit ">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#exit"></use>
            </svg>
        </div>
        <span>Вход в систему</span>
    </div>
    <div class="b2b-profile__item-content">
        <?ShowError($arResult["strProfileError"]);?>
        <?
        if ($arResult['DATA_SAVED'] == 'Y')
            ShowNote(GetMessage('PROFILE_DATA_SAVED'));
        ?>
        <form method="post" name="form1"
              class="b2b-profile__item-form"
              action="<?= $arResult["FORM_TARGET"] ?>"
              enctype="multipart/form-data"
        >
            <?= $arResult["BX_SESSION_CHECK"] ?>
            <input type="hidden" name="lang" value="<?= LANG ?>"/>
            <input type="hidden" name="ID" value=<?= $arResult["ID"] ?>/>
            <div class="b2b-profile__item-form__elem">
                <label>Логин</label>
                <input type="text"
                       name="LOGIN"
                       value="<? echo $arResult["arUser"]["LOGIN"] ?>"
                       disabled
                       maxlength="50"
                >
            </div>
            <div class="b2b-profile__item-form__elem">
                <label>
                    <span>Пароль</span>
                    <a class="js-change-state-passvord btn" href="javascript:void(0)" data-before="Изменить пароль" data-after="Сохранить"></a>
                </label>
                <input type="password" class="js-passvord-input" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" disabled=""/>
                <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" style="display: none"/>
            </div>

            <input type="submit" name="save" style="display: none"
                   value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>">
        </form>
    </div>
</div>