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

	protected $appDir;

	protected $databaseGenerator;

	public function __construct(ICheckDatabaseFactory $checkDatabaseFactory,
	                            ICreateUserFactory $createUserFactory,
	                            Model\CMSInstall\CMSInstaller $CMSInstaller,
								Model\CMSInstall\DatabaseGenerator $databaseGenerator,
								Zax\Utils\AppDir $appDir) {
		$this->checkDatabaseFactory = $checkDatabaseFactory;
		$this->createUserFactory = $createUserFactory;
		$this->CMSInstaller = $CMSInstaller;
		$this->databaseGenerator = $databaseGenerator;
		$this->appDir = $appDir;
		$this->CMSInstaller->checkIsInstalled();
	}

    public function viewDefault() {
	    $this->template->step = 1;
	    $this->template->progress = 5;
    }

	public function viewStep2() {
		$this->template->step = 2;
		$this->template->progress = 33;
	}

	public function viewStep3() {
		$this->template->step = 3;
		$this->template->progress = 66;
	}

	public function handlePrepareInstall() {
		$this->CMSInstaller->prepareInstall();
		$this->go('install!', ['step' => 0]);
	}

	public function handleInstall($step) {
		$this->CMSInstaller->install($step);
		$steps = $this->databaseGenerator->getCachedSqlCount();
		$this->template->installSteps = $steps;
		$this->template->installStep = $step;
		$this->redrawControl();
	}

	public function handlePostInstall() {
		$this->CMSInstaller->postInstall();
		$this->go('this', ['view' => 'Step3']);
	}

	public function installed() {
		$this->CMSInstaller->saveInstalledFlag();
		$this->CMSInstaller->wipeCache();
		$this->presenter->redirect('installed');
	}
    
    public function beforeRender() {
        $this->template->steps = [
	        'Připojení k databázi',
	        'Instalace CMS',
	        'Založení administrátorského účtu'
        ];
    }

	protected function createComponentCheckDatabase() {
	    return $this->checkDatabaseFactory->create();
	}

	protected function createComponentCreateUser() {
	    return $this->createUserFactory->create();
	}

}