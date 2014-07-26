<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class AclService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Acl::getClassName();
	}

	public function createAclEntry(Role $role, Permission $permission, $allow = FALSE) {
		$acl = new Acl;
		$acl->role = $role;
		$acl->permission = $permission;
		$acl->allow = (bool) $allow;

		$this->persist($acl);
		return $acl;
	}

}