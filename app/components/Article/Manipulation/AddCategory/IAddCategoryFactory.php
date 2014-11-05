<?php

namespace ZaxCMS\Components\Article;

interface IAddCategoryFactory {

    /** @return AddCategoryControl */
    public function create();

}


trait TInjectAddCategoryFactory {

	/** @var IAddCategoryFactory */
	protected $addCategoryFactory;

	public function injectAddCategoryFactory(IAddCategoryFactory $addCategoryFactory) {
		$this->addCategoryFactory = $addCategoryFactory;
	}

}

