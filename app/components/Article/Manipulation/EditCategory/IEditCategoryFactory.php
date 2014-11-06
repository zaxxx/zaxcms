<?php

namespace ZaxCMS\Components\Article;

interface IEditCategoryFactory {

    /** @return EditCategoryControl */
    public function create();

}

trait TInjectEditCategoryFactory {

	/** @var IEditCategoryFactory */
	protected $editCategoryFactory;

	public function injectEditCategoryFactory(IEditCategoryFactory $factory) {
		$this->editCategoryFactory = $factory;
	}

}