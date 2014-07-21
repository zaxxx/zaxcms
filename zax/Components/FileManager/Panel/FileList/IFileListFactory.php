<?php

namespace Zax\Components\FileManager;

interface IFileListFactory {

	/** @return FileListControl */
	public function create();

}