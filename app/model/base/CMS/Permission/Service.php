<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class PermissionService extends Zax\Model\Doctrine\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Permission::getClassName();
	}

	public function createPermission(Entity\Resource $resource, Entity\Privilege $privilege) {
		$perm = $this->create();
		$perm->resource = $resource;
		$perm->privilege = $privilege;

		return $perm;
	}

}