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



<div id="skyweb24_popup_action" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
<? if( isset($arResult['BUTTON_ANIMATION']) AND !empty($arResult['BUTTON_ANIMATION']) ) : ?>
	<link href="/bitrix/css/skyweb24.popuppro/animation.css" rel="stylesheet">
<? endif;?>
	<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
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
	<img id="img_going_s1" src="<?=$arResult['IMG_1_SRC']?>">
	<div class="text">
		<h2><?=$arResult['TITLE']?></h2>
		<div class="info"><?=$arResult['CONTENT']?></div>
		<h3><?=$arResult['SUBTITLE']?></h3>
		<a onclick="<?=$arResult['BUTTON_METRIC']?>" target="<?=$arResult['HREF_TARGET']?>" href="<?=$arResult['LINK_HREF']?>" class="sw24-button-animate sw24TargetAction going_link"><?=$arResult['LINK_TEXT']?></a>
	</div>
	<?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
		<a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a>
	<?}?>
</div>

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
    if(document.querySelector(".going_link"))
    {
        document.querySelector(".going_link").addEventListener("click", function () {
            if(buttonWindowPopup.element)
                buttonWindowPopup.destroy();
        })
    }


    var buttonAnimation     = '<?=$arResult['BUTTON_ANIMATION'];?>';
    var buttonAnimationTime = '<?=$arResult['BUTTON_ANIMATION_TIME'];?>';
    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';


    skyweb24_windowAnimation.show(showWindowAnimation);
    skyweb24_buttonAnimation.setButtonAnimation(buttonAnimation, buttonAnimationTime);


</script>