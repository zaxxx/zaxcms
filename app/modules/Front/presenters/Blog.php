<?php

namespace ZaxCMS\FrontModule;
use Nette,
	ZaxCMS,
	ZaxCMS\Components\Blog,
	ZaxCMS\Model,
	Nette\Application\UI\Presenter,
	Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
	Zax;

class BlogPresenter extends BasePresenter {

	use Blog\TInjectArticleFactory,
		Blog\TInjectCategoryFactory,
		Blog\TInjectTagFactory,
		Blog\TInjectAuthorFactory,
		Blog\TInjectAuthorListFactory;

	public function renderArticle() {
		$this->template->title = $this['article']->article->title;
	}

	public function renderCategory() {
		$this->template->category = $this['category']->category;
	}

	public function renderTag() {
		$this->template->title = $this['tag']->tag->title;
	}

	public function renderAuthor() {
		$this->template->title = $this['author']->author->name;
	}

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

	protected function createComponentAuthorList() {
	    return $this->authorListFactory->create()
		    ->enableAjax();
	}

	protected function createComponentWebContent() {
		return new NetteUI\Multiplier(function($name) {
			return $this->webContentFactory->create()
				->setCacheNamespace('ZaxCMS.WebContent.' . $name)
				->enableAjax()
				->setName($name);
		});
	}

}
