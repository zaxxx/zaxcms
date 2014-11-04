<?php

namespace ZaxCMS\Model\CMS;
use Zax,
	ZaxCMS,
	Kdyby,
	Nette;

class AclFactory extends Nette\Object {

	use Zax\Traits\TCacheable;

	const CACHE_TAG = 'ZaxCMS.Model.Acl';

	private $defaultPermissions = [
		'AdminPanel' => [
			'Use' => ['cs_CZ' => 'používat administraci', 'en_US' => 'use the admin panel']
		],
		'WebContent' => [
			'Use' => ['cs_CZ' => 'používat editor statického obsahu', 'en_US' => 'use the static content editor'],
			'Edit' => ['cs_CZ' => 'upravovat statický obsah na webu', 'en_US' => 'edit the static content on web']
		],
		'FileManager' => [
			'Use' => ['cs_CZ' => 'používat správce souborů', 'en_US' => 'use the file manager'],
			'Edit' => ['cs_CZ' => 'přejmenovávat soubory a složky, vytvářet nové složky', 'en_US' => 'rename files and folders, create new folders'],
			'Delete' => ['cs_CZ' => 'odstraňovat soubory a složky', 'en_US' => 'delete files and folders'],
			'Upload' => ['cs_CZ' => 'nahrávat nové soubory', 'en_US' => 'upload new files']
		],
		'Menu' => [
			'Use' => ['cs_CZ' => 'používat správce nabídek', 'en_US' => 'use the navigations edit panel'],
			'Edit' => ['cs_CZ' => 'upravovat nabídky a navigace', 'en_US' => 'modify menus and navigations'],
			'Secure' => ['cs_CZ' => 'nastavovat skrývání položek na základě oprávnění', 'en_US' => 'change the visibility of items based on permissions'] // TODO: implement
		],
		'Pages' => [
			'Use' => ['cs_CZ' => 'používat správce stránek', 'en_US' => 'use the pages admin panel'],
			'Add' => ['cs_CZ' => 'přidávat nové stránky', 'en_US' => 'create new pages'],
			'Edit' => ['cs_CZ' => 'upravovat existující stránky', 'en_US' => 'modify existing pages'],
			'Delete' => ['cs_CZ' => 'mazat stránky', 'en_US' => 'delete pages'],
			'Secure' => ['cs_CZ' => 'nastavovat potřebná oprávnění pro zobrazení stránky', 'en_US' => 'change the needed permissions to display a page'] // TODO: implement
		],
		'Users' => [
			'Use' => ['cs_CZ' => 'používat správce uživatelů', 'en_US' => 'use the users admin panel'],
			'Add' => ['cs_CZ' => 'vytvářet nové uživatele', 'en_US' => 'create new users'],
			'Edit' => ['cs_CZ' => 'upravovat existující uživatele', 'en_US' => 'edit existing users'],
			'Delete' => ['cs_CZ' => 'mazat uživatele', 'en_US' => 'delete users'],
			'Ban' => ['cs_CZ' => 'banovat uživatele', 'en_US' => 'ban users'],
			'Secure' => ['cs_CZ' => 'přiřazovat uživatelům role', 'en_US' => 'assign roles to users']
		],
		'Roles' => [
			'Use' => ['cs_CZ' => 'používat správce rolí', 'en_US' => 'use the roles admin panel'],
			'Add' => ['cs_CZ' => 'vytvářet nové role', 'en_US' => 'create new roles'],
			'Edit' => ['cs_CZ' => 'upravovat existující role', 'en_US' => 'edit existing roles'],
			'Delete' => ['cs_CZ' => 'mazat role', 'en_US' => 'delete roles'],
			'Secure' => ['cs_CZ' => 'měnit oprávnění', 'en_US' => 'change permissions']
		]
	];

	protected $cmsInstalled;

	protected $container;

	protected $roleService;

	protected $resourceService;

	protected $privilegeService;

	protected $permissionService;

	protected $aclService;

	public function __construct($cmsInstalled,
								Nette\DI\Container $container,
	                            Service\RoleService $roleService,
	                            Service\ResourceService $resourceService,
	                            Service\PrivilegeService $privilegeService,
	                            Service\PermissionService $permissionService,
	                            Service\AclService $aclService) {
		$this->cmsInstalled = (bool)$cmsInstalled;
		$this->container = $container;
		$this->roleService = $roleService;
		$this->resourceService = $resourceService;
		$this->privilegeService = $privilegeService;
		$this->permissionService = $permissionService;
		$this->aclService = $aclService;
	}

	protected function createDefaultPermissions() {
		foreach($this->defaultPermissions as $resource => $privileges) {
			foreach($privileges as $privilege => $translations) {
				$resourceEntity = $this->resourceService->getBy(['name' => $resource]);
				$privilegeEntity = $this->privilegeService->getBy(['name' => $privilege]);
				$permission = $this->permissionService->createPermission($resourceEntity, $privilegeEntity);
				$permission->setTranslatableLocale('cs_CZ');
				$permission->note = $translations['cs_CZ'];
				$this->permissionService->persist($permission);
			}
		}
		$this->permissionService->flush();

		$permissions = $this->permissionService->fetchQueryObject(new ZaxCMS\Model\CMS\Query\PermissionQuery('en_US'));
		foreach($permissions as $permission) {
			$permission->note = $this->defaultPermissions[$permission->resource->name][$permission->privilege->name]['en_US'];
			$permission->setTranslatableLocale('en_US');
			$this->permissionService->persist($permission);
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
	public function create() {
		if(!$this->cmsInstalled) {
			return new Nette\Security\Permission;
		}
		$acl = $this->cache->load('acl');
		if($acl === NULL) {
			$acl = new Nette\Security\Permission;
			try {
				foreach($this->roleService->findAll() as $role) {
					$acl->addRole($role->name, $role->parent === NULL ? NULL : $role->parent->name);
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
			$this->cache->save('acl', $acl, [Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		}

		return $acl;
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->aclService->getEntityManager()->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
		$newAuthorizator = $this->createNetteAcl();
		$this->container->removeService('acl');
		$this->container->addService('acl', $newAuthorizator)->createService('acl');
		return $newAuthorizator;
	}

}