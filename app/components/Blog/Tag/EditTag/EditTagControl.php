<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class EditTagControl extends SecuredControl {

	use Zax\Utils\TInjectRootDir,
		ZaxCMS\Components\FileManager\TInjectFileManagerFactory,
		TInjectDeleteTagFormFactory;

	protected $tag;

	public function setTag(Model\CMS\Entity\Tag $tag) {
		$this->tag = $tag;
		return $this;
	}

    public function viewDefault() {
        
    }
	
	public function viewFiles() {

	}

	public function viewDelete() {

	}
    
    public function beforeRender() {
        $this->template->tag = $this->tag;
    }

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/tag/' . $this->tag->id . '-' . $this->tag->slug)
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

	protected function createComponentDeleteTagForm() {
	    return $this->deleteTagFormFactory->create()
		    ->setTag($this->tag);
	}

}