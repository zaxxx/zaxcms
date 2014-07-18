<?php

namespace Zax\Components\StaticLinker;
use Nette,
	Zax;

class NoMinifier extends Nette\Object implements IMinifier {

	/**
	 * @param $text
	 * @return mixed
	 */
	public function minify($text) {
		return $text;
	}

}