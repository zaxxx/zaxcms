<?php

namespace Zax\Utils;
use Nette,
	Zax;

class Arrays extends Nette\Utils\Arrays {

	public static function stringToObjects($value, $factory, $separator = ',') {
		$values = array_filter(array_map('trim', explode($separator, $value)));
		array_walk($values, function(&$value) use ($factory) {
			$value = $factory($value);
		});
		return $values;
	}

	public static function objectsToString($entities, $filter, $separator = ', ') {
		if(!$entities) {
			return '';
		}
		$values = [];
		foreach($entities as $entity) {
			$values[] = $filter($entity);
		}
		return implode($separator, $values);
	}

	/**
	 * $stack = ['a' => TRUE, 'b' => FALSE, 'c' => FALSE]
	 * $values = ['b', 'c']
	 * result = ['a' => FALSE, 'b' => TRUE, 'c' => TRUE]
	 *
	 * @param      $stack
	 * @param      $values
	 */
	public static function checkboxlistToBooleans($stack, $values) {
		$keys = array_keys($stack);
		foreach($keys as $key) {
			if(in_array($key, $values)) {
				$stack[$key] = TRUE;
			} else {
				$stack[$key] = FALSE;
			}
		}
		return $stack;
	}

	public static function booleansToCheckboxlist($stack) {
		return array_keys(array_filter($stack));
	}

	/**
	 * @alias
	 */
	public static function boolToCbl($stack) {
		return self::booleansToCheckboxlist($stack);
	}

	/**
	 * @alias
	 */
	public static function cblToBool($stack, $values, $positiveValue = TRUE, $negativeValue = FALSE) {
		return self::checkboxlistToBooleans($stack, $values, $positiveValue, $negativeValue);
	}

}