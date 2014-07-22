<?php

namespace ZaxCMS\Components\FileManager;

interface IFileManipulation extends IFilesystemContextAware {

	/**
	 * @param string $file
	 */
	public function setFile($file);

}
