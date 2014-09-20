<?php

namespace Zax\Security;
use Zax,
	Nette;

/**
 * Class Permission
 *
 * Abstract implementation of Permission
 *
 * @package Zax\Security
 */
abstract class Permission extends Nette\Object implements IPermission {

	/** @var Nette\Security\Permission */
	protected $acl;

	/** @var Nette\Security\User */
	protected $user;

	public function injectUser(Nette\Security\User $user) {
		$this->user = $user;
	}

	public function injectAcl(Nette\Security\Permission $acl) {
		$this->acl = $acl;
	}

	public function getAcl() {
		return $this->acl;
	}

	/**
	 * @param string $resource
	 * @param string $privilege
	 * @param array $params
	 * @return bool
	 */
	abstract public function isUserAllowedTo($resource, $privilege, $params = []);

	/**
	 * @param string $resource
	 * @param string $privilege
	 * @param array $params
	 * @throws ForbiddenRequestException
	 */
	abstract public function checkRequirements($resource, $privilege, $params = []);

	/**
	 * @param string $element
	 * @param array $params
	 * @throws ForbiddenRequestException
	 */
	abstract public function checkAnnotationRequirements($element, $params = []);

}