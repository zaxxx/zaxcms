<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

interface IDoctrineService extends Zax\Model\IService {

	/** @return Kdyby\Doctrine\ResultSet */
	public function fetchQueryObject(Kdyby\Doctrine\QueryObject $queryObject);

}

