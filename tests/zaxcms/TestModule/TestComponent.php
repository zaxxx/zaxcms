<?php

namespace ZaxCMS\TestModule;
use Zax,
	ZaxCMS;

class TestControl extends Zax\Application\UI\Control {

	/** @persistent */
	public $stringParam = '';

	/** @persistent */
	public $boolParam = FALSE;

}