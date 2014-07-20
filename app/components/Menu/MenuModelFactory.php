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

	public function __construct(Zax\Components\Menu\IMenuFactory $menuFactory,
	                            ZaxCMS\Model\MenuService $menuService,
	                            Gedmo\Translatable\TranslatableListener $translatableListener) {
		$this->menuFactory = $menuFactory;
		$this->menuService = $menuService;
		$this->translatableListener = $translatableListener;
	}

	/** @return Zax\Components\Menu\MenuControl */
	public function create($menu) {
		$menu = $this->menuService->getRepository()->findOneBy(['name' => $menu]);
		//$menu->setTranslatableLocale($this->getLocale());
		$this->translatableListener->setTranslatableLocale($this->getLocale());
		$this->menuService->getEm()->refresh($menu);
		return $this->menuFactory->create()->setMenu($menu)->setRepository($this->menuService->getRepository());
	}

}
