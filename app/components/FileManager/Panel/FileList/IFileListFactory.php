<?php

namespace ZaxCMS\Components\FileManager;

interface IFileListFactory {

	/** @return FileListControl */
	public function create();

}