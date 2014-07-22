<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface ICreateDirFactory {

	/** @return CreateDirControl */
	public function create();

}