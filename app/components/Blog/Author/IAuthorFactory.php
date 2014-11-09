<?php

namespace ZaxCMS\Components\Blog;

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