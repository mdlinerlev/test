<?php

namespace Jorique\Components;

/**
 * @var array $arParams
 * @var ProductImport $this
 * @global \CMain $APPLICATION
 */

try {
	$this->prepareParams();
	$this->checkParams();

	if($this->arParams['IS_CLI']) {
		while(ob_get_level() && ob_end_clean());
		$APPLICATION->RestartBuffer();
		$this->import($this->arParams['XML_PATH']);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/importMail3.txt', print_r(date('d.m.Y H:i:s' ). $this->getMessages() .PHP_EOL, 1), 8);
		echo $this->getMessages();
		die;
	}
	else {
        if($_FILES['goodsXml']['tmp_name']) {
            $path = $_FILES['goodsXml']['tmp_name'];
            $this->checkXml($path);
            if($this->loadXml($path)) {
                $this->startExec();
            }
            //$this->import($path);
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/importMail2.txt', print_r(date('d.m.Y H:i:s' ). $this->getMessages() .PHP_EOL, 1), 8);
            $arResult['MESSAGES'] = $this->getMessages();
        }
		$this->includeComponentTemplate();
	}
}
catch(\Exception $e) {
	if($arParams['IS_CLI'] && php_sapi_name() === 'cli') {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/importMail4.txt', print_r(date('d.m.Y H:i:s' ). $e->getMessages() .PHP_EOL, 1), 8);
		echo $e->getMessage().PHP_EOL;
	}
	else {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/logs/importMail5.txt', print_r(date('d.m.Y H:i:s' ).PHP_EOL, 1), 8);
		ShowError($e->getMessage());
	}
}