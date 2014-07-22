<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IFileManagerFactory {

	/** @return FileManagerControl */
	public function create();

}