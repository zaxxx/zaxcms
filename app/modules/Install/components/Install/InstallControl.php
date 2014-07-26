<?php

namespace ZaxCMS\InstallModule\Components\Install;
use Nette,
    Zax,
    ZaxCMS\Model,
	ZaxCMS,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class InstallControl extends Control {

	protected $checkDatabaseFactory;

	protected $createUserFactory;

	protected $CMSInstaller;

	protected $fileManagerFactory;

	protected $appDir;

	public function __construct(ICheckDatabaseFactory $checkDatabaseFactory,
	                            ICreateUserFactory $createUserFactory,
	                            Model\CMSInstaller $CMSInstaller,
								ZaxCMS\Components\FileManager\IFileManagerFactory $fileManagerFactory,
								Zax\Utils\AppDir $appDir) {
		$this->checkDatabaseFactory = $checkDatabaseFactory;
		$this->createUserFactory = $createUserFactory;
		$this->CMSInstaller = $CMSInstaller;
		$this->fileManagerFactory = $fileManagerFactory;
		$this->appDir = $appDir;
	}

    public function viewDefault() {
        $this->template->step = 1;
	    $this->template->progress = 5;
    }

	public function viewStep2() {
	    $this->template->step = 2;
		$this->template->progress = 25;
	}

	public function viewStep3() {
		$this->template->step = 3;
		$this->template->progress = 50;
	}

	public function viewStep4() {
		$this->template->step = 4;
		$this->template->progress = 75;
	}

	public function handleInstall() {
		$this->CMSInstaller->install();
		$this->redirect('this', ['view' => 'Step3']);
	}
    
    public function beforeRender() {
        $this->template->steps = [
	        'Připojení k databázi',
	        'Instalace CMS',
	        'Založení administrátorského účtu',
	        'Dokončení instalace'
        ];
    }

	protected function createComponentCheckDatabase() {
	    return $this->checkDatabaseFactory->create();
	}

	protected function createComponentCreateUser() {
	    return $this->createUserFactory->create();
	}

	protected function createComponentFileManager() {
	    $fileManager = $this->fileManagerFactory->create()
		    ->setRoot($this->appDir . '/modules/Install')
		    ->enableFeature('deleteDir');

		$fileManager->getDirectoryList()->getDeleteDir()->onDirDelete[] = function($dir) {
			$this->flashMessage('system.alert.cmsInstalled');
			$this->presenter->redirect(':Front:Default:default');
		};

		return $fileManager;
	}

}