<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class RolesControl extends Control {

	/** @persistent */
	public $selectRole;

	protected $roleService;

	protected $addRoleFormFactory;

	protected $editRoleFormFactory;

	protected $deleteRoleFormFactory;

	public function __construct(Model\CMS\Service\RoleService $roleService,
	                            IAddRoleFormFactory $addRoleFormFactory,
								IEditRoleFormFactory $editRoleFormFactory,
								IDeleteRoleFormFactory $deleteRoleFormFactory) {
		$this->roleService = $roleService;
		$this->addRoleFormFactory = $addRoleFormFactory;
		$this->editRoleFormFactory = $editRoleFormFactory;
		$this->deleteRoleFormFactory = $deleteRoleFormFactory;
	}

    public function viewDefault() {

    }

	protected function getSelectedRole() {
		return $this->roleService->get($this->selectRole);
	}
    
    public function beforeRender() {
	    $this->template->guestRole = $guest = $this->roleService->getGuestRole();
	    $this->template->roles = $this->roleService->getChildren($guest, FALSE, NULL, 'ASC', TRUE);
    }

	public function viewDelete() {

	}

	public function viewEdit() {

	}

	public function viewPermissions() {

	}

	public function viewAdd() {

	}

	protected function createComponentEditRoleForm() {
	    return $this->editRoleFormFactory->create()
		    ->setRole($this->getSelectedRole());
	}

	protected function createComponentAddRoleForm() {
		$role = $this->roleService->create();
		$role->parent = $this->getSelectedRole();
		return $this->addRoleFormFactory->create()
			->setRole($role);
	}

	protected function createComponentDeleteRoleForm() {
	    return $this->deleteRoleFormFactory->create()
		    ->setRole($this->getSelectedRole());
	}

}