<?php
namespace Jorique\Validators;

class Model {

	private $_rules = array();
	private $_attributes = array();
	private $_errors = array();
	public $labels = array();

	public function __construct($rules) {
		$this->_rules = $rules;
	}

	public function setAttributes($attributes) {
		$this->_attributes = $attributes;
	}

	public function getAttribute($attrName) {
		if(preg_match('#^(.+)((\[.+\])*)(\[\])?$#Uusi', $attrName, $matches)) {
			$eval = 'return $this->_attributes['.$matches[1].']'.$matches[2].';';
			if($attr = @eval($eval)) {
				return $attr;
			}
			else {
				return false;
			}
		}
		return false;
	}

	public function createValidator($name, $attr, $options) {
		$validatorName = __NAMESPACE__.'\\'.$name.'Validator';
		$validator = new $validatorName($this, $attr);
		if(!empty($options)) {
			foreach($options as $optionName=>$optionValue) {
				$validator->$optionName = $optionValue;
			}
		}
		$validator->validate();
	}

	public function validate() {
		foreach($this->_rules as $rule) {
			$ruleAttrs = preg_split('#\s*,\s*#', $rule[0]);
			foreach($ruleAttrs as $ruleAttr) {
				$this->createValidator($rule[1], $ruleAttr, array_slice($rule, 2));
			}
		}
	}

	public function addError($attr, $message, $position=0) {
		$fullAttr = $attr.'|'.$position;
		if(!array_key_exists($fullAttr, $this->_errors)) {
			if(isset($this->labels[$attr])) {
				$message = str_replace('{attr}', $this->labels[$attr], $message);
			}
			$this->_errors[$fullAttr] = $message;
		}
	}

	public function hasErrors() {
		return !empty($this->_errors);
	}

	public function getErrors() {
		return $this->_errors;
	}

	public function getFirstValueOfAttribute($attrName) {
		$attrValue = $this->getAttribute($attrName);
		if(is_array($attrValue)) {
			foreach($attrValue as $val) {
				if(trim($val)) return $val;
			}
			return false;
		}
		return $attrValue;
	}
}