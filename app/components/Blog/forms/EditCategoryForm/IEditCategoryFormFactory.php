<?php

namespace ZaxCMS\Components\Blog;

interface IEditCategoryFormFactory {

    /** @return EditCategoryFormControl */
    public function create();

}

trait TInjectEditCategoryFormFactory {

	/** @var IEditCategoryFormFactory */
	protected $editCategoryFormFactory;

	public function injectEditCategoryFormFactory(IEditCategoryFormFactory $factory) {
		$this->editCategoryFormFactory = $factory;
	}

}