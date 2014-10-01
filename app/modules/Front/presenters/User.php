<?php

namespace ZaxCMS\FrontModule;
use Nette,
	Zax\Application\UI,
	ZaxCMS,
	Zax;

class UserPresenter extends BasePresenter {

	protected $loginFormFactory;

	protected $changePasswordFactory;

	public function __construct(ZaxCMS\Components\Auth\ILoginFormFactory $loginFormFactory,
								ZaxCMS\Components\Auth\IChangePasswordFactory $changePasswordFactory) {
		$this->loginFormFactory = $loginFormFactory;
		$this->changePasswordFactory = $changePasswordFactory;
	}

	public function actionLogin() {
		if($this->user->isLoggedIn()) {
			$this->redirect(':Front:Default:default');
		}
	}

	public function actionChangePassword() {
		if(!$this->user->isLoggedIn()) {
			throw new Nette\Application\BadRequestException;
		}
	}

	protected function createComponentChangePassword() {
	    return $this->changePasswordFactory->create();
	}

	protected function createComponentLoginForm() {
	    return $this->loginFormFactory->create()
		    ->setFormStyle('form-horizontal');
	}

}