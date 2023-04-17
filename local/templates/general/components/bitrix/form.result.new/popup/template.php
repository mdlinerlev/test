<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>




<?if ($arResult["isFormNote"] != "Y")
{
    ?>
    <?=$arResult["FORM_HEADER"]?>
    <div class="popup__title-container">
        <div><div class="popup__title"><?=$arResult["FORM_TITLE"]?></div></div>
        <?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
    </div>
    <div class="popup__content">
        <section class="popup__form">


            <?
            $closeDiv = true;
            foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
            {

                $code = $arResult['arQuestions'][$FIELD_SID]['COMMENTS'] ? : $FIELD_SID;

                if($code != 'PHONE') echo '<div class="popup-form__row">'
                ?>

                    <div class="popup-form__form-group  popup-form__form-group--<?= mb_strtolower($code)?>">
                        <label for="measurement-address" class="popup-form__label"><?=$arQuestion["CAPTION"]?> <?if ($arQuestion["REQUIRED"] == "Y"):?><span class="required">*</span><?endif;?></label>
                        <?=$arQuestion["HTML_CODE"]?>
                    </div>

                <?
                if($code != 'NAME') {
                    $closeDiv = false; echo '</div>';
                }
                ?>


                <?
            } //endwhile

            if($closeDiv)  echo '</div>';

            ?>
            <?
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




            <div class="popup-form__row popup-form__row--submit">
                <div class="popup-form__comment"><?=$arResult["REQUIRED_SIGN"];?> —  <?=GetMessage("FORM_REQUIRED_FIELDS")?></div>
                <input class="popup-form__button_valid<?=$arResult["arForm"]["SID"]?> popup-form__button popup-form__button--submit button" type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
            </div>


            <?if(!empty($arResult["FORM_DESCRIPTION"])):?>
            <div class="popup-form__row popup-form__row--comments">
                <?=$arResult["FORM_DESCRIPTION"]?>
            </div>
            <?endif;?>
        </section>
    </div>


    <script type="text/javascript">
      $(".phone_input").mask("+7 (999) 999-99-99");
    </script>

    <?=$arResult["FORM_FOOTER"]?>
    <?
}  else {
    ?>
    <div id="msgSent">
        <p><?=GetMessage("SEND_FORM_NOTE_ADDOK")?></p>
        <button class="msgSent">ОК</button>
    </div>
    <?
    switch ($arParams["WEB_FORM_ID"]) {
        case 1:
            ?>
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
                    var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_measure-measure_ru-measure&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
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
            break;
        case 2:
            ?>
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
                    var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_2_callback-callback_ru-callback&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
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
            break;
    }?>
<?}?>