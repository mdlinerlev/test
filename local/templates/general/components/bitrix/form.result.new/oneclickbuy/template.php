<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>

<?if ($arResult["isFormNote"] != "Y")
{
    ?>
    <?=$arResult["FORM_HEADER"]?>

    <div class="popup__title"><?= $arResult['PRODUCT_NAME']?></div>
    <div class="popup_info">
        <?foreach ($arResult['PROP'] as $prop):?>
        <div class="popup_info__row">
            <span class="popup_info_first"><?= $prop['NAME']?></span>
            <span class="popup_info_dots"></span>
            <span class="popup_info_second"><?= $prop['DISPLAY_VALUE']?></span>
        </div>
        <?endforeach;?>

        <?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

        <input type="hidden" name="productID" value="<?= $arParams['PRODUCT_ID'] ? : $request['productID']?>"/>

        <div class="popup-form__descr">Заполните форму быстрого заказа и наши менеджеры скоро свяжутся с вами</div>
        <?
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
        {
            $code = $arResult['arQuestions'][$FIELD_SID]['COMMENTS'] ? : $FIELD_SID;

            $value = $_POST['form_' . $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arQuestion['STRUCTURE'][0]['ID']] ? $_POST['form_' . $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arQuestion['STRUCTURE'][0]['ID']] :  $arQuestion['STRUCTURE'][0]['VALUE']; ?>


            <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden'): ?>
            <div class="popup-form__row">
                <div class="popup-form__form-group">
                        <label for="<?=$FIELD_SID?>" class="popup-form__label"><?=$arQuestion["CAPTION"]?> <?if ($arQuestion["REQUIRED"] == "Y"):?><span class="required">*</span><?endif;?></label>
            <? else:
                        $value = $arResult['PRODUCT'];
            endif; ?>

                <input type="<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>"
                       name="form_<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?>_<?= $arQuestion['STRUCTURE'][0]['ID'] ?>"
                       <?= $arQuestion['STRUCTURE'][0]['FIELD_PARAM']?>
                       value="<?= $value ?>"/>

            <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden'): ?>
                </div>
            </div>
            <?endif;
        }

        if($arResult["isUseCaptcha"] == "Y")
        {
            ?>
            <div class="popup-form__row">
                <div class="popup-form__form-group">
                    <b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b>
                </div>
                <div class="popup-form__form-group">
                    <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
                </div>
                <div class="popup-form__form-group">
                    <label for="measurement-address" class="popup-form__label"><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></label>
                    <input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
                </div>
            </div>
            <?
        } // isUseCaptcha
        ?>

        <div class="popup-form__row">
            <input class="popup-form__button_valid<?=$arResult["arForm"]["SID"]?> button popup__submit" type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
        </div>


        <?if(!empty($arResult["FORM_DESCRIPTION"])):?>
            <div class="popup-form__row popup-form__row--comments">
                <?=$arResult["FORM_DESCRIPTION"]?>
            </div>
        <?endif;?>

    </div>

    <?=$arResult["FORM_FOOTER"]?>
    <?
}  else {
    if($idProduct = CIBlockElement::GetProperty(12, $arParams['PRODUCT_ID'], array("sort" => "asc"), Array("CODE"=>"CML2_LINK"))->Fetch()["VALUE"]) {
        $idProduct = $idProduct;
    } else {
        $idProduct = $arParams['PRODUCT_ID'];
    }
    ?>
    <div id="msgSent">
        <p><?=GetMessage("SEND_FORM_NOTE_ADDOK")?></p>
        <button class="msgSent">ОК</button>
    </div>
    <script>
        (function () {
            var key = '__rtbhouse.lid';
            var lid = window.localStorage.getItem(key);
            if (!lid) {
                lid = '';
                var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
                window.localStorage.setItem(key, lid);
            }
            var body = document.getElementsByTagName("body")[0];
            var ifr = document.createElement("iframe");
            var siteReferrer = document.referrer ? document.referrer : '';
            var siteUrl = document.location.href ? document.location.href : '';
            var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
            var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
            var timestamp = "" + Date.now();
            var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_1click-1click_ru-<?=$idProduct?>&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
            ifr.setAttribute("src", source);
            ifr.setAttribute("width", "1");
            ifr.setAttribute("height", "1");
            ifr.setAttribute("scrolling", "no");
            ifr.setAttribute("frameBorder", "0");
            ifr.setAttribute("style", "display:none");
            ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
            body.appendChild(ifr);
        }());
    </script>
    <?
}
?>

<a title="Закрыть (Esc)" class="mfp-close"></a>
