<?php

namespace Zax\Components\StaticLinker;
use Nette,
	Zax;

interface IMinifier {

	public function minify($text);

}