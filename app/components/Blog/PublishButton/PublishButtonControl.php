<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class PublishButtonControl extends SecuredControl {

	use Model\CMS\Service\TInjectArticleService;

	public $onPublish = [];

	protected $articleId;

	public function setArticleId($id) {
		$this->articleId = $id;
		return $this;
	}

	/** @secured WebContent, Edit */
	public function handlePublish($id) {
		$article = $this->articleService->get($id);
		$article->isPublic = TRUE;
		$this->articleService->persist($article);
		$this->articleService->flush();
		$this->articleService->invalidateCache();
		$this->template->isPublic = TRUE;
		$this->flashMessage('common.alert.changesSaved', 'success');
		$this->onPublish($article);
	}

    public function viewDefault() {
        
    }

	public function beforeRender($isPublic) {
		$this->template->articleId = $this->articleId;
		if(!isset($this->template->isPublic)) {
			$this->template->isPublic = $isPublic;
		}
	}

}