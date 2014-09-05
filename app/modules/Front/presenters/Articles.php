<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class ArticlesPresenter extends BasePresenter {

	protected $articlesFactory;

	public function __construct(ZaxCMS\Components\Articles\IArticlesFactory $articlesFactory) {
		$this->articlesFactory = $articlesFactory;
	}

	protected function createComponentArticles() {
	    return $this->articlesFactory->create()
		    ->enableAjax();
	}

	public function actionDefault() {
		$this['articles']; // initialize component to make paginator work
	}

}