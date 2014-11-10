<?php

namespace ZaxCMS\Components\Navigation;

interface IAddMenuItemFormFactory {

    /** @return AddMenuItemFormControl */
    public function create();

}


trait TInjectAddMenuItemFormFactory {

	/** @var IAddMenuItemFormFactory */
	protected $addMenuItemFormFactory;

	public function injectAddMenuItemFormFactory(IAddMenuItemFormFactory $addMenuItemFormFactory) {
		$this->addMenuItemFormFactory = $addMenuItemFormFactory;
	}

}

