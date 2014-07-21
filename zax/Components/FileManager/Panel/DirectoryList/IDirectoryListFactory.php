<?php

namespace Zax\Components\FileManager;

interface IDirectoryListFactory {

	/** @return DirectoryListControl */
	public function create();

}