<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Context;

$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$arResult = [];
\Bitrix\Main\Loader::includeModule("skyweb24.popuppro");

if($request->get("action") == "reward")
{
    $popup = new \Skyweb24\Popuppro\Popup($request->get("popupId"));

    $reward = new \Skyweb24\Popuppro\Reward(
        $popup,
        [
            "email" => $request->get("email")
        ]
    );
    $result = $reward->get();
    $reward->send();
    $popup->filling();

    echo \Bitrix\Main\Web\Json::encode([
        "type" => $result['type'],
        "value" => $result['value'],
        "error" => $reward->getError()
    ]);
}
else if($request->get("action") == "check_email")
{
    global $USER;
    $arResult = false;
    $popup = new \Skyweb24\Popuppro\Popup($request->get("popupId"));
    $popupUser = new \Skyweb24\Popuppro\User();
    $setting = $popup->getSettings();

    if($popupUser->isValidEmail(
        $request->get("popupId"),
        $request->get("email"),
        $setting['view']['props']['EMAIL_NOT_NEW']
    )){
        if(
            !empty($setting['view']['props']['REGISTER_USER']) AND
            $setting['view']['props']['REGISTER_USER'] == "Y" AND
            !$USER->IsAuthorized()
        ) {
           $popupUser->register($request->get("email"));
        }

        $arResult = true;
    }

    echo \Bitrix\Main\Web\Json::encode([
        "status" => $arResult,
        "error" => $popupUser->getError()
    ]);
}
?>
<? require_once($_SERVER["DOCUMENT_ROOT"]. "/bitrix/modules/main/include/epilog_after.php");?>
