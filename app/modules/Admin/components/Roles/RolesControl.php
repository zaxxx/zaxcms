<?php

namespace ZaxCMS\AdminModule\Components\Roles;
use Nette,
    Zax,
	ZaxCMS,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\SecuredControl;

class RolesControl extends SecuredControl {

	use Model\CMS\Service\TInjectRoleService,
		TInjectAddRoleFormFactory,
		TInjectEditRoleFormFactory,
		TInjectDeleteRoleFormFactory,
		TInjectPermissionsFactory,
		ZaxCMS\Components\LocaleSelect\TInjectLocaleSelectFactory,
		Model\CMS\TInjectAclFactory;

	/** @persistent */
	public $selectRole;

	public $onUpdate = [];

	public function startup() {
		$this->onUpdate[] = [$this->aclFactory, 'invalidateCache'];
	}

	/** @secured Roles, Use */
    public function viewDefault() {

    }

	protected function getSelectedRole() {
		$this->roleService->setLocale($this['localeSelect']->getLocale());
		return $this->roleService->get($this->selectRole);
	}

	/** @secured Roles, Use */
    public function beforeRender() {
	    $this->roleService->setLocale($this['localeSelect']->getLocale());
	    $guest = $this->roleService->getGuestRole();
	    $guest->setTranslatableLocale($this['localeSelect']->getLocale());
	    $this->roleService->refresh($guest);
	    $this->template->guestRole = $guest;
	    $this->template->roles = $this->roleService->getChildren($guest, FALSE, NULL, 'ASC', TRUE);
    }

	/** @secured Roles, Delete */
	public function viewDelete() {

	}

	/** @secured Roles, Edit */
	public function viewEdit() {

	}

	/** @secured Roles, Secure */
	public function viewPermissions() {

	}

	/** @secured Roles, Add */
	public function viewAdd() {

	}

	protected function createComponentLocaleSelect() {
	    return $this->localeSelectFactory->create();
	}

	/** @secured Roles, Edit */
	protected function createComponentEditRoleForm() {
	    return $this->editRoleFormFactory->create()
		    ->setRole($this->getSelectedRole());
	}

	/** @secured Roles, Add */
	protected function createComponentAddRoleForm() {
		$role = $this->roleService->create();
		$role->parent = $this->getSelectedRole();
		return $this->addRoleFormFactory->create()
			->setRole($role);
	}

	/** @secured Roles, Delete */
	protected function createComponentDeleteRoleForm() {
	    return $this->deleteRoleFormFactory->create()
		    ->setRole($this->getSelectedRole());
	}

	/** @secured Roles, Secure */
	protected function createComponentPermissions() {
	    return $this->permissionsFactory->create()
		    ->setRole($this->getSelectedRole());
	}

}