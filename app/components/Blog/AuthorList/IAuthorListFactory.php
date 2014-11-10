<?php

namespace ZaxCMS\Components\Blog;

interface IAuthorListFactory {

    /** @return AuthorListControl */
    public function create();

}

trait TInjectAuthorListFactory {

	/** @var IAuthorListFactory */
	protected $authorListFactory;

	public function injectAuthorListFactory(IAuthorListFactory $factory) {
		$this->authorListFactory = $factory;
	}

}