<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class BasicInfoControl extends Control {

	protected $selectedUser;

	protected $editUserFormFactory;

	public function __construct(IEditUserFormFactory $editUserFormFactory) {
		$this->editUserFormFactory = $editUserFormFactory;
	}

	public function setSelectedUser(Model\CMS\Entity\User $user) {
		$this->selectedUser = $user;
		return $this;
	}

    public function viewDefault() {
        
    }

	public function viewEdit() {

	}
    
    public function beforeRender() {
        $this->template->selectedUser = $this->selectedUser;
    }

	protected function createComponentEditUserForm() {
	    return $this->editUserFormFactory->create()
		    ->setSelectedUser($this->selectedUser);
	}

}