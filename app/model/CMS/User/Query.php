<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class UserQuery extends Zax\Model\Doctrine\QueryObject {

	public function inRole(Model\CMS\Entity\Role $role = NULL) {
		if($role === NULL) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($role) {
			$qb->andWhere('a.role = :role')->setParameter('role', $role->id);
		};
		return $this;
	}

	public function search($needle) {
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($needle) {
			$qb->andWhere('a.name LIKE :search')
				->setParameter('search', "%$needle%");
		};
		return $this;
	}

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a, b')
			->from(Model\CMS\Entity\User::getClassName(), 'a')
			->join('a.role', 'b');
		$this->applyFilters($qb);
		return $qb->getQuery()
			->useResultCache(TRUE);
	}

}