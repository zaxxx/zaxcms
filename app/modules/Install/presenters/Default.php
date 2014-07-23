<?php

namespace ZaxCMS\InstallModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class DefaultPresenter extends BasePresenter {

	protected $installer;

	protected $createUserFactory;

	public function __construct(ZaxCMS\Model\CMSInstaller $installer, Components\ICreateUserFactory $createUserFactory) {
		$this->installer = $installer;
		$this->createUserFactory = $createUserFactory;
	}

	public function handleGenerateDatabase() {
		try {
			$this->installer->install();
			$this->flashMessage('system.alert.databaseGenerated', 'success');
		} catch (\Exception $e) {
			$this->flashMessage($e->getMessage(), 'danger');
			$this->redirect('this');
		}
		$this->redirect('createUser');
	}

	protected function createComponentCreateUser() {
	    return $this->createUserFactory->create();
	}

	public function done() {
		$this->flashMessage('system.alert.cmsInstalled', 'success');
		$this->redirect(':Front:Default:default');
	}

}