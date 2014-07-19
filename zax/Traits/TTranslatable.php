<?php

namespace Zax\Traits;
use Zax,
	Kdyby,
	Nette;

/**
 * Trait TTranslatable
 *
 * Some common translatable behavior
 *
 * @package Zax\Traits
 */
trait TTranslatable {

	/**
	 * @var string|NULL
	 */
	//protected $locale;

	/**
	 * @var Kdyby\Translation\Translator
	 */
	protected $translator;

	public function injectTranslator(Kdyby\Translation\Translator $translator) {
		$this->translator = $translator;
	}

	/**
	 * @return NULL|string
	 */
	public function getLocale() {
		if($this->locale === NULL) {
			$this->locale = $this->translator->getLocale();
		}

		if(!in_array($this->locale, $this->translator->getAvailableLocales())) {
			$this->locale = $this->translator->getLocale();
		}

		return $this->locale;
	}

	public function getAvailableLocales() {
		return $this->translator->getAvailableLocales();
	}

}