<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette;

class AclFacade extends Nette\Object {

	protected $defaultPermissions = [
		'WebContent' => ['Edit'],
		'FileManager' => ['Edit', 'Delete', 'Upload'],
		'Menu' => ['Edit'],
		'Pages' => ['Add', 'Edit', 'Delete']
	];

	protected $roleService;

	protected $resourceService;

	protected $privilegeService;

	protected $permissionService;

	protected $aclService;

	public function __construct(RoleService $roleService,
								ResourceService $resourceService,
								PrivilegeService $privilegeService,
								PermissionService $permissionService,
								AclService $aclService) {
		$this->roleService = $roleService;
		$this->resourceService = $resourceService;
		$this->privilegeService = $privilegeService;
		$this->permissionService = $permissionService;
		$this->aclService = $aclService;
	}

	protected function createDefaultPermissions() {
		foreach($this->defaultPermissions as $resource => $privileges) {
			foreach($privileges as $privilege) {
				$resourceEntity = $this->resourceService->getBy(['name' => $resource]);
				$privilegeEntity = $this->privilegeService->getBy(['name' => $privilege]);
				$this->permissionService->createPermission($resourceEntity, $privilegeEntity);
			}
		}
		$this->permissionService->flush();
	}

	public function createDefaultAcl() {
		$this->roleService->createDefaultRoles();
		$this->resourceService->createDefaultResources();
		$this->privilegeService->createDefaultPrivileges();
		$this->createDefaultPermissions();

		$admin = $this->roleService->getAdminRole();
		foreach($this->permissionService->findAll() as $permission) {
			$this->aclService->createAclEntry($admin, $permission, TRUE);
		}

		$this->aclService->flush();
	}

}