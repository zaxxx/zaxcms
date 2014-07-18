<?php

namespace Zax\Utils;
use Zax,
	Nette;

/**
 * Class AppDir
 *
 * Application dir provider
 *
 * @package Zax\Utils
 */
class AppDir extends Nette\Object {

	protected $appDir;

	public function __construct($appDir) {
		$this->appDir = realpath($appDir);
	}

	public function getAppDir() {
		return $this->appDir;
	}

	public function __toString() {
		return (string)$this->getAppDir();
	}

}