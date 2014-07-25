<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class TinyLoginBoxControl extends Control {

	protected $loginFormFactory;

	protected $logoutButtonFactory;

	public function __construct(ILoginFormFactory $loginFormFactory, ILogoutButtonFactory $logoutButtonFactory) {
		$this->loginFormFactory = $loginFormFactory;
		$this->logoutButtonFactory = $logoutButtonFactory;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        
    }

	/** @return LoginFormControl */
	public function getLoginForm() {
		return $this['loginForm'];
	}

	/** @return LogoutButtonControl */
	public function getLogoutButton() {
		return $this['logoutButton'];
	}

	protected function createComponentLogoutButton() {
	    return $this->logoutButtonFactory->create();
	}

	protected function createComponentLoginForm() {
	    return $this->loginFormFactory->create()
		    ->setFormStyle('form-inline')
		    ->showPlaceholdersOnly();
	}

}