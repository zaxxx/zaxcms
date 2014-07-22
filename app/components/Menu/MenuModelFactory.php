<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	ZaxCMS,
	Kdyby,
	Gedmo,
	Zax;

class MenuModelFactory {

	use Zax\Traits\TCacheable,
		Zax\Traits\TTranslatable;

	protected $menuFactory;

	protected $menuService;

	protected $locale;

	protected $translatableListener;

	public function __construct(IMenuFactory $menuFactory,
	                            ZaxCMS\Model\MenuService $menuService,
	                            Gedmo\Translatable\TranslatableListener $translatableListener) {
		$this->menuFactory = $menuFactory;
		$this->menuService = $menuService;
		$this->translatableListener = $translatableListener;
	}

	protected function loadMenu($name) {
		$menu = $this->cache->load($name . '-' . $this->getLocale());
		if($menu === NULL) {
			$menu = $this->menuService->getRepository()->findOneBy(['name' => $name]);
			//$menu->setTranslatableLocale($this->getLocale());
			$this->translatableListener->setTranslatableLocale($this->getLocale());
			$this->menuService->getEm()->refresh($menu);
			$this->cache->save($name . '-' . $this->getLocale(), $menu, [Nette\Caching\Cache::TAGS => 'ZaxCMS-Model-Menu']);
		}
		return $menu;
	}

	/** @return MenuControl */
	public function create($menu) {
		return $this->menuFactory->create()
			->setMenu($this->loadMenu($menu));
	}

}
