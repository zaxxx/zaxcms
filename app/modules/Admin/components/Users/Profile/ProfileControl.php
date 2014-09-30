<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class ProfileControl extends SecuredControl {

	protected $selectedUser;

    protected $basicInfoFactory;

	protected $securityInfoFactory;

    public function __construct(IBasicInfoFactory $basicInfoFactory,
                                ISecurityInfoFactory $securityInfoFactory) {
		$this->basicInfoFactory = $basicInfoFactory;
		$this->securityInfoFactory = $securityInfoFactory;
	}

	public function setSelectedUser(Model\CMS\Entity\User $user) {
		$this->selectedUser = $user;
		return $this;
	}

	/** @secured Users, Use */
    public function viewDefault() {
        
    }

	/** @secured Users, Use */
    public function beforeRender() {
        $this->template->selectedUser = $this->selectedUser;
    }

	/** @secured Users, Use */
	protected function createComponentBasicInfo() {
	    return $this->basicInfoFactory->create()
		    ->setSelectedUser($this->selectedUser);
	}

	/** @secured Users, Use */
	protected function createComponentSecurityInfo() {
	    return $this->securityInfoFactory->create()
		    ->setSelectedUser($this->selectedUser);
	}

}