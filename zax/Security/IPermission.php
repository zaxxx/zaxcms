<?php

namespace Zax\Security;

/**
 * Interface IPermission
 *
 * General purpose security check.
 *
 * @package Zax\Security
 */
interface IPermission {

	/**
	 * @param string $privilege
	 * @param string $resource
	 * @param mixed|NULL $id
	 * @return bool
	 */
	public function isUserAllowedTo($privilege, $resource, $id = NULL);

	/**
	 * @param $element
	 * @return mixed
	 */
	public function checkRequirements($element, $id = NULL);

}