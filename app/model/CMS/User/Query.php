<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class UserQuery extends Kdyby\Doctrine\QueryObject {

	private $filter = [];

	public function inRole(Model\CMS\Entity\Role $role = NULL) {
		if($role === NULL) {
			return $this;
		}
		$this->filter[] = function(Kdyby\Doctrine\QueryBuilder $qb) use ($role) {
			$qb->andWhere('a.role = :role')->setParameter('role', $role->id);
		};
		return $this;
	}

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		$qb = $repository->createQueryBuilder()
			->select('a')
			->from(Model\CMS\Entity\User::getClassName(), 'a');
		foreach($this->filter as $modifier) {
			$modifier($qb);
		};
		return $qb->getQuery()
			->useResultCache(TRUE);
	}

}