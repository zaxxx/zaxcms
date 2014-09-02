<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class MenuService extends Service {

	const CACHE_TAG = 'ZaxCMS.Model.Menu';

	use Zax\Traits\TTranslatable,
		Zax\Traits\TCacheable;

	protected $locale;

	public function __construct(Kdyby\Doctrine\EntityManager $em) {
		$this->em = $em;
		$this->className = Menu::getClassName();
	}

	/** @return MenuTreeRepository */
	public function getRepository() {
		return parent::getRepository()->setLocale($this->getLocale());
	}

	public function getByName($name, $useCache = FALSE) {
		if(!$useCache) {
			return $this->getBy(['name' => $name]);
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

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => self::CACHE_TAG]);
		$doctrineCache = $this->em->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete(self::CACHE_TAG);
		$doctrineCache->flushAll();
		return $this;
	}

	public function moveUp(Menu $entity, $number = 1) {
		$result = $this->getRepository()->moveUp($entity, $number);
		$this->invalidateCache();
		return $result;
	}

	public function moveDown(Menu $entity, $number = 1) {
		$result = $this->getRepository()->moveDown($entity, $number);
		$this->invalidateCache();
		return $result;
	}

	public function flush() {
		$this->getEm()->flush();
		$this->invalidateCache();
	}

}