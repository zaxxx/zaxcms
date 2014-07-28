<?php

namespace Zax\Security;
use Zax,
	ZaxCMS,
	ZaxCMS\Model,
	Nette;

/**
 * Class DefaultPermission
 *
 * Simple default implementation of Permission
 *
 * @package Zax\Security
 */
class DefaultPermission extends Permission {

	public function isUserAllowedTo($privilege, $resource, $id = NULL) {
		return $this->user->isAllowed($resource, $privilege);
	}

	public function checkRequirements($element, $id = NULL) {
		/** @var Nette\Reflection\Method $element */
		if(!$element->hasAnnotation('secured')) {
			return;
		}

		$security = $element->getAnnotation('secured');
		list($privilege, $resource) = explode(',', $security);

		if(!$this->isUserAllowedTo(trim($privilege), trim($resource), $id)) {
			throw new ForbiddenRequestException;
		}
	}

}