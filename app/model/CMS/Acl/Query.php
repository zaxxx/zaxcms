<?php

namespace ZaxCMS\Model\CMS\Query;
use Zax,
	ZaxCMS\Model,
	Nette,
	Kdyby,
	Doctrine;

class AclQuery extends Kdyby\Doctrine\QueryObject {

	protected function doCreateQuery(Kdyby\Persistence\Queryable $repository) {
		return $repository->createQueryBuilder()
			//->select('acl, role, resource, privilege')
			->select('acl')
			->from(Model\CMS\Entity\Acl::getClassName(), 'acl')
			//->join('acl.role', 'role')
			//->join('acl.resource', 'resource')
			//->join('acl.privilege', 'privilege')
			->getQuery()
			->useResultCache(TRUE, NULL, Model\CMS\AclFactory::CACHE_TAG);
	}

}