<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class CategoryService extends Zax\Model\Doctrine\Service {

	use Zax\Traits\TCacheable;

	const CACHE_TAG = 'ZaxCMS.Model.Category';

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Category::getClassName();
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
	}

	public function findPath(Entity\Category $node) {
		$ids = explode('/', $node->path);
		$nodes = $this->findBy(['id' => $ids], ['depth' => 'ASC']);
		return $nodes;
	}

} 