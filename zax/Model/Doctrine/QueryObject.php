<?php

namespace Zax\Model\Doctrine;
use Zax,
	Kdyby,
	Nette;

abstract class QueryObject extends Kdyby\Doctrine\QueryObject {

	protected $filter = [];

	public function orderBy($sort, $order) {
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($sort, $order) {
			$qb->addOrderBy($sort, $order);
		};
		return $this;
	}

	protected function applyFilters(Kdyby\Doctrine\QueryBuilder $qb) {
		foreach($this->filter as $filter) {
			$filter($qb);
		};
	}

}
