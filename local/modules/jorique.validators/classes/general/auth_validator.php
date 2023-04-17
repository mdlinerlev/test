<?php
namespace Jorique\Validators;

class AuthValidator extends Validator {
	public $message = 'Неверный логин или пароль';
	public $password;

	public function validate() {
		$attrVal = $this->_model->getAttribute($this->_attr);
		$counter = 0;
		if(is_array($attrVal)) {
			foreach($attrVal as $attr) {
				if(!$this->checkAuth($attr)) {
					$this->_model->addError($this->_attr, $this->message, $counter);
				}
				$counter++;
			}
		}
		else {
			if(!$this->checkAuth($attrVal)) {
				$this->_model->addError($this->_attr, $this->message, $counter);
			}
		}
	}

	private function checkAuth($attr) {
		$userObject = new \CUser;
		if($userObject->Login($attr, $this->password)===true) {
			$userObject->Logout();
			return true;
		}
		return false;
	}
}