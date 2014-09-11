<?php

namespace ZaxCMS\AdminModule;
use Nette,
	ZaxCMS,
	Zax,
	Nette\Application\UI as NetteUI,
	Zax\Application\UI;


abstract class BasePresenter extends ZaxCMS\BasePresenter {

	public function startup() {
		parent::startup();

		if(!$this->user->isAllowed('AdminPanel', 'Use')) {
			throw new Nette\Application\ForbiddenRequestException;
		}
	}

}