<?php

namespace ZaxCMS\Components\Blog;

interface IDeleteArticleFormFactory {

    /** @return DeleteArticleFormControl */
    public function create();

}

trait TInjectDeleteArticleFormFactory {

	/** @var IDeleteArticleFormFactory */
	protected $deleteArticleFormFactory;

	public function injectDeleteArticleFormFactory(IDeleteArticleFormFactory $factory) {
		$this->deleteArticleFormFactory = $factory;
	}

}