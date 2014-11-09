<?php

namespace ZaxCMS\Components\Blog;

interface IDeleteCategoryFormFactory {

    /** @return DeleteCategoryFormControl */
    public function create();

}

trait TInjectDeleteCategoryFormFactory {

	/** @var IDeleteCategoryFormFactory */
	protected $deleteCategoryFormFactory;

	public function injectDeleteCategoryFormFactory(IDeleteCategoryFormFactory $factory) {
		$this->deleteCategoryFormFactory = $factory;
	}

}