<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class AddArticleControl extends SecuredControl {

	protected $articleService;

	protected $addArticleFormFactory;

	protected $category;

	public function __construct(Model\CMS\Service\ArticleService $articleService,
								IAddArticleFormFactory $addArticleFormFactory) {
		$this->articleService = $articleService;
		$this->addArticleFormFactory = $addArticleFormFactory;
	}

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
		    ->setArticle($article);
	}

}