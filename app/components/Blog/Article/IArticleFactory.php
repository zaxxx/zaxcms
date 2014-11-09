<?php

namespace ZaxCMS\Components\Blog;

interface IArticleFactory {

    /** @return ArticleControl */
    public function create();

}

trait TInjectArticleFactory {

	protected $articleFactory;

	public function injectArticleFactory(IArticleFactory $articleFactory) {
		$this->articleFactory = $articleFactory;
	}

}