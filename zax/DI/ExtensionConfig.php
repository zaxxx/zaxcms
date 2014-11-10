<?php

namespace Zax\DI;
use Zax,
	Nette;

abstract class ExtensionConfig extends Nette\Object {

	protected $sections = [];

	protected $config;

	public function __construct(array $config) {
		$this->config = $config;
		$this->sections = array_keys($config);
	}

	/**
	 * If callback $condition($value, $key) returns TRUE, set $value to $result($value, $key)
	 */
	protected function processPattern($condition, $result) {
		$cb = function(&$value, $key, $userdata) {
			$condition = $userdata[0];
			$result = $userdata[1];
			if($condition($value, $key)) {
				$value = $result($value, $key);
			}
		};
		array_walk_recursive($this->config, $cb, [$condition, $result]);
	}

	public function getConfig() {
		return $this->config;
	}

}