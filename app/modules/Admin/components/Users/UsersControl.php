<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class UsersControl extends SecuredControl {

	protected $userService;

	public function __construct(Model\UserService $userService) {
		$this->userService = $userService;
	}

    public function viewDefault() {
        $this->template->users = $this->userService->findAll();
    }
    
    public function beforeRender() {
        
    }

}