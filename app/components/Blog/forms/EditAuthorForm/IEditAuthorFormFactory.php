<?php

namespace ZaxCMS\Components\Blog;

interface IEditAuthorFormFactory {

    /** @return EditAuthorFormControl */
    public function create();

}

trait TInjectEditAuthorFormFactory {

	/** @var IEditAuthorFormFactory */
	protected $editAuthorFormFactory;

	public function injectEditAuthorFormFactory(IEditAuthorFormFactory $factory) {
		$this->editAuthorFormFactory = $factory;
	}

}