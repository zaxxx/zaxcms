<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class ArticleControl extends SecuredControl {

	use TInjectEditArticleFactory,
		Model\CMS\Service\TInjectArticleService,
		ZaxCMS\DI\TInjectArticleConfig;

	/** @persistent */
	public $slug;

	protected $article;

	public function attached($presenter) {
		parent::attached($presenter);
		if($this->permission->isUserAllowedTo('WebContent', 'Edit')) {
			$this['editArticle'];
		}
	}

	protected function getSlug() {
		return (string)$this->slug;
	}

	public function getArticle() {
		if($this->article === NULL) {
			$article = $this->articleService->getBy(['slug' => $this->getSlug()]);
			if($article === NULL) {
				throw new Nette\Application\BadRequestException;
			}
			$article->setTranslatableLocale($this->getLocale());
			$this->articleService->refresh($article);
			$this->article = $article;
		}
		return $this->article;
	}

    public function viewDefault() {
        
    }

	/** @secured WebContent, Edit */
	public function viewEdit() {

	}
    
    public function beforeRender() {
        $this->template->article = $this->article;
	    $this->template->articleConfig = $this->articleConfig;
    }

	/** @secured WebContent, Edit */
	protected function createComponentEditArticle() {
	    return $this->editArticleFactory->create()
		    ->setArticle($this->getArticle());
	}


}