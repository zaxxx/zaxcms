<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html,
	Nette\Forms\Controls\BaseControl;

class ArrayTextAreaControl extends Nette\Forms\Controls\TextBase {

	protected $value = [];

	protected $keyValDelimiter;

	public function __construct($label = NULL, $keyValDelimiter = NULL) {
		parent::__construct($label);
		$this->control->setName('textarea');
		$this->keyValDelimiter = $keyValDelimiter === NULL ? NULL : (string) $keyValDelimiter;
	}

	public function loadHttpData() {
		$this->value = $this->textToArray($this->getHttpData(Form::DATA_TEXT));
	}

	protected function arrayToText($arr) {
		$processed = [];
		if($this->keyValDelimiter === NULL) {
			foreach($arr as $val) {
				$processed[] = $val;
			}
		} else {
			foreach($arr as $key => $val) {
				$processed[] = $key . $this->keyValDelimiter . $val;
			}
		}

		return implode(PHP_EOL, $processed);
	}

	protected function textToArray($text) {
		if(strlen($text) === 0) {
			return [];
		}

		$processed = [];
		$lines = explode("\n", preg_replace('~\r\n?~', "\n", $text)); // explode(PHP_EOL, $text);
		if($this->keyValDelimiter === NULL) {
			$processed = $lines;
		} else {
			foreach($lines as $pair) {
				list($key, $value) = explode($this->keyValDelimiter, $pair);
				$processed[trim($key)] = trim($value);
			}
		}

		return $processed;
	}

	public function setValue($value) {
		$this->value = (array)$value;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function getControl() {
		$control = parent::getControl();
		$control->setText($this->arrayToText($this->value));
		return $control;
	}
}
 