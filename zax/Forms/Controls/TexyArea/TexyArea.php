<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html;

class TexyAreaInput extends BaseControl {

	protected $prototype;

	public function __construct($label) {
		parent::__construct($label);
		$this->prototype = Html::el('textarea');
	}

	public function getControlPrototype() {
		return $this->prototype;
	}

	public function handleWidget($widget) {
		$this->setView($widget);
		$this->redrawMeOnly();
	}

	public function viewLink() {}

	public function beforeRender() {
		$t = $this->getTemplate();
		$this->prototype->setHtml($this->getValue());
		$this->prototype->id = $this->getHtmlId();
		$this->prototype->name = $this->getHtmlName();
		$t->prototype = $this->prototype;
		$t->rich = $this->rich;
	}

}
