<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="popup-b2b__wrp">
    <div class="popup-b2b__head">
        <div class="popup-b2b__zag">Задайте свой вопрос</div>
    </div>
    <? if ($arResult["isFormNote"] != "Y") { ?>
        <?= $arResult["FORM_HEADER"] ?>
        <? if ($arResult["isFormErrors"] == "Y") { ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? } ?>
        <div class="popup-b2b__form">
            <div class="popup-b2b__form-wrp">
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
                        echo $arQuestion["HTML_CODE"];
                    } else { ?>
                        <div class="popup-b2b__form-item">
                            <label><?= $arQuestion['CAPTION'] ?><? if ($arQuestion["REQUIRED"] == "Y") { ?><?= $arResult["REQUIRED_SIGN"]; ?><?
                                } ?></label>
                            <?= $arQuestion["HTML_CODE"] ?>
                        </div>
                    <? }
                } ?>
                <? if ($arResult["isUseCaptcha"] == "Y") { ?>
                    <div class="popup-b2b__form-item">
                        <input type="hidden" name="captcha_sid"
                               value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
                             width="180" height="40"/>
                        <input type="text" name="captcha_word" size="30" maxlength="50" value=""
                               class="inputtext"/>
                    </div>
                <? } ?>
            </div>
            <div class="popup-b2b__form-footer">
                <input class="button" <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?>
                       type="submit" name="web_form_submit"
                       value="<?= htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>"/>
            </div>
        </div>
        <?= $arResult["FORM_FOOTER"] ?>
    <? } else { ?>
        <p><?= $arResult["FORM_NOTE"] ?></p>
    <? } ?>
</div>