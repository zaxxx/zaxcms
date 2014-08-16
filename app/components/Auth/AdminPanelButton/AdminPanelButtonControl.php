<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class AdminPanelButtonControl extends SecuredControl {

	public function handleEnterAdminPanel() {
		$this->presenter->redirect(':Admin:Default:default');
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

}