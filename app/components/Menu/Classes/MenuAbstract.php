<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	Gedmo,
	Zax;

abstract class MenuAbstract extends Nette\Object {

	protected $data;

	protected $privileges;

	/** @var Gedmo\Tree\Entity\Repository\NestedTreeRepository|NULL */
	protected $menuRepository;

	public function __construct($data = []) {
		$this->data = $data;
	}

	public function setRepository(Gedmo\Tree\Entity\Repository\NestedTreeRepository $repository) {
		$this->menuRepository = $repository;
		return $this;
	}

	protected function getData($key, $default = NULL) {
		if(is_object($this->data)) {
			if(isset($this->data->$key)) {
				return $this->data->$key;
			} else {
				return $default;
			}
		} else if(is_array($this->data)) {
			if(isset($this->data[$key])) {
				return $this->data[$key];
			} else {
				return $default;
			}
		}
	}

	public function getHtmlClass() {
		return $this->getData('htmlClass');
	}

	public function getSecured() {
		return $this->getData('secured', FALSE);
	}

	public function getResource() {
		return $this->getData('resource');
	}

	protected function getPrivilege() {
		return $this->getData('privilege');
	}

	public function getPrivileges() {
		if($this->privileges === NULL) {
			$privilege = $this->getPrivilege();
			$privileges = [$privilege];
			if(strpos($privilege, '|') > 0) {
				$privileges = explode('|', $privilege);
			}
			$this->privileges = $privileges;
		}
		return $this->privileges;
	}

	public function isUserAllowed(Nette\Security\User $user) {
		if($this->getSecured()) {
			$pass = FALSE;
			foreach($this->getPrivileges() as $privilege) {
				if($user->isAllowed($this->getResource(), $privilege)) {
					$pass = TRUE;
				}
			}
			return $pass;
		}
		return TRUE;
	}

}
