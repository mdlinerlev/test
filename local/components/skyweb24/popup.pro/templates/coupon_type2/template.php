<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<? if(!is_numeric(strpos($APPLICATION -> GetCurPage(), "admin"))) : ?>
<style>
    :root {
        <? foreach ($arResult['theme_color']['COLORS'] as $key=> $value): ?> --<?= $key; ?>: <?= $value['VALUE']; ?>;
        <? endforeach;
        ?>
    }
</style>
<? if(is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], "Trident"))) : ?>
<script>
    cssVars();
</script>
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
<div id="skyweb24_coupon_type2" style="<?if(!empty($arResult[" GOOGLE_FONT"])){?>font-family:<?= $arResult["GOOGLE_FONT"] ?>
    <?}?>">
    <div class="bg">
        <div class="row_image"><img src="<?= $arResult["IMG_1_SRC"]?>"></div>
        <div class="row_content">
            <div class="title"><?= $arResult['TITLE'] ?></div>
            <div class="subtitle"><?= $arResult['SUBTITLE'] ?></div>
            <?
            if($arResult['EMAIL_SHOW']=='Y'){
                if($arResult['EMAIL_REQUIRED']=='N' || $arResult['EMAIL_REQUIRED']=='Y'){
                    $arResult['EMAIL_REQUIRED']=($arResult['EMAIL_REQUIRED']=='N')?'':'required';
                }
            }        
            if($arResult['EMAIL_SHOW']=='N' || $arResult['EMAIL_SHOW']=='Y'){
                $arResult['EMAIL_SHOW']=($arResult['EMAIL_SHOW']=='N')?'notshow':'';
                $param_consent[]=$arResult['EMAIL_TITLE'];
            }
            ?>
            <div class="<?=$arResult['EMAIL_SHOW']?> input">
                <input type="email" value="<?= $arResult['EMAIL'] ?>" name="EMAIL" placeholder="<?= $arResult['EMAIL_PLACEHOLDER'] ?>"/>
                <button class="sw24-button-animate"><?= $arResult['BUTTON_TEXT'] ?></button>
            </div>
            <div class="coupon_block">      
                <input class="goodCoupon" type="text" disabled>
                <?= bitrix_sessid_post() ?>
                <span><?= GetMessage('POPUPPRO_COPYED') ?></span>
            </div>
            <div class="<?= $arResult['EMAIL_SHOW'] ?> message">
                <span class="error"><?= GetMessage("POPUPPRO_WRONG") ?></span>
                <span class="not_new"><?= $arResult['EMAIL_NOT_NEW_TEXT'] ?></span>
            </div>
            <div style="text-align:center; margin-top: 30px;"><a href="javascript:void(0);" class="sw24TextCloseButton"><?= $arResult['CLOSE_TEXTAREA'] ?></a></div>
        </div>
    </div>
</div>


<? if($arParams['MODE'] !== "TEMPLATE") :?>
    <script>
        
        if(BX)
        {
            BX.ready(function () {

                let popupContainer = BX("skyweb24_coupon_type2");

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