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
    }
    
    public function beforeRender() {
	    $this->template->locale = $this->getLocale();
    }

	public function beforeRenderPresenterLocale() {
		$this->template->locale = $this->presenter->getLocale();
	}

	public function loadState(array $params) {
		parent::loadState($params);

		if(isset($params['locale'])) {
			$this->locale = $params['locale'];
		}
	}

}