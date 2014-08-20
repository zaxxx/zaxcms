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
	 * @param array $params
	 * @return bool
	 */
	public function isUserAllowedTo($resource, $privilege, $params = []);

	/**
	 * @param string $resource
	 * @param string $privilege
	 * @param array $params
	 * @throws ForbiddenRequestException
	 */
	public function checkRequirements($resource, $privilege, $params = []);

	/**
	 * @param string $element
	 * @param array $params
	 * @throws ForbiddenRequestException
	 */
	public function checkAnnotationRequirements($element, $params = []);

}