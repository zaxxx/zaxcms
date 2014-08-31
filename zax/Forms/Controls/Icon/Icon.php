<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html;

class IconInput extends BaseControl {

	/** @persistent */
	public $selectedValue;

	public function getValue() {
		return $this->selectedValue;
	}

	public function setValue($value) {
		$this->selectedValue = $value;
		return $this;
	}

	public function beforeRender() {
		$t = $this->getTemplate();
		$t->value = $this->getValue();
		$t->htmlName = $this->getHtmlName();
		$t->htmlId = $this->getHtmlId();
		$t->rich = $this->rich;
	}

	public function handleSelectIcon($icon) {
		$this->setValue($icon);
		$this->redrawMeOnly();
	}

	public function loadState(array $params) {
		parent::loadState($params);

		if(isset($params['selectedValue'])) {
			$this->setValue($params['selectedValue']);
		}
	}

}