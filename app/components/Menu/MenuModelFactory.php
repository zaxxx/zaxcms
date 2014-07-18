<?php

namespace ZaxCMS\Components\Menu;
use Nette,
	ZaxCMS,
	Kdyby,
	Zax;

class MenuModelFactory implements Zax\Components\Menu\IMenuFactory {

	use Zax\Traits\TCacheable;

	protected $menus;

	protected $menuListFactory;

	protected $menuService;

	protected $translator;

	protected $formFactory;

	protected $binder;

	public function __construct(Zax\Components\Menu\IMenuListFactory $menuListFactory,
	                            ZaxCMS\Model\MenuService $menuService,
								Kdyby\Translation\Translator $translator,
								Zax\Application\UI\FormFactory $formFactory,
								Zax\Forms\IBinder $binder) {
		$this->menus = [];
		$this->menuListFactory = $menuListFactory;
		$this->menuService = $menuService;
		$this->translator = $translator;
		$this->formFactory = $formFactory;
		$this->binder = $binder;
	}

	/** @return Zax\Components\Menu\MenuControl */
	public function create($menu) {
		$menu = $this->menuService->getRepository()->findOneBy(['name' => $menu]);
		$control = new Zax\Components\Menu\MenuControl($this->menuListFactory, $menu);
		$control->injectDependencies($this->translator, $this->formFactory, $this->binder);
		return $control;
	}

}
