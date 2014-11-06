<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class BlogPresenter extends BasePresenter {

	use ZaxCMS\Components\Article\TInjectArticleFactory,
		ZaxCMS\Components\Article\TInjectCategoryFactory,
		ZaxCMS\Components\Article\TInjectTagFactory,
		ZaxCMS\Components\Article\TInjectAuthorFactory;

	protected function createComponentArticle() {
	    return $this->articleFactory->create()
		    ->enableAjax();
	}

	protected function createComponentCategory() {
	    return $this->categoryFactory->create()
		    ->enableAjax();
	}

	protected function createComponentTag() {
	    return $this->tagFactory->create()
		    ->enableAjax();
	}

	protected function createComponentAuthor() {
	    return $this->authorFactory->create()
		    ->enableAjax();
	}

}
