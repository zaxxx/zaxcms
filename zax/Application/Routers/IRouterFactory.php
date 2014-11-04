<?php

namespace Zax\Application\Routers;
use Zax,
	Nette;

interface IRouterFactory {

	/** @return Nette\Application\IRouter */
	public function create();

}