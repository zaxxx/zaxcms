<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class AclService extends Zax\Model\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Acl::getClassName();
	}

	public function createAclEntry(Entity\Role $role, Entity\Permission $permission, $allow = FALSE) {
		$acl = $this->create();
		$acl->role = $role;
		$acl->permission = $permission;
		$acl->allow = (bool) $allow;

		$this->persist($acl);
		return $acl;
	}

}