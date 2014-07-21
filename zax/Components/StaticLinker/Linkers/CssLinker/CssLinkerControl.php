<?php

namespace Zax\Components\StaticLinker;
use Nette,
	Zax,
	Zax\Application\UI\Control;

class CssLinkerControl extends StaticLinkerAbstract {

	/**
	 * @return string
	 */
	protected function getExtension() {
		return 'css';
	}

	/**
	 * @return string
	 */
	protected function getCacheKey() {
		return 'combinedCssName';
	}

	public function viewDefault() {

	}

}