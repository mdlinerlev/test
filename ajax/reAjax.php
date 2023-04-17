<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();

if(!empty($request["action"]) && file_exists($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/ajax/" . $request["action"] . ".php")){
    require_once $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH."/ajax/" . $request["action"] . ".php";
}
if(empty($request["no-epilog"])){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
}
?>