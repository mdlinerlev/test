<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?
if (!function_exists('GetValidateMessages')) {
    function GetValidateMessages($type) {
        switch($type) {
            case 'EMAIL':
                echo "required: 'Поле email не заполнено',\r\n";
                break;
            default: echo "required: 'Обязательное поля для заполнения'\r\n";
        }
    }
}
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
if ($arResult["isFormNote"] == "Y" && !empty( $request['RESULT_ID'] )):
    // получим данные по всем вопросам
    $arAnswer = CFormResult::GetDataByID($request['RESULT_ID'], array(), $old, $arAnswer);

    $arConf = [
            'commentary' => htmlspecialcharsEx($arResult["FORM_TITLE"]),
            'name' => htmlspecialcharsEx($arResult["FORM_TITLE"]),
    ];
    foreach($arResult["QUESTIONS"] as $FIELD_SID => $q):

        $value = htmlspecialcharsEx($arAnswer[$FIELD_SID][0]['USER_TEXT']);

        switch($arResult["arQuestions"][$FIELD_SID]["COMMENTS"]) {
            case 'PHONE':
                $arConf['phone'] =$value;
                break;
        }
        $arConf['commentary'] .= " {$q['CAPTION']}: {$value}";

    endforeach;
    ?>
    <form id="model_<?=$arResult["arForm"]["SID"]?>">
        <input id="model_<?=$arResult["arForm"]["SID"]?>_name" data-value="<?= $arConf['name']?>" value="" type="hidden"/>
        <input id="model_<?=$arResult["arForm"]["SID"]?>_phone" data-mask="+7 (999) 999-99-99"data-value="<?= $arConf['phone']?>"  value="" type="hidden"/>
        <input id="model_<?=$arResult["arForm"]["SID"]?>_msg" data-value="<?= $arConf['commentary']?>"  value="" type="hidden"/>
    </form>
    <script>
        dataLayer.push(
            {'event': '<?=$arResult["arForm"]["SID"]?>'}
        );
    </script>
<?endif;?>

<script type="text/javascript">

    $('form[name="<?=$arResult["arForm"]["SID"]?>"]').validate({
        focusInvalid: false,
        focusCleanup: true,
        submitHandler: function(form) {
            form.submit();
        },
        rules:{
            <?foreach($arResult["QUESTIONS"] as $FIELD_SID => $q):?>
            form_<?=$q['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$q['STRUCTURE'][0]['ID']?>: {
                <?= $q["REQUIRED"] ? 'required: true' : 'required: false';?>
            },
            <?endforeach;?>
        },
        messages:{
            <?foreach($arResult["QUESTIONS"] as $FIELD_SID => $q):?>
            form_<?=$q['STRUCTURE'][0]['FIELD_TYPE']?>_<?=$q['STRUCTURE'][0]['ID']?>: {
                <?GetValidateMessages($arResult["arQuestions"][$FIELD_SID]["COMMENTS"])?>
            },
            <?endforeach;?>
        },
    });
    $('.popup-form__button_valid<?=$arResult["arForm"]["SID"]?>').on('click', function () {
        if(!$('form[name="<?=$arResult["arForm"]["SID"]?>"]').valid())
            return false;
    });
    <?if ($arResult["isFormNote"] == "Y"):?>
    var baseLandingId = "<?=LANDING_ID?>";
    var baseServiceUrl = "https://web.belwood.ru/0/ServiceModel/GeneratedObjectWebFormService.svc/SaveWebFormObjectData";
    var baseRedirectUrl = window.location.href;
    var config = {
        fields: {
            'Name': '#model_<?=$arResult["arForm"]["SID"]?>_name',
            'MobilePhone': '#model_<?=$arResult["arForm"]["SID"]?>_phone',
            'UsrCommentary': '#model_<?=$arResult["arForm"]["SID"]?>_msg',
        },
        landingId: baseLandingId,
        serviceUrl: baseServiceUrl,
        redirectUrl: baseRedirectUrl,
        onSuccess: function(response) {
            console.log(response.resultMessage);
        },
        onError: function(response) {
            console.log(response.resultMessage);
        }
    };
    landing.initLanding(config);

    $('#model_<?=$arResult["arForm"]["SID"]?> input').each(function(e) {
        $(this).val($(this).data('value'));
    });
    landing.createObjectFromLanding(config);
    <?endif;?>

</script>