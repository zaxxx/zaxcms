<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

abstract class FileManipulationControl extends FileManagerAbstract implements IFileManipulation {

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @param $file
	 * @return $this
	 */
	public function setFile($file) {
		$this->file = $file;
		return $this;
	}

}