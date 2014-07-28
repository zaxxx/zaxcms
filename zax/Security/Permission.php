<?php

namespace Zax\Security;
use Zax,
	ZaxCMS,
	ZaxCMS\Model,
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
	 * ->isUserAllowedTo('Article', 'Edit', 5);
	 *
	 * @param string $resource
	 * @param string $privilege
	 * @param mixed|NULL $id
	 * @return bool
	 */
	abstract public function isUserAllowedTo($resource, $privilege, $id = NULL);

	/**
	 * @param $element
	 * @throws ForbiddenRequestException
	 * @return void
	 */
	abstract public function checkRequirements($element, $id = NULL);

}