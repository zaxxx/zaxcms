<?php

namespace Zax\Utils;
use Nette,
	Zax;

/**
 * Class PathHelpers
 *
 * Quick'n'dirty path helpers. >Should< work fine on both Windows and Linux.
 *
 * @package Zax\Utils
 */
class PathHelpers extends Nette\Object {

	/**
	 * @param      $dir
	 * @param      $subdir
	 * @param bool $syntaxOnly
	 * @return bool
	 */
	static public function isSubdirOf($dir, $subdir, $syntaxOnly = FALSE) {
		if(!$syntaxOnly) {
			$dir = realpath($dir);
			$subdir = realpath($subdir);
		}
		return (strpos($subdir, $dir) === 0) && (strlen($subdir) >= strlen($dir));
	}

	/**
	 * @param $dir1
	 * @param $dir2
	 * @return bool
	 */
	static public function isEqual($dir1, $dir2) {
		return realpath($dir1) == realpath($dir2);
	}

	/**
	 * @param $dir
	 * @return mixed
	 */
	static public function getName($dir) {
		$ex = explode(strrpos($dir, '/') > strrpos($dir, '\\') ? '/' : '\\', $dir);
		return count($ex) === 0 ? $dir : $ex[count($ex)-1];
	}

	/**
	 * @param $dir
	 * @return int
	 */
	static public function getDepth($dir) {
		$dir = realpath($dir);
		return substr_count($dir, '/') + substr_count($dir, '\\');
	}

	/**
	 * @param $basePath
	 * @param $rootDir
	 * @param $file
	 * @return string
	 */
	static public function getPath($basePath, $rootDir, $file) {
		return $basePath . str_replace(realpath($rootDir), '', $file->getRealPath());
	}

	/**
	 * @param $dir
	 * @return string
	 */
	static public function getParentDir($dir) {
		return rtrim(self::rename($dir, ''), '\\\/');
	}

	/**
	 * @param $path
	 * @param $newName
	 * @return mixed
	 */
	static public function rename($path, $newName) {
		$name = self::getName($path);
		if(($pos = strrpos($path, $name)) !== FALSE) {
			$sl = strlen($name);
			$rep = substr_replace($path, $newName, $pos, $sl);
			return $rep;
		}
		return $path;
	}

}
