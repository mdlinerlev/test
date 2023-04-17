<?php
namespace Jorique\Validators;

class FileValidator extends Validator {
	public $message = 'Некорректный файл';
	public $maxSize;
	public $allowEmpty = false;

	public function validate() {
		$attrVal = $this->_model->getAttribute($this->_attr);
		$counter = 0;


		foreach($attrVal['errors'] as $key => $error) {
			$this->checkFile($counter);
			$counter++;
		}



		/*if(is_array($attrVal)) {
			foreach($attrVal as $attr) {
				if(!$this->checkEmail($attr)) {
					$this->_model->addError($this->_attr, $this->message, $counter);
				}
				$counter++;
			}
		}
		else {
			if(!$this->checkEmail($attrVal)) {
				$this->_model->addError($this->_attr, $this->message, $counter);
			}
		}*/
	}

	/*private function checkFile($counter) {
		$error = $_FILES['error'][$counter];
		$originalName = $_FILES['name'][$counter];
		switch($error) {
			case UPLOAD_ERR_OK: break;
			case
		}
	}*/




	private function getSizeLimit() {
		$limit = ini_get('upload_max_filesize');
		$limit = self::sizeToBytes($limit);
		if($this->maxSize!==null && $limit>0 && $this->maxSize<$limit) {
			$limit=$this->maxSize;
		}
		if(isset($_POST['MAX_FILE_SIZE']) && $_POST['MAX_FILE_SIZE']>0 && $_POST['MAX_FILE_SIZE']<$limit)
			$limit = (int)$_POST['MAX_FILE_SIZE'];
		return $limit;
	}

	public static function sizeToBytes($sizeStr) {
		switch (strtolower(substr($sizeStr, -1))) {
			case 'm': return (int)$sizeStr * 1048576; // 1024 * 1024
			case 'k': return (int)$sizeStr * 1024; // 1024
			case 'g': return (int)$sizeStr * 1073741824; // 1024 * 1024 * 1024
			default: return (int)$sizeStr; // do nothing
		}
	}
}