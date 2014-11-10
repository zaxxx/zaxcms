<?php

namespace ZaxCMS\Components\Navigation;

interface IAddMenuItemFactory {

    /** @return AddMenuItemControl */
	public function create();

}


trait TInjectAddMenuItemFactory {

	/** @var IAddMenuItemFactory */
	protected $addMenuItemFactory;

	public function injectAddMenuItemFactory(IAddMenuItemFactory $addMenuItemFactory) {
		$this->addMenuItemFactory = $addMenuItemFactory;
	}

}

