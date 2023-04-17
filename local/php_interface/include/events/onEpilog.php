<?
use Bitrix\Main\Application;

function SeoEpilogMethods()
{
    global $APPLICATION;
    $request = Application::getInstance()->getContext()->getRequest();
    if ($request["PAGEN_1"] && intval($request["PAGEN_1"]) > 1) {
        $APPLICATION->SetPageProperty('title', $APPLICATION->GetTitle() . " - страница " . $request["PAGEN_1"]);
        $APPLICATION->SetPageProperty('keywords', $APPLICATION->GetPageProperty("keywords") . " - страница " . $request["PAGEN_1"]);
        $APPLICATION->SetPageProperty('description', $APPLICATION->GetPageProperty("description") . " - страница " . $request["PAGEN_1"]);
    }
}
