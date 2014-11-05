<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby;


class RoleService extends Zax\Model\Doctrine\Service {

	use Zax\Traits\TTranslatable;

	protected $locale;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Role::getClassName();
	}

	public function getRepository() {
		return parent::getRepository()->setLocale($this->getLocale());
	}

	public function getGuestRole() {
		return $this->getBy(['special' => Entity\Role::GUEST_ROLE]);
	}

	public function getUserRole() {
		return $this->getBy(['special' => Entity\Role::USER_ROLE]);
	}

	public function getAdminRole() {
		return $this->getBy(['special' => Entity\Role::ADMIN_ROLE]);
	}

	public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
		return $children;
	}

	public function getFormSelectOptions() {
		$options = [];
		$guest = $this->getGuestRole();
		$children = $this->getChildren($guest);
		foreach($children as $child) {
			$options[$child->id] = $child->displayName;
		}
		return $options;
	}

}


trait TInjectRoleService {

	/** @var RoleService */
	protected $roleService;

	public function injectRoleService(RoleService $roleService) {
		$this->roleService = $roleService;
	}

}

