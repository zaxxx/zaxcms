<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class ArticleControl extends SecuredControl {

	protected $editArticleFactory;

	protected $article;

	public function __construct(IEditArticleFactory $editArticleFactory) {
		$this->editArticleFactory = $editArticleFactory;
	}

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

    public function viewDefault() {
        
    }

	/** @secured WebContent, Edit */
	public function viewEdit() {

	}
    
    public function beforeRender() {
        $this->template->article = $this->article;
    }

	protected function createComponentEditArticle() {
	    return $this->editArticleFactory->create()
		    ->setArticle($this->article);
	}


}