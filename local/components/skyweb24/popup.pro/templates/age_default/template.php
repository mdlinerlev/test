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


<div id="skyweb24_age_default" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
	<img src="<?=$arResult['IMG_1_SRC']?>">
	<div class="title"><?=$arResult['TITLE']?></div>
	<div class="buttons">
		<a rel="nofollow" href="javascript:void(0);" class="sw24TargetAction yesClick"><?=$arResult['BUTTON_TEXT_Y']?></a>
		<a rel="nofollow" href="<?=$arResult['HREF_LINK']?>" class="noClick"><?=$arResult['BUTTON_TEXT_N']?></a>
	</div>
	<?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
		<div align="center"><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
	<?}?>
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


        (function () {
            document.querySelector("#skyweb24_age_default .sw24TargetAction").addEventListener("click", function () {
               if(buttonWindowPopup.element)
                   buttonWindowPopup.destroy();

               skyweb24Popups.Popup.request({
                   url: '<?=$templateFolder?>/ajax.php',
                   fields: {
                       'id_popup':'<?=$arParams["ID_POPUP"]?>',
                       'template_name':'<?=$templateName?>',
                       'checked':'Y'
                   },
               });
               skyweb24Popups.Popup.close();
           });
        })();


	</script>
</div>
