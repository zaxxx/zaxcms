<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

interface IFilterable {

	public function getFilteredResultSet();

}