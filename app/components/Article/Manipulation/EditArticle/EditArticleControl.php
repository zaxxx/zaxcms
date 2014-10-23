<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class EditArticleControl extends Control {

	protected $editArticleFormFactory;

	protected $article;

	public function __construct(IEditArticleFormFactory $editArticleFormFactory) {
		$this->editArticleFormFactory = $editArticleFormFactory;
	}

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

	public function viewDefault() {

	}

	public function beforeRender() {

	}

	protected function createComponentEditArticleForm() {
	    return $this->editArticleFormFactory->create()
		    ->setArticle($this->article);
	}

}