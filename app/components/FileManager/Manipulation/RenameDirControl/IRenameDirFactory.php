<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IRenameDirFactory {

	/** @return RenameDirControl */
	public function create();

}