<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IDeleteDirFactory {

	/** @return DeleteDirControl */
	public function create();

}


trait TInjectDeleteDirFactory {

	protected $deleteDirFactory;

	public function injectDeleteDirFactory(IDeleteDirFactory $deleteDirFactory) {
		$this->deleteDirFactory = $deleteDirFactory;
	}

}

