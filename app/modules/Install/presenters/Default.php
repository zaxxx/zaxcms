<?php

namespace ZaxCMS\InstallModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class DefaultPresenter extends BasePresenter {

	protected $installer;

	public function __construct(ZaxCMS\Model\CMSInstaller $installer) {
		$this->installer = $installer;
	}

	public function actionDefault() {
		$this->installer->install();
		$this->flashMessage('system.alert.cmsInstalled', 'success');
		$this->redirect(':Front:Default:default');
	}

	public function renderDefault() {

	}

}