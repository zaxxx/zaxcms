<?php

namespace ZaxCMS\Components\Article;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class EditArticleControl extends SecuredControl {

	use TInjectEditArticleFormFactory,
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
	
	public function viewActions() {
	    
	}

	public function beforeRender() {

	}

	/** @secured WebContent, Edit */
	public function handleSetCreatedNow() {
		$this->article->createdAt = new \DateTime;
		$this->articleService->persist($this->article);
		$this->articleService->flush();
		$this->go('this');
	}

	protected function createComponentEditArticleForm() {
	    return $this->editArticleFormFactory->create()
		    ->setArticle($this->article)
		    ->disableAjaxFor(['form']);
	}

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/articles/' . $this->article->id)
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