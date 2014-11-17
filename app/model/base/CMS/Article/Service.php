<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class ArticleService extends Zax\Model\Doctrine\Service {

	use Zax\Traits\TCacheable;

	const CACHE_TAG = 'ZaxCMS.Model.Article';

	protected $articleConfig;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager, ZaxCMS\DI\ArticleConfig $articleConfig) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Article::getClassName();
		$this->articleConfig = $articleConfig;
	}

	public function create() {
		$article = parent::create();
		$config = $this->articleConfig->getConfig()['article']['defaults'];
		$article->isMain = $config['isMain'];
		$article->isVisibleInRootCategory = $config['isVisibleInRootCategory'];
		$article->isPublic = $config['isPublic'];
		$article->sidebarCategory = $config['sidebarCategory'];
		$article->imageConfig = $config['imageConfig'];
		$article->hideAuthors = $config['hideAuthors'];
		return $article;
	}

	public function getBySlug($slug) {
		$qb = $this->createQueryBuilder()
			->select('a, b, c')
			->from(Entity\Article::getClassName(), 'a')
			->where('a.slug = :slug')
			->leftJoin('a.tags', 'b')
			->leftJoin('a.authors', 'c')
			->setParameter('slug', $slug);
		return $qb->getQuery()
			->useQueryCache(TRUE)
			->useResultCache(TRUE, NULL, self::CACHE_TAG)
			->getSingleResult();
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
	}

}


trait TInjectArticleService {

	/** @var ArticleService */
	protected $articleService;

	public function injectArticleService(ArticleService $articleService) {
		$this->articleService = $articleService;
	}

}

