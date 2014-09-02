<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class LogoutButtonControl extends SecuredControl {

	public $onLogout = [];

	protected $deleteIdentity = TRUE;

	public function setDeleteIdentity($deleteIdentity = TRUE) {
		$this->deleteIdentity = $deleteIdentity;
		return $this;
	}

	public function handleLogout() {
		$this->user->logout($this->deleteIdentity);
		$this->onLogout();
		$this->presenter->redirect('this');
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

}