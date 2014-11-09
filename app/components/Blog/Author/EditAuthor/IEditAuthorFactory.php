<?php

namespace ZaxCMS\Components\Blog;

interface IEditAuthorFactory {

    /** @return EditAuthorControl */
    public function create();

}

trait TInjectEditAuthorFactory {

	/** @var IEditAuthorFactory */
	protected $editAuthorFactory;

	public function injectEditAuthorFactory(IEditAuthorFactory $factory) {
		$this->editAuthorFactory = $factory;
	}

}