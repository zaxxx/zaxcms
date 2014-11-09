<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class EditAuthorControl extends SecuredControl {

	use ZaxCMS\Components\FileManager\TInjectFileManagerFactory,
		Zax\Utils\TInjectRootDir,
		TInjectEditAuthorFormFactory,
		TInjectDeleteAuthorFormFactory;

	protected $author;

	public function setAuthor(Model\CMS\Entity\Author $author) {
		$this->author = $author;
		return $this;
	}

	/** @secured WebContent, Edit */
    public function viewDefault() {
        
    }

	/** @secured FileManager, Use */
	public function viewFiles() {

	}

	public function viewDelete() {

	}
    
    public function beforeRender() {
		$this->template->author = $this->author;
    }

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/author/' . $this->author->id . '-' . $this->author->slug)
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

	protected function createComponentEditAuthorForm() {
	    return $this->editAuthorFormFactory->create()
		    ->setAuthor($this->author)
		    ->disableAjaxFor(['form']);
	}

	protected function createComponentDeleteAuthorForm() {
	    return $this->deleteAuthorFormFactory->create()
		    ->setAuthor($this->author);
	}

}