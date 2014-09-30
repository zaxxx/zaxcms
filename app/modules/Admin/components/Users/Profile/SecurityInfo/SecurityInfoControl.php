<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class SecurityInfoControl extends SecuredControl {

	protected $selectedUser;

    protected $securityFormFactory;

    public function __construct(ISecurityFormFactory $securityFormFactory) {
        $this->securityFormFactory = $securityFormFactory;
    }

	public function setSelectedUser(Model\CMS\Entity\User $user) {
		$this->selectedUser = $user;
		return $this;
	}

	/** @secured Users, Use */
    public function viewDefault() {
        
    }

	/** @secured Users, Security */
    public function viewEdit() {

    }

	/** @secured Users, Use */
    public function beforeRender() {
        $this->template->selectedUser = $this->selectedUser;
    }

	/** @secured Users, Security */
    public function createComponentSecurityForm() {
        return $this->securityFormFactory->create()
            ->setSelectedUser($this->selectedUser);
    }

}