<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="skyweb24_share_default" style="background: url(<?= !empty($arResult['IMG_1_SRC']) ? $arResult['IMG_1_SRC'] : $templateFolder . "/img/background.jpg";?>); background-position: center;background-size: cover; background-repeat: no-repeat; <?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
	<div class="bg">		
		<div class="title"><?=$arResult['TITLE']?></div>
		<div class="subtitle"><?=$arResult['SUBTITLE']?></div>
		<div class="socialButtons">
			<a class="sw24TargetAction <?=$arResult['SOC_VK']?>" href="https://vk.com/share.php?url=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_VK")?>"><img src="<?=$templateFolder?>/img/vk.jpg"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_OD']?>" href="https://connect.ok.ru/offer?url=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_OK")?>"><img src="<?=$templateFolder?>/img/odnoklassniki.jpg"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_FB']?>" href="http://www.facebook.com/sharer/sharer.php?u=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_FB")?>"><img src="<?=$templateFolder?>/img/faceb.jpg"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_TW']?>" href="http://twitter.com/share?url=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_TWITTER")?>"><img src="<?=$templateFolder?>/img/tw.jpg"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_MR']?>" href="http://connect.mail.ru/share?share_url=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_MM")?>"><img src="<?=$templateFolder?>/img/mail.jpg"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_TE']?>" href="https://telegram.me/share/url?url=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_TE")?>"><img src="<?=$templateFolder?>/img/teleg.png"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_VI']?>" href="viber://forward?text=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_VI")?>"><img src="<?=$templateFolder?>/img/viber.png"></a>
			<a class="sw24TargetAction <?=$arResult['SOC_WA']?>" href="https://wa.me/?text=<?=$_SERVER["HTTP_REFERER"];?>" target="<?=$arResult['HREF_TARGET']?>" title="<?=GetMessage("skyweb24_referralsales_SHARE_IN_WA")?>"><img src="<?=$templateFolder?>/img/wa.png"></a>
		</div>
		<?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
			<div align="center"><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
		<?}?>
	</div>
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

    var elements = document.querySelectorAll(".socialButtons .sw24TargetAction");
    for (var i = 0; i < elements.length; i++){
        elements[i].addEventListener("click", function () {
            if(buttonWindowPopup.element)
                buttonWindowPopup.destroy();
        })
    }

    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';
    skyweb24_windowAnimation.show(showWindowAnimation);

</script>