<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::IncludeModule("skyweb24.popuppro");
define("NO_KEEP_STATISTIC", true);
global $APPLICATION;
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$popup = new popuppro;
$setting = $popup->getSetting($_REQUEST['idPopup']);

if ($setting['row']['active'] == 'Y') {
    if (!check_bitrix_sessid()) {
        die("ACCESS_DENIED");
    } else {
        $res = '';
        $email = '';
        if (isset($_REQUEST['email']))
            $email = $_REQUEST['email'];
        if (!isset($_REQUEST['addtotable'])) {
            $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup']);
            echo $res;
            die();
        }


        // register new user
        if
        (
            isset($_REQUEST["addtotable"])
            && isset($_REQUEST["email"])
            && $_REQUEST["addtotable"] == "Y"
            && $setting['view']['props']['REGISTER_USER'] == "Y"
        )
        {
            $tmpUsers = CUser::GetList(($by = "ID"), ($order = "desc"), array('EMAIL' => $_REQUEST['email']));
            if (!$tmpUsers->Fetch()) {
                $res = $USER->SimpleRegister($_REQUEST["email"]);
            }
        }


        if (isset($_REQUEST['addtotable']) && $_REQUEST['addtotable'] != 'Y') {
            $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup']);
            echo $res;
            die();
        }
        if (isset($_REQUEST['email']) && isset($_REQUEST['idPopup']) && $_REQUEST['addtotable'] == 'Y') {
            if ($_REQUEST['unique'] == 'Y') {
                if (!$popup->searchinMailList($email, $_REQUEST['idPopup'])) {
                    $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup']);
                    $popup->insertToMailList($email, '', $_REQUEST['idPopup']);
                    echo $res;
                } else {
                    echo 'not_unique';
                }
            } else {
                $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup']);
                $popup->insertToMailList($email, '', $_REQUEST['idPopup']);
                echo $res;
            }
        }

    }
} else {
    echo 'not active window';
}

die();