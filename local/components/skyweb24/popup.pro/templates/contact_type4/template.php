<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->addExternalJS("/bitrix/js/main/core/core.js"); ?>




<? if (!is_numeric(strpos($APPLICATION->GetCurPage(), "admin"))): ?>
    <style>
        :root {
        <? foreach ($arResult['theme_color']['COLORS'] as $key => $value): ?>
        <?="--" . strtolower($key) . ":" . $value['VALUE'] . ";";?>
        <? endforeach; ?>
        }
    </style>
    <? if(is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], "Trident"))) : ?>
        <script>cssVars();</script>
    <? endif; ?>
<? endif; ?>

<? if (!empty($arResult['GOOGLE_FONT'])) : ?>
    <link href="https://fonts.googleapis.com/css?family=<?= $arResult['GOOGLE_FONT'] ?>:400,700"
          rel="stylesheet">
<? endif; ?>

<div id="skyweb24_contact_type4"
     style="<?= (!empty($arResult['GOOGLE_FONT'])) ? "font-family:" . $arResult['GOOGLE_FONT'] : ""; ?>">

    <? if ($arParams['MODE'] != "WS_TEMPLATE") : ?>
        <? $this->addExternalJS("/bitrix/components/bitrix/main.userconsent.request/templates/.default/user_consent.js"); ?>
        <? $this->addExternalJS("/bitrix/components/bitrix/main.userconsent.request/templates/.default/user_consent.css"); ?>
        <? if (empty($arResult['SUCCESS'])): ?>
            <? $param_consent = array(); ?>
            <? if ($arResult['TIMER'] == 'Y') {
            $APPLICATION->IncludeComponent('skyweb24:popup.pro.timer', '', array(
                'TITLE' => $arResult['TIMER_TEXT'],
                'TIME' => $arResult['TIMER_DATE'],
                'LEFT' => $arResult['TIMER_LEFT'],
                'RIGHT' => $arResult['TIMER_RIGHT'],
                'TOP' => $arResult['TIMER_TOP'],
                'BOTTOM' => $arResult['TIMER_BOTTOM'],
            ), $component);
        } ?>
            <div class="img"><img src="<?= $arResult['IMG_1_SRC'] ?>"></div>
            <div class="main">
                <div class="title"><?= $arResult['TITLE'] ?></div>
                <div class="subtitle">
                    <? $arResult['SUBTITLE'] = explode('<br>', $arResult['SUBTITLE']); ?>
                    <? foreach ($arResult['SUBTITLE'] as $subtitle) { ?>
                        <span><?= $subtitle ?></span>
                    <? } ?>
                </div>
                <? if (!empty($arResult['ERRORS'])) : ?>
                    <div class="error"><p><?= GetMessage("POPUPPRO_ERROR") ?></p>
                        <p><? foreach ($arResult['ERRORS'] as $nextError) { ?>
                                <?= GetMessage("POPUPPRO_ERROR_" . $nextError) ?>
                            <? } ?></p>
                    </div>
                <? endif; ?>

                <form action="<?= $templateFolder ?>/ajax.php" method="POST"
                      onsubmit="sendForm4<?= $arParams['ID_POPUP'] ?>(this);return false;">
                    <input type="hidden" name="id_popup" value="<?= $arParams['ID_POPUP'] ?>"/>
                    <input type="hidden" name="template_name" value="<?= $templateName ?>"/>
                    <?= bitrix_sessid_post() ?>
                    <fieldset>
                        <?
                        if ($arResult['NAME_SHOW'] == 'Y') {
                            if ($arResult['NAME_REQUIRED'] == 'N' || $arResult['NAME_REQUIRED'] == 'Y') {
                                $arResult['NAME_REQUIRED'] = ($arResult['NAME_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if ($arResult['NAME_SHOW'] == 'N' || $arResult['NAME_SHOW'] == 'Y') {
                            $arResult['NAME_SHOW'] = ($arResult['NAME_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['NAME_TITLE'];
                        } ?>
                        <label class="one <?= $arResult['NAME_SHOW'] ?> <?= $arResult['NAME_REQUIRED'] ?>">
                            <input <?= $arResult['NAME_REQUIRED'] ?> name="NAME" type="text"
                                                                     value="<?= $arResult['NAME'] ?>"
                                                                     placeholder="<?= $arResult['NAME_PLACEHOLDER'] ?>"/>
                            <span><?= $arResult['NAME_TITLE'] ?><sup>*</sup></span>
                        </label>
                        <?
                        if ($arResult['PHONE_SHOW'] == 'Y') {
                            if ($arResult['PHONE_REQUIRED'] == 'N' || $arResult['PHONE_REQUIRED'] == 'Y') {
                                $arResult['PHONE_REQUIRED'] = ($arResult['PHONE_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        //$arResult['PHONE_SHOW'];
                        if ($arResult['PHONE_SHOW'] == 'N' || $arResult['PHONE_SHOW'] == 'Y') {
                            $arResult['PHONE_SHOW'] = ($arResult['PHONE_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['PHONE_TITLE'];
                        } ?>
                        <label class="one <?= $arResult['PHONE_SHOW'] ?> <?= $arResult['PHONE_REQUIRED'] ?>">
                            <input <?= $arResult['PHONE_REQUIRED'] ?> type="text" value="<?= $arResult['PHONE'] ?>"
                                                                      name="PHONE"
                                                                      placeholder="<?= $arResult['PHONE_PLACEHOLDER'] ?>"/>
                            <span><?= $arResult['PHONE_TITLE'] ?><sup>*</sup></span>
                        </label>
                        <?
                        if ($arResult['EMAIL_SHOW'] == 'Y') {
                            if ($arResult['EMAIL_REQUIRED'] == 'N' || $arResult['EMAIL_REQUIRED'] == 'Y') {
                                $arResult['EMAIL_REQUIRED'] = ($arResult['EMAIL_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if ($arResult['EMAIL_SHOW'] == 'N' || $arResult['EMAIL_SHOW'] == 'Y') {
                            $arResult['EMAIL_SHOW'] = ($arResult['EMAIL_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['EMAIL_TITLE'];
                        } ?>
                        <label class="<?= $arResult['EMAIL_SHOW'] ?> <?= $arResult['EMAIL_REQUIRED'] ?> one">
                            <input <?= $arResult['EMAIL_REQUIRED'] ?> type="email" value="<?= $arResult['EMAIL'] ?>"
                                                                      name="EMAIL"
                                                                      placeholder="<?= $arResult['EMAIL_PLACEHOLDER'] ?>"/>
                            <span><?= $arResult['EMAIL_TITLE'] ?><sup>*</sup></span>
                        </label>
                        <?
                        if ($arResult['DESCRIPTION_SHOW'] == 'Y') {
                            if ($arResult['DESCRIPTION_REQUIRED'] == 'N' || $arResult['DESCRIPTION_REQUIRED'] == 'Y') {
                                $arResult['DESCRIPTION_REQUIRED'] = ($arResult['DESCRIPTION_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if ($arResult['DESCRIPTION_SHOW'] == 'N' || $arResult['DESCRIPTION_SHOW'] == 'Y') {
                            $arResult['DESCRIPTION_SHOW'] = ($arResult['DESCRIPTION_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['DESCRIPTION_TITLE'];
                            ?>
                            <div class="clear"></div>
                            <?
                        } ?>
                        <label class="<?= $arResult['DESCRIPTION_SHOW'] ?> one <?= $arResult['DESCRIPTION_REQUIRED'] ?>">
                                        <textarea <?= $arResult['DESCRIPTION_REQUIRED'] ?> name="DESCRIPTION"
                                                                                           placeholder="<?= $arResult['DESCRIPTION_PLACEHOLDER'] ?>"><?= $arResult['DESCRIPTION'] ?></textarea><span><?= $arResult['DESCRIPTION_TITLE'] ?><sup>*</sup></span>
                        </label>

                        <?
                        if ($arResult['USE_CONSENT_SHOW'] == 'N' || $arResult['USE_CONSENT_SHOW'] == 'Y') {
                            $arResult['USE_CONSENT_SHOW'] = ($arResult['USE_CONSENT_SHOW'] == 'N') ? 'notshow' : '';
                        }
                        if ($arResult['USE_CONSENT_SHOW'] != 'N' && count($arResult['AGREEMENTS']) > 0) {
                            ?>
                            <div class="<?= $arResult['USE_CONSENT_SHOW'] ?> consentBlock">
                                <input type="checkbox" name="use_consent" value="Y" checked="checked" required/> <a
                                        href="/bitrix/tools/skyweb24_agreement.php?ID=<?= $arResult['CONSENT_ID'] ?>"
                                        target="_blank"><?= $arResult['CONSENT_LIST'] ?></a>
                            </div>
                        <? } ?>

                        <label class="submit sw24-button-animate">
                            <input type="submit" onclick="" value="<?= $arResult['BUTTON_TEXT'] ?>">
                        </label>

                        <? if (($arResult['CLOSE_TEXTBOX'] == 'Y') && (!empty($arResult['CLOSE_TEXTAREA']))) { ?>
                            <div align="center"><a href="javascript:void(0);"
                                                   class="sw24TextCloseButton"><?= $arResult['CLOSE_TEXTAREA'] ?></a></div>
                        <? } ?>
                        <? if ($arResult['INTEG_CRM_ACTIVE'] == "Y") : ?>
                            <? if ($arResult["INTEG_CRM_SERVER"] != "0"): ?>
                                <input type="hidden" name="CRM_SERVER_ID" value="<?= $arResult['INTEG_CRM_SERVER']; ?>">
                                <input type="hidden" name="SOURCE_DESCRIPTION" value="<?= $_SERVER['HTTP_HOST']; ?>">
                            <? endif; ?>
                        <? endif; ?>
                        <div class="clear"></div>
                    </fieldset>
                </form>
            </div>
            <script>

                if (typeof (buttonWindowPopup) != "undefined" && buttonWindowPopup.popupid == "") {

                    buttonWindowPopup.active = "<?=$arResult['BWP_ACTIVE']?>";
                    buttonWindowPopup.popupid = "<?=$arParams['ID_POPUP']?>";

                    if (buttonWindowPopup.active == "Y") {
                        buttonWindowPopup.create({
                            position: {
                                left: '<?=$arResult['BWP_POSITION_LEFT'];?>',
                                top: '<?=$arResult['BWP_POSITION_TOP']; ?>',
                                bottom: '<?=$arResult['BWP_POSITION_BOTTOM']; ?>',
                                right: '<?=$arResult['BWP_POSITION_RIGHT']; ?>',
                            },
                            animation: {
                                name: "<?=$arResult['BWP_ANIMATION'];?>",
                                dalay: "slower",
                            },
                            background: {
                                color: "<?=$arResult['BWP_BACKGROUND'];?>"
                            },
                            icon: {
                                class: "<?=$arResult['BWP_ICON'];?>",
                                color: "<?=$arResult['BWP_ICON_COLOR'];?>",
                            }
                        });
                    }
                }


                var buttonAnimation = '<?=$arResult['BUTTON_ANIMATION'];?>';
                var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
                var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


                skyweb24_windowAnimation.show(showWindowAnimation);
                skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);


                function sendForm4<?=$arParams['ID_POPUP']?>(f) {
                    var sendO = {},
                        cInputs = f.querySelectorAll("input, textarea");

                    for (var i = 0; i < cInputs.length; i++) {
                        sendO[cInputs[i].name] = cInputs[i].value;
                    }
                    BX.ajax({
                        url: f.action,
                        data: sendO,
                        method: 'POST',
                        dataType: 'html',
                        scriptsRunFirst: false,
                        timeout: 300,
                        onsuccess: function (data) {
                            BX("skyweb24_contact_type4").outerHTML = data;
                            skyweb24positionBanner(skyweb24Popups.Popup.getOption("currentPopup"));
                            <?=$arResult['BUTTON_METRIC']?>

                            if (buttonWindowPopup.element)
                                buttonWindowPopup.destroy();
                        },
                        onfailure: function (data) {
                            console.log(data);
                        }
                    });
                }
            </script>
        <? endif; ?>
        <? if (!empty($arResult['SUCCESS']) && $arResult['SUCCESS'] == 'Y'): ?>
        <div class="success">
            <? if (isset($arResult['WINDOW_SUCCESS']) AND !empty($arResult['WINDOW_SUCCESS'])) : ?>
                <div class="subtitle"><?= $arResult['WINDOW_SUCCESS']['WS_TITLE']["VALUE"]; ?></div>
                <div class="text"><?= $arResult['WINDOW_SUCCESS']['WS_DESCRIPTION']["VALUE"]; ?></div>
            <? else : ?>
                <?= GetMessage("POPUPPRO_THANKS") ?>
            <? endif; ?>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                skyweb24Popups.Popup.getOption("currentPopup")
                    .popupContainer.classList.add('success_send');
            });
        </script>
    <? endif; ?>
    <? else : ?>
        <div class="success">
            <div class="subtitle"><?= $arResult['WS_TITLE']; ?></div>
            <div class="text"><?= $arResult['WS_DESCRIPTION']; ?></div>
        </div>
    <? endif; ?>
</div>
