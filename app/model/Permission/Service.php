<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class PermissionService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Permission::getClassName();
	}

	public function createPermission(Resource $resource, Privilege $privilege) {
		$perm = new Permission;
		$perm->resource = $resource;
		$perm->privilege = $privilege;

		$this->persist($perm);
		return $perm;
	}

}