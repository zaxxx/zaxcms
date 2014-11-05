<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IRenameFileFactory {

	/** @return RenameFileControl */
	public function create();

}


trait TInjectRenameFileFactory {

	protected $renameFileFactory;

	public function injectRenameFileFactory(IRenameFileFactory $renameFileFactory) {
		$this->renameFileFactory = $renameFileFactory;
	}

}

