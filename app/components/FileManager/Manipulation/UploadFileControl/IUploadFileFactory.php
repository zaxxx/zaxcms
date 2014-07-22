<?php

namespace ZaxCMS\Components\FileManager;
use Zax,
	Nette,
	DevModule;

interface IUploadFileFactory {

	/** @return UploadFileControl */
	public function create();

}