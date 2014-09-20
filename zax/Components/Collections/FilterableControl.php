<?php

namespace Zax\Components\Collections;
use Zax,
	Zax\Model\Doctrine\IFilterable,
	Kdyby,
	Nette;

abstract class FilterableControl extends Zax\Application\UI\SecuredControl implements IFilterable {

	/** @return Kdyby\Doctrine\QueryObject */
	abstract protected function createQueryObject();

	/** @return Zax\Model\Doctrine\IDoctrineService */
	abstract protected function getService();

	protected function filterQueryObject(Kdyby\Doctrine\QueryObject $queryObject) {
		foreach($this->getComponents(FALSE, 'Zax\Model\Doctrine\IQueryObjectFilter') as $queryObjectFilter) {
			/** @var IQueryObjectFilter $queryObjectFilter */
			$queryObjectFilter->filterQueryObject($queryObject);
		}
		return $queryObject;
	}

	protected function filterResultSet(Kdyby\Doctrine\ResultSet $resultSet) {
		foreach($this->getComponents(FALSE, 'Zax\Model\Doctrine\IResultSetFilter') as $resultSetFilter) {
			/** @var IResultSetFilter $resultSetFilter */
			$resultSetFilter->filterResultSet($resultSet);
		}
		return $resultSet;
	}

	public function getFilteredResultSet() {
		$queryObject = $this->createQueryObject();
		$this->filterQueryObject($queryObject);

		$resultSet = $this->getService()->fetchQueryObject($queryObject);
		$this->filterResultSet($resultSet);

		return $resultSet;
	}

}