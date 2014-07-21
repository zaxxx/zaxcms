<?php

namespace Zax\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IRenameFileFactory {

	/** @return RenameFileControl */
	public function create();

}