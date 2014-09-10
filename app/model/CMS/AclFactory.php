<?php

namespace ZaxCMS\Model\CMS;
use Zax,
	ZaxCMS,
	Kdyby,
	Nette;

class AclFactory extends Nette\Object {

	use Zax\Traits\TCacheable;

	private $defaultPermissions = [
		'AdminPanel' => ['Show'],
		'WebContent' => ['Edit'],
		'FileManager' => ['Show', 'Edit', 'Delete', 'Upload'],
		'Menu' => ['Edit'],
		'Pages' => ['Add', 'Edit', 'Delete'],
		'Users' => ['Add', 'Edit', 'Delete']
	];

	protected $cmsInstalled;

	protected $roleService;

	protected $resourceService;

	protected $privilegeService;

	protected $permissionService;

	protected $aclService;

	public function __construct($cmsInstalled,
	                            Service\RoleService $roleService,
	                            Service\ResourceService $resourceService,
	                            Service\PrivilegeService $privilegeService,
	                            Service\PermissionService $permissionService,
	                            Service\AclService $aclService) {
		$this->cmsInstalled = (bool)$cmsInstalled;
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
		$this->resourceService->createDefaultResources();
		$this->privilegeService->createDefaultPrivileges();
		$this->createDefaultPermissions();

		$admin = $this->roleService->getAdminRole();
		foreach($this->permissionService->findAll() as $permission) {
			$this->aclService->createAclEntry($admin, $permission, TRUE);
		}

		$this->aclService->flush();
	}

	/** @return Nette\Security\Permission */
	public function createNetteAcl() {
		if(!$this->cmsInstalled) {
			return new Nette\Security\Permission;
		}
		$acl = $this->cache->load('acl');
		if($acl === NULL) {
			$acl = new Nette\Security\Permission;
			try {
				foreach($this->roleService->findAll() as $role) {
					$acl->addRole($role->name);
				}
			} catch (Kdyby\Doctrine\DBALException $ex) {
				return new Nette\Security\Permission;
			}
			foreach($this->resourceService->findAll() as $resource) {
				$acl->addResource($resource->name);
			}
			foreach($this->aclService->findAll() as $aclEntry) {
				if($aclEntry->allow) {
					$acl->allow($aclEntry->role->name, $aclEntry->permission->resource->name, $aclEntry->permission->privilege->name);
				} else {
					$acl->deny($aclEntry->role->name, $aclEntry->permission->resource->name, $aclEntry->permission->privilege->name);
				}
			}
			$this->cache->save('acl', $acl);
		}

		return $acl;
	}

}