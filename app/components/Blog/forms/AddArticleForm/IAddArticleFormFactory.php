<?php

namespace ZaxCMS\Components\Blog;

interface IAddArticleFormFactory {

    /** @return AddArticleFormControl */
    public function create();

}


trait TInjectAddArticleFormFactory {

	protected $addArticleFormFactory;

	public function injectAddArticleFormFactory(IAddArticleFormFactory $addArticleFormFactory) {
		$this->addArticleFormFactory = $addArticleFormFactory;
	}

}

