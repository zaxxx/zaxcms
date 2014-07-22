<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IRenameFileFactory {

	/** @return RenameFileControl */
	public function create();

}