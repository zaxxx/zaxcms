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

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->selectedUser = $this->selectedUser;
    }

	protected function createComponentBasicInfo() {
	    return $this->basicInfoFactory->create()
		    ->setSelectedUser($this->selectedUser);
	}

	protected function createComponentSecurityInfo() {
	    return $this->securityInfoFactory->create()
		    ->setSelectedUser($this->selectedUser);
	}

}