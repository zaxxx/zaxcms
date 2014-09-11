<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

class RolesControl extends Control {

	/** @persistent */
	public $selectRole;

	public $onUpdate = [];

	protected $roleService;

	protected $addRoleFormFactory;

	protected $editRoleFormFactory;

	protected $deleteRoleFormFactory;

	protected $permissionsFactory;

	protected $localeSelectFactory;

	public function __construct(Model\CMS\Service\RoleService $roleService,
	                            IAddRoleFormFactory $addRoleFormFactory,
								IEditRoleFormFactory $editRoleFormFactory,
								IDeleteRoleFormFactory $deleteRoleFormFactory,
								IPermissionsFactory $permissionsFactory,
								ZaxCMS\Components\LocaleSelect\ILocaleSelectFactory $localeSelectFactory,
								Model\CMS\AclFactory $aclFactory) {
		$this->roleService = $roleService;
		$this->addRoleFormFactory = $addRoleFormFactory;
		$this->editRoleFormFactory = $editRoleFormFactory;
		$this->deleteRoleFormFactory = $deleteRoleFormFactory;
		$this->permissionsFactory = $permissionsFactory;
		$this->localeSelectFactory = $localeSelectFactory;

		$this->onUpdate[] = [$aclFactory, 'invalidateCache'];
	}

    public function viewDefault() {

    }

	protected function getSelectedRole() {
		$this->roleService->setLocale($this['localeSelect']->getLocale());
		return $this->roleService->get($this->selectRole);
	}
    
    public function beforeRender() {
	    $this->roleService->setLocale($this['localeSelect']->getLocale());
	    $guest = $this->roleService->getGuestRole();
	    $guest->setTranslatableLocale($this['localeSelect']->getLocale());
	    $this->roleService->refresh($guest);
	    $this->template->guestRole = $guest;
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

	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
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

	protected function createComponentPermissions() {
	    return $this->permissionsFactory->create()
		    ->setRole($this->getSelectedRole());
	}

}