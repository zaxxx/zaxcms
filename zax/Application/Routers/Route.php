<?php

namespace Zax\Application\Routers;
use Zax,
	Nette;

class Route extends Nette\Application\Routers\Route {

	/**
	 * @param $name
	 * @return mixed
	 */
	static protected function findTokens($name) {
		return Nette\Utils\Strings::match($name, '~(\<[a-z-]\>)+~i');
	}

	/**
	 * @param $tokens
	 * @param $params
	 * @param $aliases
	 * @param $fullName
	 * @return mixed
	 */
	static protected function createFullName($tokens, $params, $aliases, $fullName) {
		foreach($tokens as $token) {
			$token2 = substr($token, 1, -1);
			if(isset($params[$token2])) {
				$fullName = str_replace($token, $params[$token2], $fullName);
			} else if(isset($aliases[$token2]) && isset($params[$aliases[$token2]])) {
				$fullName = str_replace($token, $params[$aliases[$token2]], $fullName);
			}
		}
		return $fullName;
	}

	/**
	 * Helps with shortening component names.
	 *
	 * @param $aliases array of [$alias => $fullName]
	 * @return array to be used as metadata for router
	 */
	static public function createAliases($aliases/**, $doAliases = array()*/) {
		$doAliases = array(); // TODO
		return array(
			self::FILTER_IN => function($params) use ($aliases, $doAliases) {
				if(isset($params['do'])) {
					foreach($doAliases as $doAlias => $doFullName) {
						$doTokens = self::findTokens($doFullName);
						if($doTokens) {
							$doFullName = self::createFullName($doTokens, $params, $aliases, $doFullName);
						}
						if($params['do'] == $doAlias)
							$params['do'] = $doFullName;
					}
				}
				foreach($aliases as $alias => $fullName) {
					if(isset($params[$alias])) {
						$tokens = self::findTokens($fullName);
						if($tokens) {
							$fullName = self::createFullName($tokens, $params, $aliases, $fullName);
						}
						$params[$fullName] = $params[$alias];
						unset($params[$alias]);
					}
				}

				return $params;
			},
			self::FILTER_OUT => function($params) use ($aliases, $doAliases) {
				if(isset($params['do'])) {
					foreach($doAliases as $doAlias => $doFullName) {
						$doTokens = self::findTokens($doFullName);
						if($doTokens) {
							$doFullName = self::createFullName($doTokens, $params, $aliases, $doFullName);
						}
						if($params['do'] == $doFullName)
							$params['do'] = $doAlias;
					}
				}
				foreach($aliases as $alias => $fullName) {
					$tokens = self::findTokens($fullName);
					if($tokens) {
						$fullName = self::createFullName($tokens, $params, $aliases, $fullName);
					}
					if(isset($params[$fullName])) {
						$params[$alias] = $params[$fullName];
						unset($params[$fullName]);
					}
				}

				return $params;
			}
		);
	}

	/**
	 * Metadata factory. First two parameters can be replaced with one parameter - array of metadata.
	 *
	 * @param       $presenter
	 * @param null  $action
	 * @param array $aliases
	 * @param array $persistentBoolParams
	 * @return array
	 */
	static public function createMetadata($presenter, $action = NULL, $aliases = array(), $persistentBoolParams = array()) {

		if(is_array($presenter)) {
			$metadata = $presenter;
			list(,$aliases, $persistentBoolParams) = func_get_args();
		} else {
			$metadata = [
				'presenter' => $presenter
			];

			if(strlen($action) > 0) {
				$metadata['action'] = $action;
			}
		}


		if(count($aliases) > 0) {
			$metadata[NULL] = self::createAliases($aliases);
		}

		if(count($persistentBoolParams) > 0) {
			foreach($persistentBoolParams as $param) {
				$metadata[$param] = [
					self::FILTER_IN => function() {
						return TRUE;
					},
					self::FILTER_OUT => function() use ($param) {
						return $param;
					}
				];
			}
		}

		return $metadata;
	}

	/**
	 * @alias
	 *
	 * @param       $presenter
	 * @param null  $action
	 * @param array $aliases
	 * @param array $persistentBoolParams
	 * @return array
	 */
	static public function meta($presenter, $action = NULL, $aliases = array(), $persistentBoolParams = array()) {
		return self::createMetadata($presenter, $action, $aliases, $persistentBoolParams);
	}

}
