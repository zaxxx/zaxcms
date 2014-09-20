<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

interface IQueryObjectFilter {

	public function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject);

}