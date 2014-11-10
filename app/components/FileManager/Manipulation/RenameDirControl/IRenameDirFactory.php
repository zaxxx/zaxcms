<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IRenameDirFactory {

	/** @return RenameDirControl */
	public function create();

}


trait TInjectRenameDirFactory {

	protected $renameDirFactory;

	public function injectRenameDirFactory(IRenameDirFactory $renameDirFactory) {
		$this->renameDirFactory = $renameDirFactory;
	}

}

