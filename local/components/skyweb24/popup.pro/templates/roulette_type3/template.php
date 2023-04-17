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
<div id="skyweb24_roulette3" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
    <div class="sw24-roulette">
        <div class="sw24-roulette__unit contain_roulette">
            <div class="rotate_shadow">
                <div class="rotate_block">
                    <section class="container">
                    </section>
                    <div class="text">
                    </div>
                </div>
            </div>
            <div class="arrow">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                    <path style="fill:#FD003A;" d="M256,0C156.698,0,76,80.7,76,180c0,33.6,9.302,66.301,27.001,94.501l140.797,230.414
                    c2.402,3.9,6.002,6.301,10.203,6.901c5.698,0.899,12.001-1.5,15.3-7.2l141.2-232.516C427.299,244.501,436,212.401,436,180
                    C436,80.7,355.302,0,256,0z M256,270c-50.398,0-90-40.8-90-90c0-49.501,40.499-90,90-90s90,40.499,90,90
                    C346,228.9,306.999,270,256,270z"/>
                    <path style="fill:#E50027;" d="M256,0v90c49.501,0,90,40.499,90,90c0,48.9-39.001,90-90,90v241.991
                    c5.119,0.119,10.383-2.335,13.3-7.375L410.5,272.1c16.799-27.599,25.5-59.699,25.5-92.1C436,80.7,355.302,0,256,0z"/>
                    <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                </svg>
            </div>
        </div>
        <div class="sw24-roulette__unit _info">
            <?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
            <? $deg = (!empty($arResult['ELEMENTS_COUNT'])) ? 100 / $arResult['ELEMENTS_COUNT'] : 0; ?>
            <div class="sw24-roulette__title"><?=$arResult['TITLE']?></div>
            <div class="sw24-roulette__subtitle"><?=$arResult['SUBTITLE']?></div>
            <?=bitrix_sessid_post()?>

            <p class="result_roll" style="display:none;"><?=$arResult['RESULT_TEXT']?></p>
            <p class="result_roll_nothing" style="display:none;"><?=$arResult['NOTHING_TEXT']?></p>
            <div class="sw24-roulette-error-result"></div>
            <div class="btn-action">
                <?php if ($arResult['PHONE_FIELD_ACTIVE'] == "Y"): ?>
                    <label class="email input">
                        <input placeholder="<?=$arResult['PHONE_PLACEHOLDER']?>" type="number" name="phone" class="sw24-roulette-input-phone">
                    </label>
                <?php endif; ?>
                <?php if ($arResult['EMAIL_SHOW'] == "Y"): ?>
                    <label class="email input">
                        <input placeholder="<?=$arResult['EMAIL_PLACEHOLDER']?>" type="email" name="email" class="sw24-roulette-input-email">
                        <p class="result_roll_fail" style="display:none;"><?=GetMessage('skyweb24.roulette_wrong')?><span></span></p>
                        <p class="result_roll_not_unique" style="display:none;"><?=$arResult['EMAIL_NOT_NEW_TEXT']?><span></span></p>
                    </label>
                <?php endif; ?>
                <button class="roll_roulette sw24-button-animate"><?=$arResult['BUTTON_TEXT']?></button>
            </div>



            <?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
                <div align="center"><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
            <?}?>

        </div>
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


        <? if(!empty($arResult['ELEMENTS'])) : ?>

        var dataset = [
            <? foreach($arResult['ELEMENTS'] as $key => $element):?>
            {
                value: <?=$deg?>,
                color: '<?=$element['color']?>',
                text: '<?=$element['text']?>',
                rule: '<?=$element['rule']?>',
                chance: '<?=$element['chance']?>'
            },
            <? endforeach; ?>
        ];

        <? else : ?>

        var dataset = [];

        <? endif; ?>


        var maxValue = 25;



        var addSector = function (data, startAngle, collapse) {
            var sectorDeg = 3.6 * data.value;
            var skewDeg = 90 + sectorDeg;
            var rotateDeg = startAngle;
            if (collapse) {
                skewDeg++;
            }
            var container = document.querySelector('#skyweb24_roulette3 .container');
            var sector = document.createElement('div');
            sector.style.background = data.color;
            sector.className = 'sector';
            sector.style.transform = 'rotate(' + rotateDeg + 'deg) skewY(' + skewDeg + 'deg)';
            container.appendChild(sector);
            var container_text = document.querySelector('#skyweb24_roulette3 .text');
            var text = document.createElement('div');
            text.style.color = 'white';
            text.dataset.rule = data.rule;
            text.innerHTML = data.text;
            text.style.transform = 'rotate(' + ((rotateDeg - (90 - sectorDeg / 2))) + 'deg) translate(0px,-50%)';
            container_text.appendChild(text);
            return startAngle + sectorDeg;
        };

        function paintRoulett() {
            dataset.reduce(function (prev, curr) {
                return (function addPart(data, angle) {
                    if (data.value <= maxValue) {
                        return addSector(data, angle, false);
                    }
                    return addPart({
                        value: data.value - maxValue,
                        color: data.color
                    }, addSector({
                        value: maxValue,
                        color: data.color,
                    }, angle, true));
                })(curr, prev);
            }, 0);
            return dataset.length;
        }

        if (window != window.top) {
            (function () {
                paintRoulett()
            })();
        }



        async function roll_roulette_func(countSector) {
            let strategy;

            let nodeInputPhone = BX('skyweb24_roulette3').querySelector(`.sw24-roulette-input-phone`);
            let nodeInputEmail = BX('skyweb24_roulette3').querySelector(`.sw24-roulette-input-email`);

            if (nodeInputPhone) {
                strategy = new StrategyPhone({
                    phone: nodeInputPhone.value,
                    popupId: skyweb24Popups.Popup.getCurPopupID(),
                });
            }

            if (nodeInputEmail) {
                strategy = new StrategyEmail({
                    email: nodeInputEmail.value,
                    popupId: skyweb24Popups.Popup.getCurPopupID()
                });
            }

            if (typeof strategy === 'undefined') {
                return false;
            }

            await (new RouletteGame(strategy))
                .setCountSector(countSector)
                .start();
        }


        class StrategyAbstract {
            constructor(params) {
                this.params = params;
            }

            /**
             * Abstract валидация отправляемых данных
             */
            async isValidData() {
            }

            /**
             * Abstract отправка купона
             */
            async sendCoupon(coupon, text) {
            }

            /**
             * Abstract отправка контакта
             */
            async sendContact() {
            }
        }

        class StrategyPhone extends StrategyAbstract {
            async isValidData() {
                return await this.requestIsValidPhone()
            }

            async requestIsValidPhone() {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.isValidPhone", {
                        data: {
                            phone: this.params.phone,
                            popupId: this.params.popupId
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }

            async sendCoupon(coupon, text) {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.sendCouponToPhone", {
                        data: {
                            phone: this.params.phone,
                            popupId: this.params.popupId,
                            text: text,
                            coupon: coupon,
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }

            async sendContact() {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.addToContactPhone", {
                        data: {
                            phone: this.params.phone,
                            popupId: this.params.popupId
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }
        }

        class StrategyEmail extends StrategyAbstract {
            async isValidData() {
                return await this.requestIsValidEmail()
            }

            async requestIsValidEmail() {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.isValidEmail", {
                        data: {
                            email: this.params.email,
                            popupId: this.params.popupId
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }

            async sendCoupon(coupon, text) {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.sendCouponToEmail", {
                        data: {
                            email: this.params.email,
                            popupId: this.params.popupId,
                            text: text,
                            coupon: coupon
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }

            async sendContact() {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.addToContactEmail", {
                        data: {
                            email: this.params.email,
                            popupId: this.params.popupId
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }
        }

        class RouletteGame {
            constructor(strategy) {
                this.strategy = strategy;
            }

            setCountSector(countSector) {
                this.countSector = countSector;
                return this;
            }

            async start() {
                this.removeErrorMessage();

                try {
                    if (await this.strategy.isValidData()) {
                        let sector = await this.startSpinWheel(this.countSector);
                        let award = this.getAwardSectorWheel(sector);

                        this.removeNodeButtonSend();
                        this.renderAward(award);

                        if (this.isAwardWin(award.rule)) {
                            await this.strategy.sendCoupon(
                                await this.getCoupon(award.rule, award.text, sector),
                                award.text
                            )
                        }

                        await this.strategy.sendContact()

                    }
                } catch (e) {
                    this.renderErrorMessage(e);
                }


                // Отправка статистики
                skyweb24Popups.Statistic.send("action");

                // Внедрение метрики
                <?=$arResult['BUTTON_METRIC']?>
            }

            async startSpinWheel(countSector) {
                return new Promise(resolve => {
                    var percent = 100 / countSector;
                    var deg = 360 / 100;
                    var center = 90 - (percent * 3.6 / 2);

                    var rand = "<?=$arResult['SECTOR']?>";
                    var res = '';
                    var sector = rand;
                    rand--;

                    var res = -(deg * percent * rand - center);

                    document.querySelector('#skyweb24_roulette3 div.rotate_block').style.transform = 'rotate(' + (res - 3600) + 'deg)';

                    setTimeout(() => resolve(sector), 7000);
                })
            }

            getAwardSectorWheel(numberSector) {
                var text = document.querySelector('#skyweb24_roulette3 div.rotate_block>.text>div:nth-child(' + numberSector + ')').innerHTML;
                var rule = document.querySelector('#skyweb24_roulette3 div.rotate_block>.text>div:nth-child(' + numberSector + ')').dataset.rule;
                var not = document.querySelectorAll('#skyweb24_roulette3 div.rotate_block>.container>div:not(:nth-child(' + numberSector + '))');

                for (var i = 0; i < not.length; i++) {
                    not[i].className += ' negative';
                }

                if (rule != 'nothing') {
                } else {
                    BX('skyweb24_roulette3').querySelector('label.input').remove();
                    BX('skyweb24_roulette3').querySelector('button.roll_roulette').remove();
                    BX('skyweb24_roulette3').querySelector('p.result_roll').remove();
                    BX('skyweb24_roulette3').querySelector('p.result_roll_nothing').style.display = '';
                }

                return {
                    text: text,
                    rule: rule,
                }
            }

            isAwardWin(rule) {
                return rule != "nothing";
            }

            async getCoupon(id, text, sector) {
                return new Promise((resolve, reject) => {
                    BX.ajax.runAction("skyweb24:popuppro.api.Component.ControllerRoulette.getCoupon", {
                        data: {
                            id: id,
                            avaliable: '<?=$arResult['TIMING']?>',
                            idPopup: '<?=$arParams['ID_POPUP']?>',
                            sector: sector
                        }
                    })
                        .then(res => resolve(res.data))
                        .catch(res => reject(res.errors[0].message))
                })
            }

            renderAward(award) {
                if (this.isAwardWin(award.rule)) {
                    BX('skyweb24_roulette3').querySelector(`.result_roll`).style.display = "block";
                } else {
                    BX('skyweb24_roulette3').querySelector(`.result_roll_nothing`).style.display = "block";
                }
            }

            renderErrorMessage(message) {
                BX('skyweb24_roulette3').querySelector(`.sw24-roulette-error-result`).style.display = "block";
                BX('skyweb24_roulette3').querySelector(`.sw24-roulette-error-result`).innerHTML = message;
            }

            removeErrorMessage() {
                BX('skyweb24_roulette3').querySelector(`.sw24-roulette-error-result`).innerHTML = "";
                BX('skyweb24_roulette3').querySelector(`.sw24-roulette-error-result`).style.display = "none";
            }

            removeNodeButtonSend() {
                BX('skyweb24_roulette3').querySelector('.btn-action').remove()

                let textCloseButton = BX('skyweb24_roulette3').querySelector('.sw24TextCloseButton');
                if (textCloseButton) {
                    textCloseButton.remove();
                }
            }
        }
    </script>

</div>

<script>
    var buttonAnimation     = '<?=$arResult['BUTTON_ANIMATION'];?>';
    var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


    skyweb24_windowAnimation.show(showWindowAnimation);
    skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);


</script>