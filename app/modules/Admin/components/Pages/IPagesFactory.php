<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IPagesFactory {

    /** @return PagesControl */
    public function create();

}


trait TInjectPagesFactory {

	protected $pagesFactory;

	public function injectPagesFactory(IPagesFactory $pagesFactory) {
		$this->pagesFactory = $pagesFactory;
	}

}

