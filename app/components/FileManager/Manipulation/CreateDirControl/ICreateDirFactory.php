<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface ICreateDirFactory {

	/** @return CreateDirControl */
	public function create();

}


trait TInjectCreateDirFactory {

	protected $createDirFactory;

	public function injectCreateDirFactory(ICreateDirFactory $createDirFactory) {
		$this->createDirFactory = $createDirFactory;
	}

}

