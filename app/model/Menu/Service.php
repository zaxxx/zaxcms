<?php

namespace ZaxCMS\Model;
use Zax,
	ZaxCMS,
	Nette,
	Kdyby,
	Gedmo;


class MenuService extends Service {

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
			$this->cache->save('menu-' . $name . '-' . $this->getLocale(), $menu, [Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
		}
		return $menu;
	}

	public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
//		$key = md5(serialize([$node->id, $this->getLocale(), $direct, $includeNode, $sortByField, $direction]));
//		$children = $this->cache->load('children-' . $key);
//		if($children === NULL) {
			$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
//			$this->cache->save('children-' . $key, $children, [Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
//		}
		return $children;
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
		$doctrineCache = $this->em->getConfiguration()->getResultCacheImpl();
		$doctrineCache->delete('ZaxCMS-Model-Menu');
		$doctrineCache->flushAll();
		return $this;
	}


	protected function createMenu($name) {
		$menu = new Menu;
		$menu->name = $name;
		$menu->text = $name;
		$menu->htmlClass = 'nav navbar-nav';
		$menu->secured = FALSE;
		return $menu;
	}

	protected function createMenuItem($text, $nhref) {
		$item = new Menu;
		$item->text = $text;
		$item->nhref = $nhref;
		$item->secured = FALSE;
		return $item;
	}

	public function createDefaultMenu() {
		$frontMenu = $this->createMenu('front');
		$this->getEm()->persist($frontMenu);

		$homeItem = $this->createMenuItem('Index', ':Front:Default:default');
		$this->getRepository()->persistAsLastChildOf($homeItem, $frontMenu);

		$this->getEm()->flush();
	}

	public function createAdminMenu() {
		$adminMenu = $this->createMenu('admin');
		$this->getEm()->persist($adminMenu);

		$dashboardItem = $this->createMenuItem('Dashboard', ':Admin:Default:default');
		$this->getRepository()->persistAsLastChildOf($dashboardItem, $adminMenu);

		$pagesItem = $this->createMenuItem('Pages', ':Admin:Pages:default');
		$this->getRepository()->persistAsLastChildOf($pagesItem, $adminMenu);

		$pagesItem = $this->createMenuItem('Users', ':Admin:Users:default');
		$this->getRepository()->persistAsLastChildOf($pagesItem, $adminMenu);

		$this->getEm()->flush();
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