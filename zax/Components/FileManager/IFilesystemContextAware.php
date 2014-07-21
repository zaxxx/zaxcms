<?php

namespace Zax\Components\FileManager;
use Zax,
	Nette,
	DevModule;

/**
 * Interface IFilesystemContextAware
 *
 * @package Zax\Components\FileManager
 */
interface IFilesystemContextAware {

	/**
	 * @param $root string        Absolute path to root, eg. '/var/www/upload'
	 */
	public function setRoot($root);

	/**
	 * @return string               Absolute path to root, eg. '/var/www/upload'
	 * @throw RootNotSetException   If root dir not set
	 */
	public function getRoot();

	/**
	 * @param $directory string   Current folder, eg. '/general'
	 */
	public function setDirectory($directory);

	/**
	 * @return string             Current folder, eg. '/general'
	 */
	public function getDirectory();

	/**
	 * @return string               Absolute path to current folder, eg. '/var/www/upload/general'
	 * @throw RootNotSetException   If root dir not set
	 * @throw InvalidPathException  If the current path is invalid
	 */
	public function getAbsoluteDirectory();

}