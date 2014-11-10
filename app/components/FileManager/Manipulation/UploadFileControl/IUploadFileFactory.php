<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IUploadFileFactory {

	/** @return UploadFileControl */
	public function create();

}


trait TInjectUploadFileFactory {

	protected $uploadFileFactory;

	public function injectUploadFileFactory(IUploadFileFactory $uploadFileFactory) {
		$this->uploadFileFactory = $uploadFileFactory;
	}

}

