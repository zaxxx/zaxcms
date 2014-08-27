<?php

namespace ZaxCMS\AdminModule;
use Nette,
	ZaxCMS\Components,
    Nette\Application\UI\Presenter,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax;

class FilesPresenter extends BasePresenter {

	protected $fileManagerFactory;

	protected $rootDir;

	public function __construct(Components\FileManager\IFileManagerFactory $fileManagerFactory,
								Zax\Utils\RootDir $rootDir) {
		$this->fileManagerFactory = $fileManagerFactory;
		$this->rootDir = $rootDir;
	}

    public function actionDefault() {
        
    }

	protected function createComponentFileManager() {
	    return $this->fileManagerFactory->create()
		    ->setRoot($this->rootDir . '/upload')
	        ->enableAjax()
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