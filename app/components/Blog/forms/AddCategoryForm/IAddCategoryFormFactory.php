<?php

namespace ZaxCMS\Components\Blog;

interface IAddCategoryFormFactory {

    /** @return AddCategoryFormControl */
    public function create();

}


trait TInjectAddCategoryFormFactory {

	protected $addCategoryFormFactory;

	public function injectAddCategoryFormFactory(IAddCategoryFormFactory $addCategoryFormFactory) {
		$this->addCategoryFormFactory = $addCategoryFormFactory;
	}

}

