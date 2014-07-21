<?php

namespace Zax;
use Zax,
	Nette;

interface IBootstrap {

	/** @return Nette\DI\Container */
	public function setUp();

	public function boot();

}