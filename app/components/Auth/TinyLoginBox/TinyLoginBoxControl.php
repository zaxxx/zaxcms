<?php

namespace ZaxCMS\Components\Auth;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class TinyLoginBoxControl extends SecuredControl {

	protected $loginFormFactory;

	protected $logoutButtonFactory;

	protected $adminPanelButtonFactory;

	protected $dropup = FALSE;

	public function __construct(ILoginFormFactory $loginFormFactory,
	                            ILogoutButtonFactory $logoutButtonFactory,
	                            IAdminPanelButtonFactory $adminPanelButtonFactory) {
		$this->loginFormFactory = $loginFormFactory;
		$this->logoutButtonFactory = $logoutButtonFactory;
		$this->adminPanelButtonFactory = $adminPanelButtonFactory;
	}

    public function viewDefault() {
        
    }

	public function setDropup() {
		$this->dropup = TRUE;
		return $this;
	}
    
    public function beforeRender() {
        $this->template->dropup = $this->dropup;
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
	 * @secured AdminPanel, Use
	 */
	protected function createComponentAdminPanelButton() {
	    return $this->adminPanelButtonFactory->create();
	}

}