<?php

namespace Zax\Security;
use Zax,
	Nette;

/**
 * Class DefaultPermission
 *
 * Simple default implementation of Permission
 *
 * @package Zax\Security
 */
class DefaultPermission extends Permission {

	protected $handlers = [];

	/**
	 * @param string $resource
	 * @param string $privilege
	 * @param $callback
	 * @return $this
	 */
	public function addHandler($resource, $privilege, $callback) {
		$this->handlers[$resource][$privilege] = $callback;
		return $this;
	}

	public function isUserAllowedTo($resource, $privilege, $params = []) {
		$can = $this->user->isAllowed($resource, $privilege);
		if(!$can && isset($this->handlers[$resource][$privilege])) {
			return $this->handlers[$resource][$privilege]($resource, $privilege, $params);
		}
		return $can;
	}

	/**
	 * Converts '@secured Resource, Privilege, id, whatever' into
	 * [ resource => Resource, privilege => Privilege, params => [id, whatever] ]
	 *
	 * @param $element
	 * @return array|null
	 */
	public function parseAnnotations($element) {
		/** @var Nette\Reflection\Method $element */
		if(!$element->hasAnnotation('secured') || strpos($security = $element->getAnnotation('secured'), ',') === FALSE) {
			return NULL;
		}

		$parts = explode(',', $security);
		$parts = array_map('trim', $parts);
		return [
			'resource' => array_shift($parts),
			'privilege' => array_shift($parts),
			'params' => $parts
		];
	}

	/**
	 * @param string $element
	 * @param array  $params
	 */
	public function checkAnnotationRequirements($element, $params = []) {
		$parsed = $this->parseAnnotations($element);
		if($parsed === NULL) {
			return;
		}

		$parsedParams = array_intersect_key($params, $parsed['params']);

		$this->checkRequirements($parsed['resource'], $parsed['privilege'], $parsedParams);
	}

	public function checkRequirements($resource, $privilege, $params = []) {
		if(!$this->isUserAllowedTo($resource, $privilege, $params)) {
			throw new ForbiddenRequestException;
		}
	}

}