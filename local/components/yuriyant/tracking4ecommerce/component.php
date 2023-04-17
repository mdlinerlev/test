<?

/**
 * yuriyant.tracking4ecommerce
 *
 * Компонент для работы с модулем регистрации данных электронной коммерции 
 * в Google Analitycs EcommerceИнформация о плагине
 *
 * Принимает запросы от клиента из публичной части
 * и записывает в backend параметры
 * - разрешение экрана посетителея
 * - глубину цвета дисплея
 * - поддержку Java
 * - заголовок стр
 * - реферала
 * - IP 
 * - User Agent
 * - UTM метки
 *  
 * @copyright 2015, Yuriyant, http://yuriyant.com/
 * @author Антохин Юрий <support@yuriyant.com>
 *
 * @package yuriyant.tracking4ecommerce
 *
 */

namespace YuriyantGA;

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Yuriyant\Modules\GoogleAnalitycs\UserParams;
use Yuriyant\Modules\GoogleAnalitycs\GAClient;
use function IsModuleInstalled;

define("NO_KEEP_STATISTIC", true);
define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define("PUBLIC_AJAX_MODE", true);
define("NO_AGENT_STATISTIC", true);

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

if (!IsModuleInstalled("yuriyant.tracking4ecommerce") || !Loader::includeModule("yuriyant.tracking4ecommerce")) {
    echo 'Module "yuriyant.tracking4ecommerce" not installed';
    return;
}

if (@mb_strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
    if ($_POST['userParams'] && $params = Json::decode($_POST['userParams'])) {
        UserParams::setParam('screenResolution', trim(strip_tags(@$params['screenResolution'])));
        UserParams::setParam('screenColorDepth', (int) @$params['screenColorDepth']);
        UserParams::setParam('javaEnabled', (int) @$params['javaEnabled']);
        UserParams::setParam('documentTitle', trim(strip_tags(@$params['documentTitle'])));
        UserParams::setParam('pageUrl', ltrim(trim(strip_tags(@$params['pageUrl'])), '/'));
        UserParams::setParam('documentReferrer', trim(strip_tags(@$params['documentReferrer'])));
        $customerID = trim(strip_tags(@$params['cid']));
        if (UserParams::isValidGoogleID($customerID)) {
            UserParams::setParam('customerID', $customerID);
        }
    }
    UserParams::setParam('userIP', @$_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : (@$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : @$_SERVER['HTTP_CLIENT_IP']));
    UserParams::setParam('userAgent', $_SERVER['HTTP_USER_AGENT']);
    //UTM, gclid, etc
    $paramsURL = parse_url($params['pageUrl']);
    if (isset($paramsURL['query']) && $paramsURL['query']) {
        parse_str(urldecode($paramsURL['query']), $queryParams);
        if ($queryParams && is_array($queryParams)) {
            foreach ($queryParams as $key => $val) {
                if (!trim($val)) {
                    continue;
                }
                if (UserParams::isTrackingParameter($key)) {
                    UserParams::setParam($key, trim(strip_tags($val)));
                }
            }
        }
    }
    if (UserParams::getParam('initializedUser', true, 'general')) {
        $gaClient = new GAClient();
        $gaClient->execTransactionsQueue();
    }
}
?>
<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>