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

	public function setSelectedUser(Model\CMS\Entity\User $user) {
		$this->selectedUser = $user;
		return $this;
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
        $this->template->selectedUser = $this->selectedUser;
    }

}