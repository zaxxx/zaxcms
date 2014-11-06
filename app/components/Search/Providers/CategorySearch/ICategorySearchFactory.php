<?php

namespace ZaxCMS\Components\Search;

interface ICategorySearchFactory {

    /** @return CategorySearchControl */
    public function create();

}

trait TInjectCategorySearchFactory {

	/** @var ICategorySearchFactory */
	protected $categorySearchFactory;

	public function injectCategorySearchFactory(ICategorySearchFactory $factory) {
		$this->categorySearchFactory = $factory;
	}

}