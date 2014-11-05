<?php

namespace ZaxCMS\Components\Navigation;

interface IEditMenuItemFactory {

	/** @return EditMenuItemControl */
	public function create();

}


trait TInjectEditMenuItemFactory {

	/** @var IEditMenuItemFactory */
	protected $editMenuItemFactory;

	public function injectEditMenuItemFactory(IEditMenuItemFactory $editMenuItemFactory) {
		$this->editMenuItemFactory = $editMenuItemFactory;
	}

}

