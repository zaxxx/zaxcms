<?php

namespace ZaxCMS\Components\Blog;

interface IDeleteAuthorFormFactory {

    /** @return DeleteAuthorFormControl */
    public function create();

}

trait TInjectDeleteAuthorFormFactory {

	/** @var IDeleteAuthorFormFactory */
	protected $deleteAuthorFormFactory;

	public function injectDeleteAuthorFormFactory(IDeleteAuthorFormFactory $factory) {
		$this->deleteAuthorFormFactory = $factory;
	}

}