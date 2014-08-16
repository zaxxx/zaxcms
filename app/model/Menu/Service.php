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

	/** @return Gedmo\Tree\Entity\Repository\NestedTreeRepository */
	public function getRepository() {
		return parent::getRepository()->setLocale($this->getLocale());
	}

	public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
		$children = $this->cache->load('children-' . $node->id . '-' . $this->getLocale());
		if($children === NULL) {
			$children = $this->getRepository()->getChildren($node, $direct, $sortByField, $direction, $includeNode);
			$this->cache->save('children-' . $node->id . '-' . $this->getLocale(), $children, [Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
		}
		return $children;
	}

	public function invalidateCache() {
		$this->cache->clean([Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
		return $this;
	}


	protected function createMenu($name) {
		$menu = new Menu;
		$menu->name = $name;
		$menu->text = $name;
		$menu->htmlClass = 'nav navbar-nav';
		$menu->isMenuItem = FALSE;
		$menu->secured = FALSE;
		return $menu;
	}

	protected function createMenuItem($name, $text, $nhref) {
		$item = new Menu;
		$item->name = $name;
		$item->text = $text;
		$item->nhref = $nhref;
		$item->isMenuItem = TRUE;
		$item->secured = FALSE;
		return $item;
	}

	public function createDefaultMenu() {
		$frontMenu = $this->createMenu('front');
		$this->getEm()->persist($frontMenu);

		$homeItem = $this->createMenuItem('home', 'Index', ':Front:Default:default');
		$this->getRepository()->persistAsLastChildOf($homeItem, $frontMenu);

		$this->getEm()->flush();
	}

	public function createAdminMenu() {
		$adminMenu = $this->createMenu('admin');
		$this->getEm()->persist($adminMenu);

		$dashboardItem = $this->createMenuItem('dashboard', 'Dashboard', ':Admin:Default:default');
		$this->getRepository()->persistAsLastChildOf($dashboardItem, $adminMenu);

		$pagesItem = $this->createMenuItem('pages', 'Pages', ':Admin:Pages:default');
		$this->getRepository()->persistAsLastChildOf($pagesItem, $adminMenu);

		$this->getEm()->flush();
	}

	public function moveUp(Menu $entity, $number = 1) {
		return $this->getRepository()->moveUp($entity, $number);
	}

	public function moveDown(Menu $entity, $number = 1) {
		return $this->getRepository()->moveDown($entity, $number);
	}

}