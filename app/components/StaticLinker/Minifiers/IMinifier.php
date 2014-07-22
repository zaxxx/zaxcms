<?php

namespace ZaxCMS\Components\StaticLinker;
use Nette,
	Zax;

interface IMinifier {

	public function minify($text);

}