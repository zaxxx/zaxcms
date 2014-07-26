<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby;


class RoleService extends Service {

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Role::getClassName();
	}

	public function createRole($name, $displayName, Role $parent = NULL) {
		$role = new Role;
		$role->name = $name;
		$role->displayName = $displayName;
		if($parent === NULL) {
			$this->getEm()->persist($role);
		} else {
			$this->getRepository()->persistAsLastChildOf($role, $parent);
		}
		return $role;
	}

	public function createDefaultRoles() {
		$guest = $this->createRole('guest', 'Anonymous user');
		$user = $this->createRole('user', 'Registered user', $guest);
		$admin = $this->createRole('admin', 'Site admin', $user);
		$this->flush();
	}

	public function getGuestRole() {
		return $this->getDao()->findOneByName('guest');
	}

	public function getUserRole() {
		return $this->getDao()->findOneByName('user');
	}

	public function getAdminRole() {
		return $this->getDao()->findOneByName('admin');
	}

}