<?php

namespace ZaxCMS\AdminModule\Components\Users;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class RoleFilterControl extends Control implements Zax\Model\Doctrine\IQueryObjectFilter {

	protected $roleService;

	protected $role;

	/** @persistent */
	public $selectRole;

	public function __construct(Model\CMS\Service\RoleService $roleService) {
		$this->roleService = $roleService;
	}

	protected function getSelectedRole() {
		if($this->selectRole === NULL) {
			return NULL;
		}
		if($this->role === NULL) {
			$role = $this->roleService->get($this->selectRole);
			$role->setTranslatableLocale($this->getLocale());
			$this->roleService->refresh($role);
			$this->role = $role;
		}
		return $this->role;
	}

	public function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		return $queryObject->inRole($this->getSelectedRole());
	}

    public function viewDefault() {
        
    }
    
    public function beforeRender() {
	    $this->roleService->setLocale($this->getLocale());
        $this->template->roles = $this->roleService->getChildren($this->roleService->getGuestRole());
	    $this->template->selectedRole = $this->getSelectedRole();
    }

}