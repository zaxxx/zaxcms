<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Gedmo,
	Doctrine;

class AuthorQuery extends Zax\Model\Doctrine\QueryObject {

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a.slug, a.name, COUNT(b) AS countArticles')
			->from(Model\CMS\Entity\Author::getClassName(), 'a')
			->leftJoin('a.articles', 'b')
			->groupBy('a.id')
			->addOrderBy('countArticles', 'DESC')
			->addOrderBy('a.id', 'DESC');

		$this->applyFilters($qb);


		$query = $qb->getQuery()
			->useResultCache(TRUE, NULL, Model\CMS\Service\ArticleService::CACHE_TAG);

		return $query;
	}

}