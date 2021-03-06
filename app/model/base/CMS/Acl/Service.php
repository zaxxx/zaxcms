<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class AclService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Acl::getClassName();
	}

	/** @internal */
	public function createAclEntry(Entity\Role $role, Entity\Permission $permission, $allow = FALSE) {
		$acl = $this->create();
		$acl->role = $role;
		$acl->permission = $permission;
		$acl->allow = (bool) $allow;

		$this->persist($acl);
		return $acl;
	}

	public function allow(Entity\Role $role, Entity\Permission $permission) {
		if($role->hasReadOnlyPermissions()) {
			throw new Zax\Security\ForbiddenRequestException('This role has read-only permissions.');
		}
		$acl = $this->getBy(['role.id' => $role->id, 'permission.id' => $permission->id]);
		if($acl === NULL) {
			$acl = $this->create();
			$acl->role = $role;
			$acl->permission = $permission;
		}
		$acl->allow = TRUE;
		$this->persist($acl);
		return $acl;
	}

	public function deny(Entity\Role $role, Entity\Permission $permission) {
		if($role->hasReadOnlyPermissions()) {
			throw new Zax\Security\ForbiddenRequestException('This role has read-only permissions.');
		}
		$acl = $this->getBy(['role.id' => $role->id, 'permission.id' => $permission->id]);
		if($acl === NULL) {
			$acl = $this->create();
			$acl->role = $role;
			$acl->permission = $permission;
		}
		$acl->allow = FALSE;
		$this->persist($acl);
		return $acl;
	}

}



trait TInjectAclService {

	/** @var AclService */
	protected $aclService;

	public function injectAclService(AclService $aclService) {
		$this->aclService = $aclService;
	}

}

