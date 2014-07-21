<?php

namespace ZaxCMS\TestModule;
use Zax,
	ZaxCMS;

interface ITestComponentFactory {

	/** @return TestControl */
	public function create();

}