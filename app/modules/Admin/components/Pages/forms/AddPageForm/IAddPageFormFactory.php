<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IAddPageFormFactory {

    /** @return AddPageFormControl */
    public function create();

}


trait TInjectAddPageFormFactory {

	protected $addPageFormFactory;

	public function injectAddPageFormFactory(IAddPageFormFactory $addPageFormFactory) {
		$this->addPageFormFactory = $addPageFormFactory;
	}

}

