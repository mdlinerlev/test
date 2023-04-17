<?php
$to = '';
//    $to .= "stranger.2k6@gmail.com,";
switch ($site = $_SERVER['SERVER_NAME']) {
    case "belwooddoors.by":
        $to = "bwdru@belwooddoors.by";
        break;
    case "belwooddoors.ru":
        $to = "bwdru@belwooddoors.by";
        break;
    case "belwood.kz":
        $to = "bwdru@belwooddoors.by";
        break;
}

if ($to && isset($_POST['phone'])) {
    $message = "";
    $message .= "Позвоните по номеру:" . htmlspecialchars($_POST['phone']);
    if ($_POST['week_day']) {
        $message .= " В удобное мне время: " . $_POST['week_day'] . ' ' . htmlspecialchars($_POST['time']);
    }
    $message = strip_tags($message);
    $headers = "Content-Type: text/html; charset=UTF-8";
    if (trim(htmlspecialchars($_POST['phone']))) {
        if (mail($to, "Запрос звонка с сайта " . $site, $message, $headers)) {
            session_start();
            $_SESSION['popup_email_send'] = true;
		} 
		if ($_SESSION['popup_email_send'] == true) {
			$result = "Отправлено";
		} else {
			$result = "Не отправлено";
		}
		$file = 'log.txt';
		$current = file_get_contents($file);
		$current .= date('Y-m-d H:i:s') . " | " . $_POST['phone'] . " | " . $result . "\n";
		file_put_contents($file, $current);

    }
}
if ($to && isset($_POST['content'])) {
    $message = '';
    foreach ($_POST['content'] as $k => $v) {
        $message .= '<b>' . $k . '</b>: ' . $v . "<br>";
    }
    $message = strip_tags($message, '<b><br>');
    $headers = "Content-Type: text/html; charset=UTF-8";
    if (trim(htmlspecialchars($message))) {
        if (mail($to, "Запрос с формы сайта " . $site, $message, $headers)) {
            session_start();
            $_SESSION['popup_email_send'] = true;
        }
    }
}
