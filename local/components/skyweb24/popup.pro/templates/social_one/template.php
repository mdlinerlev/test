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
<div id="skyweb24_banner_default" style="<?if(!empty($arResult['GOOGLE_FONT'])){?>font-family:<?=$arResult['GOOGLE_FONT']?><?}?>">
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
	<div class="top_border"></div>
	<div class="left_border"></div>
	<div class="title"><?=$arResult['TITLE']?></div>
	<?$img='vk.png';
		if($choosen == 'ID_VK'){
	?>
		<img src="<?=$templateFolder?>/img/<?=$img?>">
			<div id="skyweb24_vk_groups"></div>
            <script>
                checkElement('#skyweb24_vk_groups').then((e) => {
                    var vk_groups = document.getElementById('skyweb24_vk_groups');
                    var wrapper = vk_groups.parentElement;
                    var scriptVK = document.createElement('script');
                    scriptVK.src = 'https://vk.com/js/api/openapi.js?154';
                    wrapper.appendChild(scriptVK);
                    scriptVK.onload = () => {VK.Widgets.Group('skyweb24_vk_groups', {mode: 5, width:'auto', height:'316'}, <?=$arResult['ID_VK']?>)};
                });
            </script>
	<?}elseif($choosen == 'ID_INST'){
			$img='instagram.png';
			?>
			<img src="<?=$templateFolder?>/img/<?=$img?>">
            <script>
                (function (script) {
                    script = document.createElement('script');
                    script.src = 'https://averin.pro/widget_js/widget.js';
                    document.head.appendChild(script);
                    script.addEventListener("load", function () {
                        new myWidget('#inst_wid', {
                            login: "<?=$arResult['ID_INST'];?>",
                            style: 1,
                            width: '95%',
                            background: '#FFFFFF',
                            border_color: '#c3c3c3',
                            header: 1,
                            title: 1,
                            title_text: '<?=GetMessage("SK24_SOCIAL_INST_TITLE_TEXT")?>',
                            title_background: '#000000',
                            title_text_color: '#FFFFFF',
                            submit: 1,
                            submit_background: '#FF0000',
                            submit_text_color: '#FFFFFF',
                            submit_text: '<?=GetMessage("SK24_SOCIAL_INST_SUBSCRIBE")?>',
                            gallery: 1,
                            amount: 3,
                            flex: '33.3%'
                        });
                    });
                })();
            </script>

            <div id='inst_wid'></div>

	<?}elseif($choosen=='ID_ODNKL'){
		$img='odnkl.png';?>
		<img src="<?=$templateFolder?>/img/<?=$img?>">
		<div id="ok_group_widget"></div>
		<script>
        
        checkElement('#ok_group_widget').then((e) => {
            var ok_groups = document.getElementById('ok_group_widget');
            var wrapper = ok_groups.parentElement;
            var scriptOK = document.createElement('script');
            scriptOK.src = 'https://connect.ok.ru/connect.js';
            wrapper.appendChild(scriptOK);
            scriptOK.onload = () => {
                OK.CONNECT.insertGroupWidget('ok_group_widget','<?=$arResult['ID_ODNKL']?>','{"width":305,"height":316}');
            };

            //remove dublicate
            checkElement('#__okGroup1').then((e) => {
                var dublicate = document.getElementById('__okGroup1');
                ok_groups.removeChild(dublicate);
            });
        });

		</script>
	<?}?>
	<?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
		<div><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
	<?}?>
	<div class="right_border"></div>
	<div class="bottom_border"></div>
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
