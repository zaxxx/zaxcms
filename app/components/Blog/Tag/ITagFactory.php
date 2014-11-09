<?php

namespace ZaxCMS\Components\Blog;

interface ITagFactory {

    /** @return TagControl */
    public function create();

}

trait TInjectTagFactory {

	/** @var ITagFactory */
	protected $tagFactory;

	public function injectTagFactory(ITagFactory $factory) {
		$this->tagFactory = $factory;
	}

}