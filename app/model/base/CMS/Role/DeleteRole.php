<?php

namespace ZaxCMS\Model\CMS;
use Zax,
	Nette,
	ZaxCMS;

class DeleteRole extends Nette\Object {

	protected $roleService;

	protected $aclService;

	protected $userService;

	protected $aclFactory;

	public function __construct(ZaxCMS\Model\CMS\Service\RoleService $roleService,
								ZaxCMS\Model\CMS\Service\AclService $aclService,
								ZaxCMS\Model\CMS\Service\UserService $userService,
								AclFactory $aclFactory) {
		$this->roleService = $roleService;
		$this->aclService = $aclService;
		$this->userService = $userService;
		$this->aclFactory = $aclFactory;
	}

	public function deleteRole(ZaxCMS\Model\CMS\Entity\Role $role) {
		foreach($this->userService->findBy(['role.id' => $role->id]) as $user) {
			$user->role = $role->parent;
			$this->userService->persist($user);
		}

		foreach($this->aclService->findBy(['role.id' => $role->id]) as $acl) {
			$this->aclService->remove($acl);
		}

		foreach($role->children as $child) {
			$child->parent = $role->parent;
			$this->roleService->persist($child);
		}

		$this->roleService->remove($role);

		$this->roleService->flush();

		$this->aclFactory->invalidateCache();
	}

}


trait TInjectDeleteRole {

	/** @var DeleteRole */
	protected $deleteRole;

	public function injectDeleteRole(DeleteRole $deleteRole) {
		$this->deleteRole = $deleteRole;
	}

}

