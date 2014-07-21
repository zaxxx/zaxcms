<?php

namespace Zax\Application\UI;
use Nette,
	Zax\Application\UI;

/**
 * Class SecuredControl
 *
 * Adds syntax sugar for checking permissions.
 *
 * @package Zax\Application\UI
 */
abstract class SecuredControl extends UI\Control {

	/** @var Nette\Security\User */
	protected $user;

	public function injectPrimary(Nette\Security\User $user) {
		$this->user = $user;
	}

	/**
	 * $this->isAllowed('Edit') -> $this->user->isAllowed(@resource, 'Edit')
	 * $this->isAllowed('Article', 'Edit') -> $this->user->isAllowed('Article', 'Edit')
	 *
	 * @param string $resource (@optional if @resource annotation is set)
	 * @param type $privilege
	 * @return bool
	 */
	public function isAllowed($resource, $privilege = NULL) {
		if($privilege === NULL) {
			$ref = self::getReflection();
			if($ref->hasAnnotation('resource')) {
				$privilege = $resource;
				$resource = $ref->getAnnotation('resource');
			}
		}

		return $this->user->isAllowed($resource, $privilege);
	}

	/**
	 * @param $element
	 * @throws \Nette\Application\ForbiddenRequestException
	 */
	public function checkRequirements($element) {
		$secured = $element->getAnnotation('secured');
		$resource = $element->getAnnotation('resource');
		$privilege = $element->getAnnotation('privilege');

		if($secured && (($resource && !$this->isAllowed($resource, $privilege)) || ($privilege && !$this->isAllowed($privilege)))) {
			throw new Nette\Application\ForbiddenRequestException;
		}
	}

	/**
	 *  $this->canEdit -> $this->isAllowed('Edit') -> $this->user->isAllowed(@resource, 'Edit')
	 */
	public function & __get($name) {
		if(strpos($name, 'can') === 0) {
			$can = $this->isAllowed(substr($name, 3));
			return $can;
		}
		return parent::__get($name);
	}

}