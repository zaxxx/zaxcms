<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IFileManagerFactory {

	/** @return FileManagerControl */
	public function create();

}


trait TInjectFileManagerFactory {

	protected $fileManagerFactory;

	public function injectFileManagerFactory(IFileManagerFactory $fileManagerFactory) {
		$this->fileManagerFactory = $fileManagerFactory;
	}

}

