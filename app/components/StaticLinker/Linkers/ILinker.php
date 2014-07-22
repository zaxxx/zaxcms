<?php

namespace ZaxCMS\Components\StaticLinker;
use Zax,
	Nette;

interface ILinker {

	public function addFile($file);

	public function setRoot($root);

	public function setOutputDirectory($directory);

}