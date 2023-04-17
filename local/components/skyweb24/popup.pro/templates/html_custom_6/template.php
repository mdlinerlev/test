<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	<?if($arResult['TIMER']=='Y'){
		$APPLICATION->IncludeComponent('skyweb24:popup.pro.timer','',array(
			'TITLE'=>$arResult['TIMER_TEXT'],
			'TIME'=>$arResult['TIMER_DATE'],
			'LEFT'=>$arResult['TIMER_LEFT'],
			'RIGHT'=>$arResult['TIMER_RIGHT'],
			'TOP'=>$arResult['TIMER_TOP'],
			'BOTTOM'=>$arResult['TIMER_BOTTOM'],
		),$component);
	}?>
<div id="skyweb24_html_default">
<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>Рулетка</title>

        <style>
            .game{width:500px;height:150px;margin:0 auto;padding:15px;border-radius:10px;background-color:aliceblue }
            .prize{padding:15px 30px;border-radius:10px;background-color:#e3e3e3;text-align:center }
            #text{font-size:22px;font-weight:700 } form{padding:10px;text-align:center }
            button{cursor:pointer;width:200px;height:60px;border:none;border-radius:30px;font-size:16px;font-weight:700;background-color:teal;color:white }
        </style>

    </head>

    <body>

        <div class="game">
            <div class="prize"></div>
            <form method="POST" id="form" action="javascript:void(null);" onsubmit="call()">
                <input type="hidden" name="type" value="lot">
                <button>Сюда надо тыкать</button>
            </form>
        </div>

    </body>

</html>



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

	<?=$arResult['TEXTAREA']?>
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