<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class PageQuery extends Zax\Model\Doctrine\QueryObject {

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a')
			->from(Model\CMS\Entity\Page::getClassName(), 'a');

		$this->applyFilters($qb);

		return $qb->getQuery()
			->useResultCache(TRUE);
	}

}