<?php

namespace Zax\Security;
use Zax,
	Nette;

interface IAclFactory {

	/** @return Nette\Security\IAuthorizator */
	public function create();

}