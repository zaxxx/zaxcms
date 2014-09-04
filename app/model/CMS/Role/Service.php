<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby;


class RoleService extends Zax\Model\Service {

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Role::getClassName();
	}

	public function createRole($name, $displayName, Entity\Role $parent = NULL) {
		$role = $this->create();
		$role->name = $name;
		$role->displayName = $displayName;
		if($parent === NULL) {
			$this->persist($role);
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

	public function getByName($name) {
		return $this->getBy(['name' => $name]);
	}

	public function getGuestRole() {
		return $this->getByName('guest');
	}

	public function getUserRole() {
		return $this->getByName('user');
	}

	public function getAdminRole() {
		return $this->getByName('admin');
	}

}