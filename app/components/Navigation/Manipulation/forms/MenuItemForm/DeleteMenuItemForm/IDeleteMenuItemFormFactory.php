<?php

namespace ZaxCMS\Components\Navigation;

interface IDeleteMenuItemFormFactory {

    /** @return DeleteMenuItemFormControl */
    public function create();

}


trait TInjectDeleteMenuItemFormFactory {

	/** @var IDeleteMenuItemFormFactory */
	protected $deleteMenuItemFormFactory;

	public function injectDeleteMenuItemFormFactory(IDeleteMenuItemFormFactory $deleteMenuItemFormFactory) {
		$this->deleteMenuItemFormFactory = $deleteMenuItemFormFactory;
	}

}

