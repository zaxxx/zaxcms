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

	public function getBySlug($slug) {
		$qb = $this->createQueryBuilder()
			->select('a')
			->from(Entity\Category::getClassName(), 'a')
			->where('a.slug = :slug')
			->setParameter('slug', $slug);
		return $qb->getQuery()
			->useQueryCache(TRUE)
			->useResultCache(TRUE, NULL, self::CACHE_TAG)
			->getSingleResult();
	}

	public function findByIds($ids) {
		$qb = $this->createQueryBuilder()
			->select('a')
			->from(Entity\Category::getClassName(), 'a')
			->where('a.id IN (:ids)')
			->orderBy('a.depth', 'ASC')
			->setParameter('ids', $ids);
		return $qb->getQuery()
			->useQueryCache(TRUE)
			->useResultCache(TRUE, NULL, self::CACHE_TAG)
			->getResult();
	}

	public function findPath(Entity\Category $node) {
		return $this->findByIds(explode('/', $node->path));
	}

}


trait TInjectCategoryService {

	/** @var CategoryService */
	protected $categoryService;

	public function injectCategoryService(CategoryService $categoryService) {
		$this->categoryService = $categoryService;
	}

}

