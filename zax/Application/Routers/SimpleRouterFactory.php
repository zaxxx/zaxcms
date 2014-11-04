<?php

namespace Zax\Application\Routers;
use Nette,
	Nette\Application\Routers,
	Zax;

final class SimpleRouterFactory implements IRouterFactory {

	/** @return Nette\Application\Routers\SimpleRouter */
	public function create() {
		return new Routers\SimpleRouter('Web:Front:Default:default');
	}

}