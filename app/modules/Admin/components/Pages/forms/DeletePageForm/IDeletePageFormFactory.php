<?php

namespace ZaxCMS\AdminModule\Components\Pages;

interface IDeletePageFormFactory {

    /** @return DeletePageFormControl */
    public function create();

}


trait TInjectDeletePageFormFactory {

	protected $deletePageFormFactory;

	public function injectDeletePageFormFactory(IDeletePageFormFactory $deletePageFormFactory) {
		$this->deletePageFormFactory = $deletePageFormFactory;
	}

}

