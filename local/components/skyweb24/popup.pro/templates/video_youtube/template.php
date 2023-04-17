<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult['TIMER']=='Y'){
		$APPLICATION->IncludeComponent('skyweb24:popup.pro.timer','',array(
			'TITLE'=>$arResult['TIMER_TEXT'],
			'TIME'=>$arResult['TIMER_DATE'],
			'LEFT'=>$arResult['TIMER_LEFT'],
			'RIGHT'=>$arResult['TIMER_RIGHT'],
			'TOP'=>$arResult['TIMER_TOP'],
			'BOTTOM'=>$arResult['TIMER_BOTTOM'],
		),$component);
	}
	$arResult['VIDEO_AUTOPLAY']=($arResult['VIDEO_AUTOPLAY']==1)?'1&mute=1':$arResult['VIDEO_AUTOPLAY'];//new police youtube
	?>
<div id="skyweb24_video_youtube">
	<iframe width="100%" height="480" src="https://www.youtube.com/embed/<?=$arResult['LINK_VIDEO']?>?rel=<?=$arResult['VIDEO_SIMILAR']?>&autoplay=<?=$arResult['VIDEO_AUTOPLAY']?>" frameborder="0"></iframe>
	<?/*
	�������������� ��������� YouTube
	rel=0 - ������ ������ ������� �������
	autoplay=1 - ���������� ������
	disablekb=1 - ������ �� ���������� ���������������� ������ � ������� ���������� (������ ��������� ����� �� ����������)
	fs=0 - ������ �� ��������������� �� ���� �����
	showinfo=0 - ������� ���������� � ������
	start=N - ��������������� ����� ����� N ������ ����� ��������
	*/?>
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