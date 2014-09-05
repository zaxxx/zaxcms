<?php

namespace Zax\Components\Filter;
use Nette,
	Kdyby,
    Zax,
    ZaxCMS\Model,
    Zax\Application\UI as ZaxUI,
	Nette\Application\UI as NetteUI,
    Zax\Application\UI\Control;

abstract class FilterControl extends Control {

	/** @var Kdyby\Doctrine\ResultSet */
	protected $resultSet;

	public function setResultSet(Kdyby\Doctrine\ResultSet $resultSet) {
		$this->resultSet = $resultSet;
		return $this;
	}

	/** @return Kdyby\Doctrine\ResultSet */
	abstract protected function applyFilter();

	public function getFilteredResultSet() {
		return $this->applyFilter();
	}

}