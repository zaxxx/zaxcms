<?php

namespace ZaxCMS\Components\LocaleSelect;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class LocaleSelectControl extends Control {

	/** @persistent */
	public $locale;

    public function viewDefault() {
	    $this->template->availableLocales = $this->getAvailableLocales();
	    $this->template->locale = $this->getLocale();
    }
    
    public function beforeRender() {

    }

}