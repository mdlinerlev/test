<?php
namespace Jorique\Validators;

class CaptchaValidator extends Validator {
	public $message = 'Неверно введены символы с картинки';

	public function validate() {
		$attrVal = $this->_model->getAttribute($this->_attr);
		$counter = 0;
		if(is_array($attrVal)) {
			foreach($attrVal as $attr) {
				if(!$this->checkCaptcha($attr)) {
					$this->_model->addError($this->_attr, $this->message, $counter);
				}
				$counter++;
			}
		}
		else {
			if(!$this->checkCaptcha($attrVal)) {
				$this->_model->addError($this->_attr, $this->message, $counter);
			}
		}
	}

	private function checkCaptcha($attr) {
		/*global $APPLICATION;
		return $APPLICATION->CaptchaCheckCode($attr, $_REQUEST["captcha_sid"]);*/

		global $DB;
		$res = $DB->Query("SELECT CODE FROM b_captcha WHERE ID = '".$DB->ForSQL($_REQUEST["captcha_sid"],32)."'");
		$ar = $res->Fetch();
		if ($ar["CODE"]!=strtoupper($_REQUEST["captcha_word"]))	{
			return false;
		}
		return true;
	}
}