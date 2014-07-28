<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\NewSecuredControl as SecuredControl;

class TinyLoginBoxControl extends SecuredControl {

	protected $loginFormFactory;

	protected $logoutButtonFactory;

	protected $adminPanelButtonFactory;

	public function __construct(ILoginFormFactory $loginFormFactory,
	                            ILogoutButtonFactory $logoutButtonFactory,
	                            IAdminPanelButtonFactory $adminPanelButtonFactory) {
		$this->loginFormFactory = $loginFormFactory;
		$this->logoutButtonFactory = $logoutButtonFactory;
		$this->adminPanelButtonFactory = $adminPanelButtonFactory;
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

	/**
	 * @secured Show, AdminPanel
	 */
	protected function createComponentAdminPanelButton() {
	    return $this->adminPanelButtonFactory->create();
	}

}