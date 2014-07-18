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

	public function getGuestRole() {
		$role = $this->getDao()->findOneByName('guest');
		if($role === NULL) {
			$role = new ZaxCMS\Model\Role;
			$role->name = 'guest';
			$role->displayName = 'Anonymous user';
			$this->em->persist($role);
			$this->em->flush();
		}
		return $role;
	}

	public function getUserRole() {
		$role = $this->getDao()->findOneByName('user');
		if($role === NULL) {
			$role = new ZaxCMS\Model\Role;
			$role->name = 'user';
			$role->displayName = 'Registered user';
			$this->getRepository()->persistAsLastChildOf($role, $this->getGuestRole());
			$this->em->flush();
		}
		return $role;
	}

	public function getAdminRole() {
		$role = $this->getDao()->findOneByName('admin');
		if($role === NULL) {
			$role = new ZaxCMS\Model\Role;
			$role->name = 'admin';
			$role->displayName = 'Web administrator';
			$this->getRepository()->persistAsLastChildOf($role, $this->getUserRole());
			$this->em->flush();
		}
		return $role;
	}

	public function getDeveloperRole() {
		$role = $this->getDao()->findOneByName('dev');
		if($role === NULL) {
			$role = new ZaxCMS\Model\Role;
			$role->name = 'dev';
			$role->displayName = 'Web developer';
			$this->getRepository()->persistAsLastChildOf($role, $this->getAdminRole());
			$this->em->flush();
		}
		return $role;
	}

}