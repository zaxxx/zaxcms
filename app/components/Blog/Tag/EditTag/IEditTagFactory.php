<?php

namespace ZaxCMS\Components\Blog;

interface IEditTagFactory {

    /** @return EditTagControl */
    public function create();

}

trait TInjectEditTagFactory {

	/** @var IEditTagFactory */
	protected $editTagFactory;

	public function injectEditTagFactory(IEditTagFactory $factory) {
		$this->editTagFactory = $factory;
	}

}