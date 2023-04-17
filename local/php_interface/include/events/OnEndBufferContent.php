<?

function deleteKernelJs(&$content) {
    global $USER, $APPLICATION;
    if ((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/") !== false)
        return;

    if ($APPLICATION->GetProperty("save_kernel") == "Y")
        return;

    $arPatternsToRemove = Array(
        '/<script.+?src=".+?kernel_main\/kernel_main\.js\?\d+"><\/script\>/',
        '/<script.+?src=".+?bitrix\/js\/main\/core\/core[^"]+"><\/script\>/',
        '/<script.+?>BX\.(setCSSList|setJSList)\(\[.+?\]\).*?<\/script>/',
        '/<script.+?>if\(\!window\.BX\)window\.BX.+?<\/script>/',
        '/<script[^>]+?>\(window\.BX\|\|top\.BX\)\.message[^<]+<\/script>/',
    );

    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);

}

function deleteKernelCss(&$content) {
    global $USER, $APPLICATION;
    if ((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/") !== false)
        return;
    if ($APPLICATION->GetProperty("save_kernel") == "Y")
        return;

    $arPatternsToRemove = Array(
        '/<link.+?href=".+?bitrix\/panel\/main\/popup.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?kernel_main\/kernel_main\.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/js\/main\/core\/css\/core[^"]+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/styles.css[^"]+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/templates\/[\w\d_-]+\/template_styles.css[^"]+"[^>]+>/',
    );

    $content = preg_replace($arPatternsToRemove, "", $content);
    $content = preg_replace("/\n{2,}/", "\n\n", $content);
}

function linksReplace(&$content) {

    global $USER, $APPLICATION;
    if (strpos($APPLICATION->GetCurDir(), "/bitrix/") !== false)
        return;

    //$arPatternsToRemove = '/[\("\'](\/(?:(?:upload)|(?:bitrix)|(?:local))\/[^"\']+?\.(?:(?:png)|(?:jpg)|(?:gif)))[\)"\']/i';
    $arPatternsToRemove = '/[\("\'](\/(?:(?:upload\/uf)|(?:bitrix)|(?:local))\/[^"\']+?\.(?:(?:png)|(?:jpg)))[\)"\']/i';
    ShowAllError();
    $math = array();
    preg_match_all($arPatternsToRemove, $content, $math);

    $oRequest = Bitrix\Main\Context::getCurrent()->getRequest();


    if (!empty($math[1])) {



        $math[1] = array_unique($math[1]);

        foreach ($math[1] as $k => $cont) {

            $contresized = $cont;

            if (strpos($cont, "/upload/Sh/imageCache") === false && filesize($_SERVER["DOCUMENT_ROOT"].$cont) && strpos($cont, "/upload/uf") !== false) {
              $contresized = imageResize(array("WIDTH" => 0, "HEIGHT" => 0, "QUALITY" => 85, "HIQUALITY" => 0, "FILTER_AFTER" => array("setImagePage" => array(0, 0, 0, 0))), $cont);
            }

            $content = str_replace($cont, $contresized, $content);
        }
    }

    $arPatternsToRemove = '/["\'](\/(?:(?:upload)|(?:bitrix)|(?:local))\/[^"\']+?\.(?:(?:css)|(?:js)))(?:\?\d+)?["\']/i';

    $math = array();
    preg_match_all($arPatternsToRemove, $content, $math);


    if (!empty($math[1])) {

        $math[1] = array_unique($math[1]);

        foreach ($math[1] as $k => $cont) {

            $content = str_replace($cont, $cont, $content);
        }
    }
}

function replaceSpaces(&$buffer) {

    global $USER, $APPLICATION;
    if ((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/") !== false)
        return;


    $arPatternsToRemove = Array(
        '/<link.+?href=".+?bitrix\/panel\/main\/popup.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/panel\/main\/popup.min.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?kernel_main\/kernel_main\.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?kernel_main\/kernel_main_v1\.css\?\d+"[^>]+>/',
        '/<link.+?href=".+?bitrix\/js\/main\/core\/css\/core[^"]+"[^>]+>/',
    );

    $buffer = preg_replace($arPatternsToRemove, "", $buffer);


    $buffer = preg_replace('~>\s*\n\s*<~', '><', $buffer);
    $buffer = preg_replace('/ {2,}/', ' ', $buffer);
    $buffer = preg_replace('/>[\n]+/', '>', $buffer);
    return $buffer;
}
