<?php
//task1090
define("LANGUAGE_ID", "ru");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("sale");
CModule::IncludeModule("iblock");

//При сессии больше 5 минут выводить окно
if(empty($_SESSION['popup_closetab_session_time'])){
    $_SESSION['popup_closetab_session_time'] = time();
    $_SESSION['popup_closetab_session'] = 0;
}

$content = '';
if((time() - $_SESSION['popup_closetab_session_time']) > 300 and $_SESSION['popup_closetab_session'] == 0){
    $_SESSION['popup_closetab_session'] = 1;
    $content = '<img src="'.SITE_TEMPLATE_PATH.'/assets/images/operator.png" />
    <p>Вы находитесь на сайте 5 минут. Возможно, вам нужна консультация?</p>
    <div>
        <a class="header-text-item__callback greenbutton" id="callback_pc">
            <span class="header-text-item__callback-inner">заказать звонок</span>
        </a>
    </div>';
}

echo $content;