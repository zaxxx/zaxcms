<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PublishButtonControl extends SecuredControl {

	use Model\CMS\Service\TInjectArticleService;

	public $onPublish = [];

	/** @secured WebContent, Edit */
	public function handlePublish($id) {
		$article = $this->articleService->get($id);
		$article->isPublic = TRUE;
		$this->articleService->persist($article);
		$this->articleService->flush();
		$this->flashMessage('common.alert.changesSaved', 'success');
		$this->onPublish($article);
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender(Model\CMS\Entity\Article $article) {
        $this->template->article = $article;
    }

}