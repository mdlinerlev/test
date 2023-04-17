<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? if(!is_numeric(strpos($APPLICATION->GetCurPage(), "admin"))): ?>
    <style>
.game{width:500px;height:150px;margin:0 auto;padding:15px;border-radius:10px;background-color:aliceblue }
            .prize{padding:15px 30px;border-radius:10px;background-color:#e3e3e3;text-align:center }
            #text{font-size:22px;font-weight:700 } form{padding:10px;text-align:center }
            button{cursor:pointer;width:200px;height:60px;border:none;border-radius:30px;font-size:16px;font-weight:700;background-color:teal;color:white }
        :root {
        <? foreach ($arResult['theme_color']['COLORS'] as $key => $value): ?><?="--" . strtolower($key) . ":" . $value['VALUE'] . ";";?><? endforeach; ?>
        }
    </style>
    <? if(is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], "Trident"))) : ?>
        <script>cssVars();</script>
    <? endif; ?>
<? endif; ?>


<? if(!empty($arResult['GOOGLE_FONT'])) { ?>
    <link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet">
<? } ?>

<? if($arParams['MODE'] != "WS_TEMPLATE") : ?>


    <div id="skyweb24_contact_type1"
         style="<? if(!empty($arResult['GOOGLE_FONT'])) { ?>font-family:<?=$arResult['GOOGLE_FONT']?><? } ?>">

        <div class="bgColorBlock">
            <? if(!empty($arResult['ERRORS'])) { ?>
                <div class="error"><p><?=GetMessage("POPUPPRO_ERROR")?></p>
                    <p><? foreach($arResult['ERRORS'] as $nextError) { ?>
                            <?=GetMessage("POPUPPRO_ERROR_" . $nextError)?>
                        <? } ?></p>
                </div>
            <? } ?>
            <? if(empty($arResult['SUCCESS'])) {
                $param_consent = []; ?>

                <? if($arResult['TIMER'] == 'Y') {
                    $APPLICATION->IncludeComponent('skyweb24:popup.pro.timer', '', [
                        'TITLE'  => $arResult['TIMER_TEXT'],
                        'TIME'   => $arResult['TIMER_DATE'],
                        'LEFT'   => $arResult['TIMER_LEFT'],
                        'RIGHT'  => $arResult['TIMER_RIGHT'],
                        'TOP'    => $arResult['TIMER_TOP'],
                        'BOTTOM' => $arResult['TIMER_BOTTOM'],
                    ], $component);
                } ?>
                <form action="<?=$templateFolder?>/ajax.php" method="POST" onsubmit="sendForm1(this);return false;">
                    <input type="hidden" name="id_popup" value="<?=$arParams['ID_POPUP']?>"/>
                    <input type="hidden" name="template_name" value="<?=$templateName?>"/>
                    <?=bitrix_sessid_post()?>
                    <img id="img_going_s1" src="<?=$arResult['IMG_1_SRC']?>">
                    <div class="ct_title"><?=$arResult['TITLE']?></div>
                    <div class="subtitle"><?=$arResult['SUBTITLE']?><script>


    function call() {

        var msg = $('#form').serialize();

        $.ajax({
            type: 'POST',
            url: '/func.php',
            data: msg,
            success: function(data) {

                var obj = jQuery.parseJSON(data);

                if (obj.success == 'success') {
                    $('.prize').html('<span id="text">Твой выигрыш: ' + obj.prize + '</span>');
                } else {
                    $('.prize').html('<span id="text">Ошибка</span>');
                }

            }
        });

    }


</script>

</div>
        <div class="game">
            <div class="prize"></div>
            <form method="POST" id="form" action="javascript:void(null);" onsubmit="call()">
                <input type="hidden" name="type" value="lot">
                <button>Сюда надо тыкать</button>
            </form>
        </div>


                    <div class="fieldset">
                        <?
                        if($arResult['NAME_SHOW'] == 'Y') {
                            if($arResult['NAME_REQUIRED'] == 'N' || $arResult['NAME_REQUIRED'] == 'Y') {
                                $arResult['NAME_REQUIRED'] = ($arResult['NAME_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if($arResult['NAME_SHOW'] == 'N' || $arResult['NAME_SHOW'] == 'Y') {
                            $arResult['NAME_SHOW'] = ($arResult['NAME_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['NAME_TITLE'];
                        }

                        ?>
                        <label class="<?=$arResult['NAME_SHOW']?> <?=$arResult['NAME_REQUIRED']?> input">
                            <input <?=$arResult['NAME_REQUIRED']?> name="NAME" type="text"
                                                                   value="<?=$arResult['NAME']?>"
                                                                   placeholder="<?=$arResult['NAME_PLACEHOLDER']?>"/>
                            <span><?=$arResult['NAME_TITLE']?><sup>*</sup></span>
                        </label>
                        <?
                        if($arResult['PHONE_SHOW'] == 'Y') {
                            if($arResult['PHONE_REQUIRED'] == 'N' || $arResult['PHONE_REQUIRED'] == 'Y') {
                                $arResult['PHONE_REQUIRED'] = ($arResult['PHONE_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if($arResult['PHONE_SHOW'] == 'N' || $arResult['PHONE_SHOW'] == 'Y') {
                            $arResult['PHONE_SHOW'] = ($arResult['PHONE_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['PHONE_TITLE'];
                        }
                        ?>
                        <label class="<?=$arResult['PHONE_SHOW']?> <?=$arResult['PHONE_REQUIRED']?> input">
                            <input <?=$arResult['PHONE_REQUIRED']?> type="text" value="<?=$arResult['PHONE']?>"
                                                                    name="PHONE"
                                                                    placeholder="<?=$arResult['PHONE_PLACEHOLDER']?>"/>
                            <span><?=$arResult['PHONE_TITLE']?><sup>*</sup></span>
                        </label>
                        <?
                        if($arResult['EMAIL_SHOW'] == 'Y') {
                            if($arResult['EMAIL_REQUIRED'] == 'N' || $arResult['EMAIL_REQUIRED'] == 'Y') {
                                $arResult['EMAIL_REQUIRED'] = ($arResult['EMAIL_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if($arResult['EMAIL_SHOW'] == 'N' || $arResult['EMAIL_SHOW'] == 'Y') {
                            $arResult['EMAIL_SHOW'] = ($arResult['EMAIL_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['EMAIL_TITLE'];
                        }
                        ?>
                        <label class="<?=$arResult['EMAIL_SHOW']?> <?=$arResult['EMAIL_REQUIRED']?> input">
                            <input <?=$arResult['EMAIL_REQUIRED']?> type="email" value="<?=$arResult['EMAIL']?>"
                                                                    name="EMAIL"
                                                                    placeholder="<?=$arResult['EMAIL_PLACEHOLDER']?>"/>
                            <span><?=$arResult['EMAIL_TITLE']?><sup>*</sup></span>
                        </label>
                    </div>
                    <div class="fieldset last">
                        <?
                        if($arResult['DESCRIPTION_SHOW'] == 'Y') {
                            if($arResult['DESCRIPTION_REQUIRED'] == 'N' || $arResult['DESCRIPTION_REQUIRED'] == 'Y') {
                                $arResult['DESCRIPTION_REQUIRED'] = ($arResult['DESCRIPTION_REQUIRED'] == 'N') ? '' : 'required';
                            }
                        }
                        if($arResult['DESCRIPTION_SHOW'] == 'N' || $arResult['DESCRIPTION_SHOW'] == 'Y') {
                            $arResult['DESCRIPTION_SHOW'] = ($arResult['DESCRIPTION_SHOW'] == 'N') ? 'notshow' : '';
                            $param_consent[] = $arResult['DESCRIPTION_TITLE'];
                            ?>

                        <? } ?>
                        <label class="<?=$arResult['DESCRIPTION_SHOW']?> <?=$arResult['DESCRIPTION_REQUIRED']?> textarea">
                            <textarea <?=$arResult['DESCRIPTION_REQUIRED']?> name="DESCRIPTION"
                                                                             placeholder="<?=$arResult['DESCRIPTION_PLACEHOLDER']?>"><?=$arResult['DESCRIPTION']?></textarea>
                            <span><?=$arResult['DESCRIPTION_TITLE']?><sup>*</sup></span>
                        </label>
                        <?
                        if($arResult['USE_CONSENT_SHOW'] == 'N' || $arResult['USE_CONSENT_SHOW'] == 'Y') {
                            $arResult['USE_CONSENT_SHOW'] = ($arResult['USE_CONSENT_SHOW'] == 'N') ? 'notshow' : '';
                        }
                        if($arResult['USE_CONSENT_SHOW'] != 'N' && count($arResult['AGREEMENTS']) > 0) {
                            ?>
                            <div class="<?=$arResult['USE_CONSENT_SHOW']?> consentBlock">
                                <input type="checkbox" name="use_consent" value="Y" checked="checked" required/> <a
                                        href="/bitrix/tools/skyweb24_agreement.php?ID=<?=$arResult['CONSENT_ID']?>"
                                        target="_blank"><?=$arResult['CONSENT_LIST']?></a>
                            </div>
                        <? } ?>
                        <label class="submit sw24-button-animate">
                            <button type="submit"><?=$arResult['BUTTON_TEXT']?></button>
                        </label>
                        <? if(($arResult['CLOSE_TEXTBOX'] == 'Y') && (!empty($arResult['CLOSE_TEXTAREA']))) { ?>
                            <div align="center"><a href="javascript:void(0);"
                                                   class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a>
                            </div>
                        <? } ?>
                        <? if($arResult['INTEG_CRM_ACTIVE'] == "Y") : ?>
                            <? if($arResult["INTEG_CRM_SERVER"] != "0"): ?>
                                <input type="hidden" name="CRM_SERVER_ID" value="<?=$arResult['INTEG_CRM_SERVER'];?>">
                                <input type="hidden" name="SOURCE_DESCRIPTION" value="<?=$_SERVER['HTTP_HOST'];?>">
                            <? endif; ?>
                        <? endif; ?>
                        <div class="clear"></div>
                    </div>
                </form>


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


                    function sendForm1(f) {
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
                                BX("skyweb24_contact_type1").outerHTML = data;
                                skyweb24positionBanner(skyweb24Popups.currentPopup);
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
            <? }
            if(!empty($arResult['SUCCESS']) && $arResult['SUCCESS'] == 'Y') { ?>
                <div class="success">
                    <? if(isset($arResult['WINDOW_SUCCESS']) AND !empty($arResult['WINDOW_SUCCESS'])) : ?>
                        <div class="subtitle"><?=$arResult['WINDOW_SUCCESS']['WS_TITLE']["VALUE"];?></div>
                        <div class="text"><?=$arResult['WINDOW_SUCCESS']['WS_DESCRIPTION']["VALUE"];?></div>
                    <? else : ?>
                        <?=GetMessage("POPUPPRO_THANKS")?>
                    <? endif; ?>
                </div>
            <? } ?>
        </div>
    </div>
<? else : ?>
    <div id="skyweb24_contact_type1"
         style="<? if(!empty($arResult['GOOGLE_FONT'])) { ?>font-family:<?=$arResult['GOOGLE_FONT']?><? } ?>">

        <div class="bgColorBlock">
            <div class="success">
                <div class="subtitle"><?=$arResult['WS_TITLE'];?></div>
                <div class="text"><?=$arResult['WS_DESCRIPTION'];?></div>
            </div>
        </div>
    </div>
<script>


    function call() {

        var msg = $('#form').serialize();

        $.ajax({
            type: 'POST',
            url: '/func.php',
            data: msg,
            success: function(data) {

                var obj = jQuery.parseJSON(data);

                if (obj.success == 'success') {
                    $('.prize').html('<span id="text">Твой выигрыш: ' + obj.prize + '</span>');
                } else {
                    $('.prize').html('<span id="text">Ошибка</span>');
                }

            }
        });

    }


</script>
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
<div id="skyweb24_roulette2" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
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
            <label class="email input">
                <input placeholder="<?=$arResult['EMAIL_PLACEHOLDER']?>" type="email" name="email">
                <p class="result_roll_fail" style="display:none;"><?=GetMessage('skyweb24.roulette_wrong')?><span></span></p>
                <p class="result_roll_not_unique" style="display:none;"><?=$arResult['EMAIL_NOT_NEW_TEXT']?><span></span></p>
            </label>
            <label class="phone input">
                <input placeholder="<?=$arResult['PHONE_PLACEHOLDER']?>" type="phone" name="phone">
                <p class="result_roll_fail" style="display:none;"><?=GetMessage('skyweb24.roulette_wrong')?><span></span></p>
                <p class="result_roll_not_unique" style="display:none;"><?=$arResult['EMAIL_NOT_NEW_TEXT']?><span></span></p>
            </label>
            <button class="roll_roulette sw24-button-animate"><?=$arResult['BUTTON_TEXT']?></button>
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

      function validateEmail(elementValue) {
          var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
          return emailPattern.test(elementValue);
      }
	  
	  function validatePhone(elementValue) {
          var phonePattern = /^[0-9]$/;
          return phonePattern.test(elementValue);
      }


      function isValidEmail(email) {

          let status = false;
          let currentPopupID = skyweb24Popups.Popup.getCurPopupID();

          BX.ajax({
              url: '<?=$templateFolder?>/ajax.php?type=checkemail&idPopup=<?=$arParams['ID_POPUP']?>&email=' + email,
              method: 'POST',
              dataType: 'json',
              async: false,
              data: {
                  // coockieLife: skyweb24Popups.Popup.getOption("repeat_game", currentPopupID) || 0
              },
              onsuccess: function (data) {
                  status = data;
              },
              onfailure: function (data) {
                  console.log(data)
              }
          });
          return status;
      }

      function addMail(email) {
          let currentPopupID = skyweb24Popups.Popup.getCurPopupID();
          var url = "<?=$templateFolder?>/ajax.php?type=addMail&email=" + email + "&addtotable=<?=$arResult['EMAIL_ADD2BASE']?>&idPopup=<?=$arParams['ID_POPUP']?>&sessid=" + BX('skyweb24_roulette2').querySelector('input[name="sessid"]').value;
          BX.ajax({
              url: url,
              method: 'POST',
              data: {
                  // coockieLife: skyweb24Popups.Popup.getOption("repeat_game", currentPopupID) || 0

              },
              onsuccess: function (data) {
                  console.log(data);
              },
              onfailure: function (data) {
                  console.log(data);
              }
          });
      }

      function getCoupon(id, text, sector) {

          let currentPopupID = skyweb24Popups.Popup.getCurPopupID();
          var url = "<?=$templateFolder?>/ajax.php?id=" + id + "&avaliable=<?=$arResult['TIMING']?>&sessid=" + BX('skyweb24_roulette2').querySelector('input[name="sessid"]').value;
          var email = BX('skyweb24_roulette2').querySelector('label.input');
          var getContinue = true;

          url += "&email=" + email.querySelector('input').value + "&sector=" + sector + "&idPopup=<?=$arParams['ID_POPUP']?>&addtotable=<?=$arResult['EMAIL_ADD2BASE']?>&unique=<?=$arResult['EMAIL_NOT_NEW']?>&resultText=" + text;

          BX.ajax({
              url: url,
              method: 'POST',
              data: {
                  // coockieLife: skyweb24Popups.Popup.getOption("repeat_game", currentPopupID) || 0
              },
              onsuccess: function (data) {
                  if (data == 'not_unique') {
                      BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'block';
                  } else {
                      document.querySelector('#skyweb24_roulette2 p.result_roll').style.display = '';
                      BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'none';
                      BX('skyweb24_roulette2').querySelector('label.input').remove()//.style.display='none';
                      BX('skyweb24_roulette2').querySelector('button.roll_roulette').remove()//.style.display='none';
                      let textCloseButton = BX('skyweb24_roulette2').querySelector('.sw24TextCloseButton');
                      if (textCloseButton) {
                          textCloseButton.remove();
                      }

                      skyweb24Popups.Statistic.send("action");

                  }
              },
              onfailure: function (data) {
                  console.log(data);
              }
          });
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

      function roll_roulette_func(count) {
          if (buttonWindowPopup.element)
          {
              buttonWindowPopup.destroy();
          }

          var email = BX('skyweb24_roulette2').querySelector('label.email');
          getContinue = validateEmail(email.querySelector('input').value);
          if (email.querySelector('input').value == '') {
              email.className += " blink";
              setTimeout(function () {
                  email.className = email.className.replace('blink', '');
              }, 1000);
          }

          if (getContinue) {

              var validEmail = isValidEmail(email.querySelector('input').value);
              if (!validEmail) {
                  BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'block';
                  email.classList.add("sw24-error");
                  email.focus();
                  return;
              }
              BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'none';

              var percent = 100 / count;
              var deg = 360 / 100;
              var center = 90 - (percent * 3.6 / 2);

              var rand = "<?=$arResult['SECTOR']?>";
              var res = '';
              var sector = rand;
              rand--;

              var res = -(deg * percent * rand - center);

              document.querySelector('#skyweb24_roulette2 div.rotate_block').style.transform = 'rotate(' + (res - 3600) + 'deg)';
              setTimeout(function () {
                  res_roll(sector, email.querySelector('input').value)
              }, 7000);
          } else {
              BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'none';
              email.classList.add("sw24-error");
              email.focus();
          }

      }
	  
	        function roll_roulette_func(count) {
          if (buttonWindowPopup.element)
          {
              buttonWindowPopup.destroy();
          }

          var phone = BX('skyweb24_roulette2').querySelector('label.phone');
          getContinue = validateEmail(phone.querySelector('input').value);
          if (phone.querySelector('input').value == '') {
              phone.className += " blink";
              setTimeout(function () {
                  phone.className = phone.className.replace('blink', '');
              }, 1000);
          }

          if (getContinue) {

              var validEmail = isValidEmail(phone.querySelector('input').value);
              if (!validEmail) {
                  BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'block';
                  phone.classList.add("sw24-error");
                  phone.focus();
                  return;
              }
              BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'none';

              var percent = 100 / count;
              var deg = 360 / 100;
              var center = 90 - (percent * 3.6 / 2);

              var rand = "<?=$arResult['SECTOR']?>";
              var res = '';
              var sector = rand;
              rand--;

              var res = -(deg * percent * rand - center);

              document.querySelector('#skyweb24_roulette2 div.rotate_block').style.transform = 'rotate(' + (res - 3600) + 'deg)';
              setTimeout(function () {
                  res_roll(sector, phone.querySelector('input').value)
              }, 7000);
          } else {
              BX('skyweb24_roulette2').querySelector('p.result_roll_not_unique').style.display = 'none';
              phone.classList.add("sw24-error");
              phone.focus();
          }

      }


      function res_roll(sector, email) {
          var text = document.querySelector('#skyweb24_roulette2 div.rotate_block>.text>div:nth-child(' + sector + ')').innerHTML;
          var rule = document.querySelector('#skyweb24_roulette2 div.rotate_block>.text>div:nth-child(' + sector + ')').dataset.rule;
          var not = document.querySelectorAll('#skyweb24_roulette2 div.rotate_block>.container>div:not(:nth-child(' + sector + '))');
          for (var i = 0; i < not.length; i++) {
              not[i].className += ' negative';
          }

          if (rule != 'nothing') {
              //document.querySelector('#skyweb24_roulette2 p.result_roll').style.display='';
              getCoupon(rule, text, sector);
          } else {
              addMail(email);
              BX('skyweb24_roulette2').querySelector('label.input').remove();
              BX('skyweb24_roulette2').querySelector('button.roll_roulette').remove();
              BX('skyweb24_roulette2').querySelector('p.result_roll').remove();
              BX('skyweb24_roulette2').querySelector('p.result_roll_nothing').style.display = '';
          }
          <?=$arResult['BUTTON_METRIC']?>
      }

      var addSector = function (data, startAngle, collapse) {
          var sectorDeg = 3.6 * data.value;
          var skewDeg = 90 + sectorDeg;
          var rotateDeg = startAngle;
          if (collapse) {
              skewDeg++;
          }
          var container = document.querySelector('#skyweb24_roulette2 .container');
          var sector = document.createElement('div');
          sector.style.background = data.color;
          sector.className = 'sector';
          sector.style.transform = 'rotate(' + rotateDeg + 'deg) skewY(' + skewDeg + 'deg)';
          container.appendChild(sector);
          var container_text = document.querySelector('#skyweb24_roulette2 .text');
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
</script>

</div>

<script>
    var buttonAnimation     = '<?=$arResult['BUTTON_ANIMATION'];?>';
    var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


    skyweb24_windowAnimation.show(showWindowAnimation);
    skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);


</script><? endif; ?>