<?php

namespace ZaxCMS\AdminModule;
use Nette,
    Nette\Application\UI\Presenter,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax,
	ZaxCMS;

class UsersPresenter extends BasePresenter {

	protected $usersFactory;

	function __construct(ZaxCMS\AdminModule\Components\Users\IUsersFactory $usersFactory) {
		$this->usersFactory = $usersFactory;
	}

	public function actionDefault() {
        
    }

	protected function createComponentUsers() {
	    return $this->usersFactory->create();
	}
    
}