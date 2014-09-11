<?php

namespace ZaxCMS\FrontModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class DefaultPresenter extends BasePresenter {

	protected $installed;

	public function __construct($installed = TRUE) {
		$this->installed = $installed;
	}

	public function startup() {
		parent::startup();
		if(!$this->installed) {
			$this->redirect(':Install:Default:default');
		}
	}

	public function actionDefault() {

	}

	public function renderDefault() {

	}

}