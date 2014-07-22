<?php

namespace ZaxCMS\Components\FileManager;

interface IDirectoryListFactory {

	/** @return DirectoryListControl */
	public function create();

}