<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class PageQuery extends Kdyby\Doctrine\QueryObject {

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		return $repository->createQueryBuilder()
			->select('a')
			->from(Model\CMS\Entity\Page::getClassName(), 'a')
			->getQuery()
			->useResultCache(TRUE);
	}

}