<?php

namespace ZaxCMS\Components\LocaleSelect;

interface ILocaleSelectFactory {

    /** @return LocaleSelectControl */
    public function create();

}


trait TInjectLocaleSelectFactory {

	protected $localeSelectFactory;

	public function injectLocaleSelectFactory(ILocaleSelectFactory $localeSelectFactory) {
		$this->localeSelectFactory = $localeSelectFactory;
	}

}

