<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IDeleteFileFactory {

	/** @return DeleteFileControl */
	public function create();

}