<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html,
	Nette\Forms\Controls\BaseControl;

class ArrayTextAreaControl extends Nette\Forms\Controls\TextBase {

	protected $value = [];

	public function __construct($label = NULL) {
		parent::__construct($label);
		$this->control->setName('textarea');
	}

	public function loadHttpData() {
		$this->value = $this->textToArray($this->getHttpData(Form::DATA_TEXT));
	}

	protected function arrayToText($arr) {
		$pairs = [];
		foreach($arr as $key => $val) {
			$pairs[] = $key . '=>' . $val;
		}
		return implode(PHP_EOL, $pairs);
	}

	protected function textToArray($text) {
		$data = [];
		$pairs = explode(PHP_EOL, $text);
		foreach($pairs as $pair) {
			list($key, $value) = explode('=>', $pair);
			$data[trim($key)] = trim($value);
		}
		return $data;
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
 