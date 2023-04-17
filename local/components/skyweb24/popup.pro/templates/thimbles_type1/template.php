<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if(!is_numeric(strpos($APPLICATION->GetCurPage(), "admin"))): ?>
    <style>
        :root {
        <? foreach ($arResult['theme_color']['COLORS'] as $key => $value): ?><?="--" . strtolower($key) . ":" . $value['VALUE'] . ";";?><? endforeach; ?>
        }
    </style>
    <? if(is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], "Trident"))) : ?>
        <script>cssVars();</script>
    <? endif; ?>
<? endif; ?>

<?

if($arResult['TIMER'] == 'Y'){

    $APPLICATION->IncludeComponent('skyweb24:popup.pro.timer','',array(
        'TITLE'=>$arResult['TIMER_TEXT'],
        'TIME'=>$arResult['TIMER_DATE'],
        'LEFT'=>$arResult['TIMER_LEFT'],
        'RIGHT'=>$arResult['TIMER_RIGHT'],
        'TOP'=>$arResult['TIMER_TOP'],
        'BOTTOM'=>$arResult['TIMER_BOTTOM'],
    ),$component);
}?>

<div class="thimbles_container" style="<?=(!empty($arResult['GOOGLE_FONT'])) ? "font-family:" . $arResult['GOOGLE_FONT'] : "" ?>">
    <?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
    <div class="thimbles_header">
        <div class="th_item _title"><b><?=$arResult["TITLE"]?></b></div>
        <div class="th_item _description"><b><?=$arResult["SUBTITLE"]?></b></div>
    </div>
    <div class="thimbles_body"></div>
    <div class="thimbles_footer">
        <div class="tf_item _inputs">
            <label class="email">
                <input class="th_item_input" type="text" placeholder="<?=$arResult['EMAIL_PLACEHOLDER'];?>">
            </label>
            <input type="button" value="<?=$arResult['BUTTON_TEXT'];?>" class="play sw24-button-animate">
            <?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))):?>
                <div align="center"><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
            <? endif; ?>
        </div>
        <div class="tf_item result">
            <div class="text _disabled _error"><?=$arResult["NOTHING_TEXT"]?></div>
            <div class="text _disabled _success"><?=$arResult["RESULT_TEXT"]?></div>
            <div class="text _disabled _message"></div>
        </div>
    </div>
</div>
<template class="tb_item_template">
    <div class="tb_item">
        <div class="tb_item_img">
            <img src="" alt="">
        </div>
        <div class="tb_item_title"></div>
    </div>
</template>


<script>

    BX.namespace("skyweb24.popuppro.thimbles");
    BX.skyweb24.popuppro.thimbles = (function () {
        let thimbles = function (data) {
            this.init(data);
            this._onEvent();
        };
        thimbles.prototype = {
            init: function (data) {

                this.items = data.items;
                this.popupId = data.popupId;

                this.template = document.querySelector(".tb_item_template");
                if(!this.template) return false;
                this.template = this.template.content;

                this.container = document.querySelector(".thimbles_container");
                this.body = document.querySelector(".thimbles_body");
                this.body.innerHTML = "";
                this.errorMessage = "";


                for (let probability of this.items) {
                    let item = this.template.cloneNode(true);
                    if(probability.prizeId == 1) {
                        item.querySelector("img").src = "<?=$arResult['IMG_DEFEAT'];?>";
                    }
                    else {
                        item.querySelector("img").src = "<?=$arResult['IMG_WIN'];?>";
                    }
                    item.querySelector(".tb_item_title").innerHTML = probability.text;
                    this.body.append(item);
                }

            },

            _resultRemove() {
                this.container.querySelector(".result .text._error").classList.add("_disabled");
                this.container.querySelector(".result .text._success").classList.add("_disabled");
                this.container.querySelector(".result .text._message").classList.add("_disabled");
                this.container.querySelector(".result .text._message").innerHTML = "";
            },

            _result: function (data) {

                this._resultRemove();
                if (data && data.type && !data.error) {
                    this.container.querySelector(".result .text._success").classList.remove("_disabled");

                    if(data.type == "coupon") {
                        this.container.querySelector(".result .text._message").innerHTML = data.value;
                        this.container.querySelector(".result .text._message").classList.remove("_disabled");
                    }
                    this.imgResult(data.target, true);
                }
                else {
                    if(data.error) {
                        this.container.querySelector(".result .text._message").innerHTML = data.value;
                        this.container.querySelector(".result .text._message").classList.remove("_disabled");
                    }
                    else {
                        this.container.querySelector(".result .text._error").classList.remove("_disabled");
                        this.imgResult(data.target, false);
                    }
                }

            },

            imgResult: function(target, win)
            {
                if(!target) return false;
                if(win){
                    target.querySelector("img").src = "<?=$arResult['IMG_WIN'];?>";
                }
                else {
                    target.querySelector("img").src = "<?=$arResult['IMG_DEFEAT'];?>";
                }
            },

            isEmailValid: async function () {
                let _self = this;
                return new Promise(function (resolve, reject) {

                    let email = _self.container.querySelector(".email input");
                    if (email.value) {
                        _self._request(
                            {
                                action: "check_email",
                                popupId: _self.popupId,
                                email: email.value
                            }
                        ).onload = function (result) {

                            let data = BX.parseJSON(result.target.response);

                            if(!data.status) {
                                _self.errorMessage = data.error;
                            }

                            _self.email = email.value;
                            resolve(data.status);

                        };
                    }
                });
            },

            _play: async function () {

                if(!await this.isEmailValid())
                {
                    this._result({
                        error: "error",
                        value: this.errorMessage,
                    });
                    return false;
                }

                this._resultRemove();

                this.container.querySelector(".tf_item._inputs")
                    .classList.add("_disabled");

                let items = this.body.querySelectorAll(".tb_item");
                let indexItems = [];

                if (items) {
                    for (let i = 0; i < items.length; i++) {
                        items[i].classList.add("_hide");
                        indexItems.push(i);
                    }

                    setTimeout(function () {
                        for (let i = 0; i < items.length; i++) {
                            items[i].querySelector("img").src = "<?=$arResult['IMG_DEFAULT'];?>";
                        }
                    }, 200);

                    setTimeout(function () {
                        let time = 0;

                        while (indexItems.length != 0) {
                            let index = Math.floor(Math.random() * ((indexItems.length - 1) - 0) + 0);
                            items[indexItems[index]].style.transitionDelay = time + "s";
                            items[indexItems[index]].querySelector(".tb_item_title").classList.add("_disabled");
                            items[indexItems[index]].classList.remove("_hide");
                            items[indexItems[index]].classList.add("_action");
                            indexItems.splice(index, 1);
                            time += 0.2;
                        }

                        let itemRandom = items[Math.floor(Math.random() * items.length)];
                        itemRandom.classList.add("_animation");

                    }, 500);
                }

                skyweb24Popups.Statistic.send("action");

            },

            _action: function (e) {

                let target = BX.proxy_context;
                let _self = this;

                target.classList.add("_active");
                for (let item of _self.body.querySelectorAll(".tb_item")) {
                    item.classList.remove("_action");
                    item.classList.remove("_animation");
                    if (!BX.hasClass(item, "_active")) {
                        item.classList.add("_hide", "_fast");
                    }
                }

                _self._request({
                    action: "reward",
                    email: _self.email,
                    popupId: _self.popupId
                }).onload = function () {

                    let data = BX.parseJSON(this.response);
                    // console.log(data);
                    if (!data.error) {
                        _self._result({
                            type: data.type,
                            value: data.value,
                            target: target
                        });
                    }
                }
            },

            _request: function (fields = {}) {
                return BX.ajax({
                    url: "<?=$templateFolder?>/ajax.php",
                    method: "POST",
                    data: fields
                })
            },

            _onEvent: function () {

                BX.bindDelegate(
                    this.container,
                    "click", {className: "play"},
                    BX.proxy(this._play, this)
                );

                BX.bindDelegate(
                    this.body,
                    "click", {className: "_action"},
                    BX.proxy(this._action, this)
                );
            }
        };
        return new thimbles({
            items: JSON.parse('<?=$arResult["LIST_WINS"];?>'),
            popupId: '<?=$arResult['popupId'];?>'
        });
    })();


    var buttonAnimation = '<?=$arResult['BUTTON_ANIMATION'];?>';
    var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


    skyweb24_windowAnimation.show(showWindowAnimation);
    skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);

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
</script>


