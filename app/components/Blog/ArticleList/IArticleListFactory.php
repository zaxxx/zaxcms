<?php

namespace ZaxCMS\Components\Blog;

interface IArticleListFactory {

    /** @return ArticleListControl */
    public function create();

}

trait TInjectArticleListFactory {

	/** @var IArticleListFactory */
	protected $articleListFactory;
	
	public function injectArticleListFactory(IArticleListFactory $articleListFactory) {
		$this->articleListFactory = $articleListFactory;
	}
	
}
