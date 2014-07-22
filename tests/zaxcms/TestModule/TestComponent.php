<?php

namespace ZaxCMS\TestModule;
use Zax,
	ZaxCMS;

class TestControl extends Zax\Application\UI\Control {

	/** @persistent */
	public $stringParam = '';

	/** @persistent */
	public $boolParam = FALSE;

	public function viewDefault() {

	}

	public function viewFoo() {

	}

	public function beforeRender() {

	}

	public function beforeRenderBar() {

	}

}