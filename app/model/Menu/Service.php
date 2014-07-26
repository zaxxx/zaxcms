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

	public function createDefaultMenu() {
		$frontMenu = new Menu;
		$frontMenu->name = 'front';
		$frontMenu->text = 'front';
		$frontMenu->htmlClass = 'nav navbar-nav';
		$frontMenu->isMenuItem = FALSE;
		$frontMenu->secured = FALSE;
		$this->getEm()->persist($frontMenu);

		$homeItem = new Menu;
		$homeItem->name = 'home';
		$homeItem->text = 'Index';
		$homeItem->nhref = ':Front:Default:default';
		$homeItem->isMenuItem = TRUE;
		$homeItem->secured = FALSE;
		$this->getRepository()->persistAsLastChildOf($homeItem, $frontMenu);

		$this->getEm()->flush();
	}

	public function moveUp(Menu $entity, $number = 1) {
		return $this->getRepository()->moveUp($entity, $number);
	}

	public function moveDown(Menu $entity, $number = 1) {
		return $this->getRepository()->moveDown($entity, $number);
	}

}