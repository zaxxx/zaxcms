<?php

namespace ZaxCMS\Components\Navigation;

interface INavigationFactory {

    /** @return NavigationControl */
    public function create();

}


trait TInjectNavigationFactory {

	/** @var INavigationFactory */
	protected $navigationFactory;

	public function injectNavigationFactory(INavigationFactory $navigationFactory) {
		$this->navigationFactory = $navigationFactory;
	}

}

