<?php

namespace Zax\Application\UI;
use Zax,
	Nette,
	Nette\Application\UI as NetteUI,
	Kdyby;

/**
 * Class FormFactory
 *
 * Factory for forms translated using Kdyby/Translation
 *
 * @package Zax\Application\UI
 */
class FormFactory extends Nette\Object {

	/** @var Kdyby\Translation\Translator */
	protected $translator;

	public function __construct(Kdyby\Translation\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * Translated form factory
	 *
	 * @return Zax\Application\UI\Form
	 */
	public function create() {
		$f = new Form;
		$f->setTranslator($this->translator);
		return $f;
	}

}