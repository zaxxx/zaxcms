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
	 * @param string $resource
	 * @param string $privilege
	 * @param mixed|NULL $id
	 * @return bool
	 */
	public function isUserAllowedTo($resource, $privilege, $id = NULL);

	/**
	 * @param $element
	 * @return mixed
	 */
	public function checkRequirements($element, $id = NULL);

}