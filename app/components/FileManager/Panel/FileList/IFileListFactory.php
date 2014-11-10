<?php

namespace ZaxCMS\Components\FileManager;

interface IFileListFactory {

	/** @return FileListControl */
	public function create();

}


trait TInjectFileListFactory {

	protected $fileListFactory;

	public function injectFileListFactory(IFileListFactory $fileListFactory) {
		$this->fileListFactory = $fileListFactory;
	}

}

