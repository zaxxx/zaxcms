<?php

namespace ZaxCMS\Components\Blog;

interface ICategoryFactory {

    /** @return CategoryControl */
    public function create();

}

trait TInjectCategoryFactory {

	/** @var ICategoryFactory */
	protected $categoryFactory;

	public function injectCategoryFactory(ICategoryFactory $factory) {
		$this->categoryFactory = $factory;
	}

}