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

<?
$blocks = array();
if(!empty($arResult['ID_VK'])){
	$blocks[]='ID_VK';
}
if(!empty($arResult['ID_INST'])){
	$blocks[]='ID_INST';
}
if(!empty($arResult['ID_ODNKL'])){
	$blocks[]='ID_ODNKL';
}
$rand = array_rand($blocks,1);
$choosen = $blocks[$rand];
$arResult['COLOR_BG']=(empty($arResult['COLOR_BG']))?'#fff':$arResult['COLOR_BG'];
// $choosen == 'ID_VK';
?>

<script>
    function checkElement(selector) {
        if (document.querySelector(selector) === null) {
            return rafAsync().then(() => checkElement(selector));
        } else {
            return Promise.resolve(true);
        }
    }
    function rafAsync() {
        return new Promise(resolve => {
            requestAnimationFrame(resolve); 
        });
    }
</script>
<div id="skyweb24_banner_type4" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
    <?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
    <div class="skwb-title"><?=$arResult['TITLE']?></div>
    <div class="skwb-social">
        <a target="_blank" href="//twitter.com/<?=$arResult['ID_TWITTER'];?>" class="skwb-social__item _tw">
            <i class="icon-twitter"></i>
        </a>
        <a target="_blank" href="//youtube.com/<?=$arResult['ID_YOUTUBE'];?>" class="skwb-social__item _yt">
            <i class="icon-youtube"></i>
        </a>
        <a target="_blank" href="//vk.com/<?=$arResult['ID_VK'];?>" class="skwb-social__item _vk">
            <i class="icon-vkontakte"></i>
        </a>
        <a target="_blank" href="//ok.ru/<?=$arResult['ID_ODNKL'];?>" class="skwb-social__item _ok">
            <i class="icon-odnoklassniki"></i>
        </a>
        <a target="_blank" href="//facebook.com/<?=$arResult['ID_FACEBOOK'];?>" class="skwb-social__item _fb">
            <i class="icon-facebook"></i>
        </a>
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

    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';
    skyweb24_windowAnimation.show(showWindowAnimation);

</script>
