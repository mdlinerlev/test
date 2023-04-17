<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="skyweb24_banner_default" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
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
	<a class="sw24TargetAction" href="<?=$arResult['LINK_HREF']?>" target="<?=$arResult['HREF_TARGET']?>">
		<img src="<?=$arResult['IMG_1_SRC']?>" alt="">
	</a>
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

    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';
    skyweb24_windowAnimation.show(showWindowAnimation);
</script>
