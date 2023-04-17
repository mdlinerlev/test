<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Web\Cookie,
    Bitrix\Main\Context,
    \Skyweb24\Popuppro\CrmServer,
    Bitrix\Main\UserConsent\Internals\AgreementTable,
    Bitrix\Main\UserConsent\Agreement;

\Bitrix\Main\Loader::IncludeModule("iblock");
\Bitrix\Main\Loader::IncludeModule("skyweb24.popuppro");


Loc::loadMessages(__FILE__);


class Skyweb24PopupProComponent extends \CBitrixComponent
{

    public function onPrepareComponentParams($params)
    {
        if (empty($params['ID_POPUP'])) {
            $params['ID_POPUP'] = 1;
        }
        $params['REFERER'] = $_SERVER['HTTP_REFERER'];
        return $params;
    }

    private function getPathTemplate(){
        echo $this->GetPath() . '/templates/' . $this->getTemplateName();
    }

    public function executeComponent()
    {

        global $APPLICATION;
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        switch($this->arParams['MODE']){
            // case "WS_TEMPLATE":
            // case "TEMPLATE": return $this->viewPreview();
            case "GET_PATH": return $this->getPathTemplate();
            //default: return $this->view();
        }


        if (!empty($this->arParams['MODE'])) {
            $this->arResult = $this->getValueTemplate($this->arParams['MODE']);
        }
        else {

            // old class
            $popup = new popuppro;

            // new class
            $popup_n = new \Skyweb24\Popuppro\Popup($this->arParams['ID_POPUP']);
            $lead = new \Skyweb24\Popuppro\Lead($popup_n);

            $tmpRes = $popup->getComponentResult($this->arParams['ID_POPUP']);
            $tmpRes = $this->setPersonalize($tmpRes);

            $popupSetting = $popup->getSetting($this->arParams['ID_POPUP']);

            if ($popupSetting['view']['type'] == 'contact' ||
                $popupSetting['view']['type'] == 'discount') {

                $contactArr = ['NAME' => '', 'EMAIL' => '', 'LASTNAME' => '', 'PHONE' => '', 'DESCRIPTION' => ''];
                global $USER;
                if ($USER->IsAuthorized()) {
                    $rsUser = CUser::GetByID($USER->GetID());
                    $arUser = $rsUser->Fetch();
                    $contactArr['EMAIL'] = $arUser['EMAIL'];
                    $contactArr['NAME'] = $arUser['NAME'];
                    $contactArr['LASTNAME'] = $arUser['LAST_NAME'];
                    $contactArr['PHONE'] = $arUser['PERSONAL_PHONE'];
                }

                $errors = [];
                $cenderData = false;
                $forSendDataArr = ['NAME_FORM' => $popupSetting['row']['name']];
                foreach ($contactArr as $keyContact => $nextContact) {
                    if (!empty($tmpRes[$keyContact . '_SHOW']) && $tmpRes[$keyContact . '_SHOW'] == 'Y') {
                        $tmpRes[$keyContact] = $nextContact;
                        $reqValue = $request->get($keyContact);

                        if (isset($reqValue)) {
                            $cenderData = true;
                            $tmpRes[$keyContact] = $reqValue;
                            if (empty($reqValue) && $tmpRes[$keyContact . '_REQUIRED'] == 'Y') {
                                $errors[$keyContact] = $tmpRes[$keyContact . '_TITLE'];
                                $tmpRes[$keyContact] = '';
                            }
                            else {
                                if ($keyContact == 'EMAIL' && !filter_var($reqValue, FILTER_VALIDATE_EMAIL) && !empty($reqValue)) {
                                    $errors[$keyContact] = $tmpRes[$keyContact . '_TITLE'];
                                }
                                else {
                                    if (LANG_CHARSET == 'windows-1251') {
                                        $reqValue = mb_convert_encoding($reqValue, "windows-1251", "utf-8");
                                        $tmpRes[$keyContact] = $reqValue;
                                    }
                                    $forSendDataArr[$keyContact] = $reqValue;
                                }
                            }
                        }
                    }
                }

                if (count($errors) > 0) {
                    $tmpRes['ERRORS'] = $errors;
                }
                elseif ($cenderData) {

                    $lead->save($request);

                    // other
                    if (!empty($popupSetting['contact'])) {

                        $postTitle = ['NAME_TITLE', 'EMAIL_TITLE', 'PHONE_TITLE', 'DESCRIPTION_TITLE', 'LASTNAME_TITLE'];
                        foreach ($postTitle as $nextTitle) {
                            $forSendDataArr[$nextTitle] = '';
                            if (!empty($popupSetting['view']['props'][$nextTitle])) {
                                $forSendDataArr[$nextTitle] = $popupSetting['view']['props'][$nextTitle];
                            }
                        }

                        if (!empty($popupSetting['contact']['emailList']) && $popupSetting['contact']['emailList'] == 'Y') {
                            if (!empty($forSendDataArr['EMAIL'])) {
                                $popup->insertToMailList($forSendDataArr['EMAIL'], $forSendDataArr['NAME'], $this->arParams['ID_POPUP']);
                            }
                            if (!empty($forSendDataArr['PHONE']) && file_exists($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/sender/lib/recipient/type.php')) {
                                $popup->insertToMailList(preg_replace("/[^0-9]/", '', $forSendDataArr['PHONE']), $forSendDataArr['NAME'], $this->arParams['ID_POPUP']);
                            }
                        }

                        if (!empty($popupSetting['contact']['iblock'])) {
                            $insertArr = [
                                'IBLOCK_ID'    => $popupSetting['contact']['iblock'],
                                'NAME'         => array_shift($forSendDataArr),
                                'PREVIEW_TEXT' => $tmpInfo
                            ];
                            foreach ($forSendDataArr as $keyData => $nextData) {
                                if (!empty($tmpRes[$keyData . '_TITLE'])) {
                                    $insertArr['PREVIEW_TEXT'] .= $tmpRes[$keyData . '_TITLE'] . ' - ' . $nextData . PHP_EOL;
                                    $propId = Skyweb24\Popuppro\Tools::returnPropId($popupSetting['contact']['iblock'], $keyData, $tmpRes[$keyData . '_TITLE']);
                                    $insertArr['PROPERTY_VALUES'][$propId] = $nextData;
                                }
                            }
                            $el = new CIBlockElement;
                            $el->Add($insertArr);
                        }



                        if ($tmpRes['USE_CONSENT_SHOW'] == 'Y') {
                            $dataArr = [];
                            if (!empty($this->arParams['REFERER'])) {
                                $dataArr['URL'] = $this->arParams['REFERER'];
                            }
                            if (class_exists('Bitrix\Main\UserConsent\Agreement')) {
                                \Bitrix\Main\UserConsent\Consent::addByContext(
                                    $popupSetting['view']['props']['CONSENT_LIST'],
                                    'skyweb24/popuppro',
                                    $popupSetting['row']['id'],
                                    $dataArr
                                );
                            }
                        }
                        global $USER;

                        if (
                            !empty($popupSetting['contact']['register'])
                            && $popupSetting['contact']['register'] == 'Y'
                            && !empty($forSendDataArr['EMAIL'])
                            && !$USER->IsAuthorized()
                        ) {
                            $tmpUsers = CUser::GetList(($by = "ID"), ($order = "desc"), ['EMAIL' => $forSendDataArr['EMAIL']]);
                            $tmpRes['REG_RES'][] = !$tmpUsers->Fetch();
                            if (!$tmpUsers->Fetch()) {
                                $res = $USER->SimpleRegister($forSendDataArr['EMAIL']);
                            }
                        }
                    }

                    $popup->setStatistic($popupSetting['row']['id'], 1, 'stat_action');
                    $tmpRes['SUCCESS'] = 'Y';

                    $APPLICATION->set_cookie("skyweb24PopupFilling_" . $this->arParams['ID_POPUP'], 'Y', time() + 864000000, "/");
                }
            }
            elseif ($popupSetting['view']['type'] == "coupon") {
                foreach ($popupSetting['view']['props'] as $key => $prop) {
                    if ($key != 'IMG_1_SRC' && $key != 'EMAIL_PLACEHOLDER' && $key != "THEME") {
                        $tmpRes[$key] = $prop;
                    }
                }
            }
            elseif ($popupSetting['view']['type'] == 'age') {
                $reqValue = $request->get('checked');
                if (isset($reqValue) && $reqValue == 'Y') {
                    $APPLICATION->set_cookie("skyweb24PopupFilling_" . $this->arParams['ID_POPUP'], 'Y', time() + 864000000, "/");
                    die();
                }
            }
            $this->arResult = $tmpRes;

        }

        $timer_array = ['banner', 'video', 'action', 'contact', 'html', 'coupon', 'roulette', 'discount', "thimbles"];

        if (in_array($popupSetting['view']['type'], $timer_array) && !empty($popupSetting['timer']['date'])) {
            $this->arResult['TIMER'] = $popupSetting['timer']['enabled'];
            $this->arResult['TIMER_TEXT'] = $popupSetting['timer']['text'];
            if ($this->arResult['TIMER'] == 'Y') {
                $format = 'd.m.Y H:i:s';
                $unixtime = DateTime::createFromFormat($format, $popupSetting['timer']['date']);

                // daily timer
                if ($popupSetting['timer']['daily'] == 'Y' && !empty($popupSetting['timer']['daily_time'])) {
                    $newTimerDate = date('d.m.Y') . ' ' . $popupSetting['timer']['daily_time'] . ':00';
                    $unixtime = DateTime::createFromFormat($format, $newTimerDate);
                }
                // daily timer

                $nowtime = time();
                $nowtime = date_create();
                $unixtime = $nowtime->diff($unixtime);
                $unixtime = $unixtime->format('%a:%H:%I:%S');
                $this->arResult['TIMER_DATE'] = $unixtime;
                $this->arResult['TIMER_LEFT'] = $popupSetting['timer']['left'];
                $this->arResult['TIMER_RIGHT'] = $popupSetting['timer']['right'];
                $this->arResult['TIMER_TOP'] = $popupSetting['timer']['top'];
                $this->arResult['TIMER_BOTTOM'] = $popupSetting['timer']['bottom'];
            }

        }

        if ($popupSetting['view']['type'] == 'roulette') {
            $this->arResult['ELEMENTS'] = $popupSetting['roulett'];
            unset($this->arResult['ELEMENTS']['count']);
            $this->arResult['ELEMENTS_COUNT'] = $popupSetting['roulett']['count'];
            $this->arResult['SECTOR'] = Skyweb24\Popuppro\Tools::getRandSectorRoulette($this->arParams['ID_POPUP']);
        }

        if ($popupSetting['view']['type'] == 'thimbles') {
            $this->arResult['LIST_WINS'] = \Bitrix\Main\Web\Json::encode($popupSetting['thimbles']['list_wins']);
        }


        //consent for
        if (
            (
                !empty($popupSetting['view']['type']) &&
                ($popupSetting['view']['type'] == 'contact' || $popupSetting['view']['type'] == 'discount')
            ) ||
            $this->arParams['MODE'] == 'TEMPLATE'
        ) {

            if (!empty($popup)) {
                $agreements = $popup->getAgreements(['button_caption' => $popupSetting['view']['props']['BUTTON_TEXT']]);
                $this->arResult['AGREEMENTS'] = $agreements;
                if (!empty($popupSetting['view']['props']['CONSENT_LIST'])) {
                    $this->arResult['CONSENT_LIST'] = $agreements[$popupSetting['view']['props']['CONSENT_LIST']];
                    $this->arResult['CONSENT_ID'] = $popupSetting['view']['props']['CONSENT_LIST'];
                }
            }
            else {


                $popup = new popuppro;
                $agreements = $popup->getAgreements(['button_caption' => '#BUTTON_TEXT#']);
                $this->arResult['AGREEMENTS'] = $agreements;
                $this->arResult['CONSENT_ID'] = 1;
            }
        }
        //e. o. consent for


        if ($this->arResult['TEMPLATE_NAME']) {
            $exTemplateName = explode("_", $this->arResult['TEMPLATE_NAME']);
            $templateType = $exTemplateName[0];
            $templateName = $exTemplateName[1];

            if ($templateName == "custom") {
                $templateCustomId = $exTemplateName[2];
                $templateCustom = $popup->getCustomTemplate($templateCustomId);
                $templateCustom['template'] = unserialize($templateCustom['template']);
                $templateName = $templateCustom['template']['template_type_parent'];
            }

            $this->arResult['theme_color'] = \Skyweb24\Popuppro\Themes::getTheme(
                $templateType,
                $templateName,
                $this->arResult['THEME']
            );
        }

        $this->arResult['popupId'] = $this->arParams['ID_POPUP'];

        //antispam
        $_SESSION['skyweb24_popup' . $this->arParams['ID_POPUP']] = time();
        $_SESSION['SKYWEB24_SESS_GUEST_NEW'] = "Y";

        $this->IncludeComponentTemplate($componentPage);


    }

    private function setPersonalize($res)
    {
        if (empty($res)) return false;
        $tmpReplace = Skyweb24\Popuppro\Tools::getPersonalizationValues();

        $newRes = [];
        foreach ($res as $keyRow => $nextRow) {
            foreach ($tmpReplace as $keyRep => $nextRep) {
                $nextRow = str_replace('#' . $keyRep . '#', $nextRep, $nextRow);
            }
            $newRes[$keyRow] = $nextRow;
        }

        return $newRes;
    }

    private function getValueTemplate()
    {
        if ($this->arParams['MODE'] == "WS_TEMPLATE") {
            return [
                'WS_TITLE'       => '#WS_TITLE#',
                'WS_DESCRIPTION' => '#WS_DESCRIPTION#',
                'THEME'          => "#THEME#"
            ];
        }

        return [
            'TITLE'             => '#TITLE#',
            'SUBTITLE'          => '#SUBTITLE#',
            'CONTENT'           => '#CONTENT#',
            'LINK_TEXT'         => '#LINK_TEXT#',
            'LINK_HREF'         => '#LINK_HREF#',
            'IMG_1_SRC'         => '#IMG_1_SRC#',
            'IMG_2_SRC'         => '#IMG_2_SRC#',
            'IMG_DEFAULT'      => '#IMG_DEFAULT#',
            'IMG_WIN'      => '#IMG_WIN#',
            'IMG_DEFEAT'      => '#IMG_DEFEAT#',
            'LINK_VIDEO'        => '#LINK_VIDEO#',
            'VIDEO_SIMILAR'     => '#VIDEO_SIMILAR#',
            'VIDEO_AUTOPLAY'    => '#VIDEO_AUTOPLAY#',
            'COLOR_BG'          => '#COLOR_BG#',
            'ID_VK'             => '#ID_VK#',
            'ID_FB'             => '#ID_FB#',
            'ID_INST'           => '#ID_INST#',
            'ID_ODNKL'          => '#ID_ODNKL#',
            'ID_FACEBOOK'       => '#ID_FACEBOOK#',
            'ID_TWITTER'        => '#ID_TWITTER#',
            'ID_YOUTUBE'        => '#ID_YOUTUBE#',
            'TYPE_VIEW'         => '#TYPE_VIEW#',
            'BUTTON_TEXT'       => '#BUTTON_TEXT#',
            'COLLECT'           => '#COLLECT#',
            'VK'                => '#VK#',
            'FB'                => '#FB#',
            'TEXTAREA'          => '#TEXTAREA#',
            'EMAIL_SHOW'        => '#EMAIL_SHOW#',
            'EMAIL_REQUIRED'    => '#EMAIL_REQUIRED#',
            'EMAIL_TITLE'       => '#EMAIL_TITLE#',
            'EMAIL_PLACEHOLDER' => '#EMAIL_PLACEHOLDER#',
            'GOOGLE_FONT'       => '#GOOGLE_FONT#',
            'NAME_SHOW'         => '#NAME_SHOW#',
            'NAME_REQUIRED'     => '#NAME_REQUIRED#',
            'NAME_TITLE'        => '#NAME_TITLE#',
            'NAME_PLACEHOLDER'  => '#NAME_PLACEHOLDER#',

            'BUTTON_ANIMATION'      => '#BUTTON_ANIMATION#',
            'BUTTON_ANIMATION_TIME' => '#BUTTON_ANIMATION_TIME#',

            'SHOW_ANIMATION' => '#SHOW_ANIMATION#',

            'LASTNAME_SHOW'        => '#LASTNAME_SHOW#',
            'LASTNAME_REQUIRED'    => '#LASTNAME_REQUIRED#',
            'LASTNAME_TITLE'       => '#LASTNAME_TITLE#',
            'LASTNAME_PLACEHOLDER' => '#LASTNAME_PLACEHOLDER#',

            'PHONE_SHOW'        => '#PHONE_SHOW#',
            'PHONE_REQUIRED'    => '#PHONE_REQUIRED#',
            'PHONE_TITLE'       => '#PHONE_TITLE#',
            'PHONE_PLACEHOLDER' => '#PHONE_PLACEHOLDER#',

            'USE_CONSENT_SHOW' => '#USE_CONSENT_SHOW#',
            'CONSENT_LIST'     => '#CONSENT_LIST#',

            'BUTTON_TEXT_Y'           => '#BUTTON_TEXT_Y#',
            'BUTTON_TEXT_N'           => '#BUTTON_TEXT_N#',
            'DISCOUNT_MASK'           => '#DISCOUNT_MASK#',
            'DESCRIPTION_SHOW'        => '#DESCRIPTION_SHOW#',
            'DESCRIPTION_REQUIRED'    => '#DESCRIPTION_REQUIRED#',
            'DESCRIPTION_TITLE'       => '#DESCRIPTION_TITLE#',
            'DESCRIPTION_PLACEHOLDER' => '#DESCRIPTION_PLACEHOLDER#',
            'SOC_VK'                  => '#SOC_VK#',
            'SOC_OD'                  => '#SOC_OD#',
            'SOC_FB'                  => '#SOC_FB#',
            'SOC_TW'                  => '#SOC_TW#',
            'SOC_GP'                  => '#SOC_GP#',
            'SOC_MR'                  => '#SOC_MR#',
            'SOC_TE'                  => '#SOC_TE#',
            'SOC_VI'                  => '#SOC_VE#',
            'SOC_WA'                  => '#SOC_WA#',

            'TIMER'          => '#timer_enable#',
            'TIMER_TEXT'     => '#timer_text#',
            'TIMER_DATE'     => '#timer_date#',
            'TIMER_LEFT'     => '#timer_left#',
            'TIMER_RIGHT'    => '#timer_right#',
            'TIMER_TOP'      => '#timer_top#',
            'TIMER_BOTTOM'   => '#timer_bottom#',
            'CLOSE_TEXTAREA' => '#CLOSE_TEXTAREA#',
            'CLOSE_TEXTBOX'  => 'Y',
            'THEME'          => "#THEME#",
            "LIST_WINS" => "#LIST_WINS#"
        ];


    }


}
