<?php

namespace Zax\Application\UI;
use Zax,
	Nette,
	Nette\Application\UI as NetteUI,
	Kdyby;

/**
 * Class FormFactory
 *
 * Default Form factory
 *
 * @package Zax\Application\UI
 */
class FormFactory extends Nette\Object implements IFormFactory {

	protected $defaultClass;

	/** @var Kdyby\Translation\Translator */
	protected $translator;

	/** @var Zax\Html\Icons\IIcons */
	protected $icons;

	public function __construct($defaultClass = NULL,
								Kdyby\Translation\Translator $translator,
								Zax\Html\Icons\IIcons $icons) {
		$this->defaultClass = $defaultClass;
		$this->translator = $translator;
		$this->icons = $icons;
	}

	/**
	 * Translated form factory
	 *
	 * @return Nette\Forms\Form
	 * @throws Nette\InvalidArgumentException
	 */
	public function create() {
		$f = $this->defaultClass === NULL ? new Form : new $this->defaultClass;
		if(!$f instanceof Nette\Forms\Form) {
			throw new Nette\InvalidArgumentException("Class '$class' is not a valid Nette form.");
		}
		$f->setTranslator($this->translator);
		if($f instanceof Zax\Application\UI\Form) {
			$f->setIcons($this->icons);
		}
		return $f;
	}

}
