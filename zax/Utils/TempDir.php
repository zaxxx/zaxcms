<?php

namespace Zax\Utils;
use Zax,
	Nette;

/**
 * Class TempDir
 *
 * Temp dir provider
 *
 * @package Zax\Utils
 */
class TempDir extends Nette\Object {

	protected $tempDir;

	public function __construct($tempDir) {
		$this->tempDir = realpath($tempDir);
	}

	public function getTempDir() {
		return $this->tempDir;
	}

	public function __toString() {
		return (string)$this->getTempDir();
	}

}


trait TInjectTempDir {

	protected $tempDir;

	public function injectTempDir(TempDir $tempDir) {
		$this->tempDir = $tempDir;
	}

}

