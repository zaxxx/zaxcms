<?php

namespace Zax\Components\StaticLinker;
use Nette,
	Zax,
	Zax\Application\UI\Control;

class JsLinkerControl extends StaticLinkerAbstract {

	/**
	 * @return string
	 */
	protected function getExtension() {
		return 'js';
	}

	/**
	 * @return string
	 */
	protected function getCacheKey() {
		return 'combinedJsName';
	}

	public function viewDefault() {

	}
}