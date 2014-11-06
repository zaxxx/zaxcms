<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class AddArticleControl extends SecuredControl {

	use Model\CMS\Service\TInjectArticleService,
		TInjectAddArticleFormFactory;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	protected function createComponentAddArticleForm() {
		$article = $this->articleService->create();
		$article->category = $this->category;
	    return $this->addArticleFormFactory->create()
		    ->setArticle($article)
		    ->disableAjaxFor(['form']);
	}

}