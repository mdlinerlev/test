<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Main\Web\Cookie,
    Bitrix\Main\Context;

\Bitrix\Main\Loader::IncludeModule("skyweb24.popuppro");
define("NO_KEEP_STATISTIC", true);


function registerUser($mail, $props)
{
    global $USER;
    if(!empty($props['REGISTER_USER']) && $props['REGISTER_USER'] == 'Y') {
        if(!$USER->IsAuthorized()) {
            $rsUsers = CUser::GetList(($by = "personal_country"), ($order = "desc"), ['EMAIL' => $mail]);
            if(!$rsUser = $rsUsers->Fetch()) {
                $pass = randString(8, ["abcdefghijklnmopqrstuvwxyz", "ABCDEFGHIJKLNMOPQRSTUVWX­YZ", "0123456789"]);
                $tt = $USER->Register($mail, "", "", $pass, $pass, $mail);
            }
        }
    }
}


function getResponse()
{
    $popup = new popuppro($_REQUEST['idPopup']);
    $setting = $popup->getSetting();

    if($setting['row']['active'] == 'Y') {
        if(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'checkemail' && !empty($_REQUEST['email'])) {
            if(filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
                if(!empty($setting['view']['props']['EMAIL_NOT_NEW']) && $setting['view']['props']['EMAIL_NOT_NEW'] == 'Y') {
                    $tmpStatus = !$popup->searchinMailList($_REQUEST['email'], $_REQUEST['idPopup']);
                    if($tmpStatus) {
                        $popup->filling();
                        registerUser($_REQUEST['email'], $setting['view']['props']);
                    }
                    return json_encode($tmpStatus);
                }
                else {

                    $popup->filling();
                    registerUser($_REQUEST['email'], $setting['view']['props']);
                    return json_encode(true);
                }
            }
            else {
                return json_encode(false);
            }
            return;
        }
        else {
            $res = '';
            $email = '';

            if(isset($_REQUEST['nothing']) && isset($_REQUEST['idPopup'])) {
                $popup->filling();
                return;
            }
            $isValidCouponSector = false;

            if(!empty($_REQUEST['idPopup']) && !empty($_REQUEST['sector'])) {
                $isValidCouponSector = Skyweb24\Popuppro\Tools::sheckUserSector($_REQUEST['idPopup'], $_REQUEST['sector']);
            }

            if(isset($_REQUEST['email'])) {
                $email = $_REQUEST['email'];
            }
            if(!isset($_REQUEST['addtotable'])) {
                if($isValidCouponSector) {
                    $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup'], $_REQUEST['resultText']);
                    return $res;
                }

                $popup->filling();
                return '';

            }
            if(isset($_REQUEST['addtotable']) && $_REQUEST['addtotable'] != 'Y') {
                if($isValidCouponSector) {

                    $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup'], $_REQUEST['resultText']);
                    return $res;
                }
                else {
                    return '';
                }
                $popup->filling();

            }
            if(isset($_REQUEST['email']) && isset($_REQUEST['idPopup']) && !empty($_REQUEST['type']) && $_REQUEST['type'] == 'addMail' && $_REQUEST['addtotable'] == 'Y') {

                $popup->insertToMailList($email, '', $_REQUEST['idPopup']);
            }
            else if(isset($_REQUEST['email']) && isset($_REQUEST['idPopup']) && $_REQUEST['addtotable'] == 'Y') {

                if($_REQUEST['unique'] == 'Y') {
                    if(!$popup->searchinMailList($email, $_REQUEST['idPopup'])) {
                        if($isValidCouponSector) {
                            $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup'], $_REQUEST['resultText']);
                            $popup->insertToMailList($email, '', $_REQUEST['idPopup']);
                        }
                        else {
                            return '';
                        }
                        $popup->filling();
                        return $res;
                    }
                    else {
                        return 'not_unique';
                    }
                }
                else {
                    if($isValidCouponSector) {
                        $res = $popup->getCoupon($_REQUEST['id'], $_REQUEST['avaliable'], $email, $_REQUEST['idPopup'], $_REQUEST['resultText']);
                        $popup->insertToMailList($email, '', $_REQUEST['idPopup']);
                    }
                    else {
                        return '';
                    }
                    $popup->filling();
                    return $res;
                }
            }
        }
    }
    else {
        return 'not active window';
    }
}

echo getResponse();





require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php"); ?>