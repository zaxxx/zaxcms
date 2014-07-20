<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	ZaxCMS,
	Kdyby,
	Zax;

class MenuModelFactory {

	use Zax\Traits\TCacheable,
		Zax\Traits\TTranslatable;

	protected $menuFactory;

	protected $menuService;

	protected $locale;

	public function __construct(Zax\Components\Menu\IMenuFactory $menuFactory,
	                            ZaxCMS\Model\MenuService $menuService) {
		$this->menuFactory = $menuFactory;
		$this->menuService = $menuService;
	}

	/** @return Zax\Components\Menu\MenuControl */
	public function create($menu) {
		$menu = $this->menuService->getRepository()->findOneBy(['name' => $menu]);
		$menu->setTranslatableLocale($this->getLocale());
		$this->menuService->getEm()->refresh($menu);
		return $this->menuFactory->create()->setMenu($menu)->setRepository($this->menuService->getRepository());
	}

}
