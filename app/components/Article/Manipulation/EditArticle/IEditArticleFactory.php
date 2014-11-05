<?php

namespace ZaxCMS\Components\Article;

interface IEditArticleFactory {

    /** @return EditArticleControl */
    public function create();

}


trait TInjectEditArticleFactory {

	/** @var IEditArticleFactory */
	protected $editArticleFactory;

	public function injectEditArticleFactory(IEditArticleFactory $editArticleFactory) {
		$this->editArticleFactory = $editArticleFactory;
	}

}

