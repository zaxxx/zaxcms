<?php

namespace Zax\Forms\Controls;
use Nette,
	Nette\Forms\Helpers,
	Nette\Forms\Form,
	Nette\Utils\Html,
	Zax\Application\UI\Control;

abstract class BaseControl extends Control implements Nette\Forms\IControl {

	protected $errors = [];

	protected $omitted = FALSE;

	protected $disabled = FALSE;

	protected $rules;

	protected $caption;

	protected $value;

	protected $label;

	public function __construct($caption = NULL) {
		$this->monitor('Nette\Application\UI\Presenter');
		parent::__construct();
		$this->caption = $caption;
		$this->label = Html::el('label');
		$this->rules = new Nette\Forms\Rules($this);
	}

	public function getLocale() {
		return $this->lookup('Nette\Application\UI\Control')->getLocale();
	}

	public function getAvailableLocales() {
		return $this->lookup('Nette\Application\UI\Control')->getAvailableLocales();
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function validate() {
		if($this->isDisabled())
			return;
		$this->errors = [];
		return $this->rules->validate();
	}

	public function getErrors() {
		return $this->errors;
	}

	public function hasErrors() {
		return count($this->errors) > 0;
	}

	public function isOmitted() {
		return $this->omitted;
	}

	public function translate($s, $count = NULL) {
		return $this->lookup('Nette\Application\UI\Control')->translator->translate($s, $count);
	}

	public function getForm() {
		return $this->lookup('Nette\Forms\Form');
	}

	public function loadHttpData() {
		$this->setValue($this->getHttpData(Nette\Forms\Form::DATA_TEXT));
	}

	public function getHttpData($type, $htmlTail = NULL) {
		return $this->getForm()->getHttpData($type, $this->getHtmlName() . $htmlTail);
	}

	public function getHtmlName() {
		return Nette\Forms\Helpers::generateHtmlName($this->lookupPath('Nette\Forms\Form'));
	}

	public function isRequired() {
		return $this->rules->isRequired();
	}

	public function getControlPrototype() {
		return $this->getControl();
	}

	public function setOption() {}

	public function getOption() {}

	public function getLabel($caption = NULL) {
		$label = clone $this->label;
		$label->setText($this->translate($caption === NULL ? $this->caption : $caption));
		return $label;
	}

	/**
	 * Supress redrawing other components to prevent data loss in form
	 *
	 * @param $presenter
	 */
	public function attached($presenter) {
		foreach($presenter->getComponents(TRUE, 'Nette\Application\UI\IRenderable') as $component) {
			if(!$component instanceof BaseControl)
				$component->redrawControl(NULL, FALSE);
		}
		$this->redrawControl();
	}

	public function getControl() {
		$this->setOption('rendered', TRUE);

		ob_start();
		try {
			$this->run();
		} catch(\Exception $e) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

}