<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class EditArticleControl extends SecuredControl {

	use TInjectEditArticleFormFactory,
		TInjectDeleteArticleFormFactory,
		ZaxCMS\Components\FileManager\TInjectFileManagerFactory,
		Model\CMS\Service\TInjectArticleService,
		Zax\Utils\TInjectRootDir;

	protected $article;

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

	public function viewDefault() {

	}

	/** @secured FileManager, Use */
	public function viewFiles() {

	}

	public function viewDelete() {

	}
	
	public function viewActions() {
	    
	}

	public function beforeRender() {
		$this->template->article = $this->article;
	}

	/** @secured WebContent, Edit */
	public function handlePublish() {
		$this->article->isPublic = TRUE;
		$this->articleService->persist($this->article);
		$this->articleService->flush();
		$this->articleService->invalidateCache();
		$this->go('this');
	}

	/** @secured WebContent, Edit */
	public function handleSetCreatedNow() {
		$this->article->createdAt = new \DateTime;
		$this->articleService->persist($this->article);
		$this->articleService->flush();
		$this->articleService->invalidateCache();
		$this->go('this');
	}

	/** @secured WebContent, Edit */
	protected function createComponentEditArticleForm() {
	    return $this->editArticleFormFactory->create()
		    ->setArticle($this->article)
		    ->disableAjaxFor(['form']);
	}

	/** @secured WebContent, Edit */
	protected function createComponentDeleteArticleForm() {
	    return $this->deleteArticleFormFactory->create()
		    ->setArticle($this->article);
	}

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/article/' . $this->article->id . '-' . $this->article->slug)
			->enableFeatures(
				[
					'createDir',
					'renameDir',
					'deleteDir',
					'truncateDir',
					'uploadFile',
					'renameFile',
					'deleteFile',
					'linkFile'
				]
			);
	}

}