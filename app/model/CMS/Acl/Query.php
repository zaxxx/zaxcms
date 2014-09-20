<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class AclQuery extends Zax\Model\Doctrine\QueryObject {

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('acl')
			->from(Model\CMS\Entity\Acl::getClassName(), 'acl');

		$this->applyFilters($qb);

		return $qb->getQuery()
			->useResultCache(TRUE, NULL, Model\CMS\AclFactory::CACHE_TAG);
	}

}