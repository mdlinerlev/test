<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="popup-b2b__wrp">
    <div class="popup-b2b__head">
        <div class="popup-b2b__zag">Войти</div>
    </div>
    <? if ($arResult["FORM_TYPE"] == "login") { ?>
        <? if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
            ShowMessage($arResult['ERROR_MESSAGE']);
        ?>
        <form name="system_auth_form<?= $arResult["RND"] ?>"
              method="post"
              action="<?= $arResult["AUTH_URL"] ?>"
              class="popup-b2b__form"
              id="form_auth"
        >
            <? foreach ($arResult["POST"] as $key => $value): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
            <? endforeach ?>
            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>
            <? if ($arResult["BACKURL"] <> '') { ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? } ?>
            <? if ($arResult["STORE_PASSWORD"] == "Y") { ?>
                <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y"/>
            <? } ?>
            <div class="popup-b2b__form-wrp">
                <div class="popup-b2b__form-item _withImg">
                    <div class="popup-b2b__form-item__img">
                        <svg class="icon icon-login ">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#login"></use>
                        </svg>
                    </div>
                    <input type="text" placeholder="Введите Ваш логин" name="USER_LOGIN" maxlength="50" value=""
                           size="17"/>
                </div>
                <div class="popup-b2b__form-item _withImg">
                    <div class="popup-b2b__form-item__img">
                        <svg class="icon icon-password ">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/img/svg/symbol/sprite.svg#password"></use>
                        </svg>
                    </div>
                    <input type="text" placeholder="Введите Ваш пароль" name="USER_PASSWORD" maxlength="255"
                           size="17" autocomplete="off"/>
                </div>
                <? if ($arResult["CAPTCHA_CODE"]) { ?>
                    <div class="popup-b2b__form-item">
                        <label><? echo GetMessage("AUTH_CAPTCHA_PROMT") ?></label>
                        <input type="hidden" name="captcha_sid" value="<? echo $arResult["CAPTCHA_CODE"] ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<? echo $arResult["CAPTCHA_CODE"] ?>"
                             width="180" height="40" alt="CAPTCHA"/>
                        <input type="text" name="captcha_word" maxlength="50" value=""/>
                    </div>
                <? } ?>
                <div class="popup-b2b__form-item">
                    <a class="link" href="#">Забыли пароль?</a>
                </div>
            </div>
            <div class="popup-b2b__form-footer">
                <button class="button" name="Login" value="<?= GetMessage("AUTH_LOGIN_BUTTON") ?>" form="form_auth"
                        type="submit">Войти
                </button>
            </div>
        </form>
    <? } else { ?>
        <script>
            location.href = "/personal-b2b/";
        </script>
    <? } ?>
</div>
