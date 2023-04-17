<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



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

<?if($arResult['TIMER']=='Y'){
		$APPLICATION->IncludeComponent('skyweb24:popup.pro.timer','',array(
			'TITLE'=>$arResult['TIMER_TEXT'],
			'TIME'=>$arResult['TIMER_DATE'],
			'LEFT'=>$arResult['TIMER_LEFT'],
			'RIGHT'=>$arResult['TIMER_RIGHT'],
			'TOP'=>$arResult['TIMER_TOP'],
			'BOTTOM'=>$arResult['TIMER_BOTTOM'],
		),$component);
	}?>
<?function inducementWord($number, $wordArr){$cases = array (2, 0, 1, 1, 1, 2);return $wordArr[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];}?>
<div id="skyweb24_coupon_type6" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>

    <div class="wndw__pic">
        <img src="<?=$arResult['IMG_1_SRC']?>" alt="">
    </div>
    <div class="wndw__cont">
        <div class="wndw__cont-title">
            <?=$arResult['TITLE']?>
        </div>
        <div class="wndw__cont-subtitle">
            <?=$arResult['SUBTITLE']?>
        </div>
        <label class="<?=$arResult['EMAIL_SHOW']?> wndw__cont-input">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-envelope-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
            </svg>
            <input type="email" value="<?=$arResult['EMAIL']?>" name="EMAIL" placeholder="<?=$arResult['EMAIL_PLACEHOLDER']?>"/>
            <span class="error"><?=GetMessage("POPUPPRO_WRONG")?></span>
            <span class="not_new"><?=$arResult['EMAIL_NOT_NEW_TEXT']?></span>
        </label>
        <div class="wndw__cont-btn">
            <button class="sw24-button-animate"><?=$arResult['BUTTON_TEXT']?></button>
            <input class="goodCoupon" type="text" disabled>
            <?=bitrix_sessid_post()?>
        </div>
        <a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a>
    </div>

</div>

<? if($arParams['MODE'] !== "TEMPLATE") :?>
    <script>

        if(BX)
        {
            BX.ready(function () {

                let popupContainer = BX("skyweb24_coupon_type6");

                function validateEmailCoupon(value) {
                    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                    return emailPattern.test(value);
                }

                BX.bindDelegate(popupContainer,
                    "click", {tagName: "button"},
                    function (e) {

                        popupContainer.querySelector(".error").style.display = "none";
                        popupContainer.querySelector(".not_new").style.display = "none";

                        let _self = this;
                        let inputEmail = popupContainer.querySelector("[name=EMAIL]");
                        let inputSessid = popupContainer.querySelector("[name=sessid]");

                        if(validateEmailCoupon(inputEmail.value))
                        {

                            let url =  "<?=$templateFolder?>/ajax.php?id=<?=$arResult['RULE_ID']?>&avaliable=<?=$arResult['TIMING']?>&idPopup=<?=$arParams['ID_POPUP']?>";
                            url += "&sessid=" + inputSessid.value;
                            url += "&email=" + inputEmail.value;
                            url += "&addtotable=<?=$arResult["EMAIL_ADD2BASE"]?>";
                            url += "&unique=<?=$arResult["EMAIL_NOT_NEW"]?>";

                            BX.ajax({
                                url: url,
                                method: 'POST',
                                onsuccess: function (data) {
                                    if(data === "not_unique")
                                    {
                                        popupContainer.querySelector(".not_new").style.display = "block";
                                    }
                                    else
                                    {
                                        inputEmail.style.display = "none";
                                        _self.style.display = "none";
                                        popupContainer.querySelector(".goodCoupon").style.display = "block";
                                        popupContainer.querySelector(".goodCoupon").value = data;
                                        popupContainer.querySelector(".goodCoupon").removeAttribute("disabled");

                                        BX.bind(popupContainer.querySelector(".goodCoupon"), "click", function () {
                                            let range = document.createRange();
                                            range.selectNode(this);
                                            window.getSelection().addRange(range);
                                            let successful = document.execCommand('copy');
                                            window.getSelection().removeRange(range);
                                        });

                                        skyweb24Popups.Statistic.send("action");

                                        <?=$arResult['BUTTON_METRIC'];?>
                                        <?=PHP_EOL;?>
                                    }
                                },
                                onfailure: function (data) {
                                    console.log(data);
                                }
                            });
                        }
                        else
                        {
                            popupContainer.querySelector(".error").style.display = "block"
                        }
                    }
                );
            });
        }

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


    </script>
<? endif; ?>
