<?php

namespace ZaxCMS\Components\Blog;

interface IAddArticleFactory {

    /** @return AddArticleControl */
    public function create();

}


trait TInjectAddArticleFactory {

	/** @var IAddArticleFactory */
	protected $addArticleFactory;

	public function injectAddArticleFactory(IAddArticleFactory $addArticleFactory) {
		$this->addArticleFactory = $addArticleFactory;
	}

}

