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

	public function createRole($name, $displayName, Entity\Role $parent = NULL, $special = NULL) {
		$role = $this->create();
		$role->name = $name;
		$role->displayName = $displayName;
		$role->special = $special;
		if($parent === NULL) {
			$this->persist($role);
		} else {
			$this->getRepository()->persistAsLastChildOf($role, $parent);
		}
		return $role;
	}

	public function createDefaultRoles() {
		$guest = $this->createRole('guest', 'Anonymous user', NULL, Entity\Role::GUEST_ROLE);
		$user = $this->createRole('user', 'Registered user', $guest, Entity\Role::USER_ROLE);
		$admin = $this->createRole('admin', 'Site admin', $user, Entity\Role::ADMIN_ROLE);
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

	public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
		return $children;
	}

}