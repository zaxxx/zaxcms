<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuItemFormFactory {

    /** @return EditMenuItemFormControl */
    public function create();

}


trait TInjectEditMenuItemFormFactory {

	/** @var IEditMenuItemFormFactory */
	protected $editMenuItemFormFactory;

	public function injectEditMenuItemFormFactory(IEditMenuItemFormFactory $editMenuItemFormFactory) {
		$this->editMenuItemFormFactory = $editMenuItemFormFactory;
	}

}

