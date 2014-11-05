<?php

namespace ZaxCMS\Components\FileManager;

interface IDirectoryListFactory {

	/** @return DirectoryListControl */
	public function create();

}


trait TInjectDirectoryListFactory {

	protected $directoryListFactory;

	public function injectDirectoryListFactory(IDirectoryListFactory $directoryListFactory) {
		$this->directoryListFactory = $directoryListFactory;
	}

}

