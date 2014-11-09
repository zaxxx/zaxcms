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
		TInjectDeleteCategoryFormFactory,
		Zax\Utils\TInjectRootDir,
		ZaxCMS\Components\FileManager\TInjectFileManagerFactory;

	protected $category;

	public function setCategory(Model\CMS\Entity\Category $category) {
		$this->category = $category;
		return $this;
	}

    public function viewDefault() {
        
    }

	public function viewDelete() {

	}
	
	public function viewFiles() {
	    
	}
    
    public function beforeRender() {
        $this->template->category = $this->category;
    }

	/** @secured WebContent, Edit */
	protected function createComponentEditCategoryForm() {
	    return $this->editCategoryFormFactory->create()
		    ->setCategory($this->category)
		    ->disableAjaxFor(['form']);
	}

	/** @secured WebContent, Edit */
	protected function createComponentDeleteCategoryForm() {
	    return $this->deleteCategoryFormFactory->create()
		    ->setCategory($this->category);
	}

	/** @secured FileManager, Use */
	protected function createComponentFileManager() {
		return $this->fileManagerFactory->create()
			->setRoot($this->rootDir . '/upload/category/' . $this->category->id . '-' . strpos($this->category->slug, '/') > 0 ? end(explode('/', $this->category->slug)) : $this->category->slug)
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