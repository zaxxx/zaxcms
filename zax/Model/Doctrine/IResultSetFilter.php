<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

interface IResultSetFilter {

	public function filterResultSet(Kdyby\Doctrine\ResultSet $resultSet);

}