<?php

namespace ZaxCMS\Components\Article;

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

