<?php

namespace Zax\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IDeleteDirFactory {

	/** @return DeleteDirControl */
	public function create();

}