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

$vk = $arResult['ID_VK'];
$inst = $arResult['ID_INST'];
$odnkl = $arResult['ID_ODNKL'];

$count=0;
if(!empty($vk))
        $count++;
if(!empty($inst))
        $count++;
if(!empty($odnkl))
        $count++;
$count*=300;

?>


<div id="skyweb24_social_all" style="width: 900px;<?=($arResult['GOOGLE_FONT']) ? 'font-family:'.$arResult['GOOGLE_FONT']  : ''; ?>">
<?if(!empty($arResult['GOOGLE_FONT'])){?><link href="https://fonts.googleapis.com/css?family=<?=$arResult['GOOGLE_FONT']?>:400,700" rel="stylesheet"><?}?>
	<div class="title"><?=$arResult['TITLE']?></div>
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
	<div class="social_holder">
		<?if(!empty($arResult['ID_VK'])){?>
		<!-- VK Widget -->
		<div id="vk_groups"></div>
		<script type="text/javascript">
            checkElement('#vk_groups').then((e) => {
                var vk_groups = document.getElementById('vk_groups');
                var wrapper = vk_groups.parentElement;
                var scriptVK = document.createElement('script');
                scriptVK.src = 'https://vk.com/js/api/openapi.js?154';
                wrapper.appendChild(scriptVK);
                scriptVK.onload = () => {VK.Widgets.Group('vk_groups', {mode: 5, width:'300', height:'316'}, <?=$arResult['ID_VK']?>)};
            });
		</script>

		<?}?>
		<?if(!empty($arResult['ID_ODNKL'])){?>
		<div id="ok_group_widget"></div>
		<script>
        checkElement('#ok_group_widget').then((e) => {
            var ok_groups = document.getElementById('ok_group_widget');
            var wrapper = ok_groups.parentElement;
            var scriptOK = document.createElement('script');
            scriptOK.src = 'https://connect.ok.ru/connect.js';
            wrapper.appendChild(scriptOK);
            scriptOK.onload = () => {
                OK.CONNECT.insertGroupWidget('ok_group_widget','<?=$arResult['ID_ODNKL']?>','{"width":300,"height":316}');
            };
        });
		</script>
		<?}?>
		<?if(!empty($arResult['ID_INST'])):?>

            <script>
                (function (script) {
                    document.head.append(script = BX.create("script", {
                        attrs:{
                            src: "https://averin.pro/widget_js/widget.js"
                        }
                    }));
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

		<? endif; ?>
		<?if(($arResult['CLOSE_TEXTBOX']=='Y') && (!empty($arResult['CLOSE_TEXTAREA']))) {?>
		<div><a href="javascript:void(0);" class="sw24TextCloseButton"><?=$arResult['CLOSE_TEXTAREA']?></a></div>
		<?}?>
		<div class="clear"></div>
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

    var showWindowAnimation = '<?=$arResult['SHOW_ANIMATION'];?>';
    skyweb24_windowAnimation.show(showWindowAnimation);

</script>
