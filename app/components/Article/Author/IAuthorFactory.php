<?php

namespace ZaxCMS\Components\Article;

interface IAuthorFactory {

    /** @return AuthorControl */
    public function create();

}

trait TInjectAuthorFactory {

	/** @var IAuthorFactory */
	protected $authorFactory;

	public function injectAuthorFactory(IAuthorFactory $factory) {
		$this->authorFactory = $factory;
	}

}