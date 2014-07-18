<?php

namespace Zax\Utils;
use Zax,
	Nette;

/**
 * Class RootDir
 *
 * Root dir provider
 *
 * @package Zax\Utils
 */
class RootDir extends Nette\Object {

	protected $rootDir;

	public function __construct($rootDir) {
		$this->rootDir = realpath($rootDir);
	}

	public function getRootDir() {
		return $this->rootDir;
	}

	public function __toString() {
		return (string)$this->getRootDir();
	}

}