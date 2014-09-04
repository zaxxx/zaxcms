<?php

namespace ZaxCMS\Model\CMS\Service;
use Zax,
	ZaxCMS,
	ZaxCMS\Model\CMS\Entity,
	Nette,
	Kdyby,
	Gedmo;


class MenuService extends Zax\Model\Service {

	const CACHE_TAG = 'ZaxCMS.Model.Menu';

	use Zax\Traits\TTranslatable,
		Zax\Traits\TCacheable;

	protected $locale;

	public function __construct(Kdyby\Doctrine\EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->entityClassName = Entity\Menu::getClassName();
	}

	/** @return MenuTreeRepository */
	public function getRepository() {
		return parent::getRepository()->setLocale($this->getLocale());
	}

	public function getByName($name, $useCache = FALSE) {
		if(!$useCache) {
			$menu = $this->getBy(['name' => $name]);
			$menu->setTranslatableLocale($this->getLocale());
			$this->refresh($menu);
			return $menu;
		}
		$menu = $this->cache->load('menu-' . $name . '-' . $this->getLocale());
		if($menu === NULL) {
			$menu = $this->getBy(['name' => $name]);
			$this->cache->save('menu-' . $name . '-' . $this->getLocale(), $menu, [Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		}
		return $menu;
	}

	public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
		return $children;
	}

	public function getCachedChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$this->getRepository()->useCache = TRUE;
		$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
		$this->getRepository()->useCache = FALSE;
		return $children;
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
		return $this;
	}

	public function moveUp(Entity\Menu $entity, $number = 1) {
		$result = $this->getRepository()->moveUp($entity, $number);
		$this->invalidateCache();
		return $result;
	}

	public function moveDown(Entity\Menu $entity, $number = 1) {
		$result = $this->getRepository()->moveDown($entity, $number);
		$this->invalidateCache();
		return $result;
	}

	public function flush() {
		parent::flush();
		$this->invalidateCache();
	}

}