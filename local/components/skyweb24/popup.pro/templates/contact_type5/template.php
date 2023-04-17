<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
<script type="text/javascript" src="/bitrix/js/main/core/core.js"></script>


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

<div id="skyweb24_contact_type5" style="<? if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
    <? if($arParams['MODE'] != "WS_TEMPLATE") : ?>
        <? $this->addExternalJS("/bitrix/components/bitrix/main.userconsent.request/templates/.default/user_consent.js"); ?>
        <? $this->addExternalJS("/bitrix/components/bitrix/main.userconsent.request/templates/.default/user_consent.css"); ?>
	    <?if(empty($arResult['SUCCESS'])): ?>
            <? $param_consent = array(); ?>
            <? if ($arResult['TIMER'] == 'Y'):?>
                <?$APPLICATION->IncludeComponent('skyweb24:popup.pro.timer', '', array(
                    'TITLE' => $arResult['TIMER_TEXT'],
                    'TIME' => $arResult['TIMER_DATE'],
                    'LEFT' => $arResult['TIMER_LEFT'],
                    'RIGHT' => $arResult['TIMER_RIGHT'],
                    'TOP' => $arResult['TIMER_TOP'],
                    'BOTTOM' => $arResult['TIMER_BOTTOM'],
                ), $component);?>
            <? endif; ?>
                <div class="row_img">
                    <img src="<?=$arResult['IMG_1_SRC']?>">
                </div>
                <div class="row_content">
                    <div class="title"><?=$arResult['TITLE']?></div>
                    <div class="subtitle">
                        <?$arResult['SUBTITLE'] = explode('<br>',$arResult['SUBTITLE']);?>
                        <?foreach($arResult['SUBTITLE'] as $subtitle):?>
                            <span><?=$subtitle?></span>
                        <? endforeach; ?>
                    </div>
                    <? if(!empty($arResult['ERRORS'])):?>
                        <div class="error">
                            <p><?=GetMessage("POPUPPRO_ERROR")?></p>
                            <?foreach($arResult['ERRORS'] as $nextError):?>
                                <p><?=GetMessage("POPUPPRO_ERROR_".$nextError)?></p>
                            <? endforeach;?>
                        </div>
                    <? endif; ?>
                    <form action="<?=$templateFolder?>/ajax.php" method="POST" onsubmit="sendForm4<?=$arParams['ID_POPUP']?>(this);return false;">
                        <input type="hidden" name="id_popup" value="<?=$arParams['ID_POPUP']?>" />
                        <input type="hidden" name="template_name" value="<?=$templateName?>" />
                        <?=bitrix_sessid_post()?>


                        <div class="field-list">

                            <? if($arResult['NAME_SHOW'] !== "N"): ?>
                                <div class="field-group <?=$arResult['NAME_SHOW'];?>">
                                    <input
                                            type="text"
                                            value="<?=$arResult['NAME']?>"
                                            name="NAME"
                                            placeholder="<?=$arResult['NAME_PLACEHOLDER']?>"
                                        <?=($arResult['NAME_REQUIRED'] === "Y") ? "required" : "";?>
                                    >
                                </div>
                            <? endif; ?>

                            <? if($arResult['PHONE_SHOW'] !== "N"): ?>
                                <div class="field-group <?=$arResult['PHONE_SHOW'];?>">
                                    <input
                                            type="text"
                                            value="<?=$arResult['PHONE']?>"
                                            name="PHONE"
                                            placeholder="<?=$arResult['PHONE_PLACEHOLDER']?>"
                                        <?=($arResult['PHONE_REQUIRED'] === "Y") ? "required" : "";?>
                                    >
                                </div>
                            <? endif; ?>

                            <? if($arResult['EMAIL_SHOW'] !== "N"): ?>
                                <div class="field-group <?=$arResult['EMAIL_SHOW'];?>">
                                    <input
                                            type="email"
                                            value="<?=$arResult['EMAIL']?>"
                                            name="EMAIL"
                                            placeholder="<?=$arResult['EMAIL_PLACEHOLDER']?>"
                                        <?=($arResult['EMAIL_REQUIRED'] === "Y") ? "required" : "";?>
                                    >
                                </div>
                            <? endif; ?>

                            <? if($arResult['DESCRIPTION_SHOW'] !== "N"): ?>
                                <div class="field-group <?=$arResult['DESCRIPTION_SHOW'];?>">
                                    <textarea name="DESCRIPTION" placeholder="<?=$arResult['DESCRIPTION_PLACEHOLDER']?>" <?=($arResult['DESCRIPTION_REQUIRED'] === "Y") ? "required" : "";?>><?=$arResult['DESCRIPTION']?></textarea>
                                </div>
                            <? endif; ?>

                            <? if($arResult['USE_CONSENT_SHOW'] !== "N" && count($arResult['AGREEMENTS']) > 0) :?>
                                <div class="field-group consent <?=$arResult['USE_CONSENT_SHOW'];?>">
                                    <input type="checkbox" name="use_consent" value="Y" checked="checked" required />
                                    <a href="/bitrix/tools/skyweb24_agreement.php?ID=<?=$arResult['CONSENT_ID']?>" target="_blank"><?=$arResult['CONSENT_LIST']?></a>
                                </div>
                            <? endif; ?>

                            <div class="field-group">
                                <label class="submit sw24-button-animate">
                                    <input type="submit" value="<?=$arResult['BUTTON_TEXT']?>">
                                </label>
                            </div>


                            <?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
                                <div align="center"><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
                            <?}?>
                            <? if($arResult['INTEG_CRM_ACTIVE'] == "Y") : ?>
                                <? if( $arResult["INTEG_CRM_SERVER"] != "0" ): ?>
                                    <input type="hidden" name="CRM_SERVER_ID" value="<?=$arResult['INTEG_CRM_SERVER'];?>">
                                    <input type="hidden" name="SOURCE_DESCRIPTION" value="<?=$_SERVER['HTTP_HOST']; ?>">
                                <? endif; ?>
                            <? endif; ?>
                            <div class="clear"></div>
                        </div>
                    </form>
                </div>
        <? endif; ?>

        <? if(!empty($arResult['SUCCESS']) && $arResult['SUCCESS']=='Y'): ?>
            <? if(isset($arResult['WINDOW_SUCCESS']) AND !empty($arResult['WINDOW_SUCCESS'])) :?>
                <div class="row_content">
                    <div class="title"><?=$arResult['WINDOW_SUCCESS']['WS_TITLE']["VALUE"];?></div>
                    <div class="subtitle"><?=$arResult['WINDOW_SUCCESS']['WS_DESCRIPTION']["VALUE"];?></div>
                </div>

            <? else : ?>
                <?=GetMessage("POPUPPRO_THANKS")?>
            <? endif; ?>

        <? endif; ?>

    <? else : ?>
        <div class="row_content">
            <div class="title"><?=$arResult['WS_TITLE'];?></div>
            <div class="subtitle"><?=$arResult['WS_DESCRIPTION'];?></div>
        </div>
    <? endif; ?>

    <script>

        if(typeof(buttonWindowPopup) != "undefined" && buttonWindowPopup.popupid == ""){

            buttonWindowPopup.active  = "<?=$arResult['BWP_ACTIVE']?>";
            buttonWindowPopup.popupid = "<?=$arParams['ID_POPUP']?>";

            if(buttonWindowPopup.active == "Y"){
                buttonWindowPopup.create({
                    position: {
                        left:   '<?=$arResult['BWP_POSITION_LEFT'];?>',
                        top:    '<?=$arResult['BWP_POSITION_TOP']; ?>',
                        bottom: '<?=$arResult['BWP_POSITION_BOTTOM']; ?>',
                        right:  '<?=$arResult['BWP_POSITION_RIGHT']; ?>',
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


        var buttonAnimation     = '<?=$arResult['BUTTON_ANIMATION'];?>';
        var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
        var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


        skyweb24_windowAnimation.show(showWindowAnimation);
        skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);


        function sendForm4<?=$arParams['ID_POPUP']?>(f){
            var sendO={},
                cInputs=f.querySelectorAll("input, textarea");

            for(var i=0; i<cInputs.length; i++){
                sendO[cInputs[i].name]=cInputs[i].value;
            }
            BX.ajax({
                url: f.action,
                data:sendO,
                method: 'POST',
                dataType: 'html',
                scriptsRunFirst:false,
                timeout:300,
                onsuccess: function(data){
                    BX("skyweb24_contact_type5").outerHTML = data;
                    skyweb24positionBanner(skyweb24Popups.currentPopup);
                    <?=$arResult['BUTTON_METRIC']?>

                    if(buttonWindowPopup.element)
                        buttonWindowPopup.destroy();
                },
                onfailure: function(data){
                    console.log(data);
                }
            });
        }
    </script>
</div>
