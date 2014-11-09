<?php

namespace ZaxCMS\Components\Blog;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class EditCategoryControl extends SecuredControl {

	use TInjectEditCategoryFormFactory,
		Zax\Utils\TInjectRootDir,
		ZaxCMS\Components\FileManager\TInjectFileManagerFactory;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }
	
	public function viewFiles() {
	    
	}
    
    public function beforeRender() {
        
    }

	/** @secured WebContent, Edit */
	protected function createComponentEditCategoryForm() {
	    return $this->editCategoryFormFactory->create()
		    ->setCategory($this->category)
		    ->disableAjaxFor(['form']);
	}

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/category/' . $this->category->id)
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