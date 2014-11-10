<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IDeleteFileFactory {

	/** @return DeleteFileControl */
	public function create();

}


trait TInjectDeleteFileFactory {

	protected $deleteFileFactory;

	public function injectDeleteFileFactory(IDeleteFileFactory $deleteFileFactory) {
		$this->deleteFileFactory = $deleteFileFactory;
	}

}

