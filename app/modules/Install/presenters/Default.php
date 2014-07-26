<?php

namespace ZaxCMS\InstallModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class DefaultPresenter extends BasePresenter {

	protected $installFactory;

	public function __construct(Components\Install\IInstallFactory $installFactory) {
		$this->installFactory = $installFactory;
	}

	protected function createComponentInstall() {
	    return $this->installFactory->create()
		    ->enableAjax();
	}

}