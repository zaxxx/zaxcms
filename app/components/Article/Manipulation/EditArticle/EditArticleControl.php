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

	protected $editArticleFormFactory;

	protected $fileManagerFactory;

	protected $rootDir;

	protected $article;

	public function __construct(IEditArticleFormFactory $editArticleFormFactory,
								ZaxCMS\Components\FileManager\IFileManagerFactory $fileManagerFactory,
								Zax\Utils\RootDir $rootDir) {
		$this->editArticleFormFactory = $editArticleFormFactory;
		$this->fileManagerFactory = $fileManagerFactory;
		$this->rootDir = $rootDir;
	}

	public function setArticle(Model\CMS\Entity\Article $article) {
		$this->article = $article;
		return $this;
	}

	public function viewDefault() {

	}

	/** @secured FileManager, Use */
	public function viewFiles() {

	}

	public function beforeRender() {

	}

	protected function createComponentEditArticleForm() {
	    return $this->editArticleFormFactory->create()
		    ->setArticle($this->article);
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