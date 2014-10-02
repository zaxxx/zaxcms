<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class PageService extends Zax\Model\Doctrine\Service {

	use Zax\Traits\TCacheable;

	const CACHE_TAG = 'ZaxCMS.Model.Page';

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Page::getClassName();
	}

	public function getByName($name) {
		return $this->getBy(['name' => $name]);
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
	}

} 