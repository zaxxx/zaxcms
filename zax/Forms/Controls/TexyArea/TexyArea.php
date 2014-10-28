<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html;

class TexyAreaInput extends BaseControl {

	protected $prototype;

	protected $divButtonsClasses = [];

	public function __construct($label) {
		parent::__construct($label);
		$this->prototype = Html::el('textarea')->addClass('texyarea');
	}

	public function setDivButtons($classes = []) {
		$this->divButtonsClasses = $classes;
		return $this;
	}

	public function getControlPrototype() {
		return $this->prototype;
	}

	public function handleWidget($widget) {
		$this->setView($widget);
		$this->redrawMeOnly(strtolower($widget));
	}

	public function viewLink() {}

	public function viewYoutube() {}

	public function viewImg() {}

	public function viewColumns() {}

	public function beforeRender() {
		$t = $this->getTemplate();
		$this->prototype->setHtml($this->getValue());
		$this->prototype->id = $this->getHtmlId();
		$this->prototype->name = $this->getHtmlName();
		$t->prototype = $this->prototype;
		$t->rich = $this->rich;
		$t->divButtonsClasses = $this->divButtonsClasses;
	}

}
