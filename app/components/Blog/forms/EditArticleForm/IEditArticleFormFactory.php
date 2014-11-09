<?php

namespace ZaxCMS\Components\Blog;

interface IEditArticleFormFactory {

    /** @return EditArticleFormControl */
    public function create();

}


trait TInjectEditArticleFormFactory {

	protected $editArticleFormFactory;

	public function injectEditArticleFormFactory(IEditArticleFormFactory $editArticleFormFactory) {
		$this->editArticleFormFactory = $editArticleFormFactory;
	}

}

